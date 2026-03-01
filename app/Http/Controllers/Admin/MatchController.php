<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatterScore;
use App\Models\BowlerScore;
use App\Models\CricketMatch;
use App\Models\MatchInning;
use App\Models\Player;
use App\Models\PlayerMatchPerformance;
use App\Models\PlayerStat;
use App\Models\School;
use App\Models\Tournament;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Show form to create a match (tournament_id passed as query param).
     */
    public function create(Request $request)
    {
        $tournament = Tournament::findOrFail($request->query('tournament_id'));
        $schools = School::where('status', School::STATUS_APPROVED)
            ->orderBy('school_name')
            ->get();

        return view('admin.matches.create', compact('tournament', 'schools'));
    }

    /**
     * Store new match.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'home_school_id' => 'required|exists:schools,id',
            'away_school_id' => 'required|exists:schools,id|different:home_school_id',
            'match_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'overs_per_side' => 'required|integer|min:1|max:50',
        ]);

        CricketMatch::create($validated);

        return redirect()->route('admin.tournaments.show', $validated['tournament_id'])
            ->with('success', 'Match created successfully.');
    }

    /**
     * Show match details.
     */
    public function show(CricketMatch $cricketMatch)
    {
        $cricketMatch->load([
            'tournament',
            'homeSchool',
            'awaySchool',
            'tossWinner',
            'innings.battingSchool',
            'innings.bowlingSchool',
            'innings.batterScores.player',
            'innings.bowlerScores.player',
            'squads.player',
        ]);

        return view('admin.matches.show', compact('cricketMatch'));
    }

    /**
     * Show start match form (toss info).
     */
    public function startForm(CricketMatch $cricketMatch)
    {
        if (!$cricketMatch->isUpcoming()) {
            return redirect()->route('admin.matches.show', $cricketMatch)
                ->with('error', 'Match has already started or is completed.');
        }

        // Check both teams have playing XI
        $homeXI = $cricketMatch->playingXIForSchool($cricketMatch->home_school_id);
        $awayXI = $cricketMatch->playingXIForSchool($cricketMatch->away_school_id);

        if ($homeXI->count() < 11 || $awayXI->count() < 11) {
            return redirect()->route('admin.matches.show', $cricketMatch)
                ->with('error', 'Both teams must have 11 players in their Playing XI before the match can start. Home: ' . $homeXI->count() . '/11, Away: ' . $awayXI->count() . '/11');
        }

        $cricketMatch->load(['homeSchool', 'awaySchool']);

        return view('admin.matches.start', compact('cricketMatch'));
    }

    /**
     * Start the match – set toss info, create first innings.
     */
    public function start(Request $request, CricketMatch $cricketMatch)
    {
        if (!$cricketMatch->isUpcoming()) {
            return redirect()->route('admin.matches.show', $cricketMatch)
                ->with('error', 'Match has already started.');
        }

        $validated = $request->validate([
            'toss_winner_school_id' => 'required|in:' . $cricketMatch->home_school_id . ',' . $cricketMatch->away_school_id,
            'toss_decision' => 'required|in:bat,bowl',
        ]);

        // Determine batting and bowling teams
        $tossWinnerId = (int) $validated['toss_winner_school_id'];
        $tossLoserId = $cricketMatch->home_school_id === $tossWinnerId
            ? $cricketMatch->away_school_id
            : $cricketMatch->home_school_id;

        if ($validated['toss_decision'] === 'bat') {
            $battingSchoolId = $tossWinnerId;
            $bowlingSchoolId = $tossLoserId;
        } else {
            $battingSchoolId = $tossLoserId;
            $bowlingSchoolId = $tossWinnerId;
        }

        // Update match
        $cricketMatch->update([
            'status' => CricketMatch::STATUS_LIVE,
            'toss_winner_school_id' => $tossWinnerId,
            'toss_decision' => $validated['toss_decision'],
        ]);

        // Create first innings
        $inning = MatchInning::create([
            'match_id' => $cricketMatch->id,
            'batting_school_id' => $battingSchoolId,
            'bowling_school_id' => $bowlingSchoolId,
            'inning_number' => 1,
        ]);

        // Pre-create batter scores for batting team's playing XI
        $battingXI = $cricketMatch->playingXIForSchool($battingSchoolId);
        $position = 1;
        foreach ($battingXI as $squad) {
            BatterScore::create([
                'match_inning_id' => $inning->id,
                'player_id' => $squad->player_id,
                'batting_position' => $position++,
            ]);
        }

        // Pre-create bowler scores for bowling team's playing XI
        $bowlingXI = $cricketMatch->playingXIForSchool($bowlingSchoolId);
        foreach ($bowlingXI as $squad) {
            BowlerScore::create([
                'match_inning_id' => $inning->id,
                'player_id' => $squad->player_id,
            ]);
        }

        return redirect()->route('admin.matches.show', $cricketMatch)
            ->with('success', 'Match started! First innings is underway.');
    }

    /**
     * Complete the match with a result summary.
     * Auto-generates PlayerMatchPerformance records from live scoring data
     * and recalculates each player's career stats & ranking points.
     */
    public function complete(Request $request, CricketMatch $cricketMatch)
    {
        $validated = $request->validate([
            'result_summary' => 'required|string|max:500',
        ]);

        // Mark any open innings as completed
        $cricketMatch->innings()->where('is_completed', false)->update(['is_completed' => true]);

        $cricketMatch->update([
            'status' => CricketMatch::STATUS_COMPLETED,
            'result_summary' => $validated['result_summary'],
        ]);

        // Auto-generate player stats from live scoring data
        $this->generatePlayerStatsFromMatch($cricketMatch);

        return redirect()->route('admin.matches.show', $cricketMatch)
            ->with('success', 'Match completed. Player stats have been automatically updated.');
    }

    /**
     * Extract batter/bowler scores from a completed live match and create
     * PlayerMatchPerformance records, then recalculate each player's career stats.
     */
    private function generatePlayerStatsFromMatch(CricketMatch $cricketMatch): void
    {
        // Load all innings with scores
        $cricketMatch->load([
            'innings.batterScores',
            'innings.bowlerScores',
            'homeSchool',
            'awaySchool',
        ]);

        $innings = $cricketMatch->innings;
        if ($innings->isEmpty()) {
            return;
        }

        // Collect every player_id that appeared in any innings
        $playerIds = collect();
        foreach ($innings as $inning) {
            foreach ($inning->batterScores as $bs) {
                $playerIds->push($bs->player_id);
            }
            foreach ($inning->bowlerScores as $bws) {
                $playerIds->push($bws->player_id);
            }
        }
        $playerIds = $playerIds->unique();

        foreach ($playerIds as $playerId) {
            // Skip if we already generated a performance for this player+match
            if (PlayerMatchPerformance::where('player_id', $playerId)->where('match_id', $cricketMatch->id)->exists()) {
                continue;
            }

            $player = Player::find($playerId);
            if (!$player) continue;

            // Aggregate batting across all innings this player batted in
            $battingRuns = 0;
            $battingBalls = 0;
            $battingFours = 0;
            $battingSixes = 0;
            $battingNotOut = true; // assume not out unless dismissed
            $didBat = false;

            // Aggregate bowling across all innings this player bowled in
            $bowlingBalls = 0;
            $bowlingMaidens = 0;
            $bowlingRunsConceded = 0;
            $bowlingWickets = 0;
            $didBowl = false;

            // Determine opponent
            $opponentName = null;
            if ($player->school_id === $cricketMatch->home_school_id) {
                $opponentName = $cricketMatch->awaySchool->school_name ?? null;
            } elseif ($player->school_id === $cricketMatch->away_school_id) {
                $opponentName = $cricketMatch->homeSchool->school_name ?? null;
            }

            foreach ($innings as $inning) {
                // Check batting
                $batterScore = $inning->batterScores->where('player_id', $playerId)->first();
                if ($batterScore && $batterScore->status !== BatterScore::STATUS_YET_TO_BAT) {
                    $didBat = true;
                    $battingRuns += (int) $batterScore->runs;
                    $battingBalls += (int) $batterScore->balls_faced;
                    $battingFours += (int) $batterScore->fours;
                    $battingSixes += (int) $batterScore->sixes;
                    if ($batterScore->status === BatterScore::STATUS_OUT) {
                        $battingNotOut = false;
                    }
                }

                // Check bowling
                $bowlerScore = $inning->bowlerScores->where('player_id', $playerId)->first();
                if ($bowlerScore && floatval($bowlerScore->overs) > 0) {
                    $didBowl = true;
                    // Convert overs string to balls
                    $ov = floatval($bowlerScore->overs);
                    $fullOv = intval($ov);
                    $partBalls = round(($ov - $fullOv) * 10);
                    $bowlingBalls += ($fullOv * 6) + $partBalls;
                    $bowlingMaidens += (int) $bowlerScore->maidens;
                    $bowlingRunsConceded += (int) $bowlerScore->runs_conceded;
                    $bowlingWickets += (int) $bowlerScore->wickets;
                }
            }

            // Skip players who didn't bat or bowl (sat out entirely)
            if (!$didBat && !$didBowl) {
                continue;
            }

            // Convert total bowling balls back to overs decimal (e.g. 25 balls = 4.1)
            $bowlingOvers = 0;
            if ($bowlingBalls > 0) {
                $bowlingOvers = floor($bowlingBalls / 6) + (($bowlingBalls % 6) / 10);
            }

            // Build description
            $tournamentName = $cricketMatch->tournament->name ?? 'Match';
            $matchDesc = $tournamentName . ' — ' . ($cricketMatch->result_summary ?? 'Completed');

            PlayerMatchPerformance::create([
                'player_id'            => $playerId,
                'match_id'             => $cricketMatch->id,
                'match_date'           => $cricketMatch->match_date,
                'opponent'             => $opponentName,
                'match_description'    => $matchDesc,
                'batting_runs'         => $battingRuns,
                'batting_balls_faced'  => $battingBalls,
                'batting_fours'        => $battingFours,
                'batting_sixes'        => $battingSixes,
                'batting_not_out'      => $didBat ? $battingNotOut : false,
                'bowling_overs'        => $bowlingOvers,
                'bowling_maidens'      => $bowlingMaidens,
                'bowling_runs_conceded'=> $bowlingRunsConceded,
                'bowling_wickets'      => $bowlingWickets,
                'bowling_dot_balls'    => 0,
                'fielding_catches'     => 0,
                'fielding_run_outs'    => 0,
                'fielding_stumpings'   => 0,
            ]);

            // Recalculate career stats for this player
            PlayerStat::recalculateFromPerformances($player);
        }
    }
}

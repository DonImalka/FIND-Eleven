<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\BatterScore;
use App\Models\BowlerScore;
use App\Models\CricketMatch;
use App\Models\MatchInning;
use Illuminate\Http\Request;

class LiveScoringController extends Controller
{
    /**
     * Show the live scoring page.
     */
    public function show(CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->home_school_id === $school->id, 403, 'Only the home team can manage the scorecard.');

        if (!$cricketMatch->isLive()) {
            return redirect()->route('school.matches.show', $cricketMatch)
                ->with('error', 'Match is not live.');
        }

        $cricketMatch->load([
            'homeSchool', 'awaySchool', 'tournament',
            'innings.battingSchool', 'innings.bowlingSchool',
            'innings.batterScores.player',
            'innings.bowlerScores.player',
        ]);

        $currentInning = $cricketMatch->currentInnings();

        return view('school.matches.score', compact('cricketMatch', 'currentInning', 'school'));
    }

    /**
     * Update the scores for the current innings.
     */
    public function update(Request $request, CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->home_school_id === $school->id, 403, 'Only the home team can manage the scorecard.');
        abort_unless($cricketMatch->isLive(), 403);

        $currentInning = $cricketMatch->currentInnings();
        abort_unless($currentInning, 404);

        // Validate innings totals
        $request->validate([
            'total_runs' => 'required|integer|min:0',
            'total_wickets' => 'required|integer|min:0|max:10',
            'total_overs' => 'required|string|max:10',
            'extras' => 'required|integer|min:0',
        ]);

        // Update innings totals
        $currentInning->update([
            'total_runs' => $request->total_runs,
            'total_wickets' => $request->total_wickets,
            'total_overs' => $request->total_overs,
            'extras' => $request->extras,
        ]);

        // Update batter scores
        if ($request->has('batters')) {
            foreach ($request->batters as $batterId => $data) {
                BatterScore::where('id', $batterId)
                    ->where('match_inning_id', $currentInning->id)
                    ->update([
                        'runs' => $data['runs'] ?? 0,
                        'balls_faced' => $data['balls_faced'] ?? 0,
                        'fours' => $data['fours'] ?? 0,
                        'sixes' => $data['sixes'] ?? 0,
                        'status' => $data['status'] ?? 'yet_to_bat',
                        'dismissal_info' => $data['dismissal_info'] ?? null,
                    ]);
            }
        }

        // Update bowler scores
        if ($request->has('bowlers')) {
            foreach ($request->bowlers as $bowlerId => $data) {
                BowlerScore::where('id', $bowlerId)
                    ->where('match_inning_id', $currentInning->id)
                    ->update([
                        'overs' => $data['overs'] ?? '0',
                        'maidens' => $data['maidens'] ?? 0,
                        'runs_conceded' => $data['runs_conceded'] ?? 0,
                        'wickets' => $data['wickets'] ?? 0,
                    ]);
            }
        }

        return redirect()->route('school.matches.score', $cricketMatch)
            ->with('success', 'Scores updated.');
    }

    /**
     * End current innings and start the next one.
     */
    public function switchInnings(CricketMatch $cricketMatch)
    {
        $school = auth()->user()->school;
        abort_unless($cricketMatch->home_school_id === $school->id, 403, 'Only the home team can manage the scorecard.');
        abort_unless($cricketMatch->isLive(), 403);

        $currentInning = $cricketMatch->currentInnings();
        abort_unless($currentInning && $currentInning->inning_number === 1, 400);

        // Mark first innings as complete
        $currentInning->update(['is_completed' => true]);

        // Create second innings (swap batting/bowling)
        $secondInning = MatchInning::create([
            'match_id' => $cricketMatch->id,
            'batting_school_id' => $currentInning->bowling_school_id,
            'bowling_school_id' => $currentInning->batting_school_id,
            'inning_number' => 2,
        ]);

        // Pre-create batter scores for new batting team
        $battingXI = $cricketMatch->playingXIForSchool($secondInning->batting_school_id);
        $pos = 1;
        foreach ($battingXI as $squad) {
            BatterScore::create([
                'match_inning_id' => $secondInning->id,
                'player_id' => $squad->player_id,
                'batting_position' => $pos++,
            ]);
        }

        // Pre-create bowler scores for new bowling team
        $bowlingXI = $cricketMatch->playingXIForSchool($secondInning->bowling_school_id);
        foreach ($bowlingXI as $squad) {
            BowlerScore::create([
                'match_inning_id' => $secondInning->id,
                'player_id' => $squad->player_id,
            ]);
        }

        return redirect()->route('school.matches.score', $cricketMatch)
            ->with('success', 'Innings switched! Second innings has started.');
    }
}

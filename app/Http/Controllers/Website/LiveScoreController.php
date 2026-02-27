<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\CricketMatch;

class LiveScoreController extends Controller
{
    /**
     * Show all live and recent matches.
     */
    public function index()
    {
        $liveMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool', 'innings.battingSchool'])
            ->where('status', CricketMatch::STATUS_LIVE)
            ->latest('match_date')
            ->get();

        $recentMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool', 'innings.battingSchool'])
            ->where('status', CricketMatch::STATUS_COMPLETED)
            ->latest('match_date')
            ->take(10)
            ->get();

        $upcomingMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool'])
            ->where('status', CricketMatch::STATUS_UPCOMING)
            ->where('match_date', '>=', now()->toDateString())
            ->orderBy('match_date')
            ->take(10)
            ->get();

        return view('website.live-scores.index', compact('liveMatches', 'recentMatches', 'upcomingMatches'));
    }

    /**
     * Show full scorecard for a match.
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
        ]);

        return view('website.live-scores.show', compact('cricketMatch'));
    }

    /**
     * JSON endpoint for live polling.
     */
    public function data(CricketMatch $cricketMatch)
    {
        $cricketMatch->load([
            'homeSchool:id,school_name',
            'awaySchool:id,school_name',
            'innings.battingSchool:id,school_name',
            'innings.batterScores.player:id,full_name',
            'innings.bowlerScores.player:id,full_name',
        ]);

        return response()->json([
            'status' => $cricketMatch->status,
            'result_summary' => $cricketMatch->result_summary,
            'score_summary' => $cricketMatch->getScoreSummary(),
            'innings' => $cricketMatch->innings->map(function ($inning) {
                return [
                    'inning_number' => $inning->inning_number,
                    'batting_team' => $inning->battingSchool->school_name,
                    'total_runs' => $inning->total_runs,
                    'total_wickets' => $inning->total_wickets,
                    'total_overs' => $inning->total_overs,
                    'extras' => $inning->extras,
                    'is_completed' => $inning->is_completed,
                    'batters' => $inning->batterScores->map(fn($b) => [
                        'name' => $b->player->full_name,
                        'runs' => $b->runs,
                        'balls' => $b->balls_faced,
                        'fours' => $b->fours,
                        'sixes' => $b->sixes,
                        'status' => $b->status,
                        'dismissal' => $b->dismissal_info,
                        'sr' => $b->getStrikeRate(),
                    ]),
                    'bowlers' => $inning->bowlerScores->filter(fn($b) => $b->overs !== '0' || $b->wickets > 0)->values()->map(fn($b) => [
                        'name' => $b->player->full_name,
                        'overs' => $b->overs,
                        'maidens' => $b->maidens,
                        'runs' => $b->runs_conceded,
                        'wickets' => $b->wickets,
                        'econ' => $b->getEconomyRate(),
                    ]),
                ];
            }),
        ]);
    }
}

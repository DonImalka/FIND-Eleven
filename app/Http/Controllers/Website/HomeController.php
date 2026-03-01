<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Player;
use App\Models\PlayerStat;
use App\Models\CricketMatch;
use Illuminate\Http\Request;

/**
 * Home Controller
 * Handles the public home page
 */
class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get some statistics for the home page
        $totalSchools = School::where('status', School::STATUS_APPROVED)->count();
        $totalPlayers = Player::count();
        $totalCategories = count(PlayerStat::RANKING_CATEGORIES);

        // Top 5 All-Island rankings per category (across all age groups)
        $topRankings = [];
        foreach (PlayerStat::RANKING_CATEGORIES as $label => $playerCategories) {
            $topRankings[$label] = Player::with(['school', 'stats'])
                ->whereHas('stats', fn($q) => $q->where('ranking_points', '>', 0))
                ->whereIn('player_category', $playerCategories)
                ->get()
                ->sortByDesc(fn($p) => $p->stats->ranking_points ?? 0)
                ->take(5)
                ->values();
        }

        // Featured player: #1 ranked batsman
        $featuredPlayer = null;
        if (isset($topRankings['Batsman']) && $topRankings['Batsman']->isNotEmpty()) {
            $featuredPlayer = $topRankings['Batsman']->first();
        }

        // Live matches
        $liveMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool', 'innings.battingSchool', 'innings.batterScores.player', 'innings.bowlerScores.player'])
            ->where('status', CricketMatch::STATUS_LIVE)
            ->latest('match_date')
            ->get();

        // Recent completed matches
        $recentMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool', 'innings.battingSchool'])
            ->where('status', CricketMatch::STATUS_COMPLETED)
            ->latest('match_date')
            ->take(6)
            ->get();

        // Upcoming matches
        $upcomingMatches = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool'])
            ->where('status', CricketMatch::STATUS_UPCOMING)
            ->where('match_date', '>=', now()->toDateString())
            ->orderBy('match_date')
            ->take(6)
            ->get();

        // Upcoming matches within this week (Mon–Sun)
        $weekStart = now()->startOfWeek();
        $weekEnd   = now()->endOfWeek();
        $weeklyUpcoming = CricketMatch::with(['tournament', 'homeSchool', 'awaySchool'])
            ->where('status', CricketMatch::STATUS_UPCOMING)
            ->whereBetween('match_date', [$weekStart, $weekEnd])
            ->orderBy('match_date')
            ->get();

        // Ticker matches (live ones for the scrolling ticker)
        $tickerMatches = $liveMatches;

        return view('website.home.index', compact(
            'totalSchools', 'totalPlayers', 'totalCategories',
            'topRankings', 'featuredPlayer',
            'liveMatches', 'recentMatches', 'upcomingMatches',
            'weeklyUpcoming', 'tickerMatches'
        ));
    }
}

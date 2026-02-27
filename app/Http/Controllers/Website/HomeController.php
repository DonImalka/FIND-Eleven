<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Player;
use App\Models\PlayerStat;
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

        return view('website.home.index', compact('totalSchools', 'totalPlayers', 'topRankings'));
    }
}

<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerStat;
use App\Models\School;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Public player rankings page.
     */
    public function index(Request $request)
    {
        $ageCategory = $request->get('age', 'U15');
        $rankingCategory = $request->get('category', 'Batsman');
        $scope = $request->get('scope', 'all_island');
        $scopeValue = $request->get('scope_value', '');

        // Map ranking category to player categories
        $playerCategories = PlayerStat::RANKING_CATEGORIES[$rankingCategory] ?? [];

        // Build query
        $query = Player::with(['school', 'stats'])
            ->whereHas('stats', fn($q) => $q->where('ranking_points', '>', 0))
            ->where('age_category', $ageCategory)
            ->whereIn('player_category', $playerCategories);

        // Apply scope filters
        if ($scope === 'province' && $scopeValue) {
            $query->whereHas('school', fn($q) => $q->where('province', $scopeValue));
        } elseif ($scope === 'district' && $scopeValue) {
            $query->whereHas('school', fn($q) => $q->where('district', $scopeValue));
        }

        $players = $query->get()
            ->sortByDesc(fn($p) => $p->stats->ranking_points ?? 0)
            ->values();

        // Get filter options
        $provinces = School::where('status', School::STATUS_APPROVED)
            ->distinct()->pluck('province')->sort()->values();
        $districts = School::where('status', School::STATUS_APPROVED)
            ->distinct()->pluck('district')->sort()->values();

        $rankingCategories = array_keys(PlayerStat::RANKING_CATEGORIES);

        return view('rankings.index', compact(
            'players', 'ageCategory', 'rankingCategory', 'scope', 'scopeValue',
            'provinces', 'districts', 'rankingCategories'
        ));
    }
}

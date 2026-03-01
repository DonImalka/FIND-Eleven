<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerStat;
use Illuminate\Http\Request;

class PerfectElevenController extends Controller
{
    /**
     * The Perfect XI composition:
     * 4 Top Order Batters, 1 Power Hitter, 1 Spin All-Rounder,
     * 1 Fast Bowling All-Rounder, 2 Spinners, 2 Fast Bowlers = 11
     */
    private const SLOTS = [
        [
            'label'      => 'Top Order Batsmen',
            'icon'       => '🏏',
            'count'      => 4,
            'categories' => [Player::CATEGORY_TOP_ORDER_BATTER],
        ],
        [
            'label'      => 'Power Hitter',
            'icon'       => '⚡',
            'count'      => 1,
            'categories' => [Player::CATEGORY_POWER_HITTER],
        ],
        [
            'label'      => 'Spin All-Rounder',
            'icon'       => '🔄',
            'count'      => 1,
            'categories' => [Player::CATEGORY_SPIN_ALLROUNDER],
        ],
        [
            'label'      => 'Fast Bowling All-Rounder',
            'icon'       => '💥',
            'count'      => 1,
            'categories' => [Player::CATEGORY_FAST_BOWLING_ALLROUNDER],
        ],
        [
            'label'      => 'Spinners',
            'icon'       => '🌀',
            'count'      => 2,
            'categories' => [Player::CATEGORY_FINGER_SPIN_BOWLER, Player::CATEGORY_WRIST_SPIN_BOWLER],
        ],
        [
            'label'      => 'Fast Bowlers',
            'icon'       => '🚀',
            'count'      => 2,
            'categories' => [Player::CATEGORY_FAST_BOWLER, Player::CATEGORY_MEDIUM_BOWLER],
        ],
    ];

    /**
     * Build the Perfect XI for a given age category.
     */
    public static function buildPerfectEleven(string $ageCategory): array
    {
        $xi = [];

        foreach (self::SLOTS as $slot) {
            $players = Player::with(['school', 'stats'])
                ->where('age_category', $ageCategory)
                ->whereIn('player_category', $slot['categories'])
                ->whereHas('stats', fn ($q) => $q->where('ranking_points', '>', 0))
                ->get()
                ->sortByDesc(fn ($p) => $p->stats->ranking_points ?? 0)
                ->take($slot['count'])
                ->values();

            $xi[] = [
                'label'      => $slot['label'],
                'icon'       => $slot['icon'],
                'count'      => $slot['count'],
                'players'    => $players,
            ];
        }

        return $xi;
    }

    /**
     * Dedicated Perfect 11 page (shows all three age categories).
     */
    public function index(Request $request)
    {
        $ageGroups = [Player::AGE_U15, Player::AGE_U17, Player::AGE_U19];
        $activeAge = $request->query('age', Player::AGE_U19);

        if (! in_array($activeAge, $ageGroups)) {
            $activeAge = Player::AGE_U19;
        }

        $perfectXI = [];
        foreach ($ageGroups as $age) {
            $perfectXI[$age] = self::buildPerfectEleven($age);
        }

        return view('website.perfect-eleven.index', compact('perfectXI', 'ageGroups', 'activeAge'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerStat extends Model
{
    protected $fillable = [
        'player_id',
        'initial_stats',
        // Batting
        'batting_matches', 'batting_innings', 'batting_runs', 'batting_balls_faced',
        'batting_not_outs', 'batting_highest_score', 'batting_fifties', 'batting_hundreds',
        'batting_fours', 'batting_sixes', 'batting_average', 'batting_strike_rate',
        // Bowling
        'bowling_matches', 'bowling_innings', 'bowling_overs', 'bowling_maidens',
        'bowling_runs_conceded', 'bowling_wickets', 'bowling_best_wickets', 'bowling_best_runs',
        'bowling_five_wickets', 'bowling_average', 'bowling_economy', 'bowling_strike_rate',
        'bowling_dot_balls',
        // Fielding
        'fielding_catches', 'fielding_run_outs', 'fielding_stumpings',
        // Ranking
        'ranking_points',
    ];

    protected $casts = [
        'initial_stats' => 'array',
        'batting_average' => 'decimal:2',
        'batting_strike_rate' => 'decimal:2',
        'bowling_average' => 'decimal:2',
        'bowling_economy' => 'decimal:2',
        'bowling_strike_rate' => 'decimal:2',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function hasInitialStats(): bool
    {
        return !empty($this->initial_stats);
    }

    public function getInitial(string $key, $default = 0)
    {
        return $this->initial_stats[$key] ?? $default;
    }

    // ═══════════════════════════════════════════════════════════════
    //  RANKING POINTS CALCULATION
    // ═══════════════════════════════════════════════════════════════

    /**
     * Ranking category mapping: ranking label → player category constants.
     */
    public const RANKING_CATEGORIES = [
        'Batsman' => [Player::CATEGORY_TOP_ORDER_BATTER],
        'Power Hitter' => [Player::CATEGORY_POWER_HITTER],
        'Spinner' => [Player::CATEGORY_FINGER_SPIN_BOWLER, Player::CATEGORY_WRIST_SPIN_BOWLER],
        'Fast Bowler' => [Player::CATEGORY_FAST_BOWLER, Player::CATEGORY_MEDIUM_BOWLER],
        'Spin All-Rounder' => [Player::CATEGORY_SPIN_ALLROUNDER],
        'Fast Bowling All-Rounder' => [Player::CATEGORY_FAST_BOWLING_ALLROUNDER],
    ];

    /**
     * Calculate ranking points for a player based on their career stats and match performances.
     *
     * BATTING:  1 run=1pt, 50 runs=10 bonus, 100 runs=25 bonus, 200 runs=50 bonus,
     *           1 four=2pt, 1 six=3pt, SR 100+=10 bonus
     * BOWLING:  1 wicket=20pt, 3 wickets=10 bonus, 5 wickets=25 bonus,
     *           maiden=10 bonus, economy<5=10 bonus, dot ball=1pt
     * FIELDING: catch=1pt
     * ALL-ROUNDERS: batting + bowling + fielding combined
     */
    public static function calculateRankingPoints(Player $player): int
    {
        $stats = $player->stats;
        if (!$stats) return 0;

        $category = $player->player_category;
        $points = 0;

        // Determine which sections this category earns points from
        $earnsBatting = in_array($category, [
            Player::CATEGORY_TOP_ORDER_BATTER,
            Player::CATEGORY_POWER_HITTER,
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER,
            Player::CATEGORY_SPIN_ALLROUNDER,
        ]);

        $earnsBowling = in_array($category, [
            Player::CATEGORY_FAST_BOWLER,
            Player::CATEGORY_MEDIUM_BOWLER,
            Player::CATEGORY_FINGER_SPIN_BOWLER,
            Player::CATEGORY_WRIST_SPIN_BOWLER,
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER,
            Player::CATEGORY_SPIN_ALLROUNDER,
        ]);

        // ── BATTING POINTS ──
        if ($earnsBatting) {
            // 1 run = 1 point
            $points += (int) $stats->batting_runs;
            // 50 runs milestone (each 50) = 10 bonus
            $points += (int) $stats->batting_fifties * 10;
            // 100 runs milestone = 25 bonus
            $points += (int) $stats->batting_hundreds * 25;
            // 200 runs milestone = 50 bonus (count from match performances)
            $twoHundreds = $player->matchPerformances()->where('batting_runs', '>=', 200)->count();
            $points += $twoHundreds * 50;
            // 1 four = 2 points
            $points += (int) $stats->batting_fours * 2;
            // 1 six = 3 points
            $points += (int) $stats->batting_sixes * 3;
            // Strike rate 100+ = 10 bonus
            if ($stats->batting_strike_rate >= 100) {
                $points += 10;
            }
        }

        // ── BOWLING POINTS ──
        if ($earnsBowling) {
            // 1 wicket = 20 points
            $points += (int) $stats->bowling_wickets * 20;
            // 3 wickets in a match = 10 bonus (count from performances)
            $threeWicketHauls = $player->matchPerformances()
                ->where('bowling_wickets', '>=', 3)
                ->where('bowling_wickets', '<', 5)
                ->count();
            $points += $threeWicketHauls * 10;
            // 5 wickets = 25 bonus
            $points += (int) $stats->bowling_five_wickets * 25;
            // Maiden over = 10 bonus
            $points += (int) $stats->bowling_maidens * 10;
            // Economy under 5 = 10 bonus
            if ($stats->bowling_economy > 0 && $stats->bowling_economy < 5) {
                $points += 10;
            }
            // Dot ball = 1 point
            $points += (int) $stats->bowling_dot_balls;
        }

        // ── FIELDING POINTS (all players) ──
        $points += (int) $stats->fielding_catches;

        return $points;
    }

    // ═══════════════════════════════════════════════════════════════
    //  RECALCULATION: initial stats + match performances → career
    // ═══════════════════════════════════════════════════════════════

    public static function recalculateFromPerformances(Player $player): self
    {
        $stats = $player->stats ?? static::create(['player_id' => $player->id]);
        $performances = $player->matchPerformances()->get();
        $initial = $stats->initial_stats ?? [];

        // ─── Base values from initial stats ───
        $baseBatMatches = (int) ($initial['batting_matches'] ?? 0);
        $baseBatInnings = (int) ($initial['batting_innings'] ?? 0);
        $baseBatRuns = (int) ($initial['batting_runs'] ?? 0);
        $baseBatBalls = (int) ($initial['batting_balls_faced'] ?? 0);
        $baseBatNO = (int) ($initial['batting_not_outs'] ?? 0);
        $baseBatHS = (int) ($initial['batting_highest_score'] ?? 0);
        $baseBat50 = (int) ($initial['batting_fifties'] ?? 0);
        $baseBat100 = (int) ($initial['batting_hundreds'] ?? 0);
        $baseBat4s = (int) ($initial['batting_fours'] ?? 0);
        $baseBat6s = (int) ($initial['batting_sixes'] ?? 0);

        $baseBowlMatches = (int) ($initial['bowling_matches'] ?? 0);
        $baseBowlInnings = (int) ($initial['bowling_innings'] ?? 0);
        $baseBowlOvers = (float) ($initial['bowling_overs'] ?? 0);
        $baseBowlMaidens = (int) ($initial['bowling_maidens'] ?? 0);
        $baseBowlRC = (int) ($initial['bowling_runs_conceded'] ?? 0);
        $baseBowlWkts = (int) ($initial['bowling_wickets'] ?? 0);
        $baseBowlBestW = (int) ($initial['bowling_best_wickets'] ?? 0);
        $baseBowlBestR = (int) ($initial['bowling_best_runs'] ?? 0);
        $baseBowl5w = (int) ($initial['bowling_five_wickets'] ?? 0);

        $baseCatches = (int) ($initial['fielding_catches'] ?? 0);
        $baseRunOuts = (int) ($initial['fielding_run_outs'] ?? 0);
        $baseStumpings = (int) ($initial['fielding_stumpings'] ?? 0);

        $baseBowlOversInt = floor($baseBowlOvers);
        $baseBowlBallsPart = round(($baseBowlOvers - $baseBowlOversInt) * 10);
        $baseBowlBalls = ($baseBowlOversInt * 6) + $baseBowlBallsPart;

        // ─── Aggregate from match performances ───
        $perfCount = $performances->count();
        $battedPerfs = $performances->filter(fn($p) => $p->didBat());
        $perfBatInnings = $battedPerfs->count();
        $perfBatRuns = $battedPerfs->sum('batting_runs');
        $perfBatBalls = $battedPerfs->sum('batting_balls_faced');
        $perfBatNO = $battedPerfs->where('batting_not_out', true)->count();
        $perfBatHS = $battedPerfs->max('batting_runs') ?? 0;
        $perfBat50 = $battedPerfs->filter(fn($p) => $p->batting_runs >= 50 && $p->batting_runs < 100)->count();
        $perfBat100 = $battedPerfs->filter(fn($p) => $p->batting_runs >= 100)->count();
        $perfBat4s = $battedPerfs->sum('batting_fours');
        $perfBat6s = $battedPerfs->sum('batting_sixes');

        $bowledPerfs = $performances->filter(fn($p) => $p->didBowl());
        $perfBowlInnings = $bowledPerfs->count();
        $perfBowlMaidens = $bowledPerfs->sum('bowling_maidens');
        $perfBowlRC = $bowledPerfs->sum('bowling_runs_conceded');
        $perfBowlWkts = $bowledPerfs->sum('bowling_wickets');
        $perfBowl5w = $bowledPerfs->filter(fn($p) => $p->bowling_wickets >= 5)->count();
        $perfDotBalls = $performances->sum('bowling_dot_balls');

        $bestPerf = $bowledPerfs->sortByDesc('bowling_wickets')->sortBy('bowling_runs_conceded')->first();
        $perfBowlBestW = $bestPerf ? $bestPerf->bowling_wickets : 0;
        $perfBowlBestR = $bestPerf ? $bestPerf->bowling_runs_conceded : 0;

        $perfBowlBalls = 0;
        foreach ($bowledPerfs as $bp) {
            $oi = floor($bp->bowling_overs);
            $bPart = round(($bp->bowling_overs - $oi) * 10);
            $perfBowlBalls += ($oi * 6) + $bPart;
        }

        $perfCatches = $performances->sum('fielding_catches');
        $perfRunOuts = $performances->sum('fielding_run_outs');
        $perfStumpings = $performances->sum('fielding_stumpings');

        // ─── Combine: initial + performances ───
        $totalBatMatches = $baseBatMatches + $perfCount;
        $totalBatInnings = $baseBatInnings + $perfBatInnings;
        $totalBatRuns = $baseBatRuns + $perfBatRuns;
        $totalBatBalls = $baseBatBalls + $perfBatBalls;
        $totalBatNO = $baseBatNO + $perfBatNO;
        $totalBatHS = max($baseBatHS, $perfBatHS);
        $totalBat50 = $baseBat50 + $perfBat50;
        $totalBat100 = $baseBat100 + $perfBat100;
        $totalBat4s = $baseBat4s + $perfBat4s;
        $totalBat6s = $baseBat6s + $perfBat6s;

        $totalDismissals = $totalBatInnings - $totalBatNO;
        $totalBatAvg = $totalDismissals > 0 ? round($totalBatRuns / $totalDismissals, 2) : 0;
        $totalBatSR = $totalBatBalls > 0 ? round(($totalBatRuns / $totalBatBalls) * 100, 2) : 0;

        $totalBowlMatches = $baseBowlMatches + $perfCount;
        $totalBowlInnings = $baseBowlInnings + $perfBowlInnings;
        $totalBowlBalls = $baseBowlBalls + $perfBowlBalls;
        $totalBowlOversDecimal = $totalBowlBalls > 0
            ? floor($totalBowlBalls / 6) + (($totalBowlBalls % 6) / 10) : 0;
        $totalBowlMaidens = $baseBowlMaidens + $perfBowlMaidens;
        $totalBowlRC = $baseBowlRC + $perfBowlRC;
        $totalBowlWkts = $baseBowlWkts + $perfBowlWkts;
        $totalBowl5w = $baseBowl5w + $perfBowl5w;
        $totalDotBalls = $perfDotBalls; // only from tracked matches

        if ($perfBowlBestW > $baseBowlBestW || ($perfBowlBestW == $baseBowlBestW && $perfBowlBestR < $baseBowlBestR)) {
            $totalBowlBestW = $perfBowlBestW;
            $totalBowlBestR = $perfBowlBestR;
        } else {
            $totalBowlBestW = $baseBowlBestW;
            $totalBowlBestR = $baseBowlBestR;
        }

        $totalBowlAvg = $totalBowlWkts > 0 ? round($totalBowlRC / $totalBowlWkts, 2) : 0;
        $totalBowlEcon = $totalBowlBalls > 0 ? round(($totalBowlRC / $totalBowlBalls) * 6, 2) : 0;
        $totalBowlSR = $totalBowlWkts > 0 ? round($totalBowlBalls / $totalBowlWkts, 2) : 0;

        $totalCatches = $baseCatches + $perfCatches;
        $totalRunOuts = $baseRunOuts + $perfRunOuts;
        $totalStumpings = $baseStumpings + $perfStumpings;

        $stats->update([
            'batting_matches' => $totalBatMatches,
            'batting_innings' => $totalBatInnings,
            'batting_runs' => $totalBatRuns,
            'batting_balls_faced' => $totalBatBalls,
            'batting_not_outs' => $totalBatNO,
            'batting_highest_score' => $totalBatHS,
            'batting_fifties' => $totalBat50,
            'batting_hundreds' => $totalBat100,
            'batting_fours' => $totalBat4s,
            'batting_sixes' => $totalBat6s,
            'batting_average' => $totalBatAvg,
            'batting_strike_rate' => $totalBatSR,

            'bowling_matches' => $totalBowlMatches,
            'bowling_innings' => $totalBowlInnings,
            'bowling_overs' => $totalBowlOversDecimal,
            'bowling_maidens' => $totalBowlMaidens,
            'bowling_runs_conceded' => $totalBowlRC,
            'bowling_wickets' => $totalBowlWkts,
            'bowling_best_wickets' => $totalBowlBestW,
            'bowling_best_runs' => $totalBowlBestR,
            'bowling_five_wickets' => $totalBowl5w,
            'bowling_average' => $totalBowlAvg,
            'bowling_economy' => $totalBowlEcon,
            'bowling_strike_rate' => $totalBowlSR,
            'bowling_dot_balls' => $totalDotBalls,

            'fielding_catches' => $totalCatches,
            'fielding_run_outs' => $totalRunOuts,
            'fielding_stumpings' => $totalStumpings,
        ]);

        // Refresh and calculate ranking points
        $stats = $stats->fresh();
        $player->setRelation('stats', $stats);
        $rankingPoints = static::calculateRankingPoints($player);
        $stats->update(['ranking_points' => $rankingPoints]);

        return $stats->fresh();
    }

    // ═══════════════════════════════════════════════════════════════
    //  FIELD DEFINITIONS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Fields for initial stats entry form (no averages — those are auto-calculated).
     */
    public static function getInitialStatFields(string $category): array
    {
        $batting = [
            'batting_matches' => 'Matches Played',
            'batting_innings' => 'Innings Batted',
            'batting_runs' => 'Total Runs',
            'batting_balls_faced' => 'Balls Faced',
            'batting_not_outs' => 'Not Outs',
            'batting_highest_score' => 'Highest Score',
            'batting_fifties' => '50s',
            'batting_hundreds' => '100s',
            'batting_fours' => 'Fours (4s)',
            'batting_sixes' => 'Sixes (6s)',
        ];

        $bowling = [
            'bowling_matches' => 'Matches Played',
            'bowling_innings' => 'Innings Bowled',
            'bowling_overs' => 'Total Overs',
            'bowling_maidens' => 'Maidens',
            'bowling_runs_conceded' => 'Runs Conceded',
            'bowling_wickets' => 'Wickets',
            'bowling_best_wickets' => 'Best Figures (Wickets)',
            'bowling_best_runs' => 'Best Figures (Runs)',
            'bowling_five_wickets' => '5-Wicket Hauls',
        ];

        $fielding = [
            'fielding_catches' => 'Catches',
            'fielding_run_outs' => 'Run Outs',
            'fielding_stumpings' => 'Stumpings',
        ];

        return self::buildSections($category, $batting, $bowling, $fielding);
    }

    /**
     * Fields for displaying career stats.
     */
    public static function getFieldsForCategory(string $category): array
    {
        $batting = [
            'batting_matches' => 'Matches',
            'batting_innings' => 'Innings',
            'batting_runs' => 'Runs',
            'batting_balls_faced' => 'Balls Faced',
            'batting_not_outs' => 'Not Outs',
            'batting_highest_score' => 'Highest Score',
            'batting_fifties' => '50s',
            'batting_hundreds' => '100s',
            'batting_fours' => 'Fours (4s)',
            'batting_sixes' => 'Sixes (6s)',
            'batting_average' => 'Average',
            'batting_strike_rate' => 'Strike Rate',
        ];

        $bowling = [
            'bowling_matches' => 'Matches',
            'bowling_innings' => 'Innings',
            'bowling_overs' => 'Overs',
            'bowling_maidens' => 'Maidens',
            'bowling_runs_conceded' => 'Runs Conceded',
            'bowling_wickets' => 'Wickets',
            'bowling_best_wickets' => 'Best (Wkts)',
            'bowling_best_runs' => 'Best (Runs)',
            'bowling_five_wickets' => '5-Wicket Hauls',
            'bowling_average' => 'Average',
            'bowling_economy' => 'Economy',
            'bowling_strike_rate' => 'Strike Rate',
            'bowling_dot_balls' => 'Dot Balls',
        ];

        $fielding = [
            'fielding_catches' => 'Catches',
            'fielding_run_outs' => 'Run Outs',
            'fielding_stumpings' => 'Stumpings',
        ];

        return self::buildSections($category, $batting, $bowling, $fielding);
    }

    /**
     * Build sections array based on player category.
     */
    private static function buildSections(string $category, array $batting, array $bowling, array $fielding): array
    {
        $sections = [];

        $battingCategories = [Player::CATEGORY_TOP_ORDER_BATTER, Player::CATEGORY_POWER_HITTER];
        $bowlingCategories = [Player::CATEGORY_FAST_BOWLER, Player::CATEGORY_MEDIUM_BOWLER, Player::CATEGORY_FINGER_SPIN_BOWLER, Player::CATEGORY_WRIST_SPIN_BOWLER];
        $allRounderCategories = [Player::CATEGORY_FAST_BOWLING_ALLROUNDER, Player::CATEGORY_SPIN_ALLROUNDER];

        if (in_array($category, $battingCategories)) {
            $sections['Batting Stats'] = $batting;
            $sections['Fielding Stats'] = $fielding;
        } elseif (in_array($category, $bowlingCategories)) {
            $sections['Bowling Stats'] = $bowling;
            $sections['Fielding Stats'] = $fielding;
        } elseif (in_array($category, $allRounderCategories)) {
            $sections['Batting Stats'] = $batting;
            $sections['Bowling Stats'] = $bowling;
            $sections['Fielding Stats'] = $fielding;
        } else {
            $sections['Batting Stats'] = $batting;
            $sections['Bowling Stats'] = $bowling;
            $sections['Fielding Stats'] = $fielding;
        }

        return $sections;
    }
}

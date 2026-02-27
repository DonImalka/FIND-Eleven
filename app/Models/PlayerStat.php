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
        // Fielding
        'fielding_catches', 'fielding_run_outs', 'fielding_stumpings',
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

    /**
     * Check if initial/existing stats have been entered.
     */
    public function hasInitialStats(): bool
    {
        return !empty($this->initial_stats);
    }

    /**
     * Get an initial stat value by key, defaulting to 0.
     */
    public function getInitial(string $key, $default = 0)
    {
        return $this->initial_stats[$key] ?? $default;
    }

    /**
     * Get the list of fields that can be set as initial stats.
     * These are the "countable" career totals (no averages — those get recalculated).
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

        $sections = [];

        $battingCategories = [
            Player::CATEGORY_TOP_ORDER_BATTER,
            Player::CATEGORY_POWER_HITTER,
        ];
        $bowlingCategories = [
            Player::CATEGORY_FAST_BOWLER,
            Player::CATEGORY_MEDIUM_BOWLER,
            Player::CATEGORY_FINGER_SPIN_BOWLER,
            Player::CATEGORY_WRIST_SPIN_BOWLER,
        ];
        $allRounderCategories = [
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER,
            Player::CATEGORY_SPIN_ALLROUNDER,
        ];

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

    /**
     * Recalculate career stats = initial (base) stats + aggregated match performances.
     */
    public static function recalculateFromPerformances(Player $player): self
    {
        $stats = $player->stats ?? static::create(['player_id' => $player->id]);
        $performances = $player->matchPerformances()->get();
        $initial = $stats->initial_stats ?? [];

        // ===== Base values from initial stats =====
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

        // Convert base bowling overs to balls
        $baseBowlOversInt = floor($baseBowlOvers);
        $baseBowlBallsPart = round(($baseBowlOvers - $baseBowlOversInt) * 10);
        $baseBowlBalls = ($baseBowlOversInt * 6) + $baseBowlBallsPart;

        // ===== Aggregate from match performances =====
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

        // ===== Combine: initial + performances =====
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
            ? floor($totalBowlBalls / 6) + (($totalBowlBalls % 6) / 10)
            : 0;
        $totalBowlMaidens = $baseBowlMaidens + $perfBowlMaidens;
        $totalBowlRC = $baseBowlRC + $perfBowlRC;
        $totalBowlWkts = $baseBowlWkts + $perfBowlWkts;
        $totalBowl5w = $baseBowl5w + $perfBowl5w;

        // Best bowling: compare initial best vs performance best
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

        // Update the aggregate row
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

            'fielding_catches' => $totalCatches,
            'fielding_run_outs' => $totalRunOuts,
            'fielding_stumpings' => $totalStumpings,
        ]);

        return $stats->fresh();
    }

    /**
     * Get the display fields relevant to the player's category (for showing career stats).
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
            'bowling_best_wickets' => 'Best Figures (Wickets)',
            'bowling_best_runs' => 'Best Figures (Runs)',
            'bowling_five_wickets' => '5-Wicket Hauls',
            'bowling_average' => 'Average',
            'bowling_economy' => 'Economy',
            'bowling_strike_rate' => 'Strike Rate',
        ];

        $fielding = [
            'fielding_catches' => 'Catches',
            'fielding_run_outs' => 'Run Outs',
            'fielding_stumpings' => 'Stumpings',
        ];

        $sections = [];

        $battingCategories = [
            Player::CATEGORY_TOP_ORDER_BATTER,
            Player::CATEGORY_POWER_HITTER,
        ];
        $bowlingCategories = [
            Player::CATEGORY_FAST_BOWLER,
            Player::CATEGORY_MEDIUM_BOWLER,
            Player::CATEGORY_FINGER_SPIN_BOWLER,
            Player::CATEGORY_WRIST_SPIN_BOWLER,
        ];
        $allRounderCategories = [
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER,
            Player::CATEGORY_SPIN_ALLROUNDER,
        ];

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

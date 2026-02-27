<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\PlayerMatchPerformance;
use App\Models\PlayerStat;
use Illuminate\Database\Seeder;

class DemoStatSeeder extends Seeder
{
    /**
     * Seed demo initial stats and match performances for all existing players.
     */
    public function run(): void
    {
        $players = Player::with('school')->get();

        if ($players->isEmpty()) {
            $this->command->warn('No players found — skipping demo stats.');
            return;
        }

        foreach ($players as $player) {
            $this->seedPlayer($player);
        }

        $this->command->info("Demo stats seeded for {$players->count()} players.");
    }

    // ──────────────────────────────────────────────────────
    //  SEED ONE PLAYER
    // ──────────────────────────────────────────────────────

    private function seedPlayer(Player $player): void
    {
        $category = $player->player_category;

        // 1. Create PlayerStat record with initial (pre-system) career stats
        $initialStats = $this->generateInitialStats($category);

        $stat = PlayerStat::updateOrCreate(
            ['player_id' => $player->id],
            ['initial_stats' => $initialStats]
        );

        // 2. Create 4-6 match performances
        $matchCount = rand(4, 6);
        $opponents = ['St. Peter\'s College', 'St. Joseph\'s College', 'Ananda College', 'Nalanda College',
                       'D.S. Senanayake College', 'Thurstan College', 'Isipathana College', 'Dharmaraja College'];

        for ($i = 0; $i < $matchCount; $i++) {
            $this->createMatchPerformance($player, $category, $opponents[array_rand($opponents)], $i);
        }

        // 3. Recalculate aggregate stats + ranking points
        PlayerStat::recalculateFromPerformances($player);
    }

    // ──────────────────────────────────────────────────────
    //  INITIAL STATS GENERATORS (per category)
    // ──────────────────────────────────────────────────────

    private function generateInitialStats(string $category): array
    {
        return match ($category) {
            Player::CATEGORY_TOP_ORDER_BATTER   => $this->initialBatter(high: true),
            Player::CATEGORY_POWER_HITTER       => $this->initialPowerHitter(),
            Player::CATEGORY_FAST_BOWLER        => $this->initialBowler(pace: true),
            Player::CATEGORY_MEDIUM_BOWLER      => $this->initialBowler(pace: false),
            Player::CATEGORY_FINGER_SPIN_BOWLER => $this->initialSpinner(),
            Player::CATEGORY_WRIST_SPIN_BOWLER  => $this->initialSpinner(),
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER => $this->initialAllRounder(pace: true),
            Player::CATEGORY_SPIN_ALLROUNDER    => $this->initialAllRounder(pace: false),
            default                             => $this->initialBatter(high: false),
        };
    }

    private function initialBatter(bool $high): array
    {
        $matches = rand(15, 30);
        $innings = $matches - rand(0, 3);
        $runs = $high ? rand(400, 900) : rand(200, 500);
        $notOuts = rand(1, 5);
        $fifties = (int) ($runs / rand(100, 180));
        $hundreds = $runs > 500 ? rand(0, 2) : 0;
        $fours = (int) ($runs * rand(25, 40) / 100);
        $sixes = (int) ($runs * rand(3, 10) / 100);
        $ballsFaced = (int) ($runs / (rand(65, 110) / 100));
        $highestScore = $hundreds > 0 ? rand(100, 140) : rand(50, 95);

        return [
            'batting_matches'      => $matches,
            'batting_innings'      => $innings,
            'batting_runs'         => $runs,
            'batting_balls_faced'  => $ballsFaced,
            'batting_not_outs'     => $notOuts,
            'batting_highest_score' => $highestScore,
            'batting_fifties'      => $fifties,
            'batting_hundreds'     => $hundreds,
            'batting_fours'        => $fours,
            'batting_sixes'        => $sixes,
            'fielding_catches'     => rand(3, 12),
            'fielding_run_outs'    => rand(0, 3),
            'fielding_stumpings'   => 0,
        ];
    }

    private function initialPowerHitter(): array
    {
        $matches = rand(12, 25);
        $innings = $matches - rand(0, 3);
        $runs = rand(300, 700);
        $notOuts = rand(1, 4);
        $fifties = (int) ($runs / rand(90, 160));
        $hundreds = $runs > 450 ? rand(0, 2) : 0;
        $fours = (int) ($runs * rand(30, 45) / 100);
        $sixes = (int) ($runs * rand(10, 20) / 100);       // Power hitters hit more sixes
        $ballsFaced = (int) ($runs / (rand(100, 145) / 100));  // Higher strike rate
        $highestScore = $hundreds > 0 ? rand(100, 130) : rand(55, 90);

        return [
            'batting_matches'       => $matches,
            'batting_innings'       => $innings,
            'batting_runs'          => $runs,
            'batting_balls_faced'   => $ballsFaced,
            'batting_not_outs'      => $notOuts,
            'batting_highest_score' => $highestScore,
            'batting_fifties'       => $fifties,
            'batting_hundreds'      => $hundreds,
            'batting_fours'         => $fours,
            'batting_sixes'         => $sixes,
            'fielding_catches'      => rand(2, 8),
            'fielding_run_outs'     => rand(0, 2),
            'fielding_stumpings'    => 0,
        ];
    }

    private function initialBowler(bool $pace): array
    {
        $matches = rand(15, 28);
        $innings = $matches - rand(0, 4);
        $overs = rand(60, 150);
        $maidens = $pace ? rand(5, 20) : rand(8, 30);
        $wickets = rand(15, 50);
        $runsC = (int) ($overs * rand(30, 55) / 10);   // economy 3.0 – 5.5
        $bestW = min($wickets, rand(3, 6));
        $bestR = (int) ($bestW * rand(4, 10));
        $fiveW = $wickets > 25 ? rand(0, 2) : 0;

        return [
            'bowling_matches'       => $matches,
            'bowling_innings'       => $innings,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_best_wickets'  => $bestW,
            'bowling_best_runs'     => $bestR,
            'bowling_five_wickets'  => $fiveW,
            // Bowlers still bat a bit
            'batting_matches'       => $matches,
            'batting_innings'       => rand(5, $innings),
            'batting_runs'          => rand(30, 150),
            'batting_balls_faced'   => rand(40, 200),
            'batting_not_outs'      => rand(1, 5),
            'batting_highest_score' => rand(10, 35),
            'batting_fifties'       => 0,
            'batting_hundreds'      => 0,
            'batting_fours'         => rand(3, 15),
            'batting_sixes'         => rand(0, 4),
            'fielding_catches'      => rand(3, 15),
            'fielding_run_outs'     => rand(0, 3),
            'fielding_stumpings'    => 0,
        ];
    }

    private function initialSpinner(): array
    {
        $matches = rand(15, 28);
        $innings = $matches - rand(0, 4);
        $overs = rand(70, 180);
        $maidens = rand(10, 35);
        $wickets = rand(18, 55);
        $runsC = (int) ($overs * rand(28, 48) / 10);   // spinners tend to be more economical
        $bestW = min($wickets, rand(3, 7));
        $bestR = (int) ($bestW * rand(3, 9));
        $fiveW = $wickets > 30 ? rand(0, 3) : 0;

        return [
            'bowling_matches'       => $matches,
            'bowling_innings'       => $innings,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_best_wickets'  => $bestW,
            'bowling_best_runs'     => $bestR,
            'bowling_five_wickets'  => $fiveW,
            'batting_matches'       => $matches,
            'batting_innings'       => rand(6, $innings),
            'batting_runs'          => rand(40, 180),
            'batting_balls_faced'   => rand(50, 250),
            'batting_not_outs'      => rand(2, 6),
            'batting_highest_score' => rand(12, 40),
            'batting_fifties'       => 0,
            'batting_hundreds'      => 0,
            'batting_fours'         => rand(4, 18),
            'batting_sixes'         => rand(0, 5),
            'fielding_catches'      => rand(5, 18),
            'fielding_run_outs'     => rand(0, 3),
            'fielding_stumpings'    => 0,
        ];
    }

    private function initialAllRounder(bool $pace): array
    {
        $matches = rand(15, 30);
        $batInn = $matches - rand(0, 3);
        $bowlInn = $matches - rand(0, 5);
        $runs = rand(250, 600);
        $notOuts = rand(1, 5);
        $fifties = (int) ($runs / rand(120, 200));
        $hundreds = $runs > 500 ? rand(0, 1) : 0;
        $fours = (int) ($runs * rand(25, 35) / 100);
        $sixes = (int) ($runs * rand(5, 12) / 100);
        $ballsFaced = (int) ($runs / (rand(70, 105) / 100));
        $highestScore = $hundreds > 0 ? rand(100, 120) : rand(45, 85);

        $overs = rand(50, 130);
        $maidens = $pace ? rand(4, 16) : rand(8, 25);
        $wickets = rand(12, 40);
        $runsC = (int) ($overs * rand(32, 52) / 10);
        $bestW = min($wickets, rand(3, 5));
        $bestR = (int) ($bestW * rand(5, 11));
        $fiveW = $wickets > 25 ? rand(0, 1) : 0;

        return [
            'batting_matches'       => $matches,
            'batting_innings'       => $batInn,
            'batting_runs'          => $runs,
            'batting_balls_faced'   => $ballsFaced,
            'batting_not_outs'      => $notOuts,
            'batting_highest_score' => $highestScore,
            'batting_fifties'       => $fifties,
            'batting_hundreds'      => $hundreds,
            'batting_fours'         => $fours,
            'batting_sixes'         => $sixes,
            'bowling_matches'       => $matches,
            'bowling_innings'       => $bowlInn,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_best_wickets'  => $bestW,
            'bowling_best_runs'     => $bestR,
            'bowling_five_wickets'  => $fiveW,
            'fielding_catches'      => rand(5, 18),
            'fielding_run_outs'     => rand(1, 5),
            'fielding_stumpings'    => 0,
        ];
    }

    // ──────────────────────────────────────────────────────
    //  MATCH PERFORMANCE GENERATOR
    // ──────────────────────────────────────────────────────

    private function createMatchPerformance(Player $player, string $category, string $opponent, int $index): void
    {
        // Spread match dates across last 6 months
        $matchDate = now()->subDays(rand(10, 180))->format('Y-m-d');

        $data = [
            'player_id'         => $player->id,
            'match_date'        => $matchDate,
            'opponent'          => $opponent,
            'match_description' => $this->matchDescription($index),
        ];

        // Generate category-appropriate performance
        match ($category) {
            Player::CATEGORY_TOP_ORDER_BATTER   => $this->perfBatter($data, high: true),
            Player::CATEGORY_POWER_HITTER       => $this->perfPowerHitter($data),
            Player::CATEGORY_FAST_BOWLER        => $this->perfBowler($data, pace: true),
            Player::CATEGORY_MEDIUM_BOWLER      => $this->perfBowler($data, pace: false),
            Player::CATEGORY_FINGER_SPIN_BOWLER => $this->perfSpinner($data),
            Player::CATEGORY_WRIST_SPIN_BOWLER  => $this->perfSpinner($data),
            Player::CATEGORY_FAST_BOWLING_ALLROUNDER => $this->perfAllRounder($data, pace: true),
            Player::CATEGORY_SPIN_ALLROUNDER    => $this->perfAllRounder($data, pace: false),
            default                             => $this->perfBatter($data, high: false),
        };
    }

    private function matchDescription(int $index): string
    {
        $descriptions = [
            'Inter-School Tournament - Group Stage',
            'Friendly Match',
            'Provincial Championship - Quarter Final',
            'Annual Big Match',
            'Singer U19 Schools Tournament',
            'District Championship Match',
        ];
        return $descriptions[$index % count($descriptions)];
    }

    // ── Batting-focused performances ──

    private function perfBatter(array &$data, bool $high): void
    {
        $runs = $high ? rand(5, 85) : rand(2, 55);
        // Occasionally a big score
        if (rand(1, 5) === 1) $runs = rand(50, 110);

        $ballsFaced = max(1, (int) ($runs / (rand(60, 100) / 100)));
        $fours = (int) ($runs * rand(20, 40) / 100 / 4);
        $sixes = $runs > 40 ? rand(0, 3) : rand(0, 1);

        $data += [
            'batting_runs'         => $runs,
            'batting_balls_faced'  => $ballsFaced,
            'batting_fours'        => $fours,
            'batting_sixes'        => $sixes,
            'batting_not_out'      => rand(1, 5) === 1,
            'bowling_overs'        => 0,
            'bowling_maidens'      => 0,
            'bowling_runs_conceded' => 0,
            'bowling_wickets'      => 0,
            'bowling_dot_balls'    => 0,
            'fielding_catches'     => rand(0, 2),
            'fielding_run_outs'    => rand(0, 1) === 1 ? 1 : 0,
            'fielding_stumpings'   => 0,
        ];

        PlayerMatchPerformance::create($data);
    }

    private function perfPowerHitter(array &$data): void
    {
        $runs = rand(8, 70);
        if (rand(1, 4) === 1) $runs = rand(50, 95);

        $ballsFaced = max(1, (int) ($runs / (rand(100, 160) / 100)));  // High SR
        $fours = (int) ($runs * rand(25, 45) / 100 / 4);
        $sixes = max(0, (int) ($runs * rand(10, 25) / 100 / 6));

        $data += [
            'batting_runs'          => $runs,
            'batting_balls_faced'   => $ballsFaced,
            'batting_fours'         => $fours,
            'batting_sixes'         => $sixes,
            'batting_not_out'       => rand(1, 5) === 1,
            'bowling_overs'         => 0,
            'bowling_maidens'       => 0,
            'bowling_runs_conceded' => 0,
            'bowling_wickets'       => 0,
            'bowling_dot_balls'     => 0,
            'fielding_catches'      => rand(0, 1),
            'fielding_run_outs'     => 0,
            'fielding_stumpings'    => 0,
        ];

        PlayerMatchPerformance::create($data);
    }

    // ── Bowling-focused performances ──

    private function perfBowler(array &$data, bool $pace): void
    {
        $overs = rand(3, 10);
        $balls = $overs * 6;
        $wickets = rand(0, 4);
        if (rand(1, 6) === 1) $wickets = rand(3, 5);

        $maidens = rand(0, min(3, $overs));
        $runsC = (int) ($overs * rand(25, 60) / 10);
        $dotBalls = (int) ($balls * rand(35, 60) / 100);

        // Tail-ender batting
        $batRuns = rand(0, 25);
        $batBalls = $batRuns > 0 ? max(1, (int) ($batRuns / (rand(50, 90) / 100))) : 0;

        $data += [
            'batting_runs'          => $batRuns,
            'batting_balls_faced'   => $batBalls,
            'batting_fours'         => $batRuns > 10 ? rand(0, 3) : 0,
            'batting_sixes'         => $batRuns > 20 ? rand(0, 1) : 0,
            'batting_not_out'       => rand(1, 3) === 1,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_dot_balls'     => $dotBalls,
            'fielding_catches'      => rand(0, 2),
            'fielding_run_outs'     => 0,
            'fielding_stumpings'    => 0,
        ];

        PlayerMatchPerformance::create($data);
    }

    private function perfSpinner(array &$data): void
    {
        $overs = rand(4, 10);
        $balls = $overs * 6;
        $wickets = rand(0, 4);
        if (rand(1, 5) === 1) $wickets = rand(3, 6);

        $maidens = rand(0, min(4, $overs));
        $runsC = (int) ($overs * rand(22, 50) / 10);   // Spinners often more economical
        $dotBalls = (int) ($balls * rand(40, 65) / 100);

        $batRuns = rand(0, 30);
        $batBalls = $batRuns > 0 ? max(1, (int) ($batRuns / (rand(50, 85) / 100))) : 0;

        $data += [
            'batting_runs'          => $batRuns,
            'batting_balls_faced'   => $batBalls,
            'batting_fours'         => $batRuns > 10 ? rand(0, 3) : 0,
            'batting_sixes'         => $batRuns > 20 ? rand(0, 1) : 0,
            'batting_not_out'       => rand(1, 3) === 1,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_dot_balls'     => $dotBalls,
            'fielding_catches'      => rand(0, 2),
            'fielding_run_outs'     => 0,
            'fielding_stumpings'    => 0,
        ];

        PlayerMatchPerformance::create($data);
    }

    // ── All-rounder performances ──

    private function perfAllRounder(array &$data, bool $pace): void
    {
        // Decent batting
        $runs = rand(5, 60);
        if (rand(1, 5) === 1) $runs = rand(40, 80);
        $ballsFaced = max(1, (int) ($runs / (rand(65, 105) / 100)));
        $fours = (int) ($runs * rand(20, 35) / 100 / 4);
        $sixes = $runs > 30 ? rand(0, 2) : 0;

        // Decent bowling
        $overs = rand(3, 8);
        $balls = $overs * 6;
        $wickets = rand(0, 3);
        if (rand(1, 5) === 1) $wickets = rand(2, 4);
        $maidens = rand(0, min(2, $overs));
        $runsC = (int) ($overs * rand(28, 55) / 10);
        $dotBalls = (int) ($balls * rand(35, 58) / 100);

        $data += [
            'batting_runs'          => $runs,
            'batting_balls_faced'   => $ballsFaced,
            'batting_fours'         => $fours,
            'batting_sixes'         => $sixes,
            'batting_not_out'       => rand(1, 5) === 1,
            'bowling_overs'         => (float) $overs,
            'bowling_maidens'       => $maidens,
            'bowling_runs_conceded' => $runsC,
            'bowling_wickets'       => $wickets,
            'bowling_dot_balls'     => $dotBalls,
            'fielding_catches'      => rand(0, 2),
            'fielding_run_outs'     => rand(0, 1) === 1 ? 1 : 0,
            'fielding_stumpings'    => 0,
        ];

        PlayerMatchPerformance::create($data);
    }
}

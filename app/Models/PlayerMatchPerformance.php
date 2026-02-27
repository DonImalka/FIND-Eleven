<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerMatchPerformance extends Model
{
    protected $fillable = [
        'player_id',
        'match_date',
        'opponent',
        'match_description',
        // Batting
        'batting_runs',
        'batting_balls_faced',
        'batting_fours',
        'batting_sixes',
        'batting_not_out',
        // Bowling
        'bowling_overs',
        'bowling_maidens',
        'bowling_runs_conceded',
        'bowling_wickets',
        'bowling_dot_balls',
        // Fielding
        'fielding_catches',
        'fielding_run_outs',
        'fielding_stumpings',
    ];

    protected $casts = [
        'match_date' => 'date',
        'batting_not_out' => 'boolean',
        'bowling_overs' => 'decimal:1',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Check if the player batted in this match.
     */
    public function didBat(): bool
    {
        return $this->batting_balls_faced > 0 || $this->batting_runs > 0;
    }

    /**
     * Check if the player bowled in this match.
     */
    public function didBowl(): bool
    {
        return $this->bowling_overs > 0;
    }

    /**
     * Get the form fields relevant to the player's category for a single match performance.
     */
    public static function getFieldsForCategory(string $category): array
    {
        $batting = [
            'batting_runs' => ['label' => 'Runs Scored', 'type' => 'number', 'step' => '1'],
            'batting_balls_faced' => ['label' => 'Balls Faced', 'type' => 'number', 'step' => '1'],
            'batting_fours' => ['label' => 'Fours (4s)', 'type' => 'number', 'step' => '1'],
            'batting_sixes' => ['label' => 'Sixes (6s)', 'type' => 'number', 'step' => '1'],
            'batting_not_out' => ['label' => 'Not Out?', 'type' => 'checkbox'],
        ];

        $bowling = [
            'bowling_overs' => ['label' => 'Overs Bowled', 'type' => 'number', 'step' => '0.1'],
            'bowling_maidens' => ['label' => 'Maidens', 'type' => 'number', 'step' => '1'],
            'bowling_runs_conceded' => ['label' => 'Runs Conceded', 'type' => 'number', 'step' => '1'],
            'bowling_wickets' => ['label' => 'Wickets', 'type' => 'number', 'step' => '1'],
            'bowling_dot_balls' => ['label' => 'Dot Balls', 'type' => 'number', 'step' => '1'],
        ];

        $fielding = [
            'fielding_catches' => ['label' => 'Catches', 'type' => 'number', 'step' => '1'],
            'fielding_run_outs' => ['label' => 'Run Outs', 'type' => 'number', 'step' => '1'],
            'fielding_stumpings' => ['label' => 'Stumpings', 'type' => 'number', 'step' => '1'],
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
            $sections['Batting'] = $batting;
            $sections['Fielding'] = $fielding;
        } elseif (in_array($category, $bowlingCategories)) {
            $sections['Bowling'] = $bowling;
            $sections['Fielding'] = $fielding;
        } elseif (in_array($category, $allRounderCategories)) {
            $sections['Batting'] = $batting;
            $sections['Bowling'] = $bowling;
            $sections['Fielding'] = $fielding;
        } else {
            $sections['Batting'] = $batting;
            $sections['Bowling'] = $bowling;
            $sections['Fielding'] = $fielding;
        }

        return $sections;
    }
}

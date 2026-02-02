<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    use HasFactory;

    // Age category constants
    const AGE_U13 = 'U13';
    const AGE_U15 = 'U15';
    const AGE_U17 = 'U17';
    const AGE_U19 = 'U19';

    // Player category constants
    const CATEGORY_TOP_ORDER_BATTER = 'Top Order Batter';
    const CATEGORY_POWER_HITTER = 'Power Hitter';
    const CATEGORY_FAST_BOWLER = 'Fast Bowler';
    const CATEGORY_MEDIUM_BOWLER = 'Medium Bowler';
    const CATEGORY_FINGER_SPIN_BOWLER = 'Finger Spin Bowler';
    const CATEGORY_WRIST_SPIN_BOWLER = 'Wrist Spin Bowler';
    const CATEGORY_FAST_BOWLING_ALLROUNDER = 'Fast Bowling All-Rounder';
    const CATEGORY_SPIN_ALLROUNDER = 'Spin All-Rounder';

    // Batting style constants
    const BATTING_RIGHT_HAND = 'Right-hand Bat';
    const BATTING_LEFT_HAND = 'Left-hand Bat';

    // Bowling style constants
    const BOWLING_RIGHT_ARM_FAST = 'Right-arm Fast';
    const BOWLING_LEFT_ARM_FAST = 'Left-arm Fast';
    const BOWLING_RIGHT_ARM_MEDIUM = 'Right-arm Medium';
    const BOWLING_LEFT_ARM_MEDIUM = 'Left-arm Medium';
    const BOWLING_RIGHT_ARM_OFF_SPIN = 'Right-arm Off Spin';
    const BOWLING_LEFT_ARM_ORTHODOX = 'Left-arm Orthodox';
    const BOWLING_RIGHT_ARM_LEG_SPIN = 'Right-arm Leg Spin';
    const BOWLING_LEFT_ARM_CHINAMAN = 'Left-arm Chinaman';
    const BOWLING_DOES_NOT_BOWL = 'Does Not Bowl';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'full_name',
        'date_of_birth',
        'age_category',
        'player_category',
        'batting_style',
        'bowling_style',
        'jersey_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the school that owns the player
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Calculate age category based on date of birth
     * Reference date is typically January 1st of the current year
     */
    public static function calculateAgeCategory(string $dateOfBirth): string
    {
        $dob = Carbon::parse($dateOfBirth);
        $referenceDate = Carbon::create(now()->year, 1, 1);
        $age = $dob->diffInYears($referenceDate);

        if ($age < 13) {
            return self::AGE_U13;
        } elseif ($age < 15) {
            return self::AGE_U15;
        } elseif ($age < 17) {
            return self::AGE_U17;
        } else {
            return self::AGE_U19;
        }
    }

    /**
     * Get player's current age
     */
    public function getAge(): int
    {
        return $this->date_of_birth->age;
    }

    /**
     * Get all player categories
     */
    public static function getPlayerCategories(): array
    {
        return [
            self::CATEGORY_TOP_ORDER_BATTER,
            self::CATEGORY_POWER_HITTER,
            self::CATEGORY_FAST_BOWLER,
            self::CATEGORY_MEDIUM_BOWLER,
            self::CATEGORY_FINGER_SPIN_BOWLER,
            self::CATEGORY_WRIST_SPIN_BOWLER,
            self::CATEGORY_FAST_BOWLING_ALLROUNDER,
            self::CATEGORY_SPIN_ALLROUNDER,
        ];
    }

    /**
     * Get all age categories
     */
    public static function getAgeCategories(): array
    {
        return [
            self::AGE_U13,
            self::AGE_U15,
            self::AGE_U17,
            self::AGE_U19,
        ];
    }

    /**
     * Get all batting styles
     */
    public static function getBattingStyles(): array
    {
        return [
            self::BATTING_RIGHT_HAND,
            self::BATTING_LEFT_HAND,
        ];
    }

    /**
     * Get all bowling styles
     */
    public static function getBowlingStyles(): array
    {
        return [
            self::BOWLING_RIGHT_ARM_FAST,
            self::BOWLING_LEFT_ARM_FAST,
            self::BOWLING_RIGHT_ARM_MEDIUM,
            self::BOWLING_LEFT_ARM_MEDIUM,
            self::BOWLING_RIGHT_ARM_OFF_SPIN,
            self::BOWLING_LEFT_ARM_ORTHODOX,
            self::BOWLING_RIGHT_ARM_LEG_SPIN,
            self::BOWLING_LEFT_ARM_CHINAMAN,
            self::BOWLING_DOES_NOT_BOWL,
        ];
    }
}

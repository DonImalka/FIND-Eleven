<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatterScore extends Model
{
    use HasFactory;

    const STATUS_YET_TO_BAT = 'yet_to_bat';
    const STATUS_BATTING = 'batting';
    const STATUS_OUT = 'out';
    const STATUS_NOT_OUT = 'not_out';
    const STATUS_RETIRED = 'retired';

    protected $fillable = [
        'match_inning_id',
        'player_id',
        'runs',
        'balls_faced',
        'fours',
        'sixes',
        'status',
        'dismissal_info',
        'batting_position',
    ];

    public function inning(): BelongsTo
    {
        return $this->belongsTo(MatchInning::class, 'match_inning_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get strike rate.
     */
    public function getStrikeRate(): string
    {
        if ($this->balls_faced === 0) return '0.00';
        return number_format(($this->runs / $this->balls_faced) * 100, 2);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_YET_TO_BAT,
            self::STATUS_BATTING,
            self::STATUS_OUT,
            self::STATUS_NOT_OUT,
            self::STATUS_RETIRED,
        ];
    }
}

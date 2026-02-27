<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BowlerScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_inning_id',
        'player_id',
        'overs',
        'maidens',
        'runs_conceded',
        'wickets',
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
     * Get economy rate.
     */
    public function getEconomyRate(): string
    {
        $overs = floatval($this->overs);
        if ($overs <= 0) return '0.00';

        // Convert "4.3" to 4 + 3/6 = 4.5 actual overs
        $fullOvers = intval($overs);
        $balls = round(($overs - $fullOvers) * 10);
        $actualOvers = $fullOvers + ($balls / 6);

        if ($actualOvers <= 0) return '0.00';
        return number_format($this->runs_conceded / $actualOvers, 2);
    }
}

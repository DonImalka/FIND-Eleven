<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchInning extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'batting_school_id',
        'bowling_school_id',
        'inning_number',
        'total_runs',
        'total_wickets',
        'total_overs',
        'extras',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(CricketMatch::class, 'match_id');
    }

    public function battingSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'batting_school_id');
    }

    public function bowlingSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'bowling_school_id');
    }

    public function batterScores(): HasMany
    {
        return $this->hasMany(BatterScore::class, 'match_inning_id');
    }

    public function bowlerScores(): HasMany
    {
        return $this->hasMany(BowlerScore::class, 'match_inning_id');
    }

    /**
     * Get score display string, e.g. "150/4 (25.3 ov)"
     */
    public function getScoreDisplay(): string
    {
        return "{$this->total_runs}/{$this->total_wickets} ({$this->total_overs} ov)";
    }
}

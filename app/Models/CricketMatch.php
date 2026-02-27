<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CricketMatch extends Model
{
    use HasFactory;

    protected $table = 'cricket_matches';

    const STATUS_UPCOMING = 'upcoming';
    const STATUS_LIVE = 'live';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'tournament_id',
        'home_school_id',
        'away_school_id',
        'match_date',
        'venue',
        'overs_per_side',
        'status',
        'toss_winner_school_id',
        'toss_decision',
        'result_summary',
    ];

    protected $casts = [
        'match_date' => 'date',
    ];

    // ── Relationships ──

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'home_school_id');
    }

    public function awaySchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'away_school_id');
    }

    public function tossWinner(): BelongsTo
    {
        return $this->belongsTo(School::class, 'toss_winner_school_id');
    }

    public function squads(): HasMany
    {
        return $this->hasMany(MatchSquad::class, 'match_id');
    }

    public function innings(): HasMany
    {
        return $this->hasMany(MatchInning::class, 'match_id');
    }

    // ── Helpers ──

    public function isLive(): bool
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isUpcoming(): bool
    {
        return $this->status === self::STATUS_UPCOMING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if a school is playing in this match.
     */
    public function hasSchool(int $schoolId): bool
    {
        return $this->home_school_id === $schoolId || $this->away_school_id === $schoolId;
    }

    /**
     * Get the opponent school for a given school id.
     */
    public function getOpponent(int $schoolId): ?School
    {
        if ($this->home_school_id === $schoolId) {
            return $this->awaySchool;
        }
        if ($this->away_school_id === $schoolId) {
            return $this->homeSchool;
        }
        return null;
    }

    /**
     * Get squads for a specific school.
     */
    public function squadForSchool(int $schoolId)
    {
        return $this->squads()->where('school_id', $schoolId)->with('player')->get();
    }

    /**
     * Get playing XI for a specific school.
     */
    public function playingXIForSchool(int $schoolId)
    {
        return $this->squads()
            ->where('school_id', $schoolId)
            ->where('is_playing_xi', true)
            ->with('player')
            ->get();
    }

    /**
     * Current active innings.
     */
    public function currentInnings(): ?MatchInning
    {
        return $this->innings()->where('is_completed', false)->first();
    }

    /**
     * Get match title string.
     */
    public function getTitle(): string
    {
        return ($this->homeSchool->school_name ?? 'TBD') . ' vs ' . ($this->awaySchool->school_name ?? 'TBD');
    }

    /**
     * Get score summary string.
     */
    public function getScoreSummary(): string
    {
        $innings = $this->innings()->orderBy('inning_number')->get();

        if ($innings->isEmpty()) {
            return 'Match not started';
        }

        $parts = [];
        foreach ($innings as $inning) {
            $schoolName = $inning->battingSchool->school_name ?? 'Unknown';
            $parts[] = "{$schoolName}: {$inning->total_runs}/{$inning->total_wickets} ({$inning->total_overs} ov)";
        }

        return implode(' | ', $parts);
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_UPCOMING,
            self::STATUS_LIVE,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }
}

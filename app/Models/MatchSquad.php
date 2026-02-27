<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchSquad extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'school_id',
        'player_id',
        'is_playing_xi',
    ];

    protected $casts = [
        'is_playing_xi' => 'boolean',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(CricketMatch::class, 'match_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}

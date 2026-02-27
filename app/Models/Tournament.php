<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    const STATUS_UPCOMING = 'upcoming';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'name',
        'year',
        'description',
        'status',
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(CricketMatch::class, 'tournament_id');
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_UPCOMING,
            self::STATUS_ONGOING,
            self::STATUS_COMPLETED,
        ];
    }
}

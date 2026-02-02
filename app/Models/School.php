<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    // Status constants for approval workflow
    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_REJECTED = 'REJECTED';

    // School type constants
    const TYPE_GOVERNMENT = 'Government';
    const TYPE_PRIVATE = 'Private';
    const TYPE_SEMI_GOVERNMENT = 'Semi-Government';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'school_name',
        'school_type',
        'district',
        'province',
        'school_address',
        'contact_number',
        'cricket_incharge_name',
        'cricket_incharge_contact',
        'status',
    ];

    /**
     * Get the user that owns the school
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the players belonging to this school
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Check if school is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if school is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if school is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get all school types as array
     */
    public static function getSchoolTypes(): array
    {
        return [
            self::TYPE_GOVERNMENT,
            self::TYPE_PRIVATE,
            self::TYPE_SEMI_GOVERNMENT,
        ];
    }

    /**
     * Get all statuses as array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }
}

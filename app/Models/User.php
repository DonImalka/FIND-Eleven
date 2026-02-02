<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants for easy reference
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_SCHOOL = 'SCHOOL';
    const ROLE_PLAYER = 'PLAYER';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a school
     */
    public function isSchool(): bool
    {
        return $this->role === self::ROLE_SCHOOL;
    }

    /**
     * Check if user is a player
     */
    public function isPlayer(): bool
    {
        return $this->role === self::ROLE_PLAYER;
    }

    /**
     * Get the school profile associated with the user
     */
    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }
}

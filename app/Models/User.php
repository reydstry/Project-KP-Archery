<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRoles::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoles::ADMIN;
    }

    public function isCoach(): bool
    {
        return $this->role === UserRoles::COACH;
    }

    public function isMember(): bool
    {
        return $this->role === UserRoles::MEMBER;
    }

    /**
     * Get coach profile
     */
    public function coach()
    {
        return $this->hasOne(Coach::class);
    }

    /**
     * Get member profile
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }
}

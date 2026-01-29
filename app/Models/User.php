<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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

    public function hasRole(UserRoles|string $role): bool
    {
        $roleValue = $role instanceof UserRoles ? $role->value : (string) $role;

        $current = $this->role;
        $currentValue = $current instanceof UserRoles ? $current->value : (string) $current;

        return $currentValue === $roleValue;
    }

    public function absents(): HasMany
    {
        return $this->hasMany(Absent::class);
    }
}

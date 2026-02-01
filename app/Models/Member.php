<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registered_by',
        'name',
        'phone',
        'is_self',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_self' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user that owns this member
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who registered this member
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}

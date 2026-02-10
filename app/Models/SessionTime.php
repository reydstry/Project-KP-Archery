<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'string',
            'end_time' => 'string',
            'is_active' => 'boolean',
        ];
    }

    public function slots()
    {
        return $this->hasMany(TrainingSessionSlot::class);
    }

    /**
     * Scope for active session times
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

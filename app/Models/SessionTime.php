<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'max_capacity',
        'coach_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the coach
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Get training sessions
     */
    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    /**
     * Scope for active session times
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

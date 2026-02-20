<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSessionSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'session_time_id',
        'max_participants',
    ];

    protected function casts(): array
    {
        return [
            'max_participants' => 'integer',
        ];
    }

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function sessionTime()
    {
        return $this->belongsTo(SessionTime::class);
    }

    /**
     * Coaches assigned to this specific slot
     */
    public function coaches()
    {
        return $this->belongsToMany(Coach::class, 'training_session_slot_coach')
            ->withTimestamps();
    }
}

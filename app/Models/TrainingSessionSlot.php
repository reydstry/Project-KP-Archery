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

    public function bookings()
    {
        return $this->hasMany(SessionBooking::class);
    }

    public function confirmedBookings()
    {
        return $this->bookings()->where('status', 'confirmed');
    }

    public function getCurrentParticipantsAttribute(): int
    {
        return $this->confirmedBookings()->count();
    }

    public function isFull(): bool
    {
        return $this->current_participants >= $this->max_participants;
    }
}

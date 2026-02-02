<?php

namespace App\Models;

use App\Enums\TrainingSessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_time_id',
        'date',
        'coach_id',
        'max_participants',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => TrainingSessionStatus::class,
        ];
    }

    /**
     * Get the session time
     */
    public function sessionTime()
    {
        return $this->belongsTo(SessionTime::class);
    }

    /**
     * Get the coach
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Get attendances for this session
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get current participants count
     */
    public function getCurrentParticipantsAttribute(): int
    {
        return $this->attendances()->count();
    }

    /**
     * Check if session is full
     */
    public function isFull(): bool
    {
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Check if session is open for registration
     */
    public function isOpenForRegistration(): bool
    {
        return $this->status === TrainingSessionStatus::OPEN 
            && !$this->isFull() 
            && $this->date->isFuture();
    }

    /**
     * Open the session
     */
    public function open(): void
    {
        $this->update(['status' => TrainingSessionStatus::OPEN]);
    }

    /**
     * Close the session
     */
    public function close(): void
    {
        $this->update(['status' => TrainingSessionStatus::CLOSED]);
    }

    /**
     * Cancel the session
     */
    public function cancel(): void
    {
        $this->update(['status' => TrainingSessionStatus::CANCELED]);
    }

    /**
     * Scope for open sessions
     */
    public function scopeOpen($query)
    {
        return $query->where('status', TrainingSessionStatus::OPEN);
    }

    /**
     * Scope for closed sessions
     */
    public function scopeClosed($query)
    {
        return $query->where('status', TrainingSessionStatus::CLOSED);
    }

    /**
     * Scope for upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }
}

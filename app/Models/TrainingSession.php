<?php

namespace App\Models;

use App\Enums\TrainingSessionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'coach_id',
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
     * Get the coach
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    /**
     * Slots (one per session_time)
     */
    public function slots()
    {
        return $this->hasMany(TrainingSessionSlot::class);
    }

    /**
     * Bookings across all slots
     */
    public function bookings()
    {
        return $this->hasManyThrough(SessionBooking::class, TrainingSessionSlot::class);
    }

    /**
     * Check if session is open for registration (day-level)
     */
    public function isOpenForRegistration(): bool
    {
        return $this->status === TrainingSessionStatus::OPEN 
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

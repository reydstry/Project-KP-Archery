<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_package_id',
        'training_session_slot_id',
        'booked_by',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    /**
     * Get the member package
     */
    public function memberPackage()
    {
        return $this->belongsTo(MemberPackage::class);
    }

    /**
     * Get the training session slot (specific time within a day)
     */
    public function trainingSessionSlot()
    {
        return $this->belongsTo(TrainingSessionSlot::class);
    }

    /**
     * Get the user who booked
     */
    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    /**
     * Get the attendance record
     */
    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Cancel the booking
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for cancelled bookings
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}

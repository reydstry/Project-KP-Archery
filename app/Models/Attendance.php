<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_booking_id',
        'status',
        'validated_by',
        'validated_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
        ];
    }

    /**
     * Get the session booking
     */
    public function sessionBooking()
    {
        return $this->belongsTo(SessionBooking::class);
    }

    /**
     * Get the validator (coach who validated)
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Check if member was present
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if member was absent
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    /**
     * Scope for present attendances
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope for absent attendances
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }
}

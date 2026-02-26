<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'member_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeForSession($query, int $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForMember($query, int $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeOnlyActiveMembers($query)
    {
        return $query->whereHas('member', function ($memberQuery) {
            // Use eligibleForAttendance: is_active=true + has active package
            $memberQuery->eligibleForAttendance();
        });
    }
}

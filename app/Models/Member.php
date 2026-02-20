<?php

namespace App\Models;

use App\Enums\StatusMember;
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
        'status',
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
     * Scope for pending members
     */
    public function scopePending($query)
    {
        return $query->where('status', StatusMember::STATUS_PENDING);
    }

    /**
     * Scope for active members
     */
    public function scopeActive($query)
    {
        return $query->where('status', StatusMember::STATUS_ACTIVE);
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

    /**
     * Get member packages
     */
    public function memberPackages()
    {
        return $this->hasMany(MemberPackage::class);
    }

    /**
     * Get achievements
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Get attendance records for this member.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function broadcastLogs()
    {
        return $this->hasMany(BroadcastLog::class);
    }
}

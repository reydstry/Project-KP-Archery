<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'package_id',
        'total_sessions',
        'used_sessions',
        'start_date',
        'end_date',
        'is_active',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'validated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the validator (admin who validated)
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Check if package has remaining sessions
     */
    public function hasRemainingSessions(): bool
    {
        return (bool) $this->is_active
            && $this->end_date
            && $this->end_date->isFuture()
            && $this->used_sessions < $this->total_sessions;
    }

    /**
     * Get remaining sessions count
     */
    public function getRemainingSessionsAttribute(): int
    {
        return max(0, $this->total_sessions - $this->used_sessions);
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now());
    }
}

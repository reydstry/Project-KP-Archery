<?php

namespace App\Models;

use App\Enums\StatusMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    /**
     * 'status' is intentionally omitted — it is a COMPUTED attribute.
     * Status is derived from is_active + active member_packages.
     * Never write to 'status' manually; manage is_active + packages instead.
     */
    protected $fillable = [
        'user_id',
        'registered_by',
        'name',
        'phone',
        'is_self',
        'is_active',
    ];

    protected $appends = ['status'];

    protected function casts(): array
    {
        return [
            'is_self'   => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // =========================================================================
    // COMPUTED STATUS (dynamic — never toggled manually)
    //   is_active=false                           → inactive
    //   is_active=true + has valid active package → active
    //   is_active=true + no valid active package  → pending
    // =========================================================================

    /**
     * Dynamic status based on is_active flag and active packages.
     * Eager-load `memberPackages` when listing many members to avoid N+1.
     */
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return StatusMember::STATUS_INACTIVE->value;
        }

        if ($this->relationLoaded('memberPackages')) {
            $hasActive = $this->memberPackages->contains(
                fn (MemberPackage $pkg) => $pkg->is_active && $pkg->end_date?->isFuture()
            );
            $everHadPackage = $this->memberPackages->isNotEmpty();
        } else {
            $hasActive = $this->memberPackages()->active()->exists();
            $everHadPackage = $this->memberPackages()->exists();
        }

        if ($hasActive) {
            return StatusMember::STATUS_ACTIVE->value;
        }

        // Pernah punya paket tapi sudah expired → inactive
        // Belum pernah punya paket sama sekali → pending
        return $everHadPackage
            ? StatusMember::STATUS_INACTIVE->value
            : StatusMember::STATUS_PENDING->value;
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /** Members deactivated (is_active = false). */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /** Members not deactivated (is_active = true — both pending and active). */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
     * Members eligible to record attendance:
     * is_active=true AND at least one non-expired active package.
     * Backed by composite index mp_member_active_end_idx.
     */
    public function scopeEligibleForAttendance($query)
    {
        return $query
            ->where('is_active', true)
            ->whereHas(
                'memberPackages',
                fn ($q) => $q->where('is_active', true)->where('end_date', '>=', now())
            );
    }

    /**
     * Translate virtual status to DB-level conditions for filtering.
     */
    public function scopeWithStatus($query, string|StatusMember $status)
{
    $statusValue = $status instanceof StatusMember
        ? $status->value
        : $status;

    return match ($statusValue) {
        StatusMember::STATUS_INACTIVE->value =>
            $query->where(function ($q) {
                $q->where('is_active', false)
                ->orWhere(function ($q2) {
                    $q2->where('is_active', true)
                        ->whereHas('memberPackages') // pernah punya
                        ->whereDoesntHave('memberPackages', function ($q3) {
                            $q3->where('is_active', true)
                                ->where('end_date', '>=', now());
                        });
                });
            }),

        StatusMember::STATUS_ACTIVE->value => 
            $query->where('is_active', true)
                  ->whereHas('memberPackages', function ($q) {
                      $q->where('is_active', true)
                        ->where('end_date', '>=', now());
                  }),

        StatusMember::STATUS_PENDING->value => 
            $query->where('is_active', true)
            ->whereDoesntHave('memberPackages'),

        default => $query->whereRaw('1 = 0'), // ⛔ jangan biarkan lolos
    };
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

    // =========================================================================
    // RELATIONS
    // =========================================================================

    public function memberPackages()
    {
        return $this->hasMany(MemberPackage::class);
    }

    /** Shortcut relation for active, non-expired packages only. */
    public function activePackages()
    {
        return $this->hasMany(MemberPackage::class)
            ->where('is_active', true)
            ->where('end_date', '>=', now());
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

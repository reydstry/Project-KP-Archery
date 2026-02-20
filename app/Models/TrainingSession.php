<?php

namespace App\Models;

use App\Enums\TrainingSessionStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    /**
     * Auto-close cutoff time (local app timezone).
     */
    public const AUTO_CLOSE_HOUR = 18;
    public const AUTO_CLOSE_MINUTE = 0;

    protected $fillable = [
        'date',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => TrainingSessionStatus::class,
        ];
    }

    /**
     * User who created this session (for audit)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Slots (one per session_time)
     */
    public function slots()
    {
        return $this->hasMany(TrainingSessionSlot::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    /**
     * Check if session is open for registration (day-level)
     */
    public function isOpenForRegistration(): bool
    {
        return $this->isBookableAt(now());
    }

    public function isBookableAt(CarbonInterface $now): bool
    {
        if ($this->status !== TrainingSessionStatus::OPEN) {
            return false;
        }

        if ($this->date->isFuture()) {
            return true;
        }

        if (!$this->date->isToday()) {
            return false;
        }

        $cutoff = $this->date
            ->copy()
            ->setTime(self::AUTO_CLOSE_HOUR, self::AUTO_CLOSE_MINUTE, 0);

        return $now->lt($cutoff);
    }

    public function shouldAutoCloseAt(CarbonInterface $now): bool
    {
        if ($this->status !== TrainingSessionStatus::OPEN) {
            return false;
        }

        if ($this->date->isPast() && !$this->date->isToday()) {
            return true;
        }

        if ($this->date->isToday()) {
            $cutoff = $this->date
                ->copy()
                ->setTime(self::AUTO_CLOSE_HOUR, self::AUTO_CLOSE_MINUTE, 0);

            return $now->gte($cutoff);
        }

        return false;
    }

    public function applyAutoClose(CarbonInterface $now): bool
    {
        if (!$this->shouldAutoCloseAt($now)) {
            return false;
        }

        $this->forceFill(['status' => TrainingSessionStatus::CLOSED]);
        $this->save();

        return true;
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Absent extends Model
{
    protected $table = 'absents';

    protected $fillable = [
        'user_id',
        'training_session_id',
        'member_id',
        'status',
        'date',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public const STATUS_HADIR = 'hadir';
    public const STATUS_TIDAK_HADIR = 'tidak hadir';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function trainingSession(): BelongsTo
    {
        return $this->belongsTo(TrainingSession::class);
    }


    public function scopeFilterByMember(Builder $query, int $memberId): Builder
    {
        return $query->where('member_id', $memberId);
    }
}
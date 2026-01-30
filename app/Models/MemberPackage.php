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
        'start_at',
        'end_at',
        'total_sessions',
        'used_sessions',
        'is_active',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'is_active' => 'boolean',
            'validated_at' => 'datetime',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function remainingSessions(): int
    {
        return $this->total_sessions - $this->used_sessions;
    }

    public function hasRemainingSessions(): bool
    {
        return $this->remainingSessions() > 0;
    }
}

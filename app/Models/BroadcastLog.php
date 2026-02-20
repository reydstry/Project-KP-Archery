<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_id',
        'member_id',
        'phone_number',
        'status',
        'response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

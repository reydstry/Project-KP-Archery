<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $table = 'achievements';

    protected $fillable = [
        'member_id',
        'achievement',
        'date',
    ];

    protected $casts = [
        'date_achieved' => 'date:Y-m-d',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

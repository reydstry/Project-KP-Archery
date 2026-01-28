<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingSession extends Model
{
    protected $table = 'training_sessions';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function absents(): HasMany
    {
        return $this->hasMany(Absent::class);
    }
}
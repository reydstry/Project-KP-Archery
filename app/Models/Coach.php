<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get training sessions
     */
    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    /**
     * Session times are global templates (not owned by a coach).
     */
}

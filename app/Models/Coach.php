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
     * Training session slots where this coach is assigned.
     */
    public function trainingSessionSlots()
    {
        return $this->belongsToMany(TrainingSessionSlot::class, 'training_session_slot_coach')
            ->withTimestamps();
    }

    /**
     * Session times are global templates (not owned by a coach).
     */
}

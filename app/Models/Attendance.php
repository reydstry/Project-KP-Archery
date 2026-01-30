<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }
}
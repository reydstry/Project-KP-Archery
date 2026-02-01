<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'member_package_id',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Get the training session
     */
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    /**
     * Get the member package
     */
    public function memberPackage()
    {
        return $this->belongsTo(MemberPackage::class);
    }

    /**
     * Get the checker (who checked in)
     */
    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}

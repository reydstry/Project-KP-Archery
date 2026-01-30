<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registered_by',
        'name',
        'is_self',
        'is_active',
        'email',
    ];

    protected function casts(): array
    {
        return [
            'is_self' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function packages()
    {
        return $this->hasMany(MemberPackage::class);
    }

    public function activePackage()
    {
        return $this->hasOne(MemberPackage::class)
            ->where('is_active', true)
            ->where('end_at', '>=', now())
            ->latest();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function sessionBookings()
    {
        return $this->hasMany(SessionBooking::class);
    }
}

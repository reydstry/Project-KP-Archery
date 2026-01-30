<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
   use HasFactory;

   protected $fillable = [
       'member_id',
       'session_time_id',
       'booking_date',
       'booked_by',
       'status',
       'notes',
   ];

   protected function casts(): array
   {
       return [
           'booking_date' => 'date',
       ];
   }

   public function member()
   {
       return $this->belongsTo(Member::class);
   }

   public function sessionTime()
   {
       return $this->belongsTo(SessionTime::class);
   }

   public function bookedByUser()
   {
        return $this->belongsTo(User::class, 'booked_by');
   }
}

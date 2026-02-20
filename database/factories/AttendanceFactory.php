<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'session_id' => TrainingSession::factory(),
            'member_id' => Member::factory(),
        ];
    }
}

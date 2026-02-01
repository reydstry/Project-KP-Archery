<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\SessionBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'session_booking_id' => SessionBooking::factory(),
            'status' => 'present',
            'validated_by' => User::factory(),
            'validated_at' => now(),
            'notes' => null,
        ];
    }

    public function absent(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'absent',
        ]);
    }

    public function withNotes(string $notes): self
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $notes,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\MemberPackage;
use App\Models\SessionBooking;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionBooking>
 */
class SessionBookingFactory extends Factory
{
    protected $model = SessionBooking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_package_id' => MemberPackage::factory(),
            'training_session_slot_id' => TrainingSessionSlot::factory(),
            'booked_by' => User::factory()->member(),
            'status' => 'confirmed',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the booking is cancelled
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Enums\TrainingSessionStatus;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSession>
 */
class TrainingSessionFactory extends Factory
{
    protected $model = TrainingSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_time_id' => SessionTime::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'coach_id' => Coach::factory(),
            'max_participants' => $this->faker->numberBetween(5, 20),
            'status' => TrainingSessionStatus::OPEN->value,
        ];
    }

    /**
     * Indicate that the session is closed
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TrainingSessionStatus::CLOSED->value,
        ]);
    }

    /**
     * Indicate that the session is canceled
     */
    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TrainingSessionStatus::CANCELED->value,
        ]);
    }

    /**
     * Indicate that the session is in the past
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}

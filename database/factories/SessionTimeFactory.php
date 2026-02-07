<?php

namespace Database\Factories;

use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionTime>
 */
class SessionTimeFactory extends Factory
{
    protected $model = SessionTime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = $this->faker->numberBetween(8, 18);
        
        return [
            'day_of_week' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', $startHour + 2),
            'max_capacity' => $this->faker->numberBetween(10, 30),
            'coach_id' => User::factory()->coach(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the session time is inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

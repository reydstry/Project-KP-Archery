<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Paket ' . fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomElement([200000, 250000, 300000, 350000]),
            'duration_days' => fake()->randomElement([7, 14, 30]),
            'session_count' => fake()->randomElement([4, 8, 10, 12]),
        ];
    }
}

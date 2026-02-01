<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'title' => fake()->randomElement([
                'Juara 1 Kejuaraan Nasional',
                'Juara 2 Kejuaraan Daerah',
                'Juara 3 Kompetisi Internal',
                'Best Archer of The Month',
                'Perunggu Kejuaraan Regional',
            ]),
            'description' => fake()->sentence(10),
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'photo_path' => null,
        ];
    }
}

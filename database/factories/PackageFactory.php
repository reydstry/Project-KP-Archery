<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Paket Basic',
                'Paket Silver',
                'Paket Gold',
                'Paket Platinum',
                'Paket Reguler',
                'Paket Premium',
            ]) . ' ' . $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomElement([
                100000, 150000, 200000, 250000, 300000,
                350000, 400000, 500000, 750000, 1000000,
            ]),
            'duration_days' => $this->faker->randomElement([7, 14, 30, 60, 90]),
            'session_count' => $this->faker->randomElement([4, 8, 12, 16, 20, 24]),
        ];
    }
}

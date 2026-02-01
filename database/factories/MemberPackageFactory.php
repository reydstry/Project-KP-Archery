<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberPackage>
 */
class MemberPackageFactory extends Factory
{
    protected $model = MemberPackage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $durationDays = $this->faker->randomElement([30, 60, 90]);
        $endDate = (clone $startDate)->modify("+{$durationDays} days");

        return [
            'member_id' => Member::factory(),
            'package_id' => Package::factory(),
            'total_sessions' => $this->faker->numberBetween(8, 24),
            'used_sessions' => $this->faker->numberBetween(0, 5),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true,
            'validated_by' => User::factory()->admin(),
            'validated_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Indicate that the package is inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the package is expired
     */
    public function expired(): static
    {
        $startDate = $this->faker->dateTimeBetween('-3 months', '-2 months');
        $endDate = $this->faker->dateTimeBetween('-1 month', '-1 week');
        
        return $this->state(fn (array $attributes) => [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true,
        ]);
    }
}

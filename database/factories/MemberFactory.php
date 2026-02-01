<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $registeredBy = User::factory()->create(['role' => UserRoles::MEMBER]);

        return [
            'user_id' => $user->id,
            'registered_by' => $registeredBy->id,
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'is_self' => true,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that this is a child member (registered by parent)
     */
    public function child()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_self' => false,
                'name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            ];
        });
    }

    /**
     * Indicate that this is an inactive member
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}

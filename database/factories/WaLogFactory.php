<?php

namespace Database\Factories;

use App\Models\WaLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaLogFactory extends Factory
{
    protected $model = WaLog::class;

    public function definition(): array
    {
        return [
            'phone' => $this->faker->numerify('62812########'),
            'message' => $this->faker->sentence(),
            'response' => json_encode(['message' => 'ok']),
            'status' => $this->faker->randomElement(['success', 'failed', 'scheduled']),
            'sent_at' => now(),
        ];
    }
}

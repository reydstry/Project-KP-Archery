<?php

namespace Database\Factories;

use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingSessionSlot>
 */
class TrainingSessionSlotFactory extends Factory
{
    protected $model = TrainingSessionSlot::class;

    public function definition(): array
    {
        return [
            'training_session_id' => TrainingSession::factory(),
            'session_time_id' => SessionTime::factory(),
            'max_participants' => $this->faker->numberBetween(5, 30),
        ];
    }
}

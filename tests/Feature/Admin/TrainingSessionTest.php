<?php

namespace Tests\Feature\Admin;

use App\Enums\TrainingSessionStatus;
use App\Enums\UserRoles;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_training_session_for_any_coach(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $date = now()->addDays(3)->format('Y-m-d');

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/training-sessions', [
                'coach_id' => $coach->id,
                'date' => $date,
                'slots' => [
                    [
                        'session_time_id' => $sessionTime->id,
                        'max_participants' => 12,
                    ],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Training session created successfully',
            ])
            ->assertJsonPath('data.status', TrainingSessionStatus::OPEN->value);

        $this->assertDatabaseHas('training_sessions', [
            'coach_id' => $coach->id,
            'date' => $date,
            'status' => TrainingSessionStatus::OPEN->value,
        ]);

        $trainingSession = TrainingSession::where('coach_id', $coach->id)
            ->where('date', $date)
            ->firstOrFail();

        $this->assertDatabaseHas('training_session_slots', [
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 12,
        ]);
    }
}

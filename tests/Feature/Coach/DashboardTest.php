<?php

namespace Tests\Feature\Coach;

use App\Enums\TrainingSessionStatus;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_view_coach_dashboard(): void
    {
        $coachUser = User::factory()->coach()->create();
        $coachProfile = Coach::factory()->create(['user_id' => $coachUser->id]);

        $trainingSession = TrainingSession::create([
            'created_by' => $coachUser->id,
            'date' => now()->toDateString(),
            'status' => TrainingSessionStatus::OPEN->value,
        ]);

        $sessionTimes = SessionTime::factory()->count(2)->create();
        foreach ($sessionTimes as $sessionTime) {
            $slot = TrainingSessionSlot::create([
                'training_session_id' => $trainingSession->id,
                'session_time_id' => $sessionTime->id,
                'max_participants' => 10,
            ]);
            $slot->coaches()->attach($coachProfile->id);
        }

        $response = $this->actingAs($coachUser, 'sanctum')
            ->getJson('/api/coach/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'coach' => ['id', 'name', 'phone'],
                'statistics' => ['today_sessions', 'upcoming_sessions', 'total_sessions'],
                'today_sessions',
            ])
            ->assertJsonPath('statistics.today_sessions', 2);
    }
}

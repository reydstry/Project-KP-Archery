<?php

namespace Tests\Feature\Coach;

use App\Enums\TrainingSessionStatus;
use App\Models\Coach;
use App\Models\TrainingSession;
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

        TrainingSession::factory()->count(2)->create([
            'coach_id' => $coachProfile->id,
            'date' => now()->toDateString(),
            'status' => TrainingSessionStatus::OPEN->value,
        ]);

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

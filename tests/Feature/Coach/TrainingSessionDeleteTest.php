<?php

namespace Tests\Feature\Coach;

use App\Models\Attendance;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingSessionDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_delete_training_session_without_attendance_records()
    {
        $coach = Coach::factory()->create();

        $trainingSession = TrainingSession::factory()->create([
            'created_by' => $coach->user_id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $sessionTime = SessionTime::factory()->create();
        $slot = TrainingSessionSlot::create([
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);
        $slot->coaches()->attach($coach->id);

        $response = $this->actingAs($coach->user, 'sanctum')
            ->deleteJson('/api/coach/training-sessions/' . $trainingSession->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Training session deleted successfully',
            ]);

        $this->assertDatabaseMissing('training_sessions', [
            'id' => $trainingSession->id,
        ]);
    }

    public function test_coach_cannot_delete_training_session_with_attendance_records()
    {
        $coach = Coach::factory()->create();

        $trainingSession = TrainingSession::factory()->create([
            'created_by' => $coach->user_id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $sessionTime = SessionTime::factory()->create();
        $slot = TrainingSessionSlot::create([
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);
        $slot->coaches()->attach($coach->id);

        Attendance::factory()->create([
            'session_id' => $trainingSession->id,
        ]);

        $response = $this->actingAs($coach->user, 'sanctum')
            ->deleteJson('/api/coach/training-sessions/' . $trainingSession->id);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cannot delete session that already has attendance records',
            ]);

        $this->assertDatabaseHas('training_sessions', [
            'id' => $trainingSession->id,
        ]);
    }

    public function test_coach_cannot_delete_other_coach_training_session()
    {
        $coachA = Coach::factory()->create();
        $coachB = Coach::factory()->create();

        $trainingSession = TrainingSession::factory()->create([
            'created_by' => $coachB->user_id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $sessionTime = SessionTime::factory()->create();
        $slot = TrainingSessionSlot::create([
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);
        $slot->coaches()->attach($coachB->id);

        $response = $this->actingAs($coachA->user, 'sanctum')
            ->deleteJson('/api/coach/training-sessions/' . $trainingSession->id);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized',
            ]);
    }
}

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

class TrainingSessionTest extends TestCase
{
    use RefreshDatabase;

    private User $coach;
    private User $admin;
    private User $member;
    private Coach $coachProfile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coach = User::factory()->coach()->create();
        $this->admin = User::factory()->admin()->create();
        $this->member = User::factory()->member()->create();
        
        // Create coach profile
        $this->coachProfile = Coach::factory()->create([
            'user_id' => $this->coach->id,
        ]);
    }

    public function test_coach_can_create_training_session()
    {
        $sessionTime = SessionTime::factory()->create();
        $date = now()->addDays(7)->format('Y-m-d');

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => $date,
                'max_participants' => 15,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Training session created successfully',
            ])
            ->assertJsonPath('data.status', 'open')
            ->assertJsonPath('data.slots.0.max_participants', 15)
            ->assertJsonPath('data.slots.0.session_time_id', $sessionTime->id);

        $this->assertDatabaseHas('training_sessions', [
            'coach_id' => $this->coachProfile->id,
            'date' => $date,
            'status' => 'open',
        ]);

        $trainingSession = TrainingSession::where('coach_id', $this->coachProfile->id)
            ->where('date', $date)
            ->firstOrFail();

        $this->assertDatabaseHas('training_session_slots', [
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 15,
        ]);
    }

    public function test_coach_cannot_create_duplicate_session()
    {
        $sessionTime = SessionTime::factory()->create();
        $date = now()->addDays(7)->format('Y-m-d');

        // Create first slot via API
        $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => $date,
                'max_participants' => 15,
            ])
            ->assertStatus(201);

        // Try to create duplicate slot for the same date+time
        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => $date,
                'max_participants' => 15,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Training session slot already exists for this date and time',
            ]);
    }

    public function test_coach_can_view_their_training_sessions()
    {
        // Ensure dates are unique per coach
        TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'date' => now()->addDays(1)->format('Y-m-d'),
        ]);
        TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
        ]);
        TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->getJson('/api/coach/training-sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'date',
                        'coach_id',
                        'status',
                        'slots',
                    ]
                ]
            ]);
    }

    public function test_coach_can_view_single_training_session()
    {
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->getJson("/api/coach/training-sessions/{$session->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $session->id);
    }

    public function test_coach_cannot_view_other_coach_session()
    {
        $otherCoach = Coach::factory()->create();
        $session = TrainingSession::factory()->create([
            'coach_id' => $otherCoach->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->getJson("/api/coach/training-sessions/{$session->id}");

        $response->assertStatus(403);
    }

    public function test_coach_can_update_session_quota()
    {
        $sessionTime = SessionTime::factory()->create();
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'date' => now()->addDays(10)->format('Y-m-d'),
        ]);

        /** @var TrainingSessionSlot $slot */
        $slot = $session->slots()->where('session_time_id', $sessionTime->id)->first();
        if (!$slot) {
            $slot = TrainingSessionSlot::create([
                'training_session_id' => $session->id,
                'session_time_id' => $sessionTime->id,
                'max_participants' => 10,
            ]);
        } else {
            $slot->update(['max_participants' => 10]);
        }

        $response = $this->actingAs($this->coach, 'sanctum')
            ->patchJson("/api/coach/training-sessions/{$session->id}/quota", [
                'slot_id' => $slot->id,
                'max_participants' => 20,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Quota updated successfully',
            ])
            ->assertJsonFragment([
                'id' => $slot->id,
                'max_participants' => 20,
            ]);

        $this->assertDatabaseHas('training_session_slots', [
            'id' => $slot->id,
            'max_participants' => 20,
        ]);
    }

    public function test_coach_can_open_closed_session()
    {
        $session = TrainingSession::factory()->closed()->create([
            'coach_id' => $this->coachProfile->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/coach/training-sessions/{$session->id}/open");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Training session opened successfully',
            ])
            ->assertJsonPath('data.status', 'open');

        $this->assertDatabaseHas('training_sessions', [
            'id' => $session->id,
            'status' => 'open',
        ]);
    }

    public function test_coach_cannot_open_already_open_session()
    {
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'status' => TrainingSessionStatus::OPEN->value,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/coach/training-sessions/{$session->id}/open");

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Session is already open',
            ]);
    }

    public function test_coach_can_close_open_session()
    {
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'status' => TrainingSessionStatus::OPEN->value,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/coach/training-sessions/{$session->id}/close");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Training session closed successfully',
            ])
            ->assertJsonPath('data.status', 'closed');

        $this->assertDatabaseHas('training_sessions', [
            'id' => $session->id,
            'status' => 'closed',
        ]);
    }

    public function test_coach_cannot_close_already_closed_session()
    {
        $session = TrainingSession::factory()->closed()->create([
            'coach_id' => $this->coachProfile->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/coach/training-sessions/{$session->id}/close");

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Session is already closed',
            ]);
    }

    public function test_coach_can_cancel_session()
    {
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/coach/training-sessions/{$session->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Training session canceled successfully',
            ])
            ->assertJsonPath('data.status', 'canceled');

        $this->assertDatabaseHas('training_sessions', [
            'id' => $session->id,
            'status' => 'canceled',
        ]);
    }

    public function test_member_cannot_create_training_session()
    {
        $sessionTime = SessionTime::factory()->create();

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => now()->addDays(7)->format('Y-m-d'),
                'max_participants' => 15,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_create_training_session()
    {
        $sessionTime = SessionTime::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => now()->addDays(7)->format('Y-m-d'),
                'max_participants' => 15,
            ]);

        $response->assertStatus(403);
    }

    public function test_creating_session_requires_valid_session_time()
    {
        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => 99999,
                'date' => now()->addDays(7)->format('Y-m-d'),
                'max_participants' => 15,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('session_time_id');
    }

    public function test_creating_session_requires_future_date()
    {
        $sessionTime = SessionTime::factory()->create();

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => now()->subDays(1)->format('Y-m-d'),
                'max_participants' => 15,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('date');
    }

    public function test_creating_session_requires_valid_quota()
    {
        $sessionTime = SessionTime::factory()->create();

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => now()->addDays(7)->format('Y-m-d'),
                'max_participants' => 0,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('max_participants');
    }
}

<?php

namespace Tests\Feature\Coach;

use App\Enums\TrainingSessionStatus;
use App\Models\Coach;
use App\Models\SessionTime;
use App\Models\TrainingSession;
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

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => now()->addDays(7)->format('Y-m-d'),
                'max_participants' => 15,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Training session created successfully',
            ])
            ->assertJsonPath('data.max_participants', 15)
            ->assertJsonPath('data.status', 'open');

        $this->assertDatabaseHas('training_sessions', [
            'session_time_id' => $sessionTime->id,
            'coach_id' => $this->coachProfile->id,
            'max_participants' => 15,
            'status' => 'open',
        ]);
    }

    public function test_coach_cannot_create_duplicate_session()
    {
        $sessionTime = SessionTime::factory()->create();
        $date = now()->addDays(7)->format('Y-m-d');

        // Create first session
        TrainingSession::factory()->create([
            'session_time_id' => $sessionTime->id,
            'date' => $date,
            'coach_id' => $this->coachProfile->id,
        ]);

        // Try to create duplicate
        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson('/api/coach/training-sessions', [
                'session_time_id' => $sessionTime->id,
                'date' => $date,
                'max_participants' => 15,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Training session already exists for this date and time',
            ]);
    }

    public function test_coach_can_view_their_training_sessions()
    {
        TrainingSession::factory()->count(3)->create([
            'coach_id' => $this->coachProfile->id,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->getJson('/api/coach/training-sessions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'session_time_id',
                        'date',
                        'coach_id',
                        'max_participants',
                        'status',
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
        $session = TrainingSession::factory()->create([
            'coach_id' => $this->coachProfile->id,
            'max_participants' => 10,
        ]);

        $response = $this->actingAs($this->coach, 'sanctum')
            ->patchJson("/api/coach/training-sessions/{$session->id}/quota", [
                'max_participants' => 20,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Quota updated successfully',
            ])
            ->assertJsonPath('data.max_participants', 20);

        $this->assertDatabaseHas('training_sessions', [
            'id' => $session->id,
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

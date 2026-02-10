<?php

namespace Tests\Feature\Coach;

use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionBooking;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingSessionDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_can_delete_training_session_without_bookings()
    {
        $coach = Coach::factory()->create();

        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $sessionTime = SessionTime::factory()->create();
        TrainingSessionSlot::create([
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);

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

    public function test_coach_cannot_delete_training_session_with_bookings()
    {
        $coach = Coach::factory()->create();

        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $sessionTime = SessionTime::factory()->create();
        $slot = TrainingSessionSlot::create([
            'training_session_id' => $trainingSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);

        $member = Member::factory()->create();
        $package = Package::factory()->create([
            'session_count' => 10,
            'duration_days' => 30,
        ]);

        $memberPackage = MemberPackage::factory()->create([
            'member_id' => $member->id,
            'package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        SessionBooking::factory()->create([
            'member_package_id' => $memberPackage->id,
            'training_session_slot_id' => $slot->id,
            'booked_by' => $coach->user->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user, 'sanctum')
            ->deleteJson('/api/coach/training-sessions/' . $trainingSession->id);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cannot delete session that already has bookings',
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
            'coach_id' => $coachB->id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        $response = $this->actingAs($coachA->user, 'sanctum')
            ->deleteJson('/api/coach/training-sessions/' . $trainingSession->id);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Unauthorized',
            ]);
    }
}

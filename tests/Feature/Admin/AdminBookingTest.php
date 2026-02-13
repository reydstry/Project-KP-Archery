<?php

namespace Tests\Feature\Admin;

use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_book_session_for_member()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $coach = Coach::factory()->create();

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

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/bookings', [
                'training_session_slot_id' => $slot->id,
                'member_package_id' => $memberPackage->id,
                'notes' => 'Booked by admin',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Session booked successfully',
            ])
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('session_bookings', [
            'member_package_id' => $memberPackage->id,
            'training_session_slot_id' => $slot->id,
            'booked_by' => $admin->id,
            'status' => 'confirmed',
        ]);
    }
}

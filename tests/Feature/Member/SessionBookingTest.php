<?php

namespace Tests\Feature\Member;

use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionBooking;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\TrainingSessionSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionBookingTest extends TestCase
{
    use RefreshDatabase;

    private User $member;
    private User $admin;
    private Member $memberProfile;
    private MemberPackage $memberPackage;
    private TrainingSession $trainingSession;
    private TrainingSessionSlot $trainingSessionSlot;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->member = User::factory()->member()->create();
        $this->admin = User::factory()->admin()->create();
        
        // Create member profile
        $this->memberProfile = Member::factory()->create([
            'user_id' => $this->member->id,
            'is_self' => true,
            'status' => 'active',
        ]);
        
        // Create active package
        $package = Package::factory()->create([
            'session_count' => 10,
            'duration_days' => 30,
        ]);
        
        $this->memberPackage = MemberPackage::factory()->create([
            'member_id' => $this->memberProfile->id,
            'package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);
        
        // Create open training session
        $sessionTime = SessionTime::factory()->create();
        $coach = Coach::factory()->create();
        
        $this->trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'date' => now()->addDays(7),
            'status' => 'open',
        ]);

        $slot = $this->trainingSession->slots()->where('session_time_id', $sessionTime->id)->first();
        if (!$slot) {
            $slot = TrainingSessionSlot::create([
                'training_session_id' => $this->trainingSession->id,
                'session_time_id' => $sessionTime->id,
                'max_participants' => 10,
            ]);
        } else {
            $slot->update(['max_participants' => 10]);
        }

        $this->trainingSessionSlot = $slot;
    }

    public function test_member_can_book_training_session()
    {
        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
                'notes' => 'First booking',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Session booked successfully',
            ])
            ->assertJsonPath('data.status', 'confirmed')
            ->assertJsonPath('remaining_sessions', 10); // Quota not deducted until attendance marked as present

        $this->assertDatabaseHas('session_bookings', [
            'member_package_id' => $this->memberPackage->id,
            'training_session_slot_id' => $this->trainingSessionSlot->id,
            'status' => 'confirmed',
        ]);

        // Verify used_sessions not incremented (only deducted when attendance marked as present)
        $this->memberPackage->refresh();
        $this->assertEquals(0, $this->memberPackage->used_sessions);
    }

    public function test_member_cannot_book_without_active_package()
    {
        $this->memberPackage->update(['is_active' => false]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Your package is not active',
            ]);
    }

    public function test_member_cannot_book_with_expired_package()
    {
        $this->memberPackage->update([
            'end_date' => now()->subDays(1),
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Your package has expired',
            ]);
    }

    public function test_member_cannot_book_without_remaining_sessions()
    {
        $this->memberPackage->update([
            'total_sessions' => 5,
            'used_sessions' => 5,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'No remaining sessions in your package',
            ]);
    }

    public function test_member_cannot_book_closed_session()
    {
        $this->trainingSession->update(['status' => 'closed']);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Training session is not open for booking',
            ]);
    }

    public function test_member_cannot_book_past_session()
    {
        $this->trainingSession->update(['date' => now()->subDays(1)]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cannot book past sessions',
            ]);
    }

    public function test_member_cannot_book_full_session()
    {
        $this->trainingSessionSlot->update(['max_participants' => 2]);

        // Create 2 different packages with bookings to fill the session
        $otherMember1 = Member::factory()->create();
        $otherPackage1 = MemberPackage::factory()->create([
            'member_id' => $otherMember1->id,
            'is_active' => true,
            'total_sessions' => 10,
            'used_sessions' => 0,
        ]);

        $otherMember2 = Member::factory()->create();
        $otherPackage2 = MemberPackage::factory()->create([
            'member_id' => $otherMember2->id,
            'is_active' => true,
            'total_sessions' => 10,
            'used_sessions' => 0,
        ]);

        SessionBooking::factory()->create([
            'training_session_slot_id' => $this->trainingSessionSlot->id,
            'member_package_id' => $otherPackage1->id,
            'status' => 'confirmed',
        ]);

        SessionBooking::factory()->create([
            'training_session_slot_id' => $this->trainingSessionSlot->id,
            'member_package_id' => $otherPackage2->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Training session is full',
            ]);
    }

    public function test_member_cannot_book_same_session_twice()
    {
        // First booking
        SessionBooking::factory()->create([
            'member_package_id' => $this->memberPackage->id,
            'training_session_slot_id' => $this->trainingSessionSlot->id,
            'status' => 'confirmed',
        ]);

        // Try to book again
        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'You have already booked this session',
            ]);
    }

    public function test_member_can_view_their_bookings()
    {
        SessionBooking::factory()->count(3)->create([
            'member_package_id' => $this->memberPackage->id,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->getJson('/api/member/bookings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'member_package_id',
                        'training_session_slot_id',
                        'status',
                    ]
                ]
            ]);
    }

    public function test_member_can_view_single_booking()
    {
        $booking = SessionBooking::factory()->create([
            'member_package_id' => $this->memberPackage->id,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->getJson("/api/member/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $booking->id);
    }

    public function test_member_cannot_view_other_member_booking()
    {
        $otherPackage = MemberPackage::factory()->create();
        $booking = SessionBooking::factory()->create([
            'member_package_id' => $otherPackage->id,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->getJson("/api/member/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_member_can_cancel_booking()
    {
        $booking = SessionBooking::factory()->create([
            'member_package_id' => $this->memberPackage->id,
            'training_session_slot_id' => $this->trainingSessionSlot->id,
            'status' => 'confirmed',
        ]);

        $this->memberPackage->update(['used_sessions' => 1]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson("/api/member/bookings/{$booking->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Booking cancelled successfully',
            ])
            ->assertJsonPath('data.status', 'cancelled');

        $this->assertDatabaseHas('session_bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);

        // Verify used_sessions remains unchanged (quota wasn't deducted on booking)
        $this->memberPackage->refresh();
        $this->assertEquals(1, $this->memberPackage->used_sessions);
    }

    public function test_member_cannot_cancel_already_cancelled_booking()
    {
        $booking = SessionBooking::factory()->cancelled()->create([
            'member_package_id' => $this->memberPackage->id,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson("/api/member/bookings/{$booking->id}/cancel");

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Booking is already cancelled',
            ]);
    }

    public function test_member_cannot_cancel_past_booking()
    {
        $pastSession = TrainingSession::factory()->create([
            'date' => now()->subDays(1),
        ]);

        $sessionTime = SessionTime::factory()->create();
        $pastSlot = TrainingSessionSlot::create([
            'training_session_id' => $pastSession->id,
            'session_time_id' => $sessionTime->id,
            'max_participants' => 10,
        ]);

        $booking = SessionBooking::factory()->create([
            'member_package_id' => $this->memberPackage->id,
            'training_session_slot_id' => $pastSlot->id,
        ]);

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson("/api/member/bookings/{$booking->id}/cancel");

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cannot cancel past sessions',
            ]);
    }

    public function test_admin_cannot_book_session()
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(403);
    }

    public function test_booking_requires_valid_training_session()
    {
        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => 99999,
                'member_package_id' => $this->memberPackage->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('training_session_slot_id');
    }

    public function test_booking_requires_valid_member_package()
    {
        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson('/api/member/bookings', [
                'training_session_slot_id' => $this->trainingSessionSlot->id,
                'member_package_id' => 99999,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('member_package_id');
    }
}

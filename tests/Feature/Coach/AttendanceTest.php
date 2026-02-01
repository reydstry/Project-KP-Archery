<?php

namespace Tests\Feature\Coach;

use App\Models\Attendance;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\SessionBooking;
use App\Models\SessionTime;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function coach_can_get_session_bookings_with_attendance_status()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
            'date' => now()->addDays(1),
            'status' => 'open',
        ]);

        // Create bookings
        $booking1 = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);
        $booking2 = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        // Create attendance for first booking
        Attendance::factory()->create([
            'session_booking_id' => $booking1->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);

        $response = $this->actingAs($coach->user)
            ->getJson("/api/coach/training-sessions/{$trainingSession->id}/bookings");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'session',
                'bookings' => [
                    '*' => [
                        'id',
                        'member_name',
                        'member_id',
                        'has_attendance',
                        'attendance_status',
                        'validated_at',
                        'notes',
                    ]
                ],
                'total_bookings',
                'attended',
                'absent',
                'not_validated',
            ])
            ->assertJson([
                'total_bookings' => 2,
                'attended' => 1,
                'absent' => 0,
                'not_validated' => 1,
            ]);
    }

    public function coach_cannot_view_other_coach_session_bookings()
    {
        $coach1 = Coach::factory()->create();
        $coach2 = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach1->id,
            'session_time_id' => $sessionTime->id,
        ]);

        $response = $this->actingAs($coach2->user)
            ->getJson("/api/coach/training-sessions/{$trainingSession->id}/bookings");

        $response->assertStatus(403)
            ->assertJson(['message' => 'You can only view your own training sessions']);
    }

    public function coach_can_validate_attendance_as_present()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
                'notes' => 'On time',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Attendance validated successfully',
                'attendance' => [
                    'booking_id' => $booking->id,
                    'status' => 'present',
                    'notes' => 'On time',
                ]
            ]);

        $this->assertDatabaseHas('attendances', [
            'session_booking_id' => $booking->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);
    }

    public function coach_can_validate_attendance_as_absent()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'absent',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Attendance validated successfully',
                'attendance' => [
                    'booking_id' => $booking->id,
                    'status' => 'absent',
                ]
            ]);

        $this->assertDatabaseHas('attendances', [
            'session_booking_id' => $booking->id,
            'status' => 'absent',
        ]);
    }

    public function coach_cannot_validate_other_coach_session_attendance()
    {
        $coach1 = Coach::factory()->create();
        $coach2 = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach1->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
        ]);

        $response = $this->actingAs($coach2->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'You can only validate attendance for your own training sessions']);
    }

    public function coach_cannot_validate_cancelled_booking()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Can only validate attendance for confirmed bookings']);
    }

    public function coach_cannot_validate_attendance_twice()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        // First validation
        Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'validated_by' => $coach->user_id,
        ]);

        // Try to validate again
        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Attendance already validated for this booking']);
    }

    public function coach_can_update_attendance_status()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);
        $attendance = Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);

        $response = $this->actingAs($coach->user)
            ->patchJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'absent',
                'notes' => 'Changed to absent - no show',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Attendance updated successfully',
                'attendance' => [
                    'status' => 'absent',
                    'notes' => 'Changed to absent - no show',
                ]
            ]);

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'status' => 'absent',
        ]);
    }

    public function coach_cannot_update_non_existent_attendance()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->patchJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'absent',
            ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Attendance not found for this booking']);
    }

    public function validate_attendance_requires_valid_status()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function member_cannot_validate_attendance()
    {
        $member = Member::factory()->create();
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
        ]);

        $response = $this->actingAs($member->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(403);
    }

    public function admin_cannot_validate_attendance()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(403);
    }

    public function attendance_records_validator_and_timestamp()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $attendance = Attendance::where('session_booking_id', $booking->id)->first();
        
        $this->assertNotNull($attendance);
        $this->assertEquals($coach->user_id, $attendance->validated_by);
        $this->assertNotNull($attendance->validated_at);
    }

    
    public function marking_attendance_as_present_deducts_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 0,
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('remaining_sessions', 9);

        $memberPackage->refresh();
        $this->assertEquals(1, $memberPackage->used_sessions);
    }

    
    public function marking_attendance_as_absent_does_not_deduct_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 0,
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'absent',
            ]);

        $response->assertStatus(201);

        $memberPackage->refresh();
        $this->assertEquals(0, $memberPackage->used_sessions);
    }

    
    public function cannot_mark_attendance_as_present_when_no_remaining_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 10, // No remaining quota
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($coach->user)
            ->postJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Member has no remaining sessions in package']);

        $this->assertDatabaseMissing('attendances', [
            'session_booking_id' => $booking->id,
        ]);
    }

    
    public function updating_attendance_from_absent_to_present_deducts_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 0,
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        // Mark as absent first
        $attendance = Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'status' => 'absent',
            'validated_by' => $coach->user_id,
        ]);

        // Update to present
        $response = $this->actingAs($coach->user)
            ->patchJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('remaining_sessions', 9);

        $memberPackage->refresh();
        $this->assertEquals(1, $memberPackage->used_sessions);
    }

    
    public function updating_attendance_from_present_to_absent_refunds_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 1,
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        // Mark as present first
        $attendance = Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);

        // Update to absent
        $response = $this->actingAs($coach->user)
            ->patchJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'absent',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('remaining_sessions', 10);

        $memberPackage->refresh();
        $this->assertEquals(0, $memberPackage->used_sessions);
    }

    
    public function cannot_update_attendance_to_present_when_no_remaining_quota()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        
        $memberPackage = MemberPackage::factory()->create([
            'total_sessions' => 10,
            'used_sessions' => 10, // No remaining quota
        ]);
        
        $booking = SessionBooking::factory()->create([
            'training_session_id' => $trainingSession->id,
            'member_package_id' => $memberPackage->id,
            'status' => 'confirmed',
        ]);

        // Mark as absent first
        $attendance = Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'status' => 'absent',
            'validated_by' => $coach->user_id,
        ]);

        // Try to update to present
        $response = $this->actingAs($coach->user)
            ->patchJson("/api/coach/bookings/{$booking->id}/attendance", [
                'status' => 'present',
            ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Member has no remaining sessions in package']);

        $attendance->refresh();
        $this->assertEquals('absent', $attendance->status);
    }
}

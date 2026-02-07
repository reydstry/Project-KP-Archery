<?php

namespace Tests\Feature\Member;

use App\Models\Achievement;
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

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'member']);
        $this->member = Member::factory()->create([
            'user_id' => $this->user->id,
            'is_self' => true,
            'status' => 'active',
        ]);
    }

    public function test_member_can_view_dashboard()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'member' => [
                    'id',
                    'name',
                    'status',
                ],
                'quota',
                'attendance' => [
                    'history',
                    'statistics',
                ],
                'achievements',
            ]);
    }

    public function test_dashboard_shows_remaining_quota()
    {
        $package = Package::factory()->create([
            'session_count' => 10,
        ]);

        $memberPackage = MemberPackage::factory()->create([
            'member_id' => $this->member->id,
            'package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 3,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('quota.package_name', $package->name)
            ->assertJsonPath('quota.total_sessions', 10)
            ->assertJsonPath('quota.used_sessions', 3)
            ->assertJsonPath('quota.remaining_sessions', 7);
    }

    public function test_dashboard_shows_null_quota_when_no_active_package()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('quota', null);
    }

    public function test_dashboard_shows_attendance_history()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $package = Package::factory()->create();

        $memberPackage = MemberPackage::factory()->create([
            'member_id' => $this->member->id,
            'package_id' => $package->id,
            'is_active' => true,
        ]);

        $trainingSession = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
            'date' => now()->subDays(1),
        ]);

        $booking = SessionBooking::factory()->create([
            'member_package_id' => $memberPackage->id,
            'training_session_id' => $trainingSession->id,
            'status' => 'confirmed',
        ]);

        $attendance = Attendance::factory()->create([
            'session_booking_id' => $booking->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'attendance.history')
            ->assertJsonPath('attendance.history.0.attendance_status', 'present')
            ->assertJsonPath('attendance.statistics.total_attended', 1)
            ->assertJsonPath('attendance.statistics.total_absent', 0);
    }

    public function test_dashboard_shows_attendance_statistics()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $package = Package::factory()->create();

        $memberPackage = MemberPackage::factory()->create([
            'member_id' => $this->member->id,
            'package_id' => $package->id,
            'is_active' => true,
        ]);

        // Create 3 bookings with attendance
        for ($i = 0; $i < 3; $i++) {
            $trainingSession = TrainingSession::factory()->create([
                'coach_id' => $coach->id,
                'session_time_id' => $sessionTime->id,
                'date' => now()->subDays($i + 1),
            ]);

            $booking = SessionBooking::factory()->create([
                'member_package_id' => $memberPackage->id,
                'training_session_id' => $trainingSession->id,
                'status' => 'confirmed',
            ]);

            Attendance::factory()->create([
                'session_booking_id' => $booking->id,
                'status' => $i < 2 ? 'present' : 'absent',
                'validated_by' => $coach->user_id,
            ]);
        }

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('attendance.statistics.total_attended', 2)
            ->assertJsonPath('attendance.statistics.total_absent', 1);
    }

    public function test_dashboard_shows_achievements()
    {
        $achievement1 = Achievement::factory()->create([
            'member_id' => $this->member->id,
            'title' => 'Juara 1 Nasional',
            'description' => 'Kompetisi Panahan Nasional 2026',
            'date' => now()->subMonths(1),
        ]);

        $achievement2 = Achievement::factory()->create([
            'member_id' => $this->member->id,
            'title' => 'Juara 2 Regional',
            'description' => 'Kompetisi Regional Jawa Barat',
            'date' => now()->subMonths(2),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'achievements')
            ->assertJsonPath('achievements.0.title', 'Juara 1 Nasional')
            ->assertJsonPath('achievements.0.description', 'Kompetisi Panahan Nasional 2026')
            ->assertJsonPath('achievements.1.title', 'Juara 2 Regional');
    }

    public function test_dashboard_returns_error_if_member_not_registered()
    {
        $userWithoutMember = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($userWithoutMember, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Member profile not found. Please register as member first.',
            ]);
    }

    public function test_only_shows_attendance_history_with_attendance_records()
    {
        $coach = Coach::factory()->create();
        $sessionTime = SessionTime::factory()->create();
        $package = Package::factory()->create();

        $memberPackage = MemberPackage::factory()->create([
            'member_id' => $this->member->id,
            'package_id' => $package->id,
            'is_active' => true,
        ]);

        // Booking with attendance
        $trainingSession1 = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        $booking1 = SessionBooking::factory()->create([
            'member_package_id' => $memberPackage->id,
            'training_session_id' => $trainingSession1->id,
        ]);
        Attendance::factory()->create([
            'session_booking_id' => $booking1->id,
            'status' => 'present',
            'validated_by' => $coach->user_id,
        ]);

        // Booking without attendance
        $trainingSession2 = TrainingSession::factory()->create([
            'coach_id' => $coach->id,
            'session_time_id' => $sessionTime->id,
        ]);
        SessionBooking::factory()->create([
            'member_package_id' => $memberPackage->id,
            'training_session_id' => $trainingSession2->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'attendance.history'); // Only booking with attendance
    }

    public function test_coach_cannot_access_member_dashboard()
    {
        $coach = Coach::factory()->create();

        $response = $this->actingAs($coach->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_member_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(403);
    }
}

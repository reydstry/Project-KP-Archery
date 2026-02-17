<?php

namespace Tests\Feature\Member;

use App\Models\Achievement;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
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
                'member' => ['id', 'name', 'status'],
                'quota',
                'attendance' => ['history', 'statistics'],
                'achievements',
            ]);
    }

    public function test_dashboard_shows_remaining_quota()
    {
        $package = Package::factory()->create([
            'session_count' => 10,
        ]);

        MemberPackage::factory()->create([
            'member_id' => $this->member->id,
            'package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 3,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('quota.total_sessions', 10)
            ->assertJsonPath('quota.used_sessions', 3)
            ->assertJsonPath('quota.remaining_sessions', 7);
    }

    public function test_dashboard_shows_attendance_history_and_statistics()
    {
        Attendance::factory()->create([
            'member_id' => $this->member->id,
            'session_id' => TrainingSession::factory()->create([
                'date' => now()->subDay(),
                'status' => 'closed',
            ])->id,
        ]);

        Attendance::factory()->create([
            'member_id' => $this->member->id,
            'session_id' => TrainingSession::factory()->create([
                'date' => now()->subDays(2),
                'status' => 'closed',
            ])->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('attendance.statistics.total_attended', 2)
            ->assertJsonPath('attendance.statistics.total_absent', 0)
            ->assertJsonCount(2, 'attendance.history');
    }

    public function test_dashboard_shows_member_achievements_only()
    {
        Achievement::factory()->count(2)->create([
            'member_id' => $this->member->id,
            'type' => 'member',
        ]);

        $otherMember = Member::factory()->create();
        Achievement::factory()->create([
            'member_id' => $otherMember->id,
            'type' => 'member',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/member/dashboard');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'achievements');
    }
}

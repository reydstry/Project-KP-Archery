<?php

namespace Tests\Feature\Admin;

use App\Enums\StatusMember;
use App\Models\Member;
use App\Models\MemberPackage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPackageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $member;
    private User $coach;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->member = User::factory()->member()->create();
        $this->coach = User::factory()->coach()->create();
    }

    public function test_admin_can_view_all_member_packages()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->create();
        
        MemberPackage::factory()->create([
            'member_id' => $member->id,
            'package_id' => $package->id,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/admin/member-packages');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'member_id',
                        'package_id',
                        'total_sessions',
                        'used_sessions',
                        'start_date',
                        'end_date',
                        'is_active',
                    ]
                ]
            ]);
    }

    public function test_admin_can_assign_package_to_pending_member()
    {
        $package = Package::factory()->create([
            'duration_days' => 30,
            'session_count' => 12,
        ]);
        
        $member = Member::factory()->pending()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Package assigned successfully',
            ])
            ->assertJsonPath('data.total_sessions', 12)
            ->assertJsonPath('data.used_sessions', 0)
            ->assertJsonPath('data.is_active', true);

        // Verify member package created
        $this->assertDatabaseHas('member_packages', [
            'member_id' => $member->id,
            'package_id' => $package->id,
            'total_sessions' => 12,
            'used_sessions' => 0,
            'is_active' => true,
        ]);
    }

    public function test_assigning_package_changes_member_status_from_pending_to_active()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->pending()->create();

        $this->assertEquals('pending', $member->status);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        // Verify member status changed to active
        $member->refresh();
        $this->assertEquals('active', $member->status);
    }

    public function test_package_generates_correct_quota_from_package_session_count()
    {
        $package = Package::factory()->create(['session_count' => 20]);
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        // Verify total_sessions equals package session_count
        $this->assertDatabaseHas('member_packages', [
            'member_id' => $member->id,
            'package_id' => $package->id,
            'total_sessions' => 20,
        ]);
    }

    public function test_package_calculates_correct_end_date_based_on_duration()
    {
        $package = Package::factory()->create(['duration_days' => 60]);
        $member = Member::factory()->create();
        $startDate = now()->format('Y-m-d');
        $expectedEndDate = now()->addDays(60)->format('Y-m-d');

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => $startDate,
            ]);

        $response->assertStatus(201);

        // Verify end_date is start_date + duration_days
        $memberPackage = MemberPackage::where('member_id', $member->id)->first();
        $this->assertEquals($expectedEndDate, $memberPackage->end_date->format('Y-m-d'));
    }

    public function test_assigning_package_records_validator_information()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        // Verify validated_by and validated_at are set
        $memberPackage = MemberPackage::where('member_id', $member->id)->first();
        $this->assertEquals($this->admin->id, $memberPackage->validated_by);
        $this->assertNotNull($memberPackage->validated_at);
    }

    public function test_admin_can_view_specific_member_package()
    {
        $memberPackage = MemberPackage::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/member-packages/{$memberPackage->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $memberPackage->id,
                'member_id' => $memberPackage->member_id,
                'package_id' => $memberPackage->package_id,
            ]);
    }

    public function test_admin_can_view_packages_for_specific_member()
    {
        $member = Member::factory()->create();
        $package1 = Package::factory()->create();
        $package2 = Package::factory()->create();

        MemberPackage::factory()->create([
            'member_id' => $member->id,
            'package_id' => $package1->id,
        ]);
        
        MemberPackage::factory()->create([
            'member_id' => $member->id,
            'package_id' => $package2->id,
        ]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/admin/members/{$member->id}/packages");

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_member_cannot_assign_package()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->create();

        $response = $this->actingAs($this->member, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        $response->assertStatus(403);
    }

    public function test_coach_cannot_assign_package()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->create();

        $response = $this->actingAs($this->coach, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
                'start_date' => now()->format('Y-m-d'),
            ]);

        $response->assertStatus(403);
    }

    public function test_assigning_package_requires_valid_package_id()
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => 99999,
                'start_date' => now()->format('Y-m-d'),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('package_id');
    }

    public function test_assigning_package_requires_start_date()
    {
        $package = Package::factory()->create();
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/admin/members/{$member->id}/assign-package", [
                'package_id' => $package->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('start_date');
    }
}

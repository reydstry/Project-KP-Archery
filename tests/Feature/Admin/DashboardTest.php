<?php

namespace Tests\Feature\Admin;

use App\Enums\StatusMember;
use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        Member::factory()->count(2)->pending()->create();
        Member::factory()->count(3)->create(['status' => StatusMember::STATUS_ACTIVE->value]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'statistics' => [
                    'pending_members',
                    'active_members',
                    'total_members',
                    'total_coaches',
                    'total_packages',
                    'total_news',
                    'total_achievements',
                ],
                'recent' => [
                    'pending_members',
                ],
            ])
            ->assertJsonPath('statistics.pending_members', 2)
            ->assertJsonPath('statistics.active_members', 3)
            ->assertJsonPath('statistics.total_members', 5);
    }
}

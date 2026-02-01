<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\Achievement;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_club_achievement()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/achievements', [
                'type' => 'club',
                'member_id' => null,
                'title' => 'Juara Umum',
                'description' => 'Prestasi club',
                'date' => now()->toDateString(),
                'photo_path' => null,
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Achievement created successfully'])
            ->assertJsonPath('data.type', 'club')
            ->assertJsonPath('data.member_id', null);

        $this->assertDatabaseHas('achievements', [
            'title' => 'Juara Umum',
            'type' => 'club',
        ]);
    }

    public function test_admin_can_create_member_achievement()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $member = Member::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/achievements', [
                'type' => 'member',
                'member_id' => $member->id,
                'title' => 'Juara 1 Nasional',
                'description' => 'Prestasi member',
                'date' => now()->toDateString(),
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Achievement created successfully'])
            ->assertJsonPath('data.type', 'member')
            ->assertJsonPath('data.member_id', $member->id);
    }

    public function test_admin_cannot_create_member_achievement_without_member_id()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/achievements', [
                'type' => 'member',
                'title' => 'Juara 1 Nasional',
                'description' => 'Prestasi member',
                'date' => now()->toDateString(),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['member_id']);
    }

    public function test_member_cannot_create_achievement()
    {
        $memberUser = User::factory()->create(['role' => UserRoles::MEMBER]);

        $response = $this->actingAs($memberUser, 'sanctum')
            ->postJson('/api/admin/achievements', [
                'type' => 'club',
                'title' => 'Nope',
                'description' => 'Nope',
                'date' => now()->toDateString(),
            ]);

        $response->assertStatus(403);
    }

    public function test_coach_cannot_create_achievement()
    {
        $coachUser = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->actingAs($coachUser, 'sanctum')
            ->postJson('/api/admin/achievements', [
                'type' => 'club',
                'title' => 'Nope',
                'description' => 'Nope',
                'date' => now()->toDateString(),
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_achievement()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $achievement = Achievement::factory()->clubAchievement()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/admin/achievements/{$achievement->id}", [
                'title' => 'New Title',
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Achievement updated successfully'])
            ->assertJsonPath('data.title', 'New Title');

        $this->assertDatabaseHas('achievements', [
            'id' => $achievement->id,
            'title' => 'New Title',
        ]);
    }

    public function test_admin_can_delete_achievement()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $achievement = Achievement::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/achievements/{$achievement->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Achievement deleted successfully']);

        $this->assertDatabaseMissing('achievements', [
            'id' => $achievement->id,
        ]);
    }

    public function test_create_achievement_requires_required_fields()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/achievements', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'title', 'description', 'date']);
    }
}

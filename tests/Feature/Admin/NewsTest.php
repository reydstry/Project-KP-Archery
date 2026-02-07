<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_news()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/news', [
                'title' => 'New Announcement',
                'content' => 'Hello world',
                'publish_date' => now()->toDateString(),
                'photo_path' => null,
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'News created successfully'])
            ->assertJsonPath('data.title', 'New Announcement');

        $this->assertDatabaseHas('news', [
            'title' => 'New Announcement',
        ]);
    }

    public function test_member_cannot_create_news()
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);

        $response = $this->actingAs($member, 'sanctum')
            ->postJson('/api/admin/news', [
                'title' => 'Nope',
                'content' => 'Nope',
                'publish_date' => now()->toDateString(),
            ]);

        $response->assertStatus(403);
    }

    public function test_coach_cannot_create_news()
    {
        $coach = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->actingAs($coach, 'sanctum')
            ->postJson('/api/admin/news', [
                'title' => 'Nope',
                'content' => 'Nope',
                'publish_date' => now()->toDateString(),
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_news()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $news = News::factory()->create([
            'title' => 'Old Title',
            'publish_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/admin/news/{$news->id}", [
                'title' => 'New Title',
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'News updated successfully'])
            ->assertJsonPath('data.title', 'New Title');

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'New Title',
        ]);
    }

    public function test_admin_can_delete_news()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $news = News::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/admin/news/{$news->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'News deleted successfully']);

        $this->assertDatabaseMissing('news', [
            'id' => $news->id,
        ]);
    }

    public function test_create_news_requires_required_fields()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/news', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'content', 'publish_date']);
    }
}

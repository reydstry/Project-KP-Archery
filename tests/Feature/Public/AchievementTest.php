<?php

namespace Tests\Feature\Public;

use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_published_achievements()
    {
        Achievement::factory()->count(2)->create([
            'date' => now()->subDays(1),
        ]);

        Achievement::factory()->clubAchievement()->create([
            'date' => now()->subDays(2),
        ]);

        Achievement::factory()->create([
            'date' => now()->addDays(1),
        ]);

        $response = $this->getJson('/api/achievements');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]);

        // Only published achievements should be visible (date <= today)
        $this->assertCount(3, $response->json('data'));
    }

    public function test_public_can_filter_achievements_by_type()
    {
        Achievement::factory()->count(2)->create([
            'type' => 'member',
            'date' => now()->subDays(1),
        ]);

        Achievement::factory()->clubAchievement()->count(3)->create([
            'date' => now()->subDays(2),
        ]);

        $response = $this->getJson('/api/achievements?type=club');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));

        foreach ($response->json('data') as $row) {
            $this->assertSame('club', $row['type']);
        }
    }

    public function test_public_can_view_single_published_achievement()
    {
        $achievement = Achievement::factory()->clubAchievement()->create([
            'title' => 'Juara Umum',
            'date' => now()->subDays(1),
        ]);

        $response = $this->getJson("/api/achievements/{$achievement->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $achievement->id)
            ->assertJsonPath('data.title', 'Juara Umum');
    }

    public function test_public_cannot_view_future_achievement()
    {
        $achievement = Achievement::factory()->create([
            'date' => now()->addDays(1),
        ]);

        $response = $this->getJson("/api/achievements/{$achievement->id}");

        $response->assertStatus(404);
    }
}

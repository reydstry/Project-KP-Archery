<?php

namespace Tests\Feature\Public;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_published_news()
    {
        News::factory()->count(2)->published()->create();
        News::factory()->scheduled()->create();

        $response = $this->getJson('/api/news');

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

        // Only published news should be visible
        $this->assertCount(2, $response->json('data'));
    }

    public function test_public_can_view_single_published_news()
    {
        $news = News::factory()->published()->create([
            'title' => 'Company Update',
        ]);

        $response = $this->getJson("/api/news/{$news->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $news->id)
            ->assertJsonPath('data.title', 'Company Update');
    }

    public function test_public_cannot_view_scheduled_news()
    {
        $news = News::factory()->scheduled()->create();

        $response = $this->getJson("/api/news/{$news->id}");

        $response->assertStatus(404);
    }
}

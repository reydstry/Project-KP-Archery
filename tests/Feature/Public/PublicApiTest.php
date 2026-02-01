<?php

namespace Tests\Feature\Public;

use App\Models\Achievement;
use App\Models\Member;
use App\Models\News;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public can get packages list
     */
    public function test_public_can_get_packages_list(): void
    {
        Package::factory()->count(3)->create();

        $response = $this->getJson('/api/packages');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test public can get published news list
     */
    public function test_public_can_get_published_news_list(): void
    {
        $admin = User::factory()->admin()->create();
        
        News::factory()->count(2)->create([
            'is_published' => true,
            'author_id' => $admin->id,
        ]);
        
        News::factory()->create([
            'is_published' => false,
            'author_id' => $admin->id,
        ]);

        $response = $this->getJson('/api/news');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test public can get single published news by slug
     */
    public function test_public_can_get_single_published_news_by_slug(): void
    {
        $admin = User::factory()->admin()->create();
        
        $news = News::factory()->create([
            'is_published' => true,
            'slug' => 'test-news-slug',
            'title' => 'Test News Title',
            'author_id' => $admin->id,
            'publish_date' => now(),
        ]);

        $response = $this->getJson('/api/news/test-news-slug');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'content',
                'author_id',
            ]
        ])
        ->assertJson([
            'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'slug' => 'test-news-slug',
            ],
        ]);
    }

    /**
     * Test public cannot get unpublished news
     */
    public function test_public_cannot_get_unpublished_news(): void
    {
        $admin = User::factory()->admin()->create();
        
        $news = News::factory()->create([
            'is_published' => false,
            'slug' => 'unpublished-news',
            'author_id' => $admin->id,
        ]);

        $response = $this->getJson('/api/news/unpublished-news');

        $response->assertStatus(404);
    }

    /**
     * Test public can get achievements list
     */
    public function test_public_can_get_achievements_list(): void
    {
        $user = User::factory()->member()->create();
        $member = Member::factory()->create(['user_id' => $user->id]);
        
        Achievement::factory()->count(3)->create(['member_id' => $member->id]);

        $response = $this->getJson('/api/achievements');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test public endpoints work without authentication
     */
    public function test_public_endpoints_work_without_authentication(): void
    {
        Package::factory()->create();
        
        $response = $this->getJson('/api/packages');
        
        $response->assertStatus(200);
    }
}

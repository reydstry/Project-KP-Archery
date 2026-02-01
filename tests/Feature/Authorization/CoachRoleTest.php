<?php

namespace Tests\Feature\Authorization;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test coach can access coach routes
     */
    public function test_coach_can_access_coach_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::COACH]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/coach/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test member cannot access coach routes
     */
    public function test_member_cannot_access_coach_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/coach/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test admin cannot access coach routes
     */
    public function test_admin_cannot_access_coach_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/coach/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot access coach routes
     */
    public function test_unauthenticated_user_cannot_access_coach_route(): void
    {
        $response = $this->getJson('/api/coach/dashboard');

        $response->assertStatus(401);
    }
}

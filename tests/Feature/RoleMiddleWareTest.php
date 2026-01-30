<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test member cannot access admin route
     */
    public function test_member_cannot_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Forbidden. Anda tidak memiliki akses.',
            ]);
    }

    /**
     * Test coach cannot access admin route
     */
    public function test_coach_cannot_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::COACH]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/dashboard');

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Forbidden. Anda tidak memiliki akses.',
            ]);
    }

    /**
     * Test member cannot access coach route
     */
    public function test_member_cannot_access_coach_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/coach/sessions');

        $response->assertStatus(403);
    }

    /**
     * Test admin can access admin route
     */
    public function test_admin_can_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test coach can access coach route
     */
    public function test_coach_can_access_coach_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::COACH]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/coach/sessions');

        $response->assertStatus(200);
    }

    /**
     * Test member can access member route
     */
    public function test_member_can_access_member_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/member/dashboard');

        $response->assertStatus(200);
    }
}
<?php

namespace Tests\Feature\Authorization;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access admin routes
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
     * Test member cannot access admin routes
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
     * Test coach cannot access admin routes
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
     * Test unauthenticated user cannot access admin routes
     */
    public function test_unauthenticated_user_cannot_access_admin_route(): void
    {
        $response = $this->getJson('/api/admin/dashboard');

        $response->assertStatus(401);
    }
}

<?php

namespace Tests\Feature\Authorization;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test member can access member routes
     */
    public function test_member_can_access_member_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        
        // Create member profile for dashboard access
        \App\Models\Member::factory()->create([
            'user_id' => $user->id,
            'is_self' => true,
        ]);
        
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/member/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test coach cannot access member routes
     */
    public function test_coach_cannot_access_member_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::COACH]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/member/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test admin cannot access member routes
     */
    public function test_admin_cannot_access_member_route(): void
    {
        $user = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/member/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot access member routes
     */
    public function test_unauthenticated_user_cannot_access_member_route(): void
    {
        $response = $this->getJson('/api/member/dashboard');

        $response->assertStatus(401);
    }
}

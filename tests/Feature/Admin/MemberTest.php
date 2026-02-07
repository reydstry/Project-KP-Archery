<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsAdmin()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $admin->createToken('test-token')->plainTextToken;
        
        return $token;
    }

    /**
     * Test admin can get all members
     */
    public function test_admin_can_get_all_members(): void
    {
        $token = $this->authenticateAsAdmin();
        Member::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/members');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'user_id',
                        'name',
                        'phone',
                        'is_self',
                        'is_active',
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test admin can create member
     */
    public function test_admin_can_create_member(): void
    {
        $token = $this->authenticateAsAdmin();
        $user = User::factory()->create(['role' => UserRoles::MEMBER]);
        $registeredBy = User::factory()->create(['role' => UserRoles::MEMBER]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/members', [
            'user_id' => $user->id,
            'registered_by' => $registeredBy->id,
            'name' => 'Andi Setiawan',
            'phone' => '081234567890',
            'is_self' => true,
            'is_active' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Member berhasil dibuat',
                'data' => [
                    'name' => 'Andi Setiawan',
                    'is_self' => true,
                ],
            ]);

        $this->assertDatabaseHas('members', [
            'name' => 'Andi Setiawan',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test admin can get single member with details
     */
    public function test_admin_can_get_single_member_with_details(): void
    {
        $token = $this->authenticateAsAdmin();
        $member = Member::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/admin/members/{$member->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data member berhasil diambil',
                'data' => [
                    'id' => $member->id,
                    'name' => $member->name,
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'registered_by',
                ],
            ]);
    }

    /**
     * Test admin can update member
     */
    public function test_admin_can_update_member(): void
    {
        $token = $this->authenticateAsAdmin();
        $member = Member::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/admin/members/{$member->id}", [
            'name' => 'Budi Updated',
            'phone' => '081987654321',
            'is_self' => false,
            'is_active' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Member berhasil diupdate',
                'data' => [
                    'name' => 'Budi Updated',
                    'is_self' => false,
                ],
            ]);

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'name' => 'Budi Updated',
        ]);
    }

    /**
     * Test admin can deactivate member
     */
    public function test_admin_can_deactivate_member(): void
    {
        $token = $this->authenticateAsAdmin();
        $member = Member::factory()->create(['is_active' => true]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/members/{$member->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Member berhasil dinonaktifkan',
            ]);

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'is_active' => false,
        ]);
    }

    /**
     * Test admin can restore member
     */
    public function test_admin_can_restore_member(): void
    {
        $token = $this->authenticateAsAdmin();
        $member = Member::factory()->inactive()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/admin/members/{$member->id}/restore");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Member berhasil diaktifkan kembali',
            ]);

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'is_active' => true,
        ]);
    }

    /**
     * Test member cannot access admin member endpoints
     */
    public function test_member_cannot_access_admin_member_endpoints(): void
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $member->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/members');

        $response->assertStatus(403);
    }
}

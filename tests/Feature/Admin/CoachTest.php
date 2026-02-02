<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsAdmin()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $admin->createToken('test-token')->plainTextToken;
        
        return $token;
    }

    /**
     * Test admin can get all coaches
     */
    public function test_admin_can_get_all_coaches(): void
    {
        $token = $this->authenticateAsAdmin();
        User::factory()->count(3)->create(['role' => UserRoles::COACH]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/coaches');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test admin can create coach
     */
    public function test_admin_can_create_coach(): void
    {
        $token = $this->authenticateAsAdmin();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/coaches', [
            'name' => 'Coach Budi',
            'email' => 'coach.budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567890',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Coach berhasil dibuat',
                'data' => [
                    'name' => 'Coach Budi',
                    'email' => 'coach.budi@example.com',
                    'role' => 'coach',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Coach Budi',
            'email' => 'coach.budi@example.com',
            'role' => 'coach',
        ]);
    }

    /**
     * Test admin cannot create coach with duplicate email
     */
    public function test_admin_cannot_create_coach_with_duplicate_email(): void
    {
        $token = $this->authenticateAsAdmin();
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/coaches', [
            'name' => 'Coach Test',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test admin can get single coach
     */
    public function test_admin_can_get_single_coach(): void
    {
        $token = $this->authenticateAsAdmin();
        $coach = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/admin/coaches/{$coach->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data coach berhasil diambil',
                'data' => [
                    'id' => $coach->id,
                    'name' => $coach->name,
                    'email' => $coach->email,
                ],
            ]);
    }

    /**
     * Test admin can update coach
     */
    public function test_admin_can_update_coach(): void
    {
        $token = $this->authenticateAsAdmin();
        $coach = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/admin/coaches/{$coach->id}", [
            'name' => 'Coach Updated',
            'email' => 'coach.updated@example.com',
            'phone' => '081987654321',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Coach berhasil diupdate',
                'data' => [
                    'name' => 'Coach Updated',
                    'email' => 'coach.updated@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $coach->id,
            'name' => 'Coach Updated',
        ]);
    }

    /**
     * Test admin can update coach with password
     */
    public function test_admin_can_update_coach_with_password(): void
    {
        $token = $this->authenticateAsAdmin();
        $coach = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/admin/coaches/{$coach->id}", [
            'name' => $coach->name,
            'email' => $coach->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        
        // Verify password changed
        $coach->refresh();
        $this->assertTrue(\Hash::check('newpassword123', $coach->password));
    }

    /**
     * Test admin can delete coach
     */
    public function test_admin_can_delete_coach(): void
    {
        $token = $this->authenticateAsAdmin();
        $coach = User::factory()->create(['role' => UserRoles::COACH]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/coaches/{$coach->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Coach berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $coach->id,
        ]);
    }

    /**
     * Test admin cannot delete non-coach user
     */
    public function test_admin_cannot_delete_non_coach_user(): void
    {
        $token = $this->authenticateAsAdmin();
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/coaches/{$member->id}");

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'User bukan coach',
            ]);

        // User should still exist
        $this->assertDatabaseHas('users', [
            'id' => $member->id,
        ]);
    }

    /**
     * Test member cannot access admin coach endpoints
     */
    public function test_member_cannot_access_admin_coach_endpoints(): void
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $member->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/coaches');

        $response->assertStatus(403);
    }
}

<?php

namespace Tests\Feature\Member;

use App\Enums\UserRoles;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsMember()
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);
        $token = $member->createToken('test-token')->plainTextToken;
        
        return [$member, $token];
    }

    /**
     * Test member can register self
     */
    public function test_member_can_register_self(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-self', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Pendaftaran member berhasil. Menunggu verifikasi admin.',
                'data' => [
                    'name' => 'Budi Santoso',
                    'is_self' => true,
                    'status' => 'pending',
                ],
            ]);

        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'name' => 'Budi Santoso',
            'is_self' => true,
            'status' => 'pending',
        ]);
    }

    /**
     * Test member cannot register self twice
     */
    public function test_member_cannot_register_self_twice(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        // First registration
        Member::factory()->create([
            'user_id' => $user->id,
            'is_self' => true,
        ]);

        // Try second registration
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-self', [
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Anda sudah terdaftar sebagai member',
            ]);
    }

    /**
     * Test member can register child
     */
    public function test_member_can_register_child(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        // Register self first
        Member::factory()->create([
            'user_id' => $user->id,
            'registered_by' => $user->id,
            'is_self' => true,
        ]);

        // Register child
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-child', [
            'name' => 'Ani Santoso',
            'phone' => '081234567891',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Pendaftaran anak berhasil. Menunggu verifikasi admin.',
                'data' => [
                    'name' => 'Ani Santoso',
                    'is_self' => false,
                    'status' => 'pending',
                ],
            ]);

        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'name' => 'Ani Santoso',
            'is_self' => false,
            'status' => 'pending',
        ]);
    }

    /**
     * Test member cannot register child without registering self first
     */
    public function test_member_cannot_register_child_without_self_registration(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-child', [
            'name' => 'Ani Santoso',
            'phone' => '081234567891',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Anda harus mendaftar sebagai member terlebih dahulu sebelum mendaftarkan anak',
            ]);
    }

    /**
     * Test member can register multiple children
     */
    public function test_member_can_register_multiple_children(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        // Register self first
        Member::factory()->create([
            'user_id' => $user->id,
            'registered_by' => $user->id,
            'is_self' => true,
        ]);

        // Register first child
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-child', [
            'name' => 'Anak Pertama',
        ]);

        // Register second child
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-child', [
            'name' => 'Anak Kedua',
        ]);

        $response->assertStatus(201);

        $this->assertEquals(3, Member::where('user_id', $user->id)->count());
    }

    /**
     * Test member can get their registered members list
     */
    public function test_member_can_get_their_members_list(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        // Create members
        Member::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/member/my-members');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'is_self',
                        'status',
                    ],
                ],
            ])
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test admin can get pending members list
     */
    public function test_admin_can_get_pending_members_list(): void
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $admin->createToken('test-token')->plainTextToken;

        // Create pending members
        Member::factory()->count(3)->create([
            'status' => 'pending',
        ]);

        // Create active member
        Member::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/pending-members');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test registration requires name
     */
    public function test_registration_requires_name(): void
    {
        [$user, $token] = $this->authenticateAsMember();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/member/register-self', [
            'phone' => '081234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}

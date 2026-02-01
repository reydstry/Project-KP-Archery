<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\Package;
use App\Models\User;
use App\Http\Controllers\Admin\PackageController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $this->token = $this->admin->createToken('test-token')->plainTextToken;
    }

    /**
     * Test admin can get all packages
     */
    public function test_admin_can_get_all_packages(): void
    {
        Package::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/admin/packages');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test admin can create package
     */
    public function test_admin_can_create_package(): void
    {
        $data = [
            'name' => 'Paket Test',
            'description' => 'Deskripsi paket test',
            'price' => 250000,
            'duration_days' => 30,
            'session_count' => 10,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/admin/packages', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Paket berhasil dibuat',
            ]);

        $this->assertDatabaseHas('packages', [
            'name' => 'Paket Test',
            'price' => 250000,
        ]);
    }

    /**
     * Test admin cannot create package with invalid data
     */
    public function test_admin_cannot_create_package_with_invalid_data(): void
    {
        $data = [
            'name' => '',
            'price' => -100,
            'session_count' => 0,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/admin/packages', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'duration_days', 'session_count']);
    }

    /**
     * Test admin can get single package
     */
    public function test_admin_can_get_single_package(): void
    {
        $package = Package::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/admin/packages/{$package->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $package->id,
                    'name' => $package->name,
                ],
            ]);
    }

    /**
     * Test admin can update package
     */
    public function test_admin_can_update_package(): void
    {
        $package = Package::factory()->create();

        $data = [
            'name' => 'Paket Updated',
            'price' => 300000,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/admin/packages/{$package->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Paket berhasil diupdate',
            ]);

        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Paket Updated',
            'price' => 300000,
        ]);
    }

    /**
     * Test admin can delete package
     */
    public function test_admin_can_delete_package(): void
    {
        $package = Package::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/admin/packages/{$package->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Paket berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('packages', [
            'id' => $package->id,
        ]);
    }

    /**
     * Test member cannot access admin package endpoints
     */
    public function test_member_cannot_access_admin_package_endpoints(): void
    {
        $member = User::factory()->create(['role' => UserRoles::MEMBER]);
        $memberToken = $member->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $memberToken,
        ])->getJson('/api/admin/packages');

        $response->assertStatus(403);
    }
}

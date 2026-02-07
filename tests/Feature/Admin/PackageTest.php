<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRoles;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateAsAdmin()
    {
        $admin = User::factory()->create(['role' => UserRoles::ADMIN]);
        $token = $admin->createToken('test-token')->plainTextToken;
        
        return $token;
    }

    /**
     * Test admin can get all packages
     */
    public function test_admin_can_get_all_packages(): void
    {
        $token = $this->authenticateAsAdmin();
        Package::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/packages');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'duration_days',
                        'session_count',
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test admin can create package
     */
    public function test_admin_can_create_package(): void
    {
        $token = $this->authenticateAsAdmin();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/packages', [
            'name' => 'Paket Basic',
            'description' => 'Paket latihan basic untuk pemula',
            'price' => 200000,
            'duration_days' => 30,
            'session_count' => 8,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Package berhasil dibuat',
                'data' => [
                    'name' => 'Paket Basic',
                    'price' => '200000.00',
                    'duration_days' => 30,
                    'session_count' => 8,
                ],
            ]);

        $this->assertDatabaseHas('packages', [
            'name' => 'Paket Basic',
            'price' => 200000,
        ]);
    }

    /**
     * Test admin cannot create package with invalid data
     */
    public function test_admin_cannot_create_package_with_invalid_data(): void
    {
        $token = $this->authenticateAsAdmin();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/packages', [
            'name' => '',
            'price' => -100,
            'duration_days' => 0,
            'session_count' => -5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'duration_days', 'session_count']);
    }

    /**
     * Test admin can get single package
     */
    public function test_admin_can_get_single_package(): void
    {
        $token = $this->authenticateAsAdmin();
        $package = Package::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/admin/packages/{$package->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data package berhasil diambil',
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
        $token = $this->authenticateAsAdmin();
        $package = Package::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/admin/packages/{$package->id}", [
            'name' => 'Paket Updated',
            'description' => 'Updated description',
            'price' => 300000,
            'duration_days' => 60,
            'session_count' => 16,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Package berhasil diupdate',
                'data' => [
                    'name' => 'Paket Updated',
                    'price' => '300000.00',
                ],
            ]);

        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Paket Updated',
        ]);
    }

    /**
     * Test admin can delete package
     */
    public function test_admin_can_delete_package(): void
    {
        $token = $this->authenticateAsAdmin();
        $package = Package::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/admin/packages/{$package->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Package berhasil dihapus',
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
        $token = $member->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/packages');

        $response->assertStatus(403);
    }
}

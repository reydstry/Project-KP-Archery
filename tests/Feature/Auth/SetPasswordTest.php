<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_set_password_form(): void
    {
        $this->get(route('password.set'))
            ->assertRedirect(route('login'));
    }

    public function test_user_with_null_password_can_view_set_password_form(): void
    {
        $user = User::factory()->create([
            'password' => null,
        ]);

        $this->actingAs($user)
            ->get(route('password.set'))
            ->assertOk()
            ->assertSee('Password Baru');
    }

    public function test_user_with_null_password_can_set_password(): void
    {
        $user = User::factory()->create([
            'password' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('password.store'), [
                'phone' => '081234567890',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('dashboard'));

        $user->refresh();
        $this->assertEquals('081234567890', $user->phone);
        $this->assertNotNull($user->password);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_with_existing_password_is_redirected_from_set_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('alreadyset123'),
            'phone' => '081234567890',
        ]);

        $this->actingAs($user)
            ->get(route('password.set'))
            ->assertRedirect(route('dashboard'));
    }
}

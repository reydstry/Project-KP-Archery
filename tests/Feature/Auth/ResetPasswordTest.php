<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_reset_password_form(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $token = Password::broker()->createToken($user);

        $this->get(route('password.reset', $token) . '?email=' . urlencode($user->email))
            ->assertOk()
            ->assertSee('Reset Password')
            ->assertSee('name="token" value="' . $token . '"', false);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->from(route('password.reset', $token) . '?email=' . urlencode($user->email))
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function test_user_cannot_reset_password_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->from(route('password.request'))
            ->post(route('password.update'), [
                'token' => 'invalid-token',
                'email' => $user->email,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);

        $user->refresh();
        $this->assertFalse(Hash::check('newpassword123', $user->password));
    }
}

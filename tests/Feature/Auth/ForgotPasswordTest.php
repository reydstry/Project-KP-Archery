<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_forgot_password_form(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Forgot Password')
            ->assertSee('action="' . route('password.email') . '"', false);
    }

    public function test_forgot_password_sends_reset_link_for_existing_user(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'resetme@example.com',
        ]);

        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => $user->email,
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_forgot_password_returns_error_for_non_existent_email(): void
    {
        Notification::fake();

        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'unknown@example.com',
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors(['email']);

        Notification::assertNothingSent();
    }
}

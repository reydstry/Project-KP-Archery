<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->from('/login')
                ->post('/login', [
                    'email' => 'ratelimit@example.com',
                    'password' => 'wrong-password',
                ])
                ->assertStatus(302);
        }

        $this->from('/login')
            ->post('/login', [
                'email' => 'ratelimit@example.com',
                'password' => 'wrong-password',
            ])
            ->assertStatus(429);
    }

    public function test_forgot_password_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->from(route('password.request'))
                ->post(route('password.email'), [
                    'email' => 'ratelimit-forgot@example.com',
                ])
                ->assertStatus(302);
        }

        $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'ratelimit-forgot@example.com',
            ])
            ->assertStatus(429);
    }
}

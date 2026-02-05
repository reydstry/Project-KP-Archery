<?php

namespace Tests\Feature\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_route_redirects_to_provider(): void
    {
        $provider = Mockery::mock(Provider::class);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $provider->shouldReceive('stateless')->once()->andReturnSelf();
        $provider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        $this->get(route('auth.google.redirect'))
            ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    public function test_google_callback_logs_in_existing_social_account_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => '123',
            'email' => $user->email,
        ]);

        $provider = Mockery::mock(Provider::class);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);

        $provider->shouldReceive('stateless')->once()->andReturnSelf();
        $provider->shouldReceive('user')
            ->once()
            ->andReturn(new class implements SocialiteUserContract {
                public $user = ['email_verified' => true];

                public function getId()
                {
                    return '123';
                }

                public function getNickname()
                {
                    return null;
                }

                public function getName()
                {
                    return 'Test User';
                }

                public function getEmail()
                {
                    return 'test@example.com';
                }

                public function getAvatar()
                {
                    return null;
                }
            });

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}

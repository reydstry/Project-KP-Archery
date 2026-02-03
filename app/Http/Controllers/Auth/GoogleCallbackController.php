<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $provider = 'google';
        $providerUserId = (string) $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: ($googleUser->getNickname() ?: 'User');

        $raw = (array) ($googleUser->user ?? []);
        $emailVerified = (bool) ($raw['email_verified'] ?? $raw['verified_email'] ?? false);

        // 1) Sudah pernah link
        $social = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        if ($social) {
            Auth::login($social->user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // 2) Belum link: kalau email ada dan verified, boleh link ke user existing
        $user = null;

        if ($email && $emailVerified) {
            $user = User::where('email', $email)->first();
        }

        // 3) Kalau tetap belum ada user, buat user baru
        if (!$user) {
            if (!$email) {
                return redirect('/login')->withErrors([
                    'email' => 'Google tidak mengembalikan email. Tidak bisa login.',
                ]);
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'role' => UserRoles::MEMBER,
                'password' => null, // social-only (opsional: boleh set random)
            ]);
        }

        // 4) Simpan social link
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'email' => $email,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if (!$user->password) {
            return redirect()->route('password.set');
        }

        return redirect()->intended(route('dashboard'));
    }
}
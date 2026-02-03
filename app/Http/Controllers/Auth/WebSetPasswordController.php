<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WebSetPasswordController extends Controller
{
    public function create(Request $request)
    {
        // Kalau sudah punya password, tidak perlu set lagi
        if ($request->user()->password) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.set-password');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->password) {
            return redirect()->intended(route('dashboard'));
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        // Good practice setelah perubahan credential
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
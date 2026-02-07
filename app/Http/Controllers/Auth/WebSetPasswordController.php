<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WebSetPasswordController extends Controller
{
    public function create(Request $request)
    {
        // Kalau sudah punya password dan phone, tidak perlu set lagi
        $user = $request->user();
        if ($user->password && $user->phone) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.set-password', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->password && $user->phone) {
            return redirect()->intended(route('dashboard'));
        }

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::beginTransaction();
        try {
            // Update user password and phone
            $user->forceFill([
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ])->save();

            // Update member phone if exists
            $member = Member::where('user_id', $user->id)->first();
            if ($member) {
                $member->update(['phone' => $data['phone']]);
            }

            DB::commit();

            // Good practice setelah perubahan credential
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
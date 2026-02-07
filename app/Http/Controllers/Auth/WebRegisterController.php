<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRoles;
use App\Enums\StatusMember;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class WebRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => UserRoles::MEMBER,
            ]);

            // Create member record with pending status
            Member::create([
                'user_id' => $user->id,
                'registered_by' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'is_self' => true,
                'is_active' => true,
                'status' => StatusMember::STATUS_PENDING->value,
            ]);

            DB::commit();
            
            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
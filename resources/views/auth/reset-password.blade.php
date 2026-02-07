@extends('layouts.auth')

@section('title', 'Reset Password - FocusOneX Archery')

@section('content')
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             alt="Background"
             class="w-full h-full object-cover blur-sm">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Reset Password Card -->
    <div class="relative z-10 bg-white rounded-2xl shadow-xl
                w-full max-w-lg sm:max-w-xl lg:max-w-4xl
                p-8 sm:p-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('asset/img/logofocus.png') }}"
                 alt="FocusOneX"
                 class="h-12 w-auto">
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">
            Reset Password
        </h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email (readonly) -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $email) }}"
                       required
                       readonly
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm bg-gray-50 outline-none">
            </div>

            <!-- New Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Password Baru
                </label>
                <input type="password"
                       name="password"
                       required
                       placeholder="Minimal 8 karakter"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       required
                       placeholder="Ulangi password baru"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-blue-700
                           focus:outline-none focus:ring-4 focus:ring-blue-300">
                Reset Password
            </button>
        </form>

        <!-- Back to Login -->
        <p class="mt-6 text-center text-sm text-gray-700">
            Ingat password Anda?
            <a href="{{ route('login') }}"
               class="font-semibold text-blue-600 hover:text-blue-700">
                Login disini
            </a>
        </p>

    </div>
</section>
@endsection
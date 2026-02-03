@extends('layouts.auth')

@section('title', 'Login - FocusOneX Archery')

@section('content')
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             alt="Background"
             class="w-full h-full object-cover blur-sm">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Login Card -->
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
            Sign in to your account
        </h1>

        <!-- Form -->
        <form method="POST" action="{{ route('login.post') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Your email
                </label>
                <input type="email"
                       name="email"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Password
                </label>
                <input type="password"
                       name="password"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox"
                           name="remember"
                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    Forgot password?
                </a>
            </div>

            <!-- Button -->
            <button type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-blue-700
                           focus:outline-none focus:ring-4 focus:ring-blue-300">
                Log in
            </button>
        </form>

        <!-- Register -->
        <p class="mt-6 text-center text-sm text-gray-700">
            Don't have an account?
            <a href="{{ route('register') }}"
               class="font-semibold text-blue-600 hover:text-blue-700">
                Sign up
            </a>
        </p>

        <!-- Back -->
        <div class="mt-4 text-center">
            <a href="{{ route('beranda') }}"
               class="inline-flex items-center gap-2
                      text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Menu Utama
            </a>
        </div>

    </div>
</section>

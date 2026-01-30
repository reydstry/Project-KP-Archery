@extends('layouts.auth')

@section('title', 'Login - FocusOneX Archery')

@section('content')
<section class="relative flex items-center justify-center min-h-screen px-4 py-8 overflow-hidden bg-gray-50">

    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             class="w-full h-full object-cover filter blur-sm"
             alt="Background">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Login Card - Clean & Simple -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl px-6 py-8 sm:px-10 sm:py-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('asset/img/logofocus.png') }}"
                 class="h-12 sm:h-14 w-auto"
                 alt="FocusOneX">
        </div>

        <!-- Title -->
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-6">
            Sign in to your account
        </h1>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Success -->
        @if (session('success'))
            <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-900">
                    Your email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       placeholder=""
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                              bg-white focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">
                    Password
                </label>
                <input type="password"
                       name="password"
                       required
                       placeholder=""
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                              bg-white focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           id="remember"
                           class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                    <label for="remember" class="ml-2 text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Forgot password?
                </a>
            </div>

            <!-- Button -->
            <button type="submit"
                    class="w-full py-3 text-white font-semibold text-sm
                           bg-blue-600 rounded-lg hover:bg-blue-700
                           focus:ring-4 focus:ring-blue-300 focus:outline-none
                           transition-colors duration-200">
                Log in to your account
            </button>
        </form>

        <!-- Register Link -->
        <p class="mt-4 text-sm text-center text-gray-700">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                Sign up
            </a>
        </p>

        <!-- Back to Home -->
        <div class="mt-4 text-center">
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Menu Utama
            </a>
        </div>
    </div>
</section>
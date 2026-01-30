@extends('layouts.auth')

@section('title', 'Forgot Password - FocusOneX Archery')

@section('content')
<section class="relative flex items-center justify-center min-h-screen px-4 py-8 overflow-hidden bg-gray-50">

    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             class="w-full h-full object-cover filter blur-sm"
             alt="Background">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <!-- Forgot Password Card -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl px-6 py-8 sm:px-10 sm:py-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('asset/img/logofocus.png') }}"
                 class="h-12 sm:h-14 w-auto"
                 alt="FocusOneX">
        </div>

        <!-- Title -->
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-6">
            Change Password
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
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
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
                       autofocus
                       placeholder=""
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                              bg-white focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- New Password -->
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-900">
                    New Password
                </label>
                <input type="password"
                       name="password"
                       required
                       placeholder=""
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                              bg-white focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block mb-1.5 text-sm font-medium text-gray-900">
                    Confirm password
                </label>
                <input type="password"
                       name="password_confirmation"
                       required
                       placeholder=""
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                              bg-white focus:ring-2 focus:ring-blue-500
                              focus:border-blue-500 outline-none transition-colors">
            </div>

            <!-- Terms Checkbox -->
            <div class="flex items-start">
                <input type="checkbox"
                       name="terms"
                       id="terms"
                       required
                       class="w-4 h-4 mt-0.5 text-blue-600 rounded border-gray-300 focus:ring-2 focus:ring-blue-500">
                <label for="terms" class="ml-2 text-sm text-gray-700">
                    I accept 
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 px-4 text-sm font-medium text-white bg-blue-600
                           rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300
                           transition-colors duration-200">
                Reset password
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to login
            </a>
        </div>
    </div>
</section>
@endsection

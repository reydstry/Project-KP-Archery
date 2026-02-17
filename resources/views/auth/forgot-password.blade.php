@extends('components.layouts.auth')

@section('title', 'Forgot Password - FocusOneX Archery')

@section('content')
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             alt="Background"
             class="w-full h-full object-cover blur-sm">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Forgot Password Card -->
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
            Forgot Password
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
        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       placeholder="Masukkan email Anda"
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Info Text -->
            <p class="text-sm text-gray-600">
                Kami akan mengirimkan link reset password ke email Anda.
            </p>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3
                           text-sm font-semibold text-white
                           transition hover:bg-blue-700
                           focus:outline-none focus:ring-4 focus:ring-blue-300">
                Kirim Link Reset Password
            </button>
        </form>

        <!-- Back to Login -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2
                      text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Login
            </a>
        </div>
    </div>
</section>
@endsection

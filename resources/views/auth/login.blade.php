@extends('components.layouts.auth')

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

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Success Message -->
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

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
                       value="{{ old('email') }}"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3
                              text-sm outline-none transition
                              focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
            </div>

    <!-- Password -->
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-700">
            Enter your password
        </label>
        <div class="relative flex items-center">
            <input type="password"
               id="password"
               name="password"
               autocomplete="current-password"
               required
               class="w-full rounded-lg border border-gray-300 pl-4 pr-12 py-3
                      text-sm outline-none transition
                      focus:border-blue-500 focus:ring-2 focus:ring-blue-500
                      [&::-ms-reveal]:!hidden [&::-ms-clear]:!hidden
                      [&::-webkit-contacts-auto-fill-button]:!hidden
                      [&::-webkit-credentials-auto-fill-button]:!hidden">
            <button type="button"
                onclick="togglePassword()"
                tabindex="-1"
                class="absolute right-4 top-1/2 -translate-y-1/2
                       transition-colors focus:outline-none cursor-pointer"
                aria-label="Toggle password visibility">
                <svg id="eye-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            </button>
        </div>
    </div>
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>

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

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="bg-white px-4 text-gray-500">atau</span>
            </div>
        </div>

        <!-- Google Login Button -->
        <a href="{{ route('auth.google.redirect') }}"
           class="flex items-center justify-center gap-3 w-full rounded-lg
                  border-2 border-gray-300 bg-white py-3 px-4
                  text-sm font-semibold text-gray-700
                  transition hover:bg-gray-50 hover:border-gray-400
                  focus:outline-none focus:ring-4 focus:ring-gray-200">
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Login dengan Google
        </a>

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

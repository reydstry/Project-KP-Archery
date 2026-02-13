@extends('layouts.auth')

@section('title', 'Daftar - FocusOneX Archery')

@section('content')
<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             alt="Background"
             class="w-full h-full object-cover blur-sm">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-lg sm:max-w-xl lg:max-w-4xl
                bg-white rounded-2xl shadow-2xl p-8 sm:p-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('asset/img/logofocus.png') }}"
                 alt="FocusOneX"
                 class="h-12 w-auto">
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">
            Create an account
        </h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('register.post') }}" method="POST" class="mt-8 space-y-6">
            @csrf

            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-12 text-sm
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <button type="button"
                                onclick="togglePassword('password', 'eye-open-1', 'eye-closed-1')"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-gray-500 hover:text-gray-700 transition
                                       p-1 rounded-lg hover:bg-gray-100">
                            <svg id="eye-open-1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed-1" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-12 text-sm
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        <button type="button"
                                onclick="togglePassword('password_confirmation', 'eye-open-2', 'eye-closed-2')"
                                class="absolute right-3 top-1/2 -translate-y-1/2
                                       text-gray-500 hover:text-gray-700 transition
                                       p-1 rounded-lg hover:bg-gray-100">
                            <svg id="eye-open-2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed-2" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function togglePassword(inputId, eyeOpenId, eyeClosedId) {
                    const input = document.getElementById(inputId);
                    const eyeOpen = document.getElementById(eyeOpenId);
                    const eyeClosed = document.getElementById(eyeClosedId);
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    } else {
                        input.type = 'password';
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    }
                }
            </script>

            <!-- Terms -->
            <label class="flex items-center gap-2 pt-2 text-sm text-gray-600">
                <input type="checkbox" required
                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                I accept the terms & conditions
            </label>

            <!-- Button -->
            <button type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3 text-sm font-semibold text-white
                           transition hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Create an account
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

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-700">
            Already have an account?
            <a href="{{ route('login') }}"
               class="font-semibold text-blue-600 hover:text-blue-700">
                Login here
            </a>
        </p>

    </div>
</section>
@endsection

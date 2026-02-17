@extends('components.layouts.auth')

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
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Minimal 8 karakter"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-12
                                  text-sm outline-none transition
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

            <!-- Confirm Password -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi password baru"
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-12
                                  text-sm outline-none transition
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
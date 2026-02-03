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
                    <input type="password" name="password" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

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

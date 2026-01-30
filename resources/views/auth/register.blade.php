@extends('layouts.auth')

@section('title', 'Daftar - FocusOneX Archery')

@section('content')
<section class="relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('asset/img/latarbelakanglogin.jpeg') }}"
             class="w-full h-full object-cover"
             alt="Background">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Content -->
    <div class="relative flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen lg:py-0">
        
    <!-- Login Card - Clean & Simple -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-2xl px-6 py-8 sm:px-10 sm:py-10">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('asset/img/logofocus.png') }}"
                 class="h-12 sm:h-14 w-auto"
                 alt="FocusOneX">
        </div>
        <!-- Register Card -->
        <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-2xl xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                
                <!-- Title -->
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Create an account
                </h1>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form class="space-y-4 md:space-y-6" action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    <!-- Row 1: Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
                                Nama Lengkap
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                                placeholder="" 
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                                Email
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                                placeholder="" 
                                required
                            >
                        </div>
                    </div>
                    <!-- Row 2: Phone & Birth Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">
                                Nomor Telepon
                            </label>
                            <input 
                                type="tel" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                                placeholder="" 
                                required
                            >
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">
                                Tanggal Lahir
                            </label>
                            <input 
                                type="date" 
                                name="birth_date" 
                                id="birth_date" 
                                value="{{ old('birth_date') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5"
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">
                            Alamat
                        </label>
                        <textarea 
                            name="address" 
                            id="address" 
                            rows="3"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                            placeholder="" 
                            required
                        >{{ old('address') }}</textarea>
                    </div>

                    <!-- Row 3: Password & Confirm -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
                                Password
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                placeholder="" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                                required
                            >
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">
                                Konfirmasi Password
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                placeholder="" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5" 
                                required
                            >
                        </div>
                    </div>
                    <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input 
                                id="terms" 
                                name="terms"
                                type="checkbox" 
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"
                                required
                            >
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-light text-gray-500">
                                I accept the 
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 text-white font-semibold text-sm
                           bg-blue-600 rounded-lg hover:bg-blue-700
                           focus:ring-4 focus:ring-blue-300 focus:outline-none
                           transition-colors duration-200">
                Create an account
            </button>

                    <!-- Login Link -->
                    <p class="text-sm font-light text-gray-500">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
                            Login here
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

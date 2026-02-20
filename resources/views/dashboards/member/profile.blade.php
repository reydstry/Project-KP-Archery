<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya - FocusOneX Archery</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-animate { animation: slideIn 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">

    <!-- Toast Notifications -->
    <div x-data="toastManager()" @show-toast.window="show($event.detail)" class="fixed top-4 right-4 z-[100] space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition class="min-w-[320px] bg-white rounded-xl shadow-2xl border-l-4 p-4 flex items-start gap-3"
                 :class="{
                     'border-green-500': toast.type === 'success',
                     'border-red-500': toast.type === 'error',
                     'border-amber-500': toast.type === 'warning',
                     'border-blue-500': toast.type === 'info'
                 }">
                <div class="mt-0.5">
                    <svg class="w-5 h-5" :class="{
                        'text-green-500': toast.type === 'success',
                        'text-red-500': toast.type === 'error',
                        'text-amber-500': toast.type === 'warning',
                        'text-blue-500': toast.type === 'info'
                    }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path x-show="toast.type === 'error'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        <path x-show="toast.type === 'warning'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        <path x-show="toast.type === 'info'" stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800" x-text="toast.message"></p>
                </div>
                <button @click="remove(toast.id)" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Top Navbar -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('asset/img/logofocus.png') }}" alt="FocusOneX" class="h-10">
                    <p class="text-sm font-bold text-slate-800">Dashboard Member</p>
                </div>

                <!-- User Menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 px-4 py-2 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">Member</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 py-2">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('member.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            Edit Profile
                        </a>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('member.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all text-slate-700 hover:text-blue-600 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

<div x-data="profileData()" x-init="init(); fetchProfile();">

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Profile Content -->
    <div x-show="!loading" x-cloak class="space-y-6">

        <!-- Profile Header Card -->
        <div class="card-animate bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-lg p-8 text-white">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold mb-2" x-text="profile.name"></h2>
                    <p class="text-blue-100 mb-4 flex items-center gap-2 justify-center md:justify-start">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <span x-text="profile.email"></span>
                    </p>

                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Role</p>
                            <p class="font-bold text-sm">Member</p>
                        </div>
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Status</p>
                            <p class="font-bold text-sm">Active</p>
                        </div>
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                            <p class="text-xs text-blue-100 mb-0.5">Bergabung</p>
                            <p class="font-bold text-sm" x-text="formatDate(profile.created_at)"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.1s">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Informasi Pribadi
                    </h3>

                    <form @submit.prevent="saveProfile">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" x-model="form.name" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" x-model="form.email" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Nomor HP</label>
                                <input type="text" x-model="form.phone" :disabled="!editMode"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500">
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                                <textarea x-model="form.address" :disabled="!editMode" rows="3"
                                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-slate-50 disabled:text-slate-500"></textarea>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div x-show="editMode" class="mt-6 flex gap-3">
                            <button type="submit"
                                    :disabled="submitting"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                                <span x-show="!submitting">Simpan Perubahan</span>
                                <span x-show="submitting">Menyimpan...</span>
                            </button>
                            <button type="button" @click="editMode = false; resetForm()"
                                    class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mt-6" style="animation-delay: 0.2s">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Ganti Password
                    </h3>

                    <form @submit.prevent="changePassword">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password Lama</label>
                                <input type="password" x-model="passwordForm.current_password"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                                <input type="password" x-model="passwordForm.new_password"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password Baru</label>
                                <input type="password" x-model="passwordForm.new_password_confirmation"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <button type="submit"
                                    :disabled="passwordSubmitting"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-md">
                                <span x-show="!passwordSubmitting">Ganti Password</span>
                                <span x-show="passwordSubmitting">Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats Sidebar -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Activity Stats -->
                <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="animation-delay: 0.1s">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        Statistik Aktivitas
                    </h3>

                    <div class="space-y-3">
                        <div class="p-4 bg-gradient-to-br from-green-50 to-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-600">Hadir</span>
                                <span class="text-2xl font-bold text-green-600" x-text="stats.total_attended || 0"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-gradient-to-br from-blue-50 to-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-600">Total Anggota Keluarga</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="stats.total_members || 1"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card-animate bg-gradient-to-br from-slate-800 via-slate-700 to-slate-800 rounded-2xl shadow-lg p-6 text-white" style="animation-delay: 0.2s">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('member.dashboard') }}"
                           class="block px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                <span class="font-semibold">Kembali ke Dashboard</span>
                            </div>
                        </a>

                        <a href="https://wa.me/6281234567890?text=Halo, saya ingin booking sesi latihan" target="_blank"
                           class="block px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span class="font-semibold">Booking via WhatsApp</span>
                            </div>
                        </a>

                        <button @click="editMode = !editMode"
                                class="w-full px-4 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl transition-all">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                <span class="font-semibold" x-text="editMode ? 'Batal Edit' : 'Edit Profil'"></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-sm text-slate-600">
                <p>&copy; 2026 FocusOneX Archery. All rights reserved.</p>
            </div>
        </div>
    </footer>

<script>
// Toast Manager
function toastManager() {
    return {
        toasts: [],
        nextId: 1,
        show(detail) {
            const id = this.nextId++;
            const toast = {
                id,
                message: detail.message || 'Notification',
                type: detail.type || 'info',
                visible: true
            };
            this.toasts.push(toast);
            setTimeout(() => this.remove(id), 5000);
        },
        remove(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 300);
            }
        }
    }
}

window.showToast = (message, type = 'info') => {
    window.dispatchEvent(new CustomEvent('show-toast', {
        detail: { message, type }
    }));
};

// Auto-refresh CSRF token every 60 minutes to prevent expiry
setInterval(() => {
    fetch('/api/me', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        credentials: 'same-origin'
    }).then(response => {
        if (response.status === 419) {
            showToast('Session expired, silakan login kembali', 'error');
            setTimeout(() => window.location.href = '/login', 2000);
        }
    }).catch(console.error);
}, 60 * 60 * 1000); // Every 60 minutes

// Handle form resubmission (back button after POST)
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
    window.location.reload();
}

function profileData() {
    return {
        loading: true,
        editMode: false,
        submitting: false,
        passwordSubmitting: false,
        profile: {},
        stats: {},
        form: {
            name: '',
            email: '',
            phone: '',
            address: ''
        },
        passwordForm: {
            current_password: '',
            new_password: '',
            new_password_confirmation: ''
        },

        init() {
            // Listen to toggle event from header button
            window.addEventListener('toggle-edit-mode', () => {
                this.editMode = !this.editMode;
                // Notify header button about the change
                window.dispatchEvent(new CustomEvent('edit-mode-changed', {
                    detail: { editMode: this.editMode }
                }));
            });

            // Watch editMode changes
            this.$watch('editMode', (value) => {
                window.dispatchEvent(new CustomEvent('edit-mode-changed', {
                    detail: { editMode: value }
                }));
            });
        },

        async fetchProfile() {
            this.loading = true;
            try {
                // For now using mock data, replace with actual API call
                this.profile = {
                    name: '{{ auth()->user()->name }}',
                    email: '{{ auth()->user()->email }}',
                    phone: '081234567890',
                    address: 'Jl. Contoh No. 123, Jakarta',
                    created_at: '{{ auth()->user()->created_at }}'
                };

                this.stats = {
                    total_attended: 20,
                    total_members: 3
                };

                this.resetForm();
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat profil', 'error');
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.form = { ...this.profile };
        },

        async saveProfile() {
            this.submitting = true;
            try {
                // Replace with actual API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.profile = { ...this.form };
                this.editMode = false;
                showToast('Profil berhasil diperbarui', 'success');
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal menyimpan profil', 'error');
            } finally {
                this.submitting = false;
            }
        },

        async changePassword() {
            if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
                showToast('Konfirmasi password tidak cocok', 'error');
                return;
            }

            this.passwordSubmitting = true;
            try {
                // Replace with actual API call
                await new Promise(resolve => setTimeout(resolve, 1000));

                this.passwordForm = {
                    current_password: '',
                    new_password: '',
                    new_password_confirmation: ''
                };
                showToast('Password berhasil diubah', 'success');
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal mengubah password', 'error');
            } finally {
                this.passwordSubmitting = false;
            }
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>
</body>
</html>

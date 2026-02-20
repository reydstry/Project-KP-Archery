<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Member - FocusOneX Archery</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-down { animation: slideDown 0.3s ease-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen" x-data="memberDashboard()" x-init="init()">

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
                            <p class="text-sm font-semibold text-slate-800" x-text="userName"></p>
                            <p class="text-xs text-slate-500">Member</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
                            <span x-text="userInitial"></span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-slate-200 py-2">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800" x-text="userName"></p>
                            <p class="text-xs text-slate-500" x-text="userEmail"></p>
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

        <!-- Loading State -->
        <div x-show="loading" class="flex justify-center items-center min-h-[60vh]">
            <div class="flex flex-col items-center gap-4">
                <div class="relative">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-slate-200"></div>
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent absolute top-0"></div>
                </div>
                <p class="text-slate-600 font-medium">Memuat dashboard...</p>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div x-show="!loading" x-cloak class="space-y-6">

            <!-- Notifications Bar -->
            <div x-show="notifications.length > 0" class="space-y-3">
                <template x-for="notif in notifications" :key="notif.id">
                    <div class="relative overflow-hidden rounded-2xl border-2 p-5 animate-slide-down"
                         :class="{
                             'bg-amber-50 border-amber-300': notif.type === 'warning',
                             'bg-blue-50 border-blue-300': notif.type === 'info',
                             'bg-red-50 border-red-300': notif.type === 'urgent'
                         }">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
                                 :class="{
                                     'bg-amber-500': notif.type === 'warning',
                                     'bg-blue-500': notif.type === 'info',
                                     'bg-red-500': notif.type === 'urgent'
                                 }">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path x-show="notif.type === 'warning' || notif.type === 'urgent'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                    <path x-show="notif.type === 'info'" stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 mb-1" x-text="notif.title"></h4>
                                <p class="text-sm text-slate-700" x-text="notif.message"></p>
                                <p class="text-xs text-slate-500 mt-2" x-text="notif.date"></p>
                            </div>
                            <button @click="dismissNotification(notif.id)" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Welcome Banner -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl shadow-2xl">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 50px 50px;"></div>
                </div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-48 -mt-48"></div>

                <div class="relative px-8 py-10">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">
                        Selamat Datang, <span x-text="userName"></span>! 👋
                    </h1>
                    <p class="text-blue-100 text-lg mb-8" x-text="getCurrentDate()"></p>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <p class="text-blue-100 text-xs mb-1">Total Anggota</p>
                            <p class="text-white text-3xl font-bold" x-text="allMembers.length"></p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <p class="text-blue-100 text-xs mb-1">Paket Aktif</p>
                            <p class="text-white text-3xl font-bold" x-text="activePackagesCount"></p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <p class="text-blue-100 text-xs mb-1">Total Kehadiran</p>
                            <p class="text-white text-3xl font-bold" x-text="totalAttendance"></p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                            <p class="text-blue-100 text-xs mb-1">Rata-rata</p>
                            <p class="text-white text-3xl font-bold" x-text="averageAttendanceRate + '%'"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Member Section -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Tambah Anggota Keluarga</h2>
                        <p class="text-slate-600 mt-1">Daftarkan anggota keluarga Anda untuk latihan archery</p>
                    </div>
                    <button @click="showAddMemberForm = !showAddMemberForm" 
                            class="px-6 py-3 rounded-xl font-semibold transition-all"
                            :class="showAddMemberForm ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-blue-600 text-white hover:bg-blue-700'">
                        <span x-text="showAddMemberForm ? 'Tutup Form' : '+ Tambah Anggota'"></span>
                    </button>
                </div>

                <!-- Add Member Form -->
                <div x-show="showAddMemberForm" x-collapse class="bg-slate-50 rounded-2xl p-6">
                    <form @submit.prevent="submitAddMember" class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap Anggota *</label>
                                <input type="text" x-model="newMember.name" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                       placeholder="Masukkan nama lengkap anak/anggota keluarga">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon</label>
                                <input type="tel" x-model="newMember.phone"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                                       placeholder="08xx xxxx xxxx (opsional)">
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Catatan:</strong> Setelah mendaftar, anggota akan berstatus <em>pending</em> dan menunggu verifikasi dari admin. Anda akan diberitahu setelah disetujui.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" @click="showAddMemberForm = false"
                                    class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition">
                                Batal
                            </button>
                            <button type="submit" :disabled="submitting"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-2">
                                <svg x-show="submitting" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                                    <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="submitting ? 'Menyimpan...' : 'Simpan Anggota'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Members Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="member in allMembers" :key="member.id">
                    <div class="bg-white rounded-3xl shadow-lg border-2 border-slate-200 hover:border-blue-300 hover:shadow-xl transition-all overflow-hidden">
                        <!-- Member Header -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-5 text-white">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold mb-1" x-text="member.name"></h3>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="member.status === 'active' ? 'bg-green-300' : 'bg-amber-300'"></span>
                                        <span x-text="member.status === 'active' ? 'Aktif' : 'Pending'"></span>
                                    </span>
                                </div>
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-bold">
                                    <span x-text="member.name?.charAt(0).toUpperCase()"></span>
                                </div>
                            </div>
                            <p class="text-blue-100 text-sm" x-text="'ID: ' + member.id"></p>
                        </div>

                        <!-- Member Body -->
                        <div class="p-6 space-y-4">
                            <!-- Active Package -->
                            <template x-if="member.activePackage">
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-4">
                                    <p class="text-xs font-semibold text-green-700 mb-2">📦 Paket Aktif</p>
                                    <p class="font-bold text-slate-800 mb-2" x-text="member.activePackage.package_name"></p>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <div class="text-center">
                                            <p class="text-xs text-slate-600">Total</p>
                                            <p class="text-lg font-bold text-slate-800" x-text="member.activePackage.total_sessions"></p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-slate-600">Terpakai</p>
                                            <p class="text-lg font-bold text-slate-800" x-text="member.activePackage.used_sessions"></p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-green-700">Tersisa</p>
                                            <p class="text-lg font-bold text-green-700" x-text="member.activePackage.remaining_sessions"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-600">
                                        <span>Berlaku s/d</span>
                                        <span class="font-semibold" x-text="formatDate(member.activePackage.end_date)"></span>
                                    </div>
                                </div>
                            </template>

                            <!-- No Package -->
                            <template x-if="!member.activePackage">
                                <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-4 text-center">
                                    <p class="text-amber-800 font-semibold mb-2">Belum Ada Paket Aktif</p>
                                    <p class="text-xs text-amber-600">Hubungi admin untuk aktivasi</p>
                                </div>
                            </template>

                            <!-- Attendance Stats -->
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-3 text-center">
                                    <p class="text-xs text-blue-700 mb-1">Kehadiran</p>
                                    <p class="text-2xl font-bold text-blue-700" x-text="member.stats?.total_attended || 0"></p>
                                </div>
                                <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-3 text-center">
                                    <p class="text-xs text-red-700 mb-1">Tidak Hadir</p>
                                    <p class="text-2xl font-bold text-red-700" x-text="member.stats?.total_absent || 0"></p>
                                </div> 
                            </div>

                            <!-- Attendance Rate -->
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-semibold text-slate-700">Tingkat Kehadiran</span>
                                        <span class="text-sm font-bold text-blue-600" x-text="calculateAttendanceRate(member) + '%'"></span>
                                    </div>
                                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all"
                                             :style="`width: ${calculateAttendanceRate(member)}%`"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="pt-4 border-t border-slate-200 space-y-2">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                                    </svg>
                                    <span x-text="member.phone || 'Belum ada nomor telepon'"></span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a :href="'https://wa.me/6281234567890?text=Halo, saya ingin booking sesi latihan untuk ' + member.name" target="_blank"
                               class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white text-center rounded-xl font-semibold transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>Booking via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Recent Attendance Section -->
            <div class="bg-white rounded-3xl shadow-lg border border-slate-200 p-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Riwayat Kehadiran Terbaru</h2>
                
                <div x-show="attendanceHistory.length > 0" class="space-y-3">
                    <template x-for="(attendance, index) in attendanceHistory" :key="attendance.id">
                        <div class="flex items-center gap-4 p-5 rounded-2xl border-2 border-slate-100 hover:border-blue-200 hover:shadow-md transition-all">
                            <!-- Status Icon -->
                            <div class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center"
                                 :class="attendance.attendance_status === 'present' ? 'bg-gradient-to-br from-green-100 to-emerald-100' : 'bg-gradient-to-br from-red-100 to-rose-100'">
                                <svg class="w-7 h-7" :class="attendance.attendance_status === 'present' ? 'text-green-600' : 'text-red-600'"
                                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path x-show="attendance.attendance_status === 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    <path x-show="attendance.attendance_status !== 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 mb-1" x-text="attendance.member_name"></p>
                                <p class="text-sm text-slate-600 mb-1" x-text="formatDate(attendance.session_date) + ' · ' + attendance.session_time"></p>
                                <p class="text-xs text-slate-500" x-text="'Coach ' + attendance.coach_name"></p>
                            </div>

                            <!-- Status Badge -->
                            <span class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-bold"
                                  :class="attendance.attendance_status === 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                  x-text="attendance.attendance_status === 'present' ? 'Hadir' : 'Tidak Hadir'"></span>
                        </div>
                    </template>
                </div>

                <div x-show="attendanceHistory.length === 0" class="text-center py-12">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Riwayat</h4>
                    <p class="text-slate-500 text-sm">Booking sesi latihan via WhatsApp untuk memulai</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-sm text-slate-600">
                <p>&copy; 2026 FocusOneX Archery. All rights reserved.</p>
                <p class="mt-2">Untuk booking sesi latihan, hubungi kami via WhatsApp: <a href="https://wa.me/6281234567890" class="text-blue-600 hover:underline" target="_blank">+62 812-3456-7890</a></p>
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

        // API Helper
        const API = {
            baseUrl: '/api',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            refreshCSRFToken() {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                if (token) {
                    this.headers['X-CSRF-TOKEN'] = token;
                }
            },
            async request(method, url, data = null) {
                this.refreshCSRFToken(); // Refresh token before each request
                
                const options = {
                    method,
                    headers: this.headers,
                    credentials: 'same-origin'
                };
                if (data && ['POST', 'PUT', 'PATCH'].includes(method)) {
                    options.body = JSON.stringify(data);
                }
                
                try {
                    const response = await fetch(this.baseUrl + url, options);
                    
                    // Handle 419 Page Expired
                    if (response.status === 419) {
                        showToast('Session expired, mohon refresh halaman', 'warning');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        throw new Error('Session expired');
                    }
                    
                    if (!response.ok) {
                        const error = await response.json().catch(() => ({ message: 'Request failed' }));
                        throw new Error(error.message || `HTTP ${response.status}`);
                    }
                    return await response.json();
                } catch (error) {
                    if (error.message !== 'Session expired') {
                        console.error('API Error:', error);
                    }
                    throw error;
                }
            },
            get(url) { return this.request('GET', url); },
            post(url, data) { return this.request('POST', url, data); }
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

        window.showToast = (message, type = 'info') => {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message, type }
            }));
        };

        // Main Dashboard Component
        function memberDashboard() {
            return {
                loading: true,
                userName: '{{ auth()->user()->name }}',
                userEmail: '{{ auth()->user()->email }}',
                userInitial: '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}',
                
                allMembers: [],
                attendanceHistory: [],
                notifications: [],
                
                showAddMemberForm: false,
                submitting: false,
                newMember: {
                    name: '',
                    phone: ''
                },

                async init() {
                    await this.fetchAllData();
                    this.loading = false;
                },

                async fetchAllData() {
                    try {
                        // Fetch dashboard data for the main (self) member
                        const dashboardData = await API.get('/member/dashboard');
                        
                        // Fetch all members (self + family)
                        const membersResponse = await API.get('/member/my-members');
                        const membersData = membersResponse.data || [];
                        
                        // Map members with data
                        this.allMembers = membersData.map(member => {
                            // If this is the self member, attach full dashboard data
                            if (member.is_self) {
                                return {
                                    ...member,
                                    activePackage: dashboardData.quota || null,
                                    stats: dashboardData.attendance?.statistics || { total_attended: 0, total_absent: 0 }
                                };
                            }
                            // For family members (children), we don't have individual package data yet
                            // In the future, admin can assign packages to them
                            return {
                                ...member,
                                activePackage: null,
                                stats: { total_attended: 0, total_absent: 0 }
                            };
                        });

                        // Fetch attendance history (combined for all)
                        this.attendanceHistory = (dashboardData.attendance?.history || []).map(att => ({
                            ...att,
                            member_name: this.allMembers.find(m => m.is_self)?.name || 'Member'
                        }));
                        
                        // Generate notifications
                        this.generateNotifications();
                        
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        showToast('Gagal memuat data dashboard', 'error');
                    }
                },

                generateNotifications() {
                    this.notifications = [];
                    
                    // Check for expiring packages
                    this.allMembers.forEach(member => {
                        if (member.activePackage) {
                            const endDate = new Date(member.activePackage.end_date);
                            const today = new Date();
                            const daysUntilExpiry = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
                            
                            if (daysUntilExpiry <= 7 && daysUntilExpiry > 0) {
                                this.notifications.push({
                                    id: `expiry-${member.id}`,
                                    type: 'warning',
                                    title: `⚠️ Paket ${member.name} Akan Berakhir`,
                                    message: `Paket membership akan berakhir dalam ${daysUntilExpiry} hari (${this.formatDate(member.activePackage.end_date)}). Segera perpanjang!`,
                                    date: this.formatDate(today)
                                });
                            } else if (daysUntilExpiry <= 0) {
                                this.notifications.push({
                                    id: `expired-${member.id}`,
                                    type: 'urgent',
                                    title: `🚨 Paket ${member.name} Sudah Berakhir`,
                                    message: `Paket membership sudah tidak aktif. Hubungi admin untuk perpanjangan.`,
                                    date: this.formatDate(today)
                                });
                            }
                        }
                    });

                    // Example: Upcoming competition notification
                    // Uncomment and customize as needed:
                    // this.notifications.push({
                    //     id: 'event-1',
                    //     type: 'info',
                    //     title: '🏆 Perlombaan Mendatang',
                    //     message: 'Kompetisi Archery Regional akan diadakan tanggal 25 Februari 2026. Daftarkan anak Anda sekarang!',
                    //     date: this.formatDate(new Date())
                    // });
                },

                dismissNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                async submitAddMember() {
                    if (!this.newMember.name) {
                        showToast('Mohon masukkan nama anggota', 'error');
                        return;
                    }

                    this.submitting = true;
                    try {
                        const response = await API.post('/member/register-child', this.newMember);
                        showToast(response.message || 'Anggota berhasil ditambahkan!', 'success');
                        
                        // Reset form
                        this.newMember = {
                            name: '',
                            phone: ''
                        };
                        this.showAddMemberForm = false;
                        
                        // Refresh data
                        await this.fetchAllData();
                    } catch (error) {
                        console.error('Error adding member:', error);
                        showToast(error.message || 'Gagal menambahkan anggota', 'error');
                    } finally {
                        this.submitting = false;
                    }
                },

                calculateAttendanceRate(member) {
                    const total = (member.stats?.total_attended || 0) + (member.stats?.total_absent || 0);
                    return total === 0 ? 0 : Math.round((member.stats?.total_attended / total) * 100);
                },

                get activePackagesCount() {
                    return this.allMembers.filter(m => m.activePackage).length;
                },

                get totalAttendance() {
                    return this.allMembers.reduce((sum, m) => sum + (m.stats?.total_attended || 0), 0);
                },

                get averageAttendanceRate() {
                    if (this.allMembers.length === 0) return 0;
                    const totalRate = this.allMembers.reduce((sum, m) => sum + this.calculateAttendanceRate(m), 0);
                    return Math.round(totalRate / this.allMembers.length);
                },

                getCurrentDate() {
                    return new Date().toLocaleDateString('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
            }
        }
    </script>
</body>
</html>

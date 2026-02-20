@extends('layouts.member')

@section('title', 'Dashboard')
@section('subtitle', 'Overview aktivitas dan progress latihan Anda')

@section('content')
<div x-data="memberDashboard()" x-init="init()">

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

    <!-- Main Dashboard Content -->
    <div x-show="!loading" x-cloak class="space-y-6">

        <!-- Hero Section - Welcome Banner -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl shadow-2xl">
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 50px 50px;"></div>
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-black/10 rounded-full -ml-48 -mb-48"></div>

            <div class="relative px-8 py-10">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <!-- Left: Welcome Info -->
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-4">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            <span class="text-white text-sm font-semibold">Member Active</span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 leading-tight">
                            Halo, <span x-text="memberName"></span>! 👋
                        </h1>

                        <p class="text-blue-100 text-lg mb-6">
                            <span x-text="getCurrentDate()"></span>
                        </p>

                        <p class="text-blue-200 mb-8 max-w-xl">
                            Selamat datang di dashboard Anda. Terus berlatih dan tingkatkan kemampuan archery bersama kami!
                        </p>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                                <p class="text-blue-100 text-xs mb-1">Total Hadir</p>
                                <p class="text-white text-2xl font-bold" x-text="stats.total_attended || 0"></p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                                <p class="text-blue-100 text-xs mb-1">Prestasi</p>
                                <p class="text-white text-2xl font-bold" x-text="achievements.length || 0"></p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                                <p class="text-blue-100 text-xs mb-1">Kehadiran</p>
                                <p class="text-white text-2xl font-bold" x-text="attendanceRate + '%'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Package Card -->
                    <div x-show="activePackage">
                        <div class="bg-white/10 backdrop-blur-lg border-2 border-white/30 rounded-3xl p-6 shadow-2xl">
                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <p class="text-blue-100 text-sm mb-2">Paket Aktif</p>
                                    <h3 class="text-2xl font-bold text-white mb-1" x-text="activePackage?.package_name"></h3>
                                    <p class="text-blue-200 text-sm">
                                        Berlaku s/d <span class="font-semibold" x-text="formatDate(activePackage?.end_date)"></span>
                                    </p>
                                </div>
                                <!-- Circular Progress -->
                                <div class="relative w-20 h-20">
                                    <svg class="transform -rotate-90 w-20 h-20">
                                        <circle cx="40" cy="40" r="32" stroke="currentColor" stroke-width="6" fill="transparent" class="text-white/20"/>
                                        <circle cx="40" cy="40" r="32" stroke="currentColor" stroke-width="6" fill="transparent"
                                                class="text-white transition-all duration-1000"
                                                :stroke-dasharray="201"
                                                :stroke-dashoffset="201 - (201 * (activePackage?.used_sessions || 0) / (activePackage?.total_sessions || 1))"
                                                stroke-linecap="round"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-white text-lg font-bold" x-text="packageProgress + '%'"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="bg-white/10 rounded-xl p-3 text-center">
                                    <p class="text-blue-100 text-xs mb-1">Total</p>
                                    <p class="text-white text-xl font-bold" x-text="activePackage?.total_sessions"></p>
                                </div>
                                <div class="bg-white/10 rounded-xl p-3 text-center">
                                    <p class="text-blue-100 text-xs mb-1">Terpakai</p>
                                    <p class="text-white text-xl font-bold" x-text="activePackage?.used_sessions"></p>
                                </div>
                                <div class="bg-green-500/20 rounded-xl p-3 text-center">
                                    <p class="text-green-100 text-xs mb-1">Tersisa</p>
                                    <p class="text-white text-xl font-bold" x-text="activePackage?.remaining_sessions"></p>
                                </div>
                            </div>

                            <button @click="showBookingModal = true"
                                    class="w-full py-4 bg-white text-blue-600 rounded-2xl font-bold hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>Booking Sesi Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <!-- No Active Package -->
                    <div x-show="!activePackage" class="bg-amber-500/20 backdrop-blur-lg border-2 border-amber-400/50 rounded-3xl p-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <h3 class="text-white text-xl font-bold mb-2">Belum Ada Paket Aktif</h3>
                            <p class="text-blue-200 text-sm mb-4">Hubungi admin untuk mengaktifkan membership</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-3 gap-6">

            <!-- Left Column (2 cols) - Bookings & Attendance -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800">Jadwal Booking</h2>
                                    <p class="text-sm text-slate-500">Sesi latihan yang akan datang</p>
                                </div>
                            </div>
                            <button @click="showBookingModal = true"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>Booking Baru</span>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div x-show="upcomingBookings.length > 0" class="space-y-4">
                            <template x-for="(booking, index) in upcomingBookings" :key="booking.id">
                                <div class="relative group p-5 rounded-2xl border-2 border-slate-100 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                                     :style="`animation: slideIn 0.3s ease-out ${index * 0.1}s backwards`">
                                    <div class="flex items-start gap-4">
                                        <!-- Date Badge -->
                                        <div class="flex-shrink-0 w-16 text-center">
                                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-3 text-white shadow-lg">
                                                <p class="text-xs font-semibold uppercase" x-text="formatDayName(booking.session_date)"></p>
                                                <p class="text-2xl font-bold leading-none mt-1" x-text="formatDayNumber(booking.session_date)"></p>
                                                <p class="text-xs mt-1" x-text="formatMonthYear(booking.session_date)"></p>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-base mb-1" x-text="booking.session_time"></h4>
                                                    <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                            </svg>
                                                            <span x-text="'Coach ' + booking.coach_name"></span>
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span x-text="getDaysUntil(booking.session_date)"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1 rounded-lg text-xs font-bold"
                                                      :class="{
                                                          'bg-green-100 text-green-700': booking.status === 'confirmed',
                                                          'bg-amber-100 text-amber-700': booking.status === 'pending'
                                                      }">
                                                    <span x-text="booking.status === 'confirmed' ? 'Confirmed' : 'Pending'"></span>
                                                </span>
                                            </div>

                                            <!-- Package Info -->
                                            <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                                </svg>
                                                <span x-text="booking.package_name"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div x-show="upcomingBookings.length === 0" class="text-center py-12">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Booking</h4>
                            <p class="text-slate-500 text-sm mb-6">Booking sesi latihan pertama Anda sekarang</p>
                            <button @click="showBookingModal = true"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>Buat Booking</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Attendance History -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Riwayat Kehadiran</h2>
                                <p class="text-sm text-slate-500">Track record kehadiran Anda</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Attendance Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-4 text-center">
                                <p class="text-green-700 text-xs font-semibold mb-1">Hadir</p>
                                <p class="text-3xl font-bold text-green-700" x-text="stats.total_attended || 0"></p>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200 rounded-2xl p-4 text-center">
                                <p class="text-red-700 text-xs font-semibold mb-1">Tidak Hadir</p>
                                <p class="text-3xl font-bold text-red-700" x-text="stats.total_absent || 0"></p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-4 text-center">
                                <p class="text-blue-700 text-xs font-semibold mb-1">Tingkat</p>
                                <p class="text-3xl font-bold text-blue-700" x-text="attendanceRate + '%'"></p>
                            </div>
                        </div>

                        <!-- History List -->
                        <div x-show="attendanceHistory.length > 0" class="space-y-3">
                            <template x-for="(item, index) in attendanceHistory.slice(0, 5)" :key="item.id">
                                <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-slate-200 hover:shadow-sm transition-all">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
                                         :class="item.attendance_status === 'present' ? 'bg-gradient-to-br from-green-100 to-emerald-100' : 'bg-gradient-to-br from-red-100 to-rose-100'">
                                        <svg class="w-6 h-6" :class="item.attendance_status === 'present' ? 'text-green-600' : 'text-red-600'"
                                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path x-show="item.attendance_status === 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path x-show="item.attendance_status !== 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-800 text-sm mb-1" x-text="formatDate(item.session_date)"></p>
                                        <p class="text-xs text-slate-500" x-text="item.session_time + ' • Coach ' + item.coach_name"></p>
                                    </div>
                                    <span class="flex-shrink-0 px-3 py-1 rounded-lg text-xs font-bold"
                                          :class="item.attendance_status === 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                          x-text="item.attendance_status === 'present' ? 'Hadir' : 'Tidak Hadir'"></span>
                                </div>
                            </template>
                        </div>

                        <div x-show="attendanceHistory.length === 0" class="text-center py-8">
                            <p class="text-slate-500">Belum ada riwayat kehadiran</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column (1 col) - Achievements & Quick Info -->
            <div class="space-y-6">

                <!-- Achievements -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Prestasi</h2>
                                <p class="text-sm text-slate-500">Achievement terbaru</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div x-show="achievements.length > 0" class="space-y-4">
                            <template x-for="(achievement, index) in achievements.slice(0, 5)" :key="achievement.id">
                                <div class="relative group p-5 rounded-2xl border-2 border-amber-100 hover:border-amber-300 hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-white to-amber-50/30">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-amber-400 via-amber-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-800 text-sm mb-1 line-clamp-2" x-text="achievement.title"></h4>
                                            <p class="text-xs text-slate-600 line-clamp-2 mb-2" x-text="achievement.description"></p>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                                </svg>
                                                <span x-text="formatDate(achievement.date)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="achievements.length === 0" class="text-center py-12">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Prestasi</h4>
                            <p class="text-slate-500 text-xs">Terus berlatih untuk meraih prestasi</p>
                        </div>
                    </div>
                </div>

                <!-- Member Info Card -->
                <div class="bg-white rounded-3xl shadow-lg border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Info Member</h2>
                                <p class="text-sm text-slate-500">Informasi akun Anda</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Nama Lengkap</p>
                                <p class="text-sm font-semibold text-slate-800" x-text="member?.name || '-'"></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-500 mb-1">Status Member</p>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold"
                                      :class="member?.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="member?.status === 'active' ? 'bg-green-500' : 'bg-amber-500'"></span>
                                    <span x-text="member?.status === 'active' ? 'Active' : 'Pending'"></span>
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-600">Status Member</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                      :class="member?.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                                      x-text="member?.status === 'active' ? 'Active' : 'Pending'"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- Booking Modal -->
<div x-show="showBookingModal"
     x-cloak
     @keydown.escape.window="showBookingModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
         @click="showBookingModal = false"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden"
             @click.stop>

            <!-- Header -->
            <div class="sticky top-0 z-10 px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800">Booking Sesi Latihan</h3>
                        <p class="text-sm text-slate-600 mt-1">Pilih paket dan jadwal yang tersedia</p>
                    </div>
                    <button @click="showBookingModal = false"
                            class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 overflow-y-auto max-h-[calc(90vh-200px)]">

                <!-- Step 1: Select Package -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">1. Pilih Paket Aktif</h4>
                    <div x-show="availablePackages.length > 0" class="grid gap-4">
                        <template x-for="pkg in availablePackages" :key="pkg.id">
                            <div @click="selectPackage(pkg)"
                                 class="relative p-5 rounded-2xl border-2 cursor-pointer transition-all"
                                 :class="selectedPackage?.id === pkg.id ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-slate-200 hover:border-blue-300 bg-white'">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h5 class="font-bold text-slate-800 mb-2" x-text="pkg.package?.name || pkg.package_name"></h5>
                                        <div class="flex flex-wrap gap-3 text-sm">
                                            <span class="text-slate-600">
                                                <span class="font-semibold" x-text="pkg.remaining_sessions"></span> sesi tersisa
                                            </span>
                                            <span class="text-slate-400">•</span>
                                            <span class="text-slate-600">
                                                Berlaku hingga <span x-text="formatDate(pkg.end_date)"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center"
                                         :class="selectedPackage?.id === pkg.id ? 'border-blue-500 bg-blue-500' : 'border-slate-300'">
                                        <svg x-show="selectedPackage?.id === pkg.id" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div x-show="availablePackages.length === 0" class="text-center py-8 bg-slate-50 rounded-2xl">
                        <p class="text-slate-600">Tidak ada paket aktif tersedia</p>
                    </div>
                </div>

                <!-- Step 2: Select Session -->
                <div x-show="selectedPackage" class="mb-8">
                    <h4 class="text-lg font-bold text-slate-800 mb-4">2. Pilih Jadwal Sesi</h4>

                    <div x-show="loadingSessions" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    </div>

                    <div x-show="!loadingSessions && availableSessions.length > 0" class="space-y-4">
                        <template x-for="session in availableSessions" :key="session.id">
                            <div class="border-2 border-slate-200 rounded-2xl overflow-hidden">
                                <button @click="toggleSession(session.id)"
                                        class="w-full px-5 py-4 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold">
                                            <span x-text="formatDayNumber(session.date)"></span>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-slate-800" x-text="session.date_formatted || formatDate(session.date)"></p>
                                            <p class="text-sm text-slate-600" x-text="'Coach ' + (session.coach?.name || '-')"></p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{'rotate-180': expandedSession === session.id}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </button>

                                <div x-show="expandedSession === session.id" x-collapse class="p-5 space-y-3">
                                    <template x-for="slot in session.slots" :key="slot.id">
                                        <div @click="selectSlot(slot)"
                                             class="p-4 rounded-xl border-2 cursor-pointer transition-all"
                                             :class="[
                                                 slot.is_full ? 'border-slate-200 bg-slate-50 opacity-50 cursor-not-allowed' :
                                                 selectedSlot?.id === slot.id ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:border-blue-300'
                                             ]">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-slate-800 mb-1" x-text="slot.session_time?.name || '-'"></p>
                                                    <p class="text-sm text-slate-600" x-text="(slot.session_time?.start_time || '') + ' - ' + (slot.session_time?.end_time || '')"></p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="px-3 py-1 rounded-lg text-xs font-bold"
                                                          :class="slot.is_full ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
                                                        <span x-show="slot.is_full">Penuh</span>
                                                        <span x-show="!slot.is_full" x-text="slot.available_slots + ' slot'"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="!loadingSessions && availableSessions.length === 0" class="text-center py-8 bg-slate-50 rounded-2xl">
                        <p class="text-slate-600">Tidak ada sesi tersedia saat ini</p>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 px-8 py-6 border-t border-slate-200 bg-white">
                <div class="flex items-center justify-between gap-4">
                    <button @click="showBookingModal = false"
                            class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button @click="submitBooking"
                            :disabled="!selectedPackage || !selectedSlot || submitting"
                            :class="selectedPackage && selectedSlot && !submitting ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-300 cursor-not-allowed'"
                            class="px-8 py-3 text-white rounded-xl font-semibold transition-colors flex items-center gap-2">
                        <svg x-show="submitting" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        <span x-text="submitting ? 'Memproses...' : 'Konfirmasi Booking'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

[x-cloak] {
    display: none !important;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@push('scripts')
<script>
function memberDashboard() {
    return {
        loading: true,
        member: null,
        activePackage: null,
        upcomingBookings: [],
        attendanceHistory: [],
        achievements: [],
        stats: {
            totalAttended: 0,
            totalAbsent: 0
        },

        // Booking modal
        showBookingModal: false,
        availablePackages: [],
        availableSessions: [],
        loadingSessions: false,
        selectedPackage: null,
        selectedSlot: null,
        expandedSession: null,
        submitting: false,

        async init() {
            await this.fetchDashboardData();
            this.loading = false;
        },

        async fetchDashboardData() {
            try {
                const data = await API.get('/member/dashboard');

                this.member = data.member;
                this.activePackage = data.quota;
                this.stats = data.attendance?.statistics || { total_attended: 0, total_absent: 0 };
                this.attendanceHistory = data.attendance?.history || [];
                this.achievements = data.achievements || [];

                // Fetch upcoming bookings
                await this.fetchUpcomingBookings();

                // Fetch available packages for booking modal
                await this.fetchAvailablePackages();
            } catch (error) {
                console.error('Error fetching dashboard:', error);
                showToast('Gagal memuat data dashboard', 'error');
            }
        },

        async fetchUpcomingBookings() {
            try {
                const response = await API.get('/member/bookings?status=confirmed,pending');
                const bookings = response.data || response; // Handle paginated or direct array
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                this.upcomingBookings = bookings
                    .map(b => {
                        const sessionDate = b.training_session_slot?.training_session?.date;
                        if (!sessionDate) return null;

                        return {
                            id: b.id,
                            session_date: sessionDate,
                            session_time: b.training_session_slot?.session_time?.name || '-',
                            coach_name: b.training_session_slot?.training_session?.coach?.name || '-',
                            package_name: b.member_package?.package?.name || '-',
                            status: b.status
                        };
                    })
                    .filter(b => {
                        if (!b || !b.session_date) return false;
                        const sessionDate = new Date(b.session_date);
                        sessionDate.setHours(0, 0, 0, 0);
                        return sessionDate >= today && (b.status === 'confirmed' || b.status === 'pending');
                    })
                    .sort((a, b) => new Date(a.session_date) - new Date(b.session_date))
                    .slice(0, 5);
            } catch (error) {
                console.error('Error fetching bookings:', error);
            }
        },

        async fetchAvailablePackages() {
            try {
                const data = await API.get('/member/bookings/available');
                this.availablePackages = data.active_packages || [];
            } catch (error) {
                console.error('Error fetching available packages:', error);
            }
        },

        async fetchAvailableSessions() {
            if (!this.selectedPackage) return;

            this.loadingSessions = true;
            try {
                const data = await API.get('/member/bookings/available');
                this.availableSessions = data.sessions || [];
            } catch (error) {
                console.error('Error fetching sessions:', error);
                showToast('Gagal memuat jadwal sesi', 'error');
            } finally {
                this.loadingSessions = false;
            }
        },

        async selectPackage(pkg) {
            this.selectedPackage = pkg;
            this.selectedSlot = null;
            this.expandedSession = null;
            await this.fetchAvailableSessions();
        },

        toggleSession(sessionId) {
            this.expandedSession = this.expandedSession === sessionId ? null : sessionId;
        },

        selectSlot(slot) {
            if (slot.is_full) {
                showToast('Slot sudah penuh, pilih slot lain', 'error');
                return;
            }
            this.selectedSlot = slot;
        },

        async submitBooking() {
            if (!this.selectedPackage || !this.selectedSlot || this.submitting) return;

            this.submitting = true;
            try {
                await API.post('/member/bookings', {
                    member_package_id: this.selectedPackage.id,
                    training_session_slot_id: this.selectedSlot.id
                });

                showToast('Booking berhasil dibuat!', 'success');
                this.showBookingModal = false;

                // Refresh data
                await this.fetchDashboardData();

                // Reset form
                this.selectedPackage = null;
                this.selectedSlot = null;
                this.expandedSession = null;
            } catch (error) {
                console.error('Error creating booking:', error);
                showToast(error.response?.data?.message || 'Gagal membuat booking', 'error');
            } finally {
                this.submitting = false;
            }
        },

        // Computed properties
        get memberName() {
            return this.member?.name?.split(' ')[0] || 'Member';
        },

        get attendanceRate() {
            const total = (this.stats.total_attended || 0) + (this.stats.total_absent || 0);
            return total === 0 ? 0 : Math.round((this.stats.total_attended / total) * 100);
        },

        get packageProgress() {
            if (!this.activePackage) return 0;
            return Math.round((this.activePackage.used_sessions / this.activePackage.total_sessions) * 100);
        },

        // Helper methods
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
        },

        formatDayName(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', { weekday: 'short' });
        },

        formatDayNumber(dateString) {
            return new Date(dateString).getDate();
        },

        formatMonthYear(dateString) {
            return new Date(dateString).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        },

        getDaysUntil(dateString) {
            const target = new Date(dateString);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            target.setHours(0, 0, 0, 0);

            const diff = Math.ceil((target - today) / (1000 * 60 * 60 * 24));

            if (diff === 0) return 'Hari ini';
            if (diff === 1) return 'Besok';
            if (diff < 0) return 'Sudah lewat';
            return diff + ' hari lagi';
        }
    }
}
</script>
@endpush
@endsection

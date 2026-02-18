@extends('layouts.member')

@section('title', 'Dashboard')
@section('subtitle', 'Overview aktivitas dan progress latihan Anda')

@section('content')
<div x-data="dashboardData()" x-init="fetchDashboard()">

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-96">
        <div class="flex flex-col items-center gap-3">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#1a307b]"></div>
            <p class="text-slate-600 text-sm">Memuat data...</p>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div x-show="!loading" x-cloak class="space-y-6">

        <!-- Welcome Banner dengan Member Info -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="relative p-8 md:p-10">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-16 h-16 rounded-2xl bg-[#1a307b]/10 flex items-center justify-center text-[#1a307b] text-2xl font-bold">
                                <span x-text="data.member?.name?.charAt(0).toUpperCase()"></span>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800 mb-1">
                                    Selamat Datang, <span x-text="data.member?.name?.split(' ')[0]"></span>! 👋
                                </h1>
                                <p class="text-slate-500 text-sm">
                                    <span x-text="new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                                </p>
                            </div>
                        </div>
                        <p class="text-slate-500 text-sm max-w-2xl">
                            Terus berlatih dan tingkatkan kemampuan archery Anda. Konsistensi adalah kunci kesuksesan!
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-200">
                            <p class="text-slate-500 text-xs mb-0.5">Status Member</p>
                            <p class="text-slate-800 font-bold text-sm capitalize flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full"
                                      :class="data.member?.status === 'active' ? 'bg-green-400' : 'bg-amber-400'"></span>
                                <span x-text="data.member?.status || 'pending'"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Package Card - Prominent -->
        <div x-show="data.quota" class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="relative p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#1a307b]/10 rounded-full mb-3 border border-[#1a307b]/20">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-[#1a307b] text-sm font-semibold">Paket Aktif</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-2">
                            <span x-text="data.quota?.package_name"></span>
                        </h2>
                        <p class="text-slate-500 text-sm">
                            Berlaku hingga <span class="font-semibold" x-text="formatDate(data.quota?.end_date)"></span>
                        </p>
                    </div>

                    <!-- Circular Progress -->
                    <div class="relative w-24 h-24">
                        <svg class="transform -rotate-90 w-24 h-24">
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-200"/>
                            <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="transparent"
                                    class="text-[#1a307b] transition-all duration-1000 ease-out"
                                    :stroke-dasharray="251.2"
                                    :stroke-dashoffset="251.2 - (251.2 * (data.quota?.used_sessions || 0) / (data.quota?.total_sessions || 1))"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-slate-800" x-text="Math.round((data.quota?.used_sessions / data.quota?.total_sessions) * 100)"></p>
                                <p class="text-slate-500 text-xs">%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <p class="text-slate-500 text-xs mb-1">Total Sesi</p>
                        <p class="text-3xl font-bold text-slate-800" x-text="data.quota?.total_sessions"></p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <p class="text-slate-500 text-xs mb-1">Terpakai</p>
                        <p class="text-3xl font-bold text-slate-800" x-text="data.quota?.used_sessions"></p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <p class="text-slate-500 text-xs mb-1">Tersisa</p>
                        <p class="text-3xl font-bold text-[#1a307b]" x-text="data.quota?.remaining_sessions"></p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1 bg-slate-50 rounded-2xl p-4 border border-slate-200">
                        <p class="text-slate-500 text-xs mb-1">Sisa Waktu Paket</p>
                        <p class="text-xl font-bold text-slate-800">
                            <span x-text="Math.floor(data.quota?.days_remaining || 0)"></span> Hari
                        </p>
                    </div>

                          <a href="{{ route('member.membership') }}"
                       class="group px-8 py-4 bg-[#1a307b] text-white rounded-2xl font-bold hover:bg-[#152866] transition-all duration-300 inline-flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Lihat Keanggotaan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- No Active Package -->
        <div x-show="!data.quota" class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="relative p-8 flex items-start gap-6">
                <div class="w-16 h-16 bg-[#d12823]/10 rounded-2xl flex items-center justify-center shrink-0 border border-[#d12823]/20">
                    <svg class="w-8 h-8 text-[#d12823]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Paket Belum Aktif</h3>
                    <p class="text-slate-600 mb-4">
                        Anda belum memiliki paket membership aktif. Hubungi admin untuk mengaktifkan paket dan mulai latihan Anda.
                    </p>
                    <a href="{{ route('member.membership') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-colors">
                        <span>Lihat Paket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Hadir -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-[#1a307b]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 text-sm mb-1">Tingkat Kehadiran</p>
                            <p class="text-slate-800 text-2xl font-bold">
                                <span x-text="calculateAttendanceRate() + '%'"></span>
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-[#1a307b] text-4xl font-bold mb-1" x-text="data.attendance?.statistics?.total_attended || 0"></p>
                        <p class="text-slate-500 text-sm">Total Sesi Hadir</p>
                    </div>
                </div>
            </div>

            <!-- Total Tidak Hadir -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="relative">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-[#d12823]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-[#d12823]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 text-sm mb-1">Perlu Diperbaiki</p>
                            <p class="text-slate-800 text-2xl font-bold">
                                <span x-text="data.attendance?.statistics?.total_absent || 0"></span>
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-[#d12823] text-4xl font-bold mb-1" x-text="data.attendance?.statistics?.total_absent || 0"></p>
                        <p class="text-slate-500 text-sm">Total Sesi Tidak Hadir</p>
                    </div>
                </div>
            </div>

            <!-- Total Prestasi -->
            <div class="group bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="relative p-0">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-14 h-14 bg-[#1a307b]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 text-sm mb-1">Achievement</p>
                            <p class="text-slate-800 text-2xl font-bold">Terbaru</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-4">
                        <p class="text-[#1a307b] text-4xl font-bold mb-1" x-text="data.achievements?.length || 0"></p>
                        <p class="text-slate-500 text-sm">Total Prestasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <!-- Left Column - Attendance History (3 cols) -->
            <div class="lg:col-span-3 bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#1a307b]/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Riwayat Kehadiran</h3>
                                <p class="text-sm text-slate-500">Sesi latihan terbaru</p>
                            </div>
                        </div>
                        <a href="{{ route('member.membership') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm inline-flex items-center gap-1 hover:gap-2 transition-all">
                            <span>Lihat Semua</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div x-show="data.attendance?.history?.length > 0" class="space-y-3">
                        <template x-for="(item, index) in data.attendance?.history?.slice(0, 5)" :key="item.id">
                            <div class="group relative flex items-center gap-4 p-5 rounded-2xl border-2 border-slate-100 hover:border-blue-200 hover:shadow-md transition-all duration-300"
                                 :style="`animation: slideIn 0.3s ease-out ${index * 0.1}s backwards`">
                                <!-- Timeline Dot -->
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                         :class="item.attendance_status === 'present' ? 'bg-[#1a307b]/10' : 'bg-[#d12823]/10'">
                                        <svg class="w-6 h-6" :class="item.attendance_status === 'present' ? 'text-green-600' : 'text-red-600'"
                                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path x-show="item.attendance_status === 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path x-show="item.attendance_status !== 'present'" stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <p class="font-bold text-slate-800 text-base mb-1" x-text="formatDate(item.session_date)"></p>
                                            <p class="text-sm text-slate-600 mb-2" x-text="item.session_time"></p>
                                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                </svg>
                                                <span x-text="'Coach ' + item.coach_name"></span>
                                            </div>
                                        </div>
                                        <div class="shrink-0">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold"
                                                  :class="item.attendance_status === 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                                <span class="w-1.5 h-1.5 rounded-full" :class="item.attendance_status === 'present' ? 'bg-green-500' : 'bg-red-500'"></span>
                                                <span x-text="item.attendance_status === 'present' ? 'Hadir' : 'Tidak Hadir'"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="!data.attendance?.history || data.attendance?.history?.length === 0"
                         class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Riwayat</h4>
                                <p class="text-slate-500 text-sm mb-6">Hubungi admin untuk dicatat kehadirannya pada sesi latihan</p>
                                <a href="{{ route('member.membership') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-[#1a307b] text-white rounded-xl font-semibold hover:bg-[#152866] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            <span>Lihat Keanggotaan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column - Achievements (2 cols) -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#1a307b]/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Prestasi</h3>
                                <p class="text-sm text-slate-500">Achievement terbaru</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div x-show="data.achievements?.length > 0" class="space-y-4">
                        <template x-for="(achievement, index) in data.achievements?.slice(0, 4)" :key="achievement.id">
                            <div class="group relative p-5 rounded-2xl border-2 border-slate-100 hover:border-slate-200 hover:shadow-lg transition-all duration-300 bg-white"
                                 :style="`animation: slideIn 0.3s ease-out ${index * 0.1}s backwards`">
                                <div class="flex items-start gap-4">
                                    <div class="shrink-0 w-14 h-14 bg-[#1a307b]/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                                        <svg class="w-7 h-7 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 text-sm mb-1 line-clamp-1" x-text="achievement.title"></h4>
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

                                <a href="{{ route('member.achievements') }}"
                                    class="block w-full text-center py-3 px-4 bg-[#1a307b] text-white rounded-2xl font-semibold hover:bg-[#152866] transition-all duration-300">
                            Lihat Semua Prestasi
                        </a>
                    </div>

                    <div x-show="!data.achievements || data.achievements?.length === 0"
                         class="text-center py-16">
                        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Prestasi</h4>
                        <p class="text-slate-500 text-sm">Terus berlatih untuk meraih prestasi pertama Anda</p>
                    </div>
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
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

[x-cloak] {
    display: none !important;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
function dashboardData() {
    return {
        loading: true,
        data: {},

        async fetchDashboard() {
            this.loading = true;
            try {
                this.data = await API.get('/member/dashboard');
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data dashboard', 'error');
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        calculateAttendanceRate() {
            const attended = this.data.attendance?.statistics?.total_attended || 0;
            const absent = this.data.attendance?.statistics?.total_absent || 0;
            const total = attended + absent;
            if (total === 0) return 0;
            return Math.round((attended / total) * 100);
        }
    }
}
</script>
@endpush
@endsection

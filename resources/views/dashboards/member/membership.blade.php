@extends('layouts.member')

@section('title', 'Keanggotaan')
@section('subtitle', 'Kelola anggota keluarga dan paket pelatihan')

@section('content')
<div x-data="membershipData()" x-init="fetchMembers()">

    <!-- Header Section dengan Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Daftar Keanggotaan</h2>
            <p class="text-slate-600 text-sm mt-1">Daftarkan anggota keluarga, teman, atau kenalan untuk bergabung</p>
        </div>
        <div class="flex gap-3">
            <button @click="openRegisterModal('self')"
                    class="px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <span>Daftar Sebagai Member</span>
            </button>
            <button @click="openRegisterModal('other')"
                    class="px-5 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-xl font-semibold hover:border-slate-400 hover:shadow-md transition-all inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
                <span>Daftarkan Orang Lain</span>
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center h-64">
        <div class="flex flex-col items-center gap-3">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <p class="text-slate-600 text-sm">Memuat data...</p>
        </div>
    </div>

    <!-- Members Grid -->
    <div x-show="!loading" x-cloak>
        <div x-show="members.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="member in members" :key="member.id">
                <div class="group relative bg-white rounded-2xl border-2 border-slate-200 hover:border-blue-300 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"
                              :class="{
                                  'bg-green-100 text-green-700 border border-green-200': member.status === 'active',
                                  'bg-amber-100 text-amber-700 border border-amber-200': member.status === 'pending',
                                  'bg-slate-100 text-slate-700 border border-slate-200': member.status === 'inactive'
                              }">
                            <span class="w-1.5 h-1.5 rounded-full"
                                  :class="{
                                      'bg-green-500': member.status === 'active',
                                      'bg-amber-500': member.status === 'pending',
                                      'bg-slate-500': member.status === 'inactive'
                                  }"></span>
                            <span x-text="getStatusLabel(member.status)"></span>
                        </span>
                    </div>

                    <!-- Member Card Header -->
                    <div class="relative bg-gradient-to-br from-slate-50 to-blue-50 p-6 pb-16">
                        <div class="flex items-start gap-4">

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-lg text-slate-800 truncate" x-text="member.name"></h3>
                                    <span x-show="member.is_self"
                                          class="px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full font-semibold">
                                        Saya
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 mb-1">
                                    <span x-text="member.phone || 'Tidak ada telepon'"></span>
                                </p>
                                <p class="text-xs text-slate-500">
                                    Member ID: #<span x-text="String(member.id).padStart(4, '0')"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Package Info Section -->
                    <div class="p-6 -mt-12 relative z-10">
                        <!-- Active Package Card -->
                        <div x-show="member.active_package"
                             class="bg-white rounded-xl border-2 border-blue-200 p-4 mb-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-slate-800 text-sm">Paket Aktif</h4>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-700 mb-1">
                                <span x-text="member.active_package?.package_name"></span>
                            </p>
                            <p class="text-xs text-slate-600 mb-3">
                                Berlaku hingga <span class="font-semibold" x-text="formatDate(member.active_package?.end_date)"></span>
                            </p>
                            <div class="flex items-center justify-between text-xs text-slate-600 mb-2">
                                <span>Sesi Tersisa</span>
                                <span class="font-bold text-blue-600" x-text="member.active_package?.remaining_sessions + ' / ' + member.active_package?.total_sessions"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                     :style="`width: ${(member.active_package?.used_sessions / member.active_package?.total_sessions) * 100}%`">
                                </div>
                            </div>
                        </div>

                        <!-- No Package - Buy Package CTA -->
                        <div x-show="!member.active_package && member.status === 'active'"
                             class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border-2 border-amber-200 p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-amber-800 text-sm mb-1">Belum Ada Paket</p>
                                    <p class="text-xs text-amber-700">Beli paket untuk mulai latihan</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Approval Notice -->
                        <div x-show="member.status === 'pending'"
                             class="bg-slate-50 rounded-xl border-2 border-slate-200 p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-slate-400 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-700 text-sm mb-1">Menunggu Verifikasi</p>
                                    <p class="text-xs text-slate-600">Admin sedang memproses pendaftaran</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button @click="openBuyPackageModal(member)"
                                    x-show="member.status === 'active'"
                                    class="flex-1 px-4 py-3 rounded-xl font-semibold text-sm transition-all inline-flex items-center justify-center gap-2"
                                    :class="member.active_package
                                        ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                        : 'bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:shadow-lg hover:scale-[1.02]'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                <span x-text="member.active_package ? 'Beli Paket Lagi' : 'Beli Paket'"></span>
                            </button>

                            <button @click="viewMemberDetail(member)"
                                    class="px-4 py-3 bg-white border-2 border-slate-300 text-slate-700 rounded-xl hover:border-slate-400 hover:bg-slate-50 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="members.length === 0" class="text-center py-20">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Belum Ada Member Terdaftar</h3>
            <p class="text-slate-600 mb-8 max-w-md mx-auto">
                Mulai dengan mendaftarkan diri Anda sebagai member, lalu Anda bisa mendaftarkan anggota keluarga atau teman
            </p>
            <div class="flex justify-center gap-3">
                <button @click="openRegisterModal('self')"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Daftar Sekarang</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Register Member Modal -->
    <div x-show="showRegisterModal"
         x-cloak
         @click.self="closeRegisterModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div @click.stop
             x-show="showRegisterModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[90vh] flex flex-col overflow-hidden">

            <!-- Modal Header -->
            <div class="bg-white border-b border-slate-200 px-8 py-6 rounded-t-3xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-800" x-text="registerType === 'self' ? 'Daftar Sebagai Member' : 'Daftarkan Orang Lain'"></h3>
                        <p class="text-sm text-slate-600 mt-1"
                           x-text="registerType === 'self' ? 'Lengkapi data diri Anda' : 'Daftarkan anggota keluarga, teman, atau kenalan'"></p>
                    </div>
                    <button @click="closeRegisterModal()"
                            class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="submitRegister()" class="p-8 space-y-6 overflow-y-auto flex-1">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           x-model="registerForm.name"
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="tel"
                           x-model="registerForm.phone"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
                    <p class="text-xs text-slate-500 mt-2">Opsional - untuk kemudahan komunikasi</p>
                </div>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-blue-900 text-sm mb-1">Informasi Penting</p>
                            <p class="text-xs text-blue-800">
                                Pendaftaran akan diverifikasi oleh admin. Setelah disetujui, Anda dapat membeli paket pelatihan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button"
                            @click="closeRegisterModal()"
                            class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            :disabled="registerLoading"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                        <svg x-show="registerLoading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="registerLoading ? 'Memproses...' : 'Daftar Sekarang'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Buy Package Modal -->
    <div x-show="showBuyPackageModal"
         x-cloak
         @click.self="closeBuyPackageModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div @click.stop
             x-show="showBuyPackageModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 rounded-t-3xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-white text-xl font-bold">
                            <span x-text="selectedMember?.name?.charAt(0).toUpperCase()"></span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Pilih Paket Pelatihan</h3>
                            <p class="text-blue-100 text-sm mt-1">
                                Untuk: <span class="font-semibold" x-text="selectedMember?.name"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="closeBuyPackageModal()"
                            class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 transition-colors flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-8 overflow-y-auto flex-1">
                <div class="mb-6">
                    <h4 class="font-bold text-slate-800 mb-2">Paket yang Tersedia</h4>
                    <p class="text-sm text-slate-600">Pilih paket yang sesuai dengan kebutuhan latihan</p>
                </div>

                <!-- Package List (Static for now since backend provides this) -->
                <div class="space-y-4 mb-6">
                    <!-- Package 1 -->
                    <div class="group relative border-2 border-slate-200 hover:border-blue-400 rounded-2xl p-6 cursor-pointer transition-all hover:shadow-lg">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h5 class="font-bold text-lg text-slate-800 mb-1">Paket Basic - 8 Sesi</h5>
                                <p class="text-sm text-slate-600">Cocok untuk pemula yang ingin mencoba archery</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">Rp 500K</p>
                                <p class="text-xs text-slate-500">30 hari</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">8 Sesi Latihan</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">Berlaku 30 Hari</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">Peralatan Disediakan</span>
                        </div>
                        <button class="mt-4 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                            Pilih Paket Ini
                        </button>
                    </div>

                    <!-- Package 2 -->
                    <div class="group relative border-2 border-blue-300 bg-blue-50 rounded-2xl p-6 cursor-pointer transition-all hover:shadow-lg">
                        <div class="absolute -top-3 left-6 px-3 py-1 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold rounded-lg">
                            TERPOPULER
                        </div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h5 class="font-bold text-lg text-slate-800 mb-1">Paket Premium - 12 Sesi</h5>
                                <p class="text-sm text-slate-600">Paket terbaik untuk latihan rutin</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">Rp 750K</p>
                                <p class="text-xs text-slate-500">30 hari</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-200 text-blue-800 text-xs rounded-lg font-semibold">12 Sesi Latihan</span>
                            <span class="px-3 py-1 bg-blue-200 text-blue-800 text-xs rounded-lg font-semibold">Berlaku 30 Hari</span>
                            <span class="px-3 py-1 bg-blue-200 text-blue-800 text-xs rounded-lg font-semibold">Coaching Intensif</span>
                        </div>
                        <button class="mt-4 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                            Pilih Paket Ini
                        </button>
                    </div>

                    <!-- Package 3 -->
                    <div class="group relative border-2 border-slate-200 hover:border-blue-400 rounded-2xl p-6 cursor-pointer transition-all hover:shadow-lg">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h5 class="font-bold text-lg text-slate-800 mb-1">Paket Pro - 20 Sesi</h5>
                                <p class="text-sm text-slate-600">Untuk atlet yang serius berlatih</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">Rp 1.2JT</p>
                                <p class="text-xs text-slate-500">60 hari</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">20 Sesi Latihan</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">Berlaku 60 Hari</span>
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs rounded-lg">Personal Coach</span>
                        </div>
                        <button class="mt-4 w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                            Pilih Paket Ini
                        </button>
                    </div>
                </div>

                <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-amber-900 text-sm mb-1">Catatan Pembelian</p>
                            <p class="text-xs text-amber-800">
                                Setelah memilih paket, Anda akan diarahkan ke halaman pembayaran. Hubungi admin untuk konfirmasi pembayaran.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function membershipData() {
    return {
        loading: true,
        registerLoading: false,
        members: [],
        showRegisterModal: false,
        showBuyPackageModal: false,
        registerType: 'self',
        selectedMember: null,
        registerForm: {
            name: '',
            phone: ''
        },

        async fetchMembers() {
            this.loading = true;
            try {
                const response = await API.get('/member/my-members');
                this.members = response.data || [];
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal memuat data member', 'error');
            } finally {
                this.loading = false;
            }
        },

        openRegisterModal(type) {
            this.registerType = type;
            this.registerForm = { name: '', phone: '' };
            this.showRegisterModal = true;
        },

        closeRegisterModal() {
            this.showRegisterModal = false;
            this.registerForm = { name: '', phone: '' };
        },

        async submitRegister() {
            this.registerLoading = true;
            try {
                const endpoint = this.registerType === 'self'
                    ? '/member/register-self'
                    : '/member/register-child';

                await API.post(endpoint, this.registerForm);

                showToast(
                    this.registerType === 'self'
                        ? 'Pendaftaran berhasil! Menunggu verifikasi admin.'
                        : 'Berhasil mendaftarkan member baru! Menunggu verifikasi admin.',
                    'success'
                );

                this.closeRegisterModal();
                await this.fetchMembers();
            } catch (error) {
                console.error('Error:', error);
                const message = error.response?.data?.message || 'Gagal mendaftarkan member';
                showToast(message, 'error');
            } finally {
                this.registerLoading = false;
            }
        },

        openBuyPackageModal(member) {
            this.selectedMember = member;
            this.showBuyPackageModal = true;
        },

        closeBuyPackageModal() {
            this.showBuyPackageModal = false;
            this.selectedMember = null;
        },

        viewMemberDetail(member) {
            showToast('Fitur detail member akan segera tersedia', 'info');
        },

        getStatusLabel(status) {
            const labels = {
                'active': 'Aktif',
                'pending': 'Pending',
                'inactive': 'Nonaktif'
            };
            return labels[status] || status;
        },

        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    }
}
</script>
@endpush
@endsection

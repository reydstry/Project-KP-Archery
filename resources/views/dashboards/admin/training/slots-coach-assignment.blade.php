@extends('layouts.admin')

@section('title', 'Slot & Coach Assignment')
@section('subtitle', 'Kelola slot per sesi dan assignment coach')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-4"
     x-data="slotCoachPage()" x-init="init()">
    <x-alert-box type="info" title="Catatan">
        Assignment coach dan update kuota slot terhubung langsung ke endpoint admin yang sudah tersedia.
    </x-alert-box>

    <div class="bg-white border border-slate-200 rounded-xl p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Training Session</label>
            <select x-model="selectedSession" @change="loadSessionDetail()" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
                <template x-for="session in sessions" :key="session.id">
                    <option :value="session.id" x-text="`#${session.id} - ${session.date} (${session.status})`"></option>
                </template>
            </select>
        </div>
        <div class="flex items-end">
            <button @click="openCreateSlot()" :disabled="!selectedSession || saving" class="w-full px-4 py-2.5 bg-[#1a307b] hover:bg-[#162a69] text-white rounded-lg text-sm font-semibold disabled:opacity-60">Tambah Slot</button>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block">
        <x-table :headers="['Waktu', 'Kuota', 'Coach Assigned', 'Status', 'Aksi']">
            <template x-for="slot in filteredSlots" :key="slot.id">
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-800" x-text="slot.time_name"></p>
                        <p class="text-xs text-slate-500" x-text="slot.time_range"></p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold text-slate-700" x-text="`${slot.filled}/${slot.quota}`"></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <template x-for="coach in slot.coaches" :key="coach">
                                <span class="px-2 py-1 text-xs rounded-md bg-slate-100 text-slate-700" x-text="coach"></span>
                            </template>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-md border font-semibold"
                              :class="slot.filled >= slot.quota ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                              x-text="slot.filled >= slot.quota ? 'Penuh' : 'Tersedia'"></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <button @click="openAssign(slot)" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-[#1a307b]/20 bg-[#1a307b]/10 text-[#1a307b]">Assign Coach</button>
                            <button x-show="editingSlotId !== slot.id" @click="startEdit(slot)" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-300 text-slate-700">Edit</button>
                            <button x-show="editingSlotId === slot.id" @click="saveSlot(slot)" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-emerald-200 text-emerald-700 bg-emerald-50">Simpan</button>
                            <button @click="deleteSlot(slot)" :disabled="saving" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-700 bg-red-50 disabled:opacity-60">Delete</button>
                        </div>
                        <div x-show="editingSlotId === slot.id" class="mt-2">
                            <input type="number" min="1" max="50" x-model.number="slot.quota" class="w-28 px-2 py-1.5 rounded border border-slate-300 text-xs">
                        </div>
                    </td>
                </tr>
            </template>
        </x-table>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-3">
        <template x-if="filteredSlots.length === 0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-center text-slate-400">
                Tidak ada slot untuk session ini
            </div>
        </template>
        <template x-for="slot in filteredSlots" :key="slot.id">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-800 text-sm" x-text="slot.time_name"></h4>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="slot.time_range"></p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-md border font-semibold ml-2"
                          :class="slot.filled >= slot.quota ? 'bg-red-50 text-red-700 border-red-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200'"
                          x-text="slot.filled >= slot.quota ? 'Penuh' : 'Tersedia'"></span>
                </div>

                <!-- Quota -->
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-slate-600">Kuota:</span>
                    <span class="font-semibold text-slate-800" x-text="`${slot.filled}/${slot.quota}`"></span>
                </div>

                <!-- Coaches -->
                <div>
                    <p class="text-xs font-semibold text-slate-600 mb-1.5">Coach Assigned:</p>
                    <div class="flex flex-wrap gap-1">
                        <template x-if="slot.coaches.length === 0">
                            <span class="text-xs text-slate-400 italic">Belum ada coach</span>
                        </template>
                        <template x-for="coach in slot.coaches" :key="coach">
                            <span class="px-2 py-1 text-xs rounded-md bg-[#1a307b]/10 text-[#1a307b] border border-[#1a307b]/20" x-text="coach"></span>
                        </template>
                    </div>
                </div>

                <!-- Edit Mode -->
                <div x-show="editingSlotId === slot.id" class="pt-2 border-t border-slate-100">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kuota Baru:</label>
                    <input type="number" min="1" max="50" x-model.number="slot.quota" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-sm">
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
                    <button @click="openAssign(slot)" class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold border border-[#1a307b]/20 bg-[#1a307b]/10 text-[#1a307b]">
                        <span class="flex items-center justify-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                            </svg>
                            Assign Coach
                        </span>
                    </button>
                    <button x-show="editingSlotId !== slot.id" @click="startEdit(slot)" class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold border border-slate-300 text-slate-700">
                        Edit
                    </button>
                    <button x-show="editingSlotId === slot.id" @click="saveSlot(slot)" class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold border border-emerald-200 text-emerald-700 bg-emerald-50">
                        Simpan
                    </button>
                    <button @click="deleteSlot(slot)" :disabled="saving" class="px-3 py-2 rounded-lg text-xs font-semibold border border-red-200 text-red-700 bg-red-50 disabled:opacity-60">
                        Hapus
                    </button>
                </div>
            </div>
        </template>
    </div>

    <div x-show="isLoading" class="text-sm text-slate-500">Memuat data session...</div>

    <!-- Assign Coach Modal -->
    <div x-show="assignModal" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" @click="assignModal=false"></div>
            
            <!-- Modal -->
            <div @click.away="assignModal=false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Assign Coach</h3>
                                <p class="text-sm text-white/80 mt-0.5" x-text="activeSlot?.time_name"></p>
                            </div>
                        </div>
                        <button @click="assignModal=false" class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <label class="text-sm font-semibold text-slate-700">Pilih Coach (<span x-text="selectedCoachIds.length"></span> dipilih)</label>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto p-1">
                            <template x-for="coach in allCoaches" :key="coach.id">
                                <label class="flex items-center gap-3 p-3 rounded-lg border-2 transition-all cursor-pointer hover:shadow-md"
                                       :class="selectedCoachIds.includes(coach.id) ? 'border-[#1a307b] bg-[#1a307b]/5' : 'border-slate-200 hover:border-[#1a307b]/30'">
                                    <input type="checkbox" 
                                           class="w-4 h-4 rounded border-slate-300 text-[#1a307b] focus:ring-2 focus:ring-[#1a307b]/30" 
                                           :value="coach.id" 
                                           x-model="selectedCoachIds">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                             :class="selectedCoachIds.includes(coach.id) ? 'bg-[#1a307b]' : 'bg-slate-100'">
                                            <svg class="w-4 h-4" :class="selectedCoachIds.includes(coach.id) ? 'text-white' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium truncate" 
                                              :class="selectedCoachIds.includes(coach.id) ? 'text-[#1a307b]' : 'text-slate-700'" 
                                              x-text="coach.name"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button @click="assignModal=false" 
                            class="px-5 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button @click="saveAssign()" 
                            :disabled="saving" 
                            class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] text-white text-sm font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <span x-show="!saving" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Assignment
                        </span>
                        <span x-show="saving" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Slot Modal -->
    <div x-show="createModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" @click="createModal=false"></div>
            
            <!-- Modal -->
            <div @click.away="createModal=false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Tambah Slot Baru</h3>
                                <p class="text-sm text-white/80 mt-0.5">Buat slot training dengan waktu & coach</p>
                            </div>
                        </div>
                        <button @click="createModal=false" class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-5 space-y-5">
                    <!-- Time Slot -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                            <svg class="w-4 h-4 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Waktu Slot *
                        </label>
                        <select x-model="createForm.session_time_id" 
                                class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition">
                            <option value="">Pilih waktu slot</option>
                            <template x-for="st in sessionTimes" :key="st.id">
                                <option :value="st.id" x-text="`${st.name} (${st.start_time} - ${st.end_time})`"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Quota -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                            <svg class="w-4 h-4 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Kuota Peserta *
                        </label>
                        <input type="number" 
                               min="1" 
                               max="50" 
                               x-model.number="createForm.max_participants" 
                               class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition"
                               placeholder="Masukkan jumlah kuota">
                    </div>

                    <!-- Coaches -->
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                            <svg class="w-4 h-4 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            Pilih Coach (<span x-text="createForm.coach_ids.length"></span> dipilih)
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border-2 border-slate-200 rounded-xl p-3">
                            <template x-for="coach in allCoaches" :key="coach.id">
                                <label class="flex items-center gap-3 p-3 rounded-lg border-2 transition-all cursor-pointer hover:shadow-md"
                                       :class="createForm.coach_ids.includes(coach.id) ? 'border-[#1a307b] bg-blue-50' : 'border-slate-200 hover:border-[#2a4a9f]'">
                                    <input type="checkbox" 
                                           :value="coach.id" 
                                           x-model="createForm.coach_ids" 
                                           class="w-4 h-4 rounded border-slate-300 text-[#1a307b] focus:ring-2 focus:ring-[#1a307b]/30">
                                    <div class="flex items-center gap-2 flex-1 min-w-0">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                                             :class="createForm.coach_ids.includes(coach.id) ? 'bg-[#1a307b]' : 'bg-slate-100'">
                                            <svg class="w-4 h-4" :class="createForm.coach_ids.includes(coach.id) ? 'text-white' : 'text-slate-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium truncate" 
                                              :class="createForm.coach_ids.includes(coach.id) ? 'text-[#1a307b]' : 'text-slate-700'" 
                                              x-text="coach.name"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button @click="createModal=false" 
                            class="px-5 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button @click="createSlot()" 
                            :disabled="saving || !createForm.session_time_id" 
                            class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] text-white text-sm font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <span x-show="!saving" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Tambah Slot
                        </span>
                        <span x-show="saving" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Membuat...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Delete Modal -->
    <div x-show="showDeleteConfirm" x-cloak @click.self="showDeleteConfirm = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showDeleteConfirm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-4">
                    <svg class="h-10 w-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Hapus Slot?</h3>
                <p class="text-slate-600 mb-6" x-text="'Hapus slot ' + slotToDelete?.time_name + '?'"></p>
                <div class="flex gap-3">
                    <button @click="showDeleteConfirm = false" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition-all duration-200">
                        Batal
                    </button>
                    <button @click="confirmDeleteSlot()" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" x-cloak @click.self="closeSuccessModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showSuccessModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Berhasil Disimpan!</h3>
                <p class="text-slate-600 mb-6" x-text="successMessage"></p>
                <button @click="closeSuccessModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" x-cloak @click.self="closeErrorModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showErrorModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Terjadi Kesalahan!</h3>
                <p class="text-slate-600 mb-6" x-text="errorMessage"></p>
                <button @click="closeErrorModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function slotCoachPage() {
    return {
        selectedSession: null,
        assignModal: false,
        createModal: false,
        activeSlot: null,
        selectedCoachIds: [],
        sessions: [],
        sessionTimes: [],
        allCoaches: [],
        slots: [],
        editingSlotId: null,
        isLoading: false,
        saving: false,
        createForm: {
            session_time_id: '',
            max_participants: 10,
            coach_ids: [],
        },
        showSuccessModal: false,
        showErrorModal: false,
        showDeleteConfirm: false,
        slotToDelete: null,
        successMessage: '',
        errorMessage: '',
        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccessModal = true;
        },
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successMessage = '';
        },
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showErrorModal = true;
        },
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        async init() {
            const params = new URLSearchParams(window.location.search);
            const defaultSession = Number(params.get('session') || 0);

            await Promise.all([this.loadCoaches(), this.loadSessions(), this.loadSessionTimes()]);
            if (this.sessions.length > 0) {
                const found = this.sessions.find((item) => Number(item.id) === defaultSession);
                this.selectedSession = found ? found.id : this.sessions[0].id;
                await this.loadSessionDetail();
            }
        },
        get filteredSlots() {
            return this.slots.filter((slot) => Number(slot.session_id) === Number(this.selectedSession));
        },
        normalizeData(payload) {
            if (Array.isArray(payload)) return payload;
            if (Array.isArray(payload?.data)) return payload.data;
            return [];
        },
        async loadCoaches() {
            try {
                const response = await window.API.get('/admin/training-coaches');
                const rows = this.normalizeData(response);
                this.allCoaches = rows.map((coach) => ({
                    id: Number(coach.id),
                    name: coach.name ?? `Coach #${coach.id}`,
                })).filter((coach) => Number.isFinite(coach.id));
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal memuat data coach.');
            }
        },
        async loadSessions() {
            this.isLoading = true;
            try {
                const response = await window.API.get('/admin/training-sessions');
                const rows = this.normalizeData(response);
                this.sessions = rows.map((session) => ({
                    id: session.id,
                    date: (session.date || '').toString().slice(0, 10),
                    status: session.status || 'open',
                }));
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal memuat data session.');
            } finally {
                this.isLoading = false;
            }
        },
        async loadSessionTimes() {
            try {
                const response = await window.API.get('/admin/session-times');
                this.sessionTimes = this.normalizeData(response).map((row) => ({
                    id: Number(row.id),
                    name: row.name,
                    start_time: row.start_time,
                    end_time: row.end_time,
                }));
            } catch (error) {
                this.sessionTimes = [];
                this.showErrorMessage(error?.message || 'Gagal memuat master session time.');
            }
        },
        async loadSessionDetail() {
            if (!this.selectedSession) {
                this.slots = [];
                return;
            }

            this.isLoading = true;
            try {
                const session = await window.API.get(`/admin/training-sessions/${this.selectedSession}`);
                const attendanceCount = Array.isArray(session?.attendances) ? session.attendances.length : 0;
                const rawSlots = Array.isArray(session?.slots) ? session.slots : [];

                this.slots = rawSlots.map((slot) => {
                    const st = slot.session_time || slot.sessionTime || {};
                    const coaches = Array.isArray(slot.coaches) ? slot.coaches : [];

                    return {
                        id: slot.id,
                        session_id: Number(this.selectedSession),
                        time_name: st.name || 'Slot',
                        time_range: `${st.start_time || ''}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time || ''}`,
                        quota: Number(slot.max_participants ?? 0),
                        filled: attendanceCount,
                        coach_ids: coaches.map((coach) => Number(coach.id)).filter(Boolean),
                        coaches: coaches.map((coach) => coach.name || `Coach #${coach.id}`),
                    };
                });
            } catch (error) {
                this.slots = [];
                this.showErrorMessage(error?.message || 'Gagal memuat detail session.');
            } finally {
                this.isLoading = false;
            }
        },
        openAssign(slot) {
            this.activeSlot = slot;
            this.selectedCoachIds = Array.isArray(slot.coach_ids) ? [...slot.coach_ids] : [];
            this.assignModal = true;
        },
        startEdit(slot) {
            this.editingSlotId = slot.id;
        },
        async saveSlot(slot) {
            if (!slot || !slot.id) return;
            this.saving = true;
            try {
                await window.API.patch(`/admin/training-session-slots/${slot.id}`, {
                    coach_ids: slot.coach_ids,
                    max_participants: Number(slot.quota),
                });
                this.editingSlotId = null;
                this.showSuccessMessage('Slot berhasil diupdate.');
                await this.loadSessionDetail();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal update slot.');
            } finally {
                this.saving = false;
            }
        },
        async saveAssign() {
            if (!this.activeSlot?.id) return;
            this.saving = true;
            try {
                await window.API.patch(`/admin/training-session-slots/${this.activeSlot.id}/coaches`, {
                    coach_ids: this.selectedCoachIds.map((id) => Number(id)),
                    max_participants: Number(this.activeSlot.quota),
                });
                this.assignModal = false;
                this.showSuccessMessage('Assignment coach berhasil disimpan.');
                await this.loadSessionDetail();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal menyimpan assignment coach.');
            } finally {
                this.saving = false;
            }
        },
        openCreateSlot() {
            this.createForm = {
                session_time_id: '',
                max_participants: 10,
                coach_ids: [],
            };
            this.createModal = true;
        },
        async createSlot() {
            if (!this.selectedSession) return;

            this.saving = true;
            try {
                await window.API.post(`/admin/training-sessions/${this.selectedSession}/slots`, {
                    session_time_id: Number(this.createForm.session_time_id),
                    max_participants: Number(this.createForm.max_participants),
                    coach_ids: this.createForm.coach_ids.map((id) => Number(id)),
                });

                this.createModal = false;
                this.showSuccessMessage('Slot berhasil ditambahkan.');
                await this.loadSessionDetail();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal menambahkan slot.');
            } finally {
                this.saving = false;
            }
        },
        async deleteSlot(slot) {
            if (!slot?.id) return;
            this.slotToDelete = slot;
            this.showDeleteConfirm = true;
        },
        async confirmDeleteSlot() {
            if (!this.slotToDelete?.id) return;
            
            this.showDeleteConfirm = false;
            this.saving = true;
            try {
                const response = await window.API.delete(`/admin/training-session-slots/${this.slotToDelete.id}`);
                this.showSuccessMessage(response?.message || 'Slot berhasil dihapus.');
                await this.loadSessionDetail();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal menghapus slot.');
            } finally {
                this.saving = false;
                this.slotToDelete = null;
            }
        },
    }
}
</script>
@endpush
@endsection

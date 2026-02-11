@extends('layouts.coach')

@section('title', 'Booking Sesi')
@section('subtitle', 'Booking slot sesi latihan untuk member (pilih member dari list)')

@section('content')
<div x-data="bookingPage()" x-init="init()" class="space-y-2 sm:space-y-4 px-2 py-2 sm:px-8 sm:py-8">

    <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 shadow-lg card-animate" style="animation-delay: 0.1s">
        <div class="p-2 sm:p-3 border-b border-slate-200">
            <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-0.5">Form Booking</h3>
            <p class="text-xs text-slate-500">Pilih sesi, pilih jam (slot), lalu pilih member dari daftar.</p>
        </div>

        <div class="p-2 sm:p-3">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-3 mb-2 sm:mb-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Training Session</label>
                    <select x-model.number="form.training_session_id" @change="onSessionChange()"
                            class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-xs sm:text-sm">
                        <option value="">-- Pilih sesi --</option>
                        <template x-for="s in sessions" :key="s.id">
                            <option :value="s.id" x-text="formatSessionLabel(s)"></option>
                        </template>
                    </select>
                    <p class="text-xs text-slate-500 mt-1" x-show="loadingSessions" x-cloak>Memuat sesi...</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Slot (Jam)</label>
                    <select x-model.number="form.training_session_slot_id" @change="onSlotChange()" :disabled="!slots.length || loadingSlots"
                            class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-slate-50 disabled:text-slate-400 text-xs sm:text-sm">
                        <option value="">-- Pilih slot --</option>
                        <template x-for="slot in slots" :key="slot.id">
                            <option :value="slot.id" x-text="formatSlotLabel(slot)"></option>
                        </template>
                    </select>
                    <p class="text-xs text-slate-500 mt-1" x-show="loadingSlots" x-cloak>Memuat slot...</p>
                </div>
            </div>

            <!-- Member Selection Section -->
            <div x-show="form.training_session_slot_id" x-cloak>
                <div class="border-t border-slate-200 pt-2 sm:pt-3">
                    <h4 class="text-sm sm:text-base font-bold text-slate-800 mb-2 sm:mb-3">Pilih Member</h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-3 mb-2 sm:mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Member</label>
                            <input type="text" x-model="memberSearch" @input="filterMembers()" 
                                   placeholder="Cari nama member..."
                                   class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Status Member</label>
                            <select x-model="statusFilter" @change="filterMembers()"
                                    class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-lg sm:rounded-xl p-2 sm:p-3 max-h-80 sm:max-h-96 overflow-y-auto">
                        <p class="text-xs sm:text-sm text-slate-600 mb-2" x-show="loadingMembers" x-cloak>Memuat member...</p>
                        <p class="text-xs sm:text-sm text-slate-600 mb-2" x-show="!loadingMembers && filteredMembers.length === 0" x-cloak>Tidak ada member.</p>
                        
                        <div class="space-y-1.5 sm:space-y-2">
                            <template x-for="member in filteredMembers" :key="member.id">
                                <label class="flex items-start gap-2 sm:gap-2.5 p-2 sm:p-2.5 bg-white rounded-lg border border-slate-200 hover:border-blue-300 cursor-pointer transition-all">
                                    <input type="checkbox" 
                                           :value="member.id"
                                           @change="toggleMemberSelection(member)"
                                           :checked="selectedMembers.some(m => m.id === member.id)"
                                           class="mt-0.5 sm:mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex items-center justify-between w-full">
                                        <!-- Kiri: Nama + Status -->
                                        <div>
                                            <p class="text-xs sm:text-sm font-semibold text-slate-900" x-text="member.name"></p>
                                        </div>

                                        <!-- Kanan: Paket -->
                                        <div class="flex flex-wrap gap-1 justify-end text-right">
                                            <template x-if="member.active_packages && member.active_packages.length > 0">
                                                <template x-for="pkg in member.active_packages" :key="pkg.id">
                                                    <div
                                                        class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded whitespace-nowrap">
                                                        <span x-text="pkg.package_name"></span>:
                                                        <span x-text="`${pkg.remaining_sessions} sesi`"></span>
                                                    </div>
                                                </template>
                                            </template>

                                            <template x-if="!member.active_packages || member.active_packages.length === 0">
                                                <p class="text-xs text-red-500">Tidak ada paket aktif</p>
                                            </template>
                                        </div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="mt-2 text-xs sm:text-sm text-slate-600" x-show="selectedMembers.length > 0" x-cloak>
                        <span class="font-semibold" x-text="selectedMembers.length"></span> member dipilih
                    </div>
                </div>
            </div>

            <div class="mt-2 sm:mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5">
                <button @click="submit()" :disabled="submitting || selectedMembers.length === 0"
                        class="w-full sm:w-auto px-4 py-2 sm:px-5 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Booking (<span x-text="selectedMembers.length"></span> member)</span>
                    <span x-show="submitting" x-cloak>Memproses...</span>
                </button>

                <button @click="resetForm()" :disabled="submitting"
                        class="w-full sm:w-auto px-4 py-2 sm:px-5 sm:py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium border border-slate-200 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingPage() {
    return {
        sessions: [],
        slots: [],
        members: [],
        filteredMembers: [],
        selectedMembers: [],
        memberSearch: '',
        statusFilter: '',
        loadingSessions: false,
        loadingSlots: false,
        loadingMembers: false,
        submitting: false,
        form: {
            training_session_id: '',
            training_session_slot_id: '',
        },

        async init() {
            await this.loadSessions();
            await this.loadMembers();
        },

        async loadSessions() {
            this.loadingSessions = true;
            try {
                const res = await window.API.get('/coach/training-sessions?status=open&start_date=' + this.today());
                this.sessions = Array.isArray(res?.data) ? res.data : [];
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat training sessions', 'error');
                this.sessions = [];
            } finally {
                this.loadingSessions = false;
            }
        },

        async onSessionChange() {
            this.form.training_session_slot_id = '';
            this.slots = [];
            this.selectedMembers = [];

            if (!this.form.training_session_id) return;

            this.loadingSlots = true;
            try {
                const session = await window.API.get(`/coach/training-sessions/${this.form.training_session_id}`);
                this.slots = session?.slots || [];
                if (!Array.isArray(this.slots)) this.slots = [];
                this.slots.sort((a, b) => (a?.session_time?.start_time || '').localeCompare(b?.session_time?.start_time || ''));
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat slot sesi', 'error');
                this.slots = [];
            } finally {
                this.loadingSlots = false;
            }
        },

        onSlotChange() {
            this.selectedMembers = [];
        },

        async loadMembers() {
            this.loadingMembers = true;
            try {
                const res = await window.API.get('/coach/members');
                this.members = Array.isArray(res?.data) ? res.data : [];
                this.filterMembers();
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat members', 'error');
                this.members = [];
                this.filteredMembers = [];
            } finally {
                this.loadingMembers = false;
            }
        },

        filterMembers() {
            let filtered = [...this.members];

            if (this.memberSearch.trim()) {
                const search = this.memberSearch.toLowerCase();
                filtered = filtered.filter(m => (m.name || '').toLowerCase().includes(search));
            }

            if (this.statusFilter) {
                filtered = filtered.filter(m => (m.status || '').toLowerCase() === this.statusFilter.toLowerCase());
            }

            this.filteredMembers = filtered;
        },

        toggleMemberSelection(member) {
            const idx = this.selectedMembers.findIndex(m => m.id === member.id);
            if (idx >= 0) {
                this.selectedMembers.splice(idx, 1);
            } else {
                // Choose first active package for booking
                const firstPackage = (member.active_packages || [])[0];
                if (!firstPackage) {
                    window.showToast(`Member ${member.name} tidak memiliki paket aktif`, 'error');
                    return;
                }
                this.selectedMembers.push({
                    id: member.id,
                    name: member.name,
                    member_package_id: firstPackage.id,
                    package_name: firstPackage.package_name,
                });
            }
        },

        async submit() {
            const slotId = Number(this.form.training_session_slot_id);

            if (!Number.isInteger(slotId) || slotId <= 0) {
                window.showToast('Pilih slot terlebih dahulu', 'error');
                return;
            }

            if (this.selectedMembers.length === 0) {
                window.showToast('Pilih minimal 1 member', 'error');
                return;
            }

            this.submitting = true;
            let successCount = 0;
            let failCount = 0;

            try {
                for (const member of this.selectedMembers) {
                    try {
                        const payload = {
                            training_session_slot_id: slotId,
                            member_package_id: member.member_package_id,
                            notes: null,
                        };

                        await window.API.post('/coach/bookings', payload);
                        successCount++;
                    } catch (e) {
                        console.error(`Failed booking for ${member.name}:`, e);
                        failCount++;
                    }
                }

                const message = `Booking selesai: ${successCount} sukses${failCount > 0 ? `, ${failCount} gagal` : ''}`;
                window.showToast(message, failCount === 0 ? 'success' : 'info');

                // Reset selection
                this.selectedMembers = [];
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal booking sesi', 'error');
            } finally {
                this.submitting = false;
            }
        },

        resetForm() {
            this.form.training_session_id = '';
            this.form.training_session_slot_id = '';
            this.slots = [];
            this.selectedMembers = [];
            this.memberSearch = '';
            this.statusFilter = '';
        },

        today() {
            const d = new Date();
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        },

        formatSessionLabel(s) {
          const date = new Date(s.date)
          return date.toLocaleDateString('id-ID', {
              weekday: 'long',
              day: '2-digit',
              month: 'long',
              year: 'numeric'
          });
        },

        formatSlotLabel(slot) {
            const st = slot?.session_time;
            const name = st?.name ? `${st.name}` : 'Slot';
            const time = (st?.start_time && st?.end_time) ? `${st.start_time} - ${st.end_time}` : '';
            const cap = slot?.max_participants ? `Kuota: ${slot.max_participants}` : '';
            return [name, time, cap].filter(Boolean).join(' • ');
        }
    }
}
</script>
@endpush

@extends('layouts.admin')

@section('title', 'Booking Sesi')
@section('subtitle', 'Pilih slot waktu latihan, lalu pilih member dari daftar.')

@section('content')
<div class="space-y-6">
    <div x-data="adminBookingPage()" x-init="init()" class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-6">
            <!-- Training Session Info -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">Training Session</label>
                    <span class="text-xs text-slate-500" x-show="loadingSessions" x-cloak>Memuat...</span>
                </div>
                <template x-if="latestSession">
                    <div class="px-4 py-3 bg-blue-50 border-2 border-blue-200 rounded-xl">
                        <p class="font-semibold text-blue-900" x-text="formatSessionLabel(latestSession)"></p>
                    </div>
                </template>
                <template x-if="!loadingSessions && !latestSession">
                    <div class="px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-500 text-sm">
                        Tidak ada training session tersedia
                    </div>
                </template>
            </div>

            <!-- Slot Selection with Bubbles -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Slot Waktu</label>
                <template x-if="loadingSlots">
                    <p class="text-sm text-slate-500">Memuat slot...</p>
                </template>
                <template x-if="!loadingSlots && slots.length === 0">
                    <p class="text-sm text-slate-500">Tidak ada slot tersedia</p>
                </template>
                <template x-if="!loadingSlots && slots.length > 0">
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                        <template x-for="slot in slots" :key="slot.id">
                            <button type="button"
                                @click="selectSlot(slot.id)"
                                :class="{
                                    'bg-blue-600 text-white border-blue-700': form.training_session_slot_id === slot.id,
                                    'bg-white text-slate-700 border-slate-200 hover:border-blue-500 hover:text-blue-600': form.training_session_slot_id !== slot.id
                                }"
                                class="px-4 py-3 border-2 rounded-xl font-medium transition-all duration-200 text-sm w-full">
                                <div class="flex flex-col items-start">
                                    <span class="font-bold" x-text="slot.session_time?.name || 'Slot'"></span>
                                    <span class="text-xs opacity-90" x-text="`${slot.session_time?.start_time || ''} - ${slot.session_time?.end_time || ''}`"></span>
                                    <span class="text-xs opacity-75 mt-1" x-text="`Kuota: ${slot.max_participants || 0}`"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div x-show="form.training_session_slot_id" x-cloak>
                <div class="border-t border-slate-200 pt-6">
                    <h4 class="text-base font-bold text-slate-800 mb-4">Pilih Member</h4>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cari Member</label>
                        <input type="text" x-model="memberSearch" @input="filterMembers()" placeholder="Cari nama member aktif..."
                               class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <p class="text-xs text-slate-500 mt-1">Hanya menampilkan member dengan status aktif</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 max-h-96 overflow-y-auto">
                        <p class="text-sm text-slate-600 mb-3" x-show="loadingMembers" x-cloak>Memuat member...</p>
                        <p class="text-sm text-slate-600 mb-3" x-show="!loadingMembers && filteredMembers.length === 0" x-cloak>Tidak ada member.</p>

                        <div class="space-y-2">
                            <template x-for="member in filteredMembers" :key="member.id">
                                <label class="flex items-start gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-blue-300 cursor-pointer transition-all">
                                    <input type="checkbox" :value="member.id" @change="toggleMemberSelection(member)"
                                           :checked="selectedMembers.some(m => m.id === member.id)"
                                           class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex items-center justify-between w-full">
                                        <div>
                                            <p class="font-semibold text-slate-900" x-text="member.name"></p>
                                        </div>

                                        <div class="flex flex-wrap gap-1 justify-end text-right">
                                            <template x-if="member.active_packages && member.active_packages.length > 0">
                                                <template x-for="pkg in member.active_packages" :key="pkg.id">
                                                    <div class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded whitespace-nowrap">
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

                    <div class="mt-3 text-sm text-slate-600" x-show="selectedMembers.length > 0" x-cloak>
                        <span class="font-semibold" x-text="selectedMembers.length"></span> member dipilih
                    </div>
                </div>
            </div>

            <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button @click="submit()" :disabled="submitting || selectedMembers.length === 0"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Booking (<span x-text="selectedMembers.length"></span> member)</span>
                    <span x-show="submitting" x-cloak>Memproses...</span>
                </button>

                <button @click="resetForm()" :disabled="submitting"
                        class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function adminBookingPage() {
    return {
        latestSession: null,
        slots: [],
        members: [],
        filteredMembers: [],
        selectedMembers: [],
        memberSearch: '',
        loadingSessions: false,
        loadingSlots: false,
        loadingMembers: false,
        submitting: false,
        form: {
            training_session_slot_id: '',
        },

        async init() {
            await this.loadSessions();
            await this.loadMembers();
        },

        async loadSessions() {
            this.loadingSessions = true;
            try {
                const res = await window.API.get('/admin/training-sessions?status=open&start_date=' + this.today());
                const sessions = Array.isArray(res?.data) ? res.data : [];
                
                // Get the latest session (first one)
                if (sessions.length > 0) {
                    this.latestSession = sessions[0];
                    await this.loadSlotsForLatestSession();
                } else {
                    this.latestSession = null;
                    this.slots = [];
                }
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat training sessions', 'error');
                this.latestSession = null;
                this.slots = [];
            } finally {
                this.loadingSessions = false;
            }
        },

        async loadSlotsForLatestSession() {
            if (!this.latestSession) return;

            this.loadingSlots = true;
            try {
                const session = await window.API.get(`/admin/training-sessions/${this.latestSession.id}`);
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

        selectSlot(slotId) {
            this.form.training_session_slot_id = slotId;
            this.selectedMembers = [];
        },

        async loadMembers() {
            this.loadingMembers = true;
            try {
                const res = await window.API.get('/admin/booking-members');
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
            // Only show active members
            let filtered = this.members.filter(m => (m.status || '').toLowerCase() === 'active');

            if (this.memberSearch.trim()) {
                const search = this.memberSearch.toLowerCase();
                filtered = filtered.filter(m => (m.name || '').toLowerCase().includes(search));
            }

            this.filteredMembers = filtered;
        },

        toggleMemberSelection(member) {
            const idx = this.selectedMembers.findIndex(m => m.id === member.id);
            if (idx >= 0) {
                this.selectedMembers.splice(idx, 1);
            } else {
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

                        await window.API.post('/admin/bookings', payload);
                        successCount++;
                    } catch (e) {
                        console.error(`Failed booking for ${member.name}:`, e);
                        failCount++;
                    }
                }

                const message = `Booking selesai: ${successCount} sukses${failCount > 0 ? `, ${failCount} gagal` : ''}`;
                window.showToast(message, failCount === 0 ? 'success' : 'info');
                this.selectedMembers = [];
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal booking sesi', 'error');
            } finally {
                this.submitting = false;
            }
        },

        resetForm() {
            this.form.training_session_slot_id = '';
            this.selectedMembers = [];
            this.memberSearch = '';
        },

        today() {
            const d = new Date();
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        },

        formatSessionLabel(s) {
            const date = new Date(s.date);
            const coachName = s?.coach?.name ? ` • ${s.coach.name}` : '';
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }) + coachName;
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
@endsection

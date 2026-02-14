@extends('layouts.coach')

@section('title', 'Booking Sesi')
@section('subtitle', 'Pilih sesi, cari member, dan kelola member yang berlatih di slot terpilih.')

@section('content')
<div class="space-y-6">
    <div x-data="coachBookingPage()" x-init="init()" class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-6 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">Pilih Training Session</label>
                    <span class="text-xs text-slate-500" x-show="loadingSessions" x-cloak>Memuat...</span>
                </div>

                <template x-if="!loadingSessions && sessions.length === 0">
                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">
                        Tidak ada training session tersedia
                    </div>
                </template>

                <template x-if="sessions.length > 0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="session in sessions" :key="session.id">
                            <button type="button"
                                @click="selectSession(session.id)"
                                :class="{
                                    'bg-[#1a307b] text-white border-[#152866]': form.training_session_id === session.id,
                                    'bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]': form.training_session_id !== session.id
                                }"
                                class="px-4 py-3 border-2 rounded-xl font-medium transition-all duration-200 text-sm text-left">
                                <p class="font-semibold" x-text="formatSessionLabel(session)"></p>
                                <p class="text-xs opacity-80 mt-1" x-text="`Status: ${(session.status || '').toUpperCase()}`"></p>
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div x-show="form.training_session_id" x-cloak>
                <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Slot Waktu</label>
                <template x-if="loadingSlots">
                    <p class="text-sm text-slate-500">Memuat slot...</p>
                </template>
                <template x-if="!loadingSlots && slots.length === 0">
                    <p class="text-sm text-slate-500">Tidak ada slot tersedia di sesi ini.</p>
                </template>
                <template x-if="!loadingSlots && slots.length > 0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="slot in slots" :key="slot.id">
                            <button type="button"
                                @click="selectSlot(slot.id)"
                                :class="{
                                    'bg-[#1a307b] text-white border-[#152866]': form.training_session_slot_id === slot.id,
                                    'bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]': form.training_session_slot_id !== slot.id
                                }"
                                class="px-4 py-3 border-2 rounded-xl font-medium transition-all duration-200 text-sm w-full text-left">
                                <p class="font-bold" x-text="slot.session_time?.name || 'Slot'"></p>
                                <p class="text-xs opacity-90" x-text="`${slot.session_time?.start_time || ''} - ${slot.session_time?.end_time || ''}`"></p>
                                <p class="text-xs opacity-80 mt-1" x-text="`Kuota: ${slot.max_participants || 0}`"></p>
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <div x-show="form.training_session_slot_id" x-cloak class="border-t border-slate-200 pt-6 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Member</label>
                    <input type="text" x-model="memberSearch" @input="filterMembers()" placeholder="Cari nama member aktif..."
                           class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b] text-sm">
                    <p class="text-xs text-slate-500 mt-1">Digunakan untuk cari member baru dan filter member yang sudah berlatih.</p>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-slate-800">Tambahkan Member ke Slot Ini</h4>
                        <span class="text-xs text-slate-500" x-show="loadingMembers" x-cloak>Memuat member...</span>
                    </div>

                    <template x-if="!loadingMembers && filteredMembers.length === 0">
                        <p class="text-sm text-slate-500">Member aktif tidak ditemukan.</p>
                    </template>

                    <div class="space-y-2 max-h-64 overflow-y-auto" x-show="filteredMembers.length > 0">
                        <template x-for="member in filteredMembers" :key="member.id">
                            <div class="flex items-start justify-between gap-3 p-3 bg-white rounded-lg border border-slate-200">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900" x-text="member.name"></p>
                                    <template x-if="member.active_packages && member.active_packages.length > 0">
                                        <p class="text-xs text-slate-500 mt-1">
                                            <span x-text="member.active_packages[0].package_name"></span>
                                            •
                                            <span x-text="`${member.active_packages[0].remaining_sessions} sesi`"></span>
                                        </p>
                                    </template>
                                    <template x-if="!member.active_packages || member.active_packages.length === 0">
                                        <p class="text-xs text-[#d12823] mt-1">Tidak ada paket aktif</p>
                                    </template>
                                </div>

                                <button type="button"
                                    @click="addMemberToSlot(member)"
                                    :disabled="submitting || isMemberAlreadyInSlot(member.id)"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all"
                                    :class="isMemberAlreadyInSlot(member.id)
                                        ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed'
                                        : 'bg-[#1a307b] text-white border-[#1a307b] hover:bg-[#152866]'">
                                    <span x-show="!isMemberAlreadyInSlot(member.id)">Tambah</span>
                                    <span x-show="isMemberAlreadyInSlot(member.id)">Sudah Ada</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-slate-800">Member Berlatih di Slot Terpilih</h4>
                        <span class="text-xs text-slate-500" x-show="loadingSlotBookings" x-cloak>Memuat...</span>
                    </div>

                    <template x-if="!loadingSlotBookings && filteredSlotBookings.length === 0">
                        <p class="text-sm text-slate-500">Belum ada member pada slot ini.</p>
                    </template>

                    <div class="space-y-2 max-h-80 overflow-y-auto" x-show="filteredSlotBookings.length > 0">
                        <template x-for="booking in filteredSlotBookings" :key="booking.id">
                            <div class="p-3 bg-white rounded-lg border border-slate-200 space-y-2">
                                <p class="text-sm font-semibold text-slate-900" x-text="booking.member_name"></p>
                                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-2 items-center">
                                    <select :id="`move-booking-${booking.id}`" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
                                        <option value="">Pilih Sesi</option>
                                        <template x-for="target in moveTargets(booking.slot_id)" :key="target.id">
                                            <option :value="target.id" x-text="target.session_time?.name || 'Sesi'"></option>
                                        </template>
                                    </select>
                                    <button type="button" @click="moveBooking(booking.id)" class="px-3 py-2 rounded-lg text-xs font-semibold bg-[#1a307b] text-white hover:bg-[#162a69]">Pindah</button>
                                    <button type="button" @click="removeBooking(booking.id, booking.member_name)" class="px-3 py-2 rounded-lg text-xs font-semibold bg-[#d12823] text-white hover:bg-[#b8231f]">Hapus</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button @click="resetForm()" :disabled="submitting"
                        class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed">
                    Reset
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function coachBookingPage() {
    return {
        sessions: [],
        selectedSession: null,
        slots: [],
        members: [],
        filteredMembers: [],
        slotBookings: [],
        filteredSlotBookings: [],
        memberSearch: '',
        loadingSessions: false,
        loadingSlots: false,
        loadingMembers: false,
        loadingSlotBookings: false,
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
                const res = await window.API.get('/coach/training-sessions?for_booking=1&status=open&start_date=' + this.today());
                this.sessions = Array.isArray(res?.data) ? res.data : [];

                if (this.sessions.length > 0) {
                    await this.selectSession(this.sessions[0].id);
                }
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat training sessions', 'error');
                this.sessions = [];
            } finally {
                this.loadingSessions = false;
            }
        },

        async selectSession(sessionId) {
            this.form.training_session_id = sessionId;
            this.form.training_session_slot_id = '';
            this.slotBookings = [];
            this.filteredSlotBookings = [];
            this.selectedSession = this.sessions.find(s => Number(s.id) === Number(sessionId)) || null;
            await this.loadSlotsForSession(sessionId);
        },

        async loadSlotsForSession(sessionId) {
            if (!sessionId) return;

            this.loadingSlots = true;
            try {
                const session = await window.API.get(`/coach/training-sessions/${sessionId}?for_booking=1`);
                this.slots = Array.isArray(session?.slots) ? session.slots : [];
                this.slots.sort((a, b) => (a?.session_time?.start_time || '').localeCompare(b?.session_time?.start_time || ''));

                if (this.slots.length > 0) {
                    await this.selectSlot(this.slots[0].id);
                }
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat slot sesi', 'error');
                this.slots = [];
            } finally {
                this.loadingSlots = false;
            }
        },

        async selectSlot(slotId) {
            this.form.training_session_slot_id = slotId;
            await this.loadSlotBookings();
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

        async loadSlotBookings() {
            const sessionId = Number(this.form.training_session_id);
            const slotId = Number(this.form.training_session_slot_id);
            if (!sessionId || !slotId) {
                this.slotBookings = [];
                this.filteredSlotBookings = [];
                return;
            }

            this.loadingSlotBookings = true;
            try {
                const res = await window.API.get(`/coach/training-sessions/${sessionId}/bookings?for_booking=1&slot_id=${slotId}`);
                const bookings = Array.isArray(res?.bookings) ? res.bookings : [];
                this.slotBookings = bookings.map((booking) => ({
                    id: booking.id,
                    member_id: booking.member_id,
                    member_name: booking.member_name,
                    slot_id: booking?.slot?.id || slotId,
                }));
                this.filterMembers();
            } catch (error) {
                console.error(error);
                this.slotBookings = [];
                this.filteredSlotBookings = [];
                window.showToast(error?.message || 'Gagal memuat member pada slot ini', 'error');
            } finally {
                this.loadingSlotBookings = false;
            }
        },

        filterMembers() {
            let memberFiltered = this.members.filter(m => (m.status || '').toLowerCase() === 'active');
            const search = this.memberSearch.trim().toLowerCase();

            if (search) {
                memberFiltered = memberFiltered.filter(m => (m.name || '').toLowerCase().includes(search));
            }

            this.filteredMembers = memberFiltered;

            let slotFiltered = [...this.slotBookings];
            if (search) {
                slotFiltered = slotFiltered.filter(b => (b.member_name || '').toLowerCase().includes(search));
            }
            this.filteredSlotBookings = slotFiltered;
        },

        isMemberAlreadyInSlot(memberId) {
            return this.slotBookings.some(b => Number(b.member_id) === Number(memberId));
        },

        moveTargets(currentSlotId) {
            return this.slots.filter(slot => Number(slot.id) !== Number(currentSlotId));
        },

        async addMemberToSlot(member) {
            const slotId = Number(this.form.training_session_slot_id);
            if (!slotId) {
                window.showToast('Pilih slot terlebih dahulu', 'error');
                return;
            }

            if (this.isMemberAlreadyInSlot(member.id)) {
                window.showToast('Member sudah ada pada slot ini', 'info');
                return;
            }

            const firstPackage = (member.active_packages || [])[0];
            if (!firstPackage) {
                window.showToast(`Member ${member.name} tidak memiliki paket aktif`, 'error');
                return;
            }

            this.submitting = true;
            try {
                await window.API.post('/coach/bookings', {
                    training_session_slot_id: slotId,
                    member_package_id: firstPackage.id,
                    notes: null,
                });
                window.showToast(`Member ${member.name} berhasil ditambahkan`, 'success');
                await this.loadSlotBookings();
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal menambahkan member ke slot', 'error');
            } finally {
                this.submitting = false;
            }
        },

        async moveBooking(bookingId) {
            const select = document.getElementById(`move-booking-${bookingId}`);
            const targetSlotId = Number(select?.value || 0);

            if (!targetSlotId) {
                window.showToast('Pilih sesi tujuan terlebih dahulu', 'error');
                return;
            }

            this.submitting = true;
            try {
                await window.API.patch(`/coach/bookings/${bookingId}`, {
                    training_session_slot_id: targetSlotId,
                });
                window.showToast('Member berhasil dipindahkan', 'success');
                await this.loadSlotBookings();
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memindahkan member', 'error');
            } finally {
                this.submitting = false;
            }
        },

        async removeBooking(bookingId, memberName) {
            if (!confirm(`Hapus member ${memberName} dari slot ini?`)) {
                return;
            }

            this.submitting = true;
            try {
                await window.API.delete(`/coach/bookings/${bookingId}`);
                window.showToast(`Member ${memberName} berhasil dihapus`, 'success');
                await this.loadSlotBookings();
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal menghapus member dari slot', 'error');
            } finally {
                this.submitting = false;
            }
        },

        resetForm() {
            this.memberSearch = '';
            this.filterMembers();
            if (this.sessions.length > 0) {
                this.selectSession(this.sessions[0].id);
            }
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
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }
    };
}
</script>
@endpush
@endsection

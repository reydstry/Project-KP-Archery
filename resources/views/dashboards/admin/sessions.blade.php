@extends('layouts.admin')

@section('title', 'Training Sessions')
@section('subtitle', 'Daftar sesi latihan untuk edit atau hapus')

@section('content')
<div class="space-y-6" x-data="adminSessionsPage()" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="w-full sm:max-w-sm">
                <input type="text" x-model="search" @input="applyFilters()" placeholder="Cari tanggal sesi (YYYY-MM-DD)..."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b]">
            </div>
            <a href="{{ route('admin.sessions.create') }}" class="w-full sm:w-auto px-5 py-3 bg-[#1a307b] hover:bg-[#162a6b] text-white rounded-xl font-semibold text-sm text-center transition-colors">
                Create Session
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="sessionsContainer">
        <template x-if="loading">
            <div class="col-span-full text-center py-10 text-slate-500">Loading sessions...</div>
        </template>

        <template x-if="!loading && filtered.length === 0">
            <div class="col-span-full text-center py-10 text-slate-500">Tidak ada sesi ditemukan</div>
        </template>

        <template x-for="session in filtered" :key="session.id">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <p class="text-xs text-slate-500">Tanggal</p>
                        <p class="font-bold text-slate-900"
                        x-text="new Date(session.date).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        })"></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize"
                        :class="session.status === 'open' ? 'bg-[#1a307b]/10 text-[#1a307b]' : (session.status === 'closed' ? 'bg-slate-100 text-slate-700' : 'bg-[#d12823]/10 text-[#d12823]')"
                        x-text="session.status"></span>
                </div>

                <p class="text-sm text-slate-600 mb-4">
                    <span x-text="(session.slots || []).length"></span> slot
                </p>
                <p class="text-xs mb-4" :class="getSessionBookingsCount(session) > 0 ? 'text-[#d12823]' : 'text-emerald-600'">
                    Booking: <span class="font-semibold" x-text="getSessionBookingsCount(session)"></span>
                </p>

                <div class="space-y-2 mb-4 max-h-40 overflow-y-auto">
                    <template x-for="slot in (session.slots || [])" :key="slot.id">
                        <div class="text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg">
                            <p class="font-semibold text-slate-800" x-text="slot.session_time?.name || 'Slot'"></p>
                            <p class="text-slate-600" x-text="`${slot.session_time?.start_time || ''}${slot.session_time?.start_time && slot.session_time?.end_time ? ' - ' : ''}${slot.session_time?.end_time || ''}`"></p>
                            <p class="text-slate-500" x-text="`Kuota: ${slot.max_participants || 0}`"></p>
                        </div>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <a :href="`/admin/sessions/${session.id}/edit`" class="flex-1 px-3 py-2 bg-[#1a307b] hover:bg-[#162a6b] text-white text-sm font-medium rounded-lg text-center transition-colors">Edit</a>
                    <button type="button" @click="deleteSession(session)" :disabled="getSessionBookingsCount(session) > 0" :class="getSessionBookingsCount(session) > 0 ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' : 'bg-[#d12823]/10 hover:bg-[#d12823]/15 text-[#d12823] border-[#d12823]/20'" class="px-3 py-2 border text-sm font-medium rounded-lg transition-colors">Delete</button>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
function adminSessionsPage() {
    return {
        sessions: [],
        filtered: [],
        search: '',
        loading: false,

        async init() {
            this.loading = true;
            try {
                const res = await window.API.get('/admin/training-sessions');
                this.sessions = Array.isArray(res?.data) ? res.data : [];
                this.sessions.sort((a, b) => new Date(b.date) - new Date(a.date));
                this.applyFilters();
            } catch (e) {
                console.error(e);
                window.showToast(e?.message || 'Gagal memuat sessions', 'error');
            } finally {
                this.loading = false;
            }
        },

        applyFilters() {
            const q = this.search.trim().toLowerCase();
            this.filtered = this.sessions.filter(s => !q || String(s.date || '').toLowerCase().includes(q));
        },

        getSessionBookingsCount(session) {
            const slots = Array.isArray(session?.slots) ? session.slots : [];
            return slots.reduce((total, slot) => {
                const bookings = Array.isArray(slot?.confirmed_bookings)
                    ? slot.confirmed_bookings
                    : (Array.isArray(slot?.confirmedBookings) ? slot.confirmedBookings : []);
                return total + bookings.length;
            }, 0);
        },

        async deleteSession(session) {
            const sessionId = Number(session?.id || 0);
            if (!sessionId) return;

            if (this.getSessionBookingsCount(session) > 0) {
                window.showToast('Tidak bisa hapus session yang sudah memiliki booking.', 'error');
                return;
            }

            if (!confirm('Hapus session ini?')) return;
            try {
                await window.API.delete(`/admin/training-sessions/${sessionId}`);
                this.sessions = this.sessions.filter(s => Number(s.id) !== Number(sessionId));
                this.applyFilters();
                window.showToast('Session deleted', 'success');
            } catch (e) {
                window.showToast(e?.message || 'Gagal menghapus session', 'error');
            }
        }
    }
}
</script>
@endpush
@endsection

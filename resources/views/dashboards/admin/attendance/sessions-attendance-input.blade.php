@extends('layouts.admin')

@section('title', 'Input Kehadiran')
@section('subtitle', 'Catat member yang benar-benar hadir per sesi')

@section('content')
<div class="space-y-4" x-data="attendanceInputPage({{ (int) $sessionId }})" x-init="init()">
    <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
                <p class="text-xs font-semibold text-slate-500">Session ID</p>
                <p class="text-lg font-bold text-slate-900" x-text="session.id || '-'"></p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500">Tanggal</p>
                <p class="text-lg font-bold text-slate-900" x-text="session.date || '-'"></p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 space-y-3">
        <div class="flex items-center gap-2">
            <input type="text" x-model="search" @input.debounce.300ms="loadMembers()" placeholder="Cari member aktif..." class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
            <button type="button" @click="loadMembers()" class="px-3 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold">Cari</button>
        </div>

        <div class="max-h-80 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100">
            <template x-if="members.length === 0">
                <p class="p-4 text-sm text-slate-500">Tidak ada member aktif.</p>
            </template>

            <template x-for="member in members" :key="member.id">
                <label class="flex items-center gap-3 p-3 hover:bg-slate-50 cursor-pointer">
                    <input type="checkbox" :value="member.id" x-model="selectedMemberIds" class="rounded border-slate-300 text-[#1a307b] focus:ring-[#1a307b]">
                    <div>
                        <p class="text-sm font-semibold text-slate-800" x-text="member.name"></p>
                        <p class="text-xs text-slate-500" x-text="member.phone || '-' "></p>
                    </div>
                </label>
            </template>
        </div>

        <div class="flex items-center justify-between gap-3">
            <p class="text-sm text-slate-600">Dipilih: <span class="font-bold" x-text="selectedMemberIds.length"></span> member</p>
            <div class="flex items-center gap-2">
                <button type="button" @click="markAllPresent()" :disabled="submitting || members.length===0" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold disabled:opacity-60">Mark all present</button>
                <button type="button" @click="markAllAbsent()" :disabled="submitting || selectedMemberIds.length===0" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold disabled:opacity-60">Mark all absent</button>
                <button type="button" @click="submitAttendance()" :disabled="submitting" class="px-4 py-2.5 rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-sm font-semibold">
                <span x-show="!submitting">Simpan Kehadiran</span>
                <span x-show="submitting">Menyimpan...</span>
            </button>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5">
        <h3 class="text-sm font-bold text-slate-700 mb-2">Kehadiran Tercatat</h3>
        <div class="space-y-2 max-h-72 overflow-y-auto">
            <template x-if="attendances.length === 0">
                <p class="text-sm text-slate-500">Belum ada data kehadiran.</p>
            </template>
            <template x-for="attendance in attendances" :key="attendance.id">
                <div class="px-3 py-2 rounded-lg bg-slate-50 border border-slate-200">
                    <p class="text-sm font-medium text-slate-800" x-text="attendance.member?.name || ('Member #' + attendance.member_id)"></p>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function attendanceInputPage(sessionId) {
    return {
        sessionId,
        session: {},
        members: [],
        attendances: [],
        selectedMemberIds: [],
        search: '',
        submitting: false,

        async init() {
            await Promise.all([
                this.loadMembers(),
                this.loadAttendances(),
            ]);
        },

        async loadMembers() {
            try {
                const params = new URLSearchParams();
                if (this.search) params.set('search', this.search);
                params.set('limit', '200');
                const data = await window.API.get(`/admin/attendance/active-members?${params.toString()}`);
                this.members = Array.isArray(data?.data) ? data.data : [];
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat member aktif', 'error');
            }
        },

        async loadAttendances() {
            try {
                const data = await window.API.get(`/admin/training-sessions/${this.sessionId}/attendances`);
                this.session = data?.session || {};
                this.attendances = Array.isArray(data?.attendances) ? data.attendances : [];
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat attendance', 'error');
            }
        },

        async submitAttendance() {
            this.submitting = true;
            try {
                await window.API.put(`/admin/training-sessions/${this.sessionId}/attendances`, {
                    session_id: Number(this.sessionId),
                    member_ids: this.selectedMemberIds.map(Number),
                });

                window.showToast('Attendance berhasil diperbarui', 'success');
                await this.loadAttendances();
            } catch (error) {
                window.showToast(error?.message || 'Gagal menyimpan attendance', 'error');
            } finally {
                this.submitting = false;
            }
        },
        markAllPresent() {
            this.selectedMemberIds = this.members.map((member) => Number(member.id));
        },
        markAllAbsent() {
            this.selectedMemberIds = [];
        },
    }
}
</script>
@endpush
@endsection

@extends('admin.app')

@section('title', 'Slot & Coach Assignment')
@section('subtitle', 'Kelola slot per sesi dan assignment coach')

@section('content')
<div class="space-y-4" x-data="slotCoachPage()" x-init="init()">
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
            <button @click="showCreateSlotInfo()" class="w-full px-4 py-2.5 bg-[#1a307b] hover:bg-[#162a69] text-white rounded-lg text-sm font-semibold">Tambah Slot</button>
        </div>
    </div>

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
                        <button @click="showDeleteSlotInfo()" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-700 bg-red-50">Delete</button>
                    </div>
                    <div x-show="editingSlotId === slot.id" class="mt-2">
                        <input type="number" min="1" max="50" x-model.number="slot.quota" class="w-28 px-2 py-1.5 rounded border border-slate-300 text-xs">
                    </div>
                </td>
            </tr>
        </template>
    </x-table>

    <div x-show="isLoading" class="text-sm text-slate-500">Memuat data session...</div>

    <div x-show="assignModal" x-cloak class="fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/40" @click="assignModal=false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <h3 class="text-lg font-bold text-slate-900">Assign Coach</h3>
                <p class="text-sm text-slate-500" x-text="activeSlot?.time_name"></p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <template x-for="coach in allCoaches" :key="coach.id">
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-200">
                            <input type="checkbox" class="rounded border-slate-300 text-[#1a307b]" :value="coach.id" x-model="selectedCoachIds">
                            <span class="text-sm text-slate-700" x-text="coach.name"></span>
                        </label>
                    </template>
                </div>
                <div class="flex justify-end gap-2">
                    <button @click="assignModal=false" class="px-4 py-2 rounded-lg border border-slate-300 text-sm">Batal</button>
                    <button @click="saveAssign()" :disabled="saving" class="px-4 py-2 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-50">
                        <span x-show="!saving">Simpan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
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
        activeSlot: null,
        selectedCoachIds: [],
        sessions: [],
        allCoaches: [],
        slots: [],
        editingSlotId: null,
        isLoading: false,
        saving: false,
        async init() {
            await Promise.all([this.loadCoaches(), this.loadSessions()]);
            if (this.sessions.length > 0) {
                this.selectedSession = this.sessions[0].id;
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
                const response = await window.API.get('/admin/coaches');
                const rows = this.normalizeData(response);
                this.allCoaches = rows.map((coach) => ({
                    id: Number(coach.id ?? coach.user_id),
                    name: coach.name ?? coach.user?.name ?? `Coach #${coach.id}`,
                })).filter((coach) => Number.isFinite(coach.id));
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat data coach.', 'error');
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
                window.showToast(error?.message || 'Gagal memuat data session.', 'error');
            } finally {
                this.isLoading = false;
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
                window.showToast(error?.message || 'Gagal memuat detail session.', 'error');
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
                await window.API.patch(`/admin/training-session-slots/${slot.id}/coaches`, {
                    coach_ids: slot.coach_ids,
                    max_participants: Number(slot.quota),
                });
                this.editingSlotId = null;
                window.showToast('Slot berhasil diupdate.', 'success');
                await this.loadSessionDetail();
            } catch (error) {
                window.showToast(error?.message || 'Gagal update slot.', 'error');
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
                window.showToast('Assignment coach berhasil disimpan.', 'success');
                await this.loadSessionDetail();
            } catch (error) {
                window.showToast(error?.message || 'Gagal menyimpan assignment coach.', 'error');
            } finally {
                this.saving = false;
            }
        },
        showCreateSlotInfo() {
            window.showToast('Penambahan/hapus slot belum memiliki endpoint admin khusus. Gunakan edit session sesuai flow backend saat ini.', 'info');
        },
        showDeleteSlotInfo() {
            window.showToast('Hapus slot individual belum tersedia pada API admin saat ini.', 'warning');
        },
    }
}
</script>
@endpush
@endsection

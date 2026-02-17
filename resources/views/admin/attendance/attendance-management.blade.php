@extends('admin.app')

@section('title', 'Attendance Management')
@section('subtitle', 'Kelola kehadiran member per session dan slot')

@section('content')
<div class="space-y-4" x-data="attendancePage()" x-init="init()">
    <x-alert-box type="info" title="Attendance per Session">
        Penyimpanan attendance saat ini bersifat per session. Filter slot dipakai untuk konteks tampilan kuota.
    </x-alert-box>

    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Session</label>
                <select x-model="selectedSession" @change="onSessionChange()" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30" :disabled="isLoading">
                    <template x-for="session in sessions" :key="session.id">
                        <option :value="session.id" x-text="`#${session.id} - ${session.date}`"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Slot</label>
                <select x-model="selectedSlot" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30" :disabled="isLoading || slotOptions.length === 0">
                    <template x-for="slot in slotOptions" :key="slot.id">
                        <option :value="slot.id" x-text="slot.name"></option>
                    </template>
                </select>
            </div>
            <div class="flex items-end">
                <div class="w-full grid grid-cols-2 gap-2">
                  <x-stat-card title="Kuota Terpakai" tone="red">
                    <span x-text="quotaUsage"></span>
                  </x-stat-card>
                  <x-stat-card title="Total Hadir" tone="green">
                    <span x-text="presentCount"></span>
                  </x-stat-card>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-3">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" x-model="search" @input.debounce.300ms="loadActiveMembers()" placeholder="Cari member aktif..." class="flex-1 px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#1a307b]/30" :disabled="isLoading">
            <x-button-primary @click="loadActiveMembers()" ::disabled="isLoading">Refresh Member</x-button-primary>
            <x-button-danger @click="selectedMemberIds = []" ::disabled="saving || selectedMemberIds.length === 0">Reset Pilihan</x-button-danger>
            <x-button-primary @click="openSaveConfirm()" ::disabled="saving || !selectedSession || selectedMemberIds.length === 0">
                <span x-show="!saving">Simpan Attendance</span>
                <span x-show="saving">Menyimpan...</span>
            </x-button-primary>
        </div>
    </div>

    <x-table :headers="['Member', 'Paket', 'Status Attendance', 'Aksi']">
        <template x-if="isLoading">
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Memuat data attendance...</td>
            </tr>
        </template>
        <template x-if="!isLoading && filteredMembers.length === 0">
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Member aktif tidak ditemukan.</td>
            </tr>
        </template>
        <template x-for="row in filteredMembers" :key="row.id">
            <tr>
                <td class="px-4 py-3">
                    <p class="font-semibold text-slate-800" x-text="row.name"></p>
                    <p class="text-xs text-slate-500" x-text="row.phone"></p>
                </td>
                <td class="px-4 py-3 text-slate-700" x-text="row.package"></td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-md border font-semibold"
                          :class="isPresent(row.id) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'"
                          x-text="isPresent(row.id) ? 'Present' : 'Absent'"></span>
                </td>
                <td class="px-4 py-3">
                    <button @click="toggleMember(row.id)"
                            :disabled="saving"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold"
                            :class="isPresent(row.id) ? 'bg-slate-100 text-slate-700 border border-slate-300' : 'bg-emerald-600 text-white'"
                            x-text="isPresent(row.id) ? 'Set Absent' : 'Set Present'"></button>
                </td>
            </tr>
        </template>
    </x-table>

    <x-modal x-show="showConfirmSave" @click.self="showConfirmSave = false" title="Konfirmasi Simpan Attendance" maxWidth="max-w-md">
        <div class="space-y-4">
            <p class="text-sm text-slate-600">Simpan kehadiran untuk <span class="font-semibold" x-text="presentCount"></span> member pada session terpilih?</p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="showConfirmSave = false" class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700">Batal</button>
                <x-button-primary @click="saveAttendance()" ::disabled="saving">
                    <span x-show="!saving">Ya, Simpan</span>
                    <span x-show="saving">Menyimpan...</span>
                </x-button-primary>
            </div>
        </div>
    </x-modal>
</div>

@push('scripts')
<script>
function attendancePage() {
    return {
        selectedSession: null,
        selectedSlot: null,
        search: '',
        sessions: [],
        slots: [],
        members: [],
        packageByMember: {},
        selectedMemberIds: [],
        isLoading: false,
        saving: false,
        showConfirmSave: false,
        async init() {
            await Promise.all([this.loadSessions(), this.loadMemberPackages(), this.loadActiveMembers()]);
            if (this.sessions.length > 0) {
                this.selectedSession = this.sessions[0].id;
                this.selectedSlot = this.slotOptions[0]?.id || null;
                await this.loadAttendances();
            }
        },
        normalizeData(payload) {
            if (Array.isArray(payload)) return payload;
            if (Array.isArray(payload?.data)) return payload.data;
            return [];
        },
        async loadSessions() {
            this.isLoading = true;
            try {
                const response = await window.API.get('/admin/training-sessions');
                const rows = this.normalizeData(response);
                this.sessions = rows.map((session) => ({
                    id: session.id,
                    date: (session.date || '').toString().slice(0, 10),
                }));

                this.slots = rows.flatMap((session) => {
                    const sessionSlots = Array.isArray(session.slots) ? session.slots : [];
                    return sessionSlots.map((slot) => {
                        const st = slot.session_time || slot.sessionTime || {};
                        return {
                            id: slot.id,
                            session_id: session.id,
                            quota: Number(slot.max_participants ?? 0),
                            name: `${st.name || 'Slot'} (${st.start_time || ''}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time || ''})`,
                        };
                    });
                });
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat data session.', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        async loadMemberPackages() {
            try {
                const response = await window.API.get('/admin/member-packages');
                const rows = this.normalizeData(response);
                this.packageByMember = rows.reduce((carry, item) => {
                    const memberId = Number(item?.member_id ?? item?.member?.id);
                    if (!memberId) return carry;
                    const packageName = item?.package?.name || '-';
                    carry[memberId] = packageName;
                    return carry;
                }, {});
            } catch (error) {
                this.packageByMember = {};
            }
        },
        async loadActiveMembers() {
            try {
                const response = await window.API.get(`/admin/attendance/active-members?search=${encodeURIComponent(this.search)}`);
                const rows = this.normalizeData(response);
                this.members = rows.map((member) => ({
                    id: member.id,
                    name: member.name,
                    phone: member.phone || '-',
                    package: this.packageByMember[member.id] || '-',
                }));
            } catch (error) {
                window.showToast(error?.message || 'Gagal memuat member aktif.', 'error');
            }
        },
        async loadAttendances() {
            if (!this.selectedSession) return;
            this.isLoading = true;
            try {
                const response = await window.API.get(`/admin/training-sessions/${this.selectedSession}/attendances`);
                const attendances = Array.isArray(response?.attendances) ? response.attendances : [];
                this.selectedMemberIds = attendances.map((attendance) => Number(attendance.member_id)).filter(Boolean);
            } catch (error) {
                this.selectedMemberIds = [];
                window.showToast(error?.message || 'Gagal memuat attendance.', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        async onSessionChange() {
            this.selectedSlot = this.slotOptions[0]?.id || null;
            await this.loadAttendances();
        },
        openSaveConfirm() {
            if (!this.selectedSession || this.selectedMemberIds.length === 0) return;
            this.showConfirmSave = true;
        },
        isPresent(memberId) {
            return this.selectedMemberIds.includes(Number(memberId));
        },
        toggleMember(memberId) {
            const id = Number(memberId);
            if (this.isPresent(id)) {
                this.selectedMemberIds = this.selectedMemberIds.filter((value) => Number(value) !== id);
                return;
            }
            this.selectedMemberIds = [...this.selectedMemberIds, id];
        },
        async saveAttendance() {
            if (!this.selectedSession) return;
            if (this.selectedMemberIds.length === 0) {
                window.showToast('Pilih minimal 1 member hadir.', 'warning');
                return;
            }

            this.saving = true;
            try {
                await window.API.post(`/admin/training-sessions/${this.selectedSession}/attendances`, {
                    session_id: Number(this.selectedSession),
                    member_ids: this.selectedMemberIds,
                });
                window.showToast('Attendance berhasil disimpan.', 'success');
                this.showConfirmSave = false;
                await this.loadAttendances();
            } catch (error) {
                window.showToast(error?.message || 'Gagal menyimpan attendance.', 'error');
            } finally {
                this.saving = false;
            }
        },
        get slotOptions() {
            return this.slots.filter((slot) => Number(slot.session_id) === Number(this.selectedSession));
        },
        get filteredMembers() {
            return this.members;
        },
        get presentCount() {
            return this.selectedMemberIds.length;
        },
        get quotaUsage() {
            const slot = this.slotOptions.find((item) => Number(item.id) === Number(this.selectedSlot));
            const quota = Number(slot?.quota ?? 0);
            return `${this.presentCount} / ${quota}`;
        },
    }
}
</script>
@endpush
@endsection

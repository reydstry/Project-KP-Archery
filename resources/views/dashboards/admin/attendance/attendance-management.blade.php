@extends('layouts.admin')

@section('title', 'Attendance')
@section('subtitle', 'Kelola kehadiran member seperti flow coach')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-4 sm:p-6 space-y-6">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">Pilih Training Session</label>
                    <span class="text-xs text-slate-500" id="loadingSessionsText" style="display:none;">Memuat...</span>
                </div>
                <div id="sessionsContainer">
                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">
                        Memuat training session...
                    </div>
                </div>
            </div>

            <div id="slotsSection" class="hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Slot Waktu</label>
                <div id="slotsContainer">
                    <p class="text-sm text-slate-500">Pilih training session terlebih dahulu</p>
                </div>
            </div>
        </div>
    </div>

    <div id="sessionDetailsCard" class="bg-white rounded-2xl border border-slate-200 shadow-sm hidden">
        <div class="p-4 sm:p-6 space-y-5">
            <div class="pb-5 border-b border-slate-200">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-1" id="sessionTitle">Sesi Latihan</h2>
                        <p class="text-sm text-slate-600" id="sessionDateTime">-</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 w-full lg:w-auto">
                        <div class="text-center px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-2xl font-bold text-green-600" id="presentCount">0</p>
                            <p class="text-sm text-green-700 font-semibold">Member Hadir</p>
                        </div>
                        <div class="text-center px-4 py-3 bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-2xl font-bold text-slate-600" id="totalCount">0</p>
                            <p class="text-sm text-slate-700 font-semibold">Member Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Member</label>
                <input type="text" id="searchParticipant" placeholder="Cari nama member..."
                       oninput="filterParticipants()"
                       class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b] text-sm">
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                <h3 class="text-sm font-bold text-slate-900 mb-1">Daftar Member Aktif</h3>
                <p class="text-xs text-slate-600 mb-3">Klik badge untuk ubah status hadir/tidak hadir.</p>
                <div id="participantsContainer" class="space-y-2 max-h-[60vh] sm:max-h-96 overflow-y-auto">
                    <div id="loadingState" class="text-center py-8">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-slate-200 border-t-slate-600 mx-auto mb-3"></div>
                        <p class="text-slate-600 text-sm">Memuat daftar member...</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button onclick="saveAttendance()" id="saveBtn"
                        class="w-full sm:w-auto px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl text-sm font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed">
                    Simpan Kehadiran
                </button>
            </div>
        </div>
    </div>

    <div id="emptyState" class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Pilih Sesi Dulu</h3>
            <p class="text-sm text-slate-600">Pilih training session dan slot waktu di atas untuk melihat daftar member.</p>
        </div>
    </div>
</div>

<script>
let currentSessionId = null;
let currentSlotId = null;
let currentSession = null;
let participants = [];
let allSessions = [];
let allSlots = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSessions();
    const input = document.getElementById('searchParticipant');
    if (input) input.addEventListener('input', filterParticipants);
});

function loadSessions() {
    const container = document.getElementById('sessionsContainer');
    const loadingText = document.getElementById('loadingSessionsText');

    if (loadingText) loadingText.style.display = 'inline';

    window.API.get('/admin/training-sessions')
        .then((data) => {
            allSessions = data?.data || [];
            if (loadingText) loadingText.style.display = 'none';

            if (allSessions.length === 0) {
                container.innerHTML = '<div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">Tidak ada training session tersedia</div>';
                return;
            }

            const sessionsHtml = allSessions.map((session) => {
                const formattedDate = formatIndonesianDate(session.date || '');
                const status = (session.status || '').toUpperCase();

                return `
                    <button type="button" data-session-id="${session.id}" onclick="selectSession(${session.id})"
                        class="session-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm text-left bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]">
                        <p class="font-semibold">${escapeHtml(formattedDate)}</p>
                        <p class="text-xs opacity-80 mt-1">Status: ${escapeHtml(status)}</p>
                    </button>
                `;
            }).join('');

            container.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">${sessionsHtml}</div>`;
        })
        .catch((error) => {
            if (loadingText) loadingText.style.display = 'none';
            container.innerHTML = '<div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-red-500 text-sm">Gagal memuat session</div>';
            showNotification(error?.message || 'Gagal memuat session', 'error');
        });
}

function selectSession(sessionId) {
    currentSessionId = sessionId;
    currentSlotId = null;

    document.querySelectorAll('.session-btn').forEach((btn) => {
        const id = Number(btn.getAttribute('data-session-id'));
        const selected = id === sessionId;
        btn.className = selected
            ? 'session-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm text-left bg-[#1a307b] text-white border-[#152866]'
            : 'session-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm text-left bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]';
    });

    loadSlots(sessionId);

    document.getElementById('sessionDetailsCard')?.classList.add('hidden');
    document.getElementById('emptyState')?.classList.remove('hidden');
}

function loadSlots(sessionId) {
    const slotsSection = document.getElementById('slotsSection');
    const slotsContainer = document.getElementById('slotsContainer');

    slotsSection?.classList.remove('hidden');
    slotsContainer.innerHTML = '<p class="text-sm text-slate-500">Memuat slot...</p>';

    window.API.get(`/admin/training-sessions/${sessionId}`)
        .then((session) => {
            currentSession = session;
            allSlots = Array.isArray(session.slots) ? session.slots : [];

            if (allSlots.length === 0) {
                slotsContainer.innerHTML = '<p class="text-sm text-slate-500">Tidak ada slot tersedia di sesi ini.</p>';
                return;
            }

            const sortedSlots = [...allSlots].sort((a, b) => {
                const aTime = (a?.session_time?.start_time || a?.sessionTime?.start_time || '');
                const bTime = (b?.session_time?.start_time || b?.sessionTime?.start_time || '');
                return aTime.localeCompare(bTime);
            });

            const slotsHtml = sortedSlots.map((slot) => {
                const st = slot.session_time || slot.sessionTime || {};
                const name = st.name || 'Slot';
                const startTime = st.start_time || '';
                const endTime = st.end_time || '';
                const capacity = Number(slot.max_participants || 0);

                return `
                    <button type="button" data-slot-id="${slot.id}" onclick="selectSlot(${slot.id})"
                        class="slot-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm w-full text-left bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]">
                        <p class="font-bold">${escapeHtml(name)}</p>
                        <p class="text-xs opacity-90">${escapeHtml(startTime)} - ${escapeHtml(endTime)}</p>
                        <p class="text-xs opacity-80 mt-1">Kuota: ${capacity}</p>
                    </button>
                `;
            }).join('');

            slotsContainer.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">${slotsHtml}</div>`;
        })
        .catch((error) => {
            slotsContainer.innerHTML = '<p class="text-sm text-red-500">Gagal memuat slot</p>';
            showNotification(error?.message || 'Gagal memuat slot', 'error');
        });
}

function selectSlot(slotId) {
    currentSlotId = slotId;

    document.querySelectorAll('.slot-btn').forEach((btn) => {
        const id = Number(btn.getAttribute('data-slot-id'));
        const selected = id === slotId;
        btn.className = selected
            ? 'slot-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm w-full text-left bg-[#1a307b] text-white border-[#152866]'
            : 'slot-btn px-4 py-3 border-2 rounded-xl font-medium transition text-sm w-full text-left bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]';
    });

    loadAttendanceData(currentSessionId, slotId);
}

function loadAttendanceData(sessionId, slotId) {
    Promise.all([
        window.API.get('/admin/attendance/active-members?limit=300'),
        window.API.get(`/admin/training-sessions/${sessionId}/attendances`),
    ])
    .then(([membersResponse, attendanceResponse]) => {
        const members = membersResponse?.data || [];
        const attendances = attendanceResponse?.attendances || [];
        const presentIds = new Set(attendances.map((row) => Number(row.member_id)));

        const selectedSlot = allSlots.find((slot) => Number(slot.id) === Number(slotId));
        const st = selectedSlot?.session_time || selectedSlot?.sessionTime || {};
        const slotLabel = st.name ? `${st.name} (${st.start_time || ''} - ${st.end_time || ''})` : '';

        participants = members.map((member) => ({
            id: Number(member.id),
            member_name: member.name || '-',
            member_id: Number(member.id),
            slot_label: slotLabel,
            status: presentIds.has(Number(member.id)) ? 'present' : 'absent',
        }));

        updateSessionDetailsDisplay(sessionId, slotId);
        renderParticipants(participants);
        updateAttendanceSummaryLocal();

        document.getElementById('sessionDetailsCard')?.classList.remove('hidden');
        document.getElementById('emptyState')?.classList.add('hidden');
    })
    .catch((error) => {
        showNotification(error?.message || 'Gagal memuat data attendance', 'error');
    });
}

function updateSessionDetailsDisplay() {
    if (!currentSession) return;

    const formattedDate = formatIndonesianDate(currentSession.date || '');
    const selectedSlot = allSlots.find((slot) => Number(slot.id) === Number(currentSlotId));
    const st = selectedSlot?.session_time || selectedSlot?.sessionTime || {};
    const slotInfo = st.name ? ` - ${st.name} (${st.start_time || ''} - ${st.end_time || ''})` : '';

    const title = document.getElementById('sessionTitle');
    const date = document.getElementById('sessionDateTime');

    if (title) title.textContent = `Training Session - ${formattedDate}${slotInfo}`;
    if (date) date.textContent = `Tanggal: ${formattedDate} | Status: ${(currentSession.status || '').toUpperCase()}`;
}

function renderParticipants(filteredParticipants = participants) {
    const container = document.getElementById('participantsContainer');
    const loading = document.getElementById('loadingState');
    if (loading) loading.remove();

    if (filteredParticipants.length === 0) {
        container.innerHTML = '<p class="text-sm text-slate-500 py-4 text-center">Tidak ada member aktif.</p>';
        return;
    }

    container.innerHTML = filteredParticipants.map((p, index) => {
        const originalIndex = participants.findIndex((row) => row.id === p.id);
        const isPresent = p.status === 'present';

        return `
            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-slate-300 transition-all">
                <div class="flex-shrink-0 w-10 h-10 rounded-full ${isPresent ? 'bg-slate-600' : 'bg-slate-300'} flex items-center justify-center text-white font-bold text-base">
                    ${index + 1}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold ${isPresent ? 'text-slate-900' : 'text-slate-500'}">${escapeHtml(p.member_name)}</p>
                    <p class="text-xs text-slate-600 mt-1">ID: ${p.member_id}</p>
                </div>
                <button onclick="toggleMemberStatus(${originalIndex})"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 ${isPresent ? 'bg-green-600 hover:bg-green-700' : 'bg-slate-200 hover:bg-slate-300 text-slate-700'} text-white rounded-lg text-xs font-bold transition-all">
                    ${isPresent ? 'Hadir' : 'Tidak Hadir'}
                </button>
            </div>
        `;
    }).join('');

    updateAttendanceSummaryLocal();
}

function toggleMemberStatus(index) {
    participants[index].status = participants[index].status === 'present' ? 'absent' : 'present';
    renderParticipants(participants);
}

function filterParticipants() {
    const search = (document.getElementById('searchParticipant')?.value || '').toLowerCase();
    const filtered = participants.filter((p) =>
        (p.member_name || '').toLowerCase().includes(search)
        || String(p.member_id || '').toLowerCase().includes(search)
    );
    renderParticipants(filtered);
}

function updateAttendanceSummaryLocal() {
    const present = participants.filter((p) => p.status === 'present').length;
    const total = participants.length;

    const presentEl = document.getElementById('presentCount');
    const totalEl = document.getElementById('totalCount');

    if (presentEl) presentEl.textContent = present;
    if (totalEl) totalEl.textContent = total;
}

function saveAttendance() {
    if (!currentSessionId) return;

    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.textContent = 'Menyimpan...';

    const presentIds = participants
        .filter((p) => p.status === 'present')
        .map((p) => Number(p.member_id));

    window.API.put(`/admin/training-sessions/${currentSessionId}/attendances`, {
        session_id: Number(currentSessionId),
        member_ids: presentIds,
    })
    .then(() => {
        showNotification('Attendance berhasil disimpan.', 'success');
        loadAttendanceData(currentSessionId, currentSlotId);
    })
    .catch((error) => {
        showNotification(error?.message || 'Gagal menyimpan attendance.', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
    });
}

function formatIndonesianDate(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    } catch (error) {
        return dateString;
    }
}

function escapeHtml(str) {
    return (str || '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function showNotification(message, type = 'info') {
    window.showToast(message, type);
}
</script>
@endsection

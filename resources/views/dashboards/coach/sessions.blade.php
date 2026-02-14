@extends('layouts.coach')

@section('title', 'Training Sessions')
@section('subtitle', 'Kelola sesi latihan dengan tampilan yang ringkas dan mudah dibaca')

@section('content')
<div class="space-y-4">
    <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Cari</label>
                <input type="text" id="searchInput" placeholder="Cari tanggal (YYYY-MM-DD)" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Urutkan</label>
                <select id="sortFilter" class="w-full px-3 py-2.5 border border-slate-200 rounded-lg bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]/30">
                    <option value="date_desc">Terbaru</option>
                    <option value="date_asc">Terlama</option>
                </select>
            </div>
        </div>
    </div>

    <div id="sessionsGrid" class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-8 text-center">
            <div class="animate-spin rounded-full h-10 w-10 border-2 border-slate-200 border-t-[#1a307b] mx-auto"></div>
            <p class="text-sm text-slate-500 mt-3">Memuat sesi...</p>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50" onclick="closeDeleteModal()"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-slate-200 p-5">
                <h3 class="text-lg font-bold text-slate-900">Hapus Training Session</h3>
                <p class="text-sm text-slate-600 mt-2" id="deleteModalDate"></p>
                <p class="text-xs text-slate-500 mt-1">Aksi ini tidak bisa dibatalkan.</p>
                <div class="mt-5 grid grid-cols-2 gap-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium">Batal</button>
                    <button type="button" id="confirmDeleteBtn" onclick="confirmDelete()" class="px-4 py-2.5 rounded-lg bg-[#d12823] hover:bg-[#b8231f] text-white text-sm font-semibold">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allSessions = [];
let sessionToDelete = null;

document.addEventListener('DOMContentLoaded', () => {
    fetchSessions();
    document.getElementById('searchInput').addEventListener('input', filterSessions);
    document.getElementById('statusFilter').addEventListener('change', filterSessions);
    document.getElementById('sortFilter').addEventListener('change', filterSessions);
});

async function fetchSessions() {
    const grid = document.getElementById('sessionsGrid');
    try {
        const data = await window.API.get('/coach/training-sessions');
        allSessions = Array.isArray(data?.data) ? data.data : [];
        renderSessions(allSessions);
    } catch (error) {
        console.error(error);
        window.showToast(error?.message || 'Gagal memuat data sesi', 'error');
        grid.innerHTML = `
            <div class="col-span-full bg-white border border-slate-200 rounded-xl p-8 text-center">
                <p class="text-sm text-slate-600">Data sesi gagal dimuat.</p>
            </div>
        `;
    }
}

function filterSessions() {
    const searchTerm = (document.getElementById('searchInput').value || '').toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;

    let filtered = allSessions.filter(session => {
        const dateStr = (session.date || '').toString().slice(0, 10).toLowerCase();
        const status = (session.status || '').toLowerCase();
        const matchesSearch = !searchTerm || dateStr.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        return matchesSearch && matchesStatus;
    });

    filtered.sort((a, b) => sortFilter === 'date_asc' ? new Date(a.date) - new Date(b.date) : new Date(b.date) - new Date(a.date));
    renderSessions(filtered);
}

function renderSessions(sessions) {
    const grid = document.getElementById('sessionsGrid');

    if (!sessions.length) {
        grid.innerHTML = `
            <div class="col-span-full bg-white border border-slate-200 rounded-xl p-8 text-center">
                <p class="text-sm text-slate-600">Tidak ada sesi yang cocok dengan filter.</p>
            </div>
        `;
        return;
    }

    const statusClass = {
        open: 'bg-[#1a307b]/10 text-[#1a307b] border border-[#1a307b]/20',
        closed: 'bg-slate-100 text-slate-700 border border-slate-200',
        canceled: 'bg-[#d12823]/10 text-[#d12823] border border-[#d12823]/20'
    };

    grid.innerHTML = sessions.map(session => {
        const dateStr = (session.date || '').toString().slice(0, 10);
        const status = (session.status || '').toLowerCase();
        const slots = Array.isArray(session.slots) ? session.slots : [];
        const bookingsCount = slots.reduce((sum, slot) => {
            const bookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings : [];
            return sum + bookings.length;
        }, 0);
        const hasBookings = bookingsCount > 0;

        return `
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-500">Tanggal</p>
                        <p class="text-lg font-bold text-slate-900">${dateStr || '-'}</p>
                        <div class="mt-2 inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${statusClass[status] || statusClass.closed}">${(session.status || 'unknown').toUpperCase()}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="deleteSession(${session.id}, ${hasBookings})" class="p-2 rounded-lg ${hasBookings ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-[#d12823] text-white hover:bg-[#b8231f]'}" ${hasBookings ? 'disabled' : ''} title="${hasBookings ? 'Tidak dapat dihapus karena ada booking' : 'Hapus sesi'}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <a href="/coach/sessions/${session.id}/edit" class="p-2 rounded-lg bg-[#1a307b] text-white hover:bg-[#162a69]" title="Edit sesi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <div class="text-xs text-slate-600">${slots.length} slot • ${bookingsCount} booking</div>
                    ${slots.length ? slots.map(slot => {
                        const st = slot.session_time || slot.sessionTime || {};
                        const bookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings.length : (Array.isArray(slot.confirmedBookings) ? slot.confirmedBookings.length : 0);
                        return `<div class="px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between gap-2"><div><p class="text-sm font-semibold text-slate-800">${escapeHtml(st.name || 'Slot')}</p><p class="text-xs text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</p></div><p class="text-xs font-semibold text-slate-700">${bookings}/${slot.max_participants ?? '-'}</p></div>`;
                    }).join('') : '<div class="px-3 py-2 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600">Belum ada slot.</div>'}

                    <button type="button" onclick="toggleSessionDetails(${session.id})" class="w-full mt-2 px-3 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 flex items-center justify-center gap-2">
                        <span>Lihat Detail Coach & Member</span>
                        <svg id="detailIcon-${session.id}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div id="detailSection-${session.id}" class="hidden mt-2 border border-slate-200 rounded-lg bg-slate-50 p-3 space-y-3">
                        ${renderSessionDetails(session.id, slots)}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderSessionDetails(sessionId, slots) {
    if (!slots.length) {
        return '<p class="text-xs text-slate-500">Belum ada data slot.</p>';
    }

    return slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        const coaches = Array.isArray(slot.coaches) ? slot.coaches : [];
        const rawBookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings : (Array.isArray(slot.confirmedBookings) ? slot.confirmedBookings : []);
        const members = rawBookings.map((booking) => {
            const member = booking?.member_package?.member || booking?.memberPackage?.member || {};
            return member?.name ? escapeHtml(member.name) : null;
        }).filter(Boolean);
        const slotBubbleId = `slotDetail-${sessionId}-${slot.id}`;
        const slotBubbleIconId = `slotDetailIcon-${sessionId}-${slot.id}`;

        return `
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <button type="button" onclick="toggleSlotBubble('${slotBubbleId}', '${slotBubbleIconId}')" class="w-full flex items-center justify-between gap-3 p-3 hover:bg-slate-50 transition text-left">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">${escapeHtml(st.name || 'Slot')}</p>
                        <p class="text-xs text-slate-500">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</p>
                    </div>
                    <svg id="${slotBubbleIconId}" class="w-4 h-4 text-slate-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="${slotBubbleId}" class="hidden border-t border-slate-100 bg-slate-50 p-3 space-y-3">
                    <div>
                        <p class="text-xs font-semibold text-slate-600 mb-1">Coach (${coaches.length})</p>
                        ${coaches.length
                            ? `<div class="flex flex-wrap gap-1">${coaches.map((coach) => `<span class="px-2 py-1 text-xs rounded bg-[#1a307b]/10 text-[#1a307b] border border-[#1a307b]/20">${escapeHtml(coach.name || 'Coach')}</span>`).join('')}</div>`
                            : '<p class="text-xs text-slate-500">Belum ada coach.</p>'}
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 mb-1">Member (${members.length})</p>
                        ${members.length
                            ? `<div class="space-y-1 max-h-36 overflow-y-auto">${members.map((name) => `<p class="text-xs text-slate-700 px-2 py-1 rounded bg-white border border-slate-200">${name}</p>`).join('')}</div>`
                            : '<p class="text-xs text-slate-500">Belum ada member.</p>'}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function toggleSlotBubble(contentId, iconId) {
    const content = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    if (!content || !icon) return;

    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

function toggleSessionDetails(sessionId) {
    const detailSection = document.getElementById(`detailSection-${sessionId}`);
    const icon = document.getElementById(`detailIcon-${sessionId}`);
    if (!detailSection || !icon) return;

    detailSection.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

function deleteSession(sessionId, hasBookings = false) {
    if (hasBookings) {
        window.showToast('Sesi tidak bisa dihapus karena masih memiliki booking', 'error');
        return;
    }
    sessionToDelete = sessionId;
    const target = allSessions.find(s => Number(s.id) === Number(sessionId));
    const dateStr = (target?.date || '').toString().slice(0, 10);
    document.getElementById('deleteModalDate').textContent = `Sesi tanggal ${dateStr || ('#' + sessionId)}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    sessionToDelete = null;
}

async function confirmDelete() {
    if (!sessionToDelete) return;

    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = 'Menghapus...';

    try {
        await window.API.delete(`/coach/training-sessions/${sessionToDelete}`);
        window.showToast('Training session berhasil dihapus', 'success');
        closeDeleteModal();
        await fetchSessions();
    } catch (error) {
        console.error(error);
        window.showToast(error?.message || 'Gagal menghapus training session', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
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
</script>
@endsection

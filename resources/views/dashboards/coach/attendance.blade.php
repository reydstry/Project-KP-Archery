@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-white p-4 sm:p-8">

    <!-- Header Section -->
    <div class="mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Attendance Management</h1>
        <p class="text-slate-600 text-base sm:text-lg">Track and manage participant attendance for your sessions</p>
    </div>

    <!-- Session Filter -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">

            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Latihan</label>
                <input type="date" id="dateFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sesi / Slot</label>
                <select id="slotFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" disabled>
                    <option value="">Semua Slot</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Session Details Card (Hidden initially) -->
    <div id="sessionDetailsCard" class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 mb-6 sm:mb-8 hidden card-animate" style="animation-delay: 0.2s">
        <div class="flex flex-col sm:flex-row items-start justify-between gap-4 sm:gap-0 mb-4 sm:mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2" id="sessionTitle">-</h2>
                <div class="flex items-center gap-4 text-sm text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="sessionDateTime">-</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="sessionLocation">-</span>
                    </div>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="flex items-center gap-4">
                <div class="text-center px-6 py-3 bg-emerald-50 rounded-xl">
                    <p class="text-2xl font-bold text-emerald-600" id="presentCount">0</p>
                    <p class="text-xs text-emerald-600 font-medium">Present</p>
                </div>
                <div class="text-center px-6 py-3 bg-red-50 rounded-xl">
                    <p class="text-2xl font-bold text-red-600" id="absentCount">0</p>
                    <p class="text-xs text-red-600 font-medium">Absent</p>
                </div>
                <div class="text-center px-6 py-3 bg-orange-50 rounded-xl">
                    <p class="text-2xl font-bold text-orange-600" id="notValidatedCount">0</p>
                    <p class="text-xs text-orange-600 font-medium">Not Validated</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-4 sm:mb-6">
            <button onclick="markAllPresent()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all duration-200 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Mark All Present
            </button>
            <button onclick="markAllAbsent()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Mark All Absent
            </button>

            <a href="{{ route('coach.bookings.create') }}" class="ml-auto px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-sm flex items-center gap-2">
                Book Member
            </a>
        </div>

        <!-- Search Participants -->
        <div class="mb-4">
            <div class="relative">
                <input type="text" id="searchParticipant" placeholder="Search participants by name..." class="w-full px-4 py-3 pl-11 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Participants List -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Slot</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="participantsTableBody" class="bg-white divide-y divide-slate-200">
                    <!-- Loading state -->
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-slate-600 mt-2">Loading participants...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Save Button -->
        <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4">
            <p class="text-sm text-slate-600" id="lastSaved">Last saved: Never</p>
            <button onclick="saveAttendance()" id="saveBtn" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Save Attendance</span>
            </button>
        </div>

    </div>

    <!-- Empty State (Shown when no session selected) -->
    <div id="emptyState" class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8 sm:p-12 text-center card-animate" style="animation-delay: 0.2s">
        <svg class="w-24 h-24 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">Select a Session</h3>
        <p class="text-slate-600 mb-6">Choose a training session to take attendance</p>
    </div>

</div>

<script>
let currentSessionId = null;
let currentSlotId = null;
let currentSession = null;
let participants = [];

document.addEventListener('DOMContentLoaded', function() {
    // Default date = today
    const dateInput = document.getElementById('dateFilter');
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    dateInput.value = `${yyyy}-${mm}-${dd}`;

    loadSessions();

    document.getElementById('dateFilter').addEventListener('change', loadSessions);
    document.getElementById('slotFilter').addEventListener('change', handleSlotFilterChange);
    document.getElementById('searchParticipant').addEventListener('input', filterParticipants);
});

function loadSessions() {
    const dateFilter = document.getElementById('dateFilter').value;

    // Reset selection
    currentSessionId = null;
    currentSlotId = null;
    currentSession = null;
    resetSlotFilter();
    document.getElementById('sessionDetailsCard').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('hidden');

    const params = new URLSearchParams();
    if (dateFilter) {
        params.set('start_date', dateFilter);
        params.set('end_date', dateFilter);
    }

    const url = '/coach/training-sessions' + (params.toString() ? `?${params.toString()}` : '');

    window.API.get(url)
        .then(data => {
            const sessions = data?.data || [];
            if (sessions.length === 0) {
                return;
            }

            const chosen = sessions[0];
            currentSessionId = chosen.id;
            loadSessionAndAttendance(currentSessionId);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function resetSlotFilter() {
    const slotFilter = document.getElementById('slotFilter');
    slotFilter.disabled = true;
    slotFilter.innerHTML = '<option value="">Semua Slot</option>';
    currentSlotId = null;
}

function populateSlotFilter(slots) {
    const slotFilter = document.getElementById('slotFilter');
    
    if (!Array.isArray(slots) || slots.length === 0) {
        resetSlotFilter();
        return;
    }

    slotFilter.disabled = false;
    slotFilter.innerHTML = '<option value="">Semua Slot</option>';

    const sorted = [...slots].sort((a, b) => {
        const aTime = a?.session_time?.start_time || '';
        const bTime = b?.session_time?.start_time || '';
        return aTime.localeCompare(bTime);
    });

    sorted.forEach(slot => {
        const st = slot.session_time || {};
        const name = st.name || 'Session';
        const start = st.start_time || '';
        const end = st.end_time || '';
        const label = `${name} ${start}${start && end ? ' - ' : ''}${end}`.trim();

        const opt = document.createElement('option');
        opt.value = slot.id;
        opt.textContent = label || `Slot #${slot.id}`;
        slotFilter.appendChild(opt);
    });
}

function handleSlotFilterChange(e) {
    currentSlotId = e.target.value || null;
    if (currentSessionId) {
        loadAttendanceData(currentSessionId, currentSlotId);
    }
}

function loadSessionAndAttendance(sessionId) {
    Promise.all([
        window.API.get(`/coach/training-sessions/${sessionId}`),
    ])
        .then(([session]) => {
            currentSession = session;

            const dateStr = (session.date || '').toString().slice(0, 10);
            document.getElementById('sessionTitle').textContent = `Training Session - ${dateStr}`;
            document.getElementById('sessionDateTime').textContent = `Date: ${dateStr}`;
            document.getElementById('sessionLocation').textContent = `Status: ${(session.status || '').toString()}`;

            const slots = Array.isArray(session.slots) ? session.slots : [];
            populateSlotFilter(slots);

            // Load attendance for all slots by default
            currentSlotId = null;
            loadAttendanceData(sessionId, null);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error?.message || 'Failed to load session', 'error');
        });
}

function loadAttendanceData(sessionId, slotId) {
    const qs = new URLSearchParams();
    if (slotId) qs.set('slot_id', slotId);

    window.API.get(`/coach/training-sessions/${sessionId}/bookings${qs.toString() ? `?${qs.toString()}` : ''}`)
        .then(attendance => {
            const bookings = attendance?.bookings || [];
            participants = bookings.map(b => {
                const slot = b.slot || {};
                const st = slot.session_time || {};
                const slotLabel = `${st.name || 'Session'} ${st.start_time || ''}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time || ''}`.trim();

                return {
                    id: b.id,
                    member_name: b.member_name,
                    member_id: b.member_id,
                    slot_label: slotLabel,
                    has_attendance: !!b.has_attendance,
                    original_status: b.attendance_status || '',
                    status: b.attendance_status || '',
                };
            });

            renderParticipants(participants);
            updateAttendanceSummaryFromServer(attendance);

            document.getElementById('sessionDetailsCard').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error?.message || 'Failed to load attendance', 'error');
        });
}

function renderParticipants(filteredParticipants = participants) {
    const tbody = document.getElementById('participantsTableBody');

    if (filteredParticipants.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="px-6 py-12 text-center text-slate-600">No bookings found</td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filteredParticipants.map((p, index) => {
        const statusClass = getStatusClass(p.status);
        const canUnvalidate = !p.has_attendance;

        return `
            <tr class="hover:bg-slate-50 transition-colors duration-150">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-semibold text-slate-900">${p.member_name}</p>
                        <p class="text-xs text-slate-500">Member ID: ${p.member_id}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${p.slot_label || '-'}</td>
                <td class="px-6 py-4">
                    <select class="attendance-status px-3 py-2 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 ${statusClass}" data-index="${index}" onchange="updateStatus(${index}, this.value)">
                        ${canUnvalidate ? `<option value="" ${p.status === '' ? 'selected' : ''}>Not validated</option>` : ''}
                        <option value="present" ${p.status === 'present' ? 'selected' : ''}>Present</option>
                        <option value="absent" ${p.status === 'absent' ? 'selected' : ''}>Absent</option>
                    </select>
                </td>
            </tr>
        `;
    }).join('');
}

function getStatusClass(status) {
    const classes = {
        present: 'bg-emerald-100 text-emerald-700',
        absent: 'bg-red-100 text-red-700',
        '': 'bg-slate-100 text-slate-700'
    };
    return classes[status] || classes[''];
}

function updateStatus(index, status) {
    participants[index].status = status;
}

function updateAttendanceSummaryLocal() {
    const present = participants.filter(p => p.status === 'present').length;
    const absent = participants.filter(p => p.status === 'absent').length;
    const notValidated = participants.filter(p => !p.status).length;

    document.getElementById('presentCount').textContent = present;
    document.getElementById('absentCount').textContent = absent;
    document.getElementById('notValidatedCount').textContent = notValidated;
}

function updateAttendanceSummaryFromServer(attendance) {
    if (!attendance) {
        updateAttendanceSummaryLocal();
        return;
    }
    document.getElementById('presentCount').textContent = attendance.attended ?? 0;
    document.getElementById('absentCount').textContent = attendance.absent ?? 0;
    document.getElementById('notValidatedCount').textContent = attendance.not_validated ?? 0;
}

function filterParticipants() {
    const search = document.getElementById('searchParticipant').value.toLowerCase();
    const filtered = participants.filter(p =>
        (p.member_name || '').toLowerCase().includes(search) ||
        (p.member_id || '').toString().toLowerCase().includes(search) ||
        (p.slot_label || '').toLowerCase().includes(search)
    );
    renderParticipants(filtered);
}

function markAllPresent() {
    participants.forEach((p, index) => {
        participants[index].status = 'present';
    });
    renderParticipants();
    updateAttendanceSummaryLocal();
}

function markAllAbsent() {
    if (!confirm('Are you sure you want to mark all as absent?')) return;

    participants.forEach((p, index) => {
        participants[index].status = 'absent';
    });
    renderParticipants();
    updateAttendanceSummaryLocal();
}

function saveAttendance() {
    if (!currentSessionId) return;

    const saveBtn = document.getElementById('saveBtn');
    const originalContent = saveBtn.innerHTML;

    saveBtn.disabled = true;
    saveBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Saving...</span>
    `;

    const tasks = participants
        .map(p => {
            const desiredStatus = p.status;

            if (!['present', 'absent'].includes(desiredStatus)) {
                return null;
            }

            const statusChanged = (p.original_status || '') !== desiredStatus;

            if (!statusChanged) {
                return null;
            }

            const payload = { status: desiredStatus, notes: null };
            const url = `/coach/bookings/${p.id}/attendance`;
            const request = p.has_attendance
                ? () => window.API.patch(url, payload)
                : () => window.API.post(url, payload);

            return { p, request };
        })
        .filter(Boolean);

    (async () => {
        let successCount = 0;
        try {
            for (const task of tasks) {
                await task.request();
                successCount++;
            }

            const now = new Date().toLocaleTimeString();
            document.getElementById('lastSaved').textContent = `Last saved: ${now}`;
            showNotification(`Attendance saved (${successCount}/${tasks.length})`, 'success');

            // Refresh server state
            if (currentSessionId) {
                loadAttendanceData(currentSessionId, currentSlotId);
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification(error?.message || 'Failed to save attendance', 'error');
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalContent;
        }
    })();
}

function escapeHtml(str) {
    return (str || '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function showNotification(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-xl shadow-lg z-50 animate-fade-in-down`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection

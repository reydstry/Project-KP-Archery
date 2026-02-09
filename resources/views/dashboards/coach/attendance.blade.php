<?php
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Attendance Management</h1>
        <p class="text-slate-600 text-lg">Track and manage participant attendance for your sessions</p>
    </div>

    <!-- Session Filter -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Session Select -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Select Session</label>
                <select id="sessionSelect" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="">Loading sessions...</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Filter by Date</label>
                <input type="date" id="dateFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>

        </div>
    </div>

    <!-- Session Details Card (Hidden initially) -->
    <div id="sessionDetailsCard" class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6 mb-8 hidden">
        <div class="flex items-start justify-between mb-6">
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
                    <p class="text-2xl font-bold text-orange-600" id="lateCount">0</p>
                    <p class="text-xs text-orange-600 font-medium">Late</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex items-center gap-3 mb-6">
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
            <button onclick="exportAttendance()" class="ml-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </button>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Participant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Check-in Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="participantsTableBody" class="bg-white divide-y divide-slate-200">
                    <!-- Loading state -->
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-slate-600 mt-2">Loading participants...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Save Button -->
        <div class="mt-6 flex items-center justify-end gap-4">
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
    <div id="emptyState" class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-12 text-center">
        <svg class="w-24 h-24 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">Select a Session</h3>
        <p class="text-slate-600 mb-6">Choose a training session to take attendance</p>
    </div>

</div>

<script>
let currentSessionId = null;
let participants = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSessions();

    document.getElementById('sessionSelect').addEventListener('change', handleSessionChange);
    document.getElementById('dateFilter').addEventListener('change', loadSessions);
    document.getElementById('searchParticipant').addEventListener('input', filterParticipants);
    document.getElementById('selectAll').addEventListener('change', handleSelectAll);
});

function loadSessions() {
    const dateFilter = document.getElementById('dateFilter').value;

    fetch(`{{ route('coach.attendance.index') }}?date=${dateFilter}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('sessionSelect');
        select.innerHTML = '<option value="">Select a session</option>';

        if (data.sessions && data.sessions.length > 0) {
            data.sessions.forEach(session => {
                const option = document.createElement('option');
                option.value = session.id;
                option.textContent = `${session.title} - ${session.date} ${session.time}`;
                select.appendChild(option);
            });
        } else {
            select.innerHTML = '<option value="">No sessions available</option>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('sessionSelect').innerHTML = '<option value="">Error loading sessions</option>';
    });
}

function handleSessionChange(e) {
    const sessionId = e.target.value;

    if (!sessionId) {
        document.getElementById('sessionDetailsCard').classList.add('hidden');
        document.getElementById('emptyState').classList.remove('hidden');
        return;
    }

    currentSessionId = sessionId;
    loadSessionDetails(sessionId);
}

function loadSessionDetails(sessionId) {
    fetch(`{{ route('coach.attendance.index') }}?session_id=${sessionId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.session) {
            // Update session info
            document.getElementById('sessionTitle').textContent = data.session.title;
            document.getElementById('sessionDateTime').textContent = `${data.session.date} at ${data.session.time}`;
            document.getElementById('sessionLocation').textContent = data.session.location;

            // Store participants
            participants = data.participants || [];

            // Render participants
            renderParticipants(participants);

            // Update summary
            updateAttendanceSummary();

            // Show details card
            document.getElementById('sessionDetailsCard').classList.remove('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to load session details');
    });
}

function renderParticipants(filteredParticipants = participants) {
    const tbody = document.getElementById('participantsTableBody');

    if (filteredParticipants.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-600">
                    No participants found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filteredParticipants.map((p, index) => `
        <tr class="hover:bg-slate-50 transition-colors duration-150">
            <td class="px-6 py-4">
                <input type="checkbox" class="participant-checkbox w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" data-index="${index}">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        ${p.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">${p.name}</p>
                        <p class="text-xs text-slate-500">${p.email}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600">${p.memberId}</td>
            <td class="px-6 py-4">
                <select class="attendance-status px-3 py-2 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 ${getStatusClass(p.status)}" data-index="${index}" onchange="updateStatus(${index}, this.value)">
                    <option value="pending" ${p.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="present" ${p.status === 'present' ? 'selected' : ''}>Present</option>
                    <option value="absent" ${p.status === 'absent' ? 'selected' : ''}>Absent</option>
                    <option value="late" ${p.status === 'late' ? 'selected' : ''}>Late</option>
                    <option value="excused" ${p.status === 'excused' ? 'selected' : ''}>Excused</option>
                </select>
            </td>
            <td class="px-6 py-4">
                <input type="time" class="check-in-time px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" value="${p.checkInTime || ''}" data-index="${index}" onchange="updateCheckInTime(${index}, this.value)">
            </td>
            <td class="px-6 py-4">
                <input type="text" class="notes px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 w-full" placeholder="Add notes..." value="${p.notes || ''}" data-index="${index}" onchange="updateNotes(${index}, this.value)">
            </td>
            <td class="px-6 py-4">
                <button onclick="quickMarkPresent(${index})" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors duration-150" title="Quick mark present">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusClass(status) {
    const classes = {
        pending: 'bg-slate-100 text-slate-700',
        present: 'bg-emerald-100 text-emerald-700',
        absent: 'bg-red-100 text-red-700',
        late: 'bg-orange-100 text-orange-700',
        excused: 'bg-blue-100 text-blue-700'
    };
    return classes[status] || classes.pending;
}

function updateStatus(index, status) {
    participants[index].status = status;

    // Auto set check-in time if present
    if (status === 'present' && !participants[index].checkInTime) {
        const now = new Date();
        participants[index].checkInTime = now.toTimeString().slice(0, 5);
        renderParticipants();
    }

    updateAttendanceSummary();
}

function updateCheckInTime(index, time) {
    participants[index].checkInTime = time;
}

function updateNotes(index, notes) {
    participants[index].notes = notes;
}

function updateAttendanceSummary() {
    const present = participants.filter(p => p.status === 'present').length;
    const absent = participants.filter(p => p.status === 'absent').length;
    const late = participants.filter(p => p.status === 'late').length;

    document.getElementById('presentCount').textContent = present;
    document.getElementById('absentCount').textContent = absent;
    document.getElementById('lateCount').textContent = late;
}

function filterParticipants() {
    const search = document.getElementById('searchParticipant').value.toLowerCase();
    const filtered = participants.filter(p =>
        p.name.toLowerCase().includes(search) ||
        p.email.toLowerCase().includes(search) ||
        p.memberId.toLowerCase().includes(search)
    );
    renderParticipants(filtered);
}

function handleSelectAll(e) {
    document.querySelectorAll('.participant-checkbox').forEach(checkbox => {
        checkbox.checked = e.target.checked;
    });
}

function markAllPresent() {
    participants.forEach((p, index) => {
        participants[index].status = 'present';
        if (!participants[index].checkInTime) {
            const now = new Date();
            participants[index].checkInTime = now.toTimeString().slice(0, 5);
        }
    });
    renderParticipants();
    updateAttendanceSummary();
}

function markAllAbsent() {
    if (!confirm('Are you sure you want to mark all as absent?')) return;

    participants.forEach((p, index) => {
        participants[index].status = 'absent';
    });
    renderParticipants();
    updateAttendanceSummary();
}

function quickMarkPresent(index) {
    participants[index].status = 'present';
    if (!participants[index].checkInTime) {
        const now = new Date();
        participants[index].checkInTime = now.toTimeString().slice(0, 5);
    }
    renderParticipants();
    updateAttendanceSummary();
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

    fetch('{{ route("coach.attendance.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            session_id: currentSessionId,
            attendance: participants
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const now = new Date().toLocaleTimeString();
            document.getElementById('lastSaved').textContent = `Last saved: ${now}`;

            // Show success notification
            showNotification('Attendance saved successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to save attendance');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Failed to save attendance', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalContent;
    });
}

function exportAttendance() {
    if (!currentSessionId) return;

    window.location.href = `/coach/attendance/export?session_id=${currentSessionId}`;
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

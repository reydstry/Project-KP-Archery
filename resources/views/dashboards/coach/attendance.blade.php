@extends('layouts.coach')

@section('title', 'Attendance')
@section('subtitle', 'Semua member yang booking otomatis hadir')

@section('content')
<div class="space-y-6">
    <!-- Session Filter Card -->
    <div class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-3 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Pilih Training Session -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-slate-700">Pilih Training Session</label>
                    <span class="text-xs text-slate-500" id="loadingSessionsText" style="display:none;">Memuat...</span>
                </div>
                
                <div id="sessionsContainer" class="min-h-[80px] transition-all duration-200">
                    <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">
                        Memuat training session...
                    </div>
                </div>
            </div>

            <!-- Pilih Slot Waktu -->
            <div id="slotsSection" class="hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Slot Waktu</label>
                <div id="slotsContainer" class="min-h-[80px] transition-all duration-200">
                    <p class="text-sm text-slate-500">Pilih training session terlebih dahulu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Attendance List -->
    <div id="sessionDetailsCard" class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm hidden">
        <div class="p-3 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Session Header -->
            <div class="pb-4 sm:pb-6 border-b border-slate-200">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3 sm:gap-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-1" id="sessionTitle">Sesi Latihan</h2>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-slate-600">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span id="sessionDateTime">-</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="sessionLocation">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 lg:w-auto w-full">
                        <div class="text-center px-3 py-2 sm:px-4 sm:py-3 bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-xl sm:text-2xl font-bold text-green-600" id="presentCount">0</p>
                            <p class="text-xs sm:text-sm text-green-700 font-semibold">Member Hadir</p>
                        </div>
                        <div class="text-center px-3 py-2 sm:px-4 sm:py-3 bg-slate-50 rounded-xl border border-slate-200">
                            <p class="text-xl sm:text-2xl font-bold text-slate-600" id="totalCount">0</p>
                            <p class="text-xs sm:text-sm text-slate-700 font-semibold">Total Booking</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Only -->
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-2">Cari Member</label>
                <input type="text" id="searchParticipant" placeholder="Cari nama member..." 
                       oninput="filterParticipants()"
                       class="w-full px-3 sm:px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b] text-sm">
            </div>

            <!-- Member List -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 sm:p-4">
                <h3 class="text-xs sm:text-sm font-bold text-slate-900 mb-1">Daftar Member</h3>
                <p class="text-xs text-slate-600 mb-2 sm:mb-3">Klik badge <span> Hadir</span> untuk ubah status kehadiran.</p>
                <div id="participantsContainer" class="space-y-2 max-h-[60vh] sm:max-h-80 overflow-y-auto transition-all duration-200">
                    <!-- Loading state -->
                    <div id="loadingState" class="text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-200 border-t-slate-600 mx-auto mb-3"></div>
                        <p class="text-slate-600 text-sm">Memuat daftar member...
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button onclick="saveAttendance()" id="saveBtn" 
                        class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl text-sm font-medium transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Kehadiran</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="card-animate bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Pilih Sesi Dulu</h3>
            <p class="text-sm text-slate-600">Pilih training session dan slot waktu di atas untuk melihat daftar member</p>
        </div>
    </div>

</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform transition-all" id="successModalContent">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-slate-900 mb-2">Berhasil Disimpan!</h3>
            
            <!-- Message -->
            <p class="text-slate-600 mb-6" id="successModalMessage">
                Kehadiran member berhasil disimpan ke sistem.
            </p>
            
            <!-- Button -->
            <button onclick="closeSuccessModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                Oke, Mengerti
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes modalFadeIn {
    from { 
        opacity: 0; 
        transform: scale(0.95) translateY(-10px);
    }
    to { 
        opacity: 1; 
        transform: scale(1) translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

#successModal.show {
    display: flex !important;
    animation: fadeIn 0.2s ease-out;
}

#successModal.show #successModalContent {
    animation: modalFadeIn 0.3s ease-out;
}

#sessionsContainer > div,
#slotsContainer > div {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
let currentSessionId = null;
let currentSlotId = null;
let currentSession = null;
let participants = [];
let allSessions = [];
let allSlots = [];

document.addEventListener('DOMContentLoaded', function() {
    loadSessions();
    document.getElementById('searchParticipant').addEventListener('input', filterParticipants);
    
    // Modal close on outside click
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeSuccessModal();
            }
        });
    }
});

function loadSessions() {
    const container = document.getElementById('sessionsContainer');
    const loadingText = document.getElementById('loadingSessionsText');
    
    if (loadingText) loadingText.style.display = 'inline';
    container.innerHTML = '<div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">Memuat training session...</div>';

    // Load sessions without date filter - get all available sessions
    window.API.get('/coach/training-sessions')
        .then(data => {
            allSessions = data?.data || [];
            
            if (loadingText) loadingText.style.display = 'none';
            
            if (allSessions.length === 0) {
                container.innerHTML = '<div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-sm">Tidak ada training session tersedia</div>';
                return;
            }

            // Render session cards
            const sessionsHtml = allSessions.map(session => {
                const rawDate = session.date || '';
                const formattedDate = formatIndonesianDate(rawDate);
                const status = (session.status || '').toUpperCase();
                const isSelected = currentSessionId === session.id;
                
                return `
                    <button type="button"
                        data-session-id="${session.id}"
                        onclick="selectSession(${session.id})"
                        class="session-btn px-4 py-3 border-2 rounded-xl font-medium transition-all duration-200 text-sm text-left touch-manipulation active:scale-95 ${
                            isSelected 
                                ? 'bg-[#1a307b] text-white border-[#152866]' 
                                : 'bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]'
                        }">
                        <p class="font-semibold">${escapeHtml(formattedDate)}</p>
                        <p class="text-xs opacity-80 mt-1">Status: ${escapeHtml(status)}</p>
                    </button>
                `;
            }).join('');

            container.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">${sessionsHtml}</div>`;
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingText) loadingText.style.display = 'none';
            container.innerHTML = '<div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-red-500 text-sm">Gagal memuat session</div>';
            showNotification('❌ ' + (error?.message || 'Gagal memuat training session. Silakan refresh halaman.'), 'error');
        });
}

function selectSession(sessionId, skipReload = false) {
    currentSessionId = sessionId;
    currentSlotId = null;
    
    // Refresh session cards to show selected state only if manually clicked
    if (!skipReload) {
        document.querySelectorAll('.session-btn').forEach(btn => {
            const id = parseInt(btn.getAttribute('data-session-id'));

            if (id === sessionId) {
                btn.classList.remove('bg-white','text-slate-700','border-slate-200','hover:border-[#1a307b]','hover:text-[#1a307b]');
                btn.classList.add('bg-[#1a307b]','text-white','border-[#152866]');
            } else {
                btn.classList.remove('bg-[#1a307b]','text-white','border-[#152866]');
                btn.classList.add('bg-white','text-slate-700','border-slate-200','hover:border-[#1a307b]','hover:text-[#1a307b]');
            }
        });
    }
    
    // Load slots for selected session
    loadSlots(sessionId);
    
    // Hide attendance details
    const sessionDetailsCard = document.getElementById('sessionDetailsCard');
    const emptyState = document.getElementById('emptyState');
    if (sessionDetailsCard) sessionDetailsCard.classList.add('hidden');
    if (emptyState) emptyState.classList.remove('hidden');
}

function loadSlots(sessionId) {
    const slotsSection = document.getElementById('slotsSection');
    const slotsContainer = document.getElementById('slotsContainer');
    
    slotsSection.classList.remove('hidden');
    slotsContainer.innerHTML = '<p class="text-sm text-slate-500">Memuat slot...</p>';

    window.API.get(`/coach/training-sessions/${sessionId}`)
        .then(session => {
            currentSession = session;
            allSlots = Array.isArray(session.slots) ? session.slots : [];

            if (allSlots.length === 0) {
                slotsContainer.innerHTML = '<p class="text-sm text-slate-500">Tidak ada slot tersedia di sesi ini.</p>';
                return;
            }

            // Sort slots by time
            const sortedSlots = [...allSlots].sort((a, b) => {
                const aTime = a?.session_time?.start_time || a?.sessionTime?.start_time || '';
                const bTime = b?.session_time?.start_time || b?.sessionTime?.start_time || '';
                return aTime.localeCompare(bTime);
            });

            // Render slot cards
            const slotsHtml = sortedSlots.map(slot => {
                const st = slot.session_time || slot.sessionTime || {};
                const name = st.name || 'Slot';
                const startTime = st.start_time || '';
                const endTime = st.end_time || '';
                const capacity = slot.max_participants || 0;
                const isSelected = currentSlotId === slot.id;
                
                return `
                    <button type="button"
                        data-slot-id="${slot.id}"
                        onclick="selectSlot(${slot.id})"
                        class="slot-btn px-4 py-3 border-2 rounded-xl font-medium transition-all duration-200 text-sm w-full text-left touch-manipulation active:scale-95 ${
                            isSelected 
                                ? 'bg-[#1a307b] text-white border-[#152866]' 
                                : 'bg-white text-slate-700 border-slate-200 hover:border-[#1a307b] hover:text-[#1a307b]'
                        }">
                        <p class="font-bold">${escapeHtml(name)}</p>
                        <p class="text-xs opacity-90">${escapeHtml(startTime)} - ${escapeHtml(endTime)}</p>
                        <p class="text-xs opacity-80 mt-1">Kuota: ${capacity}</p>
                    </button>
                `;
            }).join('');

            slotsContainer.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">${slotsHtml}</div>`;
        })
        .catch(error => {
            console.error('Error:', error);
            slotsContainer.innerHTML = '<p class="text-sm text-red-500">Gagal memuat slot</p>';
            showNotification('❌ ' + (error?.message || 'Gagal memuat slot waktu. Silakan coba lagi.'), 'error');
        });
}

function selectSlot(slotId, skipReload = false) {
    currentSlotId = slotId;
    
    // Refresh slots to show selected state only if manually clicked
    if (!skipReload) {
        document.querySelectorAll('.slot-btn').forEach(btn => {
            const id = parseInt(btn.getAttribute('data-slot-id'));

            if (id === slotId) {
                btn.classList.remove('bg-white','text-slate-700','border-slate-200','hover:border-[#1a307b]','hover:text-[#1a307b]');
                btn.classList.add('bg-[#1a307b]','text-white','border-[#152866]');
            } else {
                btn.classList.remove('bg-[#1a307b]','text-white','border-[#152866]');
                btn.classList.add('bg-white','text-slate-700','border-slate-200','hover:border-[#1a307b]','hover:text-[#1a307b]');
            }
        });
    }
    
    // Load attendance data
    loadAttendanceData(currentSessionId, slotId);
}

function loadAttendanceData(sessionId, slotId) {
    Promise.all([
        window.API.get('/coach/attendance/active-members?limit=300'),
        window.API.get(`/coach/training-sessions/${sessionId}/attendances`),
    ])
        .then(([membersResponse, attendanceResponse]) => {
            const members = membersResponse?.data || [];
            const attendances = attendanceResponse?.attendances || [];
            const presentMemberIds = new Set(attendances.map((row) => Number(row.member_id)));

            const selectedSlot = allSlots.find((slot) => Number(slot.id) === Number(slotId));
            const st = selectedSlot?.session_time || selectedSlot?.sessionTime || {};
            const slotLabel = `${st.name || 'Session'} ${st.start_time || ''}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time || ''}`.trim();

            participants = members.map((member) => ({
                id: Number(member.id),
                member_name: member.name || '-',
                member_id: Number(member.id),
                slot_label: slotLabel,
                original_status: presentMemberIds.has(Number(member.id)) ? 'present' : 'absent',
                status: presentMemberIds.has(Number(member.id)) ? 'present' : 'absent',
            }));

            // Update session details display
            updateSessionDetailsDisplay(sessionId, slotId);

            renderParticipants(participants);
            updateAttendanceSummaryLocal();

            const sessionDetailsCard = document.getElementById('sessionDetailsCard');
            const emptyState = document.getElementById('emptyState');
            
            if (sessionDetailsCard) sessionDetailsCard.classList.remove('hidden');
            if (emptyState) emptyState.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('❌ ' + (error?.message || 'Gagal memuat data kehadiran. Silakan coba lagi.'), 'error');
        });
}

function updateSessionDetailsDisplay(sessionId, slotId) {
    const session = currentSession;
    if (!session) return;

    const rawDate = session.date || '';
    const formattedDate = formatIndonesianDate(rawDate);
    const titleEl = document.getElementById('sessionTitle');
    const dateTimeEl = document.getElementById('sessionDateTime');
    const locationEl = document.getElementById('sessionLocation');
    
    // Find selected slot info
    let slotInfo = '';
    if (slotId && allSlots.length > 0) {
        const selectedSlot = allSlots.find(s => s.id == slotId);
        if (selectedSlot) {
            const st = selectedSlot.session_time || selectedSlot.sessionTime || {};
            slotInfo = ` - ${st.name || 'Slot'} (${st.start_time || ''} - ${st.end_time || ''})`;
        }
    }
    
    if (titleEl) titleEl.textContent = `Training Session - ${formattedDate}${slotInfo}`;
    if (dateTimeEl) dateTimeEl.textContent = `Tanggal: ${formattedDate}`;
    if (locationEl) locationEl.textContent = `Status: ${(session.status || '').toString().toUpperCase()}`;
}

function renderParticipants(filteredParticipants = participants) {
    const container = document.getElementById('participantsContainer');
    const loadingState = document.getElementById('loadingState');
    
    if (loadingState) loadingState.remove();

    if (filteredParticipants.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-slate-600 font-medium">Tidak ada member</p>
                <p class="text-sm text-slate-500 mt-1">Belum ada member aktif atau attendance belum dipilih</p>
            </div>
        `;
        updateAttendanceSummaryLocal();
        return;
    }

    container.innerHTML = filteredParticipants.map((p, index) => {
        const originalIndex = participants.findIndex(participant => participant.id === p.id);
        const isPresent = p.status === 'present';

        return `
            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-slate-300 transition-all fade-in">
                <!-- Number Badge -->
                <div class="flex-shrink-0 w-10 h-10 rounded-full ${isPresent ? 'bg-slate-600' : 'bg-slate-300'} flex items-center justify-center text-white font-bold text-base">
                    ${index + 1}
                </div>
                
                <!-- Member Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold ${isPresent ? 'text-slate-900' : 'text-slate-500'}">${escapeHtml(p.member_name)}</p>
                    <p class="text-xs text-slate-600 mt-1">ID: ${p.member_id}</p>
                    ${p.slot_label ? `<p class="text-xs text-slate-500 mt-0.5">${escapeHtml(p.slot_label)}</p>` : ''}
                </div>

                <!-- Status Badge -->
                <div class="flex flex-wrap items-center gap-2 sm:flex-shrink-0">
                    <button 
                        onclick="toggleMemberStatus(${originalIndex})"
                        class="inline-flex items-center gap-1.5 px-2 py-2 sm:px-3 sm:py-1.5 ${
                            isPresent 
                                ? 'bg-green-600 hover:bg-green-700' 
                                : 'bg-slate-200 hover:bg-slate-300 text-slate-700'
                        } text-white rounded-lg text-xs font-bold transition-all active:scale-95 cursor-pointer"
                        title="Klik untuk ubah status kehadiran"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isPresent ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
                        </svg>
                        <span class="hidden sm:inline">${isPresent ? 'Hadir' : 'Tidak Hadir'}</span>
                    </button>
                </div>
            </div>
        `;
    }).join('');

    updateAttendanceSummaryLocal();
}

function toggleMemberStatus(index) {
    const member = participants[index];
    
    if (member.status === 'present') {
        // Toggle to absent
        participants[index].status = 'absent';
        showNotification(`❌ ${member.member_name} ditandai tidak hadir. Jangan lupa klik Simpan!`, 'info');
    } else {
        // Toggle to present
        participants[index].status = 'present';
        showNotification(`✓ ${member.member_name} ditandai hadir. Jangan lupa klik Simpan!`, 'success');
    }
    
    renderParticipants(participants);
}

function updateAttendanceSummaryLocal() {
    const present = participants.filter(p => p.status === 'present').length;
    const total = participants.length;

    const presentEl = document.getElementById('presentCount');
    const totalEl = document.getElementById('totalCount');
    
    if (presentEl) presentEl.textContent = present;
    if (totalEl) totalEl.textContent = total;
}

function updateAttendanceSummaryFromServer(attendance) {
    if (!attendance) {
        updateAttendanceSummaryLocal();
        return;
    }
    const presentEl = document.getElementById('presentCount');
    const totalEl = document.getElementById('totalCount');
    
    if (presentEl) presentEl.textContent = attendance.attended ?? 0;
    if (totalEl) {
        const totalBookings = (attendance.attended ?? 0) + (attendance.absent ?? 0) + (attendance.not_validated ?? 0);
        totalEl.textContent = totalBookings;
    }
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
        <span>Menyimpan...</span>
    `;

    const presentMemberIds = participants
        .filter((participant) => participant.status === 'present')
        .map((participant) => Number(participant.member_id));

    window.API.put(`/coach/training-sessions/${currentSessionId}/attendances`, {
        session_id: Number(currentSessionId),
        member_ids: presentMemberIds,
    })
    .then((response) => {
        showSuccessModal(response?.message || 'Data kehadiran berhasil disimpan ke sistem.');
        loadAttendanceData(currentSessionId, currentSlotId);
    })
    .catch((error) => {
        console.error('Error:', error);
        showNotification('❌ ' + (error?.message || 'Gagal menyimpan kehadiran. Silakan coba lagi.'), 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalContent;
    });
}

function formatIndonesianDate(dateString) {
    if (!dateString) return '';
    
    try {
        const date = new Date(dateString);
        
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const dayName = days[date.getDay()];
        const day = date.getDate();
        const monthName = months[date.getMonth()];
        const year = date.getFullYear();
        
        return `${dayName}, ${day} ${monthName} ${year}`;
    } catch (e) {
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

function showNotification(message, type = 'success') {
    window.showToast(message, type);
}

function showSuccessModal(message) {
    const modal = document.getElementById('successModal');
    const messageEl = document.getElementById('successModalMessage');
    
    if (messageEl) {
        messageEl.textContent = message;
    }
    
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    }
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = ''; // Restore scroll
    }
}
</script>
@endsection

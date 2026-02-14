@extends('layouts.admin')

@section('title', 'Training Sessions')
@section('subtitle', 'Edit training session (multiple coaches)')

@section('content')
<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Edit Training Session</h1>
            <p class="text-slate-600 text-base sm:text-lg">Update coaches and slot capacity for this day</p>
        </div>
        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto shrink-0 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 card-animate" style="animation-delay: 0.15s">
        <div class="mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900" id="sessionHeader">Loading...</h2>
            <p class="text-xs sm:text-sm text-slate-600" id="sessionSubheader">Please wait</p>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-4 sm:mx-0">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Coaches</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Bookings</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsBody">
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-600">Loading slots...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-3" id="slotsMobile">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">
                Loading slots...
            </div>
        </div>

        <div class="mt-4 sm:mt-6 flex items-center justify-end gap-3">
            <button type="button" onclick="updateAllSlots()" id="updateBtn" class="w-full sm:w-auto px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200">Update All</button>
        </div>
    </div>
</div>

<script>
const SESSION_ID = @json($id ?? null);
let CURRENT_SESSION = null;
const COACHES = @json($coaches ?? []);

function notify(message, type = 'info') {
    if (window.showToast) {
        window.showToast(message, type);
        return;
    }
    alert(message);
}

function notifyError(message) {
    notify(message, 'error');
}

function notifySuccess(message) {
    notify(message, 'success');
}

function confirmAction(title, message, onConfirm, options = {}) {
    if (typeof showConfirm === 'function') {
        showConfirm(title, message, onConfirm, options);
        return;
    }

    const plain = `${title}\n\n${message}`;
    if (confirm(plain)) {
        onConfirm();
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    // Validate session ID
    if (!SESSION_ID) {
        notifyError('Session ID tidak ditemukan');
        return;
    }

    // Validate coaches data
    if (!Array.isArray(COACHES) || COACHES.length === 0) {
        notifyError('Belum ada coach aktif. Tambahkan coach terlebih dahulu.');
        console.error('COACHES data is invalid or empty');
    }

    try {
        const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
        
        // Validate session response
        if (!session || !session.id) {
            throw new Error('Invalid session data received');
        }
        
        CURRENT_SESSION = session;
        const dateStr = (session.date || '').toString().slice(0, 10);
        document.getElementById('sessionHeader').textContent = `Training Session - ${dateStr}`;
        document.getElementById('sessionSubheader').textContent = `Status: ${(session.status || '').toString()}`;

        renderSlots(session.slots || []);
    } catch (e) {
        console.error('Failed to load session:', e);
        const errorMsg = e?.response?.data?.message || e?.message || 'Unknown error occurred';
        
        document.getElementById('slotsBody').innerHTML = `
            <tr><td colspan="5" class="px-6 py-10 text-center">
                <div class="text-red-600 mb-2">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold">Failed to load session</p>
                    <p class="text-sm text-slate-600 mt-1">${escapeHtml(errorMsg)}</p>
                </div>
                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-[#1a307b] text-white rounded-lg text-sm hover:bg-[#152866]">
                    Retry
                </button>
            </td></tr>
        `;
        
        document.getElementById('slotsMobile').innerHTML = `
            <div class="bg-red-50 rounded-xl border border-red-200 shadow-sm p-4 text-center">
                <svg class="w-12 h-12 mx-auto mb-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-red-600">Failed to load session</p>
                <p class="text-sm text-slate-600 mt-1">${escapeHtml(errorMsg)}</p>
                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-[#1a307b] text-white rounded-lg text-sm hover:bg-[#152866]">
                    Retry
                </button>
            </div>
        `;
        
        notifyError('Gagal memuat data training session. Silakan coba lagi.');
    }
});

function renderSlots(slots) {
    const tbody = document.getElementById('slotsBody');
    const mobileContainer = document.getElementById('slotsMobile');

    if (!Array.isArray(slots) || slots.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-10 text-center text-slate-600">No slots found for this session.</td></tr>`;
        mobileContainer.innerHTML = `<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">No slots found for this session.</div>`;
        return;
    }

    // Render mobile cards
    mobileContainer.innerHTML = slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        const slotCoaches = Array.isArray(slot.coaches) ? slot.coaches.map(c => Number(c.id)) : [];
        const bookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings : (Array.isArray(slot.confirmedBookings) ? slot.confirmedBookings : []);
        
        return `
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="space-y-3">
                    <!-- Session Name & Time -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm mb-1">${escapeHtml(st.name || 'Session')}</h3>
                            <p class="text-xs text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</p>
                        </div>
                    </div>
                    
                    <!-- Max Participants -->
                    <div class="border-t border-slate-100 pt-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Max Participants</label>
                        <input type="number" min="1" max="50" value="${slot.max_participants ?? 1}" id="quota-mobile-${slot.id}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]">
                    </div>
                    
                    <!-- Coaches -->
                    <div class="border-t border-slate-100 pt-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Coaches</label>
                        <button type="button" onclick="openCoachModal(${slot.id}, '${escapeHtml(JSON.stringify(slotCoaches))}')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-[#1a307b] rounded-lg text-sm font-medium text-slate-700 hover:text-[#1a307b] transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Select Coaches
                            <span class="selected-count-badge-mobile ml-1 px-2 py-0.5 bg-[#1a307b]/10 text-[#1a307b] rounded-full text-xs font-bold" data-slot-id="${slot.id}">${slotCoaches.length}</span>
                        </button>
                        <div class="hidden" data-selected-coaches-mobile="${slot.id}">${slotCoaches.join(',')}</div>
                        <div class="mt-2 text-xs text-green-600 font-medium" data-selected-coaches-names-mobile="${slot.id}">✓ ${COACHES.filter(c => slotCoaches.includes(Number(c.id))).map(c => c.name).join(', ')}</div>
                    </div>
                    
                    <!-- Bookings -->
                    ${bookings.length > 0 ? `
                        <div class="border-t border-slate-100 pt-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Member Bookings (${bookings.length})</label>
                            <div class="space-y-2">
                                ${bookings.map(booking => {
                                    const mp = booking.member_package || booking.memberPackage || {};
                                    const member = mp.member || {};
                                    return `
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                            <p class="text-xs font-semibold text-slate-700 mb-2">${escapeHtml(member.name || 'Member')}</p>
                                            <div class="space-y-2">
                                                <select id="move-slot-mobile-${booking.id}" class="w-full px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-[#1a307b]">
                                                    ${slots.map(s => {
                                                        const sTime = s.session_time || s.sessionTime || {};
                                                        const sLabel = `${escapeHtml(sTime.name || 'Slot')} (${escapeHtml(sTime.start_time || '')}${sTime.start_time && sTime.end_time ? ' - ' : ''}${escapeHtml(sTime.end_time || '')})`;
                                                        return `<option value="${s.id}" ${s.id === slot.id ? 'selected' : ''}>${sLabel}</option>`;
                                                    }).join('')}
                                                </select>
                                                <div class="flex gap-2">
                                                    <button type="button" class="flex-1 px-3 py-1.5 bg-[#1a307b] hover:bg-[#152866] text-white rounded-lg text-xs font-medium" onclick="moveBooking(${booking.id}, true)">Move</button>
                                                    <button type="button" class="flex-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-medium" onclick="removeBooking(${booking.id})">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }).join('');


    tbody.innerHTML = slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        const slotCoaches = Array.isArray(slot.coaches) ? slot.coaches.map(c => Number(c.id)) : [];
        const bookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings : (Array.isArray(slot.confirmedBookings) ? slot.confirmedBookings : []);
        const slotOptions = slots.map(s => {
            const sTime = s.session_time || s.sessionTime || {};
            const sLabel = `${escapeHtml(sTime.name || 'Slot')} (${escapeHtml(sTime.start_time || '')}${sTime.start_time && sTime.end_time ? ' - ' : ''}${escapeHtml(sTime.end_time || '')})`;
            return `<option value="${s.id}">${sLabel}</option>`;
        }).join('');
        
        return `
            <tr>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${escapeHtml(st.name || 'Session')}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</td>
                <td class="px-6 py-4">
                    <input type="number" min="1" max="50" value="${slot.max_participants ?? 1}" id="quota-${slot.id}" class="w-20 sm:w-28 px-2 sm:px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]">
                </td>
                <td class="px-6 py-4">
                    <button type="button" onclick="openCoachModal(${slot.id}, '${escapeHtml(JSON.stringify(slotCoaches))}')" class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 bg-white border-2 border-slate-200 hover:border-[#1a307b] rounded-lg text-xs sm:text-sm font-medium text-slate-700 hover:text-[#1a307b] transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Select Coaches</span>
                        <span class="sm:hidden">Coaches</span>
                        <span class="selected-count-badge ml-1 px-2 py-0.5 bg-[#1a307b]/10 text-[#1a307b] rounded-full text-xs font-bold" data-slot-id="${slot.id}">${slotCoaches.length}</span>
                    </button>
                    <div class="hidden" data-selected-coaches="${slot.id}">${slotCoaches.join(',')}</div>
                    <div class="mt-2 text-xs text-green-600 font-medium" data-selected-coaches-names="${slot.id}">✓ ${COACHES.filter(c => slotCoaches.includes(Number(c.id))).map(c => c.name).join(', ')}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="space-y-2 max-h-52 overflow-y-auto">
                        ${bookings.length > 0 ? bookings.map(booking => {
                            const mp = booking.member_package || booking.memberPackage || {};
                            const member = mp.member || {};
                            return `
                                <div class="p-2 bg-slate-50 rounded-lg border border-slate-200">
                                    <p class="text-xs font-semibold text-slate-700 truncate">${escapeHtml(member.name || 'Member')}</p>
                                    <div class="mt-2 flex flex-col sm:grid sm:grid-cols-3 gap-2">
                                        <select id="move-slot-${booking.id}" class="sm:col-span-2 w-full px-2 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-[#1a307b]">
                                            ${slotOptions}
                                        </select>
                                        <div class="flex gap-2">
                                            <button type="button" class="flex-1 px-2 py-1.5 bg-[#1a307b] hover:bg-[#152866] text-white rounded-lg text-xs font-medium" onclick="moveBooking(${booking.id})">Move</button>
                                            <button type="button" class="flex-1 px-2 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-medium" onclick="removeBooking(${booking.id})">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('') : '<p class="text-xs text-slate-500">No member bookings</p>'}
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    slots.forEach(slot => {
        const bookings = Array.isArray(slot.confirmed_bookings) ? slot.confirmed_bookings : (Array.isArray(slot.confirmedBookings) ? slot.confirmedBookings : []);
        bookings.forEach(booking => {
            const select = document.getElementById(`move-slot-${booking.id}`);
            if (select) {
                select.value = String(slot.id);
            }
        });
    });
}

let currentSlotId = null;

function openCoachModal(slotId, selectedCoachesJson = '[]') {
    if (!Array.isArray(COACHES) || COACHES.length === 0) {
        notifyError('Belum ada coach aktif untuk dipilih');
        return;
    }

    currentSlotId = slotId;
    const modal = document.getElementById('coachModal');
    const searchInput = document.getElementById('coachSearch');
    searchInput.value = '';
    
    renderCoachList(slotId, '');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => searchInput.focus(), 100);
}

function closeCoachModal() {
    const modal = document.getElementById('coachModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentSlotId = null;
}

function renderCoachList(slotId, searchTerm = '') {
    const container = document.getElementById('coachList');
    const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${slotId}"]`)?.textContent || '';
    const selectedCoaches = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
    
    const filtered = COACHES.filter(c => 
        c.name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    if (filtered.length === 0) {
        container.innerHTML = '<p class="text-center py-8 text-slate-500">No coaches found</p>';
        return;
    }
    
    container.innerHTML = filtered.map(coach => {
        const isSelected = selectedCoaches.includes(Number(coach.id));
        return `
            <label class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                <input type="checkbox" 
                    class="w-4 h-4 text-[#1a307b] rounded focus:ring-2 focus:ring-[#1a307b]" 
                    data-coach-id="${coach.id}"
                    ${isSelected ? 'checked' : ''}
                    onchange="toggleCoachSelection(${slotId}, ${coach.id}, this.checked)">
                <div class="flex-1">
                    <p class="font-medium text-slate-900">${escapeHtml(coach.name)}</p>
                </div>
            </label>
        `;
    }).join('');
}

function toggleCoachSelection(slotId, coachId, isChecked) {
    // Update both desktop and mobile
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    const containerMobile = document.querySelector(`[data-selected-coaches-mobile="${slotId}"]`);
    let selectedCoaches = container ? (container.textContent ? container.textContent.split(',').map(id => Number(id)).filter(Boolean) : []) : [];
    
    if (isChecked) {
        if (!selectedCoaches.includes(coachId)) {
            selectedCoaches.push(coachId);
        }
    } else {
        selectedCoaches = selectedCoaches.filter(id => id !== coachId);
    }
    
    const selectedStr = selectedCoaches.join(',');
    if (container) container.textContent = selectedStr;
    if (containerMobile) containerMobile.textContent = selectedStr;
    updateSelectedCount(slotId);
}

function updateSelectedCount(slotId) {
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    const containerMobile = document.querySelector(`[data-selected-coaches-mobile="${slotId}"]`);
    const badge = document.querySelector(`.selected-count-badge[data-slot-id="${slotId}"]`);
    const badgeMobile = document.querySelector(`.selected-count-badge-mobile[data-slot-id="${slotId}"]`);
    const namesContainer = document.querySelector(`[data-selected-coaches-names="${slotId}"]`);
    const namesContainerMobile = document.querySelector(`[data-selected-coaches-names-mobile="${slotId}"]`);
    const selectedCoaches = container ? (container.textContent ? container.textContent.split(',').filter(Boolean) : []) : [];
    
    // Update desktop badge
    if (badge) {
        badge.textContent = selectedCoaches.length;
        badge.classList.toggle('bg-[#1a307b]/10', selectedCoaches.length > 0);
        badge.classList.toggle('text-[#1a307b]', selectedCoaches.length > 0);
        badge.classList.toggle('bg-slate-100', selectedCoaches.length === 0);
        badge.classList.toggle('text-slate-500', selectedCoaches.length === 0);
    }
    
    // Update mobile badge
    if (badgeMobile) {
        badgeMobile.textContent = selectedCoaches.length;
        badgeMobile.classList.toggle('bg-[#1a307b]/10', selectedCoaches.length > 0);
        badgeMobile.classList.toggle('text-[#1a307b]', selectedCoaches.length > 0);
        badgeMobile.classList.toggle('bg-slate-100', selectedCoaches.length === 0);
        badgeMobile.classList.toggle('text-slate-500', selectedCoaches.length === 0);
    }
    
    // Update coach names display
    const updateNamesContainer = (container) => {
        if (container) {
            if (selectedCoaches.length > 0) {
                const coachNames = COACHES
                    .filter(c => selectedCoaches.includes(String(c.id)))
                    .map(c => c.name)
                    .join(', ');
                container.textContent = '✓ ' + coachNames;
                container.classList.add('text-green-600', 'font-medium');
            } else {
                container.textContent = '';
                container.classList.remove('text-green-600', 'font-medium');
            }
        }
    };
    
    updateNamesContainer(namesContainer);
    updateNamesContainer(namesContainerMobile);
}

function searchCoaches() {
    const searchTerm = document.getElementById('coachSearch').value;
    renderCoachList(currentSlotId, searchTerm);
}

async function moveBooking(bookingId, isMobile = false) {
    const selectId = isMobile ? `move-slot-mobile-${bookingId}` : `move-slot-${bookingId}`;
    const targetSelect = document.getElementById(selectId);
    const targetSlotId = Number(targetSelect?.value || 0);

    if (!Number.isInteger(targetSlotId) || targetSlotId <= 0) {
        notifyError('Pilih slot tujuan yang valid');
        return;
    }
    
    // Confirm action with better message
    confirmAction(
        '📌 Move Member Booking',
        'Are you sure you want to move this member to the selected time slot?\n\nThis will update their booking immediately.',
        async () => {
            try {
                await window.API.patch(`/admin/bookings/${bookingId}`, {
                    training_session_slot_id: targetSlotId,
                });
                notifySuccess('Member berhasil dipindahkan ke slot baru');
                
                // Reload session data
                const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
                
                // Validate response
                if (!session || !session.id) {
                    throw new Error('Failed to reload session data');
                }
                
                CURRENT_SESSION = session;
                renderSlots(session.slots || []);
            } catch (e) {
                console.error('Move booking error:', e);
                const errorMsg = e?.response?.data?.message || e?.message || 'Failed to move member';
                notifyError(errorMsg);
            }
        },
        {
            confirmText: 'Move Member',
            cancelText: 'Cancel',
            type: 'info',
            icon: '📌'
        }
    );
}

async function removeBooking(bookingId) {
    // Validate booking ID
    if (!bookingId || !Number.isInteger(Number(bookingId))) {
        notifyError('Booking ID tidak valid');
        return;
    }
    
    confirmAction(
        '⚠️ Delete Booking',
        'Are you sure you want to permanently delete this booking?\n\nThis action cannot be undone.\nThe member will be removed from this session.',
        async () => {
            try {
                await window.API.delete(`/admin/bookings/${bookingId}`);
                notifySuccess('Booking member berhasil dihapus');
                
                // Reload session data
                const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
                
                // Validate response
                if (!session || !session.id) {
                    throw new Error('Failed to reload session data');
                }
                
                CURRENT_SESSION = session;
                renderSlots(session.slots || []);
            } catch (e) {
                console.error('Delete booking error:', e);
                const errorMsg = e?.response?.data?.message || e?.message || 'Failed to delete booking';
                notifyError(errorMsg);
            }
        },
        {
            confirmText: 'Delete Booking',
            cancelText: 'Cancel',
            type: 'danger',
            icon: '⚠️'
        }
    );
}

function toggleCoachEdit(slotId, coachId, button) {
    const isSelected = button.classList.contains('selected');
    
    if (isSelected) {
        // Deselect
        button.classList.remove('selected');
        button.style.backgroundColor = '#f1f5f9';
        button.style.borderColor = '#cbd5e1';
        button.style.color = '#475569';
    } else {
        // Select
        button.classList.add('selected');
        button.style.backgroundColor = '#3b82f6';
        button.style.borderColor = '#2563eb';
        button.style.color = '#ffffff';
    }
}

async function updateAllSlots() {
    if (!CURRENT_SESSION || !CURRENT_SESSION.slots) {
        notifyError('Data sesi tidak tersedia. Silakan refresh halaman.');
        return;
    }

    const btn = document.getElementById('updateBtn');
    if (!btn) {
        console.error('Update button not found');
        return;
    }
    
    const slots = CURRENT_SESSION.slots;

    // Validate all slots have coaches and capacities
    const slotsData = slots.map(slot => {
        const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${slot.id}"]`)?.textContent || 
                                   document.querySelector(`[data-selected-coaches-mobile="${slot.id}"]`)?.textContent || '';
        const coachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
        const maxParticipants = Number(document.getElementById(`quota-${slot.id}`)?.value || document.getElementById(`quota-mobile-${slot.id}`)?.value || 0);
        return { slotId: slot.id, coachIds, maxParticipants };
    });

    const slotWithoutCoach = slotsData.find(s => s.coachIds.length === 0);
    if (slotWithoutCoach) {
        const slotObj = (CURRENT_SESSION.slots || []).find(s => Number(s.id) === Number(slotWithoutCoach.slotId));
        const st = slotObj?.session_time || slotObj?.sessionTime || {};
        const slotLabel = st?.name || `ID ${slotWithoutCoach.slotId}`;
        notifyError(`Slot ${slotLabel} belum memiliki coach. Pilih minimal 1 coach sebelum update.`);
        return;
    }

    const invalidQuota = slotsData.find(s => !Number.isInteger(s.maxParticipants) || s.maxParticipants < 1 || s.maxParticipants > 50);
    if (invalidQuota) {
        notifyError('Kuota peserta harus 1-50 untuk semua slot');
        return;
    }
    
    // Confirm before updating
    confirmAction(
        '📌 Update All Slots',
        `You are about to update all training session slots.\n\nThis will update coaches and capacities for ${slots.length} time slot(s).\n\nContinue?`,
        async () => {
            btn.disabled = true;
            const original = btn.textContent;
            btn.textContent = '⏳ Updating...';

            try {
                // Update all slots in parallel
                await Promise.all(
                    slotsData.map(({ slotId, coachIds, maxParticipants }) =>
                        window.API.patch(`/admin/training-session-slots/${slotId}/coaches`, {
                            coach_ids: coachIds,
                            max_participants: maxParticipants,
                        })
                    )
                );

                notifySuccess(`Berhasil update ${slots.length} slot`);
                
                // Reload session data
                const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
                
                // Validate response
                if (!session || !session.id) {
                    throw new Error('Failed to reload session data');
                }
                
                CURRENT_SESSION = session;
                renderSlots(session.slots || []);
            } catch (e) {
                console.error('Update slots error:', e);
                const errorMsg = e?.response?.data?.message || e?.message || 'Failed to update coaches';
                notifyError(errorMsg);
            } finally {
                btn.disabled = false;
                btn.textContent = original;
            }
        },
        {
            confirmText: 'Update All Slots',
            cancelText: 'Cancel',
            type: 'warning',
            icon: '📌'
        }
    );
}

function escapeHtml(str) {
    return (str || '')
        .toString()
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
</script>

<!-- Coach Selection Modal -->
<div id="coachModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50" onclick="if(event.target === this) closeCoachModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-[#1a307b]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Select Coaches</h3>
                <button type="button" onclick="closeCoachModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Search -->
        <div class="px-6 py-4 border-b border-slate-200">
            <div class="relative">
                <input type="text" 
                    id="coachSearch" 
                    placeholder="Search coaches..." 
                    oninput="searchCoaches()"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b]">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        
        <!-- Coach List -->
        <div id="coachList" class="px-3 py-2 max-h-96 overflow-y-auto">
            <!-- Filled by JS -->
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            <button type="button" onclick="closeCoachModal()" class="w-full px-4 py-2 bg-[#1a307b] hover:bg-[#152866] text-white rounded-lg font-medium transition-colors">
                Done
            </button>
        </div>
    </div>
</div>
@endsection

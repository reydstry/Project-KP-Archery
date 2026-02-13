@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-white p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Edit Training Session</h1>
            <p class="text-slate-600 text-base sm:text-lg">Update slot quotas for this day</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            <button type="button" id="deleteSessionBtn" onclick="deleteSession()" class="w-full sm:w-auto px-5 py-3 bg-white hover:bg-slate-50 text-red-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Delete Session</button>
            <a href="{{ route('coach.sessions.index') }}" class="w-full sm:w-auto px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 card-animate" style="animation-delay: 0.15s">
        <div class="mb-4 sm:mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900" id="sessionHeader">Loading...</h2>
            <p class="text-xs sm:text-sm text-slate-600" id="sessionSubheader">Please wait</p>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Coaches</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsBody">
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-600">Loading slots...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const SESSION_ID = @json($id ?? null);
let CURRENT_SESSION = null;
const COACHES = @json($coaches ?? []);
const MY_COACH_ID = @json($myCoachId ?? null);

document.addEventListener('DOMContentLoaded', async () => {
    if (!SESSION_ID) {
        window.showToast('Missing session id', 'error');
        return;
    }

    try {
        const session = await window.API.get(`/coach/training-sessions/${SESSION_ID}`);
        CURRENT_SESSION = session;
        const dateStr = (session.date || '').toString().slice(0, 10);
        document.getElementById('sessionHeader').textContent = `Training Session - ${dateStr}`;
        document.getElementById('sessionSubheader').textContent = `Status: ${(session.status || '').toString()}`;

        renderSlots(session.slots || []);
    } catch (e) {
        console.error(e);
        document.getElementById('slotsBody').innerHTML = `
            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-600">Failed to load session: ${escapeHtml(e?.message || 'Unknown error')}</td></tr>
        `;
    }
});

function renderSlots(slots) {
    const tbody = document.getElementById('slotsBody');

    if (!Array.isArray(slots) || slots.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-10 text-center text-slate-600">No slots found for this session.</td></tr>`;
        return;
    }

    const myIdNum = Number(MY_COACH_ID || 0);

    tbody.innerHTML = slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        const slotCoaches = Array.isArray(slot.coaches) ? slot.coaches.map(c => Number(c.id)) : [];
        const additionalCoaches = slotCoaches.filter(id => id !== myIdNum);
        
        return `
            <tr>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${escapeHtml(st.name || 'Session')}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</td>
                <td class="px-6 py-4">
                    <input type="number" min="1" max="50" value="${slot.max_participants ?? 1}" class="w-28 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500" id="quota-${slot.id}">
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-3">
                        <button type="button" onclick="openCoachModal(${slot.id})" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-blue-500 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-600 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Additional Coaches
                            <span class="selected-count-badge ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold" data-slot-id="${slot.id}">${additionalCoaches.length}</span>
                        </button>
                        <p class="text-xs text-slate-500">Anda otomatis termasuk</p>
                        <div class="hidden" data-selected-coaches="${slot.id}">${additionalCoaches.join(',')}</div>
                        <div class="text-xs text-green-600 font-medium" data-selected-coaches-names="${slot.id}">${additionalCoaches.length > 0 ? '+ ' + COACHES.filter(c => additionalCoaches.includes(Number(c.id))).map(c => c.name).join(', ') : ''}</div>
                        <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 text-sm" onclick="updateSlotCoaches(${slot.id})">Update Coaches</button>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <button type="button" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-sm" onclick="updateQuota(${slot.id})">Update Quota</button>
                        <button type="button" class="w-full sm:w-auto px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-sm whitespace-nowrap" onclick="bookForMember(${slot.id})">Book for member</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

let currentSlotId = null;
const myIdNum = Number(MY_COACH_ID || 0);

function openCoachModal(slotId) {
    currentSlotId = slotId;
    const modal = document.getElementById('coachModal');
    const searchInput = document.getElementById('coachSearch');
    searchInput.value = '';
    
    renderCoachList(slotId, '');
    modal.classList.remove('hidden');
    setTimeout(() => searchInput.focus(), 100);
}

function closeCoachModal() {
    document.getElementById('coachModal').classList.add('hidden');
    currentSlotId = null;
}

function renderCoachList(slotId, searchTerm = '') {
    const container = document.getElementById('coachList');
    const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${slotId}"]`)?.textContent || '';
    const selectedCoaches = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
    
    // Filter out current coach and apply search
    const filtered = COACHES.filter(c => 
        Number(c.id) !== myIdNum && c.name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    if (filtered.length === 0) {
        container.innerHTML = '<p class="text-center py-8 text-slate-500">No additional coaches found</p>';
        return;
    }
    
    container.innerHTML = filtered.map(coach => {
        const isSelected = selectedCoaches.includes(Number(coach.id));
        return `
            <label class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                <input type="checkbox" 
                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" 
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
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    let selectedCoaches = container.textContent ? container.textContent.split(',').map(id => Number(id)).filter(Boolean) : [];
    
    if (isChecked) {
        if (!selectedCoaches.includes(coachId)) {
            selectedCoaches.push(coachId);
        }
    } else {
        selectedCoaches = selectedCoaches.filter(id => id !== coachId);
    }
    
    container.textContent = selectedCoaches.join(',');
    updateSelectedCount(slotId);
}

function updateSelectedCount(slotId) {
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    const badge = document.querySelector(`.selected-count-badge[data-slot-id="${slotId}"]`);
    const namesContainer = document.querySelector(`[data-selected-coaches-names="${slotId}"]`);
    const selectedCoaches = container.textContent ? container.textContent.split(',').filter(Boolean) : [];
    
    badge.textContent = selectedCoaches.length;
    badge.classList.toggle('bg-blue-100', selectedCoaches.length > 0);
    badge.classList.toggle('text-blue-700', selectedCoaches.length > 0);
    badge.classList.toggle('bg-slate-100', selectedCoaches.length === 0);
    badge.classList.toggle('text-slate-500', selectedCoaches.length === 0);
    
    // Update coach names display
    if (namesContainer) {
        if (selectedCoaches.length > 0) {
            const coachNames = COACHES
                .filter(c => selectedCoaches.includes(String(c.id)))
                .map(c => c.name)
                .join(', ');
            namesContainer.textContent = '+ ' + coachNames;
            namesContainer.classList.add('text-green-600', 'font-medium');
        } else {
            namesContainer.textContent = '';
            namesContainer.classList.remove('text-green-600', 'font-medium');
        }
    }
}

function searchCoaches() {
    const searchTerm = document.getElementById('coachSearch').value;
    renderCoachList(currentSlotId, searchTerm);
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

async function updateSlotCoaches(slotId) {
    const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${slotId}"]`)?.textContent || '';
    const additionalCoachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];

    try {
        const res = await window.API.patch(`/coach/training-sessions/${SESSION_ID}/coaches`, {
            slot_id: slotId,
            coach_ids: additionalCoachIds,
        });
        CURRENT_SESSION = res?.data || CURRENT_SESSION;
        window.showToast('Slot coaches updated', 'success');
        if (res?.data) {
            renderSlots(res.data.slots || []);
        }
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to update slot coaches', 'error');
    }
}

async function updateQuota(slotId) {
    const input = document.getElementById(`quota-${slotId}`);
    const max = Number(input?.value || 0);
    if (!Number.isInteger(max) || max < 1 || max > 50) {
        window.showToast('Max participants must be 1-50', 'error');
        return;
    }

    try {
        await window.API.patch(`/coach/training-sessions/${SESSION_ID}/quota`, {
            slot_id: slotId,
            max_participants: max,
        });
        window.showToast('Quota updated', 'success');
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to update quota', 'error');
    }
}

async function deleteSession() {
    const dateStr = (CURRENT_SESSION?.date || '').toString().slice(0, 10);
    const ok = confirm(`Delete this training session ${dateStr || `#${SESSION_ID}` }?\n\nThis will remove all slots. (Not allowed if there are bookings.)`);
    if (!ok) return;

    const btn = document.getElementById('deleteSessionBtn');
    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = 'Deleting...';

    try {
        await window.API.delete(`/coach/training-sessions/${SESSION_ID}`);
        window.showToast('Training session deleted', 'success');
        window.location.href = '{{ route('coach.sessions.index') }}';
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to delete session', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
}

async function bookForMember(slotId) {
    const memberPackageIdStr = prompt('Member Package ID to book (member_package_id):');
    if (!memberPackageIdStr) return;
    const memberPackageId = Number(memberPackageIdStr);
    if (!Number.isInteger(memberPackageId) || memberPackageId <= 0) {
        window.showToast('Invalid member_package_id', 'error');
        return;
    }

    const notes = prompt('Notes (optional):') || null;

    try {
        await window.API.post('/coach/bookings', {
            training_session_slot_id: slotId,
            member_package_id: memberPackageId,
            notes,
        });
        window.showToast('Booked successfully', 'success');
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to book', 'error');
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

<!-- Coach Selection Modal -->
<div id="coachModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) closeCoachModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Select Additional Coaches</h3>
                <button type="button" onclick="closeCoachModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-blue-100 mt-1">You are automatically included</p>
        </div>
        
        <!-- Search -->
        <div class="px-6 py-4 border-b border-slate-200">
            <div class="relative">
                <input type="text" 
                    id="coachSearch" 
                    placeholder="Search coaches..." 
                    oninput="searchCoaches()"
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
            <button type="button" onclick="closeCoachModal()" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                Done
            </button>
        </div>
    </div>
</div>
@endsection

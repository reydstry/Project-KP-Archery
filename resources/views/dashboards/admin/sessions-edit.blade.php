@extends('layouts.admin')

@section('title', 'Training Sessions')
@section('subtitle', 'Edit training session (multiple coaches)')

@section('content')
<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Edit Training Session</h1>
            <p class="text-slate-600 text-base sm:text-lg">Update coaches assigned for this day</p>
        </div>
        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto shrink-0 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
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
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsBody">
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-600">Loading slots...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 sm:mt-6 flex items-center justify-end gap-3">
            <button type="button" onclick="updateAllSlots()" id="updateBtn" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">Update All Coaches</button>
        </div>
    </div>
</div>

<script>
const SESSION_ID = @json($id ?? null);
let CURRENT_SESSION = null;
const COACHES = @json($coaches ?? []);

document.addEventListener('DOMContentLoaded', async () => {
    if (!SESSION_ID) {
        window.showToast('Missing session id', 'error');
        return;
    }

    try {
        const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
        CURRENT_SESSION = session;
        const dateStr = (session.date || '').toString().slice(0, 10);
        document.getElementById('sessionHeader').textContent = `Training Session - ${dateStr}`;
        document.getElementById('sessionSubheader').textContent = `Status: ${(session.status || '').toString()}`;

        renderSlots(session.slots || []);
    } catch (e) {
        console.error(e);
        document.getElementById('slotsBody').innerHTML = `
            <tr><td colspan="4" class="px-6 py-10 text-center text-slate-600">Failed to load session: ${escapeHtml(e?.message || 'Unknown error')}</td></tr>
        `;
    }
});

function renderSlots(slots) {
    const tbody = document.getElementById('slotsBody');

    if (!Array.isArray(slots) || slots.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-10 text-center text-slate-600">No slots found for this session.</td></tr>`;
        return;
    }

    tbody.innerHTML = slots.map(slot => {
        const st = slot.session_time || slot.sessionTime || {};
        const slotCoaches = Array.isArray(slot.coaches) ? slot.coaches.map(c => Number(c.id)) : [];
        
        return `
            <tr>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${escapeHtml(st.name || 'Session')}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${escapeHtml(st.start_time || '')}${st.start_time && st.end_time ? ' - ' : ''}${escapeHtml(st.end_time || '')}</td>
                <td class="px-6 py-4">
                    <span class="text-slate-800 font-semibold">${escapeHtml(String(slot.max_participants ?? ''))}</span>
                </td>
                <td class="px-6 py-4">
                    <button type="button" onclick="openCoachModal(${slot.id}, '${escapeHtml(JSON.stringify(slotCoaches))}')" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-blue-500 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-600 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Select Coaches
                        <span class="selected-count-badge ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold" data-slot-id="${slot.id}">${slotCoaches.length}</span>
                    </button>
                    <div class="hidden" data-selected-coaches="${slot.id}">${slotCoaches.join(',')}</div>
                    <div class="mt-2 text-xs text-green-600 font-medium" data-selected-coaches-names="${slot.id}">✓ ${COACHES.filter(c => slotCoaches.includes(Number(c.id))).map(c => c.name).join(', ')}</div>
                </td>
            </tr>
        `;
    }).join('');
}

let currentSlotId = null;

function openCoachModal(slotId, selectedCoachesJson = '[]') {
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
            namesContainer.textContent = '✓ ' + coachNames;
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

async function updateAllSlots() {
    if (!CURRENT_SESSION || !CURRENT_SESSION.slots) {
        window.showToast('No session data available', 'error');
        return;
    }

    const btn = document.getElementById('updateBtn');
    const slots = CURRENT_SESSION.slots;

    // Validate all slots have coaches
    const slotsData = slots.map(slot => {
        const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${slot.id}"]`)?.textContent || '';
        const coachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
        return { slotId: slot.id, coachIds };
    });

    const slotWithoutCoach = slotsData.find(s => s.coachIds.length === 0);
    if (slotWithoutCoach) {
        window.showToast('Each slot must have at least one coach assigned', 'error');
        return;
    }

    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = 'Updating...';

    try {
        // Update all slots in parallel
        await Promise.all(
            slotsData.map(({ slotId, coachIds }) =>
                window.API.patch(`/admin/training-session-slots/${slotId}/coaches`, {
                    coach_ids: coachIds,
                })
            )
        );

        window.showToast('All coaches updated successfully', 'success');
        
        // Reload session data
        const session = await window.API.get(`/admin/training-sessions/${SESSION_ID}`);
        CURRENT_SESSION = session;
        renderSlots(session.slots || []);
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to update coaches', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
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
<div id="coachModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) closeCoachModal()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-600 to-blue-700">
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

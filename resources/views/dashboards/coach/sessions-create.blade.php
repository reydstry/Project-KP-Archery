@extends('layouts.coach')

@section('title', 'Create Training Session')
@section('subtitle', 'Create a day session with one or more slots')

@section('content')
<div class="min-h-screen bg-white px-2 py-2 sm:p-8">
    <div class="flex items-center justify-end mb-2 sm:mb-6 card-animate" style="animation-delay: 0.1s">
        <a href="{{ route('coach.sessions.index') }}" class="w-full sm:w-auto shrink-0 px-3 py-1.5 sm:px-5 sm:py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg sm:rounded-xl text-xs sm:text-sm font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200/60 p-2 sm:p-4 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 sm:gap-4 mb-2 sm:mb-4">
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Date</label>
                <input type="date" id="sessionDate" class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                <p class="text-xs text-slate-500 mt-1">Minimal hari ini (sesuai validasi backend).</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Slots</label>
                <p class="text-xs sm:text-sm text-slate-600">Pilih slot yang aktif dan isi kuota per slot.</p>
            </div>
        </div>

        <div class="overflow-x-auto -mx-2 sm:mx-0">
            <table class="w-full text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Use</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-2 sm:px-3 py-2 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsTable">
                    <!-- filled by JS -->
                </tbody>
            </table>
        </div>

        <div class="mt-2 sm:mt-4 flex items-center justify-end gap-2">
            <button type="button" onclick="submitCreate()" id="createBtn" class="w-full sm:w-auto px-3 py-1.5 sm:px-5 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">Create</button>
        </div>
    </div>
</div>


<script>
const SESSION_TIMES = @json($sessionTimes ?? []);
const COACHES = @json($coaches ?? []);
const MY_COACH_ID = @json($myCoachId ?? null);

document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('sessionDate');
    const today = new Date();   
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    dateInput.min = todayStr;
    dateInput.value = todayStr;

    renderSlotsTable();
});

function renderSlotsTable() {
    const tbody = document.getElementById('slotsTable');

    if (!Array.isArray(SESSION_TIMES) || SESSION_TIMES.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="px-2 sm:px-3 py-6 text-center text-xs sm:text-sm text-slate-600">No active session times found.</td>
            </tr>
        `;
        return;
    }

    const myIdNum = Number(MY_COACH_ID || 0);

    tbody.innerHTML = SESSION_TIMES.map(st => {
        return `
            <tr>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500" data-session-time-id="${st.id}" checked>
                </td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <p class="text-xs sm:text-sm font-semibold text-slate-900">${st.name}</p>
                </td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5 text-xs text-slate-600">${st.start_time}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time}</td>
                <td class="px-2 sm:px-3 py-2 sm:py-2.5">
                    <input type="number" min="1" max="50" value="10" class="w-14 sm:w-24 px-2 py-1 sm:px-2.5 sm:py-1.5 bg-slate-50 border border-slate-200 rounded text-xs sm:text-sm focus:ring-2 focus:ring-blue-500" data-max-input-for="${st.id}">
                </td>
                <td class="px-6 py-4">
                    <button type="button" onclick="openCoachModal(${st.id})" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-blue-500 rounded-lg text-sm font-medium text-slate-700 hover:text-blue-600 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Additional Coaches
                        <span class="selected-count-badge ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold" data-slot-id="${st.id}">0</span>
                    </button>
                    <p class="text-xs text-slate-500 mt-1">Anda otomatis termasuk</p>
                    <div class="hidden" data-selected-coaches="${st.id}"></div>
                    <div class="mt-2 text-xs text-slate-600" data-selected-coaches-names="${st.id}"></div>
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
    const selectedCoaches = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)) : [];
    
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
                    <p class="font-medium text-slate-900">${coach.name}</p>
                </div>
            </label>
        `;
    }).join('');
}

function toggleCoachSelection(slotId, coachId, isChecked) {
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    let selectedCoaches = container.textContent ? container.textContent.split(',').map(id => Number(id)) : [];
    
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

async function submitCreate() {
    const btn = document.getElementById('createBtn');
    const date = document.getElementById('sessionDate').value;

    if (!date) {
        window.showToast('Date is required', 'error');
        return;
    }

    const selected = Array.from(document.querySelectorAll('#slotsTable input[type="checkbox"][data-session-time-id]'))
        .filter(cb => cb.checked)
        .map(cb => {
            const sessionTimeId = Number(cb.getAttribute('data-session-time-id'));
            const maxInput = document.querySelector(`#slotsTable input[data-max-input-for="${sessionTimeId}"]`);
            const maxParticipants = Number(maxInput?.value || 0);
            const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${sessionTimeId}"]`)?.textContent || '';
            const additionalCoachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
            return { 
                session_time_id: sessionTimeId, 
                max_participants: maxParticipants,
                coach_ids: additionalCoachIds
            };
        });

    if (selected.length === 0) {
        window.showToast('Select at least one slot', 'error');
        return;
    }

    const invalid = selected.find(s => !Number.isInteger(s.max_participants) || s.max_participants < 1 || s.max_participants > 50);
    if (invalid) {
        window.showToast('Max participants must be 1-50', 'error');
        return;
    }

    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = 'Creating...';

    try {
        await window.API.post('/coach/training-sessions', {
            date,
            slots: selected,
        });

        window.showToast('Training session created', 'success');
        window.location.href = '{{ route('coach.sessions.index') }}';
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to create session', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
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

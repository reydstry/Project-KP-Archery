@extends('layouts.admin')

@section('title', 'Training Sessions')
@section('subtitle', 'Buat training session seperti dashboard coach')

@section('content')
<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Create Training Session</h1>
            <p class="text-slate-600 text-base sm:text-lg">Create a day session with one or more slots</p>
        </div>
        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto shrink-0 px-5 py-3 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center">Back</a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Date</label>
                <input type="date" id="sessionDate" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#1a307b] focus:border-[#1a307b] transition-all duration-200">
                <p class="text-xs text-slate-500 mt-2">Minimal hari ini (sesuai validasi backend).</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Slots</label>
                <p class="text-sm text-slate-600">Pilih slot yang aktif, isi kuota, dan pilih coach per slot.</p>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-4 sm:mx-0">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Use</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Coaches</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsTable">
                    <!-- filled by JS -->
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-3" id="slotsMobile">
            <!-- filled by JS -->
        </div>

        <div class="mt-4 sm:mt-6 flex items-center justify-end gap-3">
            <button type="button" onclick="submitCreate()" id="createBtn" class="w-full sm:w-auto px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200">Create</button>
        </div>
    </div>
</div>

@php
    /** @var \Illuminate\Support\Collection $sessionTimes */
    /** @var \Illuminate\Support\Collection $coaches */
@endphp

<script>
const SESSION_TIMES = @json($sessionTimes ?? []);
const COACHES = @json($coaches ?? []);

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
    const mobileContainer = document.getElementById('slotsMobile');

    if (!Array.isArray(SESSION_TIMES) || SESSION_TIMES.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-slate-600">No active session times found.</td>
            </tr>
        `;
        mobileContainer.innerHTML = `
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center text-slate-400">
                No active session times found.
            </div>
        `;
        return;
    }

    // Render desktop table
    tbody.innerHTML = SESSION_TIMES.map(st => {
        return `
            <tr>
                <td class="px-6 py-4">
                    <input type="checkbox" class="w-4 h-4 text-[#1a307b] rounded focus:ring-2 focus:ring-[#1a307b]" data-session-time-id="${st.id}" checked>
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-slate-900">${st.name}</p>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${st.start_time}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time}</td>
                <td class="px-6 py-4">
                    <input type="number" min="1" max="50" value="10" class="w-28 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]" data-max-input-for="${st.id}">
                </td>
                <td class="px-6 py-4">
                    <button type="button" onclick="openCoachModal(${st.id})" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-[#1a307b] rounded-lg text-sm font-medium text-slate-700 hover:text-[#1a307b] transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Select Coaches
                        <span class="selected-count-badge ml-1 px-2 py-0.5 bg-[#1a307b]/10 text-[#1a307b] rounded-full text-xs font-bold" data-slot-id="${st.id}">0</span>
                    </button>
                    <div class="hidden" data-selected-coaches="${st.id}"></div>
                    <div class="mt-3 space-y-2" data-selected-coaches-names="${st.id}"></div>
                </td>
            </tr>
        `;
    }).join('');

    // Render mobile cards
    mobileContainer.innerHTML = SESSION_TIMES.map(st => {
        return `
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="space-y-3">
                    <!-- Use Checkbox & Session Info -->
                    <div class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 w-4 h-4 text-[#1a307b] rounded focus:ring-2 focus:ring-[#1a307b]" data-session-time-id-mobile="${st.id}" checked>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-slate-900 text-sm mb-1">${st.name}</h3>
                            <p class="text-xs text-slate-600">${st.start_time}${st.start_time && st.end_time ? ' - ' : ''}${st.end_time}</p>
                        </div>
                    </div>
                    
                    <!-- Max Participants -->
                    <div class="border-t border-slate-100 pt-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Max Participants</label>
                        <input type="number" min="1" max="50" value="10" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]" data-max-input-for-mobile="${st.id}">
                    </div>
                    
                    <!-- Coaches -->
                    <div class="border-t border-slate-100 pt-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Coaches</label>
                        <button type="button" onclick="openCoachModal(${st.id})" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border-2 border-slate-200 hover:border-[#1a307b] rounded-lg text-sm font-medium text-slate-700 hover:text-[#1a307b] transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Select Coaches
                            <span class="selected-count-badge-mobile ml-1 px-2 py-0.5 bg-[#1a307b]/10 text-[#1a307b] rounded-full text-xs font-bold" data-slot-id="${st.id}">0</span>
                        </button>
                        <div class="hidden" data-selected-coaches-mobile="${st.id}"></div>
                        <div class="mt-3 space-y-2" data-selected-coaches-names-mobile="${st.id}"></div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

let currentSlotId = null;

function openCoachModal(slotId) {
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
    const selectedCoaches = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)) : [];
    
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
                    <p class="font-medium text-slate-900">${coach.name}</p>
                    ${coach.specialization ? `<p class="text-xs text-slate-500">${coach.specialization}</p>` : ''}
                </div>
            </label>
        `;
    }).join('');
}

function toggleCoachSelection(slotId, coachId, isChecked) {
    // Update both desktop and mobile
    const container = document.querySelector(`[data-selected-coaches="${slotId}"]`);
    const containerMobile = document.querySelector(`[data-selected-coaches-mobile="${slotId}"]`);
    let selectedCoaches = container ? (container.textContent ? container.textContent.split(',').map(id => Number(id)) : []) : [];
    
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
    
    // Update coach names display with badges
    const updateNamesContainer = (container) => {
        if (container) {
            if (selectedCoaches.length > 0) {
                const selectedCoachObjects = COACHES.filter(c => selectedCoaches.includes(String(c.id)));
                const badgesHtml = selectedCoachObjects
                    .map(coach => `
                        <div class="flex items-center gap-2.5 px-4 py-2.5 bg-green-100 text-green-800 rounded-lg text-sm font-semibold border-2 border-green-300 shadow-sm transition-all hover:bg-green-200 hover:shadow-md">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">${coach.name}</span>
                        </div>
                    `)
                    .join('');
                container.innerHTML = badgesHtml;
                container.classList.remove('text-green-600', 'font-medium');
            } else {
                container.innerHTML = '';
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

async function submitCreate() {
    const btn = document.getElementById('createBtn');
    const date = document.getElementById('sessionDate').value;

    if (!date) {
        window.showToast('❌ Date is required', 'error');
        return;
    }
    
    // Validate date is not in the past
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    selectedDate.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        window.showToast('❌ Cannot create session for past dates', 'error');
        return;
    }

    // Collect from desktop OR mobile (check which is visible)
    const isDesktop = window.innerWidth >= 768;
    
    let selected = [];
    if (isDesktop) {
        // Collect from desktop table
        selected = Array.from(document.querySelectorAll('#slotsTable input[type="checkbox"][data-session-time-id]'))
            .filter(cb => cb.checked)
            .map(cb => {
                const sessionTimeId = Number(cb.getAttribute('data-session-time-id'));
                const maxInput = document.querySelector(`input[data-max-input-for="${sessionTimeId}"]`);
                const maxParticipants = Number(maxInput?.value || 0);
                const selectedCoachesStr = document.querySelector(`[data-selected-coaches="${sessionTimeId}"]`)?.textContent || '';
                const coachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
                return { 
                    session_time_id: sessionTimeId, 
                    max_participants: maxParticipants,
                    coach_ids: coachIds
                };
            });
    } else {
        // Collect from mobile cards
        selected = Array.from(document.querySelectorAll('input[type="checkbox"][data-session-time-id-mobile]'))
            .filter(cb => cb.checked)
            .map(cb => {
                const sessionTimeId = Number(cb.getAttribute('data-session-time-id-mobile'));
                const maxInput = document.querySelector(`input[data-max-input-for-mobile="${sessionTimeId}"]`);
                const maxParticipants = Number(maxInput?.value || 0);
                const selectedCoachesStr = document.querySelector(`[data-selected-coaches-mobile="${sessionTimeId}"]`)?.textContent || '';
                const coachIds = selectedCoachesStr ? selectedCoachesStr.split(',').map(id => Number(id)).filter(Boolean) : [];
                return { 
                    session_time_id: sessionTimeId, 
                    max_participants: maxParticipants,
                    coach_ids: coachIds
                };
            });
    }

    if (selected.length === 0) {
        window.showToast('❌ Please select at least one time slot', 'error');
        return;
    }

    // Check if all selected slots have at least one coach
    const slotWithoutCoach = selected.find(s => !s.coach_ids || s.coach_ids.length === 0);
    if (slotWithoutCoach) {
        window.showToast('❌ Each slot must have at least one coach assigned', 'error');
        return;
    }

    const invalid = selected.find(s => !Number.isInteger(s.max_participants) || s.max_participants < 1 || s.max_participants > 50);
    if (invalid) {
        window.showToast('❌ Max participants must be between 1-50 for all slots', 'error');
        return;
    }
    
    // Confirm before creating
    showConfirm(
        '📅 Create Training Session',
        `Date: ${date}\nSlots: ${selected.length} time slot(s)\n\nAre you sure you want to create this training session?`,
        async () => {
            btn.disabled = true;
            const original = btn.textContent;
            btn.textContent = '⏳ Creating...';

            try {
                const response = await window.API.post('/admin/training-sessions', {
                    date,
                    slots: selected,
                });
                
                // Validate response
                if (!response || !response.id) {
                    throw new Error('Invalid response from server');
                }

                showSuccess('Training session has been created successfully! Redirecting...', 'Success!');
                
                // Redirect after short delay to show modal
                setTimeout(() => {
                    window.location.href = '{{ route('dashboard') }}';
                }, 1500);
            } catch (e) {
                console.error('Create session error:', e);
                const errorMsg = e?.response?.data?.message || e?.message || 'Failed to create session';
                showError(errorMsg, 'Creation Failed');
                
                btn.disabled = false;
                btn.textContent = original;
            }
        },
        { 
            confirmText: 'Create Session',
            cancelText: 'Cancel',
            type: 'info',
            icon: '📅'
        }
    );
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

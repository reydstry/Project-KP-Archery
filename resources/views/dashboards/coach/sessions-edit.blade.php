@extends('layouts.coach')

@section('title', 'Edit Training Session')
@section('subtitle', 'Update slot quotas for this day')

@section('content')
<div class="min-h-screen bg-white">
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-1.5 mb-2 card-animate" style="animation-delay: 0.1s">
        <button type="button" id="deleteSessionBtn" onclick="deleteSession()" class="w-full sm:w-auto px-3 py-1.5 bg-white hover:bg-slate-50 text-red-700 rounded-lg font-medium border border-slate-200 transition-all duration-200 text-xs text-center">Delete Session</button>
        <a href="{{ route('coach.sessions.index') }}" class="w-full sm:w-auto px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg font-medium border border-slate-200 transition-all duration-200 text-xs text-center">Back</a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-slate-200/60 p-3 card-animate" style="animation-delay: 0.15s">
        <div class="mb-2">
            <h2 class="text-sm sm:text-base font-bold text-slate-900" id="sessionHeader">Loading...</h2>
            <p class="text-xs text-slate-600" id="sessionSubheader">Please wait</p>
        </div>

        <div class="overflow-x-auto -mx-3 sm:mx-0">
            <table class="w-full min-w-[580px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-2 py-2 sm:px-3 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Session Time</th>
                        <th class="px-2 py-2 sm:px-3 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Time</th>
                        <th class="px-2 py-2 sm:px-3 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Max Participants</th>
                        <th class="px-2 py-2 sm:px-3 sm:py-2.5 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200" id="slotsBody">
                    <tr>
                        <td colspan="4" class="px-2 py-6 sm:px-4 sm:py-8 text-center text-slate-600 text-xs">Loading slots...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Book for Member Modal -->
    <div id="bookMemberModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeBookMemberModal()"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Book for Member</h3>
                        <p class="text-gray-600 text-sm mb-5">Masukkan Member Package ID untuk booking</p>
                        
                        <div class="space-y-4 text-left">
                            <div>
                                <label for="memberPackageIdInput" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Member Package ID <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="memberPackageIdInput" 
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Contoh: 1"
                                       min="1">
                            </div>
                            <div>
                                <label for="bookNotesInput" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Notes (Opsional)
                                </label>
                                <textarea id="bookNotesInput" rows="3"
                                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                          placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 justify-center">
                    <button type="button" onclick="closeBookMemberModal()" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-gray-100 text-gray-700 rounded-xl font-medium border border-gray-300 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="button" id="confirmBookBtn" onclick="confirmBookMember()" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg">
                        Book Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Session Confirmation Modal -->
    <div id="deleteSessionModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeDeleteSessionModal()"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Delete Training Session</h3>
                        <p class="text-gray-600 mb-1" id="deleteSessionDate"></p>
                        <p class="text-sm text-gray-500 mt-4">This will remove all slots. (Not allowed if there are bookings.)</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 justify-center">
                    <button type="button" onclick="closeDeleteSessionModal()" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-gray-100 text-gray-700 rounded-xl font-medium border border-gray-300 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteSessionBtn" onclick="confirmDeleteSession()" class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg">
                        Delete Session
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const SESSION_ID = @json($id ?? null);
let CURRENT_SESSION = null;
let CURRENT_SLOT_ID = null;

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
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <button type="button" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-sm" onclick="updateQuota(${slot.id})">Update</button>
                        <button type="button" class="w-full sm:w-auto px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-sm whitespace-nowrap" onclick="bookForMember(${slot.id})">Book for member</button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
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

function deleteSession() {
    const dateStr = (CURRENT_SESSION?.date || '').toString().slice(0, 10);
    document.getElementById('deleteSessionDate').textContent = dateStr || `#${SESSION_ID}`;
    document.getElementById('deleteSessionModal').classList.remove('hidden');
}

function closeDeleteSessionModal() {
    document.getElementById('deleteSessionModal').classList.add('hidden');
}

async function confirmDeleteSession() {
    const btn = document.getElementById('confirmDeleteSessionBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    try {
        await window.API.delete(`/coach/training-sessions/${SESSION_ID}`);
        window.showToast('Training session deleted successfully', 'success');
        setTimeout(() => {
            window.location.href = '{{ route('coach.sessions.index') }}';
        }, 500);
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to delete session', 'error');
        btn.disabled = false;
        btn.textContent = 'Delete Session';
    }
}

function bookForMember(slotId) {
    CURRENT_SLOT_ID = slotId;
    document.getElementById('memberPackageIdInput').value = '';
    document.getElementById('bookNotesInput').value = '';
    document.getElementById('bookMemberModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('memberPackageIdInput').focus();
    }, 100);
}

function closeBookMemberModal() {
    document.getElementById('bookMemberModal').classList.add('hidden');
    CURRENT_SLOT_ID = null;
}

async function confirmBookMember() {
    const memberPackageIdStr = document.getElementById('memberPackageIdInput').value;
    const notes = document.getElementById('bookNotesInput').value.trim() || null;
    
    if (!memberPackageIdStr) {
        window.showToast('Member Package ID is required', 'error');
        return;
    }
    
    const memberPackageId = Number(memberPackageIdStr);
    if (!Number.isInteger(memberPackageId) || memberPackageId <= 0) {
        window.showToast('Invalid Member Package ID', 'error');
        return;
    }
    
    const btn = document.getElementById('confirmBookBtn');
    btn.disabled = true;
    btn.textContent = 'Booking...';

    try {
        await window.API.post('/coach/bookings', {
            training_session_slot_id: CURRENT_SLOT_ID,
            member_package_id: memberPackageId,
            notes,
        });
        window.showToast('Member booked successfully', 'success');
        closeBookMemberModal();
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to book member', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Book Now';
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

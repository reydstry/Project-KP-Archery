@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-white p-4 sm:p-8">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.1s">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-2">Training Sessions</h1>
            <p class="text-slate-600 text-base sm:text-lg">Manage your training sessions and schedules</p>
        </div>
        <a href="{{ route('coach.sessions.create') }}" class="w-full sm:w-auto shrink-0 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Create New Session</span>
        </a>
    </div>

        <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sm:p-6 mb-6 sm:mb-8 card-animate" style="animation-delay: 0.15s">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="">All Status</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>

            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">Search Sessions</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by date (YYYY-MM-DD)..." class="w-full px-4 py-3 pl-11 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Sort By</label>
                <select id="sortFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="date_desc">Newest First</option>
                    <option value="date_asc">Oldest First</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Sessions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 card-animate" style="animation-delay: 0.2s" id="sessionsGrid">
        <!-- Loading State -->
        <div class="col-span-full text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-slate-600 mt-4">Loading sessions...</p>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeDeleteModal()"></div>
            
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
                        <p class="text-gray-600 mb-1" id="deleteModalDate"></p>
                        <p class="text-sm text-gray-500 mt-4">This will remove all slots. (Not allowed if there are bookings.)</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 justify-center">
                    <button type="button" onclick="closeDeleteModal()" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-gray-100 text-gray-700 rounded-xl font-medium border border-gray-300 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteBtn" onclick="confirmDelete()" class="w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg">
                        Delete Session
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let allSessions = [];
let sessionToDelete = null;

document.addEventListener('DOMContentLoaded', function() {
    fetchSessions();

    // Event listeners for filters
    document.getElementById('searchInput').addEventListener('input', filterSessions);
    document.getElementById('statusFilter').addEventListener('change', filterSessions);
    document.getElementById('sortFilter').addEventListener('change', filterSessions);
});

function fetchSessions() {
    const grid = document.getElementById('sessionsGrid');

    window.API.get('/coach/training-sessions')
        .then(data => {
            allSessions = data?.data || [];
            renderSessions(allSessions);
        })
        .catch(error => {
            console.error('Error:', error);
            grid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-slate-600 font-medium">Failed to load sessions</p>
                    <p class="text-slate-500 text-sm mt-1">${error?.message || 'Please try again'}</p>
                </div>
            `;
        });
}

function filterSessions() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;

    let filtered = allSessions.filter(session => {
        const dateStr = (session.date || '').toString().slice(0, 10);
        const matchesSearch = !searchTerm || dateStr.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || (session.status || '').toLowerCase() === statusFilter;

        return matchesSearch && matchesStatus;
    });

    // Sort
    filtered.sort((a, b) => {
        switch(sortFilter) {
            case 'date_asc':
                return new Date(a.date) - new Date(b.date);
            case 'date_desc':
                return new Date(b.date) - new Date(a.date);
            default:
                return 0;
        }
    });

    renderSessions(filtered);
}

function renderSessions(sessions) {
    const container = document.getElementById('sessionsGrid');

    if (sessions.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-600 font-medium">No sessions found</p>
                <p class="text-slate-500 text-sm mt-1">Try adjusting your filters</p>
            </div>
        `;
        return;
    }

    const statusMeta = {
        open: { label: 'Open', badge: 'bg-emerald-50 text-emerald-700', header: 'from-emerald-500 to-emerald-600' },
        closed: { label: 'Closed', badge: 'bg-slate-100 text-slate-700', header: 'from-slate-500 to-slate-600' },
        canceled: { label: 'Canceled', badge: 'bg-red-50 text-red-700', header: 'from-red-500 to-red-600' },
    };

    container.innerHTML = sessions.map(session => {
        const meta = statusMeta[(session.status || '').toLowerCase()] || { label: session.status || 'Unknown', badge: 'bg-slate-100 text-slate-700', header: 'from-slate-500 to-slate-600' };
        const dateStr = (session.date || '').toString().slice(0, 10);
        const slots = Array.isArray(session.slots) ? session.slots : [];

        return `
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300">

                <div class="relative">
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-3 py-1 ${meta.badge} text-xs font-semibold rounded-full shadow-lg">${meta.label}</span>
                    </div>
                    <div class="absolute top-4 right-4 z-10 flex items-center gap-2">
                        <button type="button" onclick="deleteSession(${session.id})" class="p-2 bg-white hover:bg-red-50 text-red-600 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        <a href="/coach/sessions/${session.id}/edit" class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                    <div class="h-24 bg-gradient-to-br ${meta.header} flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-white text-sm opacity-90">Training Day</p>
                            <p class="text-white text-xl font-bold">${dateStr || '-'}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Session Slots</h3>
                    <p class="text-sm text-slate-600 mb-4">${slots.length} slot(s)</p>

                    <div class="space-y-2 mb-6">
                        ${slots.length ? slots.map(slot => {
                            const st = slot.session_time || slot.sessionTime || {};
                            const name = st.name || 'Session';
                            const start = st.start_time || '';
                            const end = st.end_time || '';
                            return `
                                <div class="flex items-center justify-between text-sm bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">${name}</p>
                                        <p class="text-slate-600">${start}${start && end ? ' - ' : ''}${end}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-slate-900">Quota</p>
                                        <p class="text-slate-600">${slot.max_participants ?? '-'}</p>
                                    </div>
                                </div>
                            `;
                        }).join('') : `<div class="text-sm text-slate-600 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">No slots yet. Edit this session to add/update slots.</div>`}
                    </div>
                </div>

            </div>
        `;
    }).join('');
}

function deleteSession(sessionId) {
    sessionToDelete = sessionId;
    const target = allSessions.find(s => Number(s.id) === Number(sessionId));
    const dateStr = (target?.date || '').toString().slice(0, 10);
    
    document.getElementById('deleteModalDate').textContent = dateStr || `#${sessionId}`;
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
    btn.textContent = 'Deleting...';
    
    try {
        await window.API.delete(`/coach/training-sessions/${sessionToDelete}`);
        window.showToast('Training session deleted', 'success');
        closeDeleteModal();
        await fetchSessions();
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to delete session', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Delete Session';
    }
}
</script>
@endsection

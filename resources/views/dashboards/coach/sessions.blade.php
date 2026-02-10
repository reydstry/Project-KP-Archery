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

</div>

<script>
let allSessions = [];

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
                    <div class="absolute top-4 right-4 z-10">
                        <span class="px-3 py-1 ${meta.badge} text-xs font-semibold rounded-full shadow-lg">${meta.label}</span>
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

                    <div class="flex items-center gap-2">
                        <a href="/coach/sessions/${session.id}/edit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-center text-sm">Edit</a>
                        <button type="button" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-red-700 rounded-xl font-medium border border-slate-200 transition-all duration-200 text-center text-sm" onclick="deleteSession(${session.id})">Delete</button>
                    </div>
                </div>

            </div>
        `;
    }).join('');
}

async function deleteSession(sessionId) {
    const target = allSessions.find(s => Number(s.id) === Number(sessionId));
    const dateStr = (target?.date || '').toString().slice(0, 10);

    const ok = confirm(`Delete training session ${dateStr || `#${sessionId}` }?\n\nThis will remove all slots. (Not allowed if there are bookings.)`);
    if (!ok) return;

    try {
        await window.API.delete(`/coach/training-sessions/${sessionId}`);
        window.showToast('Training session deleted', 'success');
        await fetchSessions();
    } catch (e) {
        console.error(e);
        window.showToast(e?.message || 'Failed to delete session', 'error');
    }
}
</script>
@endsection

<?php
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Training Sessions</h1>
            <p class="text-slate-600 text-lg">Manage your training sessions and schedules</p>
        </div>
        <a href="{{ route('coach.sessions.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Create New Session</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">Search Sessions</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by title, date, or location..." class="w-full px-4 py-3 pl-11 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
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
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Sort By</label>
                <select id="sortFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="date_desc">Newest First</option>
                    <option value="date_asc">Oldest First</option>
                    <option value="title_asc">Title A-Z</option>
                    <option value="title_desc">Title Z-A</option>
                </select>
            </div>

        </div>
    </div>

    <!-- Sessions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="sessionsGrid">
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
    fetch('{{ route("coach.sessions.index") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        allSessions = data.sessions || [];
        renderSessions(allSessions);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('sessionsGrid').innerHTML = `
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-600 font-medium">No sessions found</p>
                <p class="text-slate-500 text-sm mt-1">Create your first training session</p>
            </div>
        `;
    });
}

function filterSessions() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sortFilter = document.getElementById('sortFilter').value;

    let filtered = allSessions.filter(session => {
        const matchesSearch = session.title.toLowerCase().includes(searchTerm) ||
                            session.location.toLowerCase().includes(searchTerm) ||
                            session.date.includes(searchTerm);

        const matchesStatus = !statusFilter || session.status.toLowerCase() === statusFilter;

        return matchesSearch && matchesStatus;
    });

    // Sort
    filtered.sort((a, b) => {
        switch(sortFilter) {
            case 'date_asc':
                return new Date(a.date) - new Date(b.date);
            case 'date_desc':
                return new Date(b.date) - new Date(a.date);
            case 'title_asc':
                return a.title.localeCompare(b.title);
            case 'title_desc':
                return b.title.localeCompare(a.title);
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

    container.innerHTML = sessions.map(session => `
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden hover:shadow-xl hover:border-blue-200 transition-all duration-300">

            <!-- Status Badge -->
            <div class="relative">
                <div class="absolute top-4 right-4 z-10">
                    <span class="px-3 py-1 bg-${session.statusColor}-50 text-${session.statusColor}-600 text-xs font-semibold rounded-full shadow-lg">
                        ${session.status}
                    </span>
                </div>
                <div class="h-32 bg-gradient-to-br from-${session.statusColor}-500 to-${session.statusColor}-600 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <h3 class="text-xl font-bold text-slate-900 mb-4">${session.title}</h3>

                <div class="space-y-3 text-sm text-slate-600 mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>${session.date} at ${session.time}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>${session.duration}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>${session.location}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>${session.participants}/${session.maxParticipants} participants</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="/coach/sessions/${session.id}/edit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-center text-sm">
                        Edit
                    </a>
                    <button onclick="deleteSession(${session.id})" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 text-sm">
                        Delete
                    </button>
                </div>
            </div>

        </div>
    `).join('');
}

function deleteSession(id) {
    if (!confirm('Are you sure you want to delete this session?')) return;

    fetch(`/coach/sessions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchSessions();
            alert('Session deleted successfully!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete session');
    });
}
</script>
@endsection

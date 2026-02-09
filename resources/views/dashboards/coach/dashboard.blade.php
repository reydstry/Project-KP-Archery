<?php
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">
            Welcome back, {{ auth()->user()->name }}! 👋
        </h1>
        <p class="text-slate-600 text-lg">
            Here's what's happening with your training sessions today
        </p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Total Sessions -->
        <div class="bg-white rounded-2xl shadow-lg shadow-blue-500/10 p-6 border border-slate-200/60 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-green-600 text-sm font-semibold bg-green-50 px-3 py-1 rounded-full">
                    +3 this week
                </span>
            </div>
            <h3 class="text-slate-600 text-sm font-medium mb-1">Total Sessions</h3>
            <p class="text-3xl font-bold text-slate-900" id="totalSessions">Loading...</p>
        </div>

        <!-- Upcoming Sessions -->
        <div class="bg-white rounded-2xl shadow-lg shadow-orange-500/10 p-6 border border-slate-200/60 hover:shadow-xl hover:shadow-orange-500/20 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-orange-600 text-sm font-semibold bg-orange-50 px-3 py-1 rounded-full">
                    This week
                </span>
            </div>
            <h3 class="text-slate-600 text-sm font-medium mb-1">Upcoming Sessions</h3>
            <p class="text-3xl font-bold text-slate-900" id="upcomingSessions">Loading...</p>
        </div>

        <!-- Total Participants -->
        <div class="bg-white rounded-2xl shadow-lg shadow-purple-500/10 p-6 border border-slate-200/60 hover:shadow-xl hover:shadow-purple-500/20 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-purple-600 text-sm font-semibold bg-purple-50 px-3 py-1 rounded-full">
                    Active
                </span>
            </div>
            <h3 class="text-slate-600 text-sm font-medium mb-1">Total Participants</h3>
            <p class="text-3xl font-bold text-slate-900" id="totalParticipants">Loading...</p>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white rounded-2xl shadow-lg shadow-emerald-500/10 p-6 border border-slate-200/60 hover:shadow-xl hover:shadow-emerald-500/20 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-emerald-600 text-sm font-semibold bg-emerald-50 px-3 py-1 rounded-full">
                    +5% ↑
                </span>
            </div>
            <h3 class="text-slate-600 text-sm font-medium mb-1">Attendance Rate</h3>
            <p class="text-3xl font-bold text-slate-900" id="attendanceRate">Loading...</p>
        </div>

    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Upcoming Sessions (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-1">Upcoming Sessions</h2>
                        <p class="text-slate-600 text-sm">Your scheduled training sessions this week</p>
                    </div>
                    <a href="{{ route('coach.sessions.index') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30">
                        View All
                    </a>
                </div>

                <!-- Sessions List -->
                <div class="space-y-4" id="upcomingSessionsList">
                    <!-- Loading State -->
                    <div class="text-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-slate-600 mt-4">Loading sessions...</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Quick Actions (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6">

                <h2 class="text-2xl font-bold text-slate-900 mb-6">Quick Actions</h2>

                <!-- Action Buttons -->
                <div class="space-y-3">

                    <a href="{{ route('coach.sessions.create') }}" class="block w-full px-4 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-blue-500/30 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Create New Session</span>
                        </div>
                    </a>

                    <a href="{{ route('coach.attendance.index') }}" class="block w-full px-4 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-emerald-500/30 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span>Take Attendance</span>
                        </div>
                    </a>

                    <a href="{{ route('coach.sessions.index') }}" class="block w-full px-4 py-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-purple-500/30 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Manage Sessions</span>
                        </div>
                    </a>

                    <a href="{{ route('coach.settings') }}" class="block w-full px-4 py-4 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white rounded-xl font-medium transition-all duration-200 shadow-lg shadow-slate-500/30 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </div>
                    </a>

                </div>

            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch Dashboard Stats
    fetch('{{ route("coach.dashboard") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('totalSessions').textContent = data.stats.totalSessions || '0';
        document.getElementById('upcomingSessions').textContent = data.stats.upcomingSessions || '0';
        document.getElementById('totalParticipants').textContent = data.stats.totalParticipants || '0';
        document.getElementById('attendanceRate').textContent = (data.stats.attendanceRate || '0') + '%';

        // Render upcoming sessions
        renderUpcomingSessions(data.upcomingSessions || []);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('totalSessions').textContent = '0';
        document.getElementById('upcomingSessions').textContent = '0';
        document.getElementById('totalParticipants').textContent = '0';
        document.getElementById('attendanceRate').textContent = '0%';

        document.getElementById('upcomingSessionsList').innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-600 font-medium">No upcoming sessions</p>
                <p class="text-slate-500 text-sm mt-1">Create your first training session</p>
            </div>
        `;
    });
});

function renderUpcomingSessions(sessions) {
    const container = document.getElementById('upcomingSessionsList');

    if (sessions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-600 font-medium">No upcoming sessions</p>
                <p class="text-slate-500 text-sm mt-1">Create your first training session</p>
            </div>
        `;
        return;
    }

    container.innerHTML = sessions.map(session => `
        <div class="border border-slate-200 rounded-xl p-4 hover:shadow-lg hover:border-blue-200 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">${session.title}</h3>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>${session.date} at ${session.time}</span>
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
                </div>
                <span class="px-3 py-1 bg-${session.statusColor}-50 text-${session.statusColor}-600 text-xs font-semibold rounded-full">
                    ${session.status}
                </span>
            </div>
        </div>
    `).join('');
}
</script>
@endsection

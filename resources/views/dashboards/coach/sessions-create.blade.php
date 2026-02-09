<?php
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('coach.sessions.index') }}" class="w-10 h-10 bg-white hover:bg-slate-50 rounded-xl flex items-center justify-center shadow-lg border border-slate-200 transition-all duration-200">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Create New Session</h1>
                <p class="text-slate-600 text-lg mt-1">Schedule a new training session for your athletes</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="createSessionForm" class="max-w-4xl">

        <!-- Session Information Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8 mb-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                Session Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Session Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">
                        Session Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="e.g., Beginner Archery Training">
                </div>

                <!-- Session Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">
                        Session Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Select Type</option>
                        <option value="group">Group Training</option>
                        <option value="individual">Individual Training</option>
                        <option value="competition">Competition Prep</option>
                        <option value="assessment">Skill Assessment</option>
                    </select>
                </div>

                <!-- Level -->
                <div>
                    <label for="level" class="block text-sm font-semibold text-slate-700 mb-2">
                        Skill Level <span class="text-red-500">*</span>
                    </label>
                    <select id="level" name="level" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Select Level</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="expert">Expert</option>
                        <option value="all">All Levels</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="Describe what will be covered in this session..."></textarea>
                </div>

            </div>
        </div>

        <!-- Schedule & Location Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8 mb-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                Schedule & Location
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date" name="date" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Time -->
                <div>
                    <label for="time" class="block text-sm font-semibold text-slate-700 mb-2">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="time" name="time" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-semibold text-slate-700 mb-2">
                        Duration (minutes) <span class="text-red-500">*</span>
                    </label>
                    <select id="duration" name="duration" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Select Duration</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="90">1.5 hours</option>
                        <option value="120">2 hours</option>
                        <option value="150">2.5 hours</option>
                        <option value="180">3 hours</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-semibold text-slate-700 mb-2">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="e.g., Main Training Field">
                </div>

                <!-- Max Participants -->
                <div>
                    <label for="max_participants" class="block text-sm font-semibold text-slate-700 mb-2">
                        Max Participants <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="max_participants" name="max_participants" required min="1" max="100"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="e.g., 20">
                </div>

                <!-- Equipment Required -->
                <div>
                    <label for="equipment" class="block text-sm font-semibold text-slate-700 mb-2">
                        Equipment Required
                    </label>
                    <input type="text" id="equipment" name="equipment"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="e.g., Bow, Arrows, Target">
                </div>

            </div>
        </div>

        <!-- Additional Settings Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8 mb-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                Additional Settings
            </h2>

            <div class="space-y-4">

                <!-- Allow Bookings -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div>
                        <h3 class="font-semibold text-slate-900">Allow Online Bookings</h3>
                        <p class="text-sm text-slate-600 mt-1">Members can book this session online</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="allow_bookings" name="allow_bookings" class="sr-only peer" checked>
                        <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Send Notifications -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div>
                        <h3 class="font-semibold text-slate-900">Send Email Notifications</h3>
                        <p class="text-sm text-slate-600 mt-1">Notify participants about this session</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="send_notifications" name="send_notifications" class="sr-only peer" checked>
                        <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Recurring Session -->
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                    <div>
                        <h3 class="font-semibold text-slate-900">Recurring Session</h3>
                        <p class="text-sm text-slate-600 mt-1">Repeat this session weekly</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="is_recurring" name="is_recurring" class="sr-only peer">
                        <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" id="submitBtn"
                class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Create Session</span>
            </button>
            <a href="{{ route('coach.sessions.index') }}"
                class="px-6 py-4 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-semibold transition-all duration-200">
                Cancel
            </a>
        </div>

    </form>

</div>

<script>
document.getElementById('createSessionForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const originalContent = submitBtn.innerHTML;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Creating...</span>
    `;

    // Collect form data
    const formData = new FormData(this);

    fetch('{{ route("coach.sessions.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Session created successfully!');

            // Redirect to sessions list
            window.location.href = '{{ route("coach.sessions.index") }}';
        } else {
            throw new Error(data.message || 'Failed to create session');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Failed to create session. Please try again.');

        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalContent;
    });
});

// Set minimum date to today
document.getElementById('date').min = new Date().toISOString().split('T')[0];
</script>
@endsection

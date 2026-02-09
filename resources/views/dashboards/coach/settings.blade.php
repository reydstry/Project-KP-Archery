<?php
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Coach Settings</h1>
        <p class="text-slate-600 text-lg">Manage your profile and preferences</p>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-4 sticky top-8">
                <nav class="space-y-1">
                    <button onclick="showTab('profile')" class="tab-btn w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3 bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile Information</span>
                    </button>

                    <button onclick="showTab('availability')" class="tab-btn w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3 text-slate-600 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Availability</span>
                    </button>

                    <button onclick="showTab('notifications')" class="tab-btn w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3 text-slate-600 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifications</span>
                    </button>

                    <button onclick="showTab('security')" class="tab-btn w-full text-left px-4 py-3 rounded-xl font-medium transition-all duration-200 flex items-center gap-3 text-slate-600 hover:bg-slate-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Security</span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">

            <!-- Profile Information Tab -->
            <div id="profileTab" class="tab-content">
                <form id="profileForm" class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Profile Information</h2>

                    <!-- Profile Photo -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-4">Profile Photo</label>
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 text-sm">
                                    Change Photo
                                </button>
                                <p class="text-xs text-slate-500 mt-2">JPG, GIF or PNG. Max size 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                                Phone Number
                            </label>
                            <input type="tel" id="phone" name="phone" placeholder="+62 812-3456-7890"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Specialization -->
                        <div>
                            <label for="specialization" class="block text-sm font-semibold text-slate-700 mb-2">
                                Specialization
                            </label>
                            <select id="specialization" name="specialization"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Select Specialization</option>
                                <option value="recurve">Recurve Bow</option>
                                <option value="compound">Compound Bow</option>
                                <option value="traditional">Traditional Archery</option>
                                <option value="all">All Types</option>
                            </select>
                        </div>

                        <!-- Experience Years -->
                        <div>
                            <label for="experience" class="block text-sm font-semibold text-slate-700 mb-2">
                                Years of Experience
                            </label>
                            <input type="number" id="experience" name="experience" min="0" placeholder="e.g., 5"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Certification -->
                        <div>
                            <label for="certification" class="block text-sm font-semibold text-slate-700 mb-2">
                                Certification
                            </label>
                            <input type="text" id="certification" name="certification" placeholder="e.g., Level 3 Coach"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Bio -->
                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-semibold text-slate-700 mb-2">
                                Bio
                            </label>
                            <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"></textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex items-center justify-end gap-4">
                        <button type="button" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Availability Tab -->
            <div id="availabilityTab" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Availability Schedule</h2>

                    <div class="space-y-4">
                        <!-- Days of Week -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="monday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="monday" class="font-semibold text-slate-900">Monday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="tuesday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="tuesday" class="font-semibold text-slate-900">Tuesday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="wednesday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="wednesday" class="font-semibold text-slate-900">Wednesday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="thursday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="thursday" class="font-semibold text-slate-900">Thursday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="friday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="friday" class="font-semibold text-slate-900">Friday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="saturday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="saturday" class="font-semibold text-slate-900">Saturday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="sunday" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <label for="sunday" class="font-semibold text-slate-900">Sunday</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="08:00">
                                    <span class="text-slate-500">to</span>
                                    <input type="time" class="px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm" value="17:00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">
                            Save Availability
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notificationsTab" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Notification Preferences</h2>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <h3 class="font-semibold text-slate-900">Email Notifications</h3>
                                <p class="text-sm text-slate-600 mt-1">Receive email about new bookings</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <h3 class="font-semibold text-slate-900">Session Reminders</h3>
                                <p class="text-sm text-slate-600 mt-1">Get reminded before sessions start</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <h3 class="font-semibold text-slate-900">Booking Cancellations</h3>
                                <p class="text-sm text-slate-600 mt-1">Notify when bookings are cancelled</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <h3 class="font-semibold text-slate-900">Weekly Reports</h3>
                                <p class="text-sm text-slate-600 mt-1">Receive weekly activity summary</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">
                            Save Preferences
                        </button>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div id="securityTab" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Change Password</h2>

                    <form id="passwordForm">
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Current Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="current_password" name="current_password" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="new_password" name="new_password" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <p class="text-xs text-slate-500 mt-1">Minimum 8 characters</p>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // Remove active state from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-50', 'text-blue-600');
        btn.classList.add('text-slate-600', 'hover:bg-slate-50');
    });

    // Show selected tab
    document.getElementById(tabName + 'Tab').classList.remove('hidden');

    // Add active state to clicked button
    event.currentTarget.classList.add('bg-blue-50', 'text-blue-600');
    event.currentTarget.classList.remove('text-slate-600', 'hover:bg-slate-50');
}

// Handle profile form submit
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("coach.settings") }}', {
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
            alert('Profile updated successfully!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update profile');
    });
});

// Handle password form submit
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    const formData = new FormData(this);

    fetch('{{ route("password.update") }}', {
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
            alert('Password updated successfully!');
            this.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update password');
    });
});
</script>
@endsection

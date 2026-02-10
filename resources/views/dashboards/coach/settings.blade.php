@extends('layouts.coach')

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
                            <input type="tel" id="phone" name="phone" value="{{ $coach?->phone ?? '' }}" placeholder="+62 812-3456-7890"
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
                                <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
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

    fetch('{{ route("coach.settings.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError('Response is not JSON');
        }
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update profile: ' + (error.message || 'Unknown error'));
    });
});

// Handle password form submit
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }

    const formData = new FormData(this);

    fetch('{{ route("coach.settings.password") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError('Response is not JSON');
        }
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Password updated successfully!');
            this.reset();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update password: ' + (error.message || 'Unknown error'));
    });
});
</script>
@endsection

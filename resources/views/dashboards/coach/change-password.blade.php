@extends('layouts.coach')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 p-8">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Change Password</h1>
    </div>

    <!-- Main Content -->
    <div class="w-full">
        <!-- Security Tab -->
        <div id="securityTab" class="tab-content">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-8">
                <form id="passwordForm">
                    <div class="space-y-4">
                        <div class="mb-10">
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

<script>
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

    fetch('{{ route("coach.change-password") }}', {
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

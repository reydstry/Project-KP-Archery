@extends('layouts.coach')

@section('title', 'Change Password')
@section('subtitle', 'Update your account password')

@section('content')
<div class="bg-white px-2 py-2 sm:p-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200/60 p-2 sm:p-4 card-animate" style="animation-delay: 0.1s">
                <form id="passwordForm">
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label for="current_password" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <div>
                            <label for="new_password" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="new_password" name="new_password" required
                                class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <p class="text-xs text-slate-500 mt-1">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                                class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>

                    <div class="mt-3 sm:mt-4 flex items-center justify-end">
                        <button type="submit" class="w-full sm:w-auto px-3 py-1.5 sm:px-5 sm:py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30">
                            Update Password
                        </button>
                    </div>
                </form>
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

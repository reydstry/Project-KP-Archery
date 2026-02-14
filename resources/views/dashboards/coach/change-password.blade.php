@extends('layouts.coach')

@section('title', 'Change Password')
@section('subtitle', 'Update your account password')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 card-animate" style="animation-delay: .1s">
    <form id="passwordForm" class="space-y-4">
        <div>
            <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Current Password <span class="text-[#d12823]">*</span></label>
            <input type="password" id="current_password" name="current_password" required
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b]">
        </div>

        <div>
            <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-1.5">New Password <span class="text-[#d12823]">*</span></label>
            <input type="password" id="new_password" name="new_password" required
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b]">
            <p class="text-xs text-slate-500 mt-1">Minimum 8 karakter</p>
        </div>

        <div>
            <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm New Password <span class="text-[#d12823]">*</span></label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b]">
        </div>

        <div class="pt-1 flex justify-end">
            <button id="passwordSubmitBtn" type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-[#1a307b] hover:bg-[#162a69] text-white rounded-lg text-sm font-semibold transition">
                Update Password
            </button>
        </div>
    </form>
</div>

<script>
// Handle password form submit
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('passwordSubmitBtn');
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;

    if (newPassword !== confirmPassword) {
        window.showToast('Konfirmasi password tidak sama', 'error');
        return;
    }

    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';

    const formData = new FormData(form);
    window.API.post('/coach/change-password', formData)
        .then(() => {
            window.showToast('Password berhasil diperbarui', 'success');
            form.reset();
        })
        .catch((error) => {
            console.error(error);
            window.showToast(error?.message || 'Gagal memperbarui password', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
});
</script>
@endsection

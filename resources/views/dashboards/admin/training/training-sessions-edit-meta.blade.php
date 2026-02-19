@extends('layouts.admin')

@section('title', 'Edit Training Session')
@section('subtitle', 'Update tanggal dan status sesi')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6" x-data="trainingSessionEditMetaPage({{ (int)$id }})" x-init="init()">
    
    <!-- Main Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Edit Training Session</h2>
                        <p class="text-sm text-white/80 mt-0.5">Update tanggal dan status training session</p>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="px-6 py-12">
                <div class="flex items-center justify-center gap-3 text-slate-400">
                    <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-medium">Memuat data session...</span>
                </div>
            </div>

            <!-- Form Content -->
            <div x-show="!loading" class="p-6 sm:p-8 space-y-6">
                
                <!-- Session ID (Read-only) -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        ID Session
                    </label>
                    <input type="text" 
                           value="{{ $id }}" 
                           readonly
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl bg-slate-50 text-slate-600 font-semibold cursor-not-allowed">
                </div>

                <!-- Date Input -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Tanggal Sesi *
                    </label>
                    <input type="date" 
                           x-model="form.date" 
                           required
                           class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition text-slate-700"
                           :class="errors.date ? 'border-red-400 focus:border-red-500 focus:ring-red-500/30' : ''">
                    <p x-show="errors.date" 
                       x-transition
                       class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span x-text="errors.date"></span>
                    </p>
                </div>

                <!-- Status Select -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-2">
                        <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select x-model="form.status" 
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition appearance-none bg-white text-slate-700"
                            :class="errors.status ? 'border-red-400 focus:border-red-500 focus:ring-red-500/30' : ''">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="canceled">Canceled</option>
                    </select>
                    <p x-show="errors.status" 
                       x-transition
                       class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span x-text="errors.status"></span>
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-[#1a307b] rounded-xl p-4">
                    <h3 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                        Management Cepat
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a :href="`/admin/training/slots?session=${sessionId}`" 
                           class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 border-[#1a307b]/20 bg-white hover:bg-[#1a307b]/5 text-sm font-semibold text-[#1a307b] transition shadow-sm hover:shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kelola Slot & Coach
                        </a>
                        <a :href="`/admin/sessions/${sessionId}/attendance`" 
                           class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 border-[#1a307b]/20 bg-white hover:bg-[#1a307b]/5 text-sm font-semibold text-[#1a307b] transition shadow-sm hover:shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                            </svg>
                            Kelola Attendance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-6 sm:px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="{{ route('admin.sessions.index') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button @click="submit()" 
                        :disabled="submitting || loading" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] text-white text-sm font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    <span x-show="!submitting" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Session
                    </span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccessModal" x-cloak @click.self="closeSuccessModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showSuccessModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Berhasil Disimpan!</h3>
                <p class="text-slate-600 mb-6" x-text="successMessage"></p>
                <button @click="closeSuccessModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div x-show="showErrorModal" x-cloak @click.self="closeErrorModal()"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showErrorModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Terjadi Kesalahan!</h3>
                <p class="text-slate-600 mb-6" x-text="errorMessage"></p>
                <button @click="closeErrorModal()" class="w-full px-6 py-3 bg-[#1a307b] hover:bg-[#152866] text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function trainingSessionEditMetaPage(sessionId) {
    return {
        sessionId,
        form: {
            date: new Date().toISOString().slice(0,10),
            status: 'open',
        },
        loading: false,
        submitting: false,
        errors: {},
        showSuccessModal: false,
        showErrorModal: false,
        successMessage: '',
        errorMessage: '',
        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccessModal = true;
        },
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successMessage = '';
            // Redirect after closing success modal
            window.location.href = '{{ route('admin.sessions.index') }}';
        },
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showErrorModal = true;
        },
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        async init() {
            this.loading = true;
            try {
                const data = await window.API.get(`/admin/training-sessions/${this.sessionId}`);
                this.form.date = (data?.date || '').toString().slice(0,10) || this.form.date;
                this.form.status = data?.status || this.form.status;
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal memuat detail session.');
            } finally {
                this.loading = false;
            }
        },
        async submit() {
            if (this.submitting) return;

            this.submitting = true;
            this.errors = {};

            try {
                await window.API.patch(`/admin/training-sessions/${this.sessionId}`, {
                    date: this.form.date,
                    status: this.form.status,
                });
                this.showSuccessMessage('Session berhasil diperbarui.');
            } catch (error) {
                this.errors = this.mapError(error);
                this.showErrorMessage(error?.message || 'Gagal update session.');
            } finally {
                this.submitting = false;
            }
        },
        mapError(error) {
            const message = (error?.message || '').toLowerCase();
            if (message.includes('tanggal') || message.includes('date')) {
                return { date: error.message };
            }
            if (message.includes('status')) {
                return { status: error.message };
            }
            return {};
        }
    }
}
</script>
@endpush

<?php $__env->startSection('title', 'Create Training Session'); ?>
<?php $__env->startSection('subtitle', 'Buat sesi berdasarkan tanggal dan status'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6"
     x-data="trainingSessionCreatePage()">
    
    <!-- Info Banner -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="p-2 bg-blue-500 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-blue-900 mb-1">Catatan Penting</h3>
                <p class="text-sm text-blue-700">Halaman ini untuk membuat metadata sesi (tanggal & status). Setelah membuat session, Anda dapat menambahkan slot dan assign coach di halaman management.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Buat Training Session Baru</h2>
                        <p class="text-sm text-white/80 mt-0.5">Tentukan tanggal dan status awal training session</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6 sm:p-8 space-y-6">
                
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
                        Status Awal
                    </label>
                    <select x-model="form.status" 
                            class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition appearance-none bg-white text-slate-700"
                            :class="errors.status ? 'border-red-400 focus:border-red-500 focus:ring-red-500/30' : ''">
                        <option value="open">Scheduled/Ongoing</option>
                        <option value="closed">Completed</option>
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
                    <p class="mt-2 text-xs text-slate-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pilih "Scheduled/Ongoing" untuk session yang dapat diikuti member
                    </p>
                </div>

                <!-- Info Card -->
                <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
                        </svg>
                        <div class="text-sm text-amber-800">
                            <p class="font-semibold mb-1">Langkah Selanjutnya</p>
                            <p>Setelah session dibuat, Anda akan diarahkan ke halaman list session untuk menambahkan slot waktu dan assign coach.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-6 sm:px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <a href="<?php echo e(route('admin.sessions.index')); ?>" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button @click="submit()" 
                        :disabled="submitting" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-gradient-to-r from-[#1a307b] to-[#2a4a9f] text-white text-sm font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                    <span x-show="!submitting" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Session
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function trainingSessionCreatePage() {
    return {
        form: {
            date: new Date().toISOString().slice(0,10),
            status: 'open',
        },
        errors: {},
        submitting: false,
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
            window.location.href = '<?php echo e(route('admin.sessions.index')); ?>';
        },
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showErrorModal = true;
        },
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        async submit() {
            if (this.submitting) return;

            this.errors = {};
            this.submitting = true;

            try {
                await window.API.post('/admin/training-sessions', {
                    date: this.form.date,
                    status: this.form.status,
                });

                this.showSuccessMessage('Session berhasil dibuat.');
            } catch (error) {
                this.errors = this.mapError(error);
                this.showErrorMessage(error?.message || 'Gagal membuat session.');
            } finally {
                this.submitting = false;
            }
        },
        mapError(error) {
            if (!error?.message) return {};

            if (error.message.toLowerCase().includes('tanggal')) {
                return { date: error.message };
            }

            if (error.message.toLowerCase().includes('status')) {
                return { status: error.message };
            }

            return {};
        }
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/dashboards/admin/training/training-sessions-create.blade.php ENDPATH**/ ?>
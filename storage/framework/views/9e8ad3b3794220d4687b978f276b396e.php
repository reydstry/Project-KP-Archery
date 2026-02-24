<?php $__env->startSection('title', 'Training Session'); ?>
<?php $__env->startSection('subtitle', 'Kelola tanggal sesi dan status tanpa slot/attendance'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4 sm:space-y-6" x-data="trainingSessionsPage()" x-init="init()">
    
    <!-- Filter Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                <select x-model="filters.status" class="w-full px-3 py-2.5 border-2 border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition">
                    <option value="">Semua Status</option>
                    <option value="open">Scheduled/Ongoing</option>
                    <option value="closed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai</label>
                <input type="date" x-model="filters.start_date" class="w-full px-3 py-2.5 border-2 border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Selesai</label>
                <input type="date" x-model="filters.end_date" class="w-full px-3 py-2.5 border-2 border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a307b]/30 focus:border-[#1a307b] transition">
            </div>
            <div class="flex items-end gap-2">
                <button @click="loadSessions()" :disabled="loading" class="flex-1 px-4 py-2.5 rounded-lg bg-[#1a307b] text-white text-sm font-semibold disabled:opacity-60 hover:bg-[#162a69] transition shadow-md hover:shadow-lg">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                        Filter
                    </span>
                </button>
                <button @click="resetFilters()" :disabled="loading" class="px-4 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-semibold text-slate-700 disabled:opacity-60 hover:bg-slate-50 transition">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Add Button -->
    <div class="flex justify-end">
        <a href="<?php echo e(route('admin.sessions.create')); ?>" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-semibold shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Session
        </a>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block">
        <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['headers' => ['Tanggal', 'Status', 'Aksi']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Tanggal', 'Status', 'Aksi'])]); ?>
            <template x-if="loading">
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memuat session...
                        </div>
                    </td>
                </tr>
            </template>
            <template x-if="!loading && sessions.length === 0">
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-slate-400">Tidak ada training session</td>
                </tr>
            </template>
            <template x-for="session in sessions" :key="session.id">
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-800" x-text="session.date"></p>
                        <p class="text-xs text-slate-500" x-text="`Session #${session.id}`"></p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-md border text-xs font-semibold"
                              :class="session.status==='open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (session.status==='closed' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-red-50 text-red-700 border-red-200')"
                              x-text="statusLabel(session.status)"></span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            <a :href="`/admin/sessions/${session.id}/edit`" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit Tanggal/Status</a>
                            <a :href="`/admin/training/slots?session=${session.id}`" class="px-3 py-1.5 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b] hover:bg-[#1a307b]/20">Slot & Coach</a>
                            <a :href="`/admin/sessions/${session.id}/attendance`" class="px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">Attendance</a>
                            <button @click="changeStatus(session, nextStatus(session.status))" :disabled="submittingIds.includes(session.id)" class="px-3 py-1.5 rounded-lg border border-amber-200 bg-amber-50 text-xs font-semibold text-amber-700 disabled:opacity-60 hover:bg-amber-100">Change Status</button>
                            <button @click="deleteSession(session)" :disabled="submittingIds.includes(session.id)" class="px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 disabled:opacity-60 hover:bg-red-100">Delete</button>
                        </div>
                    </td>
                </tr>
            </template>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-3">
        <template x-if="loading">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 text-center">
                <div class="flex items-center justify-center gap-2 text-slate-400">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memuat session...
                </div>
            </div>
        </template>
        <template x-if="!loading && sessions.length === 0">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-slate-400 font-medium">Tidak ada training session</p>
            </div>
        </template>
        <template x-for="session in sessions" :key="session.id">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                <!-- Header -->
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-[#1a307b]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h4 class="font-semibold text-slate-800" x-text="session.date"></h4>
                        </div>
                        <p class="text-xs text-slate-500" x-text="`Session #${session.id}`"></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg border text-xs font-semibold ml-2"
                          :class="session.status==='open' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (session.status==='closed' ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-red-50 text-red-700 border-red-200')"
                          x-text="statusLabel(session.status)"></span>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                    <a :href="`/admin/training/slots?session=${session.id}`" 
                       class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-[#1a307b]/20 bg-[#1a307b]/10 text-xs font-semibold text-[#1a307b]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Slot & Coach
                    </a>
                    <a :href="`/admin/sessions/${session.id}/attendance`" 
                       class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                        </svg>
                        Attendance
                    </a>
                </div>

                <!-- More Actions -->
                <div class="flex flex-col gap-2">
                    <a :href="`/admin/sessions/${session.id}/edit`" 
                       class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 text-center hover:bg-slate-50">
                        Edit Tanggal/Status
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="changeStatus(session, nextStatus(session.status))" 
                                :disabled="submittingIds.includes(session.id)" 
                                class="px-3 py-2 rounded-lg border border-amber-200 bg-amber-50 text-xs font-semibold text-amber-700 disabled:opacity-60">
                            Change Status
                        </button>
                        <button @click="deleteSession(session)" 
                                :disabled="submittingIds.includes(session.id)" 
                                class="px-3 py-2 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 disabled:opacity-60">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Confirmation Delete Modal -->
    <div x-show="showDeleteConfirm" x-cloak @click.self="showDeleteConfirm = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-w-md w-full mx-4 transform"
             x-show="showDeleteConfirm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 mb-4">
                    <svg class="h-10 w-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Hapus Session?</h3>
                <p class="text-slate-600 mb-6" x-text="sessionToDelete ? 'Hapus Session #' + sessionToDelete.id + '?' : 'Hapus session ini?'"></p>
                <div class="flex gap-3">
                    <button @click="showDeleteConfirm = false" class="flex-1 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition-all duration-200">
                        Batal
                    </button>
                    <button @click="confirmDeleteSession()" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-all duration-200 active:scale-95">
                        Hapus
                    </button>
                </div>
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
function trainingSessionsPage() {
    return {
        sessions: [],
        loading: false,
        submittingIds: [],
        showSuccessModal: false,
        showErrorModal: false,
        showDeleteConfirm: false,
        sessionToDelete: null,
        successMessage: '',
        errorMessage: '',
        filters: {
            status: '',
            start_date: '',
            end_date: '',
        },
        async init() {
            await this.loadSessions();
        },
        async loadSessions() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.filters.status) params.set('status', this.filters.status);
                if (this.filters.start_date) params.set('start_date', this.filters.start_date);
                if (this.filters.end_date) params.set('end_date', this.filters.end_date);

                const suffix = params.toString() ? `?${params.toString()}` : '';
                const result = await window.API.get(`/admin/training-sessions${suffix}`);
                this.sessions = Array.isArray(result?.data)
                    ? result.data.map((item) => ({ id: item.id, date: (item.date || '').toString().slice(0,10), status: item.status || 'open' }))
                    : [];
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal memuat training sessions.');
            } finally {
                this.loading = false;
            }
        },
        resetFilters() {
            this.filters = { status: '', start_date: '', end_date: '' };
            this.loadSessions();
        },
        statusLabel(status) {
            if (status === 'open') return 'Scheduled/Ongoing';
            if (status === 'closed') return 'Completed';
            if (status === 'canceled') return 'Cancelled';
            return status;
        },
        nextStatus(status) {
            if (status === 'open') return 'closed';
            if (status === 'closed') return 'canceled';
            return 'open';
        },
        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccessModal = true;
        },
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.successMessage = '';
        },
        showErrorMessage(message) {
            this.errorMessage = message;
            this.showErrorModal = true;
        },
        closeErrorModal() {
            this.showErrorModal = false;
            this.errorMessage = '';
        },
        async changeStatus(session, status) {
            if (!session?.id || !status) return;

            this.submittingIds = [...this.submittingIds, session.id];
            try {
                await window.API.patch(`/admin/training-sessions/${session.id}/status`, { status });
                this.showSuccessMessage('Status session berhasil diubah.');
                await this.loadSessions();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal mengubah status session.');
            } finally {
                this.submittingIds = this.submittingIds.filter((id) => id !== session.id);
            }
        },
        async deleteSession(session) {
            if (!session?.id) return;
            this.sessionToDelete = session;
            this.showDeleteConfirm = true;
        },
        async confirmDeleteSession() {
            if (!this.sessionToDelete?.id) return;
            
            this.showDeleteConfirm = false;
            this.submittingIds = [...this.submittingIds, this.sessionToDelete.id];
            try {
                const response = await window.API.delete(`/admin/training-sessions/${this.sessionToDelete.id}`);
                this.showSuccessMessage(response?.message || 'Session berhasil dihapus.');
                await this.loadSessions();
            } catch (error) {
                this.showErrorMessage(error?.message || 'Gagal menghapus session.');
            } finally {
                this.submittingIds = this.submittingIds.filter((id) => id !== this.sessionToDelete.id);
                this.sessionToDelete = null;
            }
        },
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/dashboards/admin/training/training-sessions.blade.php ENDPATH**/ ?>
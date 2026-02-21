<div x-data="toastManager()" <?php echo $__env->yieldSection(); ?>-toast.window="show($event.detail)" class="fixed top-4 right-4 left-4 sm:left-auto z-100 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition
             class="w-full sm:w-auto sm:min-w-[320px] bg-white rounded-xl shadow-2xl border border-slate-200 p-4 flex items-start gap-3">
            <div class="mt-0.5" :class="{
                'text-green-500': toast.type === 'success',
                'text-red-500': toast.type === 'error',
                'text-blue-500': toast.type === 'info' || toast.type === 'warning'
            }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path x-show="toast.type === 'success'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <path x-show="toast.type === 'error'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    <path x-show="toast.type === 'info' || toast.type === 'warning'" stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800" x-text="toast.message"></p>
            </div>
            <button @click="remove(toast.id)" class="text-slate-400 hover:text-slate-600 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
    function toastManager() {
        return {
            toasts: [],
            nextId: 1,
            show(detail) {
                const id = this.nextId++;
                const toast = {
                    id,
                    message: detail.message || 'Notification',
                    type: detail.type || 'info',
                    visible: true,
                };

                this.toasts.push(toast);
                setTimeout(() => this.remove(id), 3000);
            },
            remove(id) {
                const index = this.toasts.findIndex((toast) => toast.id === id);

                if (index > -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => this.toasts.splice(index, 1), 300);
                }
            },
        };
    }

    window.showToast = (message, type = 'info') => {
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: { message, type },
        }));
    };

    document.addEventListener('DOMContentLoaded', () => {
        <?php if(session('success')): ?>
            window.showToast(<?php echo json_encode(session('success'), 15, 512) ?>, 'success');
        <?php endif; ?>
        <?php if(session('error')): ?>
            window.showToast(<?php echo json_encode(session('error'), 15, 512) ?>, 'error');
        <?php endif; ?>
        <?php if(session('warning')): ?>
            window.showToast(<?php echo json_encode(session('warning'), 15, 512) ?>, 'warning');
        <?php endif; ?>
        <?php if(session('status')): ?>
            window.showToast(<?php echo json_encode(session('status'), 15, 512) ?>, 'success');
        <?php endif; ?>
    });
</script>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/toast-container.blade.php ENDPATH**/ ?>
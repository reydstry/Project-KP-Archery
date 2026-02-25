<?php $__env->startSection('title', 'Broadcast Event'); ?>
<?php $__env->startSection('subtitle', 'Kirim broadcast event ke seluruh member aktif melalui queue'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <?php if(session('success')): ?>
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Validasi gagal:</p>
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.whatsapp.broadcast.store')); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div>
            <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Judul Event</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?php echo e(old('title')); ?>"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                placeholder="Contoh: Latihan Gabungan Mingguan"
                required
            >
        </div>

        <div>
            <label for="event_date" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Event</label>
            <input
                type="date"
                id="event_date"
                name="event_date"
                value="<?php echo e(old('event_date')); ?>"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                required
            >
        </div>

        <div>
            <label for="message" class="block text-sm font-semibold text-slate-700 mb-1">Pesan Broadcast</label>
            <textarea
                id="message"
                name="message"
                rows="6"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a307b]"
                placeholder="Tulis pesan yang akan dikirim ke semua member aktif"
                required
            ><?php echo e(old('message')); ?></textarea>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl brand-btn text-sm font-semibold transition">
                Kirim Broadcast
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/dashboards/admin/whatsapp/broadcast.blade.php ENDPATH**/ ?>
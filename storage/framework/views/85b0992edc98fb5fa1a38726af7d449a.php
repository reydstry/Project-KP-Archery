<?php $__env->startSection('title', 'Log Broadcast'); ?>
<?php $__env->startSection('subtitle', 'Riwayat pengiriman broadcast event WhatsApp'); ?>

<?php
    $statusClasses = [
        'pending' => 'bg-slate-100 text-slate-700',
        'processing' => 'bg-yellow-100 text-yellow-700',
        'completed' => 'bg-emerald-100 text-emerald-700',
        'failed' => 'bg-red-100 text-red-700',
    ];
?>

<?php $__env->startSection('content'); ?>
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
                <tr class="text-left text-slate-600">
                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                    <th class="px-4 py-3 font-semibold">Judul</th>
                    <th class="px-4 py-3 font-semibold">Target</th>
                    <th class="px-4 py-3 font-semibold">Success</th>
                    <th class="px-4 py-3 font-semibold">Failed</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $broadcasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $broadcast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="text-slate-700">
                        <td class="px-4 py-3"><?php echo e($broadcast->event_date?->format('d M Y')); ?></td>
                        <td class="px-4 py-3"><?php echo e($broadcast->title); ?></td>
                        <td class="px-4 py-3"><?php echo e($broadcast->total_target); ?></td>
                        <td class="px-4 py-3 text-emerald-700"><?php echo e($broadcast->total_success); ?></td>
                        <td class="px-4 py-3 text-red-700"><?php echo e($broadcast->total_failed); ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold <?php echo e($statusClasses[$broadcast->status] ?? 'bg-slate-100 text-slate-700'); ?>">
                                <?php echo e(ucfirst($broadcast->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('admin.whatsapp.logs.show', $broadcast)); ?>" class="text-[#1a307b] font-semibold hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                            Belum ada data broadcast.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/dashboards/admin/whatsapp/logs/index.blade.php ENDPATH**/ ?>
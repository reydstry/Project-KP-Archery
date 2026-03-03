<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'headers' => [],
    'responsive' => true,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'headers' => [],
    'responsive' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->class(['bg-white border border-slate-200 rounded-xl overflow-hidden'])); ?>>
    <div class="<?php echo e($responsive ? 'overflow-x-auto' : ''); ?>">
        <table class="min-w-full text-sm">
            <?php if(!empty($headers)): ?>
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-600"><?php echo e($header); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
            <?php endif; ?>
            <tbody class="divide-y divide-slate-100">
                <?php echo e($slot); ?>

            </tbody>
        </table>
    </div>
</div>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/table.blade.php ENDPATH**/ ?>
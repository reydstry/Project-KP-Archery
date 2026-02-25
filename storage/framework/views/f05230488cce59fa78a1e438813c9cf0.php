<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'subtitle' => null,
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
    'title',
    'subtitle' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->class(['bg-white border border-slate-200 rounded-xl p-4 sm:p-5'])); ?>>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-base sm:text-lg font-semibold text-slate-800"><?php echo e($title); ?></h3>
            <?php if($subtitle): ?>
                <p class="text-xs sm:text-sm text-slate-500 mt-1"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <?php if(isset($actions)): ?>
            <div class="flex items-center gap-2"><?php echo e($actions); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/report-header.blade.php ENDPATH**/ ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id', 'image', 'title', 'date', 'category', 'categoryColor' => 'blue', 'excerpt', 'alt' => '']));

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

foreach (array_filter((['id', 'image', 'title', 'date', 'category', 'categoryColor' => 'blue', 'excerpt', 'alt' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
    <div class="relative">
        <img src="<?php echo e($image); ?>" 
             alt="<?php echo e($alt ?: $title); ?>"
             class="w-full h-56 object-cover">
        <div class="absolute top-3 left-3">
            </span>
        </div>
    </div>

    <div class="p-6">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span><?php echo e($date); ?></span>
        </div>

        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 line-clamp-2">
            <?php echo e($title); ?>

        </h3>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 line-clamp-3">
            <?php echo e($excerpt); ?>

        </p>

        <button onclick="viewBerita(<?php echo e($id); ?>)"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
            <?php echo e(__('gallery.read_more')); ?>

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/galeri/news-card.blade.php ENDPATH**/ ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['image', 'title', 'date', 'alt' => '']));

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

foreach (array_filter((['image', 'title', 'date', 'alt' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 bg-white">
    <img src="<?php echo e($image); ?>" 
         alt="<?php echo e($alt ?: $title); ?>"
         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
    
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h3 class="text-white font-semibold text-base sm:text-lg mb-1"><?php echo e($title); ?></h3>
        </div>
    </div>
</div>
<?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/components/galeri/card.blade.php ENDPATH**/ ?>
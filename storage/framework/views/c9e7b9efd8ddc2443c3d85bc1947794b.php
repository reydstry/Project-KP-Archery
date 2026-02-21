<?php $__env->startSection('title', __('contact.page_title') . ' - FocusOneX Archery'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="bg-gradient-to-br from-orange-50 to-white py-20 mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-3"><?php echo e(__('contact.hero_title')); ?></h1>
            <p class="text-gray-600"><?php echo e(__('contact.hero_subtitle')); ?></p>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Info -->
        <div>
            <?php echo $__env->make('components.kontak.contact-info', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Contact Form -->
        <div>
            <?php echo $__env->make('components.kontak.contact-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>

<!-- Location Map -->
<div class="container mx-auto px-4">
    <?php echo $__env->make('components.kontak.location-map', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<!-- Testimonials -->
<?php echo $__env->make('components.kontak.testimonials', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/pages/kontak.blade.php ENDPATH**/ ?>
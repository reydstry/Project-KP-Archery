<?php $__env->startSection('title', __('contact.page_title') . ' - FocusOneX Archery'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('components.kontak.hero-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('components.kontak.location-map', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<!-- Testimonials -->
<?php echo $__env->make('components.kontak.testimonials', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/pages/kontak.blade.php ENDPATH**/ ?>
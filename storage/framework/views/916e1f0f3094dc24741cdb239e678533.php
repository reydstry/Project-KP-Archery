<?php $__env->startSection('title', __('gallery.page_title') . ' - FocusOneX Archery'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components.galeri.hero-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.galeri.news-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
// Tab Switching
function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-white', 'text-white');
        button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-300', 'hover:border-gray-300');
    });
    
    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    
    // Add active state to selected button
    const activeButton = document.getElementById('tab-' + tab);
    activeButton.classList.add('active', 'border-white', 'text-white');
    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-300', 'hover:border-gray-300');
}

// View Berita Function
function viewBerita(id) {
    window.location.href = '/berita/' + id;
}

// Initialize first tab
document.addEventListener('DOMContentLoaded', function() {
    switchTab('latihan');
});
</script>

<style>
.tab-button.active {
    @apply border-white text-white;
}

.tab-button:not(.active) {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/pages/galeri.blade.php ENDPATH**/ ?>
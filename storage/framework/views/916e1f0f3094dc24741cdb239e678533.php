<?php $__env->startSection('title', __('gallery.page_title') . ' - FocusOneX Archery'); ?>

<?php $__env->startPush('styles'); ?>
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

<script>
// Define Alpine components BEFORE Alpine loads
window.galleryTab = function(category) {
    return {
        galleries: [],
        loading: true,
        category: category,
        
        async loadGalleries() {
            this.loading = true;
            try {
                const response = await fetch(`/api/galleries?category=${this.category}`);
                const data = await response.json();
                
                console.log('Gallery API Response for ' + this.category + ':', data);
                
                // Handle both paginated and non-paginated responses
                if (data.data && Array.isArray(data.data)) {
                    this.galleries = data.data;
                } else if (Array.isArray(data)) {
                    this.galleries = data;
                } else {
                    console.warn('Unexpected gallery data format:', data);
                    this.galleries = [];
                }
                
                console.log('Loaded ' + this.galleries.length + ' galleries for ' + this.category);
            } catch (error) {
                console.error('Failed to load galleries:', error);
                this.galleries = [];
            } finally {
                this.loading = false;
            }
        }
    };
};

// Tab Switching
window.switchTab = function(tab) {
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
};

// View Berita Function
window.viewBerita = function(id) {
    window.location.href = '/berita/' + id;
};

// Initialize first tab when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing gallery page...');
    switchTab('latihan');
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components.galeri.hero-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('components.galeri.news-section', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\Project-KP-Archery\resources\views/pages/galeri.blade.php ENDPATH**/ ?>
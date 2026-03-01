@extends('layouts.main')

@section('title', __('gallery.page_title') . ' - FocusOneX Archery')

@push('styles')
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

// View Berita Function
window.viewBerita = function(id) {
    window.location.href = '/berita/' + id;
};
</script>
@endpush

@section('content')
@include('components.galeri.hero-section')
@include('components.galeri.news-section')
@endsection

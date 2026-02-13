<!-- Header -->
<section class="bg-gradient-to-br from-gray-50 to-white py-20 mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ __('gallery.header_title') }}</h1>
    <p class="text-gray-600">{{ __('gallery.header_subtitle') }}</p>
</div>

<!-- Tabs Navigation -->
<div class="mb-8 sm:mb-10">
    <div class="flex flex-wrap justify-center gap-2 sm:gap-4 border-b-2 border-gray-200 px-4">
        <button onclick="switchTab('latihan')" id="tab-latihan" class="tab-button active px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
            {{ __('gallery.tab_training') }}
        </button>
        <button onclick="switchTab('kompetisi')" id="tab-kompetisi" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
            {{ __('gallery.tab_competition') }}
        </button>
        <button onclick="switchTab('event')" id="tab-event" class="tab-button px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
            {{ __('gallery.tab_group_selfie') }}
        </button>
    </div>
</div>

<!-- Package Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ __('program.packages_title') }}</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8 max-w-3xl mx-auto">
            <div class="border-4 border-blue-600 rounded-xl p-8 text-center hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('program.package_1') }}</h3>
                <div class="text-4xl font-bold text-blue-600 mb-2">Rp 200.000</div>
                <p class="text-gray-600">{{ __('program.times_per_month', ['count' => '4']) }}</p>
            </div>
            <div class="border-4 border-green-600 rounded-xl p-8 text-center hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('program.package_2') }}</h3>
                <div class="text-4xl font-bold text-green-600 mb-2">Rp 400.000</div>
                <p class="text-gray-600">{{ __('program.times_per_month', ['count' => '10']) }}</p>
            </div>
        </div>
    </div>
</section>

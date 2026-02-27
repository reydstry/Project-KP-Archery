<div class="relative py-24 sm:py-32 bg-gradient-to-b from-[#1b2659] to-[#0f172a] overflow-hidden">

    <div class="absolute top-10 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Header -->
        <div class="text-center mb-8 sm:mb-10">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                <?php echo e(__('contact.location_title')); ?>

            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                <?php echo e(__('contact.location_address')); ?>

            </p>
        </div>

        <!-- Map -->
        <div class="relative group">
            <div class="absolute inset-0 bg-blue-500/20 rounded-3xl blur-2xl scale-105 pointer-events-none"></div>
            <div class="relative rounded-2xl overflow-hidden border border-white/20 shadow-2xl shadow-black/50">
                <!-- Top bar dekoratif -->
                <div class="bg-white/5 backdrop-blur-md border-b border-white/10 px-5 py-3 flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-400/60"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400/60"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400/60"></div>
                    </div>
                    <div class="flex-1 bg-white/5 border border-white/10 rounded-full px-4 py-1 text-white/40 text-xs">
                        Focus One X Archery Balikpapan
                    </div>
                </div>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8683633305586!2d116.87359797472435!3d-1.2503213987377648!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df14663a7a61fb1%3A0x5213490b71cd632!2sFocus%20One%20X%20Archery%20Balikpapan!5e0!3m2!1sid!2sid!4v1770362445091!5m2!1sid!2sid" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full block">
                </iframe>
            </div>
        </div>

    </div>
</div><?php /**PATH C:\laragon\www\Project\club-panahan\resources\views/components/kontak/location-map.blade.php ENDPATH**/ ?>
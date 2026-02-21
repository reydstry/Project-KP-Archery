<section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-[#1b2659] to-[#0f172a] relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Heading -->
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6 px-4 text-white">
                {{ __('home.cta_title') }}
            </h2>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 px-4 text-white">
                {{ __('home.cta_subtitle') }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 px-4">
                <a href="https://wa.me/6281952598200?text=Halo%2C%20saya%20tertarik%20dengan%20program%20FocusOnex%20Archery" target="_blank" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4
                 bg-[#a72320]/60 backdrop-blur-md border border-white/20  rounded-full shadow-xl shadow-black/20 
                 hover:shadow-2xl hover:shadow-black/70 text-white font-bold hover:scale-105 text-sm sm:text-base transition-all duration-300 overflow-hidden group">
                <!-- SHINE EFFECT -->
                <span class="absolute inset-0 w-full h-full 
                            bg-gradient-to-r from-transparent via-white/30 to-transparent
                            -translate-x-full group-hover:translate-x-full 
                            transition-transform duration-700 ease-in-out skew-x-12">
                </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    {{ __('home.contact_whatsapp') }}
                </a>
                
                <a href="{{ route('program') }}"
                class="relative w-full sm:w-auto px-6 sm:px-8 py-3
                    bg-white/20 backdrop-blur-md border border-white/30 
                    text-white font-bold rounded-full text-sm sm:text-base md:text-lg
                    hover:bg-white/30 hover:scale-105 transition-all duration-300 shadow-xl text-center
                    overflow-hidden group">
                <!-- SHINE EFFECT -->
                <span class="absolute inset-0 w-full h-full 
                            bg-gradient-to-r from-transparent via-white/30 to-transparent
                            -translate-x-full group-hover:translate-x-full 
                            transition-transform duration-700 ease-in-out skew-x-12">
                </span>
                    {{ __('home.view_full_info') }}
                </a>
            </div>
        </div>
    </div>
</section>

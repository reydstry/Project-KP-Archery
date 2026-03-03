<!-- Contact Section -->
<section class="relative py-24 sm:py-32 bg-gradient-to-b from-[#16213a]  to-[#1b2659] overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                {{ __('contact.page_title') }}
            </h2>
            <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto">
                {{ __('contact.page_subtitle') }}
            </p>
          </div>

        <!-- Grid: Form + Info -->
        <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">

            <!-- LEFT: Contact Form -->
            <div class="liquid-glass p-8">
                <h3 class="text-2xl font-bold text-white mb-2">{{ __('contact.send_message_title') }}</h3>
                <div class="w-10 h-0.5 bg-blue-400/60 rounded-full mb-6"></div>

                <form id="contactForm" onsubmit="return false;" class="space-y-5">

                    @php
                        $fields = [
                            ['id' => 'name',  'type' => 'text',  'label' => __('contact.full_name'), 'placeholder' => __('contact.full_name_placeholder'), 'required' => true],
                            ['id' => 'email', 'type' => 'email', 'label' => __('contact.email'),     'placeholder' => __('contact.email_placeholder'),      'required' => true],
                            ['id' => 'phone', 'type' => 'tel',   'label' => __('contact.phone'),     'placeholder' => __('contact.phone_placeholder'),      'required' => true],
                        ];
                    @endphp

                    @foreach($fields as $field)
                    <div>
                        <label for="{{ $field['id'] }}" class="block text-sm font-medium text-white/70 mb-2">
                            {{ $field['label'] }} @if($field['required'])<span class="text-red-400">*</span>@endif
                        </label>
                        <input 
                            type="{{ $field['type'] }}" 
                            id="{{ $field['id'] }}" 
                            name="{{ $field['id'] }}"
                            @if($field['required']) required @endif
                            placeholder="{{ $field['placeholder'] }}"
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/30
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 
                                   transition-all duration-200"
                        >
                    </div>
                    @endforeach

                    <!-- Program -->
                    <div>
                        <label for="program" class="block text-sm font-medium text-white/70 mb-2">
                            {{ __('contact.program_interest') }} <span class="text-red-400">*</span>
                        </label>
                        <select 
                            id="program" 
                            name="program" 
                            required
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 
                                   transition-all duration-200 appearance-none"
                            style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff60' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em;"
                        >
                            <option value=""           class="bg-[#1b2659]">{{ __('contact.select_program') }}</option>
                            <option value="pemula"     class="bg-[#1b2659]">{{ __('contact.program_beginner') }}</option>
                            <option value="menengah"   class="bg-[#1b2659]">{{ __('contact.program_intermediate') }}</option>
                            <option value="advanced"   class="bg-[#1b2659]">{{ __('contact.program_advanced') }}</option>
                            <option value="kompetisi"  class="bg-[#1b2659]">{{ __('contact.program_competition') }}</option>
                            <option value="privat"     class="bg-[#1b2659]">{{ __('contact.program_private') }}</option>
                        </select>
                    </div>

                    <!-- Pesan -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-white/70 mb-2">
                            {{ __('contact.message') }}
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="4"
                            placeholder="{{ __('contact.message_placeholder_alt') }}"
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-white/30
                                   focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 
                                   transition-all duration-200 resize-none"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="button"
                        id="whatsappBtn"
                        class="relative w-full bg-green-500/80 hover:bg-green-500 backdrop-blur-sm border border-white/20
                               text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 
                               shadow-lg shadow-green-500/20 hover:shadow-green-500/40 hover:scale-[1.02]
                               flex items-center justify-center gap-2 overflow-hidden group">
                        <span class="absolute inset-0 w-full h-full 
                                    bg-gradient-to-r from-transparent via-white/20 to-transparent
                                    -translate-x-full group-hover:translate-x-full 
                                    transition-transform duration-700 ease-in-out skew-x-12 pointer-events-none">
                        </span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        {{ __('contact.send_via_whatsapp') }}
                    </button>
                </form>
            </div>

            <!-- RIGHT: Contact Info -->
            <div class="liquid-glass p-8 h-full">
                
                <h3 class="text-2xl font-bold text-white mb-2">{{ __('contact.contact_info') }}</h3>
                <div class="w-10 h-0.5 bg-red-400/60 rounded-full mb-6"></div>

                @php
                    $infos = [
                        ['color' => 'blue', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => __('contact.address'), 'lines' => [__('contact.address_line_1'), __('contact.address_line_2')]],
                        ['color' => 'red',  'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => __('contact.phone'), 'lines' => ['0819-5259-8200']],
                        ['color' => 'blue', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => __('contact.email'), 'lines' => ['focusonexarchery@gmail.com']],
                        ['color' => 'red',  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => __('contact.operating_hours'), 'lines' => [__('contact.monday_friday') . ': 08:00 - 21:00', __('contact.saturday_sunday') . ': 07:00 - 20:00']],
                    ];
                @endphp

                <div class="space-y-4 mb-8">
                    @foreach($infos as $info)
                    <div class="flex items-start gap-4 bg-white/5 border border-white/10 rounded-xl px-4 py-3.5">
                        <div class="w-10 h-10 flex-shrink-0 bg-{{ $info['color'] }}-500/20 border border-white/20 
                                    rounded-xl flex items-center justify-center shadow-lg shadow-{{ $info['color'] }}-500/10">
                            <svg class="w-5 h-5 text-{{ $info['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/50 text-xs font-semibold uppercase tracking-wider mb-1">{{ $info['label'] }}</p>
                            @foreach($info['lines'] as $line)
                            <p class="text-white/80 text-sm">{{ $line }}</p>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Social Media -->
                <div>
                    <p class="text-white/50 text-xs font-semibold uppercase tracking-wider mb-3">
                        {{ app()->getLocale() == 'id' ? 'Ikuti Kami' : 'Follow Us' }}
                    </p>
                    <div class="flex gap-3">
                        @php
                            $socials = [
                                ['href' => '#', 'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                                ['href' => 'https://www.instagram.com/fo1archery?igsh=ZG82eTRnZGgwcTNz', 'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                                ['href' => '#', 'icon' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z'],
                            ];
                        @endphp
                        @foreach($socials as $social)
                        <a href="{{ $social['href'] }}" target="_blank"
                           class="w-10 h-10 bg-white/5 border border-white/20 rounded-xl flex items-center justify-center
                                  text-white/50 hover:text-white hover:bg-white/15 hover:border-white/30
                                  transition-all duration-200 hover:scale-110">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="{{ $social['icon'] }}"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappBtn = document.getElementById('whatsappBtn');
    whatsappBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const programSelect = document.getElementById('program');
        const programValue = programSelect.value;
        const programText = programSelect.options[programSelect.selectedIndex].text;
        const message = document.getElementById('message').value.trim();
        if (!name)         { alert('Mohon isi Nama Lengkap'); document.getElementById('name').focus(); return; }
        if (!email)        { alert('Mohon isi Email'); document.getElementById('email').focus(); return; }
        if (!phone)        { alert('Mohon isi Nomor Telepon'); document.getElementById('phone').focus(); return; }
        if (!programValue) { alert('Mohon pilih Program yang Diminati'); document.getElementById('program').focus(); return; }
        let waMessage = '*Halo, saya ingin gabung dengan FocusOneX Archery*\n\n';
        waMessage += '*Nama:* ' + name + '\n';
        waMessage += '*Email:* ' + email + '\n';
        waMessage += '*No. Telepon:* ' + phone + '\n';
        waMessage += '*Program Diminati:* ' + programText + '\n';
        if (message) waMessage += '\n*Pesan:*\n' + message;
        window.open('https://wa.me/6282155245534?text=' + encodeURIComponent(waMessage), '_blank');
        setTimeout(function() { document.getElementById('contactForm').reset(); }, 1000);
    });
});
</script>
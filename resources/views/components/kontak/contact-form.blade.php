<div class="relative bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-xl shadow-black/30">
    
    <h3 class="text-2xl font-bold text-white mb-2">{{ __('contact.send_message_title') }}</h3>
    <div class="w-10 h-0.5 bg-blue-400/60 rounded-full mb-6"></div>

    <form id="contactForm" onsubmit="return false;" class="space-y-5">

        @php
            $fields = [
                ['id' => 'name',  'type' => 'text',  'label' => __('contact.full_name'),       'placeholder' => __('contact.full_name_placeholder'),  'required' => true],
                ['id' => 'email', 'type' => 'email', 'label' => __('contact.email'),            'placeholder' => __('contact.email_placeholder'),       'required' => true],
                ['id' => 'phone', 'type' => 'tel',   'label' => __('contact.phone'),            'placeholder' => __('contact.phone_placeholder'),       'required' => true],
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
                <option value="" class="bg-[#1b2659]">{{ __('contact.select_program') }}</option>
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
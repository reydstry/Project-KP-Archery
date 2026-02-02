@props(['tab', 'active' => false])

<button onclick="switchTab('{{ $tab }}')" 
        id="tab-{{ $tab }}"
        class="tab-button {{ $active ? 'active' : '' }} px-6 sm:px-8 py-3 sm:py-4 text-sm sm:text-base font-semibold border-b-2 transition-colors">
    {{ $slot }}
</button>

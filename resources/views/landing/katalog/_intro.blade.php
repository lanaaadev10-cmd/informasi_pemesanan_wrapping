{{-- ============================================
    BAGIAN: Intro & Category Filters
    Deskripsi: Category Filter Pills (Mobile friendly)
============================================ --}}
<div class="flex gap-2 overflow-x-auto pb-1 pt-0.5 no-scrollbar sm:flex-wrap -mx-4 px-4 sm:mx-0 sm:px-0">
    <!-- Tombol Semua -->
    <button onclick="filterKatalog('all')"
            class="filter-btn px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-[#ff6b00] text-black font-montserrat font-bold text-xs border border-[#ff6b00] transition-all duration-300 shadow-[0_4px_16px_rgba(255,107,0,0.35)] active:scale-95 shrink-0"
            data-category="all">
        {{ $profil->katalog_filter_all_label ?? 'Semua' }}
    </button>

    <!-- Tombol Kategori Dinamis -->
    @php
        $categories = [
            'wrapping'    => 'Wrapping',
            'striping'    => 'Striping',
            'window-film' => 'Kaca Film',
            'audio'       => 'Audio',
            'lighting'    => 'Lampu Biled',
        ];
    @endphp

    @foreach($categories as $key => $label)
        <button onclick="filterKatalog('{{ $key }}')"
                class="filter-btn px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-white/5 text-gray-400 font-montserrat font-bold text-xs border border-white/10 hover:text-white hover:border-[#ff6b00]/40 transition-all duration-300 active:scale-95 shrink-0 whitespace-nowrap"
                data-category="{{ $key }}">
            {{ $label }}
        </button>
    @endforeach
</div>

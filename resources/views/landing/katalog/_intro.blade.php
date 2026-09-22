{{-- ============================================
    BAGIAN: Intro & Category Filters
    Deskripsi: Judul "Choose Your Finish" + filter category
============================================ --}}
<!-- Category filters Dantie Autowrap -->
<div class="flex flex-wrap gap-2.5">
    <!-- Tombol Semua -->
    <button onclick="filterKatalog('all')"
            class="filter-btn px-5 py-2.5 rounded-full accent-bg text-black font-extrabold text-xs border transition-all duration-300 shadow-md active:scale-95"
            style="border-color: var(--accent-color); box-shadow: 0 0 10px color-mix(in srgb, var(--accent-color) 10%, transparent);"
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
                class="filter-btn px-5 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 active:scale-95"
                data-category="{{ $key }}">
            {{ $label }}
        </button>
    @endforeach
</div>

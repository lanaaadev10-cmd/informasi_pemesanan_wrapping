{{-- ============================================
    BAGIAN: Intro & Category Filters
    Deskripsi: Judul "Choose Your Finish" + filter category
============================================ --}}
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pt-4 z-10 relative border-t border-white/5">
    <div class="max-w-2xl space-y-2">
        <span class="accent-color text-[10px] font-bold uppercase tracking-widest block font-mono">PREMIUM SELECTION</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $heroDesc }}</h2>
        <p class="text-gray-400 text-xs sm:text-sm font-light leading-relaxed">
            {{ $introText }}
        </p>
    </div>

    <!-- Finish category filters matching figma -->
    <div class="flex flex-wrap gap-2.5">
        <button onclick="filterKatalog('all')"
                class="filter-btn px-5 py-2.5 rounded-full accent-bg text-black font-extrabold text-xs border transition-all duration-300 shadow-md active:scale-95"
                style="border-color: var(--accent-color); box-shadow: 0 0 10px color-mix(in srgb, var(--accent-color) 10%, transparent);"
                data-category="all">
            All
        </button>
        @foreach(['Matte', 'Gloss', 'Satin', 'PPF'] as $finishType)
            <button onclick="filterKatalog('{{ strtolower($finishType) }}')"
                    class="filter-btn px-5 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 active:scale-95"
                    data-category="{{ strtolower($finishType) }}">
                {{ $finishType }}
            </button>
        @endforeach
    </div>
</div>

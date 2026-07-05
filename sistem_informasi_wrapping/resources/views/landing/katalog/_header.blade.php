{{-- ============================================
    BAGIAN: Header Katalog
    Deskripsi: Judul "Wrap Catalog" + Search Bar
============================================ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative">
    <div>
        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">PREMIUM FINISHES</span>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1">
            {{ $heroTitle }}
        </h1>
    </div>

    {{-- Dynamic Search input --}}
    <div class="relative max-w-xs w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500">
            <i class="ph ph-magnifying-glass text-sm"></i>
        </span>
        <input type="text" id="catalog-search" oninput="searchCatalog()"
               placeholder="Search catalog..."
               class="w-full bg-[#121212]/80 border border-white/5 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none transition-all shadow-inner" style="focus:border-color: var(--accent-color);">
    </div>
</div>

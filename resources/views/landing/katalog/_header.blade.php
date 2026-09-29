{{-- ============================================
    BAGIAN: Header Katalog
    Deskripsi: Judul "Katalog Layanan" + Search Bar
============================================ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-6 z-10 relative">
    <div>
        <div class="flex items-center gap-1.5 mb-0.5 sm:mb-1">
            <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00]"></span>
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00]">LAYANAN UNGGULAN</span>
        </div>
        <h1 class="text-xl sm:text-3xl md:text-4xl font-audiowide font-bold text-white tracking-wide">
            {{ $heroTitle }}
        </h1>
    </div>

    {{-- Dynamic Search input --}}
    <div class="relative w-full sm:max-w-xs">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="ph-bold ph-magnifying-glass text-sm"></i>
        </span>
        <input type="text" 
               id="catalog-search" 
               oninput="searchCatalog()"
               placeholder="Search catalog..."
               class="w-full bg-[#141414]/90 border border-white/10 rounded-xl sm:rounded-2xl pl-10 pr-4 py-2.5 sm:py-3 text-xs font-montserrat text-white placeholder-gray-500 focus:outline-none focus:border-[#ff6b00] transition-all shadow-inner">
    </div>
</div>
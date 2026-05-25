{{-- ============================================
    BAGIAN: Category Filter Pills
    Deskripsi: Tombol filter kategori galeri
============================================ --}}
<section class="mb-12 px-2" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-3 pb-3 md:justify-center no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
        <button onclick="filterGaleri('all')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full text-black font-extrabold text-xs border transition-all duration-300 shadow-lg flex items-center gap-2 active:scale-95" style="background-color:var(--accent-color);border-color:var(--accent-color);box-shadow:0 0 15px color-mix(in srgb,var(--accent-color)10%,transparent)" data-category="all">
            All Works
        </button>
        <button onclick="filterGaleri('sports-cars')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="sports-cars">
            Sports Cars
        </button>
        <button onclick="filterGaleri('luxury-sedans')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="luxury-sedans">
            Luxury Sedans
        </button>
        <button onclick="filterGaleri('suvs-trucks')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="suvs-trucks">
            SUVs & Trucks
        </button>
        <button onclick="filterGaleri('satin-finish')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="satin-finish">
            Satin Finish
        </button>
        <button onclick="filterGaleri('matte-black')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/30 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="matte-black">
            Matte Black
        </button>
    </div>
</section>

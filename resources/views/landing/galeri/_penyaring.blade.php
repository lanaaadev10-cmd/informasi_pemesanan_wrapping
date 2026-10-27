
{{-- ============================================
    BAGIAN: Filter Kategori Galeri
    Deskripsi: Kancing filter kategori galeri
============================================ --}}
<section class="mb-10 px-2" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-2 pb-2 md:justify-center no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
        <button onclick="filterGaleri('all')" 
                class="filter-btn shrink-0 px-5 py-2 rounded-full bg-[#ea580c] text-white font-montserrat font-medium text-xs border border-[#ea580c] transition-all duration-300 shadow-sm flex items-center gap-1.5 cursor-pointer active:scale-95" 
                data-category="all">
            <span>{{ $galeriFilterAll }}</span>
        </button>
        @if($galeriFilterCategories && count($galeriFilterCategories) > 0)
            @foreach($galeriFilterCategories as $cat)
                <button onclick="filterGaleri('{{ $cat['slug'] }}')" 
                        class="filter-btn shrink-0 px-5 py-2 rounded-full bg-slate-100 text-slate-600 font-montserrat font-medium text-xs border border-slate-200/80 hover:bg-slate-200 hover:text-slate-900 transition-all duration-300 flex items-center gap-1.5 cursor-pointer active:scale-95" 
                        data-category="{{ $cat['slug'] }}">
                    <span>{{ $cat['label'] }}</span>
                </button>
            @endforeach
        @endif
    </div>
</section>


<section class="mb-12 px-2" data-aos="fade-up" data-aos-duration="1000">
    <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-3 pb-3 md:justify-center no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
        <button onclick="filterGaleri('all')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-[#FF6B00] text-black font-extrabold text-xs border border-[#FF6B00] transition-all duration-300 shadow-lg shadow-[#FF6B00]/20 flex items-center gap-2 active:scale-95" data-category="all">
            {{ $galeriFilterAll }}
        </button>
        @if($galeriFilterCategories && count($galeriFilterCategories) > 0)
            @foreach($galeriFilterCategories as $cat)
                <button onclick="filterGaleri('{{ $cat['slug'] }}')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#FF6B00]/40 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="{{ $cat['slug'] }}">
                    {{ $cat['label'] }}
                </button>
            @endforeach
        @endif
    </div>
</section>

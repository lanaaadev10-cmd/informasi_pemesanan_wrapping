<section class="mb-10 px-0" data-aos="fade-up" data-aos-duration="800">
    <div class="flex gap-2 overflow-x-auto snap-x snap-mandatory scrollbar-none pb-0.5">
        <button onclick="filterGaleri('all')"
                class="snap-start inline-flex items-center gap-1.5 px-[18px] py-2 rounded-full text-[13px] font-semibold tracking-[0.01em] border border-white/[0.08] bg-transparent text-white/45 whitespace-nowrap cursor-pointer transition-all duration-200 hover:text-white/85 hover:border-white/20 data-[active=true]:bg-[rgba(242,153,74,0.12)] data-[active=true]:border-[rgba(242,153,74,0.4)] data-[active=true]:text-[#f2994a]"
                data-category="all">
            <span class="w-[5px] h-[5px] rounded-full bg-current opacity-0 data-[active=true]:opacity-100 transition-opacity duration-200"></span>
            {{ $galeriFilterAll }}
        </button>

        @if($galeriFilterCategories && count($galeriFilterCategories) > 0)
            @foreach($galeriFilterCategories as $cat)
                <button onclick="filterGaleri('{{ $cat['slug'] }}')"
                        class="snap-start inline-flex items-center gap-1.5 px-[18px] py-2 rounded-full text-[13px] font-semibold tracking-[0.01em] border border-white/[0.08] bg-transparent text-white/45 whitespace-nowrap cursor-pointer transition-all duration-200 hover:text-white/85 hover:border-white/20 data-[active=true]:bg-[rgba(242,153,74,0.12)] data-[active=true]:border-[rgba(242,153,74,0.4)] data-[active=true]:text-[#f2994a]"
                        data-category="{{ $cat['slug'] }}">
                    <span class="w-[5px] h-[5px] rounded-full bg-current opacity-0 data-[active=true]:opacity-100 transition-opacity duration-200"></span>
                    {{ $cat['label'] }}
                </button>
            @endforeach
        @endif
    </div>
</section>

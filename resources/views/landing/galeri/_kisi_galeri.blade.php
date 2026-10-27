{{-- ============================================
    BAGIAN: Gallery Grid
    Deskripsi: Grid masonry galeri karya wrapping
============================================ --}}
<section class="mb-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 sm:gap-8" id="galeri-grid">
        @php
            $items = $galeris ?? collect();
        @endphp
        @forelse($items as $item)
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-slate-200/90 bg-slate-100 hover:border-slate-400/80 hover:shadow-xl transition-all duration-500 aspect-[4/3] sm:aspect-square"
                 data-category="all {{ $item->kategori }}" data-aos="fade-up" data-aos-duration="1000">
                <img src="{{ \App\Helpers\StaticContent::fotoUrl($item->foto) }}"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out"
                     alt="{{ $item->judul }}"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent z-10 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 inset-x-0 p-5 sm:p-6 z-20 space-y-1.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider">
                        {{ ucfirst($item->kategori) }}
                    </span>
                    <h3 class="text-white text-base sm:text-lg font-bold font-montserrat group-hover:text-[#ea580c] transition-colors leading-snug">
                        {{ $item->judul }}
                    </h3>
                    <p class="text-slate-300 text-xs font-normal leading-relaxed line-clamp-2">
                        {{ $item->deskripsi }}
                    </p>
                </div>
            </div>
        @empty
            <div class="md:col-span-12 py-16 text-center border border-dashed border-slate-200 rounded-3xl bg-slate-50">
                <div class="w-14 h-14 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center text-[#ea580c] mx-auto mb-3 text-xl">
                    <i class="ph-bold ph-image"></i>
                </div>
                <h4 class="text-base font-bold font-montserrat text-slate-900 mb-1">{{ \App\Helpers\StaticContent::GALERI_EMPTY_STATE }}</h4>
                <p class="text-xs text-slate-500 font-normal">Silakan periksa kembali nanti.</p>
            </div>
        @endforelse
    </div>
</section>

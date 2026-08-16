{{-- ============================================
    BAGIAN: Gallery Grid
    Deskripsi: Grid masonry galeri karya wrapping
============================================ --}}
<section class="mb-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8" id="galeri-grid">
        @php
            $items = $galeris ?? collect();
        @endphp
        @forelse($items as $item)
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square"
                 data-category="all {{ $item->kategori }}" data-aos="fade-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="{{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                     alt="{{ $item->judul }}">
                <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">
                        {{ ucfirst($item->kategori) }}
                    </span>
                    <h3 class="text-white text-lg font-bold group-hover:text-[#f2994a] transition-colors leading-tight">{{ $item->judul }}</h3>
                    <p class="text-gray-400 text-xs font-light leading-relaxed line-clamp-2">{{ $item->deskripsi }}</p>
                </div>
            </div>
        @empty
            <div class="md:col-span-12 py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40">
                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
                    <i class="ph-bold ph-image text-2xl text-[#f2994a]"></i>
                </div>
                <h4 class="text-base font-bold text-white mb-1">{{ \App\Helpers\StaticContent::GALERI_EMPTY_STATE }}</h4>
                <p class="text-xs text-gray-500 font-light">Silakan periksa kembali nanti.</p>
            </div>
        @endforelse
    </div>
</section>

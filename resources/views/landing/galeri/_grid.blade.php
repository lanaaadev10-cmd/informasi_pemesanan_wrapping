{{-- ============================================
    BAGIAN: Gallery Grid
    Deskripsi: Grid masonry galeri karya wrapping
============================================ --}}
<section class="mb-16">
    @if($galeri && $galeri->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8" id="galeri-grid">
            @foreach($galeri as $userItem)
                <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square"
                     data-category="all {{ strtolower($userItem->kategori ?? 'sports-cars') }}" data-aos="fade-up" data-aos-duration="1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                    <img src="{{ resolveImageUrl($userItem->foto, '') }}"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                         alt="{{ $userItem->judul }}">
                    <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                        <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">{{ $userItem->kategori ?? 'Portfolio' }}</span>
                        <h3 class="text-white text-lg font-bold group-hover:text-[#f2994a] transition-colors leading-tight">{{ $userItem->judul }}</h3>
                        <p class="text-gray-400 text-xs font-light leading-relaxed line-clamp-2">{{ $userItem->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/5 mb-6 border border-white/10">
                <svg class="w-10 h-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-white/70 text-lg font-semibold mb-2">Belum Ada Galeri</h3>
            <p class="text-gray-500 text-sm max-w-md mx-auto">{{ $profil->galeri_empty_state_title ?? 'Admin belum menambahkan karya galeri.' }}</p>
        </div>
    @endif
</section>

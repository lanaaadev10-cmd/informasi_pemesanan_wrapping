{{-- ============================================
    BAGIAN: Hero / Header Galeri
    Deskripsi: Judul dan deskripsi halaman galeri
============================================ --}}
<section class="relative w-full rounded-[32px] overflow-hidden mb-12 px-2 py-16 sm:py-20 flex items-center justify-center" data-aos="fade-down" data-aos-duration="1000">
    @if($profil?->galeri_hero_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/' . $profil->galeri_hero_image) }}"
                 class="w-full h-full object-cover object-center"
                 alt="Background Galeri">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/40 to-[#0a0a0a]"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
    @endif

    <div class="relative z-10 max-w-3xl mx-auto text-center">
        {{-- Premium Tag --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6"
             style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: var(--accent-color);"></span>
            <span class="text-xs font-bold tracking-wider uppercase font-mono" style="color: var(--accent-color);">{{ $galeriTitle }}</span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">
            {{ $galeriTitle }}
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl mx-auto font-light">
            {{ $galeriDesc }}
        </p>
    </div>
</section>

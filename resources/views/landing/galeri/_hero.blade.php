{{-- ============================================
    BAGIAN: Hero / Header Galeri
    Deskripsi: Judul dan deskripsi halaman galeri
============================================ --}}
<section class="relative w-full rounded-[32px] overflow-hidden mb-8 px-2 py-10 sm:py-14 flex items-center justify-center" data-aos="fade-down" data-aos-duration="1000">
    @if($galeriHeroImage)
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/' . $galeriHeroImage) }}"
                 class="w-full h-full object-cover object-center"
                 alt="Background Galeri">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/40 to-[#0a0a0a]"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
    @endif

    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">
            {{ $galeriTitle }}
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl mx-auto font-light">
            {{ $galeriDesc }}
        </p>
    </div>
</section>

{{-- ============================================
    BAGIAN: Hero / Header Galeri
    Deskripsi: Judul dan deskripsi halaman galeri
============================================ --}}
<section class="max-w-3xl mx-auto text-center mb-12 px-2 pt-8" data-aos="fade-down" data-aos-duration="1000">
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
</section>

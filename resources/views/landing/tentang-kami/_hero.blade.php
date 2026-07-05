{{-- ============================================
    BAGIAN: Hero Tentang Kami
    Deskripsi: Banner sinematik dengan judul dan deskripsi utama
============================================ --}}
<div class="relative w-full h-[50vh] sm:h-[60vh] md:h-[70vh] flex items-center justify-center overflow-hidden rounded-[32px] sm:rounded-[48px] {{ auth()->check() ? 'mt-4' : '-mt-24 sm:-mt-32' }}">
    <!-- Background Image with studio lights -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover object-center" alt="Premium Wrapping Car">
        <!-- Dark Studio Overlays -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/40 to-[#0a0a0a]"></div>
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 text-center max-w-4xl mx-auto px-6 space-y-6" data-aos="fade-up">
        <span class="text-xs uppercase font-extrabold tracking-[0.3em] text-[#f2994a] bg-[#f2994a]/10 px-4 py-2 rounded-full border border-[#f2994a]/20">
            Tentang Kami
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
            {!! nl2br(e($heroTitle)) !!}
        </h1>
        <p class="text-[#f2994a] text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
            {{ $heroDesc }}
        </p>
    </div>
</div>

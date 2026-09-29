{{-- ============================================
    BAGIAN: Hero Tentang Kami
    Deskripsi: Banner sinematik dengan judul dan deskripsi utama
============================================ --}}
<div class="relative w-full h-[50vh] sm:h-[60vh] md:h-[70vh] flex items-center justify-center overflow-hidden rounded-[32px] sm:rounded-[48px] {{ auth()->check() ? 'mt-4' : '-mt-24 sm:-mt-32' }}">
    <!-- Studio Backdrop with subtle glow -->
    <div class="absolute inset-0 z-0 bg-gradient-to-b from-[#141416] via-[#0d0d0f] to-[#0a0a0a]">
        <div class="absolute top-0 right-1/4 w-[400px] h-[400px] bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-[350px] h-[350px] bg-[#e28a44]/5 rounded-full blur-[100px] pointer-events-none"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 text-center max-w-4xl mx-auto px-6 space-y-6" data-aos="fade-up">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
            {!! nl2br(e($heroTitle)) !!}
        </h1>
        <p class="text-[#f2994a] text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
            {{ $heroDesc }}
        </p>
    </div>
</div>

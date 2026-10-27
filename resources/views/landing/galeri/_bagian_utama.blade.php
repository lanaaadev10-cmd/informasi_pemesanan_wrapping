{{-- ============================================
    BAGIAN: Hero / Jumbotron Galeri (Standardized to Profil Perusahaan Reference)
    Deskripsi: Header sinematik gelap dengan lengkungan modern & kontras tinggi
============================================ --}}
<div class="relative w-full overflow-hidden rounded-b-[40px] sm:rounded-b-[56px] bg-[#0d0f14] text-white">
    <!-- Subtle Ambient Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[260px] bg-[#ea580c]/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 max-w-4xl mx-auto px-6 pt-28 sm:pt-36 pb-16 sm:pb-20 text-center space-y-4" data-aos="fade-up">
        <h1 class="text-3xl sm:text-5xl font-black text-white font-montserrat tracking-tight leading-tight">
            {{ $galeriTitle }}
        </h1>

        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-normal">
            {{ $galeriDesc }}
        </p>
    </div>
</div>

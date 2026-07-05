{{-- ============================================
    BAGIAN: Hero Section
    Deskripsi: Banner utama halaman beranda
============================================ --}}
<section class="relative min-h-screen pt-32 pb-20 px-6 sm:px-10 lg:px-16 flex items-center overflow-hidden bg-cover bg-center lg:bg-[right_center] bg-no-repeat" style="background-image: url('{{ asset('images/hero_car.png') }}');">
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/85 to-black lg:bg-gradient-to-r lg:from-[#0a0a0a] lg:via-[#0a0a0a]/75 lg:to-transparent z-0"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-10"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#e28a44]/5 rounded-full blur-[100px] pointer-events-none z-10"></div>
    <div class="max-w-7xl mx-auto w-full relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-8 space-y-8" data-aos="fade-right" data-aos-duration="1200">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-[#f2994a]/10 border border-[#f2994a]/20 px-4 py-2 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse"></span>
                    <span class="text-xs font-bold text-[#f2994a] tracking-wider uppercase">Professional Car Wrapping Indonesia</span>
                </div>
                <!-- Heading -->
                <div class="space-y-4">
                    <h1 class="text-4xl sm:text-5xl lg:text-6.5xl font-extrabold text-white leading-[1.1] tracking-tight">
                        Elevasi Estetika <br>
                        <span class="text-gradient">Aset Mewah Anda.</span>
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base md:text-lg leading-relaxed max-w-xl">
                        Layanan premium yang melindungi dan memperindah mobil kesayangan Anda. Hubungi kami untuk penawaran terbaik.
                    </p>
                </div>
                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-2">
                    <a href="https://wa.me/628123456789" class="btn-premium px-8 py-4 rounded-xl font-bold text-xs sm:text-sm uppercase tracking-widest text-black flex items-center gap-3 transition-all hover:scale-105 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                        Pesan Sekarang <i class="ph-bold ph-arrow-right text-base"></i>
                    </a>
                    <a href="#mahakarya" class="px-8 py-4 rounded-xl border border-white/20 text-white font-bold text-xs sm:text-sm uppercase tracking-widest bg-white/5 hover:bg-white/10 hover:border-white/40 transition-all">
                        Lihat Portofolio
                    </a>
                </div>
                <!-- Micro Stats -->
                <div class="flex items-center gap-12 pt-8 border-t border-white/5 max-w-md">
                    <div>
                        <h3 class="text-3xl font-extrabold text-white">500+</h3>
                        <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">Supercars Wrapped</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#f2994a]">5 Tahun</h3>
                        <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">Garansi Material</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

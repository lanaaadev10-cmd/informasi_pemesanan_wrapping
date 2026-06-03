{{-- ============================================
    BAGIAN: Keunggulan Section
    Deskripsi: 4 card keunggulan layanan wrapping
============================================ --}}
<section class="py-24 bg-[#080808] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="tentang">
    <div class="absolute top-1/2 left-0 -translate-y-1/2 w-[350px] h-[350px] bg-[#e28a44]/5 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block mb-3">{{ $profil->home_section_keunggulan_badge ?? 'Keunggulan Layanan' }}</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                {!! $profil->home_section_keunggulan_title ?? 'Mengapa Memilih <span class="relative inline-block pb-2">Wapping<span class="absolute bottom-0 left-0 w-full h-[3px] bg-[#f2994a] rounded-full"></span></span>?' !!}
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <!-- Card 1: Wide -->
            <div class="lg:col-span-7 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up">
                <div class="space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-xl sm:text-2xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k1t }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xl">{!! $k1d !!}</p>
                    </div>
                </div>
                <div class="pt-8">
                    <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group-hover:translate-x-1">
                        {{ $profil->cta_selengkapnya ?? 'Selengkapnya' }} <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="lg:col-span-5 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                <div class="space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                        <i class="ph-bold ph-seal-check"></i>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k2t }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $k2d }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="lg:col-span-5 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                <div class="space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k3t }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $k3d }}</p>
                    </div>
                </div>
            </div>

            <!-- Card 4: Orange Emphasized -->
            <div class="lg:col-span-7 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8 shadow-lg shadow-[#f2994a]/10 hover:shadow-[#f2994a]/20 hover:scale-[1.01] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                <div class="space-y-6 md:max-w-md">
                    <div class="space-y-3">
                        <h3 class="text-2xl font-black text-black leading-tight">{{ $k4t }}</h3>
                        <p class="text-black/80 text-sm leading-relaxed">{{ $k4d }}</p>
                    </div>
                    <div class="pt-2">
                        <a href="https://wa.me/{{ $waNumber }}" class="inline-block bg-black text-[#f2994a] hover:bg-black/90 hover:text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                            {{ $profil->cta_cek_syarat ?? 'Cek Syarat &amp; Ketentuan' }}
                        </a>
                    </div>
                </div>
                <div class="flex-shrink-0 bg-black/5 rounded-3xl p-6 border border-black/10 flex items-center justify-center self-center">
                    <i class="ph-bold ph-award text-6xl text-black"></i>
                </div>
            </div>
        </div>
    </div>
</section>

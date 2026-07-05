{{-- ============================================
    BAGIAN: Nilai yang Kami Junjung
    Deskripsi: Grid berisi 3 nilai utama (Presisi, Integritas, Eksklusivitas)
============================================ --}}
@if($showValues)
    <div class="space-y-12 z-10 relative" data-aos="fade-up">
        <div class="text-center space-y-4 max-w-2xl mx-auto">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                Nilai yang Kami Junjung
            </h2>
            <div class="w-16 h-1 bg-[#f2994a] mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Value 1: Presisi -->
            <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                <div class="relative z-10 space-y-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                        <i class="ph-bold ph-target text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-xl">Presisi</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Setiap sudut, lekukan, dan detail kendaraan ditangani dengan tingkat presisi ekstrem oleh teknisi ahli berlisensi resmi.
                    </p>
                </div>
            </div>

            <!-- Value 2: Integritas -->
            <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                <div class="relative z-10 space-y-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                        <i class="ph-bold ph-shield-check text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-xl">Integritas</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Kami berkomitmen terhadap kejujuran dengan selalu menggunakan produk material premium yang terjamin keasliannya dan bergaransi resmi.
                    </p>
                </div>
            </div>

            <!-- Value 3: Eksklusivitas -->
            <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                <div class="relative z-10 space-y-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                        <i class="ph-bold ph-crown text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-white text-xl">Eksklusivitas</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Setiap proyek adalah maha karya unik. Kami memberikan sentuhan personal demi menciptakan tampilan mewah dan prestisius bagi Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

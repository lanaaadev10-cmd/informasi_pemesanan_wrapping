{{-- ============================================
    BAGIAN: Nilai yang Kami Junjung (Clean & Minimalist)
    Deskripsi: Grid 3 nilai utama (Presisi, Integritas, Eksklusivitas)
============================================ --}}
@if($showValues)
    <div class="space-y-10 z-10 relative" data-aos="fade-up">
        <!-- Dynamic Split Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200/80 pb-6">
            <div class="space-y-1.5">
                <span class="text-xs sm:text-sm font-black uppercase tracking-widest text-[#ea580c] block">
                    Prinsip Kerja
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-montserrat">
                    {{ \App\Helpers\StaticContent::VALUES_TITLE }}
                </h2>
            </div>
            <p class="text-slate-500 text-xs sm:text-sm max-w-lg leading-relaxed font-normal">
                Tiga pilar fundamental yang menjamin setiap kendaraan ditangani dengan integritas tinggi dan presisi detail terbaik.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <!-- Value 1: Presisi -->
            <div class="bg-white border border-slate-200/80 p-8 rounded-3xl hover:border-orange-300 hover:shadow-md transition-all duration-300 shadow-sm">
                <div class="space-y-3.5">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 text-[#ea580c]">
                        <i class="ph-bold ph-target text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-xl font-montserrat">Presisi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-normal">
                        Setiap sudut, lekukan, dan detail kendaraan ditangani dengan tingkat presisi ekstrem oleh teknisi ahli berlisensi resmi.
                    </p>
                </div>
            </div>

            <!-- Value 2: Integritas -->
            <div class="bg-white border border-slate-200/80 p-8 rounded-3xl hover:border-orange-300 hover:shadow-md transition-all duration-300 shadow-sm">
                <div class="space-y-3.5">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 text-[#ea580c]">
                        <i class="ph-bold ph-shield-check text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-xl font-montserrat">Integritas</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-normal">
                        Kami berkomitmen terhadap kejujuran dengan selalu menggunakan produk material premium yang terjamin keasliannya dan bergaransi resmi.
                    </p>
                </div>
            </div>

            <!-- Value 3: Eksklusivitas -->
            <div class="bg-white border border-slate-200/80 p-8 rounded-3xl hover:border-orange-300 hover:shadow-md transition-all duration-300 shadow-sm">
                <div class="space-y-3.5">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 text-[#ea580c]">
                        <i class="ph-bold ph-crown text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-xl font-montserrat">Eksklusivitas</h3>
                    <p class="text-slate-600 text-sm leading-relaxed font-normal">
                        Setiap proyek adalah maha karya unik. Kami memberikan sentuhan personal demi menciptakan tampilan mewah dan prestisius bagi Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

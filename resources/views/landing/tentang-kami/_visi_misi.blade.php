{{-- ============================================
    BAGIAN: Visi & Misi Perusahaan (Clean & Minimalist)
    Deskripsi: Kolom visi dan misi dengan desain clean, rapi, dan lapang
============================================ --}}
<div class="space-y-8 z-10 relative" data-aos="fade-up">
    <!-- Dynamic Split Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200/80 pb-6">
        <div class="space-y-1.5">
            <span class="text-xs sm:text-sm font-black uppercase tracking-widest text-[#ea580c] block">
                Arah &amp; Komitmen
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-montserrat tracking-tight">
                Visi &amp; Misi Studio
            </h2>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm max-w-lg leading-relaxed font-normal">
            Fondasi filosofi kami dalam memberikan hasil karya pelapisan kendaraan terbaik di kelasnya.
        </p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- VISI -->
        <div class="bg-white border border-slate-200/80 p-8 sm:p-10 rounded-3xl hover:border-orange-300 hover:shadow-md transition-all duration-300 group shadow-sm">
            <div class="space-y-4">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 text-[#ea580c] group-hover:scale-105 transition-transform">
                    <i class="ph-bold ph-eye text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold font-montserrat text-slate-900 tracking-tight">
                    {{ \App\Helpers\StaticContent::VISI_TITLE }}
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-normal">
                    {{ \App\Helpers\StaticContent::VISI }}
                </p>
            </div>
        </div>

        <!-- MISI -->
        <div class="bg-white border border-slate-200/80 p-8 sm:p-10 rounded-3xl hover:border-orange-300 hover:shadow-md transition-all duration-300 group shadow-sm">
            <div class="space-y-4">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 text-[#ea580c] group-hover:scale-105 transition-transform">
                    <i class="ph-bold ph-target text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold font-montserrat text-slate-900 tracking-tight">
                    {{ \App\Helpers\StaticContent::MISI_TITLE }}
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-normal">
                    {{ \App\Helpers\StaticContent::MISI }}
                </p>
            </div>
        </div>
    </div>
</div>


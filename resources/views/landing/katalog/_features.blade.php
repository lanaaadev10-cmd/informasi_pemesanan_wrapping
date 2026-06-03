{{-- ============================================
    BAGIAN: Bottom Features Bar
    Deskripsi: Grid berisi 4 keunggulan utama layanan premium
============================================ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-12 border-t border-white/5 mt-16 z-10 relative">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
            <i class="ph ph-seal-check text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_1_title ?? 'Premium Films' }}</h5>
            <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">{{ $profil->katalog_feature_1_desc ?? 'We only use top-tier 3M, Avery, and Inozetek materials.' }}</p>
        </div>
    </div>
    
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
            <i class="ph ph-wrench text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_2_title ?? 'Expert Installers' }}</h5>
            <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">{{ $profil->katalog_feature_2_desc ?? 'Certified technicians with 10+ years of collective experience.' }}</p>
        </div>
    </div>

    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
            <i class="ph ph-shield-check text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_3_title ?? 'Warranty' }}</h5>
            <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">{{ $profil->katalog_feature_3_desc ?? 'Full warranty on both material and labor defects.' }}</p>
        </div>
    </div>

    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
            <i class="ph ph-leaf text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_4_title ?? 'Eco-Friendly' }}</h5>
            <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">{{ $profil->katalog_feature_4_desc ?? 'Sustainable practices and non-toxic application methods.' }}</p>
        </div>
    </div>
</div>

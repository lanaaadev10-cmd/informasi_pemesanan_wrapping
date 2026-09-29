{{-- ============================================
    BAGIAN: Bottom Features Bar
    Deskripsi: Grid berisi 4 keunggulan utama layanan premium
============================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 pt-6 sm:pt-10 border-t border-white/10 mt-8 sm:mt-12 z-10 relative">
    <div class="flex items-start gap-3 sm:gap-3.5 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white/[0.02] border border-white/5 hover:border-[#ff6b00]/30 transition-all">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#ff6b00]/10 border border-[#ff6b00]/20 flex items-center justify-center text-[#ff6b00] shrink-0">
            <i class="ph-bold ph-seal-check text-base sm:text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-montserrat font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_1_title ?? 'Premium Films' }}</h5>
            <p class="text-[11px] font-questrial text-gray-400 mt-0.5 sm:mt-1 leading-relaxed">{{ $profil->katalog_feature_1_desc ?? 'We only use top-tier 3M, Avery, and Inozetek materials.' }}</p>
        </div>
    </div>
    
    <div class="flex items-start gap-3 sm:gap-3.5 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white/[0.02] border border-white/5 hover:border-[#ff6b00]/30 transition-all">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#ff6b00]/10 border border-[#ff6b00]/20 flex items-center justify-center text-[#ff6b00] shrink-0">
            <i class="ph-bold ph-wrench text-base sm:text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-montserrat font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_2_title ?? 'Expert Installers' }}</h5>
            <p class="text-[11px] font-questrial text-gray-400 mt-0.5 sm:mt-1 leading-relaxed">{{ $profil->katalog_feature_2_desc ?? 'Certified technicians with 10+ years of collective experience.' }}</p>
        </div>
    </div>

    <div class="flex items-start gap-3 sm:gap-3.5 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white/[0.02] border border-white/5 hover:border-[#ff6b00]/30 transition-all">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#ff6b00]/10 border border-[#ff6b00]/20 flex items-center justify-center text-[#ff6b00] shrink-0">
            <i class="ph-bold ph-shield-check text-base sm:text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-montserrat font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_3_title ?? 'Warranty' }}</h5>
            <p class="text-[11px] font-questrial text-gray-400 mt-0.5 sm:mt-1 leading-relaxed">{{ $profil->katalog_feature_3_desc ?? 'Full warranty on both material and labor defects.' }}</p>
        </div>
    </div>

    <div class="flex items-start gap-3 sm:gap-3.5 p-3.5 sm:p-4 rounded-xl sm:rounded-2xl bg-white/[0.02] border border-white/5 hover:border-[#ff6b00]/30 transition-all">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-[#ff6b00]/10 border border-[#ff6b00]/20 flex items-center justify-center text-[#ff6b00] shrink-0">
            <i class="ph-bold ph-leaf text-base sm:text-lg"></i>
        </div>
        <div>
            <h5 class="text-xs font-montserrat font-bold text-white uppercase tracking-wider">{{ $profil->katalog_feature_4_title ?? 'Eco-Friendly' }}</h5>
            <p class="text-[11px] font-questrial text-gray-400 mt-0.5 sm:mt-1 leading-relaxed">{{ $profil->katalog_feature_4_desc ?? 'Sustainable practices and non-toxic application methods.' }}</p>
        </div>
    </div>
</div>

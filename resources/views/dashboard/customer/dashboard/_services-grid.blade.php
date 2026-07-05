<!-- Layanan Cepat Section -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->section_layanan_cepat ?? 'Layanan Cepat' }}</h3>
        <a href="{{ route('katalog.user') }}" class="text-[10px] font-bold text-[#f2994a] uppercase tracking-widest hover:underline flex items-center gap-1">
            Lihat Semua Layanan <i class="ph-bold ph-caret-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                <i class="ph-bold {{ $profil->dashboard_service_1_icon ?? 'ph-shield' }} text-xl"></i>
            </div>
            <div class="space-y-1.5 mt-6">
                <h4 class="text-xs font-black text-white uppercase tracking-wider">
                    {{ $profil->dashboard_service_1_title ?? 'Paint Protection Film' }}
                </h4>
                <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                    {{ $profil->dashboard_service_1_desc ?? 'Perlindungan cat maksimal dari goresan dan kotoran.' }}
                </p>
            </div>
        </a>

        <!-- Card 2 -->
        <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                <i class="ph-bold {{ $profil->dashboard_service_2_icon ?? 'ph-palette' }} text-xl"></i>
            </div>
            <div class="space-y-1.5 mt-6">
                <h4 class="text-xs font-black text-white uppercase tracking-wider">
                    {{ $profil->dashboard_service_2_title ?? 'Color Change' }}
                </h4>
                <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                    {{ $profil->dashboard_service_2_desc ?? 'Transformasi warna total dengan bahan premium.' }}
                </p>
            </div>
        </a>

        <!-- Card 3 -->
        <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                <i class="ph-bold {{ $profil->dashboard_service_3_icon ?? 'ph-armchair' }} text-xl"></i>
            </div>
            <div class="space-y-1.5 mt-6">
                <h4 class="text-xs font-black text-white uppercase tracking-wider">
                    {{ $profil->dashboard_service_3_title ?? 'Interior Styling' }}
                </h4>
                <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                    {{ $profil->dashboard_service_3_desc ?? 'Sentuhan elegan untuk kenyamanan kabin Anda.' }}
                </p>
            </div>
        </a>

        <!-- Card 4 -->
        <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                <i class="ph-bold {{ $profil->dashboard_service_4_icon ?? 'ph-sparkles' }} text-xl"></i>
            </div>
            <div class="space-y-1.5 mt-6">
                <h4 class="text-xs font-black text-white uppercase tracking-wider">
                    {{ $profil->dashboard_service_4_title ?? 'Detailing' }}
                </h4>
                <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                    {{ $profil->dashboard_service_4_desc ?? 'Pembersihan mendalam untuk kilau sempurna.' }}
                </p>
            </div>
        </a>
    </div>
</div>

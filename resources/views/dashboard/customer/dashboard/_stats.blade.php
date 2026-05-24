<!-- Member Status Card (Mockup Matching) -->
<div class="bg-gradient-to-br from-[#f2994a]/25 to-[#e28a44]/5 border border-[#f2994a]/30 rounded-3xl p-8 flex flex-col justify-between hover:border-[#f2994a]/50 transition-all duration-300 shadow-xl relative overflow-hidden">
    <!-- Glowing Circle background -->
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#f2994a]/20 rounded-full blur-[60px] pointer-events-none"></div>

    <div class="space-y-6">
        <!-- Tag & Icon -->
        <div class="flex justify-between items-center">
            <span class="text-[9px] font-bold text-[#f2994a] uppercase tracking-widest font-mono">Member Status</span>
            <div class="w-8 h-8 rounded-lg bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a]">
                <i class="ph-bold ph-crown text-base"></i>
            </div>
        </div>

        <!-- Main Status -->
        <div class="space-y-1">
            <h4 class="text-2xl font-extrabold text-white">
                {{ $profil->dashboard_member_title ?? 'Premium Gold' }}
            </h4>
            <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                {{ $profil->dashboard_member_desc ?? 'Satu langkah lagi menuju Platinum' }}
            </p>
        </div>

        <!-- Progress Bar -->
        @php
            $progressVal = $profil->dashboard_member_progress ?? 85;
        @endphp
        <div class="space-y-2 pt-2">
            <div class="flex justify-between items-center text-[10px] font-bold">
                <span class="text-gray-400 uppercase tracking-wider">Progress</span>
                <span class="text-white font-mono">{{ $progressVal }}%</span>
            </div>
            <div class="w-full h-2 bg-white/5 border border-white/5 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#e28a44] to-[#f2994a] rounded-full shadow-[0_0_10px_rgba(242,153,74,0.3)]" style="width: {{ $progressVal }}%;"></div>
            </div>
        </div>
    </div>

    <!-- Benefits Footer -->
    <div class="pt-6 border-t border-white/5 mt-6">
        <p class="text-[9px] text-gray-400 leading-relaxed font-light">
            @if($profil->dashboard_member_benefits)
                {!! $profil->dashboard_member_benefits !!}
            @else
                Keuntungan Anda: <span class="text-[#f2994a] font-bold">Diskon 15% Detailing</span> &amp; Prioritas Antrean.
            @endif
        </p>
    </div>
</div>

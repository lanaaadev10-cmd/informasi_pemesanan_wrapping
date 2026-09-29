{{-- Hero Banner — Text Only, Black Background (No Image) --}}
<div class="relative overflow-hidden rounded-[28px] bg-[#0a0a0a] border border-white/10 shadow-2xl h-full flex flex-col justify-between" id="hero-panel-inner">

    {{-- Decorative orange glow blobs --}}
    <div class="absolute -top-8 -right-8 w-40 h-40 bg-[#ff6b00]/20 rounded-full blur-[70px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#ff6b00]/10 rounded-full blur-[60px] pointer-events-none"></div>

    {{-- Decorative vertical line accent --}}
    <div class="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-[#ff6b00] via-[#ff6b00]/30 to-transparent rounded-l-[28px]"></div>

    {{-- Content --}}
    <div class="relative z-10 p-5 sm:p-7 flex flex-col justify-between h-full space-y-4">

        {{-- Top: Clean Text Indicator --}}
        <div class="flex items-center gap-2 self-start">
            <span class="w-1.5 h-1.5 rounded-full bg-[#ff6b00]"></span>
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00]">
                {{ $profil->dashboard_member_title ?? 'LAYANAN UNGGULAN' }}
            </span>
        </div>

        {{-- Middle: Headline --}}
        <div class="space-y-2 flex-1 flex flex-col justify-center">
            <h1 class="font-audiowide font-bold text-white leading-tight tracking-wide">
                <span class="block text-xl sm:text-2xl leading-snug">{{ $profil->hero_title_1 ?? 'Transformasi' }}</span>
                <span class="block text-xl sm:text-2xl text-[#ff6b00]">{{ $profil->hero_title_2 ?? 'Kendaraan Impian' }}</span>
            </h1>
            <p class="text-[11px] sm:text-xs font-questrial text-gray-400 leading-relaxed max-w-[260px]">
                {{ $profil->dashboard_subtitle ?? 'Car wrapping & variasi kendaraan profesional dengan material grade A dan garansi resmi.' }}
            </p>
        </div>

        {{-- Bottom: CTA Buttons --}}
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('katalog.user') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#ff6b00] hover:bg-[#ea580c] active:scale-95 text-white font-montserrat font-bold text-[10px] uppercase tracking-wider rounded-full shadow-[0_6px_20px_rgba(255,107,0,0.4)] transition-all">
                <span>{{ $profil->cta_pilih_layanan ?? 'Pesan Layanan' }}</span>
                <div class="w-4 h-4 rounded-full bg-white text-black flex items-center justify-center text-[8px]">
                    <i class="ph-bold ph-arrow-right"></i>
                </div>
            </a>

            <a href="#booking-calendar"
               onclick="expandPanel('calendar')"
               class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/8 hover:bg-white/15 text-white font-montserrat font-semibold text-[10px] rounded-full border border-white/15 backdrop-blur-md transition-all">
                <i class="ph-bold ph-calendar-check text-[#ff6b00] text-xs"></i>
                <span>Cek Slot</span>
            </a>
        </div>

    </div>

    {{-- Company name accent bottom-right (text decoration only) --}}
    <div class="absolute bottom-3 right-4 text-[#ff6b00]/20 font-audiowide text-[28px] sm:text-[36px] font-bold leading-none select-none pointer-events-none tracking-tight">
        {{ mb_strtoupper(substr($profil->nama_perusahaan ?? 'DS', 0, 2)) }}
    </div>
</div>

<div class="relative w-full rounded-[32px] overflow-hidden flex items-center justify-center py-24 sm:py-32">
    @if(!empty($profil->layanan_hero_image))
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('storage/' . $profil->layanan_hero_image) }}"
                 class="w-full h-full object-cover object-center"
                 alt="Background Layanan">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/40 to-[#0a0a0a]"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
    @endif

    <div class="relative z-10 text-center space-y-6 sm:space-y-8 max-w-3xl mx-auto px-6">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest"
             style="background:color-mix(in srgb,var(--accent)10%,transparent);border-color:color-mix(in srgb,var(--accent)20%,transparent);color:var(--accent)">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent)"></span>
            {{ $profil?->layanan_section_mengapa_badge ?? 'Layanan & Paket' }}
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
            {{ $profil?->layanan_hero_title ?? 'Precision in Every Layer.' }}
        </h1>

        @if(!empty($profil->layanan_hero_desc))
            <p class="text-base leading-relaxed max-w-[42rem] mx-auto text-[var(--accent)]">
                {{ $profil->layanan_hero_desc }}
            </p>
        @else
            <p class="text-base leading-relaxed max-w-[42rem] mx-auto text-[var(--accent)]">
                Pilih paket perlindungan dan estetika terbaik untuk kendaraan Anda. Menggunakan material grade premium dengan pemasangan yang sangat mendetail.
            </p>
        @endif
    </div>
</div>

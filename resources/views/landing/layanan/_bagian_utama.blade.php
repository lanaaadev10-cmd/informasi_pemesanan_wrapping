{{-- ═══════════════════════════════════════════════════════════════════
     HERO SECTION (Mengadaptasi Komposisi Header Gambar Referensi)
     Split Grid: Sisi Kiri Visual Media Frame & Sisi Kanan Editorial Deskripsi + Dual Pill Action
═══════════════════════════════════════════════════════════════════ --}}
<div class="space-y-10 sm:space-y-14">

    {{-- ─── 1. DUAL COLUMN HERO (Sesuai Referensi Top Section) ─── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

        {{-- Sisi Kiri: Media Showcase Frame (Rounded Minimalist Card di Referensi) --}}
        <div class="order-2 lg:order-1">
            <div class="relative rounded-3xl overflow-hidden bg-slate-100 border border-slate-200/90 shadow-sm group aspect-[4/3] sm:aspect-[16/10] lg:aspect-[4/3] flex items-center justify-center">
                @php
                    $heroImage = !empty($services[0]['gambar']) 
                        ? \App\Helpers\StaticContent::fotoUrl($services[0]['gambar']) 
                        : asset('images/layanan/wrapping-mobil.webp');
                @endphp
                <img src="{{ $heroImage }}"
                     alt="Dantie Stiker Workshop Layanan"
                     class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105"
                     loading="eager"
                     fetchpriority="high"
                     decoding="async">

                {{-- Clean Media Frame Without Badges --}}
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 via-transparent to-transparent"></div>
            </div>
        </div>

        {{-- Sisi Kanan: Headline & Deskripsi (Clean Typography) --}}
        <div class="order-1 lg:order-2 space-y-4 sm:space-y-5 text-left">
            
            {{-- Clean Category Kicker (No Pill Badge) --}}
            <span class="text-xs sm:text-sm font-black uppercase tracking-widest text-[#ea580c] block">
                Katalog Lengkap Biaya &amp; Jasa
            </span>

            {{-- Headline --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
                {{ \App\Helpers\StaticContent::LAYANAN_HERO_TITLE }}
            </h1>

            {{-- Subtitle --}}
            <p class="text-sm sm:text-base lg:text-lg leading-relaxed text-slate-600 font-normal max-w-2xl">
                {{ \App\Helpers\StaticContent::LAYANAN_HERO_DESC }}
            </p>

            {{-- Dual CTA Pill Buttons --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                <a href="#layanan-filter-section"
                   class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 text-white font-montserrat font-medium text-xs sm:text-sm tracking-wide transition-all shadow-md shadow-orange-500/20 hover:scale-105 active:scale-95">
                    <i class="ph-bold ph-arrow-down text-sm text-white"></i>
                    <span>Jelajahi Paket Layanan</span>
                </a>

                <a href="#layanan-portfolio-section"
                   class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white hover:bg-slate-100 text-slate-800 font-montserrat font-medium text-xs sm:text-sm border border-slate-300 hover:border-[#ea580c] transition-all shadow-sm active:scale-95">
                    <i class="ph-bold ph-images-square text-sm text-[#ea580c]"></i>
                    <span>Lihat Portfolio</span>
                </a>
            </div>

        </div>

    </div>

    {{-- ─── 2. MATERIAL TRUST BAR ─── --}}
    <div class="border-y border-slate-100 py-5 sm:py-6 text-center space-y-3">
        <p class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest text-slate-400">
            MATERIAL KELAS DUNIA &amp; STANDAR GARANSI RESMI
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-8 text-slate-400 font-extrabold text-[11px] sm:text-xs tracking-wider">
            <span class="hover:text-slate-900 transition-colors">ORACAL 651 / 970</span>
            <span class="text-slate-300">•</span>
            <span class="hover:text-slate-900 transition-colors">TECKWRAP USA</span>
            <span class="text-slate-300">•</span>
            <span class="hover:text-slate-900 transition-colors">3M AUTOMOTIVE</span>
            <span class="text-slate-300">•</span>
            <span class="hover:text-slate-900 transition-colors">AVERY DENNISON</span>
            <span class="text-slate-300">•</span>
            <span class="hover:text-slate-900 transition-colors">MAXDECAL 7500</span>
        </div>
    </div>

</div>

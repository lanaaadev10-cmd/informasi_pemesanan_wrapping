{{-- ═══════════════════════════════════════════════════════════════════
     STANDAR WORKSHOP & GARANSI RESMI
     Mengadaptasi Seksi "ABOUT ME" pada Gambar Referensi:
     - Judul Section di Tengah (Bold Uppercase)
     - Kolom Kiri: Deskripsi & Manfaat Standar Pengerjaan
     - Kolom Kanan: Komposisi Kartu Geometris Rounded Berjenjang dengan Aksen Garis
═══════════════════════════════════════════════════════════════════ --}}
<div class="space-y-10 sm:space-y-12 w-full">

    {{-- Left-Aligned Editorial Split Heading (Beda dari section lain, bukan hanya di tengah) --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-3">
        <div>
            <span class="text-xs font-black uppercase tracking-widest text-[#ea580c] block mb-1">
                01 / Standar Pengerjaan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                {{ \App\Helpers\StaticContent::LAYANAN_MENGAPA_TITLE }}
            </h2>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md font-normal leading-relaxed">
            Garansi resmi pengerjaan, presisi sambungan bodi, dan proteksi maksimal terhadap cat orisinil kendaraan Anda.
        </p>
    </div>

    {{-- Grid 2 Kolom Sesuai Komposisi Referensi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start w-full">

        {{-- Kolom Kiri: Paragraf Filosofi & Keunggulan --}}
        <div class="w-full space-y-5 text-left">
            <h3 class="text-xl sm:text-2xl font-bold font-montserrat text-slate-900 tracking-tight leading-snug">
                Proteksi Cat Orisinil &amp; Presisi Lipatan Tanpa Cacat
            </h3>

            <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal">
                {{ \App\Helpers\StaticContent::LAYANAN_MENGAPA_DESC }}
            </p>

            {{-- 3 Tag Manfaat Utama --}}
            <div class="pt-2 space-y-2.5">
                <div class="flex items-center gap-3 text-xs sm:text-sm font-bold text-slate-800">
                    <span class="w-6 h-6 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="ph-bold ph-check text-xs"></i>
                    </span>
                    <span>{{ \App\Helpers\StaticContent::LAYANAN_BENEFIT_1 }}</span>
                </div>

                <div class="flex items-center gap-3 text-xs sm:text-sm font-bold text-slate-800">
                    <span class="w-6 h-6 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="ph-bold ph-check text-xs"></i>
                    </span>
                    <span>{{ \App\Helpers\StaticContent::LAYANAN_BENEFIT_2 }}</span>
                </div>

                <div class="flex items-center gap-3 text-xs sm:text-sm font-bold text-slate-800">
                    <span class="w-6 h-6 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="ph-bold ph-check text-xs"></i>
                    </span>
                    <span>{{ \App\Helpers\StaticContent::LAYANAN_BENEFIT_3 }}</span>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Komposisi Kartu Rounded Berjenjang (Clean, Tanpa Badge Pill) --}}
        <div class="w-full space-y-3.5">
            {{-- Kartu 1 --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2 hover:border-[#ea580c]/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">
                        Standar 01
                    </span>
                    <span class="text-xs font-bold text-[#ea580c]">100% Orisinil</span>
                </div>
                <h4 class="text-sm sm:text-base font-black font-montserrat text-slate-900">Perlindungan Cat Pabrik 100%</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">
                    Melindungi lapisan pernis pabrik dari baret kerikil jalanan, kusam akibat sinar UV, dan bercak jamur air.
                </p>
                <div class="w-12 h-1 bg-[#ea580c] rounded-full"></div>
            </div>

            {{-- Kartu 2 (Offset / Indented sedikit sesuai gaya referensi) --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2 sm:translate-x-3 hover:border-[#ea580c]/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">
                        Standar 02
                    </span>
                    <span class="text-xs font-bold text-emerald-600">Presisi Panel</span>
                </div>
                <h4 class="text-sm sm:text-base font-black font-montserrat text-slate-900">Teknisi Khusus Bodi &amp; Panel</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">
                    Pelepasan handle &amp; lampu menggunakan alat nilon tanpa merusak klip atau merusak bodi orisinil.
                </p>
                <div class="w-12 h-1 bg-emerald-500 rounded-full"></div>
            </div>

            {{-- Kartu 3 --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm space-y-2 hover:border-[#ea580c]/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">
                        Standar 03
                    </span>
                    <span class="text-xs font-bold text-sky-600">Garansi Pemasangan</span>
                </div>
                <h4 class="text-sm sm:text-base font-black font-montserrat text-slate-900">{{ \App\Helpers\StaticContent::LAYANAN_GARANSI_TITLE }}</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-normal">
                    {{ \App\Helpers\StaticContent::LAYANAN_GARANSI_DESC }}
                </p>
                <div class="w-12 h-1 bg-sky-500 rounded-full"></div>
            </div>
        </div>

    </div>

</div>

{{-- ====================================================================
    KOMPONEN: Studio Konfigurator Wrapping & Live Estimator Interaktif
    Desain Mobile-First & Desktop: Rapi, Padat, Tidak Berantakan di Layar HP
==================================================================== --}}
<div class="relative rounded-2xl sm:rounded-3xl bg-gradient-to-b from-[#16161a] to-[#0c0c0e] border border-white/10 p-4 sm:p-7 lg:p-10 shadow-2xl overflow-hidden" id="wrapping-studio-configurator">

    {{-- Ambient Glow Ornamen --}}
    <div class="absolute -top-32 -left-32 w-80 h-80 bg-[var(--accent)]/15 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- Header Studio --}}
    <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-3 sm:gap-4 border-b border-white/10 pb-4 sm:pb-6 mb-5 sm:mb-8">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-[var(--accent)]/10 border border-[var(--accent)]/30 text-[var(--accent)] text-[10px] sm:text-[11px] font-extrabold uppercase tracking-widest mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[var(--accent)] animate-ping"></span>
                <span>STUDIO ESTIMASI &amp; SIMULASI REAL-TIME</span>
            </div>
            <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-snug">
                Simulasi Kebutuhan &amp; Biaya dalam <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--accent)] to-amber-300">3 Detik</span>
            </h2>
            <p class="text-[11px] sm:text-sm text-gray-400 font-light mt-1 max-w-2xl leading-relaxed">
                Punya stiker sendiri atau mau terima beres bahan Oracal toko? Pilih opsi di bawah untuk melihat kalkulasi biaya dan waktu secara transparan.
            </p>
        </div>

        <button type="button" onclick="openComparisonModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl sm:rounded-2xl bg-white/5 hover:bg-white/10 border border-white/15 hover:border-[var(--accent)] text-xs font-bold text-gray-200 hover:text-white transition-all shadow-md shrink-0 active:scale-95 group">
            <span class="text-base group-hover:scale-110 transition-transform">⚖️</span>
            <span>Bandingkan: Bawa Bahan vs Paket Toko</span>
            <i class="ph-bold ph-arrow-up-right text-xs text-[var(--accent)]"></i>
        </button>
    </div>

    {{-- Main 2-Column Grid (Left: Configurator Inputs, Right: Live Estimation Card) --}}
    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-8 items-start">

        {{-- ════════════════════════════════════════════════════
             KOLOM KIRI: KONTROL INTERAKTIF 3 LANGKAH
        ════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-7 space-y-4 sm:space-y-6">

            {{-- LANGKAH 1: PILIH JENIS & UKURAN KENDARAAN --}}
            <div class="space-y-2.5 sm:space-y-3 bg-black/40 border border-white/5 rounded-xl sm:rounded-2xl p-3.5 sm:p-5">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-gray-300 flex items-center gap-2">
                        <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-[var(--accent)] text-black font-black text-[10px] sm:text-xs flex items-center justify-center">1</span>
                        <span>Tipe Kendaraan Anda</span>
                    </label>
                    <span class="text-[10px] sm:text-[11px] text-gray-400" id="cfg-vehicle-label-summary">Motor • Matic Kecil</span>
                </div>

                {{-- Primary Vehicle Toggle (Motor vs Mobil) --}}
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="cfgSetVehicle('motor')" id="cfg-btn-motor"
                            class="cfg-vehicle-btn active flex items-center justify-center gap-2 py-2.5 sm:py-3.5 px-3 rounded-xl border text-xs font-black uppercase tracking-wider transition-all duration-200 active:scale-98">
                        <span class="text-base sm:text-xl">🛵</span>
                        <span>Sepeda Motor</span>
                    </button>
                    <button type="button" onclick="cfgSetVehicle('mobil')" id="cfg-btn-mobil"
                            class="cfg-vehicle-btn flex items-center justify-center gap-2 py-2.5 sm:py-3.5 px-3 rounded-xl border text-xs font-black uppercase tracking-wider transition-all duration-200 active:scale-98">
                        <span class="text-base sm:text-xl">🚗</span>
                        <span>Mobil</span>
                    </button>
                </div>

                {{-- Sub-types for Motor: 3-Columns compact on mobile --}}
                <div id="cfg-subtypes-motor" class="pt-1">
                    <div class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 mb-1.5">Pilih Ukuran / Model Motor:</div>
                    <div class="grid grid-cols-3 gap-1.5 sm:gap-2">
                        <button type="button" onclick="cfgSetSubtype('kecil')" data-subtype="kecil"
                                class="cfg-subtype-btn active p-2 sm:p-3 rounded-xl border text-center sm:text-left transition-all">
                            <div class="text-[11px] sm:text-xs font-black text-white">Matic/Bebek</div>
                            <div class="text-[9px] text-gray-400 hidden sm:block truncate mt-0.5">Beat, Scoopy, Vario</div>
                        </button>
                        <button type="button" onclick="cfgSetSubtype('maxi')" data-subtype="maxi"
                                class="cfg-subtype-btn p-2 sm:p-3 rounded-xl border text-center sm:text-left transition-all">
                            <div class="text-[11px] sm:text-xs font-black text-white">Maxi Scooter</div>
                            <div class="text-[9px] text-gray-400 hidden sm:block truncate mt-0.5">NMAX, PCX, Aerox</div>
                        </button>
                        <button type="button" onclick="cfgSetSubtype('sport')" data-subtype="sport"
                                class="cfg-subtype-btn p-2 sm:p-3 rounded-xl border text-center sm:text-left transition-all">
                            <div class="text-[11px] sm:text-xs font-black text-white">Motor Sport</div>
                            <div class="text-[9px] text-gray-400 hidden sm:block truncate mt-0.5">Ninja, CBR, R15</div>
                        </button>
                    </div>
                </div>

                {{-- Sub-types for Mobil: 2-Columns on mobile --}}
                <div id="cfg-subtypes-mobil" class="pt-1 hidden">
                    <div class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 mb-1.5">Pilih Ukuran / Model Mobil:</div>
                    <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
                        <button type="button" onclick="cfgSetSubtype('city')" data-subtype="city"
                                class="cfg-subtype-btn active p-2.5 sm:p-3 rounded-xl border text-left transition-all">
                            <div class="text-[11px] sm:text-xs font-black text-white">City Car / Sedan</div>
                            <div class="text-[9px] text-gray-400 truncate mt-0.5">Brio, Agya, Yaris, Jazz</div>
                        </button>
                        <button type="button" onclick="cfgSetSubtype('suv')" data-subtype="suv"
                                class="cfg-subtype-btn p-2.5 sm:p-3 rounded-xl border text-left transition-all">
                            <div class="text-[11px] sm:text-xs font-black text-white">MPV / SUV Besar</div>
                            <div class="text-[9px] text-gray-400 truncate mt-0.5">Avanza, Innova, Fortuner</div>
                        </button>
                    </div>
                </div>
            </div>

            {{-- LANGKAH 2: PUNYA BAHAN SENDIRI ATAU PAKET TOKO? --}}
            <div class="space-y-2.5 sm:space-y-3 bg-black/40 border border-white/5 rounded-xl sm:rounded-2xl p-3.5 sm:p-5">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-gray-300 flex items-center gap-2">
                        <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-[var(--accent)] text-black font-black text-[10px] sm:text-xs flex items-center justify-center">2</span>
                        <span>Status Bahan / Stiker</span>
                    </label>
                    <span class="text-[10px] sm:text-[11px] text-emerald-400 font-bold" id="cfg-material-label-summary">Jasa Pasang Saja</span>
                </div>

                {{-- 2 Cards side-by-side on mobile --}}
                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                    {{-- Opsi A: Jasa Pasang Saja --}}
                    <button type="button" onclick="cfgSetMaterial('bawa-sendiri')" id="cfg-mat-bawa-sendiri"
                            class="cfg-material-btn active text-left p-3 sm:p-4 rounded-xl border transition-all duration-200 relative group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-base sm:text-lg">✂️</span>
                                <span class="text-[8px] sm:text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    Hemat
                                </span>
                            </div>
                            <div class="text-[11px] sm:text-xs font-black text-white leading-tight">Bawa Bahan Sendiri</div>
                        </div>
                        <p class="text-[9px] sm:text-[11px] text-gray-400 font-light leading-snug mt-1 line-clamp-2">
                            Hanya bayar jasa pasang presisi tanpa gelembung.
                        </p>
                    </button>

                    {{-- Opsi B: Paket Lengkap Toko --}}
                    <button type="button" onclick="cfgSetMaterial('toko')" id="cfg-mat-toko"
                            class="cfg-material-btn text-left p-3 sm:p-4 rounded-xl border transition-all duration-200 relative group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-base sm:text-lg">📦</span>
                                <span class="text-[8px] sm:text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-[var(--accent)]/20 text-[var(--accent)] border border-[var(--accent)]/30">
                                    Garansi 1-3 Thn
                                </span>
                            </div>
                            <div class="text-[11px] sm:text-xs font-black text-white leading-tight">Paket All-In Toko</div>
                        </div>
                        <p class="text-[9px] sm:text-[11px] text-gray-400 font-light leading-snug mt-1 line-clamp-2">
                            Vinyl Oracal Jerman toko + garansi resmi.
                        </p>
                    </button>
                </div>
            </div>

            {{-- LANGKAH 3: CAKUPAN / BAGIAN BODI --}}
            <div class="space-y-2.5 sm:space-y-3 bg-black/40 border border-white/5 rounded-xl sm:rounded-2xl p-3.5 sm:p-5">
                <div class="flex items-center justify-between">
                    <label class="text-[11px] sm:text-xs font-black uppercase tracking-wider text-gray-300 flex items-center gap-2">
                        <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-[var(--accent)] text-black font-black text-[10px] sm:text-xs flex items-center justify-center">3</span>
                        <span>Bagian Bodi yang Digarap</span>
                    </label>
                    <span class="text-[10px] sm:text-[11px] text-gray-400" id="cfg-coverage-label-summary">FULL</span>
                </div>

                {{-- Coverage Options Motor --}}
                <div id="cfg-coverage-motor" class="flex gap-2 overflow-x-auto pb-1 no-scrollbar sm:flex-wrap">
                    <button type="button" onclick="cfgSetCoverage('full')" data-cov="full"
                            class="cfg-cov-btn active px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🏍️ Full Body Wrap
                    </button>
                    <button type="button" onclick="cfgSetCoverage('striping')" data-cov="striping"
                            class="cfg-cov-btn px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🎯 Decal / Striping Saja
                    </button>
                </div>

                {{-- Coverage Options Mobil --}}
                <div id="cfg-coverage-mobil" class="flex gap-2 overflow-x-auto pb-1 no-scrollbar sm:flex-wrap hidden">
                    <button type="button" onclick="cfgSetCoverage('full')" data-cov="full"
                            class="cfg-cov-btn active px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🚗 Full Body Wrap
                    </button>
                    <button type="button" onclick="cfgSetCoverage('panel')" data-cov="panel"
                            class="cfg-cov-btn px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🏁 Kap Mesin / Atap
                    </button>
                    <button type="button" onclick="cfgSetCoverage('kaca-film')" data-cov="kaca-film"
                            class="cfg-cov-btn px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🕶️ Kaca Film
                    </button>
                    <button type="button" onclick="cfgSetCoverage('audio')" data-cov="audio"
                            class="cfg-cov-btn px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        🔊 Audio Mobil
                    </button>
                    <button type="button" onclick="cfgSetCoverage('lampu')" data-cov="lampu"
                            class="cfg-cov-btn px-3 py-2 rounded-xl border text-[11px] sm:text-xs font-bold transition-all whitespace-nowrap shrink-0">
                        💡 Lampu Bi-LED
                    </button>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════
             KOLOM KANAN: HASIL ESTIMASI REAL-TIME (TESLA STYLE)
        ════════════════════════════════════════════════════ --}}
        <div class="lg:col-span-5 lg:sticky lg:top-24 w-full">
            <div class="bg-gradient-to-b from-[#1b1b22] to-[#121216] border border-[var(--accent)]/30 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-7 shadow-[0_20px_50px_-15px_rgba(242,153,74,0.2)] relative overflow-hidden space-y-4">
                
                {{-- Badge Status Rekomendasi --}}
                <div class="flex items-center justify-between border-b border-white/10 pb-3">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-emerald-400">
                            REKOMENDASI COCOK 100%
                        </span>
                    </div>
                    <span id="cfg-result-badge-type" class="text-[9px] sm:text-[10px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                        Jasa Pasang Saja
                    </span>
                </div>

                {{-- Foto Layanan Dinamis --}}
                <div class="relative h-36 sm:h-44 rounded-xl sm:rounded-2xl overflow-hidden bg-black/60 border border-white/10 group">
                    <img id="cfg-result-img" src="{{ asset('images/layanan/jasa-pasang-decal.jpg') }}" 
                         alt="Preview Layanan" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2.5 left-2.5 right-2.5 flex items-center justify-between text-white text-xs">
                        <span id="cfg-result-tag-vehicle" class="bg-black/75 backdrop-blur-md px-2 py-0.5 rounded-lg border border-white/15 text-[10px] sm:text-[11px] font-bold">
                            🛵 Motor Matic Kecil
                        </span>
                        <span id="cfg-result-tag-duration" class="bg-black/75 backdrop-blur-md px-2 py-0.5 rounded-lg border border-white/15 text-[10px] sm:text-[11px] font-light flex items-center gap-1">
                            <i class="ph-bold ph-clock text-[var(--accent)]"></i>
                            <span id="cfg-result-duration-text">2-3 Jam</span>
                        </span>
                    </div>
                </div>

                {{-- Judul Paket & Deskripsi Singkat --}}
                <div class="space-y-0.5 sm:space-y-1">
                    <h3 id="cfg-result-title" class="text-base sm:text-lg lg:text-xl font-black text-white leading-snug">
                        Jasa Pasang Decal Motor Kecil (Bahan Sendiri)
                    </h3>
                    <p id="cfg-result-desc" class="text-[11px] sm:text-xs text-gray-300 font-light leading-relaxed line-clamp-2">
                        Pemasangan stiker presisi tanpa gelembung khusus motor matic kecil bagi Anda yang sudah membawa stiker sendiri.
                    </p>
                </div>

                {{-- Kotak Harga Transparan --}}
                <div class="bg-black/50 border border-white/10 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 flex items-baseline justify-between">
                    <div>
                        <span id="cfg-result-price-label" class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 block">
                            Ongkos Tenaga Pasang:
                        </span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span id="cfg-result-price" class="text-xl sm:text-3xl font-black text-[var(--accent)]">
                                Rp 100.000
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Garansi:</span>
                        <span id="cfg-result-warranty" class="text-[11px] sm:text-xs font-bold text-gray-200 block mt-0.5">
                            Kerapian Pasang
                        </span>
                    </div>
                </div>

                {{-- Keunggulan Termasuk (Checklist) --}}
                <div class="space-y-1.5 sm:space-y-2 pt-1 border-t border-white/10" id="cfg-result-checklist">
                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-gray-300">
                        <i class="ph-bold ph-check-circle text-emerald-400 text-sm shrink-0 mt-0.5"></i>
                        <span id="cfg-feat-1">Pembersihan &amp; degreasing bodi sebelum pemasangan</span>
                    </div>
                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-gray-300">
                        <i class="ph-bold ph-check-circle text-emerald-400 text-sm shrink-0 mt-0.5"></i>
                        <span id="cfg-feat-2">Finishing heatgun anti-mengelupas pada lekukan bodi</span>
                    </div>
                    <div class="flex items-start gap-2 text-[11px] sm:text-xs text-gray-300">
                        <i class="ph-bold ph-check-circle text-emerald-400 text-sm shrink-0 mt-0.5"></i>
                        <span id="cfg-feat-3">Teknisi ahli berpengalaman, cat orisinil aman</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-2 pt-1">
                    @auth
                        <a id="cfg-booking-btn" href="{{ route('booking.create', ['layanan_id' => 4]) }}"
                           class="w-full py-3 sm:py-3.5 px-4 bg-[var(--accent)] hover:brightness-110 active:scale-98 text-black font-black text-xs sm:text-sm tracking-wider uppercase rounded-xl transition-all shadow-[0_4px_20px_rgba(242,153,74,0.35)] flex items-center justify-center gap-2">
                            <i class="ph-bold ph-calendar-plus text-base"></i>
                            <span>Jadwalkan Booking Paket Ini</span>
                        </a>
                    @else
                        <button type="button" onclick="showRegisterPrompt()"
                                class="w-full py-3 sm:py-3.5 px-4 bg-[var(--accent)] hover:brightness-110 active:scale-98 text-black font-black text-xs sm:text-sm tracking-wider uppercase rounded-xl transition-all shadow-[0_4px_20px_rgba(242,153,74,0.35)] flex items-center justify-center gap-2">
                            <i class="ph-bold ph-calendar-plus text-base"></i>
                            <span>Booking Layanan Ini Sekarang</span>
                        </button>
                    @endauth

                    @php
                        $cleanCfgWa = preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6283117163605');
                        if (empty($cleanCfgWa)) $cleanCfgWa = '6283117163605';
                    @endphp
                    <a id="cfg-wa-btn" href="https://wa.me/{{ $cleanCfgWa }}?text={{ urlencode('Halo Admin Dantie Stiker, saya tertarik dengan pengerjaan wrapping/decal kendaraan') }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-full py-2.5 px-3 bg-white/5 hover:bg-emerald-500/15 border border-white/10 hover:border-emerald-500/40 text-gray-300 hover:text-emerald-400 font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-whatsapp-logo text-base text-emerald-400"></i>
                        <span>Tanya / Konsultasi via WhatsApp</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- ====================================================================
     FLOATING BOTTOM BAR KHUSUS HP (STICKY ESTIMATOR SUMMARY)
     Tampil melayang di bagian bawah HP agar user tidak perlu scroll bolak-balik
==================================================================== --}}
<div id="cfg-mobile-sticky-bar" class="fixed bottom-0 inset-x-0 z-40 bg-[#101014]/95 backdrop-blur-lg border-t border-white/15 px-4 py-3 sm:hidden shadow-[0_-10px_30px_rgba(0,0,0,0.8)] flex items-center justify-between">
    <div class="space-y-0.5">
        <span class="text-[9px] font-extrabold uppercase tracking-wider text-gray-400 block" id="cfg-mobile-bar-label">
            Estimasi Biaya:
        </span>
        <div class="flex items-baseline gap-1.5">
            <span class="text-base font-black text-[var(--accent)]" id="cfg-mobile-bar-price">
                Rp 100.000
            </span>
            <span class="text-[10px] text-gray-400 font-light" id="cfg-mobile-bar-duration">
                • 2-3 Jam
            </span>
        </div>
    </div>

    @auth
        <a id="cfg-mobile-booking-btn" href="{{ route('booking.create', ['layanan_id' => 4]) }}"
           class="py-2.5 px-4 bg-[var(--accent)] text-black font-black text-xs uppercase tracking-wider rounded-xl shadow-lg flex items-center gap-1.5 active:scale-95">
            <span>Booking</span>
            <i class="ph-bold ph-arrow-right text-xs"></i>
        </a>
    @else
        <button type="button" onclick="showRegisterPrompt()"
                class="py-2.5 px-4 bg-[var(--accent)] text-black font-black text-xs uppercase tracking-wider rounded-xl shadow-lg flex items-center gap-1.5 active:scale-95">
            <span>Booking</span>
            <i class="ph-bold ph-arrow-right text-xs"></i>
        </button>
    @endauth
</div>

{{-- ====================================================================
     MODAL POPUP: SIDE-BY-SIDE COMPARISON MATRIX
     "Bawa Bahan Sendiri VS Paket Lengkap Toko"
==================================================================== --}}
<div id="modal-comparison-layanan" class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md transition-all">
    <div class="bg-[#141418] border border-white/15 rounded-2xl sm:rounded-3xl max-w-3xl w-full p-4 sm:p-7 shadow-2xl relative max-h-[90vh] overflow-y-auto no-scrollbar animate-modal-in">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeComparisonModal()"
                class="absolute top-4 right-4 sm:top-5 sm:right-5 w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-gray-300 hover:text-white transition-all">
            <i class="ph-bold ph-x text-sm sm:text-base"></i>
        </button>

        {{-- Modal Header --}}
        <div class="mb-4 sm:mb-6 space-y-1 pr-8">
            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-[var(--accent)]">PANDUAN TRANSPARAN</span>
            <h3 class="text-lg sm:text-2xl font-black text-white">
                Perbandingan: Bawa Bahan Sendiri vs Paket Lengkap Toko
            </h3>
            <p class="text-[11px] sm:text-xs text-gray-400 font-light">
                Pilih opsi yang paling menguntungkan dan sesuai kebutuhan anggaran Anda.
            </p>
        </div>

        {{-- Table Comparison Matrix --}}
        <div class="overflow-x-auto rounded-xl sm:rounded-2xl border border-white/10 -mx-1 sm:mx-0">
            <table class="w-full text-left text-[11px] sm:text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-gray-300 uppercase tracking-wider text-[9px] sm:text-[10px]">
                        <th class="p-2.5 sm:p-3.5 font-bold">Parameter</th>
                        <th class="p-2.5 sm:p-3.5 font-black text-emerald-400 bg-emerald-950/20">✂️ Bawa Bahan Sendiri</th>
                        <th class="p-2.5 sm:p-3.5 font-black text-[var(--accent)] bg-[var(--accent)]/10">📦 Paket Lengkap Toko</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-gray-300">
                    <tr>
                        <td class="p-2.5 sm:p-3.5 font-bold text-white">Sumber Bahan</td>
                        <td class="p-2.5 sm:p-3.5 bg-emerald-950/10">Beli sendiri (Shopee/Tokped/Decal)</td>
                        <td class="p-2.5 sm:p-3.5 bg-[var(--accent)]/5">Oracal 651/970 Jerman Original</td>
                    </tr>
                    <tr>
                        <td class="p-2.5 sm:p-3.5 font-bold text-white">Biaya</td>
                        <td class="p-2.5 sm:p-3.5 bg-emerald-950/10 font-black text-emerald-400">Jasa pasang (Mulai Rp 100rb)</td>
                        <td class="p-2.5 sm:p-3.5 bg-[var(--accent)]/5 font-black text-[var(--accent)]">All-In Bahan + Jasa (Mulai Rp 450rb)</td>
                    </tr>
                    <tr>
                        <td class="p-2.5 sm:p-3.5 font-bold text-white">Garansi</td>
                        <td class="p-2.5 sm:p-3.5 bg-emerald-950/10">Garansi kerapian pasang</td>
                        <td class="p-2.5 sm:p-3.5 bg-[var(--accent)]/5">Garansi resmi 1 s/d 3 tahun</td>
                    </tr>
                    <tr>
                        <td class="p-2.5 sm:p-3.5 font-bold text-white">Bahan Kurang</td>
                        <td class="p-2.5 sm:p-3.5 bg-emerald-950/10 text-amber-300">Tanggung jawab pembeli olshop</td>
                        <td class="p-2.5 sm:p-3.5 bg-[var(--accent)]/5 text-emerald-300">Ditanggung penuh pihak toko</td>
                    </tr>
                    <tr>
                        <td class="p-2.5 sm:p-3.5 font-bold text-white">Waktu Kerja</td>
                        <td class="p-2.5 sm:p-3.5 bg-emerald-950/10">Cepat (2 - 4 Jam)</td>
                        <td class="p-2.5 sm:p-3.5 bg-[var(--accent)]/5">Detailing mendalam (1 - 4 Hari)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer Button --}}
        <div class="mt-4 sm:mt-6 flex justify-end">
            <button type="button" onclick="closeComparisonModal()"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-[var(--accent)] text-black font-extrabold text-xs shadow-lg hover:brightness-110 transition-all text-center">
                Saya Mengerti, Tutup Panduan
            </button>
        </div>

    </div>
</div>

{{-- ====================================================================
     STYLING & LOGIKA JAVASCRIPT CONFIGURATOR
==================================================================== --}}
<style>
    /* Styling Tombol Kendaraan */
    .cfg-vehicle-btn {
        background-color: #121215;
        border-color: rgba(255, 255, 255, 0.1);
        color: #9ca3af;
    }
    .cfg-vehicle-btn:hover {
        border-color: rgba(242, 153, 74, 0.4);
        color: #ffffff;
    }
    .cfg-vehicle-btn.active {
        background: linear-gradient(135deg, rgba(242, 153, 74, 0.25), rgba(20, 20, 24, 0.95));
        border-color: var(--accent);
        color: #ffffff;
        box-shadow: 0 0 15px rgba(242, 153, 74, 0.25);
    }

    /* Styling Tombol Subtype */
    .cfg-subtype-btn {
        background-color: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .cfg-subtype-btn:hover {
        border-color: rgba(242, 153, 74, 0.3);
        background-color: rgba(255, 255, 255, 0.06);
    }
    .cfg-subtype-btn.active {
        background-color: rgba(242, 153, 74, 0.15);
        border-color: var(--accent);
    }

    /* Styling Tombol Material */
    .cfg-material-btn {
        background-color: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .cfg-material-btn:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }
    .cfg-material-btn.active {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.06), rgba(20, 20, 24, 0.95));
        border-color: var(--accent);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    /* Styling Tombol Coverage */
    .cfg-cov-btn {
        background-color: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.1);
        color: #9ca3af;
    }
    .cfg-cov-btn:hover {
        border-color: rgba(242, 153, 74, 0.3);
        color: #ffffff;
    }
    .cfg-cov-btn.active {
        background-color: var(--accent);
        border-color: var(--accent);
        color: #000000;
        font-weight: 800;
    }
</style>

<script>
    // Database mapping konfigurator ke ID Layanan riil
    const CFG_DATABASE = {
        // Sepeda Motor
        'motor_kecil_bawa-sendiri_full': {
            id: 4,
            title: 'Jasa Pasang Decal Motor Kecil (Bahan Sendiri)',
            desc: 'Ongkos pasang decal bodi motor matic kecil presisi tanpa gelembung. Pelanggan membawa stiker sendiri.',
            price: 'Rp 100.000',
            priceLabel: 'Ongkos Tenaga Pasang:',
            priceLabelColor: 'text-emerald-400',
            duration: '2-3 Jam',
            warranty: 'Garansi Kerapian',
            badgeType: 'Jasa Pasang Saja',
            badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
            img: '{{ asset("images/layanan/jasa-pasang-decal.jpg") }}',
            tagVehicle: '🛵 Matic Kecil (Beat, Scoopy, Vario)',
            feats: [
                'Bawa bahan stiker sendiri, bebas custom desain',
                'Pembersihan & degreasing bodi sebelum pasang',
                'Finishing heatgun anti-mengelupas pada lekukan'
            ]
        },
        'motor_maxi_bawa-sendiri_full': {
            id: 5,
            title: 'Jasa Pasang Decal Motor Maxi (Bahan Sendiri)',
            desc: 'Ongkos pasang decal motor matic bongsor/maxi. Dikerjakan dengan pembongkaran bodi rapi pada lekukan bodi lebar.',
            price: 'Rp 150.000',
            priceLabel: 'Ongkos Tenaga Pasang:',
            priceLabelColor: 'text-emerald-400',
            duration: '3-4 Jam',
            warranty: 'Garansi Lekukan Presisi',
            badgeType: 'Jasa Pasang Saja',
            badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
            img: '{{ asset("images/layanan/jasa-pasang-decal.jpg") }}',
            tagVehicle: '🛵 Maxi Scooter (NMAX, PCX, Aerox)',
            feats: [
                'Bongkar pasang bodi rapi & presisi oleh teknisi ahli',
                'Pemasangan rapi pada lekukan bodi lebar tanpa kerutan',
                'Garansi kerapian sambungan & tidak ada gelembung udara'
            ]
        },
        'motor_sport_bawa-sendiri_full': {
            id: 6,
            title: 'Jasa Pasang Decal Motor Sport & Fairing (Bahan Sendiri)',
            desc: 'Ongkos pasang decal motor sport dengan lekukan fairing dan tangki bensin tajam berisiko tinggi.',
            price: 'Rp 250.000',
            priceLabel: 'Ongkos Tenaga Pasang:',
            priceLabelColor: 'text-emerald-400',
            duration: '4-6 Jam',
            warranty: 'Garansi Sambungan Simetris',
            badgeType: 'Jasa Pasang Saja',
            badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
            img: '{{ asset("images/layanan/jasa-pasang-decal.jpg") }}',
            tagVehicle: '🏍️ Motor Sport (Ninja, CBR, R15, R25)',
            feats: [
                'Pemasangan khusus lekukan tajam fairing & tangki bensin',
                'Finishing knifeless tape tanpa risiko gores cat asli',
                'Hasil simetris dan rapi hingga sudut-sudut bodi tersulit'
            ]
        },
        'motor_kecil_toko_full': {
            id: 8,
            title: 'Full Wrap Motor Kecil (Bahan Oracal + Garansi)',
            desc: 'Paket komplit bahan Oracal 651 Jerman original dari toko + jasa pasang bergaransi resmi 1 tahun.',
            price: 'Rp 450.000',
            priceLabel: 'Tarif Paket All-In Toko:',
            priceLabelColor: 'text-[var(--accent)]',
            duration: '1 Hari',
            warranty: 'Garansi Resmi 1 Tahun',
            badgeType: 'Paket All-In Toko',
            badgeBg: 'bg-[var(--accent)]/20 text-[var(--accent)] border-[var(--accent)]/40',
            img: '{{ asset("images/layanan/wrapping-motor.jpg") }}',
            tagVehicle: '🛵 Matic Kecil (Beat, Scoopy, Vario)',
            feats: [
                'Bahan premium Oracal 651 Jerman lentur & awet',
                'Pilihan finishing Glossy, Matte, atau Satin mewah',
                'Garansi lem 1 tahun & aman cat pabrik saat dilepas'
            ]
        },
        'motor_maxi_toko_full': {
            id: 9,
            title: 'Full Wrap Motor Maxi (Bahan Oracal + Garansi)',
            desc: 'Paket komplit all-in motor matic besar dengan vinyl Oracal/Maxdecal Jerman premium bergaransi resmi.',
            price: 'Rp 750.000',
            priceLabel: 'Tarif Paket All-In Toko:',
            priceLabelColor: 'text-[var(--accent)]',
            duration: '1-2 Hari',
            warranty: 'Garansi Resmi 1 Tahun',
            badgeType: 'Paket All-In Toko',
            badgeBg: 'bg-[var(--accent)]/20 text-[var(--accent)] border-[var(--accent)]/40',
            img: '{{ asset("images/layanan/wrap-motor-maxi.jpg") }}',
            tagVehicle: '🛵 Maxi Scooter (NMAX, PCX, Aerox)',
            feats: [
                'Bongkar bodi komplit, pembungkusan hingga lipatan dalam',
                'Proteksi maksimal dari baret harian dan paparan sinar UV',
                'Garansi pemasangan 1 tahun + free kontrol 1x di workshop'
            ]
        },
        'motor_sport_toko_full': {
            id: 10,
            title: 'Full Wrap Motor Sport (Bahan Oracal + Garansi)',
            desc: 'Paket lengkap wrapping motor sport full fairing & naked bike melindungi tangki bensin dan bodi tajam.',
            price: 'Rp 1.100.000',
            priceLabel: 'Tarif Paket All-In Toko:',
            priceLabelColor: 'text-[var(--accent)]',
            duration: '2 Hari',
            warranty: 'Garansi Resmi 1 Tahun',
            badgeType: 'Paket All-In Toko',
            badgeBg: 'bg-[var(--accent)]/20 text-[var(--accent)] border-[var(--accent)]/40',
            img: '{{ asset("images/layanan/wrapping-motor.jpg") }}',
            tagVehicle: '🏍️ Motor Sport (Ninja, CBR, R15, R25)',
            feats: [
                'Full fairing, tangki bensin, bodi belakang & spakbor depan',
                'Sealer edge protection anti-terkelupas terpaan angin cepat',
                'Garansi resmi 1 tahun toko Wapping'
            ]
        },
        'motor_any_any_striping': {
            id: 14,
            title: 'Striping & Grafis Presisi (Motor)',
            desc: 'Pemasangan striping cutting komputer presisi untuk aksen sporty minimalis atau racing look.',
            price: 'Rp 200.000',
            priceLabel: 'Tarif Paket Mulai:',
            priceLabelColor: 'text-sky-400',
            duration: '1-2 Jam',
            warranty: 'Garansi Kerapian',
            badgeType: 'Spesialis Striping',
            badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/40',
            img: '{{ asset("images/layanan/layanan-striping.jpg") }}',
            tagVehicle: '🛵 Striping Motor Custom',
            feats: [
                'Desain striping presisi cutting komputer tahan cuaca',
                'Pemasangan cepat 1-2 jam selesai rapi di workshop',
                'Aksen sporty tahan cuaca panas dan air hujan'
            ]
        },

        // Mobil
        'mobil_any_bawa-sendiri_any': {
            id: 7,
            title: 'Jasa Pasang Wrapping Panel Mobil (Bahan Sendiri)',
            desc: 'Ongkos jasa pasang stiker/karbon/scotlite panel mobil (kap mesin, atap, spion). Bahan dari pelanggan.',
            price: 'Rp 250.000',
            priceLabel: 'Ongkos Tenaga Pasang:',
            priceLabelColor: 'text-emerald-400',
            duration: '3-4 Jam',
            warranty: 'Garansi Kerapian Lekukan',
            badgeType: 'Jasa Pasang Saja',
            badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
            img: '{{ asset("images/layanan/jasa-pasang-mobil.jpg") }}',
            tagVehicle: '🚗 Panel Mobil (Kap / Atap / Spion)',
            feats: [
                'Bawa bahan stiker karbon/vinyl sendiri dari marketplace',
                'Teknik basah/kering dengan heatgun presisi profesional',
                'Knifeless tape tanpa risiko goresan pada cat mobil'
            ]
        },
        'mobil_city_toko_full': {
            id: 1,
            title: 'Variasi Mobil City Car (Bahan Toko + Garansi)',
            desc: 'Paket komplit full wrap mobil City Car / Hatchback / Sedan Kompak dengan vinyl otomotif Oracal Jerman.',
            price: 'Rp 3.500.000',
            priceLabel: 'Tarif Paket All-In Toko:',
            priceLabelColor: 'text-[var(--accent)]',
            duration: '3-4 Hari',
            warranty: 'Garansi Resmi 3 Tahun',
            badgeType: 'Paket All-In Toko',
            badgeBg: 'bg-[var(--accent)]/20 text-[var(--accent)] border-[var(--accent)]/40',
            img: '{{ asset("images/layanan/wrapping-mobil.jpg") }}',
            tagVehicle: '🚗 City Car (Brio, Agya, Yaris, Jazz)',
            feats: [
                'Pengerjaan indoor bebas debu dengan detail lipatan pintu',
                'Bahan Oracal/Maxdecal Jerman orisinil tahan cuaca tropis',
                'Garansi resmi gelembung & daya rekat 3 tahun penuh'
            ]
        },
        'mobil_suv_toko_full': {
            id: 11,
            title: 'Full Wrap Mobil SUV / MPV Besar (Bahan Toko + Garansi)',
            desc: 'Paket lengkap bodi penuh mobil MPV/SUV besar. Transformasi elegan dan protektif dengan vinyl premium.',
            price: 'Rp 5.500.000',
            priceLabel: 'Tarif Paket All-In Toko:',
            priceLabelColor: 'text-[var(--accent)]',
            duration: '4-5 Hari',
            warranty: 'Garansi Resmi 3 Tahun',
            badgeType: 'Paket All-In Toko',
            badgeBg: 'bg-[var(--accent)]/20 text-[var(--accent)] border-[var(--accent)]/40',
            img: '{{ asset("images/layanan/wrapping-mobil.jpg") }}',
            tagVehicle: '🚙 MPV / SUV (Avanza, Innova, Fortuner)',
            feats: [
                'Bahan premium Oracal/TeckWrap tebal tahan gores kerikil',
                'Garansi resmi 3 tahun + free sealant coating khusus vinyl',
                'Free kontrol berkala 1x di workshop Wapping'
            ]
        },
        'mobil_any_toko_panel': {
            id: 12,
            title: 'Custom Decal Mobil (Livery & Striping)',
            desc: 'Pengerjaan decal kustom, livery balap, atau grafis bodi mobil. Negosiasi konsep dan harga langsung via WhatsApp.',
            price: 'Custom Quote',
            priceLabel: 'Estimasi Proyek:',
            priceLabelColor: 'text-emerald-400',
            duration: '1-3 Hari',
            warranty: 'Garansi Kerapian',
            badgeType: 'Custom Project',
            badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
            img: '{{ asset("images/layanan/wrapping-mobil.jpg") }}',
            tagVehicle: '🚗 Custom Decal Mobil',
            feats: [
                'Desain custom eksklusif sesuai konsep impian Anda',
                'Bahan vinyl premium / printable decal standar otomotif',
                'Konsultasi desain & negosiasi harga via WhatsApp'
            ]
        },
        'mobil_any_any_kaca-film': {
            id: 2,
            title: 'Kaca Film Tolak Panas Premium',
            desc: 'Pemasangan kaca film tolak panas UV 99% dan IR 80%. Kabin sejuk, visibilitas jernih dari dalam, privasi terjaga.',
            price: 'Rp 350.000',
            priceLabel: 'Tarif Paket Mulai:',
            priceLabelColor: 'text-sky-400',
            duration: '1 Hari',
            warranty: 'Garansi 5 Tahun',
            badgeType: 'Kaca Film Tolak Panas',
            badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/40',
            img: '{{ asset("images/layanan/kaca-film.jpg") }}',
            tagVehicle: '🕶️ Kaca Film Full Set',
            feats: [
                'Anti UV 99% & tolak panas inframerah hingga 80%',
                'Pilihan kegelapan bebas: 40%, 60%, atau 80%',
                'Garansi anti-gelembung & warna pudar selama 5 tahun'
            ]
        },
        'mobil_any_any_audio': {
            id: 3,
            title: 'Instalasi & Tuning Audio Mobil',
            desc: 'Instalasi audio mobil profesional audiophile: speaker 2-way, amplifier, subwoofer aktif & peredam suara kabin.',
            price: 'Rp 2.000.000',
            priceLabel: 'Tarif Paket Mulai:',
            priceLabelColor: 'text-sky-400',
            duration: '2-4 Hari',
            warranty: 'Garansi Instalasi 2 Tahun',
            badgeType: 'Spesialis Audio Mobil',
            badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/40',
            img: '{{ asset("images/layanan/audio-head.jpg") }}',
            tagVehicle: '🔊 Audio & Head Unit',
            feats: [
                'Sound tuning profesional sesuai selera musik Anda',
                'Instalasi kabel rapi dengan fuse pengaman khusus',
                'Garansi instalasi & kelistrikan 2 tahun'
            ]
        },
        'mobil_any_any_lampu': {
            id: 13,
            title: 'Upgrade Lampu Proyektor Bi-LED',
            desc: 'Pemasangan lampu proyektor Bi-LED terang maksimal, cut-off fokus rata tidak menyilaukan pengendara lawan.',
            price: 'Rp 850.000',
            priceLabel: 'Tarif Paket Mulai:',
            priceLabelColor: 'text-sky-400',
            duration: '1 Hari',
            warranty: 'Garansi 1 Tahun',
            badgeType: 'Spesialis Lampu Bi-LED',
            badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/40',
            img: '{{ asset("images/layanan/lampu-biled.jpg") }}',
            tagVehicle: '💡 Upgrade Headlamp Bi-LED',
            feats: [
                'Proyektor lensa Bi-LED sorot terang & cut-off presisi',
                'Kelistrikan aman dengan relay set tahan air',
                'Garansi unit lampu & instalasi 1 tahun'
            ]
        }
    };

    // State konfigurator aktif
    let cfgState = {
        vehicle: 'motor',
        subtype: 'kecil',
        material: 'bawa-sendiri',
        coverage: 'full'
    };

    function cfgSetVehicle(v) {
        cfgState.vehicle = v;
        
        // Update UI vehicle buttons
        document.getElementById('cfg-btn-motor').classList.toggle('active', v === 'motor');
        document.getElementById('cfg-btn-mobil').classList.toggle('active', v === 'mobil');

        // Toggle Subtypes visibility
        document.getElementById('cfg-subtypes-motor').classList.toggle('hidden', v !== 'motor');
        document.getElementById('cfg-subtypes-mobil').classList.toggle('hidden', v !== 'mobil');

        // Toggle Coverage visibility
        document.getElementById('cfg-coverage-motor').classList.toggle('hidden', v !== 'motor');
        document.getElementById('cfg-coverage-mobil').classList.toggle('hidden', v !== 'mobil');

        // Set default subtype & coverage
        if (v === 'motor') {
            cfgState.subtype = 'kecil';
            cfgState.coverage = 'full';
        } else {
            cfgState.subtype = 'city';
            cfgState.coverage = 'full';
        }

        cfgUpdateSubtypeButtons();
        cfgUpdateCoverageButtons();
        cfgRenderResult();
    }

    function cfgSetSubtype(st) {
        cfgState.subtype = st;
        cfgUpdateSubtypeButtons();
        cfgRenderResult();
    }

    function cfgSetMaterial(m) {
        cfgState.material = m;
        document.getElementById('cfg-mat-bawa-sendiri').classList.toggle('active', m === 'bawa-sendiri');
        document.getElementById('cfg-mat-toko').classList.toggle('active', m === 'toko');
        cfgRenderResult();
    }

    function cfgSetCoverage(c) {
        cfgState.coverage = c;
        cfgUpdateCoverageButtons();
        cfgRenderResult();
    }

    function cfgUpdateSubtypeButtons() {
        const selector = cfgState.vehicle === 'motor' ? '#cfg-subtypes-motor .cfg-subtype-btn' : '#cfg-subtypes-mobil .cfg-subtype-btn';
        document.querySelectorAll(selector).forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-subtype') === cfgState.subtype);
        });
    }

    function cfgUpdateCoverageButtons() {
        const selector = cfgState.vehicle === 'motor' ? '#cfg-coverage-motor .cfg-cov-btn' : '#cfg-coverage-mobil .cfg-cov-btn';
        document.querySelectorAll(selector).forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-cov') === cfgState.coverage);
        });
    }

    function cfgRenderResult() {
        // Resolve target key
        let targetKey = null;

        if (cfgState.vehicle === 'motor') {
            if (cfgState.coverage === 'striping') {
                targetKey = 'motor_any_any_striping';
            } else {
                targetKey = `motor_${cfgState.subtype}_${cfgState.material}_${cfgState.coverage}`;
            }
        } else { // mobil
            if (cfgState.coverage === 'kaca-film') {
                targetKey = 'mobil_any_any_kaca-film';
            } else if (cfgState.coverage === 'audio') {
                targetKey = 'mobil_any_any_audio';
            } else if (cfgState.coverage === 'lampu') {
                targetKey = 'mobil_any_any_lampu';
            } else if (cfgState.material === 'bawa-sendiri') {
                targetKey = 'mobil_any_bawa-sendiri_any';
            } else if (cfgState.coverage === 'panel') {
                targetKey = 'mobil_any_toko_panel';
            } else {
                targetKey = `mobil_${cfgState.subtype}_${cfgState.material}_${cfgState.coverage}`;
            }
        }

        const data = CFG_DATABASE[targetKey] || CFG_DATABASE['motor_kecil_bawa-sendiri_full'];

        // Update Labels Summary
        const vSummary = cfgState.vehicle === 'motor' 
            ? `Motor • ${cfgState.subtype.toUpperCase()}` 
            : `Mobil • ${cfgState.subtype === 'city' ? 'City Car' : 'MPV/SUV'}`;
        document.getElementById('cfg-vehicle-label-summary').textContent = vSummary;
        
        const mSummary = cfgState.material === 'bawa-sendiri' ? 'Bawa Bahan (Jasa)' : 'Paket Toko (Oracal)';
        document.getElementById('cfg-material-label-summary').textContent = mSummary;
        document.getElementById('cfg-material-label-summary').className = cfgState.material === 'bawa-sendiri' ? 'text-[10px] sm:text-[11px] text-emerald-400 font-bold' : 'text-[10px] sm:text-[11px] text-[var(--accent)] font-bold';

        document.getElementById('cfg-coverage-label-summary').textContent = cfgState.coverage.toUpperCase();

        // Update Result Box
        document.getElementById('cfg-result-title').textContent = data.title;
        document.getElementById('cfg-result-desc').textContent = data.desc;
        document.getElementById('cfg-result-price').textContent = data.price;
        document.getElementById('cfg-result-price-label').textContent = data.priceLabel;
        document.getElementById('cfg-result-price-label').className = `text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider block ${data.priceLabelColor}`;
        document.getElementById('cfg-result-duration-text').textContent = data.duration;
        document.getElementById('cfg-result-warranty').textContent = data.warranty;
        document.getElementById('cfg-result-tag-vehicle').textContent = data.tagVehicle;

        // Badge Type
        const badgeEl = document.getElementById('cfg-result-badge-type');
        badgeEl.textContent = data.badgeType;
        badgeEl.className = `text-[9px] sm:text-[10px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wider border ${data.badgeBg}`;

        // Image
        document.getElementById('cfg-result-img').src = data.img;

        // Checklists
        if (data.feats && data.feats.length >= 3) {
            document.getElementById('cfg-feat-1').textContent = data.feats[0];
            document.getElementById('cfg-feat-2').textContent = data.feats[1];
            document.getElementById('cfg-feat-3').textContent = data.feats[2];
        }

        // Action Buttons Links
        const bookingBtn = document.getElementById('cfg-booking-btn');
        if (bookingBtn) {
            bookingBtn.href = `/booking/buat?layanan_id=${data.id}`;
        }

        const waBtn = document.getElementById('cfg-wa-btn');
        if (waBtn) {
            const waNumber = '{{ $cleanCfgWa ?? preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6283117163605') }}' || '6283117163605';
            const waText = encodeURIComponent(`Halo Admin Dantie Stiker, saya ingin tanya/booking paket hasil simulasi: ${data.title} (${data.price}) untuk kendaraan saya.`);
            waBtn.href = `https://wa.me/${waNumber}?text=${waText}`;
        }

        // Update Mobile Sticky Bottom Bar
        const mobilePrice = document.getElementById('cfg-mobile-bar-price');
        if (mobilePrice) mobilePrice.textContent = data.price;

        const mobileDuration = document.getElementById('cfg-mobile-bar-duration');
        if (mobileDuration) mobileDuration.textContent = `• ${data.duration}`;

        const mobileBookingBtn = document.getElementById('cfg-mobile-booking-btn');
        if (mobileBookingBtn) {
            mobileBookingBtn.href = `/booking/buat?layanan_id=${data.id}`;
        }
    }

    // Modal Comparison Handlers
    function openComparisonModal() {
        const modal = document.getElementById('modal-comparison-layanan');
        if (modal) modal.classList.remove('hidden');
    }

    function closeComparisonModal() {
        const modal = document.getElementById('modal-comparison-layanan');
        if (modal) modal.classList.add('hidden');
    }

    // Close modal on click outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-comparison-layanan');
        if (modal && e.target === modal) {
            closeComparisonModal();
        }
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        cfgRenderResult();
    });
</script>

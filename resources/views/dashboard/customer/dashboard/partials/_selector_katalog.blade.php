{{-- ==============================================================
     2-TIER CASCADING SELECTOR BOX
============================================================== --}}
<div class="space-y-3 sm:space-y-4 bg-gradient-to-b from-slate-50/90 to-white p-3 sm:p-4 rounded-2xl border border-slate-200/90 shadow-sm" id="cascading-selector-container">

    <!-- TIER 1: Selector Jenis Kendaraan (Segmented Control) -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] sm:text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="ph-bold ph-steering-wheel text-[#ff6b00]"></i>
                <span>Tier 1 — Jenis Kendaraan:</span>
            </span>
            <span class="text-[10px] sm:text-xs font-questrial text-slate-500">
                Filter: <strong class="text-slate-800" id="current-vehicle-text">Semua Kendaraan</strong>
            </span>
        </div>

        <!-- Segmented Control Bar: 3 Tombol Proporsional -->
        <div class="grid grid-cols-3 gap-1.5 sm:gap-2 p-1 bg-slate-200/70 rounded-xl" role="tablist" aria-label="Filter Jenis Kendaraan">
            <!-- 1. Semua Kendaraan -->
            <button type="button"
                    role="tab"
                    id="tier1-btn-all"
                    data-vehicle="all"
                    onclick="selectVehicleTier('all', this)"
                    aria-selected="true"
                    class="tier1-btn px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs font-montserrat font-bold transition-all duration-200 flex items-center justify-center gap-1.5 active:scale-95 bg-[#ff6b00] text-white shadow-sm shadow-orange-500/20">
                <span class="text-sm">🏁</span>
                <span class="truncate">Semua</span>
                <span class="hidden sm:inline-block text-[10px] py-0.2 px-1.5 rounded-full bg-white/25 ml-0.5 tier1-count">({{ $countAll }})</span>
            </button>

            <!-- 2. Mobil -->
            <button type="button"
                    role="tab"
                    id="tier1-btn-mobil"
                    data-vehicle="mobil"
                    onclick="selectVehicleTier('mobil', this)"
                    aria-selected="false"
                    class="tier1-btn px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs font-montserrat font-medium transition-all duration-200 flex items-center justify-center gap-1.5 active:scale-95 bg-transparent text-slate-700 hover:text-[#ff6b00] hover:bg-white/60">
                <span class="text-sm">🚗</span>
                <span class="truncate">Mobil</span>
                <span class="hidden sm:inline-block text-[10px] py-0.2 px-1.5 rounded-full bg-slate-300/60 ml-0.5 tier1-count">({{ $countMobil }})</span>
            </button>

            <!-- 3. Motor -->
            <button type="button"
                    role="tab"
                    id="tier1-btn-motor"
                    data-vehicle="motor"
                    onclick="selectVehicleTier('motor', this)"
                    aria-selected="false"
                    class="tier1-btn px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs font-montserrat font-medium transition-all duration-200 flex items-center justify-center gap-1.5 active:scale-95 bg-transparent text-slate-700 hover:text-[#ff6b00] hover:bg-white/60">
                <span class="text-sm">🛵</span>
                <span class="truncate">Motor</span>
                <span class="hidden sm:inline-block text-[10px] py-0.2 px-1.5 rounded-full bg-slate-300/60 ml-0.5 tier1-count">({{ $countMotor }})</span>
            </button>
        </div>
    </div>

    <!-- TIER 2: Selector Empat Pilar Kebutuhan (Pilar Layanan) -->
    <div class="pt-2 border-t border-slate-200/70">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] sm:text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="ph-bold ph-stack text-[#ff6b00]"></i>
                <span>Tier 2 — Pilar Kebutuhan:</span>
            </span>
            <span class="text-[10px] sm:text-xs font-questrial text-slate-500">
                Tersedia: <span id="active-matches-counter" class="font-bold text-[#ff6b00]">{{ $countAll }}</span> paket
            </span>
        </div>

        <!-- Tier 2 Pills Grid/Scroll: Responsif di Mobile, Touch-Friendly -->
        <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar py-1 -mx-1 px-1 sm:mx-0 sm:px-0" id="tier2-pill-container" role="tablist" aria-label="Filter Pilar Kebutuhan">
            <!-- 0. Semua Kategori -->
            <button type="button"
                    role="tab"
                    id="tier2-btn-all"
                    data-pillar="all"
                    onclick="selectPillarTier('all', this)"
                    aria-selected="true"
                    class="tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-bold transition-all duration-200 bg-[#ff6b00] text-white shadow-sm flex items-center gap-1.5 shrink-0 active:scale-95">
                <i class="ph-bold ph-squares-four text-xs"></i>
                <span>Semua Kategori</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-white/25 tier2-count" data-pillar-count="all">{{ $countAll }}</span>
            </button>

            <!-- 1. Jasa Pasang (Bawa Bahan Sendiri) -->
            <button type="button"
                    role="tab"
                    id="tier2-btn-jasa-pasang"
                    data-pillar="jasa-pasang"
                    onclick="selectPillarTier('jasa-pasang', this)"
                    aria-selected="false"
                    class="tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-medium transition-all duration-200 bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-[#ff6b00] flex items-center gap-1.5 shrink-0 active:scale-95">
                <i class="ph-bold ph-wrench text-xs text-emerald-600"></i>
                <span>Jasa Pasang</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-600 tier2-count" data-pillar-count="jasa-pasang">
                    {{ $countJasa }}
                </span>
            </button>

            <!-- 2. Full Wrap (Paket Bergaransi) -->
            <button type="button"
                    role="tab"
                    id="tier2-btn-full-wrap"
                    data-pillar="full-wrap"
                    onclick="selectPillarTier('full-wrap', this)"
                    aria-selected="false"
                    class="tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-medium transition-all duration-200 bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-[#ff6b00] flex items-center gap-1.5 shrink-0 active:scale-95">
                <i class="ph-bold ph-shield-check text-xs text-blue-600"></i>
                <span>Full Wrap</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-600 tier2-count" data-pillar-count="full-wrap">
                    {{ $countWrap }}
                </span>
            </button>

            <!-- 3. Custom (Desain & Spesifikasi Khusus) -->
            <button type="button"
                    role="tab"
                    id="tier2-btn-custom"
                    data-pillar="custom"
                    onclick="selectPillarTier('custom', this)"
                    aria-selected="false"
                    class="tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-medium transition-all duration-200 bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-[#ff6b00] flex items-center gap-1.5 shrink-0 active:scale-95">
                <i class="ph-bold ph-paint-brush text-xs text-purple-600"></i>
                <span>Custom</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-600 tier2-count" data-pillar-count="custom">
                    {{ $countCustom }}
                </span>
            </button>

            <!-- 4. Aksesoris (Kaca Film, Audio, Biled) -->
            <button type="button"
                    role="tab"
                    id="tier2-btn-aksesoris"
                    data-pillar="aksesoris"
                    onclick="selectPillarTier('aksesoris', this)"
                    aria-selected="false"
                    class="tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-medium transition-all duration-200 bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-[#ff6b00] flex items-center gap-1.5 shrink-0 active:scale-95">
                <i class="ph-bold ph-sliders text-xs text-amber-600"></i>
                <span>Aksesoris</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-600 tier2-count" data-pillar-count="aksesoris">
                    {{ $countAksesoris }}
                </span>
            </button>
        </div>
    </div>

</div>

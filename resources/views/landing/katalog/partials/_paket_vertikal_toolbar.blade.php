<!-- 1. TOOLBAR E-COMMERCE RAMPIS -->
<div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-4 shadow-sm space-y-3">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">

        <!-- Kiri: Mobile Filter Button & Counter -->
        <div class="flex items-center justify-between sm:justify-start gap-2.5">
            <div class="flex items-center gap-2">
                <!-- Tombol Buka Filter (Mobile < 768px) -->
                <button type="button"
                        onclick="toggleMobileFilter(true)"
                        class="md:hidden inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-200 text-[#ff6b00] text-xs font-montserrat font-bold active:scale-95 transition-all">
                    <i class="ph-bold ph-sliders-horizontal text-sm"></i>
                    <span>Filter</span>
                </button>

                <!-- Counter Hasil -->
                <div class="text-xs font-questrial text-slate-500">
                    Menampilkan <strong id="catalog-visible-count" class="text-slate-900 font-montserrat font-bold text-sm">{{ $totalCount }}</strong> layanan
                </div>
            </div>
        </div>

        <!-- Kanan: Search Bar Cepat & Dropdown Urutkan -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-1 sm:flex-initial justify-end">
            <!-- Search Input Ringkas -->
            <div class="relative flex-1 sm:w-56 md:w-60">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="ph-bold ph-magnifying-glass text-xs"></i>
                </span>
                <input type="text"
                       id="catalog-search"
                       oninput="searchCatalog()"
                       placeholder="Cari layanan..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-7 py-2 text-xs font-montserrat text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#ff6b00] focus:bg-white transition-all">
                <button type="button"
                        id="catalog-search-clear"
                        onclick="clearCatalogQuery()"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 hidden">
                    <i class="ph-bold ph-x text-xs"></i>
                </button>
            </div>

            <!-- Dropdown Sortir E-Commerce -->
            <div class="relative shrink-0">
                <select id="catalog-sort-select"
                        onchange="sortCatalog(this.value)"
                        aria-label="Urutkan katalog layanan"
                        class="w-full sm:w-auto bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2 text-xs font-montserrat font-semibold text-slate-700 hover:text-slate-900 focus:outline-none focus:border-[#ff6b00] focus:bg-white cursor-pointer transition-all pr-7 appearance-none">
                    <option value="default">Paling Sesuai</option>
                    <option value="price-asc">Harga: Termurah</option>
                    <option value="price-desc">Harga: Termahal</option>
                    <option value="rating-desc">Rating Tertinggi</option>
                    <option value="name-asc">Nama: A - Z</option>
                </select>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                    <i class="ph-bold ph-caret-down text-xs"></i>
                </span>
            </div>
        </div>

    </div>

    <!-- Baris Kategori Kendaraan & Layanan Cepat (Horizontal Scrollable Chips untuk Mobile & Desktop) -->
    <div class="pt-2.5 border-t border-slate-100 flex items-center gap-2 overflow-x-auto no-scrollbar py-1 -mx-3 px-3 sm:mx-0 sm:px-0" id="quick-vehicle-filter-chips">
        <button type="button"
                onclick="setVehicleFilter('all')"
                data-quick-vehicle="all"
                class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-bold whitespace-nowrap transition-all bg-[#ff6b00] text-white shadow-sm flex items-center gap-1.5 shrink-0 active:scale-95">
            <span>🏁 Semua ({{ $totalCount }})</span>
        </button>
        <button type="button"
                onclick="setVehicleFilter('mobil')"
                data-quick-vehicle="mobil"
                class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-semibold whitespace-nowrap transition-all bg-slate-100 border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-slate-900 flex items-center gap-1.5 shrink-0 active:scale-95">
            <span>🚗 Mobil ({{ $mobilCount }})</span>
        </button>
        <button type="button"
                onclick="setVehicleFilter('motor')"
                data-quick-vehicle="motor"
                class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-semibold whitespace-nowrap transition-all bg-slate-100 border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-slate-900 flex items-center gap-1.5 shrink-0 active:scale-95">
            <span>🛵 Motor ({{ $motorCount }})</span>
        </button>
        <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}"
           target="_blank" rel="noopener noreferrer"
           data-quick-cat="jasa-pasang"
           class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-bold whitespace-nowrap transition-all bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 flex items-center gap-1.5 shrink-0 active:scale-95">
            <i class="ph-bold ph-whatsapp-logo text-emerald-600"></i>
            <span>Jasa Pasang (WA)</span>
        </a>
        <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
           target="_blank" rel="noopener noreferrer"
           data-quick-cat="custom"
           class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-bold whitespace-nowrap transition-all bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 flex items-center gap-1.5 shrink-0 active:scale-95">
            <i class="ph-bold ph-paint-brush text-purple-600"></i>
            <span>Custom Request (WA)</span>
        </a>
        <button type="button"
                onclick="setServiceFilter('wrapping')"
                data-quick-cat="wrapping"
                class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-semibold whitespace-nowrap transition-all bg-slate-100 border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-slate-900 flex items-center gap-1.5 shrink-0 active:scale-95">
            <span>Full Wrap</span>
        </button>
        <button type="button"
                onclick="setServiceFilter('aksesoris')"
                data-quick-cat="aksesoris"
                class="quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-semibold whitespace-nowrap transition-all bg-slate-100 border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-slate-900 flex items-center gap-1.5 shrink-0 active:scale-95">
            <span>Aksesoris &amp; Film</span>
        </button>
    </div>

    <!-- Active Filter Indicator Bar (Menampilkan tag filter yang sedang aktif) -->
    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
        <div class="flex items-center gap-1.5 flex-wrap">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-400">Filter Aktif:</span>
            <div id="catalog-active-chips" class="flex items-center gap-1.5 flex-wrap">
                <span class="px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                    Semua Produk
                </span>
            </div>
        </div>

        <button type="button"
                id="catalog-clear-all-btn"
                onclick="resetAllKatalogFilters()"
                class="hidden text-[#ff6b00] hover:underline text-[11px] font-montserrat font-bold">
            Hapus Semua Filter ×
        </button>
    </div>
</div>

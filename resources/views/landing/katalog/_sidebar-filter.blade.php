{{-- ============================================
    BAGIAN: Sidebar Filter E-Commerce Minimalis
    Deskripsi: Panel filter kiri ramping & sticky di desktop & tablet,
               dengan dropdown accordion sub-kategori pada Mobil & Motor
============================================ --}}
@php
    $totalCount = $layanan->count();
    $mobilCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor')->count();
    $motorCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor')->count();

    // Hitung per jenis layanan Mobil
    $mobilWrapCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        str_contains(strtolower($l->tipe_paket ?? ''), 'wrapping') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'full wrap') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'variasi')
    ))->count();

    $mobilJasaCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        ($l->jenis_layanan === 'jasa_pasang') || 
        str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'jasa pasang')
    ))->count();

    $mobilCustomCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        $l->jenis_layanan !== 'jasa_pasang' &&
        !str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') &&
        (
            str_contains(strtolower($l->tipe_paket ?? ''), 'custom') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'custom') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'decal')
        )
    ))->count();

    $mobilAksesorisCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        in_array(strtolower($l->tipe_paket ?? ''), ['window-film', 'lighting', 'audio']) || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'kaca film') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'lampu') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'audio')
    ))->count();

    // Hitung per jenis layanan Motor
    $motorWrapCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        str_contains(strtolower($l->tipe_paket ?? ''), 'wrapping') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'full wrap')
    ))->count();

    $motorJasaCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        ($l->jenis_layanan === 'jasa_pasang') || 
        str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'jasa pasang')
    ))->count();

    $motorCustomCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        $l->jenis_layanan !== 'jasa_pasang' &&
        !str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') &&
        (
            str_contains(strtolower($l->tipe_paket ?? ''), 'custom') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'striping') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'striping') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'custom')
        )
    ))->count();
@endphp

<!-- Mobile Filter Backdrop (Hidden on Desktop & Tablet) -->
<div id="filter-backdrop" 
     onclick="toggleMobileFilter(false)"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300">
</div>

<!-- Sidebar Filter Container -->
<aside id="catalog-sidebar"
       class="w-full md:w-64 lg:w-72 xl:w-80 shrink-0 md:sticky md:top-24 self-start space-y-4
              fixed md:static inset-y-0 left-0 z-50 md:z-auto bg-white md:bg-transparent
              p-5 md:p-0 overflow-y-auto max-h-screen md:max-h-none -translate-x-full md:translate-x-0
              transition-transform duration-300 ease-in-out">

    <!-- Card Filter Utama -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4.5">

        <!-- Header Filter -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-sliders-horizontal text-[#ff6b00] text-base"></i>
                <h3 class="text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider">
                    Filter Katalog
                </h3>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="resetAllKatalogFilters()"
                        class="text-xs font-montserrat font-semibold text-slate-500 hover:text-[#ff6b00] transition-colors flex items-center gap-1.5">
                    <i class="ph-bold ph-arrow-counter-clockwise text-xs"></i>
                    <span>Reset</span>
                </button>
                <!-- Close Button on Mobile -->
                <button type="button" 
                        onclick="toggleMobileFilter(false)"
                        class="md:hidden w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>
        </div>

        <!-- 1. KATEGORI KENDARAAN (DENGAN DROPDOWN ACCORDION SUB-LAYANAN) -->
        <div class="space-y-2.5">
            <span class="text-xs sm:text-[13px] font-montserrat font-bold text-slate-800 uppercase tracking-wider block">
                Kategori Kendaraan:
            </span>

            <div class="space-y-2" id="filter-vehicle-group">
                <!-- 1.1 Semua Kendaraan -->
                <button type="button" 
                        onclick="setVehicleFilter('all')" 
                        data-filter-vehicle="all"
                        class="filter-vehicle-btn w-full px-3.5 py-2.5 rounded-xl text-sm font-montserrat font-bold transition-all flex items-center justify-between
                               bg-[#ff6b00] text-white shadow-md shadow-orange-500/20">
                    <div class="flex items-center gap-2.5">
                        <span>Semua Kendaraan</span>
                    </div>
                    <span class="filter-count text-xs px-2.5 py-0.5 rounded-full bg-white/20 text-white font-mono font-bold">
                        {{ $totalCount }}
                    </span>
                </button>

                <!-- 1.2 MOBIL (DROPDOWN ACCORDION) -->
                <div class="rounded-xl border border-slate-200/80 bg-slate-50/60 overflow-hidden transition-all duration-300" id="accordion-wrapper-mobil">
                    <!-- Tombol Utama Mobil -->
                    <button type="button" 
                            onclick="toggleVehicleAccordion('mobil')" 
                            data-filter-vehicle="mobil"
                            class="filter-vehicle-btn w-full px-3.5 py-2.5 text-sm font-montserrat font-semibold transition-all flex items-center justify-between text-slate-700 hover:text-slate-900 hover:bg-slate-100/80">
                        <div class="flex items-center gap-2.5">
                            <span class="font-bold">Mobil</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="filter-count text-xs px-2.5 py-0.5 rounded-full bg-slate-200/80 text-slate-600 font-mono">
                                {{ $mobilCount }}
                            </span>
                            <i class="ph-bold ph-caret-down text-sm transition-transform duration-300 text-slate-500" id="chevron-mobil"></i>
                        </div>
                    </button>

                    <!-- Sub-Layanan Dropdown Mobil -->
                    <div id="dropdown-sub-mobil" class="hidden border-t border-slate-200/80 bg-white p-2 space-y-1">
                        <!-- Semua Mobil -->
                        <button type="button"
                                onclick="setVehicleCategory('mobil', 'all')"
                                data-sub-vehicle="mobil" data-sub-cat="all"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Semua Layanan Mobil</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $mobilCount }}</span>
                        </button>

                        <!-- Full Wrapping Body Mobil -->
                        <button type="button"
                                onclick="setVehicleCategory('mobil', 'wrapping')"
                                data-sub-vehicle="mobil" data-sub-cat="wrapping"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Full Wrapping Body</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $mobilWrapCount }}</span>
                        </button>

                        <!-- Jasa Pasang Mobil -->
                        <button type="button"
                                onclick="setVehicleCategory('mobil', 'jasa-pasang')"
                                data-category="jasa-pasang"
                                data-sub-vehicle="mobil" data-sub-cat="jasa-pasang"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Jasa Pasang Saja</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $mobilJasaCount }}</span>
                        </button>

                        <!-- Custom Decal Mobil -->
                        <button type="button"
                                onclick="setVehicleCategory('mobil', 'custom')"
                                data-sub-vehicle="mobil" data-sub-cat="custom"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Custom </span>
                            <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                        </button>

                        <!-- Aksesoris Mobil -->
                        <button type="button"
                                onclick="setVehicleCategory('mobil', 'aksesoris')"
                                data-sub-vehicle="mobil" data-sub-cat="aksesoris"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Aksesoris</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $mobilAksesorisCount }}</span>
                        </button>
                    </div>
                </div>

                <!-- 1.3 MOTOR (DROPDOWN ACCORDION) -->
                <div class="rounded-xl border border-slate-200/80 bg-slate-50/60 overflow-hidden transition-all duration-300" id="accordion-wrapper-motor">
                    <!-- Tombol Utama Motor -->
                    <button type="button" 
                            onclick="toggleVehicleAccordion('motor')" 
                            data-filter-vehicle="motor"
                            class="filter-vehicle-btn w-full px-3.5 py-2.5 text-sm font-montserrat font-semibold transition-all flex items-center justify-between text-slate-700 hover:text-slate-900 hover:bg-slate-100/80">
                        <div class="flex items-center gap-2.5">
                            <span class="font-bold">Motor</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="filter-count text-xs px-2.5 py-0.5 rounded-full bg-slate-200/80 text-slate-600 font-mono">
                                {{ $motorCount }}
                            </span>
                            <i class="ph-bold ph-caret-down text-sm transition-transform duration-300 text-slate-500" id="chevron-motor"></i>
                        </div>
                    </button>

                    <!-- Sub-Layanan Dropdown Motor -->
                    <div id="dropdown-sub-motor" class="hidden border-t border-slate-200/80 bg-white p-2 space-y-1">
                        <!-- Semua Motor -->
                        <button type="button"
                                onclick="setVehicleCategory('motor', 'all')"
                                data-sub-vehicle="motor" data-sub-cat="all"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Semua Layanan Motor</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $motorCount }}</span>
                        </button>

                        <!-- Full Wrap Motor -->
                        <button type="button"
                                onclick="setVehicleCategory('motor', 'wrapping')"
                                data-sub-vehicle="motor" data-sub-cat="wrapping"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Full Wrap Motor</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $motorWrapCount }}</span>
                        </button>

                        <!-- Jasa Pasang Decal Motor -->
                        <button type="button"
                                onclick="setVehicleCategory('motor', 'jasa-pasang')"
                                data-category="jasa-pasang"
                                data-sub-vehicle="motor" data-sub-cat="jasa-pasang"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Jasa Pasang Decal</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $motorJasaCount }}</span>
                        </button>

                        <!-- Striping & Custom Motor -->
                        <button type="button"
                                onclick="setVehicleCategory('motor', 'custom')"
                                data-sub-vehicle="motor" data-sub-cat="custom"
                                class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                            <span>• Custom </span>
                            <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. FILTER RENTANG HARGA -->
        <div class="space-y-2.5 pt-3.5 border-t border-slate-100">
            <span class="text-xs sm:text-[13px] font-montserrat font-bold text-slate-800 uppercase tracking-wider block">
                Rentang Harga:
            </span>

            <div class="space-y-1.5" id="filter-price-group">
                <button type="button"
                        onclick="setPriceFilter('all')"
                        data-filter-price="all"
                        class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                               bg-orange-50 text-[#ff6b00] border border-orange-300 font-bold flex items-center justify-between">
                    <span>Semua Harga</span>
                    <i class="ph-bold ph-check text-sm"></i>
                </button>

                <button type="button"
                        onclick="setPriceFilter('under-500k')"
                        data-filter-price="under-500k"
                        class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                               bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
                    <span>&lt; Rp 500.000</span>
                    <span class="text-xs text-slate-400 font-mono">Ekonomis</span>
                </button>

                <button type="button"
                        onclick="setPriceFilter('500k-2m')"
                        data-filter-price="500k-2m"
                        class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                               bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
                    <span>Rp 500rb - 2 Jt</span>
                    <span class="text-xs text-slate-400 font-mono">Menengah</span>
                </button>

                <button type="button"
                        onclick="setPriceFilter('above-2m')"
                        data-filter-price="above-2m"
                        class="filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat text-left transition-all
                               bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between">
                    <span>&gt; Rp 2.000.000</span>
                    <span class="text-xs text-slate-400 font-mono">Premium</span>
                </button>
            </div>
        </div>

        <!-- 3. KONSULTASI & NEGOSIASI CUSTOM WA -->
        @php
            $rawWaSidebar = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
            if (str_starts_with($rawWaSidebar, '0')) {
                $rawWaSidebar = '62' . substr($rawWaSidebar, 1);
            }
        @endphp
        <div class="pt-3.5 border-t border-slate-100">
            <div class="p-3.5 rounded-2xl bg-orange-50/50 border border-orange-200/80 hover:border-[#ff6b00]/50 transition-all space-y-2.5 shadow-sm">
                <div class="flex items-center gap-2 text-xs font-montserrat font-bold text-slate-900">
                    <i class="ph-bold ph-chats-circle text-[#ff6b00] text-base"></i>
                    <span>Ingin Konsep Custom?</span>
                </div>
                <p class="text-[11px] font-questrial text-slate-600 leading-relaxed">
                    Pengerjaan custom dibuat khusus sesuai konsep &amp; ukuran kendaraan Anda. Hubungi kami via WhatsApp untuk konsultasi desain dan estimasi biaya!
                </p>
                <a href="https://wa.me/{{ $rawWaSidebar }}?text={{ urlencode('Halo Tim Dantie Stiker, saya ingin konsultasi desain & estimasi biaya untuk proyek Custom kendaraan saya...') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white text-xs font-montserrat font-bold uppercase tracking-wider shadow-sm transition-all active:scale-95 shadow-orange-500/20">
                    <i class="ph-bold ph-whatsapp-logo text-base"></i>
                    <span>Konsultasi via WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- 4. JAMINAN WORKSHOP -->
        <div class="pt-3.5 border-t border-slate-100">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                <div class="flex items-center gap-2 text-xs sm:text-sm font-montserrat font-bold text-slate-800">
                    <i class="ph-fill ph-seal-check text-[#ff6b00] text-base"></i>
                    <span>Garansi Workshop</span>
                </div>
                <div class="text-xs sm:text-[13px] font-questrial text-slate-600 space-y-1">
                    <div>✓ 100% Bebas Gelembung</div>
                    <div>✓ Garansi Pemasangan Resmi</div>
                </div>
            </div>
        </div>

    </div>
</aside>

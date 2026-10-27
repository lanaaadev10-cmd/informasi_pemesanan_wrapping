{{-- ============================================
    BAGIAN: Filter Sidebar - Kategori Kendaraan & Sub-Layanan
============================================ --}}
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
                <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}"
                   target="_blank" rel="noopener noreferrer"
                   data-category="jasa-pasang"
                   data-sub-vehicle="mobil" data-sub-cat="jasa-pasang"
                   title="Konsultasi & Reservasi Jasa Pasang via WhatsApp Resmi"
                   class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                    <span>• Jasa Pasang Saja</span>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                </a>

                <!-- Custom Decal Mobil -->
                <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
                   target="_blank" rel="noopener noreferrer"
                   data-sub-vehicle="mobil" data-sub-cat="custom"
                   title="Konsultasi Desain & Negosiasi Custom via WhatsApp Resmi"
                   class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                    <span>• Custom </span>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                </a>

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
                <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}"
                   target="_blank" rel="noopener noreferrer"
                   data-category="jasa-pasang"
                   data-sub-vehicle="motor" data-sub-cat="jasa-pasang"
                   title="Konsultasi & Reservasi Jasa Pasang via WhatsApp Resmi"
                   class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                    <span>• Jasa Pasang Saja</span>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                </a>

                <!-- Striping & Custom Motor -->
                <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
                   target="_blank" rel="noopener noreferrer"
                   data-sub-vehicle="motor" data-sub-cat="custom"
                   title="Konsultasi Desain & Negosiasi Custom via WhatsApp Resmi"
                   class="sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                    <span>• Custom </span>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Via WA</span>
                </a>
            </div>
        </div>
    </div>
</div>

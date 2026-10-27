{{-- ═══════════════════════════════════════════════════════════════════
     FILTER & ASISTEN PINTAR KATALOG LAYANAN (SERVICES SECTION HEADER)
     Desain Minimalis, Rapi, & Clean (Sesuai Referensi Wireframe)
═══════════════════════════════════════════════════════════════════ --}}
<div class="space-y-6 sm:space-y-8" id="layanan-filter-section">

    {{-- Variasi Heading Services: Left Title with Category Indicator & Right Description --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-xs font-black uppercase tracking-widest text-[#ea580c] block mb-1">
                Katalog Biaya &amp; Jasa Pengerjaan
            </span>
            <div class="flex items-center gap-3">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight uppercase">
                    Services
                </h2>
                <div class="h-8 w-[2px] bg-slate-300 hidden sm:block"></div>
                <span class="text-xs sm:text-sm font-semibold text-slate-400 hidden sm:block">Motor &amp; Mobil</span>
            </div>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 max-w-md font-normal leading-relaxed">
            Daftar paket pengerjaan terstruktur dengan transparansi estimasi tarif dan opsi jasa pasang bawa bahan sendiri.
        </p>
    </div>

    {{-- Filter Panel Box: Simple, Clean & Minimalis --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-4 sm:p-5 shadow-sm space-y-4">
        
        {{-- Baris Filter: Tipe Kendaraan & Jenis Kebutuhan --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            
            {{-- Pilihan Kendaraan (Segmented Pill Bersih) --}}
            <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-full w-fit shrink-0">
                <button type="button" onclick="setPublicVehicleFilter('all')" data-vehicle-btn="all"
                        class="vehicle-filter-btn active-vehicle px-4 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer">
                    <span>Semua</span>
                </button>

                <button type="button" onclick="setPublicVehicleFilter('motor')" data-vehicle-btn="motor"
                        class="vehicle-filter-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                    <span>🛵 Motor</span>
                </button>

                <button type="button" onclick="setPublicVehicleFilter('mobil')" data-vehicle-btn="mobil"
                        class="vehicle-filter-btn px-4 py-1.5 rounded-full text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5">
                    <span>🚗 Mobil</span>
                </button>
            </div>

            {{-- Pilihan Kebutuhan (Pills Rapi Tanpa Badge Berlapis) --}}
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                <button type="button" onclick="setPublicScopeFilter('all')" data-scope-btn="all"
                        class="scope-filter-btn active-scope px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap cursor-pointer">
                    Semua Kebutuhan
                </button>

                <button type="button" onclick="setPublicScopeFilter('wrapping')" data-scope-btn="wrapping"
                        class="scope-filter-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap cursor-pointer">
                    Full Wrap
                </button>

                <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}" target="_blank" rel="noopener noreferrer"
                   data-scope-btn="jasa-pasang"
                   title="Konsultasi Jasa Pasang via WhatsApp Resmi"
                   class="scope-filter-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap cursor-pointer inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200">
                    <i class="ph-bold ph-whatsapp-logo text-emerald-600"></i>
                    <span>Jasa Pasang Stiker</span>
                </a>

                <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}" target="_blank" rel="noopener noreferrer"
                   data-scope-btn="partial"
                   title="Konsultasi Desain & Negosiasi Custom via WhatsApp Resmi"
                   class="scope-filter-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap cursor-pointer inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200">
                    <i class="ph-bold ph-whatsapp-logo text-emerald-600"></i>
                    <span>Custom</span>
                </a>

                <button type="button" onclick="setPublicScopeFilter('aksesoris')" data-scope-btn="aksesoris"
                        class="scope-filter-btn px-3.5 py-1.5 rounded-full text-xs font-bold transition-all whitespace-nowrap cursor-pointer">
                    Aksesoris &amp; Film
                </button>
            </div>
        </div>

        {{-- Baris Live Search & Counter Hasil --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="relative flex-1">
                <i class="ph-bold ph-magnifying-glass text-slate-400 text-sm absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="public-layanan-search" oninput="searchPublicLayanan()"
                       placeholder="Cari layanan (Beat, Vario, Maxi, Innova, Atap, Oracal)..."
                       class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-slate-400 rounded-xl pl-9 pr-9 py-2 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none transition-all">
                <button type="button" id="btn-clear-search" onclick="clearPublicLayananSearch()"
                        class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 cursor-pointer">
                    <i class="ph-bold ph-x-circle text-base"></i>
                </button>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-3 text-xs text-slate-500 px-1">
                <div>
                    Menampilkan <strong id="count-visible-services" class="font-bold text-slate-900">0</strong> layanan
                </div>

                <button type="button" onclick="resetPublicLayananFilters()" id="btn-reset-filter"
                        class="hidden items-center gap-1 text-[11px] font-bold text-[#ea580c] hover:underline cursor-pointer">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

    </div>

</div>



<script>
    let activeVehicleFilter = 'all';
    let activeScopeFilter = 'all';
    let publicSearchQuery = '';

    function applyPublicLayananFilters() {
        const cards = document.querySelectorAll('.public-service-card');
        let visibleCount = 0;
        let motorVisible = 0;
        let mobilVisible = 0;

        cards.forEach(card => {
            const vehicle = (card.getAttribute('data-vehicle') || '').toLowerCase();
            const scope = (card.getAttribute('data-scope') || '').toLowerCase();
            const keywords = (card.getAttribute('data-keywords') || '').toLowerCase();

            // Match Vehicle (All / Motor / Mobil)
            const matchVehicle = (activeVehicleFilter === 'all') || (vehicle === activeVehicleFilter);

            // Match Scope (All / Jasa Pasang / Wrapping / Specialist)
            const matchScope = (activeScopeFilter === 'all') || (scope === activeScopeFilter);

            // Match Search
            const matchSearch = (publicSearchQuery === '') || keywords.includes(publicSearchQuery);

            if (matchVehicle && matchScope && matchSearch) {
                card.style.display = '';
                visibleCount++;
                if (vehicle === 'motor') {
                    motorVisible++;
                } else {
                    mobilVisible++;
                }
            } else {
                card.style.display = 'none';
            }
        });

        // Kontrol tampilan seksi Motor & Mobil
        const secMotor = document.getElementById('section-motor');
        if (secMotor) {
            secMotor.style.display = (activeVehicleFilter === 'mobil' || motorVisible === 0) ? 'none' : '';
        }
        const secMobil = document.getElementById('section-mobil');
        if (secMobil) {
            secMobil.style.display = (activeVehicleFilter === 'motor' || mobilVisible === 0) ? 'none' : '';
        }

        // Update count
        const countEl = document.getElementById('count-visible-services');
        if (countEl) countEl.textContent = visibleCount;
        const countMotorEl = document.getElementById('count-motor-services');
        if (countMotorEl) countMotorEl.textContent = motorVisible;
        const countMobilEl = document.getElementById('count-mobil-services');
        if (countMobilEl) countMobilEl.textContent = mobilVisible;

        // Toggle reset button
        const resetBtn = document.getElementById('btn-reset-filter');
        if (resetBtn) {
            const isFiltering = activeVehicleFilter !== 'all' || activeScopeFilter !== 'all' || publicSearchQuery !== '';
            resetBtn.style.display = isFiltering ? 'inline-flex' : 'none';
        }

        // Empty state
        const emptyState = document.getElementById('public-services-empty');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function setPublicVehicleFilter(vehicle) {
        activeVehicleFilter = vehicle;
        document.querySelectorAll('.vehicle-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-vehicle-btn') === vehicle) {
                btn.classList.add('active-vehicle');
            } else {
                btn.classList.remove('active-vehicle');
            }
        });
        applyPublicLayananFilters();
    }

    function setPublicScopeFilter(scope) {
        if (scope === 'jasa-pasang') {
            window.open('{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}', '_blank');
            return;
        }
        if (scope === 'partial' || scope === 'custom') {
            window.open('{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}', '_blank');
            return;
        }
        activeScopeFilter = scope;
        document.querySelectorAll('.scope-filter-btn').forEach(btn => {
            if (btn.getAttribute('data-scope-btn') === scope) {
                btn.classList.add('active-scope');
            } else {
                btn.classList.remove('active-scope');
            }
        });
        applyPublicLayananFilters();
    }

    function searchPublicLayanan() {
        const input = document.getElementById('public-layanan-search');
        const clearBtn = document.getElementById('btn-clear-search');
        publicSearchQuery = (input ? input.value : '').toLowerCase().trim();

        if (clearBtn) {
            clearBtn.style.display = publicSearchQuery.length > 0 ? 'block' : 'none';
        }
        applyPublicLayananFilters();
    }

    function clearPublicLayananSearch() {
        const input = document.getElementById('public-layanan-search');
        if (input) input.value = '';
        searchPublicLayanan();
    }

    function resetPublicLayananFilters() {
        activeVehicleFilter = 'all';
        activeScopeFilter = 'all';
        publicSearchQuery = '';
        const input = document.getElementById('public-layanan-search');
        if (input) input.value = '';

        document.querySelectorAll('.vehicle-filter-btn').forEach(btn => {
            btn.classList.toggle('active-vehicle', btn.getAttribute('data-vehicle-btn') === 'all');
        });
        document.querySelectorAll('.scope-filter-btn').forEach(btn => {
            btn.classList.toggle('active-scope', btn.getAttribute('data-scope-btn') === 'all');
        });

        applyPublicLayananFilters();
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyPublicLayananFilters();
    });
</script>

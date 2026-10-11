{{-- ============================================
    BAGIAN: Filter & Asisten Pintar Layanan Publik
    Deskripsi: Filter interaktif untuk Tipe Kendaraan (Motor / Mobil)
    dan Jenis Kebutuhan (Jasa Pasang Saja vs Full Wrap All-In)
============================================ --}}
<div class="space-y-6" id="layanan-filter-section">

    <div class="bg-gradient-to-br from-[#18181c] to-[#0f0f13] border border-white/10 rounded-2xl sm:rounded-3xl p-3.5 sm:p-7 shadow-2xl relative overflow-hidden space-y-4 sm:space-y-5">
        {{-- Glow Accent --}}
        <div class="absolute -right-20 -top-20 w-60 h-60 bg-[var(--accent)]/10 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Header Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-4 border-b border-white/10 pb-3 sm:pb-4 relative z-10">
            <div>
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--accent)] animate-pulse"></span>
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-[var(--accent)]">Eksplorasi Katalog Lengkap</span>
                </div>
                <h3 class="text-lg sm:text-2xl font-black text-white tracking-tight">
                    Filter Berdasarkan Kendaraan &amp; Kebutuhan
                </h3>
            </div>

            <button type="button" onclick="resetPublicLayananFilters()" id="btn-reset-filter"
                    class="hidden items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-[11px] font-bold text-gray-300 hover:text-white transition-all shadow-sm shrink-0 self-start sm:self-auto">
                <i class="ph-bold ph-arrow-counter-clockwise text-xs text-[var(--accent)]"></i>
                <span>Reset Filter</span>
            </button>
        </div>

        {{-- 1. Pilihan Kendaraan Utama (Motor vs Mobil vs Semua) - 3 Segmented Buttons di HP --}}
        <div class="space-y-1.5 sm:space-y-2 relative z-10">
            <label class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-[var(--accent)] text-black text-[9px] flex items-center justify-center font-black">1</span>
                <span>Tipe Kendaraan:</span>
            </label>
            <div class="grid grid-cols-3 gap-1.5 sm:gap-3">
                <button type="button" onclick="setPublicVehicleFilter('all')" data-vehicle-btn="all"
                        class="vehicle-filter-btn active-vehicle flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1 sm:gap-3 p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all duration-300 text-center sm:text-left">
                    <span class="text-base sm:text-2xl">🏁</span>
                    <div>
                        <div class="text-[11px] sm:text-xs font-black text-white">Semua</div>
                        <div class="text-[9px] text-gray-400 font-light hidden sm:block">Motor &amp; mobil</div>
                    </div>
                </button>

                <button type="button" onclick="setPublicVehicleFilter('motor')" data-vehicle-btn="motor"
                        class="vehicle-filter-btn flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1 sm:gap-3 p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all duration-300 text-center sm:text-left">
                    <span class="text-base sm:text-2xl">🛵</span>
                    <div>
                        <div class="text-[11px] sm:text-xs font-black text-white">Motor</div>
                        <div class="text-[9px] text-gray-400 font-light hidden sm:block">Bebek, Matic &amp; Sport</div>
                    </div>
                </button>

                <button type="button" onclick="setPublicVehicleFilter('mobil')" data-vehicle-btn="mobil"
                        class="vehicle-filter-btn flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1 sm:gap-3 p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl border transition-all duration-300 text-center sm:text-left">
                    <span class="text-base sm:text-2xl">🚗</span>
                    <div>
                        <div class="text-[11px] sm:text-xs font-black text-white">Mobil</div>
                        <div class="text-[9px] text-gray-400 font-light hidden sm:block">City Car, MPV &amp; SUV</div>
                    </div>
                </button>
            </div>
        </div>

        {{-- 2. Pilihan Kebutuhan (Jasa Pasang vs Paket All-In vs Spesialis) --}}
        <div class="space-y-1.5 sm:space-y-2 relative z-10 pt-1">
            <label class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-[var(--accent)] text-black text-[9px] flex items-center justify-center font-black">2</span>
                <span>Jenis Kebutuhan:</span>
            </label>
            <div class="flex gap-1.5 sm:gap-2 overflow-x-auto pb-1 pt-0.5 no-scrollbar sm:flex-wrap">
                <button type="button" onclick="setPublicScopeFilter('all')" data-scope-btn="all"
                        class="scope-filter-btn active-scope px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-bold border transition-all duration-300 whitespace-nowrap shrink-0">
                    Semua Kebutuhan
                </button>

                <button type="button" onclick="setPublicScopeFilter('wrapping')" data-scope-btn="wrapping"
                        class="scope-filter-btn px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-bold border transition-all duration-300 whitespace-nowrap shrink-0 flex items-center gap-1.5">
                    <span>📦 Full Wrap All-In</span>
                    <span class="text-[8px] sm:text-[9px] bg-[var(--accent)]/20 text-[var(--accent)] border border-[var(--accent)]/40 px-1.5 py-0.5 rounded-full uppercase">Bahan Toko</span>
                </button>

                <button type="button" onclick="setPublicScopeFilter('jasa-pasang')" data-scope-btn="jasa-pasang"
                        class="scope-filter-btn px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-bold border transition-all duration-300 whitespace-nowrap shrink-0 flex items-center gap-1.5">
                    <span>✂️ Jasa Pasang Saja</span>
                    <span class="text-[8px] sm:text-[9px] bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 px-1.5 py-0.5 rounded-full uppercase">Bawa Bahan</span>
                </button>

                <button type="button" onclick="setPublicScopeFilter('partial')" data-scope-btn="partial"
                        class="scope-filter-btn px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-bold border transition-all duration-300 whitespace-nowrap shrink-0 flex items-center gap-1.5">
                    <span>🎨 Custom</span>
                    <span class="text-[8px] sm:text-[9px] bg-purple-500/20 text-purple-300 border border-purple-500/40 px-1.5 py-0.5 rounded-full uppercase">Atap / Striping</span>
                </button>

                <button type="button" onclick="setPublicScopeFilter('aksesoris')" data-scope-btn="aksesoris"
                        class="scope-filter-btn px-3.5 py-1.5 sm:px-5 sm:py-2.5 rounded-full text-[11px] sm:text-xs font-bold border transition-all duration-300 whitespace-nowrap shrink-0 flex items-center gap-1.5">
                    <span>💡 Aksesoris &amp; Film</span>
                    <span class="text-[8px] sm:text-[9px] bg-sky-500/20 text-sky-400 border border-sky-500/40 px-1.5 py-0.5 rounded-full uppercase">Lampu / Audio</span>
                </button>
            </div>
        </div>

        {{-- 3. Live Search Field --}}
        <div class="pt-2 border-t border-white/10 relative z-10 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="relative flex-1">
                <i class="ph-bold ph-magnifying-glass text-gray-400 text-sm absolute left-4 top-1/2 -translate-y-1/2 text-[var(--accent)]"></i>
                <input type="text" id="public-layanan-search" oninput="searchPublicLayanan()"
                       placeholder="Cari berdasarkan model (misal: Beat, Vario, NMAX, PCX, Brio, Innova, Atap, Oracal)..."
                       class="w-full bg-[#121215] border border-white/15 focus:border-[var(--accent)] rounded-2xl pl-10 pr-10 py-3 text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[var(--accent)] transition-all">
                <button type="button" id="btn-clear-search" onclick="clearPublicLayananSearch()"
                        class="hidden absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                    <i class="ph-bold ph-x-circle text-base"></i>
                </button>
            </div>

            <div class="text-[11px] text-gray-400 font-light flex items-center justify-between sm:justify-end gap-3 px-1">
                <div>
                    Menampilkan <span id="count-visible-services" class="font-extrabold text-white">0</span> layanan
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Styling Tombol Filter Kendaraan */
    .vehicle-filter-btn {
        background-color: #141417;
        border-color: rgba(255, 255, 255, 0.1);
    }
    .vehicle-filter-btn:hover {
        border-color: rgba(242, 153, 74, 0.4);
        background-color: #1a1a20;
    }
    .vehicle-filter-btn.active-vehicle {
        background: linear-gradient(135deg, rgba(242, 153, 74, 0.18), rgba(20, 20, 24, 0.95));
        border-color: var(--accent);
        box-shadow: 0 0 20px rgba(242, 153, 74, 0.2);
    }

    /* Styling Tombol Filter Kebutuhan */
    .scope-filter-btn {
        background-color: rgba(255, 255, 255, 0.05);
        color: #9ca3af;
        border-color: rgba(255, 255, 255, 0.1);
    }
    .scope-filter-btn:hover {
        color: #ffffff;
        border-color: rgba(242, 153, 74, 0.4);
    }
    .scope-filter-btn.active-scope {
        background-color: var(--accent);
        color: #000000;
        border-color: var(--accent);
        box-shadow: 0 4px 15px rgba(242, 153, 74, 0.35);
    }
</style>

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

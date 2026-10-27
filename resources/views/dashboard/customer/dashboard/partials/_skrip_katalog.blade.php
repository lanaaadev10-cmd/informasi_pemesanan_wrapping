{{-- ==============================================================
     LOGIKA JAVASCRIPT: 2-TIER CASCADING SELECTOR STATE ENGINE
============================================================== --}}
<script>
    let currentVehicle = 'all';
    let currentPillar  = 'all';

    function selectVehicleTier(vehicle, el) {
        currentVehicle = vehicle;
        updateTier1UI();
        applyCascadingFilter();
        updateTier2DynamicCounts();
        syncUrlFilterParams();
    }

    function selectPillarTier(pillar, el) {
        currentPillar = pillar;
        updateTier2UI();
        applyCascadingFilter();
        syncUrlFilterParams();
    }

    function resetCascadingFilters() {
        currentVehicle = 'all';
        currentPillar  = 'all';
        updateTier1UI();
        updateTier2UI();
        applyCascadingFilter();
        updateTier2DynamicCounts();
        syncUrlFilterParams();
    }

    function updateTier1UI() {
        const buttons = document.querySelectorAll('.tier1-btn');
        buttons.forEach(btn => {
            const v = btn.getAttribute('data-vehicle');
            const isMatch = (v === currentVehicle);
            btn.setAttribute('aria-selected', isMatch ? 'true' : 'false');

            if (isMatch) {
                btn.className = 'tier1-btn px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs font-montserrat font-bold transition-all duration-200 flex items-center justify-center gap-1.5 active:scale-95 bg-[#ff6b00] text-white shadow-sm shadow-orange-500/20';
            } else {
                btn.className = 'tier1-btn px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs font-montserrat font-medium transition-all duration-200 flex items-center justify-center gap-1.5 active:scale-95 bg-transparent text-slate-700 hover:text-[#ff6b00] hover:bg-white/60';
            }
        });

        const labelEl = document.getElementById('current-vehicle-text');
        if (labelEl) {
            if (currentVehicle === 'mobil') labelEl.textContent = 'Mobil 🚗';
            else if (currentVehicle === 'motor') labelEl.textContent = 'Motor 🛵';
            else labelEl.textContent = 'Semua Kendaraan 🏁';
        }
    }

    function updateTier2UI() {
        const buttons = document.querySelectorAll('.tier2-btn');
        buttons.forEach(btn => {
            const p = btn.getAttribute('data-pillar');
            const isMatch = (p === currentPillar);
            btn.setAttribute('aria-selected', isMatch ? 'true' : 'false');

            const countBadge = btn.querySelector('.tier2-count');

            if (isMatch) {
                btn.className = 'tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-bold transition-all duration-200 bg-[#ff6b00] text-white shadow-sm flex items-center gap-1.5 shrink-0 active:scale-95';
                if (countBadge) {
                    countBadge.className = 'text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-white/25 tier2-count';
                }
            } else {
                btn.className = 'tier2-btn px-3 py-1.5 sm:py-2 rounded-xl text-xs font-montserrat font-medium transition-all duration-200 bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-[#ff6b00] flex items-center gap-1.5 shrink-0 active:scale-95';
                if (countBadge) {
                    countBadge.className = 'text-[10px] font-bold px-1.5 py-0.2 rounded-full bg-slate-100 text-slate-600 tier2-count';
                }
            }
        });
    }

    function applyCascadingFilter() {
        const cards = document.querySelectorAll('.dash-pick-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardVehicle = card.getAttribute('data-vehicle');
            const cardPillar  = card.getAttribute('data-pillar');

            const matchVehicle = (currentVehicle === 'all' || cardVehicle === currentVehicle);
            const matchPillar  = (currentPillar === 'all' || cardPillar === currentPillar);

            if (matchVehicle && matchPillar) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const counterEl = document.getElementById('active-matches-counter');
        if (counterEl) {
            counterEl.textContent = visibleCount;
        }

        const emptyState = document.getElementById('dash-filter-empty');
        const emptyDesc  = document.getElementById('empty-filter-desc');
        const jasaInfo   = document.getElementById('dash-filter-jasa-info');
        const customInfo = document.getElementById('dash-filter-custom-info');

        if (emptyState) emptyState.classList.add('hidden');
        if (jasaInfo) jasaInfo.classList.add('hidden');
        if (customInfo) customInfo.classList.add('hidden');

        if (visibleCount === 0) {
            if (currentPillar === 'jasa-pasang' && jasaInfo) {
                jasaInfo.classList.remove('hidden');
            } else if (currentPillar === 'custom' && customInfo) {
                customInfo.classList.remove('hidden');
            } else if (emptyState) {
                emptyState.classList.remove('hidden');
                if (emptyDesc) {
                    const vName = currentVehicle === 'all' ? 'semua kendaraan' : (currentVehicle === 'mobil' ? 'Mobil' : 'Motor');
                    const pName = currentPillar === 'all' ? 'semua kategori' : currentPillar.replace('-', ' ');
                    emptyDesc.textContent = `Tidak ditemukan produk untuk kombinasi kendaraan "${vName}" dan pilar "${pName}". Silakan ubah filter atau atur ulang.`;
                }
            }
        }
    }

    function updateTier2DynamicCounts() {
        const cards = document.querySelectorAll('.dash-pick-card');
        const counts = {
            'all': 0,
            'jasa-pasang': 0,
            'full-wrap': 0,
            'custom': 0,
            'aksesoris': 0
        };

        cards.forEach(card => {
            const v = card.getAttribute('data-vehicle');
            const p = card.getAttribute('data-pillar');

            if (currentVehicle === 'all' || v === currentVehicle) {
                counts['all']++;
                if (counts[p] !== undefined) {
                    counts[p]++;
                }
            }
        });

        document.querySelectorAll('.tier2-count').forEach(badge => {
            const p = badge.getAttribute('data-pillar-count');
            if (counts[p] !== undefined) {
                badge.textContent = counts[p];
                if (counts[p] === 0 && p !== 'all') {
                    badge.classList.add('opacity-40');
                } else {
                    badge.classList.remove('opacity-40');
                }
            }
        });
    }

    function syncUrlFilterParams() {
        try {
            const url = new URL(window.location.href);
            if (currentVehicle !== 'all') {
                url.searchParams.set('kendaraan', currentVehicle);
            } else {
                url.searchParams.delete('kendaraan');
            }

            if (currentPillar !== 'all') {
                url.searchParams.set('kategori', currentPillar);
            } else {
                url.searchParams.delete('kategori');
            }

            window.history.replaceState({}, '', url.toString());
        } catch (e) {}
    }

    document.addEventListener('DOMContentLoaded', function () {
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const v = urlParams.get('kendaraan');
            const k = urlParams.get('kategori');

            if (v && ['mobil', 'motor', 'all'].includes(v)) {
                currentVehicle = v;
            }
            if (k && ['jasa-pasang', 'full-wrap', 'custom', 'aksesoris', 'all'].includes(k)) {
                currentPillar = k;
            }
        } catch (e) {}

        updateTier1UI();
        updateTier2UI();
        updateTier2DynamicCounts();
        applyCascadingFilter();
    });
</script>

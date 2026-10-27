function toggleVehicleAccordion(vehicle) {
    const subMobil = document.getElementById('dropdown-sub-mobil');
    const subMotor = document.getElementById('dropdown-sub-motor');
    const chevronMobil = document.getElementById('chevron-mobil');
    const chevronMotor = document.getElementById('chevron-motor');

    if (vehicle === 'mobil') {
        const isCurrentlyOpen = subMobil && !subMobil.classList.contains('hidden');
        if (katalogVehicle === 'mobil' && isCurrentlyOpen) {
            subMobil.classList.add('hidden');
            if (chevronMobil) chevronMobil.classList.remove('rotate-180');
        } else {
            if (subMobil) subMobil.classList.remove('hidden');
            if (chevronMobil) chevronMobil.classList.add('rotate-180');
            if (subMotor) subMotor.classList.add('hidden');
            if (chevronMotor) chevronMotor.classList.remove('rotate-180');

            katalogVehicle = 'mobil';
            katalogCategory = 'all';
            applyCatalogFilters();
        }
    } else if (vehicle === 'motor') {
        const isCurrentlyOpen = subMotor && !subMotor.classList.contains('hidden');
        if (katalogVehicle === 'motor' && isCurrentlyOpen) {
            subMotor.classList.add('hidden');
            if (chevronMotor) chevronMotor.classList.remove('rotate-180');
        } else {
            if (subMotor) subMotor.classList.remove('hidden');
            if (chevronMotor) chevronMotor.classList.add('rotate-180');
            if (subMobil) subMobil.classList.add('hidden');
            if (chevronMobil) chevronMobil.classList.remove('rotate-180');

            katalogVehicle = 'motor';
            katalogCategory = 'all';
            applyCatalogFilters();
        }
    }
}

function setVehicleCategory(vehicle, category) {
    if (category === 'jasa-pasang') {
        window.open('{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}', '_blank');
        return;
    }
    if (category === 'custom') {
        window.open('{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}', '_blank');
        return;
    }

    katalogVehicle = vehicle;
    katalogCategory = category;

    const subMobil = document.getElementById('dropdown-sub-mobil');
    const subMotor = document.getElementById('dropdown-sub-motor');
    const chevronMobil = document.getElementById('chevron-mobil');
    const chevronMotor = document.getElementById('chevron-motor');

    if (vehicle === 'mobil') {
        if (subMobil) subMobil.classList.remove('hidden');
        if (chevronMobil) chevronMobil.classList.add('rotate-180');
        if (subMotor) subMotor.classList.add('hidden');
        if (chevronMotor) chevronMotor.classList.remove('rotate-180');
    } else if (vehicle === 'motor') {
        if (subMotor) subMotor.classList.remove('hidden');
        if (chevronMotor) chevronMotor.classList.add('rotate-180');
        if (subMobil) subMobil.classList.add('hidden');
        if (chevronMobil) chevronMobil.classList.remove('rotate-180');
    }

    applyCatalogFilters();
}

function setVehicleFilter(vehicle) {
    katalogVehicle = vehicle;
    katalogCategory = 'all';

    const subMobil = document.getElementById('dropdown-sub-mobil');
    const subMotor = document.getElementById('dropdown-sub-motor');
    const chevronMobil = document.getElementById('chevron-mobil');
    const chevronMotor = document.getElementById('chevron-motor');

    if (vehicle === 'all') {
        if (subMobil) subMobil.classList.add('hidden');
        if (chevronMobil) chevronMobil.classList.remove('rotate-180');
        if (subMotor) subMotor.classList.add('hidden');
        if (chevronMotor) chevronMotor.classList.remove('rotate-180');
    } else if (vehicle === 'mobil') {
        if (subMobil) subMobil.classList.remove('hidden');
        if (chevronMobil) chevronMobil.classList.add('rotate-180');
        if (subMotor) subMotor.classList.add('hidden');
        if (chevronMotor) chevronMotor.classList.remove('rotate-180');
    } else if (vehicle === 'motor') {
        if (subMotor) subMotor.classList.remove('hidden');
        if (chevronMotor) chevronMotor.classList.add('rotate-180');
        if (subMobil) subMobil.classList.add('hidden');
        if (chevronMobil) chevronMobil.classList.remove('rotate-180');
    }

    applyCatalogFilters();
}

function setServiceFilter(category) {
    if (category === 'jasa-pasang') {
        window.open('{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}', '_blank');
        return;
    }
    if (category === 'custom') {
        window.open('{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}', '_blank');
        return;
    }

    katalogCategory = category;
    applyCatalogFilters();
}

function setPriceFilter(range) {
    katalogPriceRange = range;
    applyCatalogFilters();
}

function searchCatalog() {
    const input = document.getElementById('catalog-search');
    const clearBtn = document.getElementById('catalog-search-clear');
    if (!input) return;

    katalogQuery = input.value.toLowerCase().trim();
    if (clearBtn) {
        clearBtn.classList.toggle('hidden', katalogQuery === '');
    }

    applyCatalogFilters();
}

function clearCatalogQuery() {
    katalogQuery = '';
    const input = document.getElementById('catalog-search');
    const clearBtn = document.getElementById('catalog-search-clear');
    if (input) input.value = '';
    if (clearBtn) clearBtn.classList.add('hidden');

    applyCatalogFilters();
}

function updateActiveChips() {
    const chipsContainer = document.getElementById('catalog-active-chips');
    const clearBtn = document.getElementById('catalog-clear-all-btn');
    if (!chipsContainer) return;

    chipsContainer.innerHTML = '';
    const hasFilter = (katalogVehicle !== 'all') || (katalogCategory !== 'all') || (katalogPriceRange !== 'all') || (katalogQuery !== '');

    if (katalogVehicle !== 'all') {
        const vSpan = document.createElement('span');
        vSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 flex items-center gap-1';
        vSpan.innerHTML = `<span>${katalogVehicle === 'mobil' ? '🚗 Mobil' : '🛵 Motor'}</span> <button type="button" onclick="setVehicleFilter('all')" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(vSpan);
    }

    if (katalogCategory !== 'all') {
        const cSpan = document.createElement('span');
        cSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 flex items-center gap-1';
        let catText = categoryLabels[katalogCategory] || katalogCategory;
        if (katalogCategory === 'custom' || katalogCategory === 'partial' || katalogCategory === 'striping') {
            catText = (katalogVehicle === 'motor') ? 'Striping & Custom Decal' : 'Custom Decal';
        }
        cSpan.innerHTML = `<span>${catText}</span> <button type="button" onclick="setServiceFilter('all')" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(cSpan);
    }

    if (katalogPriceRange !== 'all') {
        const pSpan = document.createElement('span');
        pSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 flex items-center gap-1';
        pSpan.innerHTML = `<span>${priceLabels[katalogPriceRange] || katalogPriceRange}</span> <button type="button" onclick="setPriceFilter('all')" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(pSpan);
    }

    if (katalogQuery !== '') {
        const qSpan = document.createElement('span');
        qSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-blue-50 text-blue-600 border border-blue-200 flex items-center gap-1';
        qSpan.innerHTML = `<span>"${katalogQuery}"</span> <button type="button" onclick="clearCatalogQuery()" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(qSpan);
    }

    if (!hasFilter) {
        const dSpan = document.createElement('span');
        dSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-semibold bg-slate-100 text-slate-600 border border-slate-200';
        dSpan.textContent = 'Semua Produk';
        chipsContainer.appendChild(dSpan);
    }

    if (clearBtn) {
        clearBtn.classList.toggle('hidden', !hasFilter);
    }
}

function updateSidebarUI() {
    document.querySelectorAll('.filter-vehicle-btn').forEach(btn => {
        const v = btn.getAttribute('data-filter-vehicle');
        const countSpan = btn.querySelector('.filter-count');
        const wrapper = btn.closest('#accordion-wrapper-' + v);

        if (v === katalogVehicle) {
            btn.classList.add('bg-[#ff6b00]', 'text-white', 'shadow-md', 'shadow-orange-500/20');
            btn.classList.remove('text-slate-700', 'bg-white', 'hover:bg-slate-100/80');
            if (countSpan) {
                countSpan.className = 'filter-count text-xs px-2.5 py-0.5 rounded-full bg-white/20 text-white font-mono font-bold';
            }
            if (wrapper) {
                wrapper.classList.add('border-orange-300', 'bg-orange-50/30');
                wrapper.classList.remove('border-slate-200/80', 'bg-slate-50/60');
            }
        } else {
            btn.classList.remove('bg-[#ff6b00]', 'text-white', 'shadow-md', 'shadow-orange-500/20');
            btn.classList.add('text-slate-700');
            if (countSpan) {
                countSpan.className = 'filter-count text-xs px-2.5 py-0.5 rounded-full bg-slate-200/80 text-slate-600 font-mono';
            }
            if (wrapper) {
                wrapper.classList.remove('border-orange-300', 'bg-orange-50/30');
                wrapper.classList.add('border-slate-200/80', 'bg-slate-50/60');
            }
        }
    });

    document.querySelectorAll('.sub-filter-btn').forEach(btn => {
        const subVeh = btn.getAttribute('data-sub-vehicle');
        const subCat = btn.getAttribute('data-sub-cat');
        const isActive = (katalogVehicle === subVeh) && (katalogCategory === subCat);

        if (isActive) {
            btn.className = 'sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between bg-[#ff6b00] text-white font-bold shadow-sm transition-all';
            const count = btn.querySelector('span:last-child');
            if (count) count.className = 'text-xs text-white font-mono font-bold';
        } else {
            btn.className = 'sub-filter-btn w-full px-3 py-2 rounded-lg text-xs sm:text-[13px] font-montserrat text-left flex items-center justify-between text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all';
            const count = btn.querySelector('span:last-child');
            if (count) count.className = 'text-xs text-slate-500 font-mono';
        }
    });

    document.querySelectorAll('.filter-price-btn').forEach(btn => {
        const p = btn.getAttribute('data-filter-price');
        if (p === katalogPriceRange) {
            btn.className = 'filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat font-bold text-left transition-all bg-orange-50 text-[#ff6b00] border border-orange-300 flex items-center justify-between';
        } else {
            btn.className = 'filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat font-medium text-left transition-all bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between';
        }
    });

    document.querySelectorAll('.quick-filter-chip').forEach(btn => {
        const v = btn.getAttribute('data-quick-vehicle');
        const c = btn.getAttribute('data-quick-cat');
        let isActive = false;

        if (v) {
            if (v === 'all') {
                isActive = (katalogVehicle === 'all' && katalogCategory === 'all');
            } else {
                isActive = (katalogVehicle === v);
            }
        } else if (c) {
            isActive = (katalogCategory === c);
        }

        if (isActive) {
            btn.className = 'quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-bold whitespace-nowrap transition-all bg-[#ff6b00] text-white shadow-sm flex items-center gap-1.5 shrink-0 active:scale-95 border border-[#ff6b00]';
        } else {
            btn.className = 'quick-filter-chip px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-semibold whitespace-nowrap transition-all bg-slate-100 border border-slate-200 text-slate-700 hover:border-orange-300 hover:text-slate-900 flex items-center gap-1.5 shrink-0 active:scale-95';
        }
    });
}

function resetAllKatalogFilters() {
    katalogQuery = '';
    katalogCategory = 'all';
    katalogVehicle = 'all';
    katalogPriceRange = 'all';
    katalogSort = 'default';

    const input = document.getElementById('catalog-search');
    if (input) input.value = '';
    const sortSelect = document.getElementById('catalog-sort-select');
    if (sortSelect) sortSelect.value = 'default';

    const subMobil = document.getElementById('dropdown-sub-mobil');
    const subMotor = document.getElementById('dropdown-sub-motor');
    const chevronMobil = document.getElementById('chevron-mobil');
    const chevronMotor = document.getElementById('chevron-motor');

    if (subMobil) subMobil.classList.add('hidden');
    if (chevronMobil) chevronMobil.classList.remove('rotate-180');
    if (subMotor) subMotor.classList.add('hidden');
    if (chevronMotor) chevronMotor.classList.remove('rotate-180');

    applyCatalogFilters();
}

function toggleMobileFilter(show) {
    const sidebar = document.getElementById('catalog-sidebar');
    const backdrop = document.getElementById('filter-backdrop');
    if (!sidebar || !backdrop) return;

    if (show) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    applyCatalogFilters();
});

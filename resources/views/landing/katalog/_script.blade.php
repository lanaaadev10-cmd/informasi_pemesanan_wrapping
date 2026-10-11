{{-- ============================================
    BAGIAN: Scripting E-Commerce Katalog
    Deskripsi: Logika filter real-time (Kendaraan, Dropdown Accordion Sub-Layanan,
               Kategori, Harga, Pencarian) dan pengurutan (Sorting)
============================================ --}}
<script>
let katalogQuery = '';
let katalogCategory = 'all';
let katalogVehicle = 'all';
let katalogPriceRange = 'all';
let katalogSort = 'default';

const categoryLabels = {
    'all': 'Semua Layanan',
    'wrapping': 'Full Body Wrap',
    'jasa-pasang': 'Jasa Pasang (Bahan Sendiri)',
    'partial': 'Custom Decal',
    'custom': 'Custom Decal',
    'striping': 'Striping & Custom Decal',
    'aksesoris': 'Aksesoris'
};

const vehicleLabels = {
    'all': 'Semua Kendaraan',
    'mobil': 'Mobil (Roda 4)',
    'motor': 'Motor (Roda 2)'
};

const priceLabels = {
    'all': 'Semua Harga',
    'under-500k': '< Rp 500rb',
    '500k-2m': 'Rp 500rb - 2 Jt',
    'above-2m': '> Rp 2 Jt'
};

// 1. Terapkan Semua Filter & Sorting
function applyCatalogFilters() {
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

    const guidanceBox = document.getElementById('catalog-custom-guidance');
    const emptyState = document.getElementById('catalog-empty-state');
    const countBadge = document.getElementById('catalog-visible-count');

    const isCustomFilter = (katalogCategory === 'custom' || katalogCategory === 'partial' || katalogCategory === 'striping');

    if (isCustomFilter) {
        // Mode Arahan Khusus Custom: Sembunyikan grid produk & empty state
        grid.style.display = 'none';
        if (emptyState) emptyState.style.display = 'none';

        if (guidanceBox) {
            guidanceBox.style.display = 'block';

            const titleEl = document.getElementById('custom-guidance-title');
            const descEl = document.getElementById('custom-guidance-desc');
            const waBtn = document.getElementById('custom-guidance-wa-btn');

            const vehName = (katalogVehicle === 'motor') ? 'Motor' : (katalogVehicle === 'mobil' ? 'Mobil' : 'Kendaraan');
            const customType = (katalogVehicle === 'motor') ? 'Custom Decal & Striping' : 'Custom Decal';

            if (titleEl) {
                titleEl.textContent = `Alur Pemesanan ${customType} ${vehName}`;
            }
            if (descEl) {
                descEl.textContent = `Pengerjaan custom dibuat khusus sesuai konsep dan ukuran ${vehName.toLowerCase()} Anda. Ikuti 3 langkah mudah berikut untuk memesan:`;
            }
            if (waBtn) {
                const msg = encodeURIComponent(`Halo Tim Dantie Stiker, saya ingin konsultasi desain & estimasi biaya untuk proyek ${customType} ${vehName} saya...`);
                @php
                    $rawCleanWa = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
                    if (str_starts_with($rawCleanWa, '0')) {
                        $rawCleanWa = '62' . substr($rawCleanWa, 1);
                    }
                @endphp
                waBtn.href = `https://api.whatsapp.com/send?phone={{ $rawCleanWa }}&text=${msg}`;
            }
        }

        if (countBadge) {
            countBadge.textContent = 'Konsultasi via WA';
        }

        updateActiveChips();
        updateSidebarUI();
        return;
    }

    // Mode Normal Paket Baku
    if (guidanceBox) {
        guidanceBox.style.display = 'none';
    }
    grid.style.display = '';

    const items = Array.from(grid.querySelectorAll('.katalog-item'));
    let visibleCount = 0;

    items.forEach(item => {
        const categories = (item.getAttribute('data-category') || '').toLowerCase().split(' ');
        const vehicle = (item.getAttribute('data-vehicle') || '').toLowerCase();
        const price = parseFloat(item.getAttribute('data-price')) || 0;

        const titleEl = item.querySelector('.katalog-title');
        const text = ((titleEl ? titleEl.textContent : '') + ' ' + (item.textContent || '')).toLowerCase();

        // 1. Filter Kendaraan
        const matchVehicle = (katalogVehicle === 'all') || (vehicle === katalogVehicle) || categories.includes(katalogVehicle);

        // 2. Filter Kategori Layanan
        const matchCategory = (katalogCategory === 'all') || categories.includes(katalogCategory);

        // 3. Filter Rentang Harga
        let matchPrice = true;
        if (katalogPriceRange === 'under-500k') {
            matchPrice = (price > 0 && price < 500000);
        } else if (katalogPriceRange === '500k-2m') {
            matchPrice = (price >= 500000 && price <= 2000000);
        } else if (katalogPriceRange === 'above-2m') {
            matchPrice = (price > 2000000);
        }

        // 4. Filter Pencarian Teks
        const matchSearch = (katalogQuery === '') || text.includes(katalogQuery);

        if (matchVehicle && matchCategory && matchPrice && matchSearch) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // 5. Jalankan Sorting pada Kartu yang Aktif
    sortItems(grid, items);

    // 6. Update Angka Layanan Ditemukan
    if (countBadge) {
        countBadge.textContent = visibleCount;
    }

    // 7. Update Tampilan Empty State
    if (emptyState) {
        emptyState.style.display = (visibleCount === 0) ? 'flex' : 'none';
    }

    // 8. Update Chips Ringkasan Filter
    updateActiveChips();

    // 9. Update UI Sidebar & Accordion
    updateSidebarUI();
}

// 2. Sorting Logika E-Commerce
function sortItems(grid, items) {
    if (katalogSort === 'default') {
        items.sort((a, b) => {
            return (parseInt(a.getAttribute('data-index')) || 0) - (parseInt(b.getAttribute('data-index')) || 0);
        });
    } else if (katalogSort === 'price-asc') {
        items.sort((a, b) => {
            const pA = parseFloat(a.getAttribute('data-price')) || 0;
            const pB = parseFloat(b.getAttribute('data-price')) || 0;
            return pA - pB;
        });
    } else if (katalogSort === 'price-desc') {
        items.sort((a, b) => {
            const pA = parseFloat(a.getAttribute('data-price')) || 0;
            const pB = parseFloat(b.getAttribute('data-price')) || 0;
            return pB - pA;
        });
    } else if (katalogSort === 'rating-desc') {
        items.sort((a, b) => {
            const rA = parseFloat(a.getAttribute('data-rating')) || 0;
            const rB = parseFloat(b.getAttribute('data-rating')) || 0;
            return rB - rA;
        });
    } else if (katalogSort === 'name-asc') {
        items.sort((a, b) => {
            const nA = (a.getAttribute('data-name') || '').toLowerCase();
            const nB = (b.getAttribute('data-name') || '').toLowerCase();
            return nA.localeCompare(nB);
        });
    }

    // Re-append items to update DOM order
    items.forEach(el => grid.appendChild(el));
}

// Handler Dropdown Sortir
function sortCatalog(sortVal) {
    katalogSort = sortVal;
    applyCatalogFilters();
}

// 3. Handler Dropdown Accordion Kendaraan
function toggleVehicleAccordion(vehicle) {
    const subMobil = document.getElementById('dropdown-sub-mobil');
    const subMotor = document.getElementById('dropdown-sub-motor');
    const chevronMobil = document.getElementById('chevron-mobil');
    const chevronMotor = document.getElementById('chevron-motor');

    if (vehicle === 'mobil') {
        const isCurrentlyOpen = subMobil && !subMobil.classList.contains('hidden');
        if (katalogVehicle === 'mobil' && isCurrentlyOpen) {
            // Tutup jika diklik lagi saat sudah aktif
            subMobil.classList.add('hidden');
            if (chevronMobil) chevronMobil.classList.remove('rotate-180');
        } else {
            // Buka mobil, tutup motor
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
            // Tutup jika diklik lagi saat sudah aktif
            subMotor.classList.add('hidden');
            if (chevronMotor) chevronMotor.classList.remove('rotate-180');
        } else {
            // Buka motor, tutup mobil
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

// 4. Handler Memilih Sub-Kategori Langsung dari Accordion
function setVehicleCategory(vehicle, category) {
    katalogVehicle = vehicle;
    katalogCategory = category;

    // Pastikan accordion kendaraan terbuka
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

// 5. Handler Filter Kendaraan Umum
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

// 6. Handler Filter Layanan / Kategori Umum
function setServiceFilter(category) {
    katalogCategory = category;
    applyCatalogFilters();
}

// 7. Handler Filter Rentang Harga
function setPriceFilter(range) {
    katalogPriceRange = range;
    applyCatalogFilters();
}

// 8. Pencarian Teks
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

// 9. Perbarui Chip Status Filter Aktif di Toolbar
function updateActiveChips() {
    const chipsContainer = document.getElementById('catalog-active-chips');
    const clearBtn = document.getElementById('catalog-clear-all-btn');
    if (!chipsContainer) return;

    chipsContainer.innerHTML = '';
    const hasFilter = (katalogVehicle !== 'all') || (katalogCategory !== 'all') || (katalogPriceRange !== 'all') || (katalogQuery !== '');

    // Chip Kendaraan (jika bukan 'all')
    if (katalogVehicle !== 'all') {
        const vSpan = document.createElement('span');
        vSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 flex items-center gap-1';
        vSpan.innerHTML = `<span>${katalogVehicle === 'mobil' ? '🚗 Mobil' : '🛵 Motor'}</span> <button type="button" onclick="setVehicleFilter('all')" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(vSpan);
    }

    // Chip Kategori Layanan
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

    // Chip Harga
    if (katalogPriceRange !== 'all') {
        const pSpan = document.createElement('span');
        pSpan.className = 'px-2 py-0.5 rounded-lg text-[10px] font-montserrat font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 flex items-center gap-1';
        pSpan.innerHTML = `<span>${priceLabels[katalogPriceRange] || katalogPriceRange}</span> <button type="button" onclick="setPriceFilter('all')" class="hover:text-slate-900">×</button>`;
        chipsContainer.appendChild(pSpan);
    }

    // Chip Search
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

// 10. Perbarui Visual Sidebar, Accordion, dan Rentang Harga
function updateSidebarUI() {
    // 10.1 Highlight Tombol Utama Kendaraan
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

    // 10.2 Highlight Sub-Layanan di Dalam Accordion Dropdown
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

    // 10.3 Highlight Tombol Rentang Harga
    document.querySelectorAll('.filter-price-btn').forEach(btn => {
        const p = btn.getAttribute('data-filter-price');
        if (p === katalogPriceRange) {
            btn.className = 'filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat font-bold text-left transition-all bg-orange-50 text-[#ff6b00] border border-orange-300 flex items-center justify-between';
        } else {
            btn.className = 'filter-price-btn w-full px-3 py-2.5 rounded-xl text-sm font-montserrat font-medium text-left transition-all bg-white border border-slate-200/80 text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-between';
        }
    });
}

// 11. Reset Semua Filter
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

// 12. Drawer Toggle Mobile
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

// Inisialisasi Pertama Kali
document.addEventListener('DOMContentLoaded', () => {
    applyCatalogFilters();
});
</script>

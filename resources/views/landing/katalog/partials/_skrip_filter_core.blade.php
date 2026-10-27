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

function applyCatalogFilters() {
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

    const guidanceBox = document.getElementById('catalog-custom-guidance');
    const emptyState = document.getElementById('catalog-empty-state');
    const countBadge = document.getElementById('catalog-visible-count');

    const isCustomFilter = (katalogCategory === 'custom' || katalogCategory === 'partial' || katalogCategory === 'striping');

    if (isCustomFilter) {
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

        const matchVehicle = (katalogVehicle === 'all') || (vehicle === katalogVehicle) || categories.includes(katalogVehicle);
        const matchCategory = (katalogCategory === 'all') || categories.includes(katalogCategory);

        let matchPrice = true;
        if (katalogPriceRange === 'under-500k') {
            matchPrice = (price > 0 && price < 500000);
        } else if (katalogPriceRange === '500k-2m') {
            matchPrice = (price >= 500000 && price <= 2000000);
        } else if (katalogPriceRange === 'above-2m') {
            matchPrice = (price > 2000000);
        }

        const matchSearch = (katalogQuery === '') || text.includes(katalogQuery);

        if (matchVehicle && matchCategory && matchPrice && matchSearch) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    sortItems(grid, items);

    if (countBadge) {
        countBadge.textContent = visibleCount;
    }

    if (emptyState) {
        emptyState.style.display = (visibleCount === 0) ? 'flex' : 'none';
    }

    updateActiveChips();
    updateSidebarUI();
}

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

    items.forEach(el => grid.appendChild(el));
}

function sortCatalog(sortVal) {
    katalogSort = sortVal;
    applyCatalogFilters();
}

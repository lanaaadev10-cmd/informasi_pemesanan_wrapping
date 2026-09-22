{{-- ============================================
    BAGIAN: Scripting Katalog
    Deskripsi: Logika JavaScript pencarian langsung & penyaringan kategori
============================================ --}}
<script>
   // State bersama untuk search + filter (biar dua-duanya jalan bareng)
let katalogQuery = '';
let katalogCategory = 'all';

// Terapkan search + filter sekaligus ke semua kartu
function applyCatalogFilters() {
    const items = document.querySelectorAll('.katalog-item');
    items.forEach(item => {
        const categories = (item.getAttribute('data-category') || '').toLowerCase().split(' ');

        const titleEl = item.querySelector('.katalog-title');
        const descEl = item.querySelector('.katalog-desc');
        const text = ((titleEl ? titleEl.textContent : '') + ' ' + (descEl ? descEl.textContent : '')).toLowerCase();

        const matchCategory = katalogCategory === 'all' || categories.includes(katalogCategory);
        const matchSearch = katalogQuery === '' || text.includes(katalogQuery);

        item.style.display = (matchCategory && matchSearch) ? '' : 'none';
    });
}

// Javascript Live Search
function searchCatalog() {
    const input = document.getElementById('catalog-search');
    if (!input) return;

    katalogQuery = input.value.toLowerCase().trim();
    applyCatalogFilters();
}

// Javascript Category Filter Pills
function filterKatalog(category) {
    // 1. Ubah tampilan tombol aktif (Warna Highlight)
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        const btnCategory = btn.getAttribute('data-category');
        if (btnCategory === category) {
            btn.classList.add('accent-bg', 'text-black');
            btn.classList.remove('bg-white/5', 'text-gray-400');
        } else {
            btn.classList.remove('accent-bg', 'text-black');
            btn.classList.add('bg-white/5', 'text-gray-400');
        }
    });

    // 2. Simpan kategori aktif + tampilkan/sembunyikan kartu
    katalogCategory = category;
    applyCatalogFilters();
}
</script>

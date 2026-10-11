{{-- Category Filter Chips (Ditempatkan di Atas Paket Layanan) --}}
<div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1" id="category-filter-chips">
    <button type="button"
            onclick="applyCategoryFilter('all', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#FF6B00] text-white shadow-md shadow-[#FF6B00]/25 shrink-0 active:scale-95 flex items-center gap-1.5">
        <span>🏁</span>
        <span>Semua Paket</span>
    </button>
    <button type="button"
            onclick="applyCategoryFilter('motor', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-[#FF6B00]/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm">
        <span>🛵</span>
        <span>Khusus Motor</span>
    </button>
    <button type="button"
            onclick="applyCategoryFilter('mobil', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-[#FF6B00]/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm">
        <span>🚗</span>
        <span>Khusus Mobil</span>
    </button>
    <button type="button"
            onclick="applyCategoryFilter('jasa-pasang', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-emerald-500/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm">
        <span>✂️</span>
        <span>Jasa Pasang (Bahan Sendiri)</span>
    </button>
    <button type="button"
            onclick="applyCategoryFilter('wrapping', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-[#FF6B00]/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm">
        <span>📦</span>
        <span>Full Body Wrap</span>
    </button>
    <button type="button"
            onclick="applyCategoryFilter('variasi', this)"
            class="cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-[#FF6B00]/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm">
        <span>🎯</span>
        <span>Kaca Film &amp; Variasi</span>
    </button>
</div>

<script>
    let activeCategory = 'all';

    function applyCategoryFilter(cat, btn) {
        activeCategory = cat;
        // Update button states
        document.querySelectorAll('.cat-filter-btn').forEach(b => {
            b.className = 'cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:border-[#FF6B00]/40 shrink-0 active:scale-95 flex items-center gap-1.5 shadow-sm';
        });
        if (btn) {
            btn.className = 'cat-filter-btn px-4 py-2 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#FF6B00] text-white shadow-md shadow-[#FF6B00]/25 shrink-0 active:scale-95 flex items-center gap-1.5';
        }
        filterCards();
    }

    function filterCards() {
        const cards = document.querySelectorAll('.top-pick-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
            let matchCat = false;

            if (activeCategory === 'all') {
                matchCat = true;
            } else if (activeCategory === 'motor') {
                matchCat = cardCat.includes('motor');
            } else if (activeCategory === 'mobil') {
                matchCat = cardCat.includes('mobil');
            } else if (activeCategory === 'jasa-pasang') {
                matchCat = cardCat.includes('jasa-pasang') || cardCat.includes('jasa pasang');
            } else if (activeCategory === 'wrapping') {
                matchCat = cardCat.includes('wrapping') && !cardCat.includes('jasa-pasang');
            } else if (activeCategory === 'variasi') {
                matchCat = cardCat.includes('window-film') || cardCat.includes('audio') || cardCat.includes('lighting') || cardCat.includes('striping');
            } else {
                matchCat = cardCat.includes(activeCategory);
            }

            if (matchCat) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('top-picks-empty-state');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
        }
    }
</script>

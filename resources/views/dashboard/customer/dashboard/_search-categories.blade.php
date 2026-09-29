{{-- Category Filter Chips (Ditempatkan di Atas Paket Layanan) --}}
<div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1" id="category-filter-chips">
    <button type="button"
            onclick="applyCategoryFilter('all', this)"
            class="cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25 shrink-0 active:scale-95">
        Semua Paket
    </button>
    <button type="button"
            onclick="applyCategoryFilter('wrapping', this)"
            class="cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#0E0E10] border border-white/10 text-[#8A8D93] hover:text-white hover:border-[#FF6B00]/40 shrink-0 active:scale-95">
        Car Wrapping
    </button>
    <button type="button"
            onclick="applyCategoryFilter('window-film', this)"
            class="cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#0E0E10] border border-white/10 text-[#8A8D93] hover:text-white hover:border-[#FF6B00]/40 shrink-0 active:scale-95">
        Kaca Film
    </button>
    <button type="button"
            onclick="applyCategoryFilter('audio', this)"
            class="cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#0E0E10] border border-white/10 text-[#8A8D93] hover:text-white hover:border-[#FF6B00]/40 shrink-0 active:scale-95">
        Audio &amp; Aksesori
    </button>
</div>

<script>
    let activeCategory = 'all';

    function applyCategoryFilter(cat, btn) {
        activeCategory = cat;
        // Update button states
        document.querySelectorAll('.cat-filter-btn').forEach(b => {
            b.className = 'cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#0E0E10] border border-white/10 text-[#8A8D93] hover:text-white hover:border-[#FF6B00]/40 shrink-0 active:scale-95';
        });
        if (btn) {
            btn.className = 'cat-filter-btn px-4 py-2.5 rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25 shrink-0 active:scale-95';
        }
        filterCards();
    }

    function filterCards() {
        const cards = document.querySelectorAll('.top-pick-card');
        let visibleCount = 0;
        cards.forEach(card => {
            const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
            const matchCat = activeCategory === 'all' || cardCat.includes(activeCategory);
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

{{-- ============================================
    BAGIAN: Scripting Katalog
    Deskripsi: Logika JavaScript pencarian langsung & penyaringan kategori
============================================ --}}
<script>
    // Javascript Live Search
    function searchCatalog() {
        const query = document.getElementById('catalog-search').value.toLowerCase();
        const items = document.querySelectorAll('.katalog-item');
        
        items.forEach(item => {
            const title = item.querySelector('.katalog-title').textContent.toLowerCase();
            const desc = item.querySelector('.katalog-desc').textContent.toLowerCase();
            
            if (title.includes(query) || desc.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Javascript Category Filter Pills
    function filterKatalog(category) {
        const items = document.querySelectorAll('.katalog-item');
        const buttons = document.querySelectorAll('.filter-btn');

        // Toggle active styles on buttons
        buttons.forEach(btn => {
            const btnCategory = btn.getAttribute('data-category').toLowerCase();
            if (btnCategory === category.toLowerCase()) {
                btn.className = "filter-btn px-5 py-2.5 rounded-full bg-[#f2994a] text-black font-extrabold text-xs border border-[#f2994a] transition-all duration-300 shadow-md shadow-[#f2994a]/10 active:scale-95";
            } else {
                btn.className = "filter-btn px-5 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/20 hover:text-white transition-all duration-300 active:scale-95";
            }
        });

        // Toggle items visibility
        items.forEach(item => {
            const categories = item.getAttribute('data-category').toLowerCase().split(' ');
            
            if (category === 'all' || categories.includes(category.toLowerCase())) {
                item.style.display = '';
                // entrance fade-in micro-animation
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transition = 'opacity 0.4s ease';
                }, 50);
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>

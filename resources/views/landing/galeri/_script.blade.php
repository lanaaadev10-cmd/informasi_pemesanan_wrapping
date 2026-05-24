{{-- ============================================
    BAGIAN: Filter JavaScript
    Deskripsi: Script untuk filter kategori galeri
============================================ --}}
<script>
    function filterGaleri(category) {
        const items = document.querySelectorAll('.galeri-item');
        const buttons = document.querySelectorAll('.filter-btn');

        buttons.forEach(btn => {
            const btnCategory = (btn.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();

            if (btnCategory === filterCategory) {
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-[#f2994a] text-black font-extrabold text-xs border border-[#f2994a] transition-all duration-300 shadow-lg shadow-[#f2994a]/10 flex items-center gap-2 active:scale-95";
            } else {
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/30 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95";
            }
        });

        items.forEach(item => {
            const itemCategoriesString = (item.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();

            if (filterCategory === 'all' || itemCategoriesString.includes(filterCategory)) {
                // Restore responsive layouts cleanly on match
                if (item.classList.contains('md:col-span-8')) {
                    item.style.display = 'block';
                } else if (item.classList.contains('md:col-span-4')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'block';
                }

                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transition = 'opacity 0.4s ease';
                }, 50);
            } else {
                item.style.display = 'none';
            }
        });

        if (typeof AOS !== 'undefined') AOS.refresh();
    }
</script>

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
                btn.className = "filter-btn shrink-0 px-5 py-2 rounded-full bg-[#ea580c] text-white font-montserrat font-medium text-xs border border-[#ea580c] transition-all duration-300 shadow-sm flex items-center gap-1.5 cursor-pointer active:scale-95";
            } else {
                btn.className = "filter-btn shrink-0 px-5 py-2 rounded-full bg-slate-100 text-slate-600 font-montserrat font-medium text-xs border border-slate-200/80 hover:bg-slate-200 hover:text-slate-900 transition-all duration-300 flex items-center gap-1.5 cursor-pointer active:scale-95";
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

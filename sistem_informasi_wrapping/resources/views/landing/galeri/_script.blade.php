{{-- ============================================
    BAGIAN: Filter JavaScript
    Deskripsi: Script untuk filter kategori galeri
============================================ --}}
<script>
    function filterGaleri(category) {
        const items = document.querySelectorAll('.galeri-item');
        const buttons = document.querySelectorAll('.filter-tab');

        // Toggle active class on buttons
        buttons.forEach(btn => {
            const btnCategory = (btn.getAttribute('data-category') || '').toLowerCase();
            btn.classList.toggle('active', btnCategory === category.toLowerCase());
        });

        // Filter items dengan fade
        items.forEach(item => {
            const itemCategory = (item.getAttribute('data-category') || '').toLowerCase();
            const match = category === 'all' || itemCategory.includes(category.toLowerCase());

            if (match) {
                item.style.display = 'block';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transition = 'opacity 0.35s ease';
                }, 30);
            } else {
                item.style.display = 'none';
            }
        });

        if (typeof AOS !== 'undefined') AOS.refresh();
    }
</script>


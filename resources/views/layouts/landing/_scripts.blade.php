{{-- AOS dan Script Inisialisasi Layout Utama --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Sembunyikan Preloader saat DOMContentLoaded selesai agar FCP/LCP lebih optimal
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 300);
        }
    });

    AOS.init({
        duration: 1000,
        once: true,
        easing: 'ease-out-cubic'
    });

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');
        
        if (menu && menu.classList.contains('opacity-0')) {
            // Buka Menu
            menu.classList.remove('opacity-0', '-translate-y-full', 'pointer-events-none');
            menu.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            if (icon) {
                icon.classList.remove('ph-list');
                icon.classList.add('ph-x');
            }
            document.body.style.overflow = 'hidden';
        } else if (menu) {
            // Tutup Menu
            menu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
            menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            if (icon) {
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
            }
            document.body.style.overflow = '';
        }
    }

    // Tutup menu saat resize kembali ke layar desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            if (menu) {
                menu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
            if (icon) {
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
            }
            document.body.style.overflow = '';
        }
    });
</script>

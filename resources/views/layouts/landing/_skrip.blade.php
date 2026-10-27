{{-- AOS dan Script Inisialisasi Layout Utama --}}
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
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

        function initAos() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    offset: 50,
                    once: true,
                    easing: 'ease-out-cubic'
                });
            }
        }
        initAos();
        window.addEventListener('load', initAos, { once: true });
    });
</script>

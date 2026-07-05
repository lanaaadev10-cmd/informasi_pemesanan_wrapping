{{-- ============================================
    BAGIAN: Styling Tentang Kami
    Deskripsi: Custom CSS khusus halaman Tentang Kami
============================================ --}}
<style>
    :root {
        --accent-color: {{ $accentColor }};
    }
    .accent-color { color: var(--accent-color); }
    .accent-bg { background-color: var(--accent-color); }
    .accent-border { border-color: var(--accent-color); }
    .text-glow {
        text-shadow: 0 0 10px rgba(242, 153, 74, 0.3);
    }
</style>

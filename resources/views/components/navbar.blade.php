<!-- Navigation Bar -->
<nav class="fixed top-0 w-full z-50 backdrop-blur-md bg-opacity-80 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                <i class="ph-bold ph-car text-xl text-white"></i>
            </div>
            <span class="font-bold text-xl hidden sm:inline">{{ \App\Helpers\StaticContent::APP_NAME }}</span>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">
            <a href="#beranda" class="text-gray-300 hover:text-white transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
            <a href="{{ route('katalog.user') }}" class="text-gray-300 hover:text-white transition-colors">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
            <a href="{{ route('galeri.user') }}" class="text-gray-300 hover:text-white transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
            <a href="{{ route('profil.perusahaan') }}" class="text-gray-300 hover:text-white transition-colors">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
        </div>

        <!-- CTA Buttons -->
        <div class="flex items-center gap-3">
            @guest
                <a href="{{ route('login') }}" class="hidden sm:inline text-gray-300 hover:text-white transition-colors">{{ \App\Helpers\StaticContent::NAV_MASUK }}</a>
                <a href="{{ route('register') }}" class="btn-premium text-sm">{{ \App\Helpers\StaticContent::NAV_DAFTAR }}</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="btn-premium text-sm">{{ \App\Helpers\StaticContent::NAV_DASHBOARD }}</a>
            @endauth

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-gray-300 text-2xl" id="mobile-menu-btn">
                <i class="ph-bold ph-list"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-opacity-95 border-t border-gray-700 animate-in fade-in">
        <div class="max-w-7xl mx-auto px-6 py-4 space-y-4">
            <a href="#beranda" class="block text-gray-300 hover:text-white transition-colors py-2">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
            <a href="{{ route('katalog.user') }}" class="block text-gray-300 hover:text-white transition-colors py-2">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
            <a href="{{ route('galeri.user') }}" class="block text-gray-300 hover:text-white transition-colors py-2">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
            <a href="{{ route('profil.perusahaan') }}" class="block text-gray-300 hover:text-white transition-colors py-2">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
        </div>
    </div>
</nav>

<!-- Spacer untuk fixed navbar -->
<div class="h-16"></div>

<script>
    const navbar = document.getElementById('navbar');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    // Mobile menu toggle
    mobileMenuBtn?.addEventListener('click', () => {
        mobileMenu?.classList.toggle('hidden');
    });

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar?.classList.add('bg-opacity-100', 'border-b', 'border-gray-700');
        } else {
            navbar?.classList.remove('bg-opacity-100', 'border-b', 'border-gray-700');
        }
    });
</script>

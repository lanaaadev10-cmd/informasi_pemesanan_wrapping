{{-- Navbar Utama (Layout Central) dengan Alpine.js --}}
<nav class="fixed top-0 w-full z-[100] {{ $is_frontend ? 'bg-racing-dark/85 border-b border-white/5 text-white' : 'bg-white/80 border-b border-gray-100 text-gray-900' }} backdrop-blur-md transition-all duration-300"
     x-data="{ mobileMenuOpen: false }" @keydown.escape="mobileMenuOpen = false">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        {{-- Logo --}}
        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-3">
            @if($is_frontend)
                <div class="w-10 h-10 bg-gradient-to-br from-racing-orangeHover to-racing-orangeLight rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="ph-bold ph-sketch-logo text-2xl"></i>
                </div>
            @else
                @if(!empty($profil->logo))
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" width="40" height="40" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-racing-orange rounded-xl flex items-center justify-center text-white">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                @endif
            @endif
            <span class="font-bold text-xl tracking-tight uppercase {{ $is_frontend ? 'text-white' : 'text-gray-900' }}">{{ \App\Helpers\StaticContent::APP_NAME }}</span>
        </a>
        
        {{-- Menu Navigasi Desktop --}}
        <div class="hidden md:flex items-center gap-10">
            <a href="{{ route('home') }}" class="text-sm font-medium {{ Request::routeIs('home') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-500 hover:text-racing-orange') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
            <a href="{{ route('layanan') }}" class="text-sm font-medium {{ Request::routeIs('layanan') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-500 hover:text-racing-orange') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
            <a href="{{ route('galeri.user') }}" class="text-sm font-medium {{ Request::routeIs('galeri.user') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-500 hover:text-racing-orange') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
            <a href="{{ route('tentang-kami') }}" class="text-sm font-medium {{ Request::routeIs('tentang-kami') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-500 hover:text-racing-orange') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
            <a href="{{ route('testimoni.index') }}" class="text-sm font-medium {{ Request::routeIs('testimoni.index') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-500 hover:text-racing-orange') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TESTIMONI }}</a>
            
            @if($is_frontend)
                <div class="flex items-center gap-4 border-l pl-6 border-white/10">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-racing-orangeLight transition-colors">
                            {{ \App\Helpers\StaticContent::NAV_MASUK }}
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-racing-orangeLight hover:bg-racing-orangeHover transition-all hover:scale-105 shadow-md">
                            {{ \App\Helpers\StaticContent::NAV_DAFTAR }}
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('katalog.user') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-racing-orangeLight hover:bg-racing-orangeHover transition-all hover:scale-105 shadow-md">
                            {{ \App\Helpers\StaticContent::NAV_PESANAN }}
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all" title="{{ \App\Helpers\StaticContent::NAV_DASHBOARD }}">
                            <i class="ph-bold ph-user-circle text-xl text-racing-orangeLight"></i>
                        </a>
                        <a href="{{ route('logout.get') }}" class="text-sm font-bold text-red-500 hover:text-red-400 transition-colors">
                            {{ \App\Helpers\StaticContent::NAV_KELUAR }}
                        </a>
                    @endauth
                </div>
            @else
                @guest
                    <div class="flex items-center gap-6 border-l pl-8 border-gray-100">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-racing-orange transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-premium text-white px-8 py-2.5 rounded-full text-sm font-bold transition-all hover:scale-105 active:scale-95">
                            Register
                        </a>
                    </div>
                @endguest

                @auth
                    <div class="flex items-center gap-6 border-l pl-8 border-gray-100">
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-user-circle text-lg"></i>
                            {{ $profil->nav_dashboard ?? 'Dashboard' }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600">{{ $profil->nav_keluar ?? 'Keluar' }}</button>
                        </form>
                    </div>
                @endauth
            @endif
        </div>

        {{-- Tombol Mobile Menu --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 {{ $is_frontend ? 'text-white' : 'text-gray-900' }} cursor-pointer transition-transform active:scale-90">
            <i class="ph-bold" :class="mobileMenuOpen ? 'ph-x text-3xl' : 'ph-list text-3xl'"></i>
        </button>
    </div>

    {{-- Mobile Menu Overlay (Alpine.js controlled) --}}
    <div x-show="mobileMenuOpen" 
         class="fixed inset-x-0 top-20 {{ $is_frontend ? 'bg-racing-dark/95 border-b border-white/5 text-white' : 'bg-white/95 border-b border-gray-100 text-gray-900' }} backdrop-blur-xl shadow-2xl transition-all duration-300 md:hidden overflow-y-auto max-h-[calc(100vh-5rem)]"
         x-transition
         @click.outside="mobileMenuOpen = false">
        <div class="p-8 space-y-6">
            <div class="space-y-1">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('home') ? 'text-racing-orangeLight' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-600') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
                <a href="{{ route('layanan') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('layanan') ? 'text-racing-orangeLight' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-900') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
                <a href="{{ route('galeri.user') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('galeri.user') ? 'text-racing-orangeLight' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-900') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
                <a href="{{ route('tentang-kami') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('tentang-kami') ? 'text-racing-orangeLight' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-900') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
                <a href="{{ route('testimoni.index') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('testimoni.index') ? 'text-racing-orangeLight' : ($is_frontend ? 'text-gray-300 hover:text-racing-orangeLight' : 'text-gray-900') }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TESTIMONI }}</a>
            </div>
            
            <div class="pt-6 border-t {{ $is_frontend ? 'border-white/5' : 'border-gray-100' }}">
                @if($is_frontend)
                    @guest
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-white/5 rounded-2xl font-bold text-gray-300 border border-white/10 transition-colors hover:bg-white/10">{{ \App\Helpers\StaticContent::NAV_MASUK }}</a>
                            <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 btn-premium text-white rounded-2xl font-bold shadow-lg transition-transform hover:scale-105">{{ \App\Helpers\StaticContent::NAV_DAFTAR }}</a>
                        </div>
                    @endguest
                    @auth
                        <div class="grid grid-cols-1 gap-4">
                            <a href="{{ route('katalog.user') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-racing-orangeLight text-black font-extrabold rounded-2xl shadow-lg uppercase tracking-wider text-sm transition-transform hover:scale-105">{{ \App\Helpers\StaticContent::NAV_PESANAN }}</a>
                            <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-white/5 rounded-2xl font-bold text-gray-300 border border-white/10 transition-colors hover:bg-white/10">{{ \App\Helpers\StaticContent::NAV_DASHBOARD }}</a>
                            <a href="{{ route('logout.get') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-red-500/10 text-red-500 rounded-2xl font-bold border border-red-500/20 transition-colors hover:bg-red-500/20">{{ \App\Helpers\StaticContent::NAV_KELUAR }}</a>
                        </div>
                    @endauth
                @else
                    @guest
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-gray-50 rounded-2xl font-bold text-gray-600 transition-colors hover:bg-gray-100">Login</a>
                            <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 btn-premium text-white rounded-2xl font-bold shadow-lg transition-transform hover:scale-105">Register</a>
                        </div>
                    @endguest

                    @auth
                        <div class="space-y-4">
                            <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 text-xl font-bold text-gray-900 transition-colors hover:text-racing-orange">
                                <i class="ph-bold ph-user-circle text-2xl text-racing-orange"></i>
                                Akun Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 text-center text-lg font-bold text-red-500 bg-red-50 rounded-2xl transition-colors hover:bg-red-100">{{ $profil->nav_keluar ?? 'Keluar' }}</button>
                            </form>
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

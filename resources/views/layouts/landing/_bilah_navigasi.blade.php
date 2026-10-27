{{-- Navbar Utama (Layout Central) dengan Alpine.js - Clean Modern White --}}
<nav class="fixed top-0 w-full z-[100] bg-white/90 border-b border-slate-200/80 text-slate-900 backdrop-blur-md shadow-sm transition-all duration-300"
     x-data="{ mobileMenuOpen: false }" @keydown.escape="mobileMenuOpen = false">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex justify-between items-center">
        {{-- Logo --}}
        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0">
            <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-[#ea580c] to-[#f97316] rounded-xl flex items-center justify-center text-white shadow-md shrink-0">
                <i class="ph-bold ph-sketch-logo text-xl sm:text-2xl"></i>
            </div>
            <span class="font-extrabold text-base sm:text-xl tracking-tight uppercase text-slate-950 truncate max-w-[170px] sm:max-w-none">{{ $profil->nama_perusahaan ?? \App\Helpers\StaticContent::APP_NAME }}</span>
        </a>
        
        {{-- Menu Navigasi Desktop --}}
        <div class="hidden md:flex items-center gap-8 lg:gap-10">
            <a href="{{ route('home') }}" class="text-sm font-semibold {{ Request::routeIs('home') ? 'nav-link-active' : 'text-slate-600 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
            <a href="{{ route('layanan') }}" class="text-sm font-semibold {{ Request::routeIs('layanan') ? 'nav-link-active' : 'text-slate-600 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
            <a href="{{ route('galeri.user') }}" class="text-sm font-semibold {{ Request::routeIs('galeri.user') ? 'nav-link-active' : 'text-slate-600 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
            <a href="{{ route('tentang-kami') }}" class="text-sm font-semibold {{ Request::routeIs('tentang-kami') ? 'nav-link-active' : 'text-slate-600 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
            <a href="{{ route('testimoni.index') }}" class="text-sm font-semibold {{ Request::routeIs('testimoni.index') ? 'nav-link-active' : 'text-slate-600 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TESTIMONI }}</a>
            
            <div class="flex items-center gap-4 border-l pl-6 border-slate-200">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-[#ea580c] transition-colors">
                        {{ \App\Helpers\StaticContent::NAV_MASUK }}
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full text-xs font-montserrat font-medium uppercase tracking-wider text-white bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 transition-all hover:scale-105 shadow-md shadow-orange-500/20 active:scale-95">
                        {{ \App\Helpers\StaticContent::NAV_DAFTAR }}
                    </a>
                @endguest
                @auth
                    <a href="{{ route('katalog.user') }}" class="px-6 py-2.5 rounded-full text-xs font-montserrat font-medium uppercase tracking-wider text-white bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 transition-all hover:scale-105 shadow-md shadow-orange-500/20 active:scale-95">
                        {{ \App\Helpers\StaticContent::NAV_PESANAN }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-orange-50 hover:text-[#ea580c] transition-all" title="{{ \App\Helpers\StaticContent::NAV_DASHBOARD }}">
                        <i class="ph-bold ph-user-circle text-xl text-[#ea580c]"></i>
                    </a>
                    <a href="{{ route('logout.get') }}" class="text-sm font-bold text-red-500 hover:text-red-600 transition-colors">
                        {{ \App\Helpers\StaticContent::NAV_KELUAR }}
                    </a>
                @endauth
            </div>
        </div>

        {{-- Tombol Mobile Menu --}}
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-900 cursor-pointer transition-transform active:scale-90">
            <i class="ph-bold" :class="mobileMenuOpen ? 'ph-x text-3xl text-[#ea580c]' : 'ph-list text-3xl'"></i>
        </button>
    </div>

    {{-- Mobile Menu Overlay (Alpine.js controlled) --}}
    <div x-show="mobileMenuOpen" 
         class="fixed inset-x-0 top-20 bg-white/95 border-b border-slate-200 text-slate-900 backdrop-blur-xl shadow-2xl transition-all duration-300 md:hidden overflow-y-auto max-h-[calc(100vh-5rem)]"
         x-transition
         @click.outside="mobileMenuOpen = false">
        <div class="p-6 sm:p-8 space-y-5 sm:space-y-6">
            <div class="space-y-2">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="block text-lg sm:text-xl font-bold {{ Request::routeIs('home') ? 'text-[#ea580c]' : 'text-slate-700 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
                <a href="{{ route('layanan') }}" @click="mobileMenuOpen = false" class="block text-lg sm:text-xl font-bold {{ Request::routeIs('layanan') ? 'text-[#ea580c]' : 'text-slate-700 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_LAYANAN }}</a>
                <a href="{{ route('galeri.user') }}" @click="mobileMenuOpen = false" class="block text-lg sm:text-xl font-bold {{ Request::routeIs('galeri.user') ? 'text-[#ea580c]' : 'text-slate-700 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
                <a href="{{ route('tentang-kami') }}" @click="mobileMenuOpen = false" class="block text-lg sm:text-xl font-bold {{ Request::routeIs('tentang-kami') ? 'text-[#ea580c]' : 'text-slate-700 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TENTANG }}</a>
                <a href="{{ route('testimoni.index') }}" @click="mobileMenuOpen = false" class="block text-lg sm:text-xl font-bold {{ Request::routeIs('testimoni.index') ? 'text-[#ea580c]' : 'text-slate-700 hover:text-[#ea580c]' }} transition-colors">{{ \App\Helpers\StaticContent::NAV_TESTIMONI }}</a>
            </div>
            
            <div class="pt-5 sm:pt-6 border-t border-slate-200">
                @guest
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-3 sm:py-3.5 bg-slate-100 rounded-2xl font-montserrat font-medium text-xs text-slate-800 border border-slate-200 transition-colors hover:bg-slate-200">{{ \App\Helpers\StaticContent::NAV_MASUK }}</a>
                        <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-3 sm:py-3.5 bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white rounded-2xl font-montserrat font-medium text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 transition-transform active:scale-95">{{ \App\Helpers\StaticContent::NAV_DAFTAR }}</a>
                    </div>
                @endguest
                @auth
                    <div class="grid grid-cols-1 gap-2.5 sm:gap-3">
                        <a href="{{ route('katalog.user') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-3 sm:py-3.5 bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white font-montserrat font-medium rounded-2xl shadow-md shadow-orange-500/20 uppercase tracking-wider text-xs">{{ \App\Helpers\StaticContent::NAV_PESANAN }}</a>
                        <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-3 sm:py-3.5 bg-slate-100 rounded-2xl font-montserrat font-medium text-xs text-slate-800 border border-slate-200 transition-colors hover:bg-slate-200">{{ \App\Helpers\StaticContent::NAV_DASHBOARD }}</a>
                        <a href="{{ route('logout.get') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-2.5 sm:py-3 bg-red-50 text-red-600 rounded-2xl font-montserrat font-medium text-xs border border-red-200 transition-colors hover:bg-red-100">{{ \App\Helpers\StaticContent::NAV_KELUAR }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

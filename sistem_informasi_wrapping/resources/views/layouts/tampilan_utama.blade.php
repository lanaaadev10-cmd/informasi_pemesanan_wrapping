<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @php
        $is_frontend = in_array(Route::currentRouteName(), ['home', 'profil.perusahaan', 'galeri.user', 'katalog.user', 'tentang-kami', 'layanan']);
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ !empty($profil->meta_description) ? $profil->meta_description : (!empty($profil->deskripsi) ? $profil->deskripsi : 'Penyedia layanan stiker dan wrapping kendaraan premium bergaransi resmi.') }}">
    <meta name="keywords" content="stiker mobil, wrapping mobil, branding kendaraan, dantie sticker">
    <meta name="author" content="{{ $profil->nama_perusahaan ?? 'Altra' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ $profil->meta_title ?? ($profil->nama_perusahaan ?? 'Official Website') }}</title>
    
    <!-- Preconnect to external origins to speed up connection handshake -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Tipografi Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon & Animasi (Deferred load to prevent render-blocking) -->
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root{--accent:{{ $profil->accent_color ?? '#f2994a' }}}
        [x-cloak]{display:none!important}
        .swal-dark{background:#121212!important;color:#fff!important;border:1px solid rgba(255,255,255,.1)!important;border-radius:24px!important}
        .swal-title{color:#f2994a!important}
        .swal-dark .swal2-html-container{color:#d1d5db!important}
        .swal2-timer-progress-bar{background:#f2994a!important}
    </style>
</head>
<body class="antialiased overflow-x-hidden font-['Plus_Jakarta_Sans',sans-serif]">
    {{-- Preloader Element --}}
    <div id="preloader" class="fixed inset-0 flex items-center justify-center z-[9999] transition-opacity duration-500" style="background-color:{{$is_frontend?'#0a0a0a':'#ffffff'}}">
        <span class="inline-block w-12 h-12 rounded-full animate-spin" style="border:5px solid {{$is_frontend?'#1f2937':'#fff'}};border-top-color:transparent;border-bottom-color:{{$is_frontend?'#f2994a':'#ea580c'}}"></span>
    </div>

    {{-- Navbar Utama (Layout Central) dengan Alpine.js --}}
    <nav class="fixed top-0 w-full z-[100] {{ $is_frontend ? 'bg-[#0a0a0a]/85 border-b border-white/5 text-white' : 'bg-white/80 border-b border-gray-100 text-gray-900' }} backdrop-blur-md transition-all duration-300"
         x-data="mobileMenu()" @keydown.escape.window="closeMenu()">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-3">
                @if($is_frontend)
                    <div class="w-10 h-10 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                @else
                    @if($profil && $profil->logo)
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" width="40" height="40" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white">
                            <i class="ph-bold ph-sketch-logo text-2xl"></i>
                        </div>
                    @endif
                @endif
                <span class="font-bold text-xl tracking-tight uppercase {{ $is_frontend ? 'text-white' : 'text-gray-900' }}">{{ $profil->nama_perusahaan ?? 'Wapping' }}</span>
            </a>
            
            {{-- Menu Navigasi Desktop --}}
            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ Request::routeIs('home') ? ($is_frontend ? 'text-[#f2994a]' : 'text-orange-600') . ' font-extrabold' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">{{ $profil->nav_beranda ?? 'Beranda' }}</a>
                <a href="{{ route('layanan') }}" class="text-sm font-medium {{ Request::routeIs('layanan') ? ($is_frontend ? 'text-[#f2994a]' : 'text-orange-600') . ' font-extrabold' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">{{ $profil->nav_layanan ?? 'Layanan' }}</a>
                <a href="{{ route('galeri.user') }}" class="text-sm font-medium {{ Request::routeIs('galeri.user') ? ($is_frontend ? 'text-[#f2994a]' : 'text-orange-600') . ' font-extrabold' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">{{ $profil->nav_galeri ?? 'Galeri' }}</a>
                <a href="{{ route('tentang-kami') }}" class="text-sm font-medium {{ Request::routeIs('tentang-kami') ? ($is_frontend ? 'text-[#f2994a]' : 'text-orange-600') . ' font-extrabold' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">{{ $profil->nav_tentang_kami ?? 'Tentang Kami' }}</a>
                
                @if($is_frontend)
                    <div class="flex items-center gap-4 border-l pl-6 border-white/10">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-[#f2994a] transition-colors">
                                {{ $profil->nav_masuk ?? 'Masuk' }}
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-[#f2994a] hover:bg-[#e28a44] transition-all hover:scale-105 shadow-md">
                                {{ $profil->nav_daftar ?? 'Daftar' }}
                            </a>
                        @endguest
                        @auth
                            <a href="{{ route('katalog.user') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-[#f2994a] hover:bg-[#e28a44] transition-all hover:scale-105 shadow-md">
                                {{ $profil->nav_pemesanan ?? 'Pemesanan' }}
                            </a>
                            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all" title="{{ $profil->nav_dashboard ?? 'Dashboard' }}">
                                <i class="ph-bold ph-user-circle text-xl text-[#f2994a]"></i>
                            </a>
                            <a href="{{ route('logout.get') }}" class="text-sm font-bold text-red-500 hover:text-red-400 transition-colors">
                                {{ $profil->nav_keluar ?? 'Keluar' }}
                            </a>
                        @endauth
                    </div>
                @else
                    @guest
                        <div class="flex items-center gap-6 border-l pl-8 border-gray-100">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-orange-600 transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="text-white px-8 py-2.5 rounded-full text-sm font-bold transition-all hover:scale-105 active:scale-95 bg-gradient-to-r from-[#e28a44] to-[#f2994a]">
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

            {{-- Tombol Hamburger --}}
            <button @click="toggleMenu()" 
                    :class="{'is-active': mobileMenuOpen}"
                    class="hamburger-btn md:hidden relative w-10 h-10 flex items-center justify-center cursor-pointer z-[9999] transition-transform active:scale-90">
                <div class="hamburger-box relative w-6 h-5">
                    <span class="hamburger-line absolute left-0 top-0 w-6 h-0.5 {{ $is_frontend ? 'bg-white' : 'bg-gray-900' }} rounded-full transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                          :class="mobileMenuOpen ? 'top-1/2 -translate-y-1/2 rotate-45' : ''"></span>
                    <span class="hamburger-line absolute left-0 top-1/2 -translate-y-1/2 w-6 h-0.5 {{ $is_frontend ? 'bg-white' : 'bg-gray-900' }} rounded-full transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                          :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                    <span class="hamburger-line absolute left-0 bottom-0 w-6 h-0.5 {{ $is_frontend ? 'bg-white' : 'bg-gray-900' }} rounded-full transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                          :class="mobileMenuOpen ? 'top-1/2 -translate-y-1/2 -rotate-45' : ''"></span>
                </div>
            </button>
        </div>

        {{-- Overlay --}}
        <div x-show="mobileMenuOpen"
             x-cloak
             class="fixed inset-0 z-[9990] bg-black/60 backdrop-blur-[2px] md:hidden transition-opacity duration-300"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeMenu()">
        </div>

        {{-- Panel Mobile Menu --}}
        <div x-show="mobileMenuOpen"
             x-cloak
             class="fixed top-0 right-0 z-[9995] h-screen w-full sm:w-80 bg-[#0a0a0a] shadow-2xl md:hidden flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] overflow-y-auto"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            {{-- Header Panel --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/5 shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-3" @click="closeMenu()">
                    <div class="w-9 h-9 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="ph-bold ph-sketch-logo text-xl"></i>
                    </div>
                    <span class="font-extrabold text-sm tracking-wider text-white uppercase">DANTIE WRAPPING</span>
                </a>
                <button @click="closeMenu()" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-all">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            {{-- Menu Items --}}
            <div class="flex-1 px-4 py-6 space-y-1">
                <template x-for="(item, index) in menuItems" :key="index">
                    <a :href="item.url"
                       @click="closeMenu()"
                       class="menu-link flex items-center gap-4 px-5 py-4 rounded-2xl text-lg font-semibold text-white hover:text-[#f97316] transition-all duration-200 group relative overflow-hidden"
                       :class="{'bg-[#f97316]/10 text-[#f97316]': item.active, 'hover:bg-white/[0.02]': !item.active}">
                        <i :class="'ph-bold ph-' + item.icon + ' text-xl text-gray-400 group-hover:text-[#f97316] transition-colors'"></i>
                        <span x-text="item.label"></span>
                        <span class="absolute left-0 bottom-0 h-0.5 bg-[#f97316] w-0 group-hover:w-full transition-all duration-300 ease-out"></span>
                    </a>
                </template>
            </div>

            {{-- Divider --}}
            <div class="mx-6 border-t border-white/5"></div>

            {{-- Auth Buttons --}}
            @if($is_frontend)
                @guest
                <div class="px-6 py-6 space-y-3 shrink-0">
                    <a href="{{ route('login') }}" @click="closeMenu()" 
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-bold text-white border border-white/30 hover:bg-white hover:text-black transition-all duration-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-extrabold text-white transition-all duration-200 uppercase tracking-wider" style="background-color:#f97316">
                        Daftar
                    </a>
                </div>
                @endguest
                @auth
                <div class="px-6 py-6 space-y-3 shrink-0">
                    <a href="{{ route('dashboard') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-extrabold text-white transition-all duration-200 uppercase tracking-wider" style="background-color:#f97316">
                        Dashboard
                    </a>
                    <a href="{{ route('logout.get') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-bold text-red-400 border border-red-500/30 hover:bg-red-500/10 transition-all duration-200">
                        Keluar
                    </a>
                </div>
                @endauth
            @else
                @guest
                <div class="px-6 py-6 space-y-3 shrink-0">
                    <a href="{{ route('login') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-bold text-white border border-white/30 hover:bg-white hover:text-black transition-all duration-200">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-extrabold text-white transition-all duration-200 uppercase tracking-wider" style="background-color:#f97316">
                        Daftar
                    </a>
                </div>
                @endguest
                @auth
                <div class="px-6 py-6 space-y-3 shrink-0">
                    <a href="{{ route('dashboard') }}" @click="closeMenu()"
                       class="block w-full text-center py-3.5 px-6 rounded-xl text-sm font-extrabold text-white transition-all duration-200 uppercase tracking-wider" style="background-color:#f97316">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-center py-3.5 px-6 rounded-xl text-sm font-bold text-red-400 border border-red-500/30 hover:bg-red-500/10 transition-all duration-200">
                            Keluar
                        </button>
                    </form>
                </div>
                @endauth
            @endif

            {{-- Footer Sosial Media --}}
            <div class="px-6 py-5 border-t border-white/5 shrink-0">
                <div class="flex items-center justify-center gap-6">
                    <a href="https://instagram.com/{{ $profil->instagram ?? 'dantiewrapping' }}" target="_blank" class="text-gray-400 hover:text-[#f97316] transition-colors">
                        <i class="ph-bold ph-instagram-logo text-xl"></i>
                    </a>
                    <a href="mailto:{{ $profil->email ?? 'info@dantiewrapping.com' }}" class="text-gray-400 hover:text-[#f97316] transition-colors">
                        <i class="ph-bold ph-envelope text-xl"></i>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6281234567890') }}" target="_blank" class="text-gray-400 hover:text-[#f97316] transition-colors">
                        <i class="ph-bold ph-whatsapp-logo text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="{{ (Request::routeIs('home') || Request::routeIs('profil.perusahaan') || Request::routeIs('layanan')) ? 'pt-0' : 'pt-32' }}">
        @yield('content')
    </main>

    {{-- Baru saya tambah: Footer Utama (Layout Central) --}}
    @if($is_frontend)
        <footer class="bg-[#060606] pt-20 pb-12 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6 text-center">
                {{-- Brand Logo/Name --}}
                <div class="mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 justify-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="ph-bold ph-sketch-logo text-2xl"></i>
                        </div>
                        <span class="font-extrabold text-2xl tracking-wider text-white uppercase">{{ $profil->nama_perusahaan ?? 'Wapping' }}</span>
                    </a>
                </div>

                {{-- Horizontal Nav Links --}}
                <div class="flex flex-wrap justify-center gap-8 md:gap-12 mb-10 text-sm font-medium text-gray-400">
                    <a href="{{ route('tentang-kami') }}" class="hover:text-[#f2994a] transition-all">{{ $profil->footer_tentang ?? 'Tentang Kami' }}</a>
                    <a href="{{ route('katalog.user') }}" class="hover:text-[#f2994a] transition-all">{{ $profil->footer_layanan ?? 'Layanan' }}</a>
                    <a href="#" class="hover:text-[#f2994a] transition-all">{{ $profil->footer_kebijakan_privasi ?? 'Kebijakan Privasi' }}</a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" class="hover:text-[#f2994a] transition-all">{{ $profil->footer_hubungi_kami ?? 'Hubungi Kami' }}</a>
                </div>

                {{-- Social Icons --}}
                <div class="flex justify-center gap-6 mb-10">
                    @if($profil && $profil->instagram_url)
                        <a href="{{ $profil->instagram_url }}" target="_blank" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-instagram-logo text-lg"></i>
                        </a>
                    @else
                        <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-instagram-logo text-lg"></i>
                        </a>
                    @endif
                    @if($profil && $profil->email)
                        <a href="mailto:{{ $profil->email }}" aria-label="Email" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-envelope text-lg"></i>
                        </a>
                    @else
                        <a href="#" aria-label="Email" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-envelope text-lg"></i>
                        </a>
                    @endif
                    @if($profil && $profil->nomor_telepon)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon) }}" target="_blank" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                        </a>
                    @else
                        <a href="#" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                        </a>
                    @endif
                </div>

                {{-- Copyright Notice --}}
                <div class="pt-8 border-t border-white/5 text-gray-500 text-xs font-medium">
                    <p>{{ $profil->footer_copyright ?? '&copy; 2026 Dantie Wrapping. Hak Cipta Dilindungi.' }}</p>
                </div>
            </div>
        </footer>
    @else
        <footer class="bg-gray-50 pt-24 pb-12 border-t border-gray-100 mt-24">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-16 mb-20">
                    <div class="col-span-1 md:col-span-1">
                        <h5 class="font-bold text-xl text-gray-900 mb-8 uppercase">{{ $profil->nama_perusahaan ?? 'Dantie' }}</h5>
                        <p class="text-gray-500 leading-relaxed text-sm">
                            {{ $profil->deskripsi ?? 'Solusi stiker terbaik untuk kendaraan dan bisnis Anda.' }}
                        </p>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_navigasi ?? 'Navigasi' }}</h5>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li><a href="{{ route('home') }}" class="hover:text-orange-600 transition-all">{{ $profil->nav_beranda ?? 'Beranda' }}</a></li>
                            <li><a href="{{ route('tentang-kami') }}" class="hover:text-orange-600 transition-all">{{ $profil->nav_tentang_kami ?? 'Tentang Kami' }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_hubungi_kami ?? 'Hubungi Kami' }}</h5>
                        <ul class="space-y-4 text-sm font-medium text-gray-500">
                            <li class="flex items-center gap-3">
                                <i class="ph ph-envelope-simple text-orange-600 text-lg"></i>
                                {{ $profil->email ?? '-' }}
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="ph ph-phone text-orange-600 text-lg"></i>
                                {{ $profil->nomor_telepon ?? '-' }}
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_lokasi ?? 'Lokasi Kami' }}</h5>
                        <p class="text-gray-500 text-sm leading-relaxed italic">
                            {{ $profil->alamat ?? 'Alamat belum diatur' }}
                        </p>
                    </div>
                </div>
                
                <div class="pt-12 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-gray-400 font-medium">
                    <p>{{ $profil->footer_copyright ?? '&copy; 2026 Dantie Sticker. All rights reserved.' }}</p>
                    <div class="flex gap-6 items-center">
                        @if($profil && $profil->instagram_url)
                            <a href="{{ $profil->instagram_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-instagram-logo"></i> {{ $profil->footer_instagram ?? 'Instagram' }}</a>
                        @endif
                        @if($profil && $profil->facebook_url)
                            <a href="{{ $profil->facebook_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-facebook-logo"></i> {{ $profil->footer_facebook ?? 'Facebook' }}</a>
                        @endif
                        @if($profil && $profil->tiktok_url)
                            <a href="{{ $profil->tiktok_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-tiktok-logo"></i> TikTok</a>
                        @endif
                        @if($profil && $profil->whatsapp_link)
                            <a href="{{ $profil->whatsapp_link }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-whatsapp-logo"></i> WhatsApp</a>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
    @endif

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Hide Preloader on DOMContentLoaded for faster Paint metrics (FCP/LCP)
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

        // Alpine.js Mobile Menu Component
        document.addEventListener('alpine:init', () => {
            Alpine.data('mobileMenu', () => ({
                mobileMenuOpen: false,
                menuItems: [
                    { url: '{{ route("home") }}', label: '{{ $profil->nav_beranda ?? "Beranda" }}', icon: 'house', active: {{ Request::routeIs("home") ? 'true' : 'false' }}, delay: 0 },
                    { url: '{{ route("layanan") }}', label: '{{ $profil->nav_layanan ?? "Layanan" }}', icon: 'gear-six', active: {{ Request::routeIs("layanan") ? 'true' : 'false' }}, delay: 50 },
                    { url: '{{ route("galeri.user") }}', label: '{{ $profil->nav_galeri ?? "Galeri" }}', icon: 'image-square', active: {{ Request::routeIs("galeri.*") ? 'true' : 'false' }}, delay: 100 },
                    { url: '{{ route("tentang-kami") }}', label: '{{ $profil->nav_tentang_kami ?? "Tentang Kami" }}', icon: 'info', active: {{ Request::routeIs("tentang-kami") ? 'true' : 'false' }}, delay: 150 }
                ],
                toggleMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                    document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
                },
                closeMenu() {
                    this.mobileMenuOpen = false;
                    document.body.style.overflow = '';
                }
            }));
        });
    </script>

    @stack('scripts')
</body>
</html>

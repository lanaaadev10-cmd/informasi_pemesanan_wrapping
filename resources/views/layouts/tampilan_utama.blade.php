<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @php
        $is_frontend = in_array(Route::currentRouteName(), ['home', 'profil.perusahaan', 'galeri.user', 'katalog.user', 'tentang-kami', 'layanan']);
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Penyedia layanan stiker dan wrapping kendaraan premium bergaransi resmi.">
    <meta name="keywords" content="stiker mobil, wrapping mobil, branding kendaraan, dantie sticker">
    <meta name="author" content="{{ $profil->nama_perusahaan ?? 'Altra' }}">
    <title>@yield('title') - Wapping Premium Wrap</title>
    
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

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: {{ $is_frontend ? '#0a0a0a' : '#ffffff' }};
            color: {{ $is_frontend ? '#ffffff' : '#1a1a1a' }};
        }
        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: {{ $is_frontend ? '#0a0a0a' : '#ffffff' }};
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid {{ $is_frontend ? '#1f2937' : '#fff' }};
            border-bottom-color: {{ $is_frontend ? '#f2994a' : '#ea580c' }};
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }
        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .text-gradient {
            background: linear-gradient(135deg, {{ $is_frontend ? '#f2994a 0%, #e28a44 100%' : '#ea580c 0%, #9a3412 100%' }});
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-premium {
            background: linear-gradient(135deg, {{ $is_frontend ? '#e28a44 0%, #f2994a 100%' : '#ea580c 0%, #c2410c 100%' }});
            box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.3);
        }
        .soft-card {
            background: {{ $is_frontend ? '#121212' : '#ffffff' }};
            border: 1px solid {{ $is_frontend ? '#1f2937' : '#f3f4f6' }};
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-radius: 24px;
        }
        .nav-link-active {
            color: {{ $is_frontend ? '#f2994a' : '#ea580c' }};
            font-weight: 800;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Preloader Element --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    {{-- Navbar Utama (Layout Central) dengan Alpine.js --}}
    <nav class="fixed top-0 w-full z-[100] {{ $is_frontend ? 'bg-[#0a0a0a]/85 border-b border-white/5 text-white' : 'bg-white/80 border-b border-gray-100 text-gray-900' }} backdrop-blur-md transition-all duration-300"
         x-data="{ mobileMenuOpen: false }" @keydown.escape="mobileMenuOpen = false">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-3">
                @if($is_frontend)
                    <div class="w-10 h-10 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                @else
                    @if(!empty($profil->logo))
                        <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" width="40" height="40" class="h-10 w-auto">
                    @else
                        <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white">
                            <i class="ph-bold ph-sketch-logo text-2xl"></i>
                        </div>
                    @endif
                @endif
                <span class="font-bold text-xl tracking-tight uppercase {{ $is_frontend ? 'text-white' : 'text-gray-900' }}">Wapping</span>
            </a>
            
            {{-- Menu Navigasi Desktop --}}
            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ Request::routeIs('home') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">Beranda</a>
                <a href="{{ route('layanan') }}" class="text-sm font-medium {{ Request::routeIs('layanan') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">Layanan</a>
                <a href="{{ route('galeri.user') }}" class="text-sm font-medium {{ Request::routeIs('galeri.user') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">Galeri</a>
                <a href="{{ route('tentang-kami') }}" class="text-sm font-medium {{ Request::routeIs('tentang-kami') ? 'nav-link-active' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-500 hover:text-orange-600') }} transition-colors">Tentang Kami</a>
                
                @if($is_frontend)
                    <div class="flex items-center gap-4 border-l pl-6 border-white/10">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-300 hover:text-[#f2994a] transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-[#f2994a] hover:bg-[#e28a44] transition-all hover:scale-105 shadow-md">
                                Daftar
                            </a>
                        @endguest
                        @auth
                            <a href="{{ route('katalog.user') }}" class="px-6 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider text-black bg-[#f2994a] hover:bg-[#e28a44] transition-all hover:scale-105 shadow-md">
                                Pemesanan
                            </a>
                            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all" title="Dashboard">
                                <i class="ph-bold ph-user-circle text-xl text-[#f2994a]"></i>
                            </a>
                            <a href="{{ route('logout.get') }}" class="text-sm font-bold text-red-500 hover:text-red-400 transition-colors">
                                Keluar
                            </a>
                        @endauth
                    </div>
                @else
                    @guest
                        <div class="flex items-center gap-6 border-l pl-8 border-gray-100">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-orange-600 transition-colors">Login</a>
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
             class="fixed inset-x-0 top-20 {{ $is_frontend ? 'bg-[#0a0a0a]/95 border-b border-white/5 text-white' : 'bg-white/95 border-b border-gray-100 text-gray-900' }} backdrop-blur-xl shadow-2xl transition-all duration-300 md:hidden overflow-y-auto max-h-[calc(100vh-5rem)]"
             x-transition
             @click.outside="mobileMenuOpen = false">
            <div class="p-8 space-y-6">
                <div class="space-y-1">
                    <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('home') ? 'text-[#f2994a]' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-600') }} transition-colors">Beranda</a>
                    <a href="{{ route('layanan') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('layanan') ? 'text-[#f2994a]' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-900') }} transition-colors">Layanan</a>
                    <a href="{{ route('galeri.user') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('galeri.user') ? 'text-[#f2994a]' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-900') }} transition-colors">Galeri</a>
                    <a href="{{ route('tentang-kami') }}" @click="mobileMenuOpen = false" class="block text-2xl font-bold {{ Request::routeIs('tentang-kami') ? 'text-[#f2994a]' : ($is_frontend ? 'text-gray-300 hover:text-[#f2994a]' : 'text-gray-900') }} transition-colors">Tentang Kami</a>
                </div>
                
                <div class="pt-6 border-t {{ $is_frontend ? 'border-white/5' : 'border-gray-100' }}">
                    @if($is_frontend)
                        @guest
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-white/5 rounded-2xl font-bold text-gray-300 border border-white/10 transition-colors hover:bg-white/10">Masuk</a>
                                <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 btn-premium text-white rounded-2xl font-bold shadow-lg transition-transform hover:scale-105">Daftar</a>
                            </div>
                        @endguest
                        @auth
                            <div class="grid grid-cols-1 gap-4">
                                <a href="{{ route('katalog.user') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-[#f2994a] text-black font-extrabold rounded-2xl shadow-lg uppercase tracking-wider text-sm transition-transform hover:scale-105">Pemesanan</a>
                                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-white/5 rounded-2xl font-bold text-gray-300 border border-white/10 transition-colors hover:bg-white/10">Dashboard</a>
                                <a href="{{ route('logout.get') }}" @click="mobileMenuOpen = false" class="flex items-center justify-center py-4 bg-red-500/10 text-red-500 rounded-2xl font-bold border border-red-500/20 transition-colors hover:bg-red-500/20">Keluar</a>
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
                                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 text-xl font-bold text-gray-900 transition-colors hover:text-orange-600">
                                    <i class="ph-bold ph-user-circle text-2xl text-orange-600"></i>
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
                        <span class="font-extrabold text-2xl tracking-wider text-white uppercase">Wapping</span>
                    </a>
                </div>

                {{-- Horizontal Nav Links --}}
                <div class="flex flex-wrap justify-center gap-8 md:gap-12 mb-10 text-sm font-medium text-gray-400">
                    <a href="{{ route('profil.perusahaan') }}" class="hover:text-[#f2994a] transition-all">Tentang Kami</a>
                    <a href="{{ route('katalog.user') }}" class="hover:text-[#f2994a] transition-all">Layanan</a>
                    <a href="#" class="hover:text-[#f2994a] transition-all">Kebijakan Privasi</a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" class="hover:text-[#f2994a] transition-all">Hubungi Kami</a>
                </div>

                {{-- Social Icons --}}
                <div class="flex justify-center gap-6 mb-10">
                    <a href="https://instagram.com/wapping.id" target="_blank" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                        <i class="ph-bold ph-instagram-logo text-lg"></i>
                    </a>
                    @if(!empty($profil->email))
                        <a href="mailto:{{ $profil->email }}" aria-label="Email" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-envelope text-lg"></i>
                        </a>
                    @else
                        <a href="#" aria-label="Email" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] hover:bg-white/10 transition-all duration-300">
                            <i class="ph-bold ph-envelope text-lg"></i>
                        </a>
                    @endif
                    @if(!empty($profil->nomor_telepon))
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
                    <p>&copy; 2026 Wapping Premium Wrapping. Hak Cipta Dilindungi.</p>
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
                            <li><a href="{{ route('profil.perusahaan') }}" class="hover:text-orange-600 transition-all">{{ $profil->nav_profil_perusahaan ?? 'Profil Perusahaan' }}</a></li>
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
                        @if(!empty($profil->instagram_url))
                            <a href="{{ $profil->instagram_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-instagram-logo"></i> {{ $profil->footer_instagram ?? 'Instagram' }}</a>
                        @endif
                        @if(!empty($profil->facebook_url))
                            <a href="{{ $profil->facebook_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-facebook-logo"></i> {{ $profil->footer_facebook ?? 'Facebook' }}</a>
                        @endif
                        @if(!empty($profil->tiktok_url))
                            <a href="{{ $profil->tiktok_url }}" target="_blank" class="hover:text-orange-600 transition-colors flex items-center gap-1"><i class="ph-bold ph-tiktok-logo"></i> TikTok</a>
                        @endif
                        @if(!empty($profil->whatsapp_link))
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

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            
            if (menu.classList.contains('opacity-0')) {
                // Open
                menu.classList.remove('opacity-0', '-translate-y-full', 'pointer-events-none');
                menu.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                icon.classList.remove('ph-list');
                icon.classList.add('ph-x');
                document.body.style.overflow = 'hidden'; // Prevent scroll
            } else {
                // Close
                menu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
                document.body.style.overflow = ''; // Restore scroll
            }
        }

        // Close menu on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                const menu = document.getElementById('mobile-menu');
                const icon = document.getElementById('menu-icon');
                menu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>

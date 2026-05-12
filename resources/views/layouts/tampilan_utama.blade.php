<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $profil->deskripsi ?? 'Penyedia layanan stiker dan wrapping kendaraan premium.' }}">
    <meta name="keywords" content="stiker mobil, wrapping mobil, branding kendaraan, dantie sticker">
    <meta name="author" content="{{ $profil->nama_perusahaan ?? 'Altra' }}">
    <title>@yield('title') - {{ $profil->nama_perusahaan ?? 'Official Website' }}</title>
    
    <!-- Tipografi Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon & Animasi -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
        }
        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid #fff;
            border-bottom-color: #ea580c;
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
            background: linear-gradient(135deg, #ea580c 0%, #9a3412 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-premium {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            box-shadow: 0 10px 20px -5px rgba(234, 88, 12, 0.3);
        }
        .soft-card {
            background: #ffffff;
            border: 1px solid #f3f4f6;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-radius: 24px;
        }
        .nav-link-active {
            color: #ea580c;
            font-weight: 800;
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Preloader Element --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    {{-- Baru saya tambah: Navbar Utama (Layout Central) --}}
    <nav class="fixed top-0 w-full z-[100] bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-3">
                @if($profil && $profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                @endif
                <span class="font-bold text-xl tracking-tight uppercase">{{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}</span>
            </a>
            
            {{-- Menu Navigasi Desktop --}}
            <div class="hidden md:flex items-center gap-10">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="text-sm font-medium {{ (Request::routeIs('home') || Request::routeIs('dashboard')) ? 'nav-link-active' : 'text-gray-500' }} hover:text-orange-600 transition-colors">Beranda</a>
                <a href="{{ route('profil.perusahaan') }}" class="text-sm font-medium {{ Request::routeIs('profil.perusahaan') ? 'nav-link-active' : 'text-gray-500' }} hover:text-orange-600 transition-colors">Profil Perusahaan</a>
                <a href="{{ route('galeri.user') }}" class="text-sm font-medium {{ Request::routeIs('galeri.user') ? 'nav-link-active' : 'text-gray-500' }} hover:text-orange-600 transition-colors">Galeri</a>
                <a href="{{ route('katalog.user') }}" class="text-sm font-medium {{ Request::routeIs('katalog.user') ? 'nav-link-active' : 'text-gray-500' }} hover:text-orange-600 transition-colors">Katalog</a>
                
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
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600">Keluar</button>
                        </form>
                    </div>
                @endauth
            </div>

            {{-- Tombol Mobile Menu (Pure CSS Hack) --}}
            <label for="menu-toggle" class="md:hidden p-2 text-gray-900 cursor-pointer transition-transform active:scale-90">
                <i class="ph-bold ph-list text-3xl" id="open-icon"></i>
            </label>
        </div>

        {{-- Hidden Checkbox for Toggle --}}
        <input type="checkbox" id="menu-toggle" class="hidden peer">

        {{-- Mobile Menu Overlay (Pure CSS via Tailwind Peer) --}}
        <div class="fixed inset-x-0 top-20 bg-white/95 backdrop-blur-xl border-b border-gray-100 shadow-2xl transition-all duration-300 transform -translate-y-full opacity-0 pointer-events-none peer-checked:translate-y-0 peer-checked:opacity-100 peer-checked:pointer-events-auto md:hidden overflow-y-auto max-h-[calc(100vh-5rem)]">
            <div class="p-8 space-y-6">
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block text-2xl font-bold {{ Request::routeIs('home') ? 'text-orange-600' : 'text-gray-900' }}">Beranda</a>
                    <a href="{{ route('profil.perusahaan') }}" class="block text-2xl font-bold {{ Request::routeIs('profil.perusahaan') ? 'text-orange-600' : 'text-gray-900' }}">Profil</a>
                    <a href="{{ route('galeri.user') }}" class="block text-2xl font-bold {{ Request::routeIs('galeri.user') ? 'text-orange-600' : 'text-gray-900' }}">Galeri</a>
                    <a href="{{ route('katalog.user') }}" class="block text-2xl font-bold {{ Request::routeIs('katalog.user') ? 'text-orange-600' : 'text-gray-900' }}">Katalog</a>
                </div>
                
                <div class="pt-6 border-t border-gray-100">
                    @guest
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}" class="flex items-center justify-center py-4 bg-gray-50 rounded-2xl font-bold text-gray-600">Login</a>
                            <a href="{{ route('register') }}" class="flex items-center justify-center py-4 btn-premium text-white rounded-2xl font-bold shadow-lg">Register</a>
                        </div>
                    @endguest

                    @auth
                        <div class="space-y-4">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-xl font-bold text-gray-900">
                                <i class="ph-bold ph-user-circle text-2xl text-orange-600"></i>
                                Akun Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 text-center text-lg font-bold text-red-500 bg-red-50 rounded-2xl">Keluar</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="pt-32">
        @yield('content')
    </main>

    {{-- Baru saya tambah: Footer Utama (Layout Central) --}}
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
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">Navigasi</h5>
                    <ul class="space-y-4 text-sm font-medium text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-orange-600 transition-all">Beranda</a></li>
                        <li><a href="{{ route('profil.perusahaan') }}" class="hover:text-orange-600 transition-all">Profil Perusahaan</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">Hubungi Kami</h5>
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
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">Lokasi Kami</h5>
                    <p class="text-gray-500 text-sm leading-relaxed italic">
                        {{ $profil->alamat ?? 'Alamat belum diatur' }}
                    </p>
                </div>
            </div>
            
            <div class="pt-12 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-gray-400 font-medium">
                <p>&copy; 2026 {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-orange-600 transition-colors">Instagram</a>
                    <a href="#" class="hover:text-orange-600 transition-colors">TikTok</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Hide Preloader
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.visibility = 'hidden';
            }, 500);
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Preconnect to external origins to speed up connection handshake -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Fonts: Audiowide (Display), Montserrat (Bold/UI), Questrial (Clean Body) -->
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Questrial&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --font-audiowide: 'Audiowide', cursive, sans-serif;
            --font-montserrat: 'Montserrat', sans-serif;
            --font-questrial: 'Questrial', sans-serif;
            --bg-carbon: #000000;
            --surface-dark: #0E0E10;
            --surface-elevated: #16161A;
            --border-glass: rgba(255, 255, 255, 0.08);
            --primary-orange: #FF6B00;
            --primary-orange-hover: #E05D00;
            --text-white: #FFFFFF;
            --text-muted: #8A8D93;
        }

        [x-cloak] {
            display: none !important;
        }

        body { 
            font-family: 'Questrial', 'Montserrat', sans-serif; 
            background-color: #000000;
            color: #ffffff;
        }

        h1, h2, h3, .font-heading, .brand-font {
            font-family: 'Audiowide', cursive, sans-serif;
        }

        .font-audiowide { font-family: 'Audiowide', cursive, sans-serif !important; }
        .font-montserrat { font-family: 'Montserrat', sans-serif !important; }
        .font-questrial { font-family: 'Questrial', sans-serif !important; }

        .sidebar-link-active { 
            background-color: #ff6b00; 
            color: #000000 !important; 
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.35);
        }

        /* Hide scrollbars for clean horizontal slider look */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Pengaturan Khusus agar Desktop tidak rusak oleh gaya HP */
        @media (min-width: 1024px) {
            #bottom-nav { display: none !important; }
        }
        /* Bottom Nav Mobile */
        #bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 8999;
            background: rgba(8, 8, 8, 0.95);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>
<body class="bg-black text-white antialiased font-questrial">

    <div class="min-h-screen flex flex-col bg-black">
        <!-- Top bar: Brand Identity, Modern Center Nav Links, Bell, Wishlist & Profile Dropdown -->
        <header class="py-3.5 px-4 sm:px-6 md:px-8 bg-black/85 sticky top-0 z-[100] backdrop-blur-md border-b border-white/5">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                
                <!-- Left: Brand Logo & Title -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-[#FF6B00]/10 border border-[#FF6B00]/30 flex items-center justify-center text-[#FF6B00] group-hover:scale-105 group-hover:border-[#FF6B00]/60 transition-all shadow-[0_0_12px_rgba(255,107,0,0.15)]">
                        <i class="ph-bold ph-steering-wheel text-xl"></i>
                    </div>
                    
                    <div class="flex flex-col justify-center min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wider leading-tight group-hover:text-[#FF6B00] transition-colors truncate">
                                {{ $profil->nama_perusahaan ?? 'Dantie Stiker' }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00] shrink-0"></span>
                        </div>
                        <span class="text-[9px] font-mono font-bold uppercase tracking-widest text-[#8A8D93] truncate">
                            {{ $profil->dashboard_subtitle ?? 'Car Wrapping & Detailing' }}
                        </span>
                    </div>
                </a>

                <!-- Center: Sleek Desktop Navigation Links (Clean Automotive Bar) -->
                <nav class="hidden lg:flex items-center gap-1 bg-[#16161A]/80 border border-white/10 rounded-2xl p-1.5 shadow-inner">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('dashboard') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Beranda
                    </a>
                    <a href="{{ route('katalog.user') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('katalog.*') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Katalog
                    </a>
                    <a href="{{ route('booking.index') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('booking.*') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Booking
                    </a>
                    <a href="{{ route('pesanan.index') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('pesanan.*') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Pesanan
                    </a>
                    <a href="{{ route('kalkulator.index') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('kalkulator.*') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Wrap Studio
                    </a>
                    <a href="{{ route('galeri.user') }}"
                       class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('galeri.*') ? 'bg-[#FF6B00] text-black shadow-md shadow-[#FF6B00]/25' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                        Galeri
                    </a>
                </nav>
                
                <!-- Right: Notification Bell, Wishlist & Desktop User Profile Menu -->
                <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                    <!-- Notification Bell & Dropdown -->
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-btn" onclick="toggleNotifPanel()" class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-[#141414] border border-white/10 flex items-center justify-center text-white hover:border-[#ff6b00]/50 hover:text-[#ff6b00] transition-all shadow-sm relative active:scale-95">
                            <i class="ph-bold ph-bell text-lg sm:text-xl"></i>
                            <span id="notif-badge" class="absolute -top-1 -right-1 hidden min-w-[18px] h-[18px] px-1 bg-[#ff6b00] text-white text-[9px] font-montserrat font-extrabold rounded-full flex items-center justify-center shadow-lg"></span>
                        </button>

                        <!-- Dropdown Panel -->
                        <div id="notif-panel" class="hidden fixed z-[9999] top-20 inset-x-4 w-auto sm:inset-x-auto sm:right-6 sm:w-96 bg-[#111111] border border-white/10 rounded-2xl shadow-2xl overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.8);">
                            <!-- Header -->
                            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-bell text-[#ff6b00]"></i>
                                    <span class="text-sm font-montserrat font-bold text-white">Notifikasi</span>
                                    <span id="notif-count-label" class="hidden text-[10px] font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider"></span>
                                </div>
                                <button onclick="markAllRead()" class="text-[10px] font-montserrat font-bold text-gray-400 hover:text-[#ff6b00] transition-colors uppercase tracking-wide">
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <!-- Notification List -->
                            <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-white/5 font-questrial">
                                <div class="flex flex-col items-center justify-center py-10 text-gray-500">
                                    <i class="ph ph-circle-notch ph-spin text-3xl mb-2 animate-spin text-[#ff6b00]"></i>
                                    <span class="text-xs">Memuat notifikasi...</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-5 py-3 border-t border-white/5 bg-[#0c0c0c]">
                                <a href="{{ route('pesanan.index') }}" class="text-[10px] font-montserrat font-bold text-gray-400 hover:text-[#ff6b00] transition-colors uppercase tracking-widest flex items-center justify-center gap-1.5">
                                    Lihat semua pesanan <i class="ph-bold ph-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Wishlist / Keranjang Button -->
                    <a href="{{ route('keranjang.index') }}" title="Keranjang Belanja" class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-[#141414] border border-white/10 flex items-center justify-center text-white hover:border-[#ff6b00]/50 hover:text-[#ff6b00] transition-all shadow-sm relative active:scale-95">
                        <i class="ph-bold ph-shopping-cart text-lg sm:text-xl"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-[#ff6b00] text-black text-[9px] font-montserrat font-black rounded-full flex items-center justify-center shadow-lg">
                            {{ $cartCount > 9 ? '9+' : $cartCount }}
                        </span>
                        @endif
                    </a>

                    <!-- User Profile Dropdown (Desktop & Quick Access) -->
                    <div class="relative" x-data="{ userOpen: false }" @click.away="userOpen = false">
                        <button @click="userOpen = !userOpen"
                                class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl bg-[#16161A] hover:bg-white/5 border border-white/10 transition-all active:scale-95">
                            <div class="w-8 h-8 rounded-xl bg-[#FF6B00] text-black font-montserrat font-extrabold flex items-center justify-center text-xs shadow-md shadow-[#FF6B00]/25">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-montserrat font-bold text-white leading-none truncate max-w-[100px]">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                <span class="text-[9px] font-montserrat font-bold text-[#FF6B00] uppercase tracking-wider block mt-0.5">Member</span>
                            </div>
                            <i class="ph-bold ph-caret-down text-xs text-[#8A8D93] transition-transform duration-200 hidden sm:block" :class="userOpen ? 'rotate-180 text-white' : ''"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="userOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 mt-2 w-64 rounded-2xl bg-[#16161A] border border-white/10 shadow-2xl overflow-hidden z-[9999] py-2">

                            {{-- User Info Header --}}
                            <div class="px-4 py-3 border-b border-white/5 bg-white/[0.02]">
                                <p class="text-xs font-montserrat font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-questrial text-[#8A8D93] truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            {{-- Menu Links --}}
                            <div class="p-2 space-y-0.5 font-montserrat text-xs">
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#8A8D93] hover:text-white hover:bg-white/5 transition-all">
                                    <i class="ph-bold ph-user-circle text-base text-[#FF6B00]"></i>
                                    <span>Profil &amp; Akun Saya</span>
                                </a>
                                <a href="{{ route('profil.perusahaan') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#8A8D93] hover:text-white hover:bg-white/5 transition-all">
                                    <i class="ph-bold ph-buildings text-base text-[#FF6B00]"></i>
                                    <span>Profil Workshop</span>
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}"
                                   target="_blank"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[#8A8D93] hover:text-white hover:bg-white/5 transition-all">
                                    <i class="ph-bold ph-whatsapp-logo text-base text-emerald-400"></i>
                                    <span>Konsultasi CS</span>
                                </a>
                            </div>

                            {{-- Logout Form --}}
                            <div class="p-2 pt-1 border-t border-white/5">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400 hover:text-red-300 hover:bg-red-500/10 font-montserrat font-bold text-xs uppercase tracking-wider transition-all">
                                        <i class="ph-bold ph-sign-out text-base"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6 md:p-8 flex-grow pb-24 lg:pb-8">
            @yield('content')
        </main>
        
        <!-- Footer matching mockup style -->
        <footer class="p-6 md:p-8 border-t border-white/5 bg-[#050505] text-[10px] font-questrial text-gray-400">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>{{ $profil->footer_copyright ?? '&copy; ' . date('Y') . ' ' . ($profil->nama_perusahaan ?? 'Dantie Stiker') . '. All rights reserved.' }}</p>
                <div class="flex items-center gap-6 font-montserrat text-[10px]">
                    <a href="#" class="hover:text-[#ff6b00] transition-colors">Support</a>
                    <a href="#" class="hover:text-[#ff6b00] transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-[#ff6b00] transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-[#ff6b00] transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- BOTTOM NAVIGATION — Fitur asli sistem --}}
    <nav id="bottom-nav" class="lg:hidden">
        <div class="flex items-center justify-around h-16 px-2 bg-black/95 backdrop-blur-xl border-t border-white/10">

            {{-- 1. Beranda --}}
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('dashboard') ? 'text-[#ff6b00]' : 'text-gray-400 hover:text-white' }}">
                <i class="ph-bold ph-house text-2xl"></i>
                <span class="text-[10px] font-montserrat font-semibold tracking-wide">Beranda</span>
            </a>

            {{-- 2. Katalog Layanan --}}
            <a href="{{ route('katalog.user') }}"
               class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('katalog.*') ? 'text-[#ff6b00]' : 'text-gray-400 hover:text-white' }}">
                <i class="ph-bold ph-storefront text-2xl"></i>
                <span class="text-[10px] font-montserrat font-semibold tracking-wide">Katalog</span>
            </a>

            {{-- 3. Keranjang / Booking --}}
            <a href="{{ route('booking.index') }}"
               class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('booking.*') ? 'text-[#ff6b00]' : 'text-gray-400 hover:text-white' }}">
                <i class="ph-bold ph-calendar-check text-2xl"></i>
                <span class="text-[10px] font-montserrat font-semibold tracking-wide">Booking</span>
            </a>

            {{-- 4. Keranjang Belanja --}}
            <a href="{{ route('keranjang.index') }}"
               class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('keranjang.*') ? 'text-[#ff6b00]' : 'text-gray-400 hover:text-white' }} relative">
                <div class="relative">
                    <i class="ph-bold ph-shopping-cart text-2xl"></i>
                    @if(isset($cartCount) && $cartCount > 0)
                    <span class="absolute -top-1.5 -right-2 min-w-[17px] h-[17px] px-1 bg-[#ff6b00] text-white text-[9px] font-montserrat font-bold rounded-full flex items-center justify-center shadow-lg">
                        {{ $cartCount > 9 ? '9+' : $cartCount }}
                    </span>
                    @endif
                </div>
                <span class="text-[10px] font-montserrat font-semibold tracking-wide">Keranjang</span>
            </a>

            {{-- 5. Profil Saya --}}
            <a href="{{ route('profile.edit') }}"
               class="flex flex-col items-center justify-center gap-1 flex-1 py-1 transition-all {{ Request::routeIs('profile.*') ? 'text-[#ff6b00]' : 'text-gray-400 hover:text-white' }}">
                <i class="ph-bold ph-user-circle text-2xl"></i>
                <span class="text-[10px] font-montserrat font-semibold tracking-wide">Profil</span>
            </a>

        </div>
    </nav>

    <script>
        // =============================================
        // Notification Panel
        // =============================================
        let notifPanelOpen = false;
        let notifLoaded   = false;

        function toggleNotifPanel() {
            const panel = document.getElementById('notif-panel');
            notifPanelOpen = !notifPanelOpen;

            if (notifPanelOpen) {
                panel.classList.remove('hidden');
                // Animasi masuk
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(-8px)';
                panel.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                requestAnimationFrame(() => {
                    panel.style.opacity = '1';
                    panel.style.transform = 'translateY(0)';
                });
                if (!notifLoaded) loadNotifikasi();
            } else {
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(-8px)';
                setTimeout(() => panel.classList.add('hidden'), 200);
            }
        }

        // Tutup jika klik di luar panel
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (notifPanelOpen && wrapper && !wrapper.contains(e.target)) {
                const panel = document.getElementById('notif-panel');
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(-8px)';
                setTimeout(() => panel.classList.add('hidden'), 200);
                notifPanelOpen = false;
            }
        });

        function getIconByJudul(judul) {
            const j = (judul || '').toLowerCase();
            if (j.includes('selesai') || j.includes('complet')) return 'ph-check-circle text-green-400';
            if (j.includes('bayar') || j.includes('payment'))  return 'ph-credit-card text-yellow-400';
            if (j.includes('tolak') || j.includes('reject'))   return 'ph-x-circle text-red-400';
            if (j.includes('proses') || j.includes('mulai'))   return 'ph-gear text-blue-400';
            return 'ph-bell text-[#f2994a]';
        }

        function timeAgo(dateStr) {
            const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
            if (diff < 60)   return diff + 'd lalu';
            if (diff < 3600) return Math.floor(diff/60) + 'm lalu';
            if (diff < 86400) return Math.floor(diff/3600) + 'j lalu';
            return Math.floor(diff/86400) + ' hari lalu';
        }

        async function loadNotifikasi() {
            const list = document.getElementById('notif-list');
            try {
                const res  = await fetch('{{ route("api.notifikasi.index") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const json = await res.json();
                const data = json.data || [];
                notifLoaded = true;

                // Hitung unread
                const unread = data.filter(n => !n.is_read).length;
                updateBadge(unread);

                if (data.length === 0) {
                    list.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                            <i class="ph ph-bell-slash text-4xl mb-2"></i>
                            <span class="text-xs font-medium">Tidak ada notifikasi</span>
                        </div>`;
                    return;
                }

                list.innerHTML = data.map(n => `
                    <div id="notif-item-${n.id_notif}" class="flex items-start gap-3 px-5 py-4 transition-all ${n.is_read ? 'opacity-60' : 'bg-[#f2994a]/[0.03]'} hover:bg-white/[0.03] cursor-pointer group" onclick="handleNotifClick(${n.id_notif}, ${n.id_pesanan || 'null'}, '${(n.judul || '').replace(/'/g, "\\'")}')">
                        <div class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ph ${getIconByJudul(n.judul)} text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-bold text-white truncate">${n.judul || 'Notifikasi'}</p>
                                ${!n.is_read ? '<span class="w-2 h-2 bg-[#f2994a] rounded-full shrink-0"></span>' : ''}
                            </div>
                            <p class="text-[11px] text-gray-400 leading-relaxed mt-0.5 line-clamp-2">${n.pesan || ''}</p>
                            <span class="text-[9px] text-gray-600 font-medium mt-1 block">${timeAgo(n.created_at)}</span>
                        </div>
                    </div>
                `).join('');

            } catch (err) {
                list.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                        <i class="ph ph-warning text-3xl mb-2 text-red-500/60"></i>
                        <span class="text-xs">Gagal memuat notifikasi</span>
                    </div>`;
            }
        }

        async function handleNotifClick(id_notif, id_pesanan, judul = '') {
            const item = document.getElementById('notif-item-' + id_notif);
            if (item) {
                item.classList.remove('bg-[#f2994a]/[0.03]');
                item.classList.add('opacity-60');
                const dot = item.querySelector('.w-2.h-2.bg-\\[\\#f2994a\\]');
                if (dot) dot.remove();
            }

            try {
                await fetch(`{{ url('/api/notifikasi') }}/${id_notif}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const unread = document.querySelectorAll('[id^="notif-item-"] .w-2.h-2.bg-\\[\\#f2994a\\]').length;
                updateBadge(unread);
            } catch(e) {}

            if (id_pesanan && id_pesanan !== 'null') {
                window.location.href = `{{ url('/pesanan') }}/${id_pesanan}`;
            } else if ((judul || '').toLowerCase().includes('booking')) {
                window.location.href = `{{ route('booking.index') }}`;
            }
        }

        async function markAsRead(id) {
            return handleNotifClick(id, null);
        }

        async function markAllRead() {
            try {
                await fetch('{{ route("api.notifikasi.markAllAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                notifLoaded = false;
                loadNotifikasi();
            } catch(e) {}
        }

        function updateBadge(count) {
            const badge = document.getElementById('notif-badge');
            const label = document.getElementById('notif-count-label');
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
                if (label) {
                    label.textContent = count + ' belum dibaca';
                    label.classList.remove('hidden');
                }
            } else {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
                if (label) label.classList.add('hidden');
            }
        }

        // Cek unread count saat halaman load (tanpa buka panel)
        async function checkUnreadCount() {
            try {
                const res  = await fetch('{{ route("api.notifikasi.unread") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const json = await res.json();
                const total = json.data?.unread_count ?? (typeof json.data === 'number' ? json.data : 0);
                updateBadge(total);
            } catch(e) {}
        }

        // Jalankan saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            checkUnreadCount();
            // Auto-refresh unread count setiap 30 detik
            setInterval(function() {
                if (!notifPanelOpen) checkUnreadCount();
                else { notifLoaded = false; loadNotifikasi(); }
            }, 30000);
        });
    </script>

    <!-- Floating Toast Notification Component -->
    @if(session('toast_success'))
        <div id="floating-toast" class="fixed bottom-24 right-6 z-50 flex items-center gap-3 bg-[#0E0E10] border border-[#FF6B00] text-white px-6 py-4 rounded-2xl shadow-[0_10px_30px_rgba(255,107,0,0.3)] animate-bounce-short transition-all duration-500">
            <div class="w-8 h-8 rounded-full bg-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] shrink-0">
                <i class="ph-bold ph-check-circle text-lg"></i>
            </div>
            <p class="text-xs font-bold">{{ session('toast_success') }}</p>
            <button onclick="document.getElementById('floating-toast').remove()" class="text-[#8A8D93] hover:text-white ml-2">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('floating-toast');
                if(toast) {
                    toast.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    @if(session('toast_error'))
        <div id="floating-toast-error" class="fixed bottom-24 right-6 z-50 flex items-center gap-3 bg-[#121212] border border-red-500 text-white px-6 py-4 rounded-2xl shadow-[0_10px_30px_rgba(239,68,68,0.3)] animate-bounce-short transition-all duration-500">
            <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 shrink-0">
                <i class="ph-bold ph-warning-circle text-lg"></i>
            </div>
            <p class="text-xs font-bold">{{ session('toast_error') }}</p>
            <button onclick="document.getElementById('floating-toast-error').remove()" class="text-gray-400 hover:text-white ml-2">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('floating-toast-error');
                if(toast) {
                    toast.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        </script>
    @endif

    @stack('scripts')
</body>
</html>

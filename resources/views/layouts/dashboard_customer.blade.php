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

    <!-- Plus Jakarta Sans & Phosphor Icons (Deferred & Preloaded) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #0a0a0a;
            color: #ffffff;
        }
        .sidebar-link-active { 
            background-color: #f2994a; 
            color: #000000 !important; 
            box-shadow: 0 4px 15px rgba(242, 153, 74, 0.25);
        }
        
        /* Pengaturan Khusus agar Desktop tidak rusak oleh gaya HP */
        @media (min-width: 1024px) {
            #side-menu { transform: translateX(0) !important; }
            #side-overlay { display: none !important; }
            #bottom-nav { display: none !important; }
        }
        /* Bottom Nav Mobile */
        #bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 8999;
            background: rgba(12,12,12,0.97);
            border-top: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>
<body class="bg-[#0a0a0a] text-white antialiased">
    
    {{-- OVERLAY GELAP --}}
    <div id="side-overlay" onclick="tutupMenu()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(6px); z-index:9000;"></div>

    {{-- SIDEBAR --}}
    <aside id="side-menu" style="position:fixed; top:0; bottom:0; left:0; width:288px; background:#0c0c0c; border-right:1px solid rgba(255,255,255,0.05); z-index:9001; transition:0.3s; transform:translateX(-100%);" class="flex flex-col">
        
        <!-- Logo & Exclusive Member -->
        <div class="p-8 flex flex-col gap-1 border-b border-white/5">
            <div class="flex justify-between items-center w-full">
                <div class="flex flex-col gap-0.5">
                    <span class="font-extrabold text-xl tracking-wider text-[#f2994a] uppercase">Premium Wrap</span>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">Exclusive Member</span>
                </div>
                {{-- TOMBOL TUTUP HP --}}
                <button onclick="tutupMenu()" class="lg:hidden w-8 h-8 bg-white/5 rounded-full flex items-center justify-center border border-white/10 hover:bg-white/10">
                    <i class="ph-bold ph-x text-white"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow p-6 space-y-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('dashboard') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-layout text-lg"></i> Beranda
            </a>
            
            <div class="pt-5 pb-2">
                <span class="px-4 text-[9px] font-bold text-gray-600 uppercase tracking-widest">Belanja</span>
            </div>

            <a href="{{ route('katalog.user') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('katalog.user') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-tag text-lg"></i> Katalog Layanan
            </a>
            <a href="{{ route('keranjang.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('keranjang.index') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-shopping-cart text-lg"></i> Keranjang
            </a>

            <div class="pt-5 pb-2">
                <span class="px-4 text-[9px] font-bold text-gray-600 uppercase tracking-widest">Manajemen</span>
            </div>

            <a href="{{ route('pesanan.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('pesanan.index') && !request()->has('status') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-folder text-lg"></i> Riwayat Pesanan
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'menunggu_pembayaran']) }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ request('status') == 'menunggu_pembayaran' ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-credit-card text-lg"></i> Pembayaran
            </a>

            <div class="pt-5 pb-2">
                <span class="px-4 text-[9px] font-bold text-gray-600 uppercase tracking-widest">Pengaturan</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('profile.edit') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-user text-lg"></i> Profil Saya
            </a>
            
            <div class="h-[1px] bg-white/5 my-4"></div>
            
            <a href="{{ route('profil.perusahaan') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('profil.perusahaan') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-buildings text-lg"></i> Profil Perusahaan
            </a>
            <a href="{{ route('galeri.user') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('galeri.user') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-image-square text-lg"></i> Galeri Portofolio
            </a>
            <a href="{{ route('katalog.user') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all {{ Request::routeIs('katalog.user') ? 'sidebar-link-active' : 'text-gray-400 hover:text-white hover:bg-white/[0.02]' }}">
                <i class="ph-bold ph-tag text-lg"></i> Layanan & Paket
            </a>

            <!-- CTA Pesan Layanan di Sidebar -->
            <div class="pt-6">
                <a href="{{ route('katalog.user') }}" class="flex items-center justify-center gap-2 py-3 bg-[#ffd8a8]/90 hover:bg-[#ffd8a8] text-black font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_15px_rgba(255,216,168,0.2)] hover:scale-[1.02] active:scale-95">
                    <i class="ph-bold ph-plus-circle text-sm"></i> Pesan Layanan Baru
                </a>
            </div>
        </nav>

        <!-- Bottom Actions (Support & Settings) -->
        <div class="p-6 border-t border-white/5 space-y-1">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" target="_blank" class="flex items-center gap-4 px-4 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider text-gray-400 hover:text-white hover:bg-white/[0.02] transition-all">
                <i class="ph-bold ph-question text-base"></i> Bantuan
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider text-gray-400 hover:text-white hover:bg-white/[0.02] transition-all">
                <i class="ph-bold ph-gear text-base"></i> Pengaturan
            </a>
            
            <div class="pt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 text-red-500 font-extrabold text-[10px] uppercase tracking-widest bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 rounded-xl transition-all active:scale-95">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="lg:ml-72 min-h-screen flex flex-col">
        <!-- Top bar (only notifications and avatar on the right, rest transparent) -->
        <header class="h-20 bg-transparent px-6 md:px-8 flex items-center justify-between sticky top-0 z-[100] backdrop-blur-sm">
            {{-- TOMBOL BUKA HP --}}
            <button onclick="bukaMenu()" class="lg:hidden p-3 bg-white/5 border border-white/10 rounded-xl text-white">
                <i class="ph-bold ph-list text-xl"></i>
            </button>
            
            <div class="ml-auto flex items-center gap-6">
                <!-- Notification Bell & Dropdown -->
                <div class="relative" id="notif-wrapper">
                    <button id="notif-btn" onclick="toggleNotifPanel()" class="relative w-10 h-10 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                        <i class="ph ph-bell text-xl"></i>
                        <span id="notif-badge" class="absolute -top-1 -right-1 hidden min-w-[18px] h-[18px] px-1 bg-[#f2994a] text-black text-[9px] font-extrabold rounded-full flex items-center justify-center shadow-lg"></span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div id="notif-panel" class="hidden absolute right-0 top-[calc(100%+12px)] w-80 sm:w-96 bg-[#111111] border border-white/8 rounded-2xl shadow-2xl z-[9999] overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.6);">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                            <div class="flex items-center gap-2">
                                <i class="ph-bold ph-bell text-[#f2994a]"></i>
                                <span class="text-sm font-bold text-white">Notifikasi</span>
                                <span id="notif-count-label" class="hidden text-[9px] font-extrabold text-[#f2994a] bg-[#f2994a]/10 px-2 py-0.5 rounded-full"></span>
                            </div>
                            <button onclick="markAllRead()" class="text-[10px] font-bold text-gray-500 hover:text-[#f2994a] transition-colors uppercase tracking-wide">
                                Tandai semua dibaca
                            </button>
                        </div>

                        <!-- Notification List -->
                        <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-white/5">
                            <div class="flex flex-col items-center justify-center py-10 text-gray-600">
                                <i class="ph ph-circle-notch ph-spin text-3xl mb-2 animate-spin"></i>
                                <span class="text-xs">Memuat notifikasi...</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 border-t border-white/5 bg-[#0c0c0c]">
                            <a href="{{ route('pesanan.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest flex items-center justify-center gap-1.5">
                                Lihat semua pesanan <i class="ph-bold ph-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-extrabold text-white leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-bold text-[#f2994a] uppercase tracking-wider mt-1">Premium Member</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#e28a44] to-[#f2994a] text-black font-extrabold flex items-center justify-center shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 md:p-8 flex-grow pb-24 lg:pb-8">
            @yield('content')
        </main>
        
        <!-- Footer matching mockup style -->
        <footer class="p-6 md:p-8 border-t border-white/5 bg-[#080808] text-[10px] text-gray-500 font-medium">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>&copy; 2026 Wapping Premium Service. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-white transition-colors">Support</a>
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- BOTTOM NAVIGATION (Mobile Only) --}}
    <nav id="bottom-nav" class="lg:hidden">
        <div class="flex items-stretch justify-around h-16">
            {{-- Beranda --}}
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 flex-1 transition-all {{ Request::routeIs('dashboard') ? 'text-[#f2994a]' : 'text-gray-500 hover:text-white' }}">
                <i class="ph-bold ph-layout text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Beranda</span>
            </a>
            {{-- Katalog --}}
            <a href="{{ route('katalog.user') }}" class="flex flex-col items-center justify-center gap-1 flex-1 transition-all {{ Request::routeIs('katalog.user') ? 'text-[#f2994a]' : 'text-gray-500 hover:text-white' }}">
                <i class="ph-bold ph-tag text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Katalog</span>
            </a>
            {{-- Keranjang --}}
            <a href="{{ route('keranjang.index') }}" class="flex flex-col items-center justify-center gap-1 flex-1 transition-all {{ Request::routeIs('keranjang.index') ? 'text-[#f2994a]' : 'text-gray-500 hover:text-white' }}">
                <i class="ph-bold ph-shopping-cart text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Keranjang</span>
            </a>
            {{-- Pesanan --}}
            <a href="{{ route('pesanan.index') }}" class="flex flex-col items-center justify-center gap-1 flex-1 transition-all {{ Request::routeIs('pesanan.index') ? 'text-[#f2994a]' : 'text-gray-500 hover:text-white' }}">
                <i class="ph-bold ph-folder text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Pesanan</span>
            </a>
            {{-- Menu Lebih (Hamburger) --}}
            <button onclick="bukaMenu()" class="flex flex-col items-center justify-center gap-1 flex-1 transition-all text-gray-500 hover:text-white">
                <i class="ph-bold ph-list text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-wide">Menu</span>
            </button>
        </div>
    </nav>

    <script>
        // =============================================
        // Sidebar Menu (Mobile)
        // =============================================
        function bukaMenu() {
            document.getElementById('side-menu').style.transform = 'translateX(0)';
            document.getElementById('side-overlay').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function tutupMenu() {
            document.getElementById('side-menu').style.transform = 'translateX(-100%)';
            document.getElementById('side-overlay').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

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
                    <div id="notif-item-${n.id_notif}" class="flex items-start gap-3 px-5 py-4 transition-all ${n.is_read ? 'opacity-60' : 'bg-[#f2994a]/[0.03]'} hover:bg-white/[0.03] cursor-pointer group" onclick="handleNotifClick(${n.id_notif}, ${n.id_pesanan || 'null'})">
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

        async function handleNotifClick(id_notif, id_pesanan) {
            const item = document.getElementById('notif-item-' + id_notif);
            if (item) {
                item.classList.remove('bg-[#f2994a]/[0.03]');
                item.classList.add('opacity-60');
                const dot = item.querySelector('.w-2.h-2.bg-\\[\\#f2994a\\]');
                if (dot) dot.remove();
            }

            try {
                await fetch(`{{ url('/api/notifikasi') }}/${id_notif}`, {
                    method: 'PATCH',
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
            }
        }

        async function markAsRead(id) {
            return handleNotifClick(id, null);
        }

        async function markAllRead() {
            try {
                await fetch('{{ route("api.notifikasi.markAllAsRead") }}', {
                    method: 'PATCH',
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
                const count = json.data?.length ?? (json.data ?? 0);
                const total = Array.isArray(count) ? count.length : (parseInt(count) || 0);
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
        <div id="floating-toast" class="fixed bottom-24 right-6 z-50 flex items-center gap-3 bg-[#121212] border border-[#f2994a] text-white px-6 py-4 rounded-2xl shadow-[0_10px_30px_rgba(242,153,74,0.3)] animate-bounce-short transition-all duration-500">
            <div class="w-8 h-8 rounded-full bg-[#f2994a]/20 flex items-center justify-center text-[#f2994a] shrink-0">
                <i class="ph-bold ph-check-circle text-lg"></i>
            </div>
            <p class="text-xs font-bold">{{ session('toast_success') }}</p>
            <button onclick="document.getElementById('floating-toast').remove()" class="text-gray-400 hover:text-white ml-2">
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
</body>
</html>

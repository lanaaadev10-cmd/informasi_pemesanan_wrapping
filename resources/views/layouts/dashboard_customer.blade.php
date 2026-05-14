<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-link-active { background-color: #ea580c; color: white !important; font-weight: 800; }
        .sidebar-link-active i { color: white !important; }
        
        /* Smooth Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-orange-500 selection:text-white">
    
    {{-- OVERLAY GELAP UNTUK MOBILE --}}
    <div id="side-overlay" onclick="toggleMenu()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[90] hidden opacity-0 transition-opacity duration-300 lg:hidden"></div>

    {{-- SIDEBAR --}}
    <aside id="side-menu" class="fixed top-0 left-0 bottom-0 w-[280px] bg-white border-r border-slate-200/60 z-[100] flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none">
        
        <!-- Logo Section -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/20 group-hover:scale-105 transition-transform">
                    <i class="ph-bold ph-sketch-logo text-xl"></i>
                </div>
                <span class="font-black text-slate-900 text-xl tracking-tight">Altra<span class="text-orange-600">.</span></span>
            </a>
            <button onclick="toggleMenu()" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5">
            <p class="px-4 text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-4 mt-2">Menu Utama</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('dashboard') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="ph-fill ph-squares-four text-[22px] {{ Request::routeIs('dashboard') ? '' : 'text-slate-400' }}"></i> Beranda
            </a>
            
            <a href="{{ route('katalog.user') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('katalog.user') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="ph-fill ph-storefront text-[22px] {{ Request::routeIs('katalog.user') ? '' : 'text-slate-400' }}"></i> Katalog Layanan
            </a>
            
            <a href="{{ route('keranjang.index') }}" class="flex items-center justify-between px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('keranjang.*') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <div class="flex items-center gap-3.5">
                    <i class="ph-fill ph-shopping-cart text-[22px] {{ Request::routeIs('keranjang.*') ? '' : 'text-slate-400' }}"></i> Keranjang
                </div>
                @php $cartCount = \App\Models\Keranjang::where('id_user', auth()->id())->count(); @endphp
                @if($cartCount > 0)
                <span class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black {{ Request::routeIs('keranjang.*') ? 'bg-white text-orange-600' : 'bg-orange-600 text-white' }}">{{ $cartCount }}</span>
                @endif
            </a>

            <button onclick="bukaModalPanduan()" class="w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all text-left">
                <i class="ph-fill ph-info text-[22px] text-slate-400"></i> Panduan Pemesanan
            </button>

            <a href="{{ route('pesanan.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('pesanan.index') || Request::routeIs('pesanan.show') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="ph-fill ph-receipt text-[22px] {{ Request::routeIs('pesanan.*') ? '' : 'text-slate-400' }}"></i> Riwayat Pesanan
            </a>

            @if(Request::routeIs('pesanan.checkout.form'))
            <div class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold sidebar-link-active shadow-md shadow-orange-500/20">
                <i class="ph-fill ph-credit-card text-[22px]"></i> Pembayaran
            </div>
            @endif

            <p class="px-4 text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-4 mt-8">Lainnya</p>

            <a href="{{ route('profil.perusahaan') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('profil.perusahaan') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="ph-fill ph-buildings text-[22px] {{ Request::routeIs('profil.perusahaan') ? '' : 'text-slate-400' }}"></i> Profil Bisnis
            </a>
            
            <a href="{{ route('galeri.user') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm font-semibold transition-all {{ Request::routeIs('galeri.user') ? 'sidebar-link-active shadow-md shadow-orange-500/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="ph-fill ph-image text-[22px] {{ Request::routeIs('galeri.user') ? '' : 'text-slate-400' }}"></i> Galeri Hasil
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-colors group mb-2">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-medium text-slate-500 truncate">Pengaturan Akun</p>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 text-red-600 font-bold text-sm bg-red-50/50 rounded-xl hover:bg-red-50 transition-colors">
                    <i class="ph-bold ph-sign-out"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <div class="lg:ml-[280px] min-h-screen flex flex-col bg-slate-50/50">
        
        <!-- Top Navbar -->
        <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 px-4 md:px-8 flex items-center justify-between sticky top-0 z-[50]">
            <div class="flex items-center gap-4">
                <button onclick="toggleMenu()" class="lg:hidden p-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>
                <div class="hidden md:block">
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">@yield('title', 'Dashboard')</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-5">
                {{-- Notifications --}}
                <div class="relative group" id="notif-wrapper">
                    <button class="relative w-10 h-10 sm:w-11 sm:h-11 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 hover:bg-orange-50 hover:text-orange-600 transition-all">
                        <i class="ph-fill ph-bell text-xl"></i>
                        @php
                            $notifCount = \App\Models\Notifikasi::where('id_user', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <span id="notif-badge" class="{{ $notifCount > 0 ? '' : 'hidden' }} absolute 0 right-0 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    
                    {{-- Dropdown Notif --}}
                    <div class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-3xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-top-right transform scale-95 group-hover:scale-100 z-[100] p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-900">Notifikasi Terbaru</h4>
                        </div>
                        <div id="notif-list" class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                            @forelse(\App\Models\Notifikasi::where('id_user', auth()->id())->latest()->take(5)->get() as $n)
                            <div class="p-3.5 bg-slate-50 rounded-2xl hover:bg-orange-50 transition-colors cursor-pointer group/item">
                                <p class="text-xs font-bold text-slate-900 mb-1 group-hover/item:text-orange-700 transition-colors">{{ $n->judul }}</p>
                                <p class="text-[11px] font-medium text-slate-500 leading-relaxed">{{ $n->pesan }}</p>
                            </div>
                            @empty
                            <div class="py-8 flex flex-col items-center justify-center text-center">
                                <i class="ph-fill ph-bell-slash text-3xl text-slate-200 mb-2"></i>
                                <p class="text-xs text-slate-400 font-medium">Semua notifikasi sudah dibaca.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- User Avatar Mobile Only --}}
                <a href="{{ route('profile.edit') }}" class="lg:hidden w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="py-6 px-4 md:px-8 border-t border-slate-200/60 mt-auto">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs font-medium text-slate-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <div class="flex items-center gap-4 text-xs font-medium text-slate-400">
                    <a href="#" class="hover:text-slate-900 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Terms</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- Toast Notification System --}}
    <div id="toast-container" class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

    {{-- Modal Panduan Pemesanan --}}
    <div id="modal-panduan" class="fixed inset-0 z-[10001] hidden flex items-center justify-center p-4 sm:p-6">
        <div id="modal-panduan-overlay" onclick="tutupModalPanduan()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="modal-panduan-content" class="relative bg-white rounded-[32px] w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col">
            <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur-xl z-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Panduan Pemesanan</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Langkah mudah menggunakan layanan kami.</p>
                </div>
                <button onclick="tutupModalPanduan()" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            <div class="p-6 md:p-8 space-y-8">
                @php $company = \App\Models\ProfilPerusahaan::first(); @endphp
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-black text-xl z-10 shadow-sm border-4 border-white">1</div>
                        <div class="w-0.5 h-full bg-slate-100 absolute top-12 left-6 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-lg font-bold text-slate-900 mb-2">{{ $company->step_1_title ?? 'Pilih Layanan' }}</h4>
                        <p class="text-sm font-medium text-slate-500 leading-relaxed">{{ $company->step_1_desc ?? 'Eksplorasi katalog kami dan pilih layanan yang paling sesuai dengan kebutuhan Anda. Masukkan ke keranjang.' }}</p>
                    </div>
                </div>
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-black text-xl z-10 shadow-sm border-4 border-white">2</div>
                        <div class="w-0.5 h-full bg-slate-100 absolute top-12 left-6 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-lg font-bold text-slate-900 mb-2">{{ $company->step_2_title ?? 'Lengkapi Data' }}</h4>
                        <p class="text-sm font-medium text-slate-500 leading-relaxed">{{ $company->step_2_desc ?? 'Masuk ke menu keranjang, lalu klik Checkout. Isi formulir detail kendaraan dan informasi lainnya dengan lengkap.' }}</p>
                    </div>
                </div>
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-black text-xl z-10 shadow-sm border-4 border-white">3</div>
                        <div class="w-0.5 h-full bg-slate-100 absolute top-12 left-6 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-lg font-bold text-slate-900 mb-2">{{ $company->step_3_title ?? 'Verifikasi & Bayar' }}</h4>
                        <p class="text-sm font-medium text-slate-500 leading-relaxed">{{ $company->step_3_desc ?? 'Admin akan memverifikasi pesanan Anda. Setelah disetujui, Anda dapat melakukan pembayaran DP atau Lunas.' }}</p>
                    </div>
                </div>
                <div class="flex gap-6 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-black text-xl z-10 shadow-sm border-4 border-white">4</div>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2">{{ $company->step_4_title ?? 'Pengerjaan' }}</h4>
                        <p class="text-sm font-medium text-slate-500 leading-relaxed">{{ $company->step_4_desc ?? 'Bawa kendaraan Anda ke lokasi kami sesuai jadwal. Tim ahli kami akan mengerjakan pesanan Anda dengan sempurna.' }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 md:p-8 border-t border-slate-100 bg-slate-50 mt-auto rounded-b-[32px]">
                <button onclick="tutupModalPanduan()" class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-sm tracking-widest uppercase hover:bg-orange-600 transition-all shadow-lg">
                    Saya Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        // Modal Panduan Logic
        const modalPanduan = document.getElementById('modal-panduan');
        const modalPanduanOverlay = document.getElementById('modal-panduan-overlay');
        const modalPanduanContent = document.getElementById('modal-panduan-content');

        function bukaModalPanduan() {
            modalPanduan.classList.remove('hidden');
            setTimeout(() => {
                modalPanduanOverlay.classList.remove('opacity-0');
                modalPanduanContent.classList.remove('scale-95', 'opacity-0');
                modalPanduanContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.body.style.overflow = 'hidden';
            if(window.innerWidth < 1024) toggleMenu(); // Tutup sidebar HP jika terbuka
        }

        function tutupModalPanduan() {
            modalPanduanOverlay.classList.add('opacity-0');
            modalPanduanContent.classList.remove('scale-100', 'opacity-100');
            modalPanduanContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modalPanduan.classList.add('hidden');
            }, 300);
            document.body.style.overflow = '';
        }

        // Menu Toggle Logic
        const sideMenu = document.getElementById('side-menu');
        const sideOverlay = document.getElementById('side-overlay');
        let isMenuOpen = false;

        function toggleMenu() {
            isMenuOpen = !isMenuOpen;
            if (isMenuOpen) {
                sideMenu.classList.remove('-translate-x-full');
                sideOverlay.classList.remove('hidden');
                // Small delay to allow display block to apply before opacity transition
                setTimeout(() => sideOverlay.classList.remove('opacity-0'), 10);
                document.body.style.overflow = 'hidden';
            } else {
                sideMenu.classList.add('-translate-x-full');
                sideOverlay.classList.add('opacity-0');
                setTimeout(() => sideOverlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }
        }

        // Toast System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const isSuccess = type === 'success';
            const bgColor = isSuccess ? 'bg-slate-900' : 'bg-red-500';
            const icon = isSuccess ? 'ph-check-circle text-green-400' : 'ph-warning-circle text-white';
            
            toast.className = `${bgColor} text-white px-5 sm:px-6 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300 pointer-events-auto border border-white/10 max-w-sm w-full`;
            toast.innerHTML = `
                <i class="ph-fill ${icon} text-xl shrink-0"></i>
                <span class="text-sm font-semibold tracking-tight leading-snug">${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            }, 10);
            
            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        @if(session('toast_success')) showToast("{{ session('toast_success') }}", 'success'); @endif
        @if(session('toast_error')) showToast("{{ session('toast_error') }}", 'error'); @endif
        @if(session('success')) showToast("{{ session('success') }}", 'success'); @endif

        // Notifications Polling
        @auth
        setInterval(() => {
            fetch('/api/notifikasi/unread')
                .then(res => res.json())
                .then(notifs => {
                    if (notifs.length > 0) {
                        const badge = document.getElementById('notif-badge');
                        const list = document.getElementById('notif-list');
                        
                        notifs.forEach(n => {
                            showToast(`${n.judul}`, 'success');
                            
                            const item = document.createElement('div');
                            item.className = "p-3.5 bg-orange-50 rounded-2xl transition-colors cursor-pointer";
                            item.innerHTML = `
                                <p class="text-xs font-bold text-orange-700 mb-1">${n.judul}</p>
                                <p class="text-[11px] font-medium text-slate-600 leading-relaxed">${n.pesan}</p>
                            `;
                            
                            if (list.querySelector('.ph-bell-slash')) {
                                list.innerHTML = ''; 
                            }
                            list.insertBefore(item, list.firstChild);
                        });

                        badge.classList.remove('hidden');
                    }
                }).catch(e => console.log('Notif check error'));
        }, 15000); 
        @endauth
    </script>
</body>
</html>

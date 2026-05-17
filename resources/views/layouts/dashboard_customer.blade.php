<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f5f4f0; }
        .font-serif { font-family: 'Poppins', sans-serif; }
        .sidebar-link-active { background-color: #f2541b !important; color: white !important; font-weight: 800; border-radius: 16px; box-shadow: 0 10px 20px -10px rgba(242, 84, 27, 0.4); }
        .sidebar-link-active i { color: white !important; }
        
        /* Smooth Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a29e; }

        /* Mobile Safe Area & Typography */
        @media (max-width: 1023px) {
            .main-content-area { padding-bottom: 6rem; }
        }
        /* Scrollbar hide for step nav */
        .scrollbar-none::-webkit-scrollbar { display: none; }
        .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
        /* Mobile fluid font helpers */
        .text-fluid-hero { font-size: clamp(1.6rem, 6vw, 2.5rem); line-height: 1.15; }
        .text-fluid-title { font-size: clamp(1.3rem, 5vw, 2rem); line-height: 1.2; }
    </style>
</head>
<body class="text-stone-850 antialiased selection:bg-[#f2541b] selection:text-white">
    
    {{-- OVERLAY GELAP UNTUK MOBILE --}}
    <div id="side-overlay" onclick="toggleMenu()" class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-[90] hidden opacity-0 transition-opacity duration-300 lg:hidden"></div>

    {{-- SIDEBAR --}}
    <aside id="side-menu" class="fixed top-0 left-0 bottom-0 w-[280px] bg-[#151413] z-[100] flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none border-r border-stone-900">
        
        <!-- Logo Section -->
        <div class="h-24 px-6 flex items-center justify-between border-b border-stone-800/40 relative overflow-hidden shrink-0">
            <!-- Glowing accent in logo section -->
            <div class="absolute -left-10 -top-10 w-28 h-28 bg-[#f2541b]/15 rounded-full blur-2xl"></div>
            
            <a href="/" class="flex items-center gap-3 relative z-10 py-1.5 px-4 bg-[#1f1d1b] rounded-full border border-stone-800/60">
                <div class="w-6.5 h-6.5 bg-[#f2541b] rounded-lg flex items-center justify-center text-white font-black text-xs shadow-md shadow-[#f2541b]/20">
                    D
                </div>
                <span class="font-serif font-black text-white text-base tracking-tight">dantiestiker</span>
            </a>
            <button onclick="toggleMenu()" class="lg:hidden p-2 text-stone-400 hover:text-white hover:bg-stone-800 rounded-xl transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <p class="px-4 text-[9px] font-bold tracking-widest text-stone-500 uppercase mb-3 mt-1">Menu Utama</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('dashboard') ? 'sidebar-link-active' : 'text-[#a19f9a] hover:bg-stone-800/30 hover:text-white' }}">
                <i class="ph-fill ph-squares-four text-xl {{ Request::routeIs('dashboard') ? '' : 'text-stone-500' }}"></i> Beranda
            </a>
            
            <a href="{{ route('katalog.user') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('katalog.user') ? 'sidebar-link-active' : 'text-[#a19f9a] hover:bg-stone-800/30 hover:text-white' }}">
                <i class="ph-fill ph-storefront text-xl {{ Request::routeIs('katalog.user') ? '' : 'text-stone-500' }}"></i> Katalog Layanan
            </a>
            
            <a href="{{ route('keranjang.index') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('keranjang.*') ? 'sidebar-link-active' : 'text-[#a19f9a] hover:bg-stone-800/30 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <i class="ph-fill ph-shopping-cart text-xl {{ Request::routeIs('keranjang.*') ? '' : 'text-stone-500' }}"></i> Keranjang
                </div>
                @php $cartCount = \App\Models\Keranjang::where('id_user', auth()->id())->count(); @endphp
                @if($cartCount > 0)
                <span class="w-5 h-5 flex items-center justify-center rounded-full text-[9px] font-bold {{ Request::routeIs('keranjang.*') ? 'bg-white text-[#f2541b]' : 'bg-[#f2541b] text-white' }}">{{ $cartCount }}</span>
                @endif
            </a>

            <button onclick="bukaModalPanduan()" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-[#a19f9a] hover:bg-stone-800/30 hover:text-white transition-all text-left">
                <i class="ph-fill ph-info text-xl text-stone-500"></i> Panduan Pemesanan
            </button>

            @if(Request::routeIs('pesanan.checkout.form'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold sidebar-link-active">
                <i class="ph-fill ph-credit-card text-xl"></i> Pembayaran
            </div>
            @endif

            <p class="px-4 text-[9px] font-bold tracking-widest text-stone-500 uppercase mb-3 mt-6">Lainnya</p>

            <a href="{{ route('profil.perusahaan') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('profil.perusahaan') ? 'sidebar-link-active' : 'text-[#a19f9a] hover:bg-stone-800/30 hover:text-white' }}">
                <i class="ph-fill ph-buildings text-xl {{ Request::routeIs('profil.perusahaan') ? '' : 'text-stone-500' }}"></i> Profil Bisnis
            </a>
            
            <a href="{{ route('galeri.user') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition-all {{ Request::routeIs('galeri.user') ? 'sidebar-link-active' : 'text-[#a19f9a] hover:bg-stone-800/30 hover:text-white' }}">
                <i class="ph-fill ph-image text-xl {{ Request::routeIs('galeri.user') ? '' : 'text-stone-500' }}"></i> Galeri Hasil
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-5 border-t border-stone-800/40 shrink-0">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 mb-4 group block">
                <div class="w-8.5 h-8.5 rounded-full bg-stone-800 text-[#f2541b] font-black text-sm flex items-center justify-center border border-stone-750 shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-xs font-bold text-white group-hover:text-[#f2541b] transition-colors truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] font-semibold text-stone-500 truncate">Pengaturan Akun</p>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 text-[10px] font-bold text-[#f2541b] hover:text-[#f36b37] transition-colors bg-transparent border-0 p-0 uppercase tracking-wider">
                    Keluar &rarr;
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <div class="lg:ml-[280px] min-h-screen flex flex-col bg-[#f5f4f0]">
        
        <!-- Top Navbar -->
        <header class="h-24 px-6 md:px-10 flex items-center justify-between sticky top-0 z-[50] bg-[#f5f4f0]/95 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <div class="block">
                    <h2 class="font-serif font-black text-stone-900 text-base md:text-xl tracking-tight">@yield('title', 'Dashboard')</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-5">
                {{-- Notifications --}}
                <div class="relative group" id="notif-wrapper">
                    <button class="relative w-10 h-10 bg-white border border-stone-200 rounded-full flex items-center justify-center text-stone-800 hover:bg-stone-50 transition-all shadow-sm">
                        <i class="ph-bold ph-bell text-lg"></i>
                        @php
                            $notifCount = \App\Models\Notifikasi::where('id_user', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <span id="notif-badge" class="{{ $notifCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 w-2.5 h-2.5 bg-[#f2541b] rounded-full border-2 border-white"></span>
                    </button>
                    
                    {{-- Dropdown Notif --}}
                    <div class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-3xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] border border-stone-200/50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-350 origin-top-right transform scale-95 group-hover:scale-100 z-[100] p-5">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-[9px] font-bold uppercase tracking-wider text-stone-400">Notifikasi Terbaru</h4>
                        </div>
                        <div id="notif-list" class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
                            @forelse(\App\Models\Notifikasi::where('id_user', auth()->id())->latest()->take(5)->get() as $n)
                            <div class="p-3 bg-stone-50 rounded-xl hover:bg-[#f2541b]/5 transition-colors cursor-pointer group/item border border-stone-200/40">
                                <p class="text-xs font-bold text-stone-900 mb-0.5 group-hover/item:text-[#f2541b] transition-colors">{{ $n->judul }}</p>
                                <p class="text-[10px] font-medium text-stone-500 leading-relaxed">{{ $n->pesan }}</p>
                            </div>
                            @empty
                            <div class="py-8 flex flex-col items-center justify-center text-center">
                                <i class="ph-fill ph-bell-slash text-2xl text-stone-200 mb-2"></i>
                                <p class="text-[10px] text-stone-400 font-semibold">Semua notifikasi sudah dibaca.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- User Avatar Mobile Only --}}
                <a href="{{ route('profile.edit') }}" class="lg:hidden w-10 h-10 rounded-full bg-[#151413] text-white flex items-center justify-center font-bold shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow px-6 md:px-10 pb-28 lg:pb-12">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="py-6 px-6 md:px-10 mt-auto border-t border-stone-250/40 bg-transparent mb-20 lg:mb-0">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">&copy; {{ date('Y') }} dantiestiker. All rights reserved.</p>
                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-wider text-stone-400">
                    <a href="#" class="hover:text-stone-950 transition-colors">Privacy</a>
                    <a href="#" class="hover:text-stone-950 transition-colors">Terms</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- BOTTOM MOBILE NAVIGATION BAR --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-stone-200/40 z-[95] px-6 py-3 flex justify-between items-center shadow-[0_-8px_30px_rgba(0,0,0,0.03)] safe-bottom">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 flex-1 transition-all active:scale-95">
            <i class="ph-fill ph-squares-four text-xl {{ Request::routeIs('dashboard') ? 'text-[#f2541b]' : 'text-stone-400 hover:text-stone-600' }}"></i>
            <span class="text-[9px] font-black tracking-tight {{ Request::routeIs('dashboard') ? 'text-[#f2541b]' : 'text-stone-400' }}">Beranda</span>
        </a>
        
        <a href="{{ route('katalog.user') }}" class="flex flex-col items-center gap-1 flex-1 transition-all active:scale-95">
            <i class="ph-fill ph-storefront text-xl {{ Request::routeIs('katalog.user') ? 'text-[#f2541b]' : 'text-stone-400 hover:text-stone-600' }}"></i>
            <span class="text-[9px] font-black tracking-tight {{ Request::routeIs('katalog.user') ? 'text-[#f2541b]' : 'text-stone-400' }}">Katalog</span>
        </a>
        
        <a href="{{ route('keranjang.index') }}" class="flex flex-col items-center gap-1 flex-1 relative transition-all active:scale-95">
            <i class="ph-fill ph-shopping-cart text-xl {{ Request::routeIs('keranjang.*') ? 'text-[#f2541b]' : 'text-stone-400 hover:text-stone-600' }}"></i>
            <span class="text-[9px] font-black tracking-tight {{ Request::routeIs('keranjang.*') ? 'text-[#f2541b]' : 'text-stone-400' }}">Keranjang</span>
            @if($cartCount > 0)
                <span class="absolute top-0 right-4 w-4 h-4 bg-[#f2541b] text-white text-[8px] font-black rounded-full flex items-center justify-center border border-white">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
        
        
        <button onclick="toggleMenu()" class="flex flex-col items-center gap-1 flex-1 bg-transparent border-0 outline-none transition-all active:scale-95">
            <i class="ph-bold ph-list text-xl text-stone-400 hover:text-stone-600"></i>
            <span class="text-[9px] font-black tracking-tight text-stone-400">Menu</span>
        </button>
    </div>

    {{-- Toast Notification System --}}
    <div id="toast-container" class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

    {{-- Modal Panduan Pemesanan --}}
    <div id="modal-panduan" class="fixed inset-0 z-[10001] hidden flex items-center justify-center p-4 sm:p-6">
        <div id="modal-panduan-overlay" onclick="tutupModalPanduan()" class="absolute inset-0 bg-stone-900/50 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="modal-panduan-content" class="relative bg-white rounded-[32px] w-full max-w-xl max-h-[85vh] overflow-y-auto shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col border border-stone-200/50">
            <div class="p-6 md:p-8 border-b border-stone-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-xl z-10">
                <div>
                    <h3 class="font-serif text-2xl font-black text-stone-900 tracking-tight">Panduan Pemesanan</h3>
                    <p class="text-xs font-semibold text-stone-500 mt-0.5">Langkah mudah menggunakan layanan kami.</p>
                </div>
                <button onclick="tutupModalPanduan()" class="w-9 h-9 bg-stone-50 border border-stone-200 rounded-full flex items-center justify-center text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            <div class="p-6 md:p-8 space-y-6">
                @php $company = \App\Models\ProfilPerusahaan::first(); @endphp
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-[#151413] text-[#f2541b] rounded-full flex items-center justify-center font-serif font-black text-base z-10 shadow-sm border border-stone-850">1</div>
                        <div class="w-0.5 h-full bg-stone-100 absolute top-10 left-5 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-sm font-bold text-stone-900 mb-1">{{ $company->step_1_title ?? 'Pilih Layanan' }}</h4>
                        <p class="text-xs font-medium text-stone-500 leading-relaxed">{{ $company->step_1_desc ?? 'Eksplorasi katalog kami dan pilih layanan yang paling sesuai dengan kebutuhan Anda. Masukkan ke keranjang.' }}</p>
                    </div>
                </div>
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-[#151413] text-[#f2541b] rounded-full flex items-center justify-center font-serif font-black text-base z-10 shadow-sm border border-stone-850">2</div>
                        <div class="w-0.5 h-full bg-stone-100 absolute top-10 left-5 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-sm font-bold text-stone-900 mb-1">{{ $company->step_2_title ?? 'Lengkapi Data' }}</h4>
                        <p class="text-xs font-medium text-stone-500 leading-relaxed">{{ $company->step_2_desc ?? 'Masuk ke menu keranjang, lalu klik Checkout. Isi formulir detail kendaraan dan informasi lainnya dengan lengkap.' }}</p>
                    </div>
                </div>
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-[#151413] text-[#f2541b] rounded-full flex items-center justify-center font-serif font-black text-base z-10 shadow-sm border border-stone-850">3</div>
                        <div class="w-0.5 h-full bg-stone-100 absolute top-10 left-5 -ml-[1px]"></div>
                    </div>
                    <div class="pb-6">
                        <h4 class="text-sm font-bold text-stone-900 mb-1">{{ $company->step_3_title ?? 'Verifikasi & Bayar' }}</h4>
                        <p class="text-xs font-medium text-stone-500 leading-relaxed">{{ $company->step_3_desc ?? 'Admin akan memverifikasi pesanan Anda. Setelah disetujui, Anda dapat melakukan pembayaran DP atau Lunas.' }}</p>
                    </div>
                </div>
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 bg-[#151413] text-[#f2541b] rounded-full flex items-center justify-center font-serif font-black text-base z-10 shadow-sm border border-stone-850">4</div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-stone-900 mb-1">{{ $company->step_4_title ?? 'Pengerjaan' }}</h4>
                        <p class="text-xs font-medium text-stone-500 leading-relaxed">{{ $company->step_4_desc ?? 'Bawa kendaraan Anda ke lokasi kami sesuai jadwal. Tim ahli kami akan mengerjakan pesanan Anda dengan sempurna.' }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 md:p-8 border-t border-stone-100 bg-stone-50 mt-auto rounded-b-[32px]">
                <button onclick="tutupModalPanduan()" class="w-full py-3.5 bg-[#151413] text-white hover:bg-[#2b2a28] rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md">
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
            const bgColor = isSuccess ? 'bg-[#151413]' : 'bg-red-650';
            const icon = isSuccess ? 'ph-check-circle text-emerald-400' : 'ph-warning-circle text-white';
            
            toast.className = `${bgColor} text-white px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300 pointer-events-auto border border-stone-800 max-w-sm w-full`;
            toast.innerHTML = `
                <i class="ph-fill ${icon} text-xl shrink-0"></i>
                <span class="text-xs font-bold tracking-tight leading-snug">${message}</span>
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
                            item.className = "p-3 bg-stone-50 border border-stone-200/40 rounded-xl transition-colors cursor-pointer";
                            item.innerHTML = `
                                <p class="text-xs font-bold text-stone-900 mb-0.5">${n.judul}</p>
                                <p class="text-[10px] font-medium text-stone-500 leading-relaxed">${n.pesan}</p>
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


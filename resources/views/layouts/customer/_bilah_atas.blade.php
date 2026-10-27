<!-- Top bar: Brand Identity, Modern Center Nav Links, Bell, Wishlist & Profile Dropdown -->
<header class="py-3.5 px-4 sm:px-6 md:px-8 bg-white/95 sticky top-0 z-[100] backdrop-blur-md border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        
        <!-- Left: Brand Logo & Title -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group shrink-0">
            <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#FF6B00] group-hover:scale-105 group-hover:border-orange-400 transition-all shadow-sm">
                <i class="ph-bold ph-steering-wheel text-xl"></i>
            </div>
            
            <div class="flex flex-col justify-center min-w-0">
                <span class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wider leading-tight group-hover:text-[#FF6B00] transition-colors truncate">
                    {{ $profil->nama_perusahaan ?? 'Dantie Stiker' }}
                </span>
                <span class="text-[9px] font-mono font-bold uppercase tracking-widest text-slate-400 truncate">
                    {{ $profil->dashboard_subtitle ?? 'Wrapping & Variasi Kendaraan' }}
                </span>
            </div>
        </a>

        <!-- Center: Sleek Desktop Navigation Links (Clean Automotive Bar) -->
        <nav class="hidden lg:flex items-center gap-1 bg-slate-100/90 border border-slate-200 rounded-2xl p-1.5 shadow-inner">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('dashboard') ? 'bg-[#FF6B00] text-white shadow-sm shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                Beranda
            </a>
            <a href="{{ route('katalog.user') }}"
               class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('katalog.*') ? 'bg-[#FF6B00] text-white shadow-sm shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                Katalog
            </a>
            <a href="{{ route('booking.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('booking.*') ? 'bg-[#FF6B00] text-white shadow-sm shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                Booking
            </a>
            <a href="{{ route('pesanan.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('pesanan.*') ? 'bg-[#FF6B00] text-white shadow-sm shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                Pesanan
            </a>
            <a href="{{ route('galeri.user') }}"
               class="px-4 py-2 rounded-xl text-xs font-montserrat font-bold transition-all {{ Request::routeIs('galeri.*') ? 'bg-[#FF6B00] text-white shadow-sm shadow-orange-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}">
                Galeri
            </a>
        </nav>
        
        <!-- Right: Notification Bell, Wishlist & Desktop User Profile Menu -->
        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <!-- Notification Bell & Dropdown -->
            <div class="relative" id="notif-wrapper">
                <button id="notif-btn" onclick="toggleNotifPanel()" class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#FF6B00] hover:text-[#FF6B00] transition-all shadow-sm relative active:scale-95">
                    <i class="ph-bold ph-bell text-lg sm:text-xl"></i>
                    <span id="notif-badge" class="absolute -top-1 -right-1 hidden min-w-[18px] h-[18px] px-1 bg-[#FF6B00] text-white text-[9px] font-montserrat font-extrabold rounded-full flex items-center justify-center shadow-md"></span>
                </button>

                <!-- Dropdown Panel -->
                <div id="notif-panel" class="hidden fixed z-[9999] top-20 inset-x-4 w-auto sm:inset-x-auto sm:right-6 sm:w-96 bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden" style="box-shadow: 0 20px 60px rgba(0,0,0,0.12);">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-bell text-[#FF6B00]"></i>
                            <span class="text-sm font-montserrat font-bold text-slate-900">Notifikasi</span>
                            <span id="notif-count-label" class="hidden text-[10px] font-montserrat font-bold text-[#FF6B00] uppercase tracking-wider"></span>
                        </div>
                        <button onclick="markAllRead()" class="text-[10px] font-montserrat font-bold text-slate-400 hover:text-[#FF6B00] transition-colors uppercase tracking-wide">
                            Tandai semua dibaca
                        </button>
                    </div>

                    <!-- Notification List -->
                    <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-slate-100 font-questrial">
                        <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                            <i class="ph ph-circle-notch ph-spin text-3xl mb-2 animate-spin text-[#FF6B00]"></i>
                            <span class="text-xs">Memuat notifikasi...</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
                        <a href="{{ route('pesanan.index') }}" class="text-[10px] font-montserrat font-bold text-slate-500 hover:text-[#FF6B00] transition-colors uppercase tracking-widest flex items-center justify-center gap-1.5">
                            Lihat semua pesanan <i class="ph-bold ph-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wishlist / Keranjang Button -->
            <a href="{{ route('keranjang.index') }}" title="Keranjang Belanja" class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:border-[#FF6B00] hover:text-[#FF6B00] transition-all shadow-sm relative active:scale-95">
                <i class="ph-bold ph-shopping-cart text-lg sm:text-xl"></i>
                @if(isset($cartCount) && $cartCount > 0)
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-[#FF6B00] text-white text-[9px] font-montserrat font-black rounded-full flex items-center justify-center shadow-md">
                    {{ $cartCount > 9 ? '9+' : $cartCount }}
                </span>
                @endif
            </a>

            <!-- User Profile Dropdown (Desktop & Quick Access) -->
            <div class="relative" x-data="{ userOpen: false }" @click.away="userOpen = false">
                <button @click="userOpen = !userOpen"
                        class="flex items-center gap-3 p-1.5 sm:px-3 sm:py-2 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 transition-all active:scale-95 shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-[#FF6B00] text-white font-montserrat font-extrabold flex items-center justify-center text-xs shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-montserrat font-bold text-slate-900 leading-none truncate max-w-[100px]">{{ explode(' ', Auth::user()->name)[0] }}</p>
                        <span class="text-[9px] font-montserrat font-bold text-[#FF6B00] uppercase tracking-wider block mt-0.5">Pelanggan</span>
                    </div>
                    <i class="ph-bold ph-caret-down text-xs text-slate-400 transition-transform duration-200 hidden sm:block" :class="userOpen ? 'rotate-180 text-slate-700' : ''"></i>
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
                     class="absolute right-0 mt-2 w-64 rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden z-[9999] py-2">

                    {{-- User Info Header --}}
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/70">
                        <p class="text-xs font-montserrat font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] font-questrial text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Menu Links --}}
                    <div class="p-2 space-y-0.5 font-montserrat text-xs">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
                            <i class="ph-bold ph-user-circle text-base text-[#FF6B00]"></i>
                            <span>Profil &amp; Akun Saya</span>
                        </a>
                        <a href="{{ route('profil.perusahaan') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
                            <i class="ph-bold ph-buildings text-base text-[#FF6B00]"></i>
                            <span>Profil Workshop</span>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}"
                           target="_blank"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all">
                            <i class="ph-bold ph-whatsapp-logo text-base text-emerald-500"></i>
                            <span>Konsultasi CS</span>
                        </a>
                    </div>

                    {{-- Logout Form --}}
                    <div class="p-2 pt-1 border-t border-slate-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-600 hover:bg-red-50 font-montserrat font-bold text-xs uppercase tracking-wider transition-all cursor-pointer">
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

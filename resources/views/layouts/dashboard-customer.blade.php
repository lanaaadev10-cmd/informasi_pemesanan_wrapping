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

    <!--
        Tipografi:
        - Audiowide  → Display / Brand heading
        - Montserrat → UI label, tombol
        - Questrial  → Body text

        Semua gaya kustom dashboard ada di: resources/css/dashboard.css
        Jangan tambahkan CSS inline baru di sini.
    -->
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Questrial&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased font-questrial">

    <div class="min-h-screen flex flex-col bg-white">
        {{-- Top Navigation Bar --}}
        @include('layouts.customer._topbar')

        {{-- Contextual Back Bar: muncul di semua halaman kecuali beranda --}}
        @if(!Request::routeIs('dashboard'))
        <div class="sticky top-[69px] z-[90] bg-white/90 backdrop-blur-sm border-b border-slate-200 px-4 sm:px-6 md:px-8 shadow-sm">
            <div class="max-w-7xl mx-auto flex items-center h-10">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 text-sm font-montserrat font-bold text-slate-600 hover:text-[#FF6B00] transition-colors group">
                    <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
                    Beranda
                </a>
                <span class="mx-2.5 text-slate-300">·</span>
                <span class="text-sm font-montserrat font-bold text-slate-500 truncate">
                    @php
                        $pageLabels = [
                            'booking.create'      => 'Booking Baru',
                            'booking.index'       => 'Jadwal Servis',
                            'booking.show'        => 'Detail Booking',
                            'booking.invoice'     => 'Invoice Booking',
                            'katalog.*'           => 'Katalog Layanan',
                            'katalog.user'        => 'Katalog Layanan',
                            'keranjang.index'     => 'Keranjang',
                            'transaksi.index'     => 'Riwayat Order',
                            'transaksi.tagihan'   => 'Bayar Tagihan',
                            'pesanan.index'       => 'Pesanan',
                            'pesanan.show'        => 'Detail Pesanan',
                            'pesanan.checkout'    => 'Checkout',
                            'pesanan.invoice'     => 'Invoice Pesanan',
                            'galeri.user'         => 'Galeri Workshop',
                            'profil.perusahaan'   => 'Profil Workshop',
                            'profile.edit'        => 'Akun Saya',
                            'rating.create'       => 'Beri Ulasan',
                        ];
                        $currentLabel = 'Halaman Ini';
                        foreach ($pageLabels as $routePattern => $label) {
                            if (Request::routeIs($routePattern)) {
                                $currentLabel = $label;
                                break;
                            }
                        }
                    @endphp
                    {{ $currentLabel }}
                </span>
            </div>
        </div>
        @endif

        {{-- Main Page Content --}}
        <main class="p-4 sm:p-6 md:p-8 flex-grow pb-24 lg:pb-8">
            @yield('content')
        </main>
        
        <!-- Footer matching light design -->
        <footer class="p-6 md:p-8 border-t border-slate-200 bg-white text-[11px] font-questrial text-slate-500">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>{{ $profil->footer_copyright ?? '&copy; ' . date('Y') . ' ' . ($profil->nama_perusahaan ?? 'Dantie Stiker') . '. All rights reserved.' }}</p>
                <div class="flex items-center gap-6 font-montserrat text-[11px] font-medium text-slate-500">
                    <a href="#" class="hover:text-[#FF6B00] transition-colors">Support</a>
                    <a href="#" class="hover:text-[#FF6B00] transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-[#FF6B00] transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-[#FF6B00] transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- Bottom Navigation Bar for Mobile --}}
    @include('layouts.customer._bottom-nav')

    {{-- Notification System & Floating Toasts --}}
    @include('layouts.customer._notification-scripts')

    {{-- Rating & Ulasan Modal Global --}}
    @include('dashboard.customer.partials._rating-modal')

    @stack('scripts')
</body>
</html>

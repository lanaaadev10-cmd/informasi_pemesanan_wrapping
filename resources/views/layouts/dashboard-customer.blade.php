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
<body class="bg-black text-white antialiased font-questrial">

    <div class="min-h-screen flex flex-col bg-black">
        {{-- Top Navigation Bar --}}
        @include('layouts.customer._topbar')

        {{-- Main Page Content --}}
        <main class="p-4 sm:p-6 md:p-8 flex-grow pb-24 lg:pb-8">
            @yield('content')
        </main>
        
        <!-- Footer matching mockup style -->
        <footer class="p-6 md:p-8 border-t border-white/5 bg-racing-black text-[10px] font-questrial text-gray-400">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>{{ $profil->footer_copyright ?? '&copy; ' . date('Y') . ' ' . ($profil->nama_perusahaan ?? 'Dantie Stiker') . '. All rights reserved.' }}</p>
                <div class="flex items-center gap-6 font-montserrat text-[10px]">
                    <a href="#" class="hover:text-racing-orange transition-colors">Support</a>
                    <a href="#" class="hover:text-racing-orange transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-racing-orange transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-racing-orange transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    {{-- Bottom Navigation Bar for Mobile --}}
    @include('layouts.customer._bottom-nav')

    {{-- Notification System & Floating Toasts --}}
    @include('layouts.customer._notification-scripts')

    @stack('scripts')
</body>
</html>

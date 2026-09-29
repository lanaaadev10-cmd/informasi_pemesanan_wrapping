<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @php
        $is_frontend = in_array(Route::currentRouteName(), ['home', 'profil.perusahaan', 'galeri.user', 'katalog.user', 'tentang-kami', 'layanan', 'kebijakan-privasi', 'testimoni.index']);
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

    @include('layouts.landing._styles')
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Preloader Element --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    {{-- Navbar Utama --}}
    @include('layouts.landing._navbar')

    {{-- Konten Utama --}}
    <main class="{{ (Request::routeIs('home') || Request::routeIs('layanan')) ? 'pt-0' : 'pt-32' }}">
        @yield('content')
    </main>

    {{-- Footer Utama --}}
    @include('layouts.landing._footer')

    {{-- Script Inisialisasi --}}
    @include('layouts.landing._scripts')
</body>
</html>

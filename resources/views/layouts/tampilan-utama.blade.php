<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @php
        $is_frontend = in_array(Route::currentRouteName(), ['home', 'profil.perusahaan', 'galeri.user', 'tentang-kami', 'layanan', 'kebijakan-privasi', 'testimoni.index']);
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

    <!-- Tipografi Standar Sistem (Montserrat untuk Heading/Tombol, Inter untuk Deskripsi/Body) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    @if(Request::routeIs('home'))
        <!-- LCP Image Preload untuk Beranda -->
        <link rel="preload" as="image" href="{{ asset('images/banner_exclusive_car.webp') }}" type="image/webp" fetchpriority="high">
    @endif
    
    <!-- Icon & Animasi (Deferred load to prevent render-blocking) -->
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"></noscript>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('layouts.landing._gaya_tampilan')
</head>
<body class="antialiased overflow-x-hidden">
    {{-- Preloader Element --}}
    <div id="preloader">
        <span class="loader"></span>
    </div>

    {{-- Navbar Utama --}}
    @include('layouts.landing._bilah_navigasi')

    {{-- Konten Utama --}}
    @php
        $hasTopHero = Request::routeIs('home') 
            || Request::routeIs('layanan') 
            || Request::routeIs('galeri.*') 
            || Request::routeIs('tentang-kami') 
            || Request::routeIs('profil.perusahaan') 
            || Request::routeIs('testimoni.index');
    @endphp
    <main class="{{ $hasTopHero ? 'pt-0' : 'pt-24 sm:pt-28' }}">
        @yield('content')
    </main>

    {{-- Footer Utama --}}
    @include('layouts.landing._kaki_halaman')

    {{-- Script Inisialisasi --}}
    @include('layouts.landing._skrip')
</body>
</html>

@extends(auth()->check() ? 'layouts.dashboard-customer' : 'layouts.tampilan-utama')

@section('title', 'Katalog Layanan - Dantie Stiker')

@section('content')
    <!-- Container Utama -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-5 sm:space-y-6 relative overflow-hidden">

        @php
            $accentColor = '#ff6b00';
            $heroTitle = $profil->katalog_hero_title ?? 'Katalog Layanan';
            $heroDesc = $profil->katalog_hero_desc ?? 'Pilih Layanan yang Anda Inginkan';
        @endphp

        <!-- Ambient Glowing Core di Background -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0 bg-[#ea580c]/5"></div>

        <!-- 1. HEADER SECTION (Breadcrumbs & Hero Title) -->
        @include('landing.katalog._kepala_halaman')

        <!-- 2. MAIN E-COMMERCE SECTION: 2 KOLOM (Sidebar Filter Kiri + Grid Produk Kanan) -->
        <div class="flex flex-col md:flex-row gap-5 lg:gap-6 items-start relative z-10">
            <!-- Kolom Kiri: Sidebar Filter E-Commerce (Sticky di Desktop & Tablet) -->
            @include('landing.katalog._bilah_sisi_filter')

            <!-- Kolom Kanan: E-Commerce Product Grid & Toolbar -->
            <div class="flex-1 min-w-0 w-full space-y-4">
                @include('landing.katalog._paket_vertikal')
            </div>
        </div>

        <!-- 3. BOTTOM FEATURES BAR -->
        @include('landing.katalog._fitur_keunggulan')

    </div>

    <!-- 4. FLOATING CART ACTION BUTTON -->
    @include('landing.katalog._tombol_keranjang')

    <!-- 5. SCRIPT FILTERING & SORTING E-COMMERCE -->
    @include('landing.katalog._skrip')
@endsection

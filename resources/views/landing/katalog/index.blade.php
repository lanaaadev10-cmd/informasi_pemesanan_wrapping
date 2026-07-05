@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Katalog Layanan')

@section('content')
    @if(!auth()->check())
        <!-- Spacer untuk Public View agar tidak tertutup Navbar -->
        <div class="h-28"></div>
    @endif

    <!-- Container Utama -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-10 relative overflow-hidden">

        @php
            $accentColor = $profil->accent_color ?? '#f2994a';
            $heroTitle = $profil->katalog_hero_title ?? 'Wrap Catalog';
            $heroDesc = $profil->katalog_hero_desc ?? 'Choose Your Finish';
            $introText = $profil->katalog_intro_text ?? 'Elevate your vehicle\'s aesthetic with our curated collection of high-performance wraps and protective finishes.';
        @endphp

        <style>
            :root {
                --accent-color: {{ $accentColor }};
            }
            .accent-color { color: var(--accent-color); }
            .accent-bg { background-color: var(--accent-color); }
        </style>

        <!-- Ambient Glowing Core di Background -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

        <!-- 1. HEADER SECTION (Wrap Catalog Title + Search Bar) -->
        @include('landing.katalog._header')

        <!-- 2. INTRO SECTION (Choose Your Finish Header + Filters) -->
        @include('landing.katalog._intro')

        <!-- 3. CATALOG DYNAMIC GRID -->
        @include('landing.katalog._grid')

        <!-- 4. BOTTOM FEATURES BAR -->
        @include('landing.katalog._features')

    </div>

    <!-- 5. FLOATING CART ACTION BUTTON (Dynamically counts active items) -->
    @include('landing.katalog._cart-button')

    <!-- Scripting for live searching & dynamic filtering -->
    @include('landing.katalog._script')
@endsection

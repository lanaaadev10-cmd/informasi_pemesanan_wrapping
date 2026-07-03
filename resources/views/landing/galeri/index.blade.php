@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Galeri Karya')

@section('content')
<div class="{{ auth()->check() ? 'max-w-6xl mx-auto py-8 px-4 sm:px-0' : 'max-w-7xl mx-auto px-6 py-12' }}">
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @php
        $siteConfig = config('site');
        $accentColor = $siteConfig['landing']['accent_color'] ?? '#f2994a';
        $galeriTitle = $siteConfig['galeri']['hero_title'] ?? 'Precision Mastery Gallery';
        $galeriDesc = $siteConfig['galeri']['hero_desc'] ?? 'Explore our curated selection of high-end automotive transformations. From matte finishes to protective layers, witness the art of precision in every detail.';
        $galeriItems = $siteConfig['galeri']['items'] ?? [];
    @endphp

    <style>
        :root {
            --accent-color: {{ $accentColor }};
        }
    </style>

    {{-- 1. Hero / Header Section --}}
    @include('landing.galeri._hero')

    {{-- 2. Category Filter Pills --}}
    @include('landing.galeri._filter')

    {{-- 3. Gallery Grid --}}
    @include('landing.galeri._grid')

</div>

{{-- 5. Filter JavaScript --}}
@include('landing.galeri._script')
@endsection

@extends('layouts.tampilan_utama')

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
        use App\Helpers\StaticContent;
        $accentColor = '#f2994a';
        $galeriTitle = StaticContent::GALERI_TITLE;
        $galeriDesc  = StaticContent::GALERI_DESC;
        $galeriFilterAll = StaticContent::GALERI_FILTER_ALL;
        $galeriFilterCategories = $galeris->pluck('kategori')->unique()->filter()->map(fn($k) => ['slug' => $k, 'label' => ucfirst($k) . ' Series'])->values()->toArray();
        $galeriHeroImage = null;
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

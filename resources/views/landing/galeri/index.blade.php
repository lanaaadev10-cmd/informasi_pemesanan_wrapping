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
        use App\Helpers\StaticContent;
        $accentColor = '#f2994a';
        $galeriTitle = StaticContent::GALERI_TITLE;
        $galeriDesc  = StaticContent::GALERI_DESC;
        $galeriFilterAll = StaticContent::GALERI_FILTER_ALL;
        $galeriFilterCategories = [
            ['slug' => 'matte',   'label' => 'Variasi mobil'],
            ['slug' => 'glossy',  'label' => 'Kaca film'],
            ['slug' => 'satin',   'label' => 'Audio mobil'],
        ];
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

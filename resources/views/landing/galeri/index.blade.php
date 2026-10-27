@extends('layouts.tampilan-utama')

@section('title', 'Galeri Karya')

@section('content')
    @php
        use App\Helpers\StaticContent;
        $accentColor = '#ea580c';
        $galeriTitle = StaticContent::GALERI_TITLE;
        $galeriDesc  = StaticContent::GALERI_DESC;
        $galeriFilterAll = StaticContent::GALERI_FILTER_ALL;
        $galeriFilterCategories = StaticContent::galeriCategories();
        $galeriHeroImage = null;
    @endphp

    {{-- 1. Bagian Utama / Jumbotron (Full Cinematic Banner) --}}
    @include('landing.galeri._bagian_utama')

    {{-- 2. Kontainer Utama Konten Galeri --}}
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 sm:space-y-10">
        {{-- Penyaring Kategori --}}
        @include('landing.galeri._penyaring')

        {{-- Kisi Galeri --}}
        @include('landing.galeri._kisi_galeri')
    </div>

{{-- 5. Skrip JavaScript Penyaring --}}
@include('landing.galeri._skrip')
@endsection

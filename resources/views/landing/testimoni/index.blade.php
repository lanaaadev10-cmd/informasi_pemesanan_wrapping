@extends('layouts.tampilan-utama')

@php
    $accentColor = '#ea580c';
    $sort = request('sort') === 'tertinggi' ? 'tertinggi' : 'terbaru';
@endphp

@section('title', 'Testimoni Pelanggan')

@section('content')
    {{-- 1. Bagian Utama / Jumbotron Sinematik --}}
    @include('landing.testimoni._bagian_utama')

    {{-- 2. Kontainer Utama Konten Testimoni --}}
    <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8 sm:space-y-10">
        @if($summary['total'] > 0)
            {{-- Ringkasan Statistik Rating --}}
            @include('landing.testimoni._ringkasan_statistik')

            {{-- Toolbar Penyaring & Pengurutan Ulasan --}}
            @include('landing.testimoni._penyaring')

            {{-- Kisi Ulasan Pelanggan --}}
            @include('landing.testimoni._kisi_testimoni')
        @else
            {{-- Tampilan Kosong Jika Belum Ada Ulasan --}}
            @include('landing.testimoni._kondisi_kosong')
        @endif
    </div>
@endsection
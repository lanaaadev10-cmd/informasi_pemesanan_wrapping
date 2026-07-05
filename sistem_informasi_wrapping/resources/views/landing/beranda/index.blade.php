@extends('layouts.tampilan_utama')

@section('title', 'Beranda')

@section('content')
    @php $waNumber = '628123456789'; @endphp

    {{-- 1. Hero Section --}}
    @include('landing.beranda._hero')

    {{-- 2. Keunggulan Section --}}
    @include('landing.beranda._keunggulan')

    {{-- 3. Portofolio Section --}}
    @include('landing.beranda._portofolio')

    {{-- 4. CTA + Langkah Mudah Section --}}
    @include('landing.beranda._cta-langkah')

@endsection
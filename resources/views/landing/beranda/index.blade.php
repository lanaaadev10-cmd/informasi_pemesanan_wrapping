@extends('layouts.tampilan-utama')

@section('title', 'Beranda')

@section('content')
    @php 
        $waNumber = preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6283117163605') ?: '6283117163605'; 
    @endphp

    {{-- 1. Bagian Utama / Hero Banner Sinematik --}}
    @include('landing.beranda._bagian_utama')

    {{-- 2. Tentang Kami: 3 Kartu Layanan + Narasi & Ulasan Pelanggan --}}
    @include('landing.beranda._tentang_kami')

    {{-- 3. Sorotan Karya Transformasi Eksterior (Slider Interaktif Membesar/Mengecil) --}}
    @include('landing.beranda._sorotan_karya')

    {{-- 4. Reservasi Slot Pengerjaan Workshop (Tiket Reservasi Mewah) --}}
    @include('landing.beranda._reservasi_slot')

    {{-- 5. Standar Pengerjaan Tinggi & FAQ Accordion --}}
    @include('landing.beranda._standar_pengerjaan')

    {{-- 6. Kolase Foto Galeri & Tautan Media Sosial --}}
    @include('landing.beranda._kolase_galeri')

    {{-- 7. Kalender Kuota Reservasi Pengerjaan Wrapping --}}
    @include('landing.booking._kalender')

    {{-- 8. Ajakan Bertindak & Garansi Resmi Pengerjaan --}}
    @include('landing.beranda._ajakan_bertindak')

@endsection
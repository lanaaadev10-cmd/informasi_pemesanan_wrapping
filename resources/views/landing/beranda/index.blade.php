@extends('layouts.tampilan_utama')

@section('title', 'Beranda')

@section('content')
    @php
        $siteConfig = config('site');
        $landing = $siteConfig['landing'];
        $hero = $landing['hero'];
        $stats = $landing['stats'];
        $keunggulan = $landing['keunggulan'];
        $ctaBanner = $landing['cta_banner'];
        $steps = $landing['steps'];
        $portfolio = $landing['portfolio'];
        $brand = $siteConfig['brand'];

        $badge = $hero['badge'];
        $title1 = $hero['title_1'];
        $title2 = $hero['title_2'];
        $subtitle = $hero['subtitle'];
        $stat1v = $stats[0]['value'];
        $stat1l = $stats[0]['label'];
        $stat2v = $stats[1]['value'];
        $stat2l = $stats[1]['label'];
        $ctaTitle = $ctaBanner['title'];
        $ctaSubtitle = $ctaBanner['subtitle'];
        $s1t = $steps[0]['title'];
        $s1d = $steps[0]['description'];
        $s2t = $steps[1]['title'];
        $s2d = $steps[1]['description'];
        $s3t = $steps[2]['title'];
        $s3d = $steps[2]['description'];

        $k1t = $keunggulan[0]['title'] ?? 'Kualitas Material Grade-A';
        $k1d = $keunggulan[0]['description'] ?? 'Kami hanya menggunakan bahan wrapping premium yang tahan lama dan rapi.';
        $k2t = $keunggulan[1]['title'] ?? 'Teknisi Tersertifikasi';
        $k2d = $keunggulan[1]['description'] ?? 'Dikerjakan oleh teknisi ahli yang terlatih penuh.';
        $k3t = $keunggulan[2]['title'] ?? 'Pengerjaan Tepat Waktu';
        $k3d = $keunggulan[2]['description'] ?? 'Kami menghormati jadwal Anda dengan pengerjaan yang presisi.';
        $k4t = $keunggulan[3]['title'] ?? 'Garansi Hingga 5 Tahun';
        $k4d = $keunggulan[3]['description'] ?? 'Nikmati proteksi dan layanan purna jual premium.';

        $waNumber = preg_replace('/[^0-9]/', '', $hero['cta_primary_url']);
    @endphp
    @include('landing.beranda._hero')

    {{-- 2. Keunggulan Section --}}
    @include('landing.beranda._keunggulan')

    {{-- 3. Portofolio Section --}}
    @include('landing.beranda._portofolio')

    {{-- 4. CTA + Langkah Mudah Section --}}
    @include('landing.beranda._cta-langkah')

@endsection
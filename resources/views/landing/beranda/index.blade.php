@extends('layouts.tampilan_utama')

@section('title', 'Beranda')

@section('content')
    {{-- Ambil nilai dari DB dengan fallback default --}}
    @php
        $badge       = $profil->home_badge_text         ?? 'Professional Car Wrapping Indonesia';
        $title1      = $profil->home_hero_title_line1   ?? 'Elevasi Estetika';
        $title2      = $profil->home_hero_title_line2   ?? 'Aset Mewah Anda.';
        $subtitle    = $profil->home_subtitle           ?? 'Layanan premium yang melindungi dan memperindah mobil kesayangan Anda. Hubungi kami untuk penawaran terbaik.';
        $stat1v      = $profil->home_stat1_value        ?? '500+';
        $stat1l      = $profil->home_stat1_label        ?? 'Supercars Wrapped';
        $stat2v      = $profil->home_stat2_value        ?? '5 Tahun';
        $stat2l      = $profil->home_stat2_label        ?? 'Garansi Material';

        $k1t = $profil->home_keunggulan_card1_title ?? 'Kualitas Material Grade-A';
        $k1d = $profil->home_keunggulan_card1_desc  ?? 'Kami hanya menggunakan merk premium dunia seperti <span class="text-white font-semibold">Avery Dennison, 3M, dan Teckwrap</span>. Memberikan hasil akhir yang sangat rapi, warna yang tahan lama, serta perlindungan cat orisinil mobil yang maksimal.';
        $k2t = $profil->home_keunggulan_card2_title ?? 'Teknisi Tersertifikasi';
        $k2d = $profil->home_keunggulan_card2_desc  ?? 'Dikerjakan oleh tim profesional yang terlatih dan memiliki sertifikasi resmi di bidang car wrapping untuk menjamin ketelitian tinggi.';
        $k3t = $profil->home_keunggulan_card3_title ?? 'Pengerjaan Tepat Waktu';
        $k3d = $profil->home_keunggulan_card3_desc  ?? 'Kami menghargai waktu berharga Anda. Dengan SOP terstruktur, kami menjamin kendaraan Anda selesai dikerjakan sesuai estimasi waktu.';
        $k4t = $profil->home_keunggulan_card4_title ?? 'Garansi Hingga 5 Tahun';
        $k4d = $profil->home_keunggulan_card4_desc  ?? 'Kami sangat yakin atas kualitas pengerjaan dan ketahanan bahan yang kami berikan. Nikmati perlindungan garansi penuh hingga 5 tahun untuk kepuasan total Anda.';

        $ctaTitle    = $profil->home_cta_title    ?? 'Siap Mengubah Tampilan Kendaraan?';
        $ctaSubtitle = $profil->home_cta_subtitle ?? 'Hubungi konsultan desain gratis sekarang. Tim ahli kami akan membantu Anda memilih material dan warna terbaik yang sesuai dengan kepribadian Anda.';

        $s1t = $profil->home_step1_title ?? 'Konsultasi & Estimasi';
        $s1d = $profil->home_step1_desc  ?? 'Hubungi tim admin kami untuk berkonsultasi mengenai desain & biaya.';
        $s2t = $profil->home_step2_title ?? 'Pilihan Wrapping';
        $s2d = $profil->home_step2_desc  ?? 'Tentukan pilihan material, merk premium, dan warna sesuai keinginan Anda.';
        $s3t = $profil->home_step3_title ?? 'Pengerjaan Rapi';
        $s3d = $profil->home_step3_desc  ?? 'Bawa mobil Anda ke workshop kami dan serahkan pengerjaan pada ahlinya.';

        $waNumber = preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '628123456789');
    @endphp

    {{-- 1. Hero Section --}}
    @include('landing.beranda._hero')

    {{-- 2. Keunggulan Section --}}
    @include('landing.beranda._keunggulan')

    {{-- 3. Portofolio Section --}}
    @include('landing.beranda._portofolio')

    {{-- 4. CTA + Langkah Mudah Section --}}
    @include('landing.beranda._cta-langkah')

    {{-- 5. Kontak & Lokasi Section --}}
    @include('landing.beranda._kontak')

@endsection


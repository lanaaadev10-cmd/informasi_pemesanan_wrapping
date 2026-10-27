@extends('layouts.tampilan-utama')

@section('title', 'Layanan - ' . (\App\Helpers\StaticContent::APP_NAME ?? 'Dantie Stiker'))

@section('content')

    @php
        $accentColor = '#ea580c';

        // Sesuai ketentuan: Untuk pilihan Jasa dan Custom, jangan tampilkan daftar layanan,
        // detail layanan, paket, harga paket, keranjang, atau checkout (dialihkan langsung via WhatsApp).
        $services = $layanans->filter(function ($l) {
            $isJasa = ($l->jenis_layanan === 'jasa_pasang')
                || str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang')
                || str_contains(strtolower($l->nama_layanan ?? ''), 'jasa pasang');
            $isCustom = ($l->jenis_layanan === 'custom')
                || in_array(strtolower($l->tipe_paket ?? ''), ['custom', 'partial'])
                || str_contains(strtolower($l->nama_layanan ?? ''), 'custom');
            return !$isJasa && !$isCustom;
        })->map(fn($l) => [
            'id'         => $l->id_layanan,
            'nama'       => $l->nama_layanan,
            'harga'      => $l->harga > 0 ? 'Rp ' . number_format($l->harga, 0, ',', '.') : 'Menyesuaikan',
            'deskripsi'  => $l->deskripsi,
            'fitur'      => $l->fitur ?? [],
            'gambar'     => $l->foto_contoh,
            'kategori'   => $l->kategori ?? 'mobil',
            'tipe_paket' => $l->tipe_paket ?? 'wrapping',
            'estimasi'   => $l->estimasi_waktu ?: '1-3 Hari',
        ])->values()->toArray();

        $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
        $galeris = $galeris ?? \App\Models\Galeri::take(4)->get();
    @endphp

    <div class="bg-white min-h-screen relative">

        {{-- Subtle Top Accent Background Gradient --}}
        <div class="absolute inset-x-0 top-0 h-[450px] pointer-events-none z-0 bg-gradient-to-b from-orange-500/5 via-transparent to-transparent"></div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 pb-20 sm:pb-28 space-y-16 sm:space-y-20 lg:space-y-28">

            {{-- ══════════════════════════════════════
                 1. HERO HEADER (Dual Column Sesuai Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._bagian_utama')

            {{-- ══════════════════════════════════════
                 2. STANDAR WORKSHOP (Seksi "ABOUT ME" pada Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._manfaat_garansi')

            {{-- ══════════════════════════════════════
                 3. SERVICES TITLE & FILTER INTERAKTIF ("SERVICES" pada Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._penyaring')

            {{-- ══════════════════════════════════════
                 4. SERVICE CARDS GRID (Sesuai 4-Column Grid pada Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._kisi_layanan')

            {{-- ══════════════════════════════════════
                 5. PORTOFOLIO PENGERJAAN (Seksi "Portfolio" Staggered pada Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._portofolio')

            {{-- ══════════════════════════════════════
                 7. KONSULTASI & KONTAK WORKSHOP (Seksi "Contact Me" pada Gambar Referensi)
            ══════════════════════════════════════ --}}
            @include('landing.layanan._kontak')

        </div>{{-- /max-w-7xl --}}

    </div>
@endsection

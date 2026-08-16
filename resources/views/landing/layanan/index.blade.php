@extends('layouts.tampilan_utama')

@section('title', 'Layanan - Wapping Premium Wrap')

@section('content')

    @php
        $accentColor = '#f2994a';

        $services = $layanans->map(fn($l) => [
            'nama'      => $l->nama_layanan,
            'harga'     => $l->harga > 0 ? 'Rp ' . number_format($l->harga, 0, ',', '.') : 'Menyesuaikan',
            'deskripsi' => $l->deskripsi,
            'fitur'     => $l->fitur ?? [],
            'gambar'    => $l->foto_contoh,
        ])->toArray();

        $badgeLabels = array_map(fn($l) => strtoupper($l->tipe_paket), $layanans->all());
        $badgeColors = array_fill(0, count($services), 'rgba(242,153,74,0.12)');
        $badgeTextColors = array_fill(0, count($services), 'var(--accent)');

        $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
    @endphp

    <style>:root{--accent:{{$accentColor}}}</style>

    <div class="bg-[#0a0a0a] min-h-screen relative">

        <div class="absolute inset-x-0 top-0 h-[500px] pointer-events-none z-0"
             style="background:radial-gradient(ellipse 55% 35% at 50% 0,{{$accentColor}}15,transparent 70%)"></div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-28 pb-24 space-y-24">

            {{-- ══════════════════════════════════════
                 HERO HEADER
            ══════════════════════════════════════ --}}
            @include('landing.layanan._hero')

            {{-- ══════════════════════════════════════
                 SERVICE CARDS GRID
            ══════════════════════════════════════ --}}
            @include('landing.layanan._grid')

            {{-- ══════════════════════════════════════
                 MENGAPA MEMILIH KAMI + GARANSI RESMI
            ══════════════════════════════════════ --}}
            @include('landing.layanan._benefits')

        </div>{{-- /max-w-7xl --}}
        </div>
    </div>
@endsection


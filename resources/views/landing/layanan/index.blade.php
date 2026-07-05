@extends('layouts.tampilan_utama')

@section('title', 'Layanan - Wapping Premium Wrap')

@section('content')

    @php
        $accentColor = '#f2994a';

        // Badge labels untuk setiap card
        $badgeLabels = ['Matte Series', 'Best Seller', 'Satin Series', 'Paint Protection'];
        $badgeColors = [
            'rgba(242,153,74,0.12)',         // subtle orange
            'rgba(242,153,74,0.22)',         // highlighted orange
            'rgba(242,153,74,0.12)',         // subtle orange
            'rgba(242,153,74,0.12)',         // subtle orange
        ];
        $badgeTextColors = [
            'var(--accent)',
            'var(--accent)',
            'var(--accent)',
            'var(--accent)',
        ];

        $services = [
            [
                'nama'      => 'Stealth Matte',
                'harga'     => 'Rp 12.500.000',
                'deskripsi' => 'Finishing non-reflektif yang memberikan kesan bersih, modern, dan elegan pada kendaraan Anda. Menggunakan material premium dengan ketahanan maksimal.',
                'fitur'     => ['Premium Avery Dennison Material', 'Garansi 3 Tahun', 'Estimasi 3-4 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Mirror Glossy',
                'harga'     => 'Rp 10.500.000',
                'deskripsi' => 'Warna yang hidup dan permukaan cermin sempurna. Layanan cat wrapping paling populer dengan hasil mengkilap maksimal.',
                'fitur'     => ['3M Series 2080 Premium Vinyl', 'Garansi 3 Tahun', 'Estimasi 3 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Satin Silk',
                'harga'     => 'Rp 11.800.000',
                'deskripsi' => 'Perpaduan sempurna antara matte dan glossy. Memberikan tekstur lembut dan elegan pada setiap lekukan bodi.',
                'fitur'     => ['Satin Finish Luxury Grade', 'Garansi 4 Tahun', 'Estimasi 4 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Paint Protection',
                'harga'     => 'Rp 25.000.000',
                'deskripsi' => 'Proteksi tertinggi dengan Paint Protection Film (PPF) transparan yang melindungi cat orisinil kendaraan Anda.',
                'fitur'     => ['Self-healing Technology TPU', 'Garansi 5 Tahun', 'Estimasi 5-7 Hari Kerja'],
                'gambar'    => null,
            ],
        ];

        // Fallback car images
        $fallbackImages = [
            'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800&auto=format&fit=crop',
        ];
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


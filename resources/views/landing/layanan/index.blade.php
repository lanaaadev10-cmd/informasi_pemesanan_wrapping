@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', ($profil?->layanan_hero_title ?? 'Layanan') . ' - ' . ($profil?->nama_perusahaan ?? 'Dantie Wrapping'))

@section('content')

    @php
        $accentColor = $profil?->accent_color ?? '#f2994a';
        // Badge labels untuk setiap card (dari settings, fallback ke hardcoded)
        $badgeLabels = [
            $profil?->layanan_badge_1 ?? 'Matte Series',
            $profil?->layanan_badge_2 ?? 'Best Seller',
            $profil?->layanan_badge_3 ?? 'Satin Series',
            $profil?->layanan_badge_4 ?? 'Paint Protection',
        ];
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

        // Gunakan data dari Manajemen Layanan
        $services = [];
        if (isset($layanans) && $layanans->count() > 0) {
            foreach ($layanans as $lay) {
                $fiturArr = [];
                if (is_array($lay->fitur)) {
                    foreach ($lay->fitur as $f) {
                        $fiturArr[] = is_array($f) ? ($f['nama_fitur'] ?? '') : $f;
                    }
                }
                if ($lay->estimasi_waktu) {
                    $fiturArr[] = 'Estimasi ' . $lay->estimasi_waktu;
                }
                $services[] = [
                    'id'        => $lay->id_layanan,
                    'nama'      => $lay->nama_layanan,
                    'harga'     => 'Rp ' . number_format($lay->harga, 0, ',', '.'),
                    'deskripsi' => $lay->deskripsi ?? '',
                    'fitur'     => $fiturArr,
                    'gambar'    => $lay->foto_contoh,
                ];
            }
        }

        // Fallback car images
        $fallbackImages = [
            'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800&auto=format&fit=crop',
        ];
    @endphp

    <div class="bg-[#0a0a0a] min-h-screen relative">

        <div class="absolute inset-x-0 top-0 h-[500px] pointer-events-none z-0"
             style="background:radial-gradient(ellipse 55% 35% at 50% 0,{{$accentColor}}15,transparent 70%)"></div>

        <div class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-28 pb-24 space-y-24">

            {{-- ══════════════════════════════════════
                 HERO HEADER
            ══════════════════════════════════════ --}}
            @include('landing.layanan._hero')
@include('landing.layanan._grid')

            {{-- ══════════════════════════════════════
                 SERVICE CARDS GRID
            ══════════════════════════════════════ --}}


            {{-- ══════════════════════════════════════
                 MENGAPA MEMILIH KAMI + GARANSI RESMI
            ══════════════════════════════════════ --}}
            @include('landing.layanan._benefits')

        </div>{{-- /max-w-7xl --}}
        </div>
    </div>
@endsection


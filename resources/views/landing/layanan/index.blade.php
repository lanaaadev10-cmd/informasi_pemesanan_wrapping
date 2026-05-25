@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', ($profil?->layanan_hero_title ?? 'Layanan') . ' - ' . ($profil?->nama_perusahaan ?? 'Premium Wrap'))

@section('content')

    @php
        $accentColor = $profil?->accent_color ?? '#f2994a';

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

        // Fallback layanan jika belum ada data dari Layanan model
        $defaultServices = [
            [
                'nama'      => 'Stealth Matte',
                'harga'     => 'Rp 12.500.000',
                'deskripsi' => 'Finishing non-reflektif yang memberikan kesan bersih, modern, dan elegan pada...',
                'fitur'     => ['Premium Avery Dennison Material', 'Garansi 3 Tahun', 'Estimasi 3-4 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Mirror Glossy',
                'harga'     => 'Rp 10.500.000',
                'deskripsi' => 'Warna yang hidup dan permukaan cermin sempurna. Layanan cat wrested paling...',
                'fitur'     => ['3M Series 2080 Premium Vinyl', 'Garansi 3 Tahun', 'Estimasi 3 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Satin Silk',
                'harga'     => 'Rp 11.800.000',
                'deskripsi' => 'Perpaduan sempurna antara matte dan glossy. Memberikan tekstur lembut dan...',
                'fitur'     => ['Satin Finish Luxury Grade', 'Garansi 4 Tahun', 'Estimasi 4 Hari Kerja'],
                'gambar'    => null,
            ],
            [
                'nama'      => 'Paint Protection',
                'harga'     => 'Rp 25.000.000',
                'deskripsi' => 'Proteksi tertinggi dengan Paint Protection Film (PPF) transparan yang...',
                'fitur'     => ['Self-healing Technology TPU', 'Garansi 5 Tahun', 'Estimasi 5-7 Hari Kerja'],
                'gambar'    => null,
            ],
        ];

        // Gunakan data dari Layanan model jika tersedia
        $services = [];
        if (isset($layanans) && $layanans->count() > 0) {
            foreach ($layanans as $idx => $lay) {
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
                    'nama'      => $lay->nama_layanan,
                    'harga'     => 'Rp ' . number_format($lay->harga, 0, ',', '.'),
                    'deskripsi' => $lay->deskripsi ?? '',
                    'fitur'     => $fiturArr,
                    'gambar'    => $lay->foto_contoh,
                ];
            }
        }

        // Jika tidak ada data dari model, coba dari CMS ProfilPerusahaan
        if (empty($services) && $profil) {
            for ($i = 1; $i <= 4; $i++) {
                $nama = $profil->{"layanan_{$i}_nama"};
                if ($nama) {
                    $services[] = [
                        'nama'      => $nama,
                        'harga'     => $profil->{"layanan_{$i}_harga"} ?? '',
                        'deskripsi' => $profil->{"layanan_{$i}_deskripsi"} ?? '',
                        'fitur'     => $profil->{"layanan_{$i}_fitur"} ?? [],
                        'gambar'    => $profil->{"layanan_{$i}_gambar"} ?? null,
                    ];
                }
            }
        }

        // Final fallback: Hanya gunakan defaultServices jika $layanans tidak dikirim/tidak diatur.
        // Jika $layanans diatur tetapi kosong, artinya data dari database kosong (admin belum menambahkan), maka biarkan kosong agar memicu status kosong (empty state).
        if (empty($services) && !isset($layanans)) {
            $services = $defaultServices;
        }

        // Fallback car images
        $fallbackImages = [
            'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800&auto=format&fit=crop',
        ];
    @endphp

    @include('landing.layanan._style')

    {{-- ═══════════════════════════════════════════════
         OUTER WRAPPER
    ═══════════════════════════════════════════════ --}}
    <div class="layanan-page relative">

        {{-- Ambient glow top --}}
        <div class="glow-ambient-top absolute inset-x-0 top-0 h-[500px] z-0"></div>

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
    </div>{{-- /outer wrapper --}}
@endsection


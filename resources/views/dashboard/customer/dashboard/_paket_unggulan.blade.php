{{-- ============================================
    BAGIAN: 2-Tier Cascading Selector & Grid Produk (User Dashboard)
    Deskripsi:
    - Tier 1: Selector Jenis Kendaraan (Mobil, Motor, Semua)
    - Tier 2: Selector 4 Pilar Kebutuhan (Jasa Pasang, Full Wrap, Custom, Aksesoris, Semua)
    - Grid Produk: Responsif 2 kolom di HP (gap-x 5px), 3-4 kolom di desktop.
============================================ --}}
<div class="space-y-4 sm:space-y-6" id="dashboard-catalog-section">

    <!-- Section Header: E-Commerce Header with Orange Accent & Link to Full Katalog -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-3 gap-2">
        <div class="space-y-1">
            <div class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#ff6b00] flex items-center gap-1.5">
                <i class="ph-bold ph-seal-check text-xs"></i>
                <span>Katalog Workshop Bergaransi</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-slate-900 tracking-wide flex items-center gap-2">
                <span>Paket Layanan Pilihan</span>
                <span class="hidden sm:inline-flex text-[11px] font-montserrat font-bold px-2.5 py-0.5 rounded-full bg-orange-100 text-[#ff6b00]">
                    2-Tier Selector
                </span>
            </h3>
            <p class="text-xs font-questrial text-slate-500">
                Temukan layanan spesifik sesuai jenis kendaraan dan pilar kebutuhan pengerjaan Anda.
            </p>
        </div>

        <a href="{{ route('katalog.user') }}" 
           class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl bg-white hover:bg-orange-50 border border-slate-200 hover:border-orange-300 text-xs font-montserrat font-bold text-[#FF6B00] transition-all group shrink-0 active:scale-95 shadow-sm">
            <span>Katalog Lengkap ({{ $layanans->count() }})</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    @php
        $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
        $cleanWaNumber = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
        if (str_starts_with($cleanWaNumber, '0')) {
            $cleanWaNumber = '62' . substr($cleanWaNumber, 1);
        }
        $companyName = $profil->nama_perusahaan ?? 'Dantie Stiker';

        // Precalculate counts for initial blade render
        $countAll = $layanans->count();
        $countMobil = $layanans->where('kategori', 'mobil')->count();
        $countMotor = $layanans->where('kategori', 'motor')->count();

        $countJasa = $layanans->filter(fn($l) => $l->pillar === 'jasa-pasang')->count();
        $countWrap = $layanans->filter(fn($l) => $l->pillar === 'full-wrap')->count();
        $countCustom = $layanans->filter(fn($l) => $l->pillar === 'custom')->count();
        $countAksesoris = $layanans->filter(fn($l) => $l->pillar === 'aksesoris')->count();
    @endphp

    <!-- 2-Tier Cascading Selector Box (Tier 1 & Tier 2) -->
    @include('dashboard.customer.dashboard.partials._selector_katalog')

    <!-- Grid Produk Dinamis & Kotak Konsultasi Khusus -->
    @include('dashboard.customer.dashboard.partials._grid_katalog')

</div>

<!-- State Engine Filter Cascading -->
@include('dashboard.customer.dashboard.partials._skrip_katalog')

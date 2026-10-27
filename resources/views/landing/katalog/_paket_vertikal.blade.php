{{-- ============================================
    BAGIAN: Grid Produk Katalog E-Commerce Minimalis
    Deskripsi: Toolbar sortir & pencarian ramping dengan grid produk
               bergaya e-commerce minimalis (clean, compact, & proporsional)
============================================ --}}
@php
    $totalCount = $layanan->count();
    $mobilCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor')->count();
    $motorCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor')->count();
@endphp

<div class="space-y-4">

    <!-- 1. Toolbar E-Commerce & Filter Chips -->
    @include('landing.katalog.partials._paket_vertikal_toolbar')

    @if($layanan->isNotEmpty())
        @php
            $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
        @endphp

        <!-- 2. Panel Arahan Custom via WhatsApp & Empty State Filter -->
        @include('landing.katalog.partials._paket_vertikal_guidance')

        <!-- 3. Grid Produk E-Commerce -->
        @include('landing.katalog.partials._paket_vertikal_cards')
    @else
        <!-- Empty State jika memang belum ada data layanan -->
        <div class="py-14 px-6 flex flex-col items-center justify-center text-center bg-slate-50 border border-slate-200 rounded-2xl shadow-sm">
            <h4 class="text-sm font-audiowide font-bold text-slate-900 tracking-wide">
                Belum Ada Layanan
            </h4>
            <p class="text-xs font-questrial text-slate-500 max-w-md mx-auto mt-1">
                Layanan workshop sedang dalam pembaruan berkala.
            </p>
        </div>
    @endif

</div>

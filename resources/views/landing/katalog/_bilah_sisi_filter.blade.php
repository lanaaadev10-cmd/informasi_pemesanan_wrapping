{{-- ============================================
    BAGIAN: Sidebar Filter E-Commerce Minimalis
    Deskripsi: Panel filter kiri ramping & sticky di desktop & tablet,
               dengan dropdown accordion sub-kategori pada Mobil & Motor
============================================ --}}
@php
    $totalCount = $layanan->count();
    $mobilCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor')->count();
    $motorCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor')->count();

    // Hitung per jenis layanan Mobil
    $mobilWrapCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        str_contains(strtolower($l->tipe_paket ?? ''), 'wrapping') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'full wrap') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'variasi')
    ))->count();

    $mobilJasaCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        ($l->jenis_layanan === 'jasa_pasang') || 
        str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'jasa pasang')
    ))->count();

    $mobilCustomCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        $l->jenis_layanan !== 'jasa_pasang' &&
        !str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') &&
        (
            str_contains(strtolower($l->tipe_paket ?? ''), 'custom') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'custom') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'decal')
        )
    ))->count();

    $mobilAksesorisCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? 'mobil') !== 'motor' && (
        in_array(strtolower($l->tipe_paket ?? ''), ['window-film', 'lighting', 'audio']) || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'kaca film') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'lampu') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'audio')
    ))->count();

    // Hitung per jenis layanan Motor
    $motorWrapCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        str_contains(strtolower($l->tipe_paket ?? ''), 'wrapping') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'full wrap')
    ))->count();

    $motorJasaCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        ($l->jenis_layanan === 'jasa_pasang') || 
        str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') || 
        str_contains(strtolower($l->nama_layanan ?? ''), 'jasa pasang')
    ))->count();

    $motorCustomCount = $layanan->filter(fn($l) => strtolower($l->kategori ?? '') === 'motor' && (
        $l->jenis_layanan !== 'jasa_pasang' &&
        !str_contains(strtolower($l->tipe_paket ?? ''), 'jasa-pasang') &&
        (
            str_contains(strtolower($l->tipe_paket ?? ''), 'custom') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'striping') || 
            str_contains(strtolower($l->tipe_paket ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'striping') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'decal') || 
            str_contains(strtolower($l->nama_layanan ?? ''), 'custom')
        )
    ))->count();
@endphp

<!-- Mobile Filter Backdrop (Hidden on Desktop & Tablet) -->
<div id="filter-backdrop" 
     onclick="toggleMobileFilter(false)"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300">
</div>

<!-- Sidebar Filter Container -->
<aside id="catalog-sidebar"
       class="w-full md:w-64 lg:w-72 xl:w-80 shrink-0 md:sticky md:top-24 self-start space-y-4
              fixed md:static inset-y-0 left-0 z-50 md:z-auto bg-white md:bg-transparent
              p-5 md:p-0 overflow-y-auto max-h-screen md:max-h-none -translate-x-full md:translate-x-0
              transition-transform duration-300 ease-in-out">

    <!-- Card Filter Utama -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4.5">

        <!-- Header Filter -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="ph-bold ph-sliders-horizontal text-[#ff6b00] text-base"></i>
                <h3 class="text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider">
                    Filter Katalog
                </h3>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="resetAllKatalogFilters()"
                        class="text-xs font-montserrat font-semibold text-slate-500 hover:text-[#ff6b00] transition-colors flex items-center gap-1.5">
                    <i class="ph-bold ph-arrow-counter-clockwise text-xs"></i>
                    <span>Reset</span>
                </button>
                <!-- Close Button on Mobile -->
                <button type="button" 
                        onclick="toggleMobileFilter(false)"
                        class="md:hidden w-7 h-7 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>
        </div>

        <!-- 1. KATEGORI KENDARAAN (DENGAN DROPDOWN ACCORDION SUB-LAYANAN) -->
        @include('landing.katalog.partials._bilah_sisi_kategori')

        <!-- 2. RENTANG HARGA & JAMINAN WORKSHOP -->
        @include('landing.katalog.partials._bilah_sisi_harga')

    </div>
</aside>

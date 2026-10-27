{{-- ============================================
    BAGIAN: Header Katalog E-Commerce
    Deskripsi: Breadcrumb navigasi, judul katalog, dan deskripsi toko
============================================ --}}
<div class="space-y-3 z-10 relative">
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs font-montserrat text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-[#ff6b00] transition-colors flex items-center gap-1">
            <i class="ph-bold ph-house text-xs"></i>
            <span>Beranda</span>
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Katalog Layanan</span>
    </nav>

    <!-- Header Content -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-orange-50 text-[#ff6b00] border border-orange-200 uppercase tracking-wider">
                    Katalog Layanan
                </span>
                <span class="text-[11px] font-questrial text-slate-500 hidden sm:inline">
                    Pusat Variasi &amp; Wrapping Kendaraan Resmi
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-montserrat font-black text-slate-900 tracking-tight">
                {{ $heroTitle }}
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-slate-600 mt-1 max-w-2xl leading-relaxed">
                Temukan paket wrapping full body, jasa pemasangan decal mandiri, aksesoris, dan kaca film bergaransi resmi.
            </p>
        </div>

        <!-- Quick Info Badges -->
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <div class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-2 text-xs text-slate-700 shadow-sm">
                <i class="ph-bold ph-shield-check text-[#ff6b00]"></i>
                <span class="text-[11px] font-montserrat font-medium">Garansi Resmi</span>
            </div>
            <div class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-2 text-xs text-slate-700 shadow-sm">
                <i class="ph-bold ph-calendar-check text-emerald-600"></i>
                <span class="text-[11px] font-montserrat font-medium">Booking Mudah</span>
            </div>
        </div>
    </div>
</div>
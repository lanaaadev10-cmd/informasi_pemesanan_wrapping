@extends('layouts.dashboard_customer')

@section('title', ($profil->nama_perusahaan ?? 'Dantie Stiker') . ' - Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-6xl mx-auto pb-16" data-aos="fade-up" data-aos-duration="800">

    {{-- ═══════════════════════════════════════════════════════════
         1. TOP APP-BAR GREETING: Sapaan Ramah + Konteks Progres
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
                <span class="text-[10px] font-montserrat font-black uppercase tracking-widest text-[#FF6B00]">
                    Pusat Kontrol Pelanggan
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-audiowide font-bold text-white tracking-wide">
                Hello, {{ explode(' ', trim(Auth::user()->name))[0] }} 👋
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] mt-1 max-w-2xl leading-relaxed">
                Pantau progres pengerjaan kendaraan Anda atau amankan slot pengerjaan workshop minggu ini.
            </p>
        </div>

        {{-- Quick CTA --}}
        <div class="hidden sm:flex items-center gap-3 shrink-0">
            <a href="{{ route('kalkulator.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[44px] bg-[#16161A] hover:bg-white/10 border border-white/10 rounded-xl text-xs font-montserrat font-bold text-white transition-all active:scale-95 shadow-sm">
                <i class="ph-bold ph-calculator text-[#FF6B00] text-base"></i>
                <span>Wrap Studio</span>
            </a>
            <a href="{{ route('katalog.user') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[44px] bg-white/[0.03] hover:bg-white/[0.08] border border-white/10 rounded-xl text-xs font-montserrat font-bold text-white transition-all active:scale-95">
                <i class="ph-bold ph-tag text-[#FF6B00] text-base"></i>
                <span>Katalog Layanan</span>
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         2. WIDGET: BOOKING SAYA YANG AKTIF (FOTO PERTAMA)
            (Ditaruh tepat di atas Aksi Cepat sesuai instruksi)
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._active-booking-widget')

    {{-- ═══════════════════════════════════════════════════════════
         3. AKSI CEPAT: 12 FITUR LENGKAP USER DASHBOARD
            (Thumb-Zone Grid: 4 Kolom di HP, 6 Kolom di Desktop)
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._quick-action-grid')

    {{-- ═══════════════════════════════════════════════════════════
         4. KALENDER KOTAK KETERSEDIAAN SLOT (RESPONSIF SEMUA DEVICE)
            (Format Kotak Bulanan 7 Kolom + Live Quota + Detail Slot)
    ═══════════════════════════════════════════════════════════ --}}
    <div id="booking-calendar" class="scroll-mt-24 w-full">
        @include('dashboard.customer.dashboard._booking-calendar')
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         5. PEEK CAROUSEL: PAKET WRAPPING TERPOPULER & FILTER KATEGORI
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._top-picks')

    {{-- ═══════════════════════════════════════════════════════════
         6. GALERI PORTOFOLIO KARYA TERBARU
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._gallery-section')

</div>
@endsection
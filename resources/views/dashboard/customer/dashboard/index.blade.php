@extends('layouts.dashboard-customer')

@section('title', ($profil->nama_perusahaan ?? 'Dantie Stiker') . ' - Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-6xl mx-auto pb-16" data-aos="fade-up" data-aos-duration="800">

    {{-- ═══════════════════════════════════════════════════════════
         1. TOP APP-BAR GREETING: Sapaan Ramah + Konteks Progres
    ═══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-audiowide font-bold text-slate-900 tracking-wide">
                Hello, {{ explode(' ', trim(Auth::user()->name))[0] }}
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-slate-500 mt-1 max-w-2xl leading-relaxed">
                Pantau progres pengerjaan kendaraan Anda atau amankan slot pengerjaan workshop minggu ini.
            </p>
        </div>

        {{-- Quick CTA --}}
        <div class="hidden sm:flex items-center gap-3 shrink-0">
            <a href="{{ route('katalog.user') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[44px] bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-xs font-montserrat font-bold text-slate-700 transition-all active:scale-95 shadow-sm">
                <i class="ph-bold ph-storefront text-[#FF6B00] text-base"></i>
                <span>Katalog Layanan</span>
            </a>
            <a href="{{ route('booking.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#ea580c] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 active:scale-95">
                <i class="ph-bold ph-calendar-plus text-base"></i>
                <span>Booking Baru</span>
            </a>
        </div>
    </div>

    {{-- Banner Pengingat Ulasan Pengerjaan Selesai (Jika Ada yang Belum Dinilai) --}}
    @include('dashboard.customer.dashboard._pengingat_ulasan')

    {{-- ═══════════════════════════════════════════════════════════
         2. WIDGET: BOOKING SAYA YANG AKTIF (FOTO PERTAMA)
            (Ditaruh tepat di atas Aksi Cepat sesuai instruksi)
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._widget_booking_aktif')

    {{-- ═══════════════════════════════════════════════════════════
         3. AKSI CEPAT: 12 FITUR LENGKAP USER DASHBOARD
            (Thumb-Zone Grid: 4 Kolom di HP, 6 Kolom di Desktop)
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._menu_aksi_cepat')

    {{-- ═══════════════════════════════════════════════════════════
         4. KALENDER KOTAK KETERSEDIAAN SLOT (RESPONSIF SEMUA DEVICE)
            (Format Kotak Bulanan 7 Kolom + Live Quota + Detail Slot)
    ═══════════════════════════════════════════════════════════ --}}
    <div id="booking-calendar" class="scroll-mt-24 w-full">
        @include('dashboard.customer.dashboard._kalender_booking')
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         5. PEEK CAROUSEL: PAKET WRAPPING TERPOPULER & FILTER KATEGORI
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._paket_unggulan')

    {{-- ═══════════════════════════════════════════════════════════
         6. GALERI PORTOFOLIO KARYA TERBARU
    ═══════════════════════════════════════════════════════════ --}}
    @include('dashboard.customer.dashboard._seksi_galeri')

</div>
@endsection
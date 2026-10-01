@extends('layouts.dashboard-customer')

@section('title', 'Aktivitas & Transaksi Saya')

@php
    $dateInfo = function (?string $date) {
        if (!$date) return null;
        $d = \Carbon\Carbon::parse($date)->startOfDay();
        $today = now()->startOfDay();
        $diff = $today->diffInDays($d, false);
        return match (true) {
            $diff === 0 => 'Hari Ini',
            $diff === 1 => 'Besok',
            $diff === -1 => 'Kemarin',
            $diff < 0 => 'Lewat',
            $diff <= 3 => 'Segera',
            default => null,
        };
    };
@endphp

@section('content')
<div class="max-w-6xl mx-auto py-6 sm:py-8 text-white space-y-6 sm:space-y-8 relative overflow-hidden">
    <!-- Ambient glowing backdrop -->
    <div class="absolute -top-24 -left-24 w-[450px] h-[350px] bg-[#FF6B00]/5 rounded-full blur-[140px] pointer-events-none z-0"></div>

    <!-- 1. Header Hub Transaksi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10 relative border-b border-white/10 pb-6">
        <div>
            <div class="mb-2">
                <span class="text-[10px] font-montserrat font-black uppercase tracking-widest text-[#FF6B00]">
                    Pusat Manajemen Pelanggan
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-audiowide font-bold tracking-tight text-white">
                Aktivitas &amp; Transaksi
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] mt-1">
                Pantau seluruh reservasi jadwal booking bengkel dan pesanan layanan dalam satu panel terintegrasi.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('katalog.user') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[44px] bg-[#16161A] hover:bg-white/10 border border-white/10 rounded-xl text-xs font-montserrat font-bold text-white transition-all active:scale-95">
                <i class="ph-bold ph-storefront text-base"></i>
                <span class="hidden xs:inline">Katalog</span> Layanan
            </a>
            <a href="{{ route('booking.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_18px_rgba(255,107,0,0.35)] active:scale-95">
                <i class="ph-bold ph-plus-circle text-base"></i>
                <span>Booking Baru</span>
            </a>
        </div>
    </div>

    <!-- 2. Master Segmented Control (Peralihan Tab Utama) -->
    <div class="z-10 relative" role="tablist" aria-label="Kategori Transaksi">
        <div class="bg-[#0E0E10] border border-white/10 p-1.5 rounded-2xl flex items-center gap-2 max-w-md">
            {{-- Tab 1: Jadwal Booking --}}
            <a href="{{ route('transaksi.index', ['type' => 'booking']) }}"
               role="tab"
               id="tab-booking"
               aria-selected="{{ $type === 'booking' ? 'true' : 'false' }}"
               class="flex-1 flex items-center justify-center gap-2.5 py-3 min-h-[46px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all {{ $type === 'booking' ? 'bg-[#FF6B00] text-black shadow-lg font-black' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                <i class="ph-bold ph-calendar-check text-base"></i>
                <span>Jadwal Booking</span>
                @if($activeBookingsCount > 0)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold {{ $type === 'booking' ? 'bg-black text-white' : 'bg-[#FF6B00]/20 text-[#FF6B00]' }}">
                        {{ $activeBookingsCount }}
                    </span>
                @endif
            </a>

            {{-- Tab 2: Pesanan & Pembayaran --}}
            <a href="{{ route('transaksi.index', ['type' => 'pesanan']) }}"
               role="tab"
               id="tab-pesanan"
               aria-selected="{{ $type === 'pesanan' ? 'true' : 'false' }}"
               class="flex-1 flex items-center justify-center gap-2.5 py-3 min-h-[46px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all {{ $type === 'pesanan' ? 'bg-[#FF6B00] text-black shadow-lg font-black' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                <i class="ph-bold ph-receipt text-base"></i>
                <span>Pesanan Layanan</span>
                @if($activePesanansCount > 0)
                    <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold {{ $type === 'pesanan' ? 'bg-black text-white' : 'bg-[#FF6B00]/20 text-[#FF6B00]' }}">
                        {{ $activePesanansCount }}
                    </span>
                @endif
            </a>
        </div>
    </div>

    <!-- 3. KONTEN TAB: JADWAL BOOKING -->
    @if($type === 'booking')
        @include('dashboard.customer.transaksi.partials._tab-booking')
    @endif

    <!-- 4. KONTEN TAB: PESANAN & PEMBAYARAN -->
    @if($type === 'pesanan')
        @include('dashboard.customer.transaksi.partials._tab-pesanan')
    @endif
</div>
@endsection

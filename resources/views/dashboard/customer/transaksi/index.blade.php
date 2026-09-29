@extends('layouts.dashboard_customer')

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
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-[#FF6B00]"></span>
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
        <div class="space-y-6 z-10 relative" role="tabpanel" aria-labelledby="tab-booking">
            
            {{-- Filter Sub-Tabs Booking --}}
            @php
                $bookingFilters = [
                    'all' => ['label' => 'Semua', 'count' => $bookingStats['tab_all'] ?? 0],
                    'unpaid' => ['label' => 'Menunggu Bayar', 'count' => $bookingStats['tab_unpaid'] ?? 0],
                    'processing' => ['label' => 'Diproses', 'count' => $bookingStats['tab_processing'] ?? 0],
                    'completed' => ['label' => 'Selesai', 'count' => $bookingStats['tab_completed'] ?? 0],
                    'cancelled' => ['label' => 'Dibatalkan', 'count' => $bookingStats['tab_cancelled'] ?? 0],
                ];
            @endphp
            <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
                @foreach($bookingFilters as $key => $filter)
                    @php
                        $isActive = ($bookingTab === $key);
                    @endphp
                    <a href="{{ route('transaksi.index', ['type' => 'booking', 'booking_tab' => $key]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider whitespace-nowrap transition-all {{ $isActive ? 'bg-white text-black shadow-md font-black' : 'bg-[#0E0E10] text-[#8A8D93] hover:text-white hover:bg-white/5 border border-white/5' }}">
                        <span>{{ $filter['label'] }}</span>
                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded {{ $isActive ? 'bg-black/15 text-black' : 'bg-white/10 text-gray-300' }}">
                            {{ $filter['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- Daftar Kartu Booking --}}
            @if($bookings->isEmpty())
                <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-12 sm:p-16 text-center shadow-xl">
                    <div class="w-16 h-16 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] text-2xl mx-auto mb-4">
                        <i class="ph-bold ph-calendar-blank"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white mb-2">
                        Tidak Ada Jadwal Booking Ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-md mx-auto mb-6">
                        Belum ada reservasi pengerjaan pada filter ini. Amankan slot bengkel untuk jadwal Anda sekarang.
                    </p>
                    <a href="{{ route('booking.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-lg active:scale-95 transition-all">
                        <i class="ph-bold ph-plus-circle text-base"></i> Buat Booking Baru
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($bookings as $bk)
                        @php
                            $statusVal = $bk->status instanceof \App\Enums\BookingStatus ? $bk->status->value : $bk->status;
                            $infoDate = $dateInfo($bk->booking_date?->toDateString());
                            $isPast = $bk->booking_date?->isPast();
                        @endphp
                        <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/40 rounded-[24px] p-5 sm:p-6 transition-all shadow-xl group">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start sm:items-center gap-4 min-w-0">
                                    {{-- Chip Tanggal --}}
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border flex flex-col items-center justify-center shrink-0 shadow-md {{ $isPast ? 'bg-white/[0.02] border-white/5' : 'bg-[#FF6B00]/10 border-[#FF6B00]/30' }}">
                                        <span class="text-xl sm:text-2xl font-audiowide font-bold leading-none {{ $isPast ? 'text-gray-500' : 'text-[#FF6B00]' }}">
                                            {{ $bk->booking_date?->format('d') }}
                                        </span>
                                        <span class="text-[8px] sm:text-[9px] font-montserrat font-bold uppercase tracking-widest mt-1 {{ $isPast ? 'text-gray-500' : 'text-gray-300' }}">
                                            {{ $bk->booking_date?->translatedFormat('M Y') }}
                                        </span>
                                    </div>

                                    {{-- Rincian Data --}}
                                    <div class="flex-1 min-w-0 space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-audiowide text-white text-sm sm:text-base tracking-wide">
                                                {{ $bk->booking_code }}
                                            </span>
                                            <span class="text-gray-600">&bull;</span>
                                            @include('customer.booking.partials.status-badge', ['statusValue' => $statusVal])
                                        </div>

                                        <p class="text-xs sm:text-sm font-montserrat font-bold text-white truncate">
                                            {{ $bk->layanan?->nama_layanan ?? 'Paket Wrapping' }}
                                            <span class="text-[#8A8D93] font-normal">({{ $bk->vehicle_name }})</span>
                                        </p>

                                        <div class="flex items-center gap-3 text-xs font-questrial text-[#8A8D93] flex-wrap">
                                            <span>Pukul {{ $bk->booking_time ?: '09:00' }} WIB</span>
                                            @if($infoDate)
                                                <span class="text-gray-600">&bull;</span>
                                                <span class="font-bold text-[#FF6B00]">{{ $infoDate }}</span>
                                            @endif
                                            <span class="text-gray-600">&bull;</span>
                                            @include('customer.booking.partials.payment-pill', ['paymentType' => $bk->payment_type])
                                        </div>
                                    </div>
                                </div>

                                {{-- Aksi --}}
                                <div class="flex items-center justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/5 shrink-0">
                                    <a href="{{ route('booking.show', $bk->id) }}"
                                       class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-5 py-2.5 min-h-[44px] bg-[#16161A] hover:bg-[#FF6B00] hover:text-black border border-white/10 hover:border-[#FF6B00] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider text-white transition-all shadow-md active:scale-95">
                                        <span>Rincian Booking</span>
                                        <i class="ph-bold ph-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $bookings->appends(['type' => 'booking', 'booking_tab' => $bookingTab])->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- 4. KONTEN TAB: PESANAN & PEMBAYARAN -->
    @if($type === 'pesanan')
        <div class="space-y-6 z-10 relative" role="tabpanel" aria-labelledby="tab-pesanan">
            
            {{-- Filter Sub-Tabs Pesanan --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
                @php
                    $pesananFilters = [
                        '' => 'Semua Pesanan',
                        'menunggu_pembayaran' => 'Tagihan / Menunggu Bayar',
                        'berjalan' => 'Sedang Dikerjakan',
                        'selesai' => 'Selesai',
                    ];
                @endphp
                @foreach($pesananFilters as $fKey => $fLabel)
                    @php
                        $isActive = ((string) $pesananStatus === (string) $fKey);
                    @endphp
                    <a href="{{ route('transaksi.index', ['type' => 'pesanan', 'pesanan_status' => $fKey]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider whitespace-nowrap transition-all {{ $isActive ? 'bg-white text-black shadow-md font-black' : 'bg-[#0E0E10] text-[#8A8D93] hover:text-white hover:bg-white/5 border border-white/5' }}">
                        <span>{{ $fLabel }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Daftar Kartu Pesanan --}}
            @if($pesanans->isEmpty())
                <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-12 sm:p-16 text-center shadow-xl">
                    <div class="w-16 h-16 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] text-2xl mx-auto mb-4">
                        <i class="ph-bold ph-receipt"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white mb-2">
                        Tidak Ada Pesanan Layanan
                    </h3>
                    <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-md mx-auto mb-6">
                        Belum ada pesanan pada kategori ini. Anda dapat menjelajahi paket layanan dan melakukan pemesanan langsung.
                    </p>
                    <a href="{{ route('katalog.user') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-lg active:scale-95 transition-all">
                        <i class="ph-bold ph-storefront text-base"></i> Jelajahi Layanan
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pesanans as $ps)
                        @php
                            $stVal = $ps->status instanceof \App\Enums\OrderStatus ? $ps->status->value : $ps->status;
                            $isWaitingPay = in_array($stVal, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin', 'menunggu_verifikasi_pembayaran']);
                            $isDone = ($stVal === 'selesai');
                            $isWork = in_array($stVal, ['sedang_diproses', 'dikonfirmasi']);
                            $thumbnail = $ps->details->first()?->layanan?->foto_contoh;
                            $imageUrl = \App\Helpers\StaticContent::fotoUrl($thumbnail ?? '');
                        @endphp
                        <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/40 rounded-[24px] overflow-hidden flex flex-col md:flex-row group transition-all shadow-xl">
                            
                            <!-- Thumbnail Visual -->
                            <div class="md:w-56 h-40 md:h-auto relative shrink-0 bg-black/60 overflow-hidden">
                                <img src="{{ $imageUrl }}" alt="{{ $ps->form?->model_kendaraan ?? 'Mobil' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/80 via-transparent to-transparent"></div>
                            </div>

                            <!-- Detail Content -->
                            <div class="p-5 sm:p-6 flex flex-col justify-between flex-grow gap-4">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div class="space-y-1">
                                        <!-- Header & Clean Text Status -->
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-[10px] font-mono font-bold text-[#8A8D93] uppercase tracking-widest">
                                                #{{ $ps->kode_pesanan }}
                                            </span>
                                            <span class="text-gray-600">&bull;</span>
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $isWork || $isWaitingPay ? 'text-[#FF6B00]' : ($isDone ? 'text-white' : 'text-[#8A8D93]') }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $isWork || $isWaitingPay ? 'bg-[#FF6B00] animate-pulse' : ($isDone ? 'bg-white' : 'bg-gray-500') }}"></span>
                                                {{ $ps->label_status }}
                                            </span>
                                        </div>

                                        <h3 class="text-lg font-audiowide font-bold text-white leading-tight">
                                            {{ $ps->form?->model_kendaraan ?? 'Kendaraan Customer' }}
                                        </h3>
                                        <p class="text-xs font-questrial text-[#8A8D93]">
                                            {{ $ps->details->first()?->layanan?->nama_layanan ?? 'Full Wrapping' }}
                                            @if($ps->form?->warna_kendaraan)
                                                &bull; Warna: {{ $ps->form->warna_kendaraan }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="text-left sm:text-right">
                                        <span class="text-[9px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest block mb-0.5">Total Tagihan</span>
                                        <span class="text-[#FF6B00] font-audiowide font-bold text-xl sm:text-2xl">
                                            Rp {{ number_format($ps->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions Bar -->
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-white/5">
                                    <div class="flex items-center gap-4 text-xs font-montserrat font-bold text-[#8A8D93]">
                                        <span>Dipesan: {{ \Carbon\Carbon::parse($ps->tanggal_pesan)->translatedFormat('d M Y') }}</span>
                                    </div>

                                    <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                        @if($isWaitingPay)
                                            <a href="{{ route('pesanan.show', $ps->id_pesanan) }}"
                                               class="w-full sm:w-auto text-center px-5 py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_14px_rgba(255,107,0,0.35)] flex items-center justify-center gap-1.5 active:scale-95">
                                                <span>Bayar Tagihan</span>
                                                <i class="ph-bold ph-arrow-right text-xs"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('pesanan.show', $ps->id_pesanan) }}"
                                               class="w-full sm:w-auto text-center px-5 py-2.5 min-h-[44px] bg-[#16161A] hover:bg-[#FF6B00] hover:text-black border border-white/10 hover:border-[#FF6B00] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                                <span>Lihat Rincian</span>
                                                <i class="ph-bold ph-arrow-right text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $pesanans->appends(['type' => 'pesanan', 'pesanan_status' => $pesananStatus])->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

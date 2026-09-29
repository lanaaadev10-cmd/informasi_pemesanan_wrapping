@extends('layouts.dashboard_customer')

@php
    $accentColor = '#ff6b00';

    // Tanggal relatif (hari ini / besok / H-1) — dipakai di kartu.
    $dateInfo = function (?string $date) {
        if (!$date) return null;
        $d = \Carbon\Carbon::parse($date)->startOfDay();
        $today = now()->startOfDay();
        $diff = $today->diffInDays($d, false);
        $label = match (true) {
            $diff === 0 => ['text-[#ff6b00]', 'Hari Ini'],
            $diff === 1 => ['text-[#ff6b00]', 'Besok'],
            $diff === -1 => ['text-gray-500', 'Kemarin'],
            $diff < 0 => ['text-gray-500', 'Lewat'],
            $diff <= 3 => ['text-sky-400', 'Segera'],
            default => ['text-gray-500', null],
        };
        return $label;
    };
@endphp

@section('title', 'Booking Saya')

@section('content')
<div class="max-w-6xl mx-auto text-white space-y-8 relative overflow-hidden">
    {{-- Glow dekoratif --}}
    <div class="absolute top-0 right-0 w-[500px] h-[400px] bg-[#ff6b00]/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute bottom-10 left-0 w-[300px] h-[300px] bg-[#ff6b00]/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative border-b border-white/10 pb-6">
        <div>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#ff6b00]/10 border border-[#ff6b00]/20 text-[#ff6b00] text-[10px] font-montserrat font-bold uppercase tracking-widest mb-2.5">
                Sistem Informasi Wrapping
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-audiowide font-bold tracking-wide text-white">Booking Saya</h1>
            <p class="text-xs sm:text-sm font-questrial text-gray-400 mt-1">Kelola jadwal pengerjaan wrapping kendaraan Anda dalam satu panel terpadu.</p>
        </div>
        <a href="{{ route('booking.create') }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-2xl transition-all shadow-[0_4px_20px_rgba(255,107,0,0.35)] hover:scale-[1.02] active:scale-95 shrink-0 w-full sm:w-auto">
            <i class="ph-bold ph-calendar-plus text-base"></i> Booking Jadwal Baru
        </a>
    </div>

    {{-- Statistik ringkas (Sekaligus Filter Cepat) --}}
    @php
        $currentTab = $tab ?? request('tab', 'all');
        if (request('status') && !request('tab')) {
            $currentTab = match(request('status')) {
                'awaiting_payment' => 'unpaid',
                'pending', 'confirmed', 'payment_uploaded', 'approved', 'in_progress' => 'processing',
                'completed' => 'completed',
                'cancelled', 'rejected' => 'cancelled',
                default => 'all',
            };
        }

        $statCards = [
            [
                'key' => 'all',
                'label' => 'Total Booking',
                'value' => $stats['tab_all'] ?? $stats['total'] ?? 0,
                'icon' => 'ph-calendar-check',
                'color' => 'text-[#FF6B00]',
                'activeBorder' => 'border-[#FF6B00]',
                'url' => route('booking.index'),
            ],
            [
                'key' => 'unpaid',
                'label' => 'Menunggu Pembayaran',
                'value' => $stats['tab_unpaid'] ?? 0,
                'icon' => 'ph-credit-card',
                'color' => 'text-[#FF6B00]',
                'activeBorder' => 'border-[#FF6B00]',
                'url' => route('booking.index', ['tab' => 'unpaid']),
            ],
            [
                'key' => 'processing',
                'label' => 'Sedang Diproses',
                'value' => $stats['tab_processing'] ?? 0,
                'icon' => 'ph-gear-six',
                'color' => 'text-white',
                'activeBorder' => 'border-[#FF6B00]',
                'url' => route('booking.index', ['tab' => 'processing']),
            ],
            [
                'key' => 'completed',
                'label' => 'Selesai',
                'value' => $stats['tab_completed'] ?? 0,
                'icon' => 'ph-check-circle',
                'color' => 'text-white',
                'activeBorder' => 'border-[#FF6B00]',
                'url' => route('booking.index', ['tab' => 'completed']),
            ],
        ];

        $mainTabs = [
            'all' => [
                'label' => 'Semua',
                'count' => $stats['tab_all'] ?? $stats['total'] ?? 0,
                'url' => route('booking.index'),
                'icon' => 'ph-squares-four',
            ],
            'unpaid' => [
                'label' => 'Menunggu Bayar',
                'count' => $stats['tab_unpaid'] ?? 0,
                'url' => route('booking.index', ['tab' => 'unpaid']),
                'icon' => 'ph-credit-card',
                'highlight' => ($stats['tab_unpaid'] ?? 0) > 0,
            ],
            'processing' => [
                'label' => 'Sedang Diproses',
                'count' => $stats['tab_processing'] ?? 0,
                'url' => route('booking.index', ['tab' => 'processing']),
                'icon' => 'ph-gear-six',
            ],
            'completed' => [
                'label' => 'Selesai',
                'count' => $stats['tab_completed'] ?? 0,
                'url' => route('booking.index', ['tab' => 'completed']),
                'icon' => 'ph-check-circle',
            ],
            'cancelled' => [
                'label' => 'Dibatalkan',
                'count' => $stats['tab_cancelled'] ?? 0,
                'url' => route('booking.index', ['tab' => 'cancelled']),
                'icon' => 'ph-x-circle',
            ],
        ];

        $emptyMessage = match($currentTab) {
            'unpaid' => 'Tidak Ada Booking yang Menunggu Pembayaran',
            'processing' => 'Tidak Ada Booking yang Sedang Diproses',
            'completed' => 'Belum Ada Booking yang Berstatus Selesai',
            'cancelled' => 'Tidak Ada Booking yang Dibatalkan atau Ditolak',
            default => 'Belum Ada Jadwal Booking',
        };

        $emptySubtext = match($currentTab) {
            'unpaid' => 'Semua tagihan pesanan Anda sudah dibayar atau dalam proses verifikasi tim kami.',
            'processing' => 'Saat ini belum ada pesanan wrapping yang sedang dalam tahap konfirmasi maupun pengerjaan.',
            'completed' => 'Riwayat pengerjaan wrapping yang telah selesai akan tercatat rapi di sini.',
            'cancelled' => 'Tidak ada booking yang dibatalkan atau ditolak.',
            default => 'Amankan slot kuota harian kendaraan Anda untuk garansi pengerjaan wrapping terbaik dari tim teknisi kami.',
        };
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 z-10 relative">
        @foreach($statCards as $card)
            @php
                $isCardActive = ($currentTab === $card['key']);
            @endphp
            <a href="{{ $card['url'] }}"
               class="bg-[#0E0E10] border {{ $isCardActive ? $card['activeBorder'] . ' ring-1 ring-[#FF6B00]/30 shadow-[0_4px_20px_rgba(255,107,0,0.15)]' : 'border-white/10 hover:border-[#FF6B00]/40' }} rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-3 transition-all shadow-xl group hover:scale-[1.01] active:scale-[0.99]">
                <div class="min-w-0">
                    <p class="text-xl sm:text-2xl md:text-3xl font-audiowide font-bold text-white">{{ number_format($card['value']) }}</p>
                    <p class="text-[9px] sm:text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93] mt-1 truncate">{{ $card['label'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-white/[0.03] group-hover:bg-[#FF6B00]/10 border border-white/5 group-hover:border-[#FF6B00]/20 flex items-center justify-center {{ $card['color'] }} shrink-0 transition-colors">
                    <i class="ph-bold {{ $card['icon'] }} text-lg"></i>
                </div>
            </a>
        @endforeach
    </div>

    {{-- ── BARIS TAB STATUS TUNGGAL (BERSIH & TANPA DUPLIKASI) ── --}}
    <div class="z-10 relative">
        <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
            @foreach($mainTabs as $key => $tabItem)
                @php
                    $isActiveTab = ($currentTab === $key);
                @endphp
                <a href="{{ $tabItem['url'] }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider transition-all whitespace-nowrap {{ $isActiveTab ? 'bg-[#FF6B00] text-black shadow-[0_4px_16px_rgba(255,107,0,0.35)] scale-[1.02]' : 'bg-[#0E0E10] text-[#8A8D93] hover:text-white hover:bg-white/10 border border-white/10' }}">
                    <i class="ph-bold {{ $tabItem['icon'] }} text-sm {{ $isActiveTab ? 'text-black' : 'text-[#8A8D93]' }}"></i>
                    <span>{{ $tabItem['label'] }}</span>
                    @if(!empty($tabItem['highlight']))
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-[#FF6B00] text-black">
                            {{ $tabItem['count'] }}
                        </span>
                    @else
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold {{ $isActiveTab ? 'bg-black/20 text-black' : 'bg-white/10 text-gray-300' }}">
                            {{ $tabItem['count'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Daftar booking --}}
    @if($bookings->isEmpty())
        <div class="text-center py-16 sm:py-20 z-10 relative bg-[#0E0E10] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl">
            <div class="w-16 h-16 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] text-2xl mx-auto mb-4">
                <i class="ph-bold ph-calendar-blank"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white mb-2">
                {{ $emptyMessage }}
            </h3>
            <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-md mx-auto mb-6 leading-relaxed">
                {{ $emptySubtext }}
            </p>
            <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-[0_4px_20px_rgba(255,107,0,0.35)] transition-all active:scale-95">
                <i class="ph-bold ph-plus-circle text-base"></i> Buat Booking Sekarang
            </a>
        </div>
    @else
        <div class="space-y-4 z-10 relative">
            @foreach($bookings as $booking)
                @php
                    $statusValue = $booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : $booking->status;
                    $info = $dateInfo($booking->booking_date?->toDateString());
                    $isPast = $booking->booking_date?->isPast();
                @endphp
                <div class="group bg-[#0E0E10] border border-white/10 rounded-2xl p-4 sm:p-5 md:p-6 hover:border-[#FF6B00]/40 transition-all shadow-xl relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-4 min-w-0">
                            {{-- Chip tanggal --}}
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl border flex flex-col items-center justify-center shrink-0 shadow-lg {{ $isPast ? 'bg-white/[0.03] border-white/10' : 'bg-[#ff6b00]/10 border-[#ff6b00]/30' }}">
                                <span class="text-xl sm:text-2xl font-audiowide font-bold leading-none {{ $isPast ? 'text-gray-500' : 'text-[#ff6b00]' }}">{{ $booking->booking_date?->format('d') }}</span>
                                <span class="text-[8px] sm:text-[9px] font-montserrat font-bold uppercase tracking-widest mt-1 {{ $isPast ? 'text-gray-600' : 'text-gray-300' }}">{{ $booking->booking_date?->translatedFormat('M Y') }}</span>
                            </div>

                            {{-- Info utama --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-audiowide text-white text-sm sm:text-base tracking-wide">{{ $booking->booking_code }}</span>
                                    @include('customer.booking.partials.status-badge', ['statusValue' => $statusValue])
                                </div>
                                <p class="text-xs sm:text-sm font-montserrat font-bold text-gray-300 mt-1 truncate flex items-center gap-2">
                                    <span>{{ $booking->layanan?->nama_layanan }}</span>
                                    <span class="text-gray-600">&bull;</span>
                                    <span class="text-[#ff6b00]">{{ $booking->vehicle_name }}</span>
                                </p>
                                <div class="flex items-center gap-3 mt-1.5 flex-wrap font-questrial text-xs text-gray-400">
                                    <span>{{ $booking->booking_date?->translatedFormat('l, d F Y') }}</span>
                                    @if($info && $info[1])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-montserrat font-bold uppercase tracking-wider bg-[#ff6b00]/15 text-[#ff6b00] border border-[#ff6b00]/25">{{ $info[1] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/5 shrink-0">
                            <div class="inline-flex">
                                @include('customer.booking.partials.payment-pill', ['paymentType' => $booking->payment_type])
                            </div>
                            <a href="{{ route('booking.show', $booking->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 text-xs font-montserrat font-bold uppercase tracking-wider text-white bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 hover:border-[#ff6b00] rounded-xl transition-all shadow-md active:scale-95">
                                Detail <i class="ph-bold ph-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="z-10 relative pt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
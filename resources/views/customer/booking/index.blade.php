@extends('layouts.dashboard_customer')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';

    // Tanggal relatif (hari ini / besok / H-1) — dipakai di kartu.
    $dateInfo = function (?string $date) {
        if (!$date) return null;
        $d = \Carbon\Carbon::parse($date)->startOfDay();
        $today = now()->startOfDay();
        $diff = $today->diffInDays($d, false);
        $label = match (true) {
            $diff === 0 => ['text-[#f2994a]', 'Hari Ini'],
            $diff === 1 => ['text-[#f2994a]', 'Besok'],
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
    {{-- Glow dekoratif (konsisten token #f2994a) --}}
    <div class="absolute top-0 right-0 w-[550px] h-[450px] bg-[#f2994a]/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="absolute bottom-10 left-0 w-[350px] h-[350px] bg-[#e28a44]/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 z-10 relative border-b border-white/10 pb-6">
        <div>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a] text-xs font-extrabold uppercase tracking-widest mb-2">
                Sistem Informasi Wrapping
            </div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white">Booking Saya</h1>
            <p class="text-sm text-gray-400 mt-1">Kelola jadwal pengerjaan wrapping kendaraan Anda dalam satu panel terpadu.</p>
        </div>
        <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 min-h-[48px] bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-wider rounded-2xl transition-all shadow-[0_8px_25px_rgba(242,153,74,0.3)] hover:shadow-[0_12px_32px_rgba(242,153,74,0.5)] hover:scale-[1.02] active:scale-95">
            Booking Jadwal Baru
        </a>
    </div>

    {{-- Statistik ringkas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 z-10 relative">
        @php
            $statCards = [
                ['label' => 'Total Booking', 'value' => $stats['total']],
                ['label' => 'Menunggu Konfirmasi', 'value' => $stats['pending']],
                ['label' => 'Verifikasi / Bayar', 'value' => $stats['menunggu_bayar']],
                ['label' => 'Aktif & Selesai', 'value' => $stats['aktif'] + $stats['selesai']],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="bg-[#111111]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-5 flex items-center gap-4 hover:border-white/20 transition-all shadow-xl">
                <div class="min-w-0">
                    <p class="text-2xl font-black leading-none text-white">{{ number_format($card['value']) }}</p>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mt-1.5 truncate">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter status (scroll horizontal di mobile) --}}
    <div class="z-10 relative">
        <div class="flex gap-2 overflow-x-auto pb-2 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            @foreach([
                '' => ['Semua Status'],
                'pending' => ['Menunggu Konfirmasi'],
                'confirmed' => ['Dikonfirmasi'],
                'awaiting_payment' => ['Menunggu Bayar'],
                'payment_uploaded' => ['Verifikasi Bayar'],
                'approved' => ['Disetujui'],
                'in_progress' => ['Pengerjaan'],
                'completed' => ['Selesai'],
                'rejected' => ['Ditolak'],
                'cancelled' => ['Dibatalkan'],
            ] as $val => [$filterLabel])
                <a href="{{ route('booking.index', $val ? ['status' => $val] : []) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-extrabold uppercase tracking-wide transition-all whitespace-nowrap {{ request('status') === $val ? 'bg-[#f2994a] text-black shadow-[0_4px_16px_rgba(242,153,74,0.4)] scale-[1.02]' : 'bg-white/5 text-gray-400 hover:text-white border border-white/10 hover:bg-white/10' }}">
                    {{ $filterLabel }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Daftar booking --}}
    @if($bookings->isEmpty())
        <div class="text-center py-24 z-10 relative bg-[#111111]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            <h3 class="text-xl font-black text-white mb-2">
                {{ request('status') ? 'Tidak Ada Booking Berstatus ' . str_replace('_', ' ', strtoupper(request('status'))) : 'Belum Ada Jadwal Booking' }}
            </h3>
            <p class="text-sm text-gray-400 max-w-md mx-auto mb-8 leading-relaxed">
                Amankan slot kuota harian kendaraan Anda untuk garansi pengerjaan wrapping terbaik dari tim teknisi kami.
            </p>
            <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 min-h-[48px] bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-wider rounded-xl transition-all hover:scale-[1.02] shadow-[0_8px_25px_rgba(242,153,74,0.3)]">
                Buat Booking Sekarang
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
                <div class="group bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-2xl p-5 md:p-6 hover:border-[#f2994a]/40 transition-all shadow-xl relative overflow-hidden">
                    <div class="flex items-center gap-5">
                        {{-- Chip tanggal sebagai jangkar visual --}}
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl border flex flex-col items-center justify-center shrink-0 shadow-lg {{ $isPast ? 'bg-white/[0.03] border-white/10' : 'bg-[#f2994a]/10 border-[#f2994a]/30' }}">
                            <span class="text-2xl md:text-3xl font-black leading-none {{ $isPast ? 'text-gray-500' : 'text-[#f2994a]' }}">{{ $booking->booking_date?->format('d') }}</span>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest mt-1 {{ $isPast ? 'text-gray-600' : 'text-gray-300' }}">{{ $booking->booking_date?->translatedFormat('M Y') }}</span>
                        </div>

                        {{-- Info utama --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-black text-white text-base md:text-lg tracking-tight">{{ $booking->booking_code }}</span>
                                @include('customer.booking.partials.status-badge', ['statusValue' => $statusValue])
                            </div>
                            <p class="text-sm font-semibold text-gray-300 mt-1.5 truncate flex items-center gap-2">
                                <span>{{ $booking->layanan?->nama_layanan }}</span>
                                <span class="text-gray-600">•</span>
                                <span class="text-[#f2994a]">{{ $booking->vehicle_name }}</span>
                            </p>
                            <div class="flex items-center gap-4 mt-2.5 flex-wrap">
                                <span class="inline-flex items-center text-xs text-gray-400 font-medium">
                                    {{ $booking->booking_date?->translatedFormat('l, d F Y') }}
                                </span>
                                @if($info && $info[1])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#f2994a]/15 text-[#f2994a] border border-[#f2994a]/25">{{ $info[1] }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 shrink-0">
                            <div class="hidden lg:inline-flex">
                                @include('customer.booking.partials.payment-pill', ['paymentType' => $booking->payment_type])
                            </div>
                            <a href="{{ route('booking.show', $booking->id) }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-3 min-h-[46px] text-xs font-black uppercase tracking-wider text-white bg-white/5 hover:bg-[#f2994a] hover:text-black border border-white/10 hover:border-[#f2994a] rounded-xl transition-all shadow-md">
                                Detail
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
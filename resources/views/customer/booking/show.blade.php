@extends('layouts.dashboard-customer')

@php
    $buy = $booking->status instanceof \App\Enums\BookingStatus ? $booking->status : \App\Enums\BookingStatus::from($booking->status);
    $statusVal = $buy->value;

    $allSteps = [
        'pending'          => ['label' => 'Ajukan'],
        'confirmed'        => ['label' => 'Dikonfirmasi'],
        'awaiting_payment' => ['label' => 'Menunggu Bayar'],
        'payment_uploaded' => ['label' => 'Bukti Diperiksa'],
        'approved'         => ['label' => 'Disetujui'],
        'in_progress'      => ['label' => 'Dikerjakan'],
        'completed'        => ['label' => 'Selesai'],
    ];
    $orderKeys = array_keys($allSteps);
    $currentIdx = array_search($statusVal, $orderKeys, true);
    $isRejected = $statusVal === 'rejected';
    $isCancelled = $statusVal === 'cancelled';

    // Copy ringkas nilai nominal (sinkron dengan calculateDpAmount di backend).
    $harga = (float) ($booking->layanan?->harga ?? 0);
    $dpAmount = round($harga * 0.5);
    $nominal = $booking->payment_type === 'dp' ? $dpAmount : $harga;
@endphp

@section('title', 'Detail Booking - ' . $booking->booking_code)

@section('content')
<div class="max-w-5xl mx-auto text-white space-y-8 relative overflow-hidden">
    {{-- Glow background --}}
    <div class="absolute top-0 right-0 w-[550px] h-[450px] bg-[#f2994a]/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

    {{-- Navigasi Top Bar --}}
    <div class="flex items-center justify-between gap-4 z-10 relative">
        <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[44px] bg-white/5 hover:bg-white/10 text-xs font-extrabold uppercase tracking-wider rounded-xl border border-white/10 transition-all text-gray-300 hover:text-white">
            Kembali ke Daftar Booking
        </a>
        <button x-data="{ copied: false }"
                @click="navigator.clipboard.writeText('{{ $booking->booking_code }}'); copied = true; setTimeout(() => copied = false, 1600)"
                class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[44px] bg-white/5 hover:bg-white/10 text-xs font-extrabold uppercase tracking-wider rounded-xl border border-white/10 transition-all text-gray-300 hover:text-white">
            <span x-text="copied ? 'Kode Tersalin!' : 'Salin Kode Booking'"></span>
        </button>
    </div>

    {{-- Flash Toast Success Notification --}}
    @if(session('toast_success'))
        <div class="p-5 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-200 text-sm font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10 relative shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-black text-white text-base">Booking Berhasil Diajukan!</p>
                    <p class="text-xs text-emerald-300 mt-0.5">{{ session('toast_success') }}</p>
                </div>
            </div>
            <a href="{{ $booking->whatsapp_notification_url }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider transition-all shrink-0 shadow-md">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Kirim Bukti ke WhatsApp
            </a>
        </div>
    @endif

    {{-- Hero Kartu Booking --}}
    <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 z-10 relative shadow-2xl overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#f2994a]/15 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-2">Kode Pendaftaran Booking</p>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">{{ $booking->booking_code }}</h1>
                    @include('customer.booking.partials.status-badge', ['statusValue' => $statusVal, 'extra' => 'text-xs px-3 py-1 font-black uppercase tracking-wider'])
                </div>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                    Diajukan pada {{ $booking->created_at?->translatedFormat('l, d F Y • H:i') }} WIB
                </p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-right min-w-[200px]">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Jadwal & Jam Pengerjaan</p>
                    <p class="font-black text-lg text-[#f2994a] mt-0.5">{{ $booking->booking_date?->translatedFormat('l, d M Y') }}</p>
                    <p class="text-xs font-bold text-white mt-0.5">Pukul {{ $booking->booking_time ?: '09:00' }} WIB</p>
                </div>
                <a href="{{ $booking->whatsapp_notification_url }}" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-4 rounded-2xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/40 text-emerald-300 font-black text-xs uppercase tracking-wider transition-all shadow-md">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </div>

    {{-- Progress Stepper, Callout, & Form Upload Bukti --}}
    @include('customer.booking.partials._booking-stepper')

    {{-- Detail Paket Layanan, Spesifikasi Kendaraan, dan Pembayaran --}}
    @include('customer.booking.partials._booking-details')
</div>
@endsection
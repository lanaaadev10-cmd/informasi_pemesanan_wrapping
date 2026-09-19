{{-- Pill tipe pembayaran (DP / Lunas) — satu sumber gaya. --}}
@php
    $paymentType = $paymentType ?? $booking->payment_type;
    $isDp = $paymentType === 'dp';
@endphp
<span class="inline-flex items-center px-3 py-1.5 text-[10px] font-bold rounded-lg border border-white/10 uppercase tracking-wide {{ $isDp ? 'bg-yellow-500/15 text-yellow-300' : 'bg-emerald-500/15 text-emerald-300' }}">
    {{ $isDp ? 'DP' : 'Lunas' }}
</span>
{{-- Tipe pembayaran (DP / Lunas) — Clean Typography-First (No Pill Badge) --}}
@php
    $paymentType = $paymentType ?? ($booking->payment_type ?? 'dp');
    $isDp = strtolower($paymentType) === 'dp';
@endphp
<span class="inline-flex items-center gap-1.5 text-[10px] font-mono font-bold uppercase tracking-wider text-white">
    <span class="w-1.5 h-1.5 rounded-full {{ $isDp ? 'bg-[#FF6B00]' : 'bg-white' }} shrink-0"></span>
    <span class="{{ $isDp ? 'text-[#FF6B00]' : 'text-white' }}">{{ $isDp ? 'Skema DP' : 'Lunas' }}</span>
</span>
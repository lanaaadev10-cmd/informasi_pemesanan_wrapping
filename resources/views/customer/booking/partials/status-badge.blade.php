{{-- Badge status booking — dipakai di index & show (satu sumber warna). --}}
@php
    $statusValue = $statusValue ?? ($booking->status instanceof \App\Enums\BookingStatus ? $booking->status->value : ($booking->status ?? null));
    $enum = \App\Enums\BookingStatus::from($statusValue);
    $color = $enum->badgeColor();
    $classes = [
        'warning' => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
        'success' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30',
        'danger'  => 'bg-red-500/15 text-red-300 border-red-500/30',
        'info'    => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
        'primary' => 'bg-[#f2994a]/15 text-[#f2994a] border-[#f2994a]/30',
        'gray'    => 'bg-gray-500/15 text-gray-300 border-gray-500/30',
    ][$color] ?? 'bg-gray-500/15 text-gray-300 border-gray-500/30';
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full border {{ $classes }} {{ $extra ?? '' }}">
    {{ $enum->label() }}
</span>
{{-- Status Booking — Clean Text Indicator (White, Black, Orange Palette, No Badges) --}}
@php
    $statusRaw = $statusValue ?? ($booking->status ?? null);
    $enum = $statusRaw instanceof \App\Enums\BookingStatus 
        ? $statusRaw 
        : (\App\Enums\BookingStatus::tryFrom((string) $statusRaw) ?? \App\Enums\BookingStatus::PENDING);
    $statusVal = $enum->value;

    $textColor = match($statusVal) {
        'pending', 'awaiting_payment', 'payment_uploaded', 'in_progress', 'confirmed' => 'text-[#ff6b00]',
        'completed', 'approved' => 'text-white',
        default => 'text-gray-400',
    };
    $dotColor = match($statusVal) {
        'pending', 'awaiting_payment', 'payment_uploaded', 'in_progress', 'confirmed' => 'bg-[#ff6b00] animate-pulse',
        'completed', 'approved' => 'bg-white',
        default => 'bg-gray-500',
    };
@endphp
<span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $textColor }} {{ $extra ?? '' }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} shrink-0"></span>
    <span>{{ $enum->label() }}</span>
</span>
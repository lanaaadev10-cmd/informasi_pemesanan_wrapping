{{-- Widget: Booking Saya yang Aktif (Ditempatkan di Atas Aksi Cepat) --}}
@php
    use Carbon\Carbon;
    $dotColor = function(?string $status): string {
        return match($status) {
            'pending'                                           => 'bg-yellow-400',
            'confirmed','awaiting_payment','payment_uploaded'  => 'bg-blue-400',
            'approved','in_progress'                           => 'bg-[#FF6B00]',
            'completed'                                        => 'bg-white',
            'rejected','cancelled'                             => 'bg-red-400',
            default                                            => 'bg-gray-500',
        };
    };
@endphp

<div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-5 sm:p-6 shadow-2xl relative overflow-hidden group">
    {{-- Ambient Orange Glow --}}
    <div class="absolute -top-12 -right-12 w-40 h-40 bg-[#FF6B00]/10 rounded-full blur-[70px] pointer-events-none"></div>

    {{-- Widget Header --}}
    <div class="flex items-center justify-between border-b border-white/5 pb-3.5 mb-4">
        <div class="flex items-center gap-2">
            <i class="ph-bold ph-bookmark-simple text-[#FF6B00] text-base"></i>
            <h3 class="text-xs sm:text-sm font-montserrat font-black uppercase tracking-wider text-white">
                Booking Saya yang Aktif
            </h3>
        </div>
        <span class="text-xs font-mono font-bold text-[#FF6B00]">
            {{ $upcomingBookings->count() }} Booking
        </span>
    </div>

    {{-- State: Daftar Booking Aktif ATAU Kosong (Foto Pertama) --}}
    @if($upcomingBookings->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($upcomingBookings as $bk)
                @php
                    $bkStatus = $bk->status instanceof \App\Enums\BookingStatus
                        ? $bk->status->value : (string)$bk->status;
                    $bkLabel  = $bk->status instanceof \App\Enums\BookingStatus
                        ? $bk->status->label()
                        : ucfirst(str_replace('_', ' ', $bkStatus));
                    $isPast   = $bk->booking_date?->isPast();
                @endphp
                <a href="{{ route('booking.show', $bk->id) }}"
                   class="group/item flex items-center gap-3.5 bg-white/[0.02] hover:bg-[#FF6B00]/5 border border-white/5 hover:border-[#FF6B00]/30 rounded-2xl p-3.5 transition-all duration-200">
                    {{-- Badge Tanggal --}}
                    <div class="w-12 h-12 rounded-xl {{ $isPast ? 'bg-white/[0.03]' : 'bg-[#FF6B00]/10 border border-[#FF6B00]/30' }} flex flex-col items-center justify-center shrink-0">
                        <span class="text-base font-audiowide font-bold leading-none {{ $isPast ? 'text-[#8A8D93]' : 'text-[#FF6B00]' }}">{{ $bk->booking_date?->format('d') }}</span>
                        <span class="text-[8px] font-montserrat font-bold uppercase mt-0.5 text-[#8A8D93]">{{ $bk->booking_date?->format('M') }}</span>
                    </div>

                    {{-- Info Booking --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-montserrat font-bold text-white truncate group-hover/item:text-[#FF6B00] transition-colors">
                            {{ $bk->layanan?->nama_layanan ?? 'Car Wrapping' }}
                        </p>
                        <p class="text-[10px] font-questrial text-[#8A8D93] mt-0.5 truncate">
                            {{ $bk->vehicle_name }} &bull; {{ $bk->booking_code }}
                        </p>
                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-montserrat font-bold uppercase tracking-wider text-white">
                                <span class="w-1.5 h-1.5 rounded-full {{ in_array($bkStatus, ['completed']) ? 'bg-white' : 'bg-[#FF6B00]' }} shrink-0"></span>
                                <span class="{{ in_array($bkStatus, ['completed']) ? 'text-white' : 'text-[#FF6B00]' }}">{{ $bkLabel }}</span>
                            </span>
                        </div>
                    </div>

                    <i class="ph-bold ph-caret-right text-gray-500 group-hover/item:text-[#FF6B00] group-hover/item:translate-x-0.5 transition-all text-sm shrink-0"></i>
                </a>
            @endforeach
        </div>

        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between">
            <span class="text-[10px] font-questrial text-[#8A8D93]">
                Jadwal pengerjaan terkonfirmasi di workshop resmi.
            </span>
            <a href="{{ route('booking.index') }}"
               class="text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#E05D00] inline-flex items-center gap-1 transition-colors">
                <span>Kelola Semua Booking</span>
                <i class="ph-bold ph-arrow-right text-xs"></i>
            </a>
        </div>
    @else
        {{-- State Kosong: Persis Sesuai Foto Pertama yang Diunggah Pengguna --}}
        <div class="flex flex-col items-center justify-center text-center py-6 sm:py-8 px-4 space-y-3.5">
            <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93]">
                <i class="ph-bold ph-calendar-slash text-2xl"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-base sm:text-lg font-montserrat font-bold text-white">
                    Belum Ada Booking Aktif
                </h4>
                <p class="text-xs font-questrial text-[#8A8D93] leading-relaxed max-w-sm mx-auto">
                    Anda tidak memiliki jadwal booking kendaraan yang sedang aktif.
                </p>
            </div>
            <a href="{{ route('booking.create') }}"
               class="inline-flex items-center justify-center gap-1.5 px-6 py-2.5 min-h-[44px] text-xs font-montserrat font-black uppercase tracking-wider text-black bg-[#FF6B00] hover:bg-[#E05D00] rounded-xl transition-all active:scale-95 shadow-[0_4px_16px_rgba(255,107,0,0.35)]">
                <span>+ BUAT BOOKING</span>
            </a>
        </div>
    @endif
</div>

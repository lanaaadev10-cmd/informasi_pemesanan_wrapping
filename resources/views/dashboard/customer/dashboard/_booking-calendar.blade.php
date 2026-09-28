{{-- ============================================================
     WIDGET: Kalender Booking — Dashboard Customer
     Menampilkan kalender mini bulan ini + daftar booking mendatang.
     Variabel yang dibutuhkan: $upcomingBookings (Collection<Booking>),
     $bookingCalendar (array keyed 'Y-m-d' => BookingStatus value|null)
============================================================ --}}

@php
    use Carbon\Carbon;

    $today      = Carbon::today();
    $year       = (int) $today->format('Y');
    $month      = (int) $today->format('m');
    $firstDay   = Carbon::createFromDate($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    // Offset: Senin = 0, Selasa = 1, … Minggu = 6
    $startOffset = ($firstDay->dayOfWeek + 6) % 7;

    $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    // Warna dot per status
    $dotColor = function(?string $status): string {
        return match($status) {
            'pending'           => 'bg-yellow-400',
            'confirmed',
            'awaiting_payment',
            'payment_uploaded'  => 'bg-blue-400',
            'approved',
            'in_progress'       => 'bg-[#f2994a]',
            'completed'         => 'bg-emerald-400',
            'rejected',
            'cancelled'         => 'bg-red-400',
            default             => '',
        };
    };
@endphp

<div class="bg-[#111111] border border-white/5 rounded-3xl p-6 md:p-8 hover:border-[#f2994a]/20 transition-all duration-300 shadow-xl relative overflow-hidden">
    {{-- Glow dekoratif --}}
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#f2994a]/5 rounded-full blur-[80px] pointer-events-none"></div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 bg-[#f2994a]/10 border border-[#f2994a]/25 text-[#f2994a] text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                <i class="ph-bold ph-calendar-check"></i> Jadwal Booking
            </span>
            <h3 class="text-xl font-extrabold text-white mt-3 tracking-tight">
                {{ $today->translatedFormat('F Y') }}
            </h3>
        </div>
        <a href="{{ route('booking.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-[10px] uppercase tracking-wider rounded-xl hover:scale-[1.03] active:scale-95 transition-all shadow-[0_4px_16px_rgba(242,153,74,0.35)]">
            <i class="ph-bold ph-plus-circle text-sm"></i> Booking Baru
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ── Mini Kalender ── --}}
        <div>
            {{-- Day labels --}}
            <div class="grid grid-cols-7 mb-2">
                @foreach($dayLabels as $dl)
                    <div class="text-center text-[9px] font-black uppercase tracking-widest text-gray-500 py-1">{{ $dl }}</div>
                @endforeach
            </div>

            {{-- Day cells --}}
            <div class="grid grid-cols-7 gap-1">
                {{-- Offset kosong di awal --}}
                @for($i = 0; $i < $startOffset; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateKey   = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $status    = $bookingCalendar[$dateKey] ?? null;
                        $dot       = $dotColor($status);
                        $isToday   = ($day === (int)$today->format('d'));
                        $isPast    = Carbon::createFromDate($year, $month, $day)->isPast() && !$isToday;
                    @endphp
                    <div class="relative flex flex-col items-center justify-center aspect-square rounded-xl
                        {{ $isToday ? 'bg-[#f2994a]/20 border border-[#f2994a]/50 ring-1 ring-[#f2994a]/30' : ($isPast ? 'opacity-40' : 'bg-white/[0.02] hover:bg-white/[0.05]') }}
                        transition-all cursor-default group/day"
                        title="{{ $dateKey }}{{ $status ? ' — ' . str_replace('_', ' ', ucfirst($status)) : '' }}">
                        <span class="text-xs font-bold {{ $isToday ? 'text-[#f2994a]' : 'text-gray-300' }}">{{ $day }}</span>
                        @if($dot)
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot }} mt-0.5 block"></span>
                        @endif
                    </div>
                @endfor
            </div>

            {{-- Legenda --}}
            <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-white/5">
                @foreach([
                    ['bg-yellow-400',  'Pending'],
                    ['bg-blue-400',    'Konfirmasi / Bayar'],
                    ['bg-[#f2994a]',   'Disetujui / Proses'],
                    ['bg-emerald-400', 'Selesai'],
                    ['bg-red-400',     'Batal / Tolak'],
                ] as [$clr, $lbl])
                    <span class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 font-semibold">
                        <span class="w-2 h-2 rounded-full {{ $clr }} shrink-0"></span>{{ $lbl }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- ── Booking Mendatang ── --}}
        <div class="flex flex-col gap-3">
            @forelse($upcomingBookings as $bk)
                @php
                    $bkStatus = $bk->status instanceof \App\Enums\BookingStatus
                        ? $bk->status->value
                        : (string)$bk->status;
                    $bkDot    = $dotColor($bkStatus);
                    $bkLabel  = $bk->status instanceof \App\Enums\BookingStatus
                        ? $bk->status->label()
                        : ucfirst(str_replace('_', ' ', $bkStatus));
                    $isPast   = $bk->booking_date?->isPast();
                @endphp
                <a href="{{ route('booking.show', $bk->id) }}"
                   class="group/bk flex items-center gap-4 bg-white/[0.02] hover:bg-[#f2994a]/5 border border-white/5 hover:border-[#f2994a]/30 rounded-2xl px-4 py-3.5 transition-all duration-200">

                    {{-- Tanggal chip --}}
                    <div class="w-12 h-12 rounded-xl {{ $isPast ? 'bg-white/[0.03]' : 'bg-[#f2994a]/10' }} flex flex-col items-center justify-center shrink-0">
                        <span class="text-lg font-black leading-none {{ $isPast ? 'text-gray-500' : 'text-[#f2994a]' }}">
                            {{ $bk->booking_date?->format('d') }}
                        </span>
                        <span class="text-[8px] font-extrabold uppercase text-gray-500">
                            {{ $bk->booking_date?->format('M') }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-white truncate group-hover/bk:text-[#f2994a] transition-colors">
                            {{ $bk->layanan?->nama_layanan ?? '-' }}
                        </p>
                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">
                            {{ $bk->vehicle_name }} &bull; {{ $bk->booking_code }}
                        </p>
                    </div>

                    {{-- Status dot --}}
                    <span class="inline-flex items-center gap-1.5 text-[9px] font-extrabold uppercase tracking-wider whitespace-nowrap px-2.5 py-1 rounded-full bg-white/5 border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full {{ $bkDot }} shrink-0"></span>
                        {{ $bkLabel }}
                    </span>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center py-10 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-[#f2994a]">
                        <i class="ph-bold ph-calendar-slash text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Belum Ada Booking Aktif</p>
                    <p class="text-xs text-gray-600 max-w-xs leading-relaxed">Amankan jadwal pengerjaan kendaraan Anda sekarang.</p>
                    <a href="{{ route('booking.create') }}"
                       class="inline-flex items-center gap-2 mt-1 px-5 py-2.5 text-[10px] font-black uppercase tracking-wider text-black bg-[#f2994a] rounded-xl hover:bg-[#e28a44] transition-all active:scale-95">
                        Buat Booking Sekarang
                    </a>
                </div>
            @endforelse

            @if($upcomingBookings->count() > 0)
                <a href="{{ route('booking.index') }}"
                   class="mt-auto text-center text-[10px] font-black uppercase tracking-widest text-[#f2994a] hover:underline py-2">
                    Lihat Semua Booking <i class="ph-bold ph-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
</div>

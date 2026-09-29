{{-- ============================================================
     WIDGET: Kalender Ketersediaan Slot — Dashboard Customer
     Menampilkan kuota SEMUA user per hari.
     Klik tanggal → fetch API → modal detail slot (anonim).
     Variabel: $upcomingBookings (Collection<Booking>)
============================================================ --}}

@php
    use Carbon\Carbon;
    $today       = Carbon::today();
    $year        = (int) $today->format('Y');
    $month       = (int) $today->format('m');
    $firstDay    = Carbon::createFromDate($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startOffset = ($firstDay->dayOfWeek + 6) % 7; // Senin=0
    $dayLabels   = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    $dotColor = function(?string $status): string {
        return match($status) {
            'pending'                                            => 'bg-yellow-400',
            'confirmed','awaiting_payment','payment_uploaded'   => 'bg-blue-400',
            'approved','in_progress'                            => 'bg-[#FF6B00]',
            'completed'                                         => 'bg-emerald-400',
            'rejected','cancelled'                              => 'bg-red-400',
            default                                             => '',
        };
    };
@endphp

{{-- ════════════════════ WIDGET CARD ════════════════════ --}}
<div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-3.5 sm:p-6 md:p-8 hover:border-[#FF6B00]/40 transition-all duration-300 shadow-xl relative overflow-hidden"
     x-data="bookingCalWidget()"
     x-init="init()">

    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#FF6B00]/10 rounded-full blur-[80px] pointer-events-none"></div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-white/10">
        <div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                Ketersediaan Slot &bull; {{ $today->translatedFormat('F Y') }}
            </h3>
            <p class="text-xs font-questrial text-[#8A8D93] mt-1">Pilih tanggal di kalender kotak untuk melihat rincian slot dan kuota harian.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/[0.03] border border-white/10 text-xs font-mono text-[#8A8D93] shrink-0">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <span>Maks. 5 Slot / Hari</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        {{-- ── Kolom Kiri: Kalender Kotak Bulanan (7 Cols Span) ── --}}
        <div class="lg:col-span-7 flex flex-col justify-between">
            <div>
                {{-- Day labels --}}
                <div class="grid grid-cols-7 gap-1.5 mb-2">
                    @foreach($dayLabels as $dl)
                        <div class="text-center text-[10px] font-black uppercase tracking-widest text-gray-500 py-1.5">{{ $dl }}</div>
                    @endforeach
                </div>

                {{-- Day cells --}}
                <div class="grid grid-cols-7 gap-1 sm:gap-2">
                    {{-- Skeleton loading --}}
                    <template x-if="loading">
                        <template x-for="i in 35" :key="i">
                            <div class="aspect-square min-h-[38px] sm:min-h-[44px] rounded-xl bg-white/[0.02] border border-white/5 animate-pulse"></div>
                        </template>
                    </template>

                    {{-- Offset kosong --}}
                    <template x-if="!loading">
                        <template x-for="i in {{ $startOffset }}" :key="'off'+i">
                            <div class="aspect-square min-h-[38px] sm:min-h-[44px] rounded-xl bg-transparent border border-transparent"></div>
                        </template>
                    </template>

                    {{-- Tanggal cells --}}
                    <template x-if="!loading">
                        <template x-for="day in {{ $daysInMonth }}" :key="day">
                            <button
                                type="button"
                                @click="onDayClick(day)"
                                :class="cellClass(day)"
                                class="relative flex flex-col items-center justify-center aspect-square min-h-[38px] sm:min-h-[44px] rounded-xl text-xs sm:text-sm font-montserrat font-bold transition-all duration-150 p-1">

                                <span x-text="day"></span>

                                {{-- Kuota dot --}}
                                <span class="w-1.5 h-1.5 rounded-full mt-0.5 block shrink-0"
                                      :class="dotClass(day)"></span>

                                {{-- Badge jumlah slot terpakai --}}
                                <template x-if="slotCount(day) > 0">
                                    <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 rounded-full text-black text-[8px] font-black flex items-center justify-center leading-none shadow-md font-montserrat"
                                          :class="slotCount(day) >= 5 ? 'bg-red-500 text-white' : (slotCount(day) >= 3 ? 'bg-[#ff6b00] text-white' : 'bg-emerald-500 text-black')"
                                          x-text="slotCount(day)">
                                    </span>
                                </template>

                                {{-- FULL badge --}}
                                <template x-if="isFull(day)">
                                    <span class="absolute -top-1 -left-1 text-[6px] font-black bg-red-500 text-white px-1 rounded-full leading-tight shadow font-montserrat">FULL</span>
                                </template>
                            </button>
                        </template>
                    </template>
                </div>
            </div>

            {{-- Legenda --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-6 pt-4 border-t border-white/5 font-questrial">
                @foreach([
                    ['bg-emerald-500', 'Tersedia (1–2 slot)'],
                    ['bg-[#ff6b00]',   'Sisa Sedikit (3–4 slot)'],
                    ['bg-red-500',     'Penuh (5/5 slot)'],
                ] as [$clr, $lbl])
                    <span class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                        <span class="w-2 h-2 rounded-full {{ $clr }} shrink-0"></span>{{ $lbl }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- ── Kolom Kanan: Panel Interaktif Detail Ketersediaan Slot ── --}}
        @include('dashboard.customer.dashboard.partials._calendar-detail-panel')

    </div>
</div>

{{-- Calendar Widget Alpine scripts --}}
@include('dashboard.customer.dashboard.partials._calendar-widget-script')

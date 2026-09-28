{{-- ============================================================
     WIDGET: Kalender Booking Interaktif — Dashboard Customer
     Klik tanggal → modal popup detail booking hari itu.
     Variabel: $upcomingBookings (Collection), $bookingCalendar (array),
               $monthBookingsJson (JSON string dari controller)
============================================================ --}}

@php
    use Carbon\Carbon;

    $today       = Carbon::today();
    $year        = (int) $today->format('Y');
    $month       = (int) $today->format('m');
    $firstDay    = Carbon::createFromDate($year, $month, 1);
    $daysInMonth = $firstDay->daysInMonth;
    $startOffset = ($firstDay->dayOfWeek + 6) % 7; // Senin = 0

    $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    $dotColor = function(?string $status): string {
        return match($status) {
            'pending'                                        => 'bg-yellow-400',
            'confirmed', 'awaiting_payment','payment_uploaded' => 'bg-blue-400',
            'approved', 'in_progress'                        => 'bg-[#f2994a]',
            'completed'                                      => 'bg-emerald-400',
            'rejected', 'cancelled'                          => 'bg-red-400',
            default                                          => '',
        };
    };

    $statusLabel = function(?string $status): string {
        return match($status) {
            'pending'           => 'Menunggu Konfirmasi',
            'confirmed'         => 'Dikonfirmasi',
            'awaiting_payment'  => 'Menunggu Bayar',
            'payment_uploaded'  => 'Verifikasi Bayar',
            'approved'          => 'Disetujui',
            'in_progress'       => 'Sedang Dikerjakan',
            'completed'         => 'Selesai',
            'rejected'          => 'Ditolak',
            'cancelled'         => 'Dibatalkan',
            default             => ucfirst(str_replace('_', ' ', $status ?? '')),
        };
    };

    // Build JSON data semua booking bulan ini untuk JS modal
    $calendarData = collect($monthBookings ?? [])->groupBy(
        fn($b) => optional($b->booking_date)->format('Y-m-d')
    )->map(fn($group) => $group->map(fn($b) => [
        'id'           => $b->id,
        'code'         => $b->booking_code,
        'layanan'      => $b->layanan?->nama_layanan ?? '-',
        'vehicle'      => $b->vehicle_name,
        'status'       => $b->status instanceof \App\Enums\BookingStatus ? $b->status->value : (string)$b->status,
        'submitted_at' => optional($b->created_at)->format('H:i') . ' WIB',
        'show_url'     => route('booking.show', $b->id),
    ])->values())->toArray();
@endphp

{{-- ════════════════════ WIDGET CARD ════════════════════ --}}
<div class="bg-[#111111] border border-white/5 rounded-3xl p-6 md:p-8 hover:border-[#f2994a]/20 transition-all duration-300 shadow-xl relative overflow-hidden">
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
            <div class="grid grid-cols-7 gap-1" id="bk-calendar-grid">
                @for($i = 0; $i < $startOffset; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateKey  = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $status   = $bookingCalendar[$dateKey] ?? null;
                        $dot      = $dotColor($status);
                        $isToday  = ($day === (int)$today->format('d'));
                        $isPast   = Carbon::createFromDate($year, $month, $day)->isPast() && !$isToday;
                        $hasData  = isset($calendarData[$dateKey]) && count($calendarData[$dateKey]) > 0;
                        $count    = $hasData ? count($calendarData[$dateKey]) : 0;
                    @endphp
                    <button type="button"
                        class="bk-day-cell relative flex flex-col items-center justify-center aspect-square rounded-xl transition-all
                            {{ $isToday
                                ? 'bg-[#f2994a]/20 border border-[#f2994a]/50 ring-1 ring-[#f2994a]/30 text-[#f2994a]'
                                : ($hasData
                                    ? 'bg-white/[0.04] hover:bg-[#f2994a]/10 border border-white/10 hover:border-[#f2994a]/40 cursor-pointer text-white hover:text-[#f2994a]'
                                    : ($isPast
                                        ? 'opacity-40 cursor-default text-gray-500'
                                        : 'bg-white/[0.02] hover:bg-white/[0.05] cursor-default text-gray-400'))
                            }}"
                        data-date="{{ $dateKey }}"
                        data-has="{{ $hasData ? 'true' : 'false' }}"
                        {{ !$hasData ? 'disabled' : '' }}
                        title="{{ $hasData ? $count . ' booking pada ' . $dateKey : $dateKey }}">
                        <span class="text-xs font-bold leading-none">{{ $day }}</span>
                        @if($dot)
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot }} mt-0.5 block"></span>
                        @elseif($isToday)
                            <span class="w-1 h-1 rounded-full bg-[#f2994a] mt-0.5 block"></span>
                        @endif
                        @if($hasData)
                            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#f2994a] text-black text-[8px] font-black flex items-center justify-center leading-none shadow">{{ $count }}</span>
                        @endif
                    </button>
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
                        ? $bk->status->value : (string)$bk->status;
                    $bkDot    = $dotColor($bkStatus);
                    $bkLabel  = $bk->status instanceof \App\Enums\BookingStatus
                        ? $bk->status->label()
                        : ucfirst(str_replace('_', ' ', $bkStatus));
                    $isPast   = $bk->booking_date?->isPast();
                @endphp
                <a href="{{ route('booking.show', $bk->id) }}"
                   class="group/bk flex items-center gap-4 bg-white/[0.02] hover:bg-[#f2994a]/5 border border-white/5 hover:border-[#f2994a]/30 rounded-2xl px-4 py-3.5 transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl {{ $isPast ? 'bg-white/[0.03]' : 'bg-[#f2994a]/10' }} flex flex-col items-center justify-center shrink-0">
                        <span class="text-lg font-black leading-none {{ $isPast ? 'text-gray-500' : 'text-[#f2994a]' }}">{{ $bk->booking_date?->format('d') }}</span>
                        <span class="text-[8px] font-extrabold uppercase text-gray-500">{{ $bk->booking_date?->format('M') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-white truncate group-hover/bk:text-[#f2994a] transition-colors">{{ $bk->layanan?->nama_layanan ?? '-' }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">{{ $bk->vehicle_name }} &bull; {{ $bk->booking_code }}</p>
                    </div>
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

{{-- ════════════════════ MODAL DETAIL HARI ════════════════════ --}}
<div id="bk-day-modal"
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200"
     aria-hidden="true">
    <div id="bk-day-modal-panel"
         class="relative w-full max-w-md bg-[#161616] border border-white/10 rounded-3xl shadow-2xl scale-95 transition-transform duration-200 overflow-hidden">

        {{-- Modal glow --}}
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#f2994a]/10 rounded-full blur-[60px] pointer-events-none"></div>

        {{-- Modal header --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-white/5">
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-[#f2994a] mb-1">Detail Booking</p>
                <h4 id="bk-modal-title" class="text-lg font-black text-white tracking-tight">—</h4>
            </div>
            <button id="bk-modal-close"
                    class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-all">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>

        {{-- Summary bar --}}
        <div id="bk-modal-summary" class="flex items-center gap-4 px-6 py-3 bg-white/[0.02] border-b border-white/5">
            <span class="text-[10px] text-gray-400 font-semibold">Memuat...</span>
        </div>

        {{-- Booking list --}}
        <div id="bk-modal-list" class="px-6 py-4 space-y-3 max-h-72 overflow-y-auto [scrollbar-width:thin] [scrollbar-color:rgba(242,153,74,0.3)_transparent]">
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6 pt-2">
            <a id="bk-modal-create-link" href="{{ route('booking.create') }}"
               class="block w-full text-center py-3 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-wider rounded-xl hover:opacity-90 transition-all active:scale-95">
                <i class="ph-bold ph-plus-circle mr-1"></i> Booking Tanggal Ini
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // ── Data ──────────────────────────────────────────────────────────────
    const CALENDAR_DATA = @json($calendarData);

    const STATUS_CONFIG = {
        pending:           { label: 'Menunggu Konfirmasi', dot: '#facc15' },
        confirmed:         { label: 'Dikonfirmasi',        dot: '#60a5fa' },
        awaiting_payment:  { label: 'Menunggu Bayar',      dot: '#60a5fa' },
        payment_uploaded:  { label: 'Verifikasi Bayar',    dot: '#60a5fa' },
        approved:          { label: 'Disetujui',           dot: '#f2994a' },
        in_progress:       { label: 'Sedang Dikerjakan',   dot: '#f2994a' },
        completed:         { label: 'Selesai',             dot: '#34d399' },
        rejected:          { label: 'Ditolak',             dot: '#f87171' },
        cancelled:         { label: 'Dibatalkan',          dot: '#f87171' },
    };

    // ── Elemen ────────────────────────────────────────────────────────────
    const modal      = document.getElementById('bk-day-modal');
    const panel      = document.getElementById('bk-day-modal-panel');
    const title      = document.getElementById('bk-modal-title');
    const summary    = document.getElementById('bk-modal-summary');
    const list       = document.getElementById('bk-modal-list');
    const closeBtn   = document.getElementById('bk-modal-close');
    const createLink = document.getElementById('bk-modal-create-link');

    // ── Helpers ───────────────────────────────────────────────────────────
    function openModal(dateKey, bookings) {
        // Format judul tanggal Indonesia
        const [y, m, d]   = dateKey.split('-').map(Number);
        const dateObj      = new Date(y, m - 1, d);
        const bulan        = ['Januari','Februari','Maret','April','Mei','Juni',
                              'Juli','Agustus','September','Oktober','November','Desember'];
        const hari         = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        title.textContent = `${hari[dateObj.getDay()]}, ${d} ${bulan[m-1]} ${y}`;

        // Summary bar
        const total   = bookings.length;
        const statuses = [...new Set(bookings.map(b => b.status))];
        summary.innerHTML = `
            <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-white">
                <i class="ph-bold ph-users text-[#f2994a]"></i>
                ${total} Booking
            </span>
            <span class="text-gray-600">|</span>
            ${statuses.map(s => {
                const cfg = STATUS_CONFIG[s] || { label: s, dot: '#6b7280' };
                return `<span class="inline-flex items-center gap-1 text-[9px] font-extrabold uppercase text-gray-400">
                    <span style="width:7px;height:7px;border-radius:50%;background:${cfg.dot};display:inline-block;"></span>
                    ${cfg.label}
                </span>`;
            }).join('<span class="text-gray-700">·</span>')}
        `;

        // List booking
        list.innerHTML = bookings.map((bk, i) => {
            const cfg = STATUS_CONFIG[bk.status] || { label: bk.status, dot: '#6b7280' };
            return `
            <a href="${bk.show_url}"
               class="flex items-center gap-3 bg-white/[0.02] hover:bg-[#f2994a]/5 border border-white/5 hover:border-[#f2994a]/30 rounded-2xl px-4 py-3 transition-all group/item">
                <div class="w-8 h-8 rounded-lg bg-[#f2994a]/10 flex items-center justify-center shrink-0 text-[#f2994a] font-black text-xs">
                    ${i + 1}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-white truncate group-hover/item:text-[#f2994a] transition-colors">${bk.layanan}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5 truncate">${bk.vehicle} &bull; ${bk.code}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="inline-flex items-center gap-1 text-[9px] font-extrabold uppercase px-2 py-1 rounded-full"
                          style="background: ${cfg.dot}22; color: ${cfg.dot}; border: 1px solid ${cfg.dot}44;">
                        <span style="width:5px;height:5px;border-radius:50%;background:${cfg.dot};display:inline-block;"></span>
                        ${cfg.label}
                    </span>
                    <p class="text-[9px] text-gray-600 mt-1 font-mono">Dikirim ${bk.submitted_at}</p>
                </div>
            </a>`;
        }).join('');

        // Update link booking baru dengan tanggal pre-fill
        createLink.href = `{{ route('booking.create') }}?date=${dateKey}`;

        // Tampilkan modal
        modal.classList.remove('opacity-0', 'pointer-events-none');
        panel.classList.remove('scale-95');
        panel.classList.add('scale-100');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('opacity-0', 'pointer-events-none');
        panel.classList.add('scale-95');
        panel.classList.remove('scale-100');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // ── Event Listeners ───────────────────────────────────────────────────
    document.querySelectorAll('.bk-day-cell[data-has="true"]').forEach(btn => {
        btn.addEventListener('click', () => {
            const dateKey  = btn.dataset.date;
            const bookings = CALENDAR_DATA[dateKey] || [];
            if (bookings.length > 0) openModal(dateKey, bookings);
        });
    });

    closeBtn.addEventListener('click', closeModal);

    // Klik di luar panel = tutup
    modal.addEventListener('click', e => {
        if (!panel.contains(e.target)) closeModal();
    });

    // ESC = tutup
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('pointer-events-none')) closeModal();
    });
})();
</script>
@endpush

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
            'approved','in_progress'                            => 'bg-[#f2994a]',
            'completed'                                         => 'bg-emerald-400',
            'rejected','cancelled'                              => 'bg-red-400',
            default                                             => '',
        };
    };
@endphp

{{-- ════════════════════ WIDGET CARD ════════════════════ --}}
<div class="bg-[#111111] border border-white/5 rounded-3xl p-6 md:p-8 hover:border-[#f2994a]/20 transition-all duration-300 shadow-xl relative overflow-hidden"
     x-data="bookingCalWidget()"
     x-init="init()">

    <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#f2994a]/5 rounded-full blur-[80px] pointer-events-none"></div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 bg-[#f2994a]/10 border border-[#f2994a]/25 text-[#f2994a] text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                <i class="ph-bold ph-calendar-check"></i> Ketersediaan Slot
            </span>
            <h3 class="text-xl font-extrabold text-white mt-3 tracking-tight">
                {{ $today->translatedFormat('F Y') }}
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Klik tanggal untuk melihat slot yang sudah terisi hari itu</p>
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

            {{-- Day cells — rendered by Alpine setelah quota di-load --}}
            <div class="grid grid-cols-7 gap-1 min-h-[160px]">
                {{-- Skeleton loading --}}
                <template x-if="loading">
                    <template x-for="i in 35" :key="i">
                        <div class="aspect-square rounded-xl bg-white/[0.02] animate-pulse"></div>
                    </template>
                </template>

                {{-- Offset kosong --}}
                <template x-if="!loading">
                    <template x-for="i in {{ $startOffset }}" :key="'off'+i">
                        <div></div>
                    </template>
                </template>

                {{-- Tanggal cells --}}
                <template x-if="!loading">
                    <template x-for="day in {{ $daysInMonth }}" :key="day">
                        <button
                            type="button"
                            @click="onDayClick(day)"
                            :disabled="isPast(day)"
                            :class="cellClass(day)"
                            class="relative flex flex-col items-center justify-center aspect-square rounded-xl text-xs font-bold transition-all duration-150">

                            <span x-text="day"></span>

                            {{-- Kuota dot --}}
                            <span class="w-1.5 h-1.5 rounded-full mt-0.5 block"
                                  :class="dotClass(day)"></span>

                            {{-- Badge jumlah slot terpakai --}}
                            <template x-if="slotCount(day) > 0">
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-black text-[8px] font-black flex items-center justify-center leading-none shadow"
                                      :class="slotCount(day) >= 5 ? 'bg-red-500' : (slotCount(day) >= 3 ? 'bg-[#f2994a]' : 'bg-emerald-500')"
                                      x-text="slotCount(day)">
                                </span>
                            </template>

                            {{-- FULL badge --}}
                            <template x-if="isFull(day)">
                                <span class="absolute -top-1 -left-1 text-[6px] font-black bg-red-500 text-white px-1 rounded-full leading-tight">FULL</span>
                            </template>
                        </button>
                    </template>
                </template>
            </div>

            {{-- Legenda --}}
            <div class="flex flex-wrap gap-x-4 gap-y-2 mt-4 pt-4 border-t border-white/5">
                @foreach([
                    ['bg-emerald-500', 'Tersedia (1–2 slot)'],
                    ['bg-[#f2994a]',   'Sisa Sedikit (3–4 slot)'],
                    ['bg-red-500',     'Penuh (5/5 slot)'],
                ] as [$clr, $lbl])
                    <span class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 font-semibold">
                        <span class="w-2 h-2 rounded-full {{ $clr }} shrink-0"></span>{{ $lbl }}
                    </span>
                @endforeach
                <span class="inline-flex items-center gap-1.5 text-[10px] text-[#f2994a] font-black">
                    <i class="ph-bold ph-cursor-click"></i> Klik tanggal untuk detail
                </span>
            </div>
        </div>

        {{-- ── Booking Mendatang Milik Saya ── --}}
        <div class="flex flex-col gap-3">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Booking Saya yang Aktif</p>
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
                <div class="flex flex-col items-center justify-center py-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-[#f2994a]">
                        <i class="ph-bold ph-calendar-slash text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-400">Belum Ada Booking Aktif</p>
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

    {{-- ════ Modal Detail Hari ════ --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         @click.self="closeModal()"
         style="display:none;">

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-lg bg-[#161616] border border-white/10 rounded-3xl shadow-2xl overflow-hidden">

            <div class="absolute -top-8 -right-8 w-28 h-28 bg-[#f2994a]/10 rounded-full blur-[50px] pointer-events-none"></div>

            {{-- Modal Header --}}
            <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-white/5">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-[#f2994a] mb-1">Ketersediaan Slot</p>
                    <h4 class="text-lg font-black text-white tracking-tight" x-text="modalTitle">—</h4>
                </div>
                <button @click="closeModal()"
                        class="mt-1 w-8 h-8 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-all shrink-0">
                    <i class="ph-bold ph-x text-xs"></i>
                </button>
            </div>

            {{-- Quota Bar --}}
            <div class="px-6 py-3 bg-white/[0.02] border-b border-white/5">
                <template x-if="modalLoading">
                    <div class="h-5 bg-white/5 rounded animate-pulse w-48"></div>
                </template>
                <template x-if="!modalLoading && modalData">
                    <div class="flex items-center gap-4 flex-wrap">
                        {{-- Progress bar kuota --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">Slot Terpakai</span>
                                <span class="text-[10px] font-black"
                                      :class="modalData.quota.is_full ? 'text-red-400' : (modalData.quota.total_used >= 3 ? 'text-[#f2994a]' : 'text-emerald-400')"
                                      x-text="modalData.quota.total_used + ' / ' + modalData.quota.max + ' slot'"></span>
                            </div>
                            <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                     :class="modalData.quota.is_full ? 'bg-red-500' : (modalData.quota.total_used >= 3 ? 'bg-[#f2994a]' : 'bg-emerald-500')"
                                     :style="`width: ${Math.round((modalData.quota.total_used / modalData.quota.max) * 100)}%`"></div>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider px-3 py-1.5 rounded-full shrink-0"
                              :class="modalData.quota.is_full
                                ? 'bg-red-500/15 text-red-400 border border-red-500/25'
                                : (modalData.quota.available <= 2
                                    ? 'bg-[#f2994a]/15 text-[#f2994a] border border-[#f2994a]/25'
                                    : 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/25')"
                              x-text="modalData.quota.is_full ? 'PENUH' : (modalData.quota.available + ' Slot Tersedia')">
                        </span>
                    </div>
                </template>
            </div>

            {{-- Slot List --}}
            <div class="px-6 py-4 space-y-2.5 max-h-64 overflow-y-auto [scrollbar-width:thin] [scrollbar-color:rgba(242,153,74,0.25)_transparent]">

                {{-- Loading skeleton --}}
                <template x-if="modalLoading">
                    <template x-for="i in 3" :key="i">
                        <div class="h-14 rounded-xl bg-white/[0.03] animate-pulse"></div>
                    </template>
                </template>

                {{-- Empty state --}}
                <template x-if="!modalLoading && modalData && modalData.slots.length === 0">
                    <div class="text-center py-6">
                        <p class="text-sm font-bold text-emerald-400">Semua slot masih tersedia!</p>
                        <p class="text-xs text-gray-500 mt-1">Belum ada yang booking di tanggal ini.</p>
                    </div>
                </template>

                {{-- Slot items --}}
                <template x-if="!modalLoading && modalData">
                    <template x-for="(slot, idx) in modalData.slots" :key="idx">
                        <component :is="slot.show_url ? 'a' : 'div'"
                                   :href="slot.show_url || undefined"
                                   class="flex items-center gap-3 rounded-2xl px-3.5 py-3 border transition-all"
                                   :class="slot.is_mine
                                     ? 'bg-[#f2994a]/8 border-[#f2994a]/30 hover:bg-[#f2994a]/12'
                                     : 'bg-white/[0.02] border-white/5'">

                            {{-- Nomor slot --}}
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-[10px] font-black"
                                 :class="slot.is_mine ? 'bg-[#f2994a] text-black' : 'bg-white/[0.05] text-gray-400'"
                                 x-text="idx + 1"></div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-white truncate flex items-center gap-2">
                                    <span x-text="slot.layanan"></span>
                                    <template x-if="slot.is_mine">
                                        <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded bg-[#f2994a]/20 text-[#f2994a]">Milik Saya</span>
                                    </template>
                                </p>
                                <p class="text-[10px] text-gray-500 mt-0.5 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="ph-bold ph-clock text-[9px]"></i>
                                        <span x-text="'Dikirim ' + slot.submitted_at"></span>
                                    </span>
                                    <span class="text-gray-700">·</span>
                                    <span x-text="slot.slot_type === 'booking' ? 'Booking' : 'Pesanan'"></span>
                                </p>
                            </div>

                            {{-- Status badge --}}
                            <span class="inline-flex items-center gap-1 text-[9px] font-extrabold uppercase whitespace-nowrap px-2 py-1 rounded-full"
                                  :style="`background: ${statusDot(slot.status)}22; color: ${statusDot(slot.status)}; border: 1px solid ${statusDot(slot.status)}44;`">
                                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                                      :style="`background: ${statusDot(slot.status)}`"></span>
                                <span x-text="slot.status_label"></span>
                            </span>
                        </component>
                    </template>
                </template>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-5 pt-3 flex gap-3">
                <template x-if="modalData && !modalData.quota.is_full">
                    <a :href="'{{ route('booking.create') }}?date=' + modalDate"
                       class="flex-1 text-center py-3 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-wider rounded-xl hover:opacity-90 transition-all active:scale-95">
                        <i class="ph-bold ph-plus-circle mr-1"></i> Booking Tanggal Ini
                    </a>
                </template>
                <template x-if="modalData && modalData.quota.is_full">
                    <div class="flex-1 text-center py-3 bg-red-500/10 border border-red-500/30 text-red-400 font-black text-xs uppercase tracking-wider rounded-xl">
                        Slot Penuh — Pilih Tanggal Lain
                    </div>
                </template>
                <button @click="closeModal()"
                        class="px-5 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-400 hover:text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bookingCalWidget() {
    const MAX  = 5;
    const TODAY_Y = {{ $year }};
    const TODAY_M = {{ $month }};
    const TODAY_D = {{ $today->day }};

    const STATUS_DOT = {
        pending:           '#facc15',
        confirmed:         '#60a5fa',
        awaiting_payment:  '#60a5fa',
        payment_uploaded:  '#60a5fa',
        approved:          '#f2994a',
        in_progress:       '#f2994a',
        completed:         '#34d399',
        rejected:          '#f87171',
        cancelled:         '#f87171',
        // Pesanan statuses
        menunggu_konfirmasi_admin:    '#facc15',
        menunggu_pembayaran:          '#60a5fa',
        pembayaran_diproses:          '#60a5fa',
        sedang_diproses:              '#f2994a',
    };

    const BULAN = ['Januari','Februari','Maret','April','Mei','Juni',
                   'Juli','Agustus','September','Oktober','November','Desember'];
    const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    return {
        loading:      true,
        monthQuota:   {},   // { 'Y-m-d': { available, total_used, is_full, max } }
        modalOpen:    false,
        modalLoading: false,
        modalDate:    null,
        modalTitle:   '',
        modalData:    null,

        async init() {
            await this.loadMonthQuota();
            this.loading = false;
        },

        async loadMonthQuota() {
            try {
                const res  = await fetch(`/api/booking/quota-month/${TODAY_Y}/${TODAY_M}`);
                const json = await res.json();
                this.monthQuota = json.quota ?? {};
            } catch (e) {
                console.error('Gagal load quota:', e);
            }
        },

        // ── Helpers ─────────────────────────────────────────────────
        dateKey(day) {
            return `${TODAY_Y}-${String(TODAY_M).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        },

        quotaForDay(day) {
            return this.monthQuota[this.dateKey(day)] ?? null;
        },

        slotCount(day) {
            return this.quotaForDay(day)?.total_used ?? 0;
        },

        isFull(day) {
            const q = this.quotaForDay(day);
            return q ? q.is_full : false;
        },

        isPast(day) {
            return day < TODAY_D;
        },

        dotClass(day) {
            if (this.isPast(day)) return 'bg-transparent';
            const q = this.quotaForDay(day);
            if (!q || q.total_used === 0) return 'bg-transparent';
            if (q.is_full)          return 'bg-red-500';
            if (q.total_used >= 3)  return 'bg-[#f2994a]';
            return 'bg-emerald-500';
        },

        cellClass(day) {
            const isToday = day === TODAY_D;
            const past    = this.isPast(day);
            const full    = this.isFull(day);
            const used    = this.slotCount(day);

            if (isToday) return 'bg-[#f2994a]/20 border border-[#f2994a]/50 ring-1 ring-[#f2994a]/30 text-[#f2994a] cursor-pointer hover:bg-[#f2994a]/25';
            if (past)    return 'opacity-30 cursor-not-allowed text-gray-600 bg-transparent border border-transparent';
            if (full)    return 'bg-red-500/10 border border-red-500/30 text-red-400 cursor-pointer hover:bg-red-500/15';
            if (used >= 3) return 'bg-[#f2994a]/8 border border-[#f2994a]/25 text-[#f2994a] cursor-pointer hover:bg-[#f2994a]/15';
            if (used > 0)  return 'bg-emerald-500/8 border border-emerald-500/20 text-white cursor-pointer hover:bg-emerald-500/12';
            return 'bg-white/[0.02] border border-white/5 text-gray-400 cursor-pointer hover:bg-white/[0.06] hover:text-white';
        },

        statusDot(status) {
            return STATUS_DOT[status] ?? '#6b7280';
        },

        // ── Modal ────────────────────────────────────────────────────
        async onDayClick(day) {
            if (this.isPast(day) && day !== TODAY_D) return;
            const dk = this.dateKey(day);
            const d  = new Date(TODAY_Y, TODAY_M - 1, day);
            this.modalDate  = dk;
            this.modalTitle = `${HARI[d.getDay()]}, ${day} ${BULAN[TODAY_M - 1]} ${TODAY_Y}`;
            this.modalOpen  = true;
            this.modalData  = null;
            this.modalLoading = true;

            try {
                const res  = await fetch(`/api/booking/day-detail/${dk}`);
                this.modalData = await res.json();
            } catch (e) {
                this.modalData = { quota: { available: MAX, total_used: 0, is_full: false, max: MAX }, slots: [] };
            } finally {
                this.modalLoading = false;
            }
        },

        closeModal() {
            this.modalOpen = false;
            this.modalData = null;
        },
    };
}
</script>
@endpush

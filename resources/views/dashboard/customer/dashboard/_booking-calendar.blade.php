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
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00]"></span>
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#FF6B00]">
                    Ketersediaan Slot
                </span>
                <span class="text-xs font-questrial text-[#8A8D93]">&bull; Kuota Harian</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white mt-2 tracking-wide">
                {{ $today->translatedFormat('F Y') }}
            </h3>
            <p class="text-xs font-questrial text-[#8A8D93] mt-0.5">Pilih tanggal di kalender kotak untuk melihat rincian slot yang terisi.</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/[0.03] border border-white/10 text-xs font-mono text-[#8A8D93] shrink-0">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
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

        {{-- ── Kolom Kanan: Panel Interaktif Detail Ketersediaan Slot (5 Cols Span) ── --}}
        <div id="calendar-slot-panel" class="lg:col-span-5 flex flex-col h-full">

            {{-- Placeholder Saat Belum Ada Tanggal yang Dipilih --}}
            <div x-show="!modalOpen"
                 class="h-full min-h-[220px] p-5 sm:p-6 rounded-2xl bg-white/[0.015] border border-dashed border-white/10 flex flex-col items-center justify-center text-center transition-all">
                <div class="w-12 h-12 rounded-2xl bg-[#ff6b00]/10 border border-[#ff6b00]/20 flex items-center justify-center text-[#ff6b00] mb-3">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <h4 class="text-sm font-montserrat font-bold text-white">Detail Ketersediaan Slot</h4>
                <p class="text-xs font-questrial text-[#8A8D93] mt-1 max-w-xs leading-relaxed">
                    Pilih salah satu tanggal pada kalender di sebelah kiri untuk melihat rincian slot yang telah terisi dan kuota harian.
                </p>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-mono text-[#8A8D93] bg-white/[0.03] px-3 py-1.5 rounded-lg border border-white/5">
                    <i class="ph-bold ph-cursor-click text-[#FF6B00]"></i>
                    <span>Klik kotak tanggal mana saja</span>
                </div>
            </div>

            {{-- Card Detail Saat Tanggal Dipilih --}}
            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="relative bg-[#16161A] border border-[#FF6B00]/30 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full"
                 style="display:none;">

                <div class="absolute -top-6 -right-6 w-24 h-24 bg-[#FF6B00]/10 rounded-full blur-[40px] pointer-events-none"></div>

                {{-- Panel Header --}}
                <div class="flex items-start justify-between px-5 pt-4 pb-3 border-b border-white/10">
                    <div>
                        <p class="text-[9px] font-montserrat font-bold uppercase tracking-widest text-[#FF6B00] mb-0.5">Ketersediaan Slot</p>
                        <h4 class="text-base font-audiowide font-bold text-white tracking-wide" x-text="modalTitle">—</h4>
                    </div>
                    <button @click="closeModal()"
                            title="Tutup detail tanggal"
                            class="mt-0.5 w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-[#8A8D93] hover:text-white transition-all shrink-0">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                </div>

                {{-- Quota Bar --}}
                <div class="px-5 py-3 bg-white/[0.02] border-b border-white/5">
                    <template x-if="modalLoading">
                        <div class="h-5 bg-white/5 rounded animate-pulse w-48"></div>
                    </template>
                    <template x-if="!modalLoading && modalData">
                        <div class="flex items-center gap-4 flex-wrap">
                            {{-- Progress bar kuota --}}
                            <div class="flex-1 min-w-[140px]">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93]">Slot Terpakai</span>
                                    <span class="text-[10px] font-montserrat font-bold"
                                          :class="modalData.quota.is_full ? 'text-red-400' : (modalData.quota.total_used >= 3 ? 'text-[#FF6B00]' : 'text-emerald-400')"
                                          x-text="modalData.quota.total_used + ' / ' + modalData.quota.max + ' slot'"></span>
                                </div>
                                <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :class="modalData.quota.is_full ? 'bg-red-500' : (modalData.quota.total_used >= 3 ? 'bg-[#FF6B00]' : 'bg-emerald-500')"
                                         :style="`width: ${Math.round((modalData.quota.total_used / modalData.quota.max) * 100)}%`"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] font-montserrat font-bold uppercase tracking-wider shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full"
                                      :class="modalData.quota.is_full ? 'bg-red-500' : (modalData.quota.available <= 2 ? 'bg-[#FF6B00]' : 'bg-white')"></span>
                                <span :class="modalData.quota.is_full ? 'text-red-400' : (modalData.quota.available <= 2 ? 'text-[#FF6B00]' : 'text-white')"
                                      x-text="modalData.quota.is_full ? 'PENUH' : (modalData.quota.available + ' Slot Tersedia')"></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Slot List --}}
                <div class="px-5 py-3.5 space-y-2 flex-1 max-h-60 overflow-y-auto [scrollbar-width:thin] [scrollbar-color:rgba(255,107,0,0.25)_transparent]">
                    {{-- Loading skeleton --}}
                    <template x-if="modalLoading">
                        <template x-for="i in 2" :key="i">
                            <div class="h-12 rounded-xl bg-white/[0.03] animate-pulse"></div>
                        </template>
                    </template>

                    {{-- Empty state --}}
                    <template x-if="!modalLoading && modalData && modalData.slots.length === 0">
                        <div class="text-center py-6">
                            <p class="text-xs font-montserrat font-bold text-emerald-400">Semua slot masih tersedia!</p>
                            <p class="text-[11px] font-questrial text-[#8A8D93] mt-0.5">Belum ada booking pengerjaan di tanggal ini.</p>
                        </div>
                    </template>

                    {{-- Slot items --}}
                    <template x-if="!modalLoading && modalData">
                        <template x-for="(slot, idx) in modalData.slots" :key="idx">
                            <component :is="slot.show_url ? 'a' : 'div'"
                                       :href="slot.show_url || undefined"
                                       class="flex items-center gap-2.5 rounded-xl px-3 py-2 border transition-all text-left"
                                       :class="slot.is_mine
                                         ? 'bg-[#FF6B00]/10 border-[#FF6B00]/30 hover:bg-[#FF6B00]/15'
                                         : 'bg-white/[0.02] border-white/5'">

                                {{-- Nomor slot --}}
                                <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-montserrat font-bold"
                                     :class="slot.is_mine ? 'bg-[#FF6B00] text-black' : 'bg-white/[0.05] text-[#8A8D93]'"
                                     x-text="idx + 1"></div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-montserrat font-bold text-white truncate flex items-center gap-1.5">
                                        <span x-text="slot.layanan"></span>
                                        <template x-if="slot.is_mine">
                                            <span class="text-[9px] font-mono font-bold text-[#FF6B00]">(Milik Saya)</span>
                                        </template>
                                    </p>
                                    <p class="text-[9px] font-questrial text-[#8A8D93] mt-0.5 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="ph-bold ph-clock text-[9px]"></i>
                                            <span x-text="'Dikirim ' + slot.submitted_at"></span>
                                        </span>
                                        <span class="text-white/20">&bull;</span>
                                        <span x-text="slot.slot_type === 'booking' ? 'Booking' : 'Pesanan'"></span>
                                    </p>
                                </div>

                                {{-- Status clean text (No pill badge) --}}
                                <span class="inline-flex items-center gap-1.5 text-[9px] font-montserrat font-bold uppercase whitespace-nowrap text-[#FF6B00]">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0 bg-[#FF6B00]"></span>
                                    <span x-text="slot.status_label"></span>
                                </span>
                            </component>
                        </template>
                    </template>
                </div>

                {{-- Actions --}}
                <div class="px-5 pb-4 pt-2.5 flex items-center gap-2.5 border-t border-white/5">
                    <template x-if="modalData && modalIsPast">
                        <div class="flex-1 text-center py-2.5 min-h-[44px] bg-white/5 border border-white/10 text-[#8A8D93] font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-clock-counter-clockwise"></i> Tanggal Sudah Berlalu
                        </div>
                    </template>
                    <template x-if="modalData && !modalIsPast && !modalData.quota.is_full">
                        <a :href="'{{ route('booking.create') }}?date=' + modalDate"
                           class="flex-1 text-center py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-[11px] uppercase tracking-wider rounded-xl hover:opacity-95 transition-all active:scale-95 shadow-md shadow-[#FF6B00]/25 flex items-center justify-center gap-1.5">
                            <i class="ph-bold ph-plus-circle text-sm"></i> Booking Tanggal Ini
                        </a>
                    </template>
                    <template x-if="modalData && !modalIsPast && modalData.quota.is_full">
                        <div class="flex-1 text-center py-2.5 min-h-[44px] bg-red-500/10 border border-red-500/30 text-red-400 font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl flex items-center justify-center">
                            Slot Penuh — Pilih Tanggal Lain
                        </div>
                    </template>
                    <button @click="closeModal()"
                            class="px-4 py-2.5 min-h-[44px] bg-white/5 hover:bg-white/10 border border-white/10 text-[#8A8D93] hover:text-white font-montserrat font-bold text-[11px] uppercase tracking-wider rounded-xl transition-all">
                        Tutup
                    </button>
                </div>
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
        approved:          '#FF6B00',
        in_progress:       '#FF6B00',
        completed:         '#34d399',
        rejected:          '#f87171',
        cancelled:         '#f87171',
        // Pesanan statuses
        menunggu_konfirmasi_admin:    '#facc15',
        menunggu_pembayaran:          '#60a5fa',
        pembayaran_diproses:          '#60a5fa',
        sedang_diproses:              '#FF6B00',
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
        modalIsPast:  false,
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
            if (this.isPast(day)) return 'bg-gray-600/40';
            const q = this.quotaForDay(day);
            if (!q || q.total_used === 0) return 'bg-transparent';
            if (q.is_full)          return 'bg-red-500';
            if (q.total_used >= 3)  return 'bg-[#ff6b00]';
            return 'bg-emerald-500';
        },

        cellClass(day) {
            const isToday    = day === TODAY_D;
            const isSelected = this.modalOpen && this.modalDate === this.dateKey(day);
            const past       = this.isPast(day);
            const full       = this.isFull(day);
            const used       = this.slotCount(day);

            let base = 'relative flex flex-col items-center justify-center aspect-square min-h-[38px] sm:min-h-[44px] rounded-xl text-xs sm:text-sm font-montserrat font-bold transition-all duration-150 p-1 cursor-pointer ';

            if (isSelected) {
                return base + 'bg-[#ff6b00] text-black font-extrabold ring-2 ring-white scale-105 z-10 shadow-[0_4px_16px_rgba(255,107,0,0.5)]';
            }
            if (isToday) return base + 'bg-[#ff6b00]/20 border border-[#ff6b00]/70 text-[#ff6b00] ring-2 ring-[#ff6b00]/30 hover:bg-[#ff6b00]/30 shadow-[0_0_12px_rgba(255,107,0,0.3)]';
            if (past)    return base + 'bg-white/[0.015] border border-white/[0.04] text-gray-500 opacity-60 hover:bg-white/[0.06] hover:opacity-100 hover:text-white';
            if (full)    return base + 'bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20';
            if (used >= 3) return base + 'bg-[#ff6b00]/15 border border-[#ff6b00]/30 text-[#ff6b00] hover:bg-[#ff6b00]/25';
            if (used > 0)  return base + 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20';
            return base + 'bg-white/[0.03] border border-white/5 text-gray-300 hover:bg-white/[0.08] hover:border-white/20 hover:text-white';
        },

        statusDot(status) {
            return STATUS_DOT[status] ?? '#6b7280';
        },

        // ── Inline Detail ─────────────────────────────────────────────
        async onDayClick(day) {
            const dk = this.dateKey(day);
            const d  = new Date(TODAY_Y, TODAY_M - 1, day);
            this.modalDate   = dk;
            this.modalIsPast = this.isPast(day);
            this.modalTitle  = `${HARI[d.getDay()]}, ${day} ${BULAN[TODAY_M - 1]} ${TODAY_Y}`;
            this.modalOpen   = true;
            this.modalData   = null;
            this.modalLoading = true;

            // Scroll on mobile into panel smoothly
            if (window.innerWidth < 1024) {
                this.$nextTick(() => {
                    document.getElementById('calendar-slot-panel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }

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
            this.modalDate = null;
        },
    };
}
</script>
@endpush

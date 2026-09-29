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
        monthQuota:   {},
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

        async onDayClick(day) {
            const dk = this.dateKey(day);
            const d  = new Date(TODAY_Y, TODAY_M - 1, day);
            this.modalDate   = dk;
            this.modalIsPast = this.isPast(day);
            this.modalTitle  = `${HARI[d.getDay()]}, ${day} ${BULAN[TODAY_M - 1]} ${TODAY_Y}`;
            this.modalOpen   = true;
            this.modalData   = null;
            this.modalLoading = true;

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

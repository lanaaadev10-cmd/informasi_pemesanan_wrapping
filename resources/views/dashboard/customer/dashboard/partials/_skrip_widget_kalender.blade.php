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
            if (this.isPast(day)) return 'bg-slate-300';
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
                return base + 'bg-[#ff6b00] text-white font-extrabold ring-2 ring-orange-300 scale-105 z-10 shadow-md shadow-orange-500/30';
            }
            if (isToday) return base + 'bg-orange-50 border border-orange-400 text-[#ff6b00] ring-2 ring-orange-200 hover:bg-orange-100 shadow-sm';
            if (past)    return base + 'bg-slate-50 border border-slate-100 text-slate-400 opacity-60 hover:bg-slate-100 hover:opacity-100 hover:text-slate-600';
            if (full)    return base + 'bg-red-50 border border-red-200 text-red-600 hover:bg-red-100';
            if (used >= 3) return base + 'bg-orange-50 border border-orange-200 text-[#ff6b00] hover:bg-orange-100';
            if (used > 0)  return base + 'bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100';
            return base + 'bg-white border border-slate-200 text-slate-700 hover:border-orange-300 hover:bg-orange-50/50 hover:text-[#ff6b00]';
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

<script>
function adminCalendarApp() {
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const MAX = 5;

    return {
        viewMode: 'month',
        year: new Date().getFullYear(),
        month: new Date().getMonth(),
        cells: [],
        quota: {},
        weekCells: [],
        weekOffset: 0,

        selectedDateDetail: null,
        selectedDateTitle: '',
        selectedDateQuota: null,
        daySlots: [],

        get monthLabel() {
            return `${MONTHS[this.month]} ${this.year}`;
        },

        async init() {
            await this.loadMonth();
            this.renderMonth();
        },

        async loadMonth() {
            try {
                const res = await fetch(`/api/booking/quota-month/${this.year}/${this.month + 1}`);
                if (res.ok) {
                    const json = await res.json();
                    this.quota = json.quota ?? {};
                }
            } catch(e) {}
        },

        renderMonth() {
            const todayStr = new Date().toISOString().slice(0, 10);
            const firstDay = new Date(this.year, this.month, 1).getDay();
            const totalDays = new Date(this.year, this.month + 1, 0).getDate();
            const list = [];

            for (let i = 0; i < firstDay; i++) {
                list.push({ empty: true, key: `e_${i}` });
            }

            for (let d = 1; d <= totalDays; d++) {
                const m = String(this.month + 1).padStart(2, '0');
                const dayStr = String(d).padStart(2, '0');
                const dateStr = `${this.year}-${m}-${dayStr}`;

                const q = this.quota[dateStr] || { available: MAX, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX);
                const used = q.total_used ?? (MAX - available);

                list.push({
                    empty: false,
                    key: dateStr,
                    date: dateStr,
                    day: d,
                    isToday: dateStr === todayStr,
                    isBlocked,
                    blockedReason: q.blocked_reason,
                    used,
                    available,
                });
            }

            this.cells = list;
        },

        renderWeek() {
            const base = new Date();
            base.setDate(base.getDate() + (this.weekOffset * 7));
            const sunday = new Date(base);
            sunday.setDate(base.getDate() - base.getDay());

            const list = [];
            for (let i = 0; i < 7; i++) {
                const cur = new Date(sunday);
                cur.setDate(sunday.getDate() + i);

                const y = cur.getFullYear();
                const m = String(cur.getMonth() + 1).padStart(2, '0');
                const d = String(cur.getDate()).padStart(2, '0');
                const dateStr = `${y}-${m}-${d}`;

                const q = this.quota[dateStr] || { available: MAX, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX);
                const used = q.total_used ?? (MAX - available);

                list.push({
                    date: dateStr,
                    dayName: DAYS[cur.getDay()],
                    dayNum: cur.getDate(),
                    monthShort: MONTHS_SHORT[cur.getMonth()],
                    isBlocked,
                    blockedReason: q.blocked_reason,
                    used,
                    available,
                });
            }
            this.weekCells = list;
        },

        cellClasses(c) {
            if (c.isBlocked) {
                return 'border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 text-gray-400';
            }
            if (c.used >= 5) {
                return 'border-rose-300 dark:border-rose-800/60 bg-rose-50/60 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300';
            }
            if (c.used === 4) {
                return 'border-amber-300 dark:border-amber-800/60 bg-amber-50/60 dark:bg-amber-950/20 text-amber-700 dark:text-amber-300';
            }
            return 'border-emerald-300 dark:border-emerald-800/60 bg-emerald-50/40 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300';
        },

        async openDayDetail(dateStr) {
            this.selectedDateDetail = dateStr;
            const dt = new Date(dateStr);
            this.selectedDateTitle = `${DAYS[dt.getDay()]}, ${dt.getDate()} ${MONTHS[dt.getMonth()]} ${dt.getFullYear()}`;
            this.selectedDateQuota = this.quota[dateStr] || { available: MAX, total_used: 0 };

            try {
                const res = await fetch(`/api/booking/day-detail/${dateStr}`);
                if (res.ok) {
                    const json = await res.json();
                    this.daySlots = json.slots || [];
                }
            } catch(e) {
                this.daySlots = [];
            }
        },

        async prevMonth() {
            this.month--;
            if (this.month < 0) { this.month = 11; this.year--; }
            await this.loadMonth();
            this.renderMonth();
        },

        async nextMonth() {
            this.month++;
            if (this.month > 11) { this.month = 0; this.year++; }
            await this.loadMonth();
            this.renderMonth();
        },

        async todayMonth() {
            const now = new Date();
            this.year = now.getFullYear();
            this.month = now.getMonth();
            await this.loadMonth();
            this.renderMonth();
        }
    };
}
</script>

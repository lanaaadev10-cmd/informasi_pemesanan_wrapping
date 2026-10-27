{{-- ============================================
    SKRIP: Alpine.js Booking Calendar Logic
============================================ --}}
<script>
    function bookingCalendar() {
        const now = new Date();
        const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const DAYS = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        const todayStr = fmtDate(now);
        const MAX = 5;

        function fmtDate(d) {
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${d.getFullYear()}-${m}-${day}`;
        }

        return {
            year: now.getFullYear(),
            month: now.getMonth(),
            cells: [],
            quota: {},
            selected: null,
            pendingDate: null,
            showLoginModal: false,
            showDateAlert: false,
            isLoggedIn: @json(auth()->check()),

            get monthLabel() {
                return `${MONTHS[this.month]} ${this.year}`;
            },

            async init() {
                await this.loadQuota();
                this.render();
            },

            async loadQuota() {
                try {
                    const res = await fetch(`/api/booking/quota-month/${this.year}/${this.month + 1}`);
                    if (res.ok) {
                        const json = await res.json();
                        this.quota = json.quota ?? {};
                    }
                } catch (e) {
                    this.quota = {};
                }
            },

            render() {
                const firstDay = new Date(this.year, this.month, 1);
                const startWeekday = firstDay.getDay();
                const daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                const cells = [];

                for (let i = 0; i < startWeekday; i++) {
                    cells.push({ empty: true, key: `e${i}` });
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = fmtDate(new Date(this.year, this.month, d));
                    const past = dateStr < todayStr;
                    const q = this.quota[dateStr];
                    const available = q ? q.available : MAX;
                    const status = past ? 'past' : (available <= 0 ? 'full' : (available <= 2 ? 'few' : 'available'));
                    cells.push({
                        empty: false,
                        key: dateStr,
                        date: dateStr,
                        day: d,
                        status,
                        available,
                        max: MAX,
                        disabled: past || status === 'full',
                        label: `${d} ${MONTHS[this.month]} ${this.year}`,
                    });
                }

                this.cells = cells;
            },

            cellClasses(cell) {
                const base = 'disabled:opacity-35 disabled:cursor-not-allowed ';
                if (cell.date === this.selected?.date) {
                    return base + 'border-stone-900 bg-stone-900 text-white font-bold shadow-lg ring-2 ring-[#ea580c] scale-105 z-10';
                }
                if (cell.status === 'full' || cell.status === 'past') return base + 'border-stone-100 bg-stone-50 text-stone-300';
                if (cell.status === 'few') return base + 'border-orange-200 bg-orange-50/50 text-stone-800 hover:border-[#ea580c] hover:bg-orange-50';
                return base + 'border-stone-200 bg-white text-stone-800 hover:border-stone-900 hover:shadow-sm';
            },

            selectDate(cell) {
                if (cell.disabled) return;
                this.selected = cell;
                this.pendingDate = cell.date;
                this.showDateAlert = false;
            },

            async prevMonth() {
                this.month--;
                if (this.month < 0) { this.month = 11; this.year--; }
                await this.loadQuota();
                this.render();
            },

            async nextMonth() {
                this.month++;
                if (this.month > 11) { this.month = 0; this.year++; }
                await this.loadQuota();
                this.render();
            },

            thisMonth() {
                this.year = now.getFullYear();
                this.month = now.getMonth();
                this.loadQuota().then(() => this.render());
            },

            goBooking() {
                // Jika belum login, selalu tampilkan pop-up clean & elegan
                if (!this.isLoggedIn) {
                    this.pendingDate = this.selected ? this.selected.date : null;
                    this.showLoginModal = true;
                    return;
                }

                // Jika sudah login tapi belum pilih tanggal di kalender
                if (!this.selected) {
                    this.showDateAlert = true;
                    setTimeout(() => { this.showDateAlert = false; }, 4000);
                    return;
                }

                // Jika sudah login dan sudah pilih tanggal, langsung lanjut ke form booking
                const target = `/booking/create?tanggal=${encodeURIComponent(this.selected.date)}`;
                window.location.href = target;
            }
        };
    }
</script>

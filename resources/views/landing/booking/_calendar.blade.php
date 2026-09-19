{{-- ============================================
     JADWAL BOOKING (KALENDER KUOTA)
     Butuh Alpine.js (sudah tersedia via resources/js/app.js)
     ============================================ --}}
<div id="booking" class="max-w-7xl mx-auto px-6 py-20" x-data="bookingCalendar()" x-init="init()">
    <div class="text-center mb-12">
        <span class="text-sm font-bold uppercase tracking-widest text-[#f2994a]">Jadwal Booking</span>
        <h2 class="section-title text-gradient font-black mt-2">Pilih Tanggal Pengerjaan</h2>
        <p class="section-subtitle max-w-2xl mx-auto">Cek ketersediaan slot harian (maksimal 4 booking) dan amankan jadwal pengerjaan wrapping kendaraan Anda.</p>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap items-center justify-center gap-6 mb-8 text-xs">
        <span class="flex items-center gap-2 text-gray-300"><span class="w-3 h-3 rounded-full inline-block bg-emerald-500"></span> Tersedia</span>
        <span class="flex items-center gap-2 text-gray-300"><span class="w-3 h-3 rounded-full inline-block bg-[#f2994a]"></span> Sedikit Slot</span>
        <span class="flex items-center gap-2 text-gray-300"><span class="w-3 h-3 rounded-full inline-block bg-red-500"></span> Penuh (FULL)</span>
        <span class="flex items-center gap-2 text-gray-500"><span class="w-3 h-3 rounded-full inline-block bg-white/10"></span> Hari Libur / Lewat</span>
    </div>

    {{-- Kalender --}}
    <div class="max-w-4xl mx-auto bg-[#111111] border border-white/8 rounded-3xl p-6 md:p-8 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-60 h-60 bg-[#f2994a]/10 rounded-full blur-[100px] pointer-events-none"></div>

        {{-- Header Navigasi Bulan --}}
        <div class="flex items-center justify-between mb-6 z-10 relative">
            <button @click="prevMonth()" class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-gray-300 hover:text-white text-xs font-bold uppercase tracking-wide transition-all">
                Sebelumnya
            </button>
            <h3 class="text-xl font-bold text-white capitalize" x-text="monthLabel"></h3>
            <div class="flex items-center gap-2">
                <button @click="thisMonth()" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-xs font-bold uppercase tracking-wide text-gray-300 hover:text-white transition-all">Bulan Ini</button>
                <button @click="nextMonth()" class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-gray-300 hover:text-white text-xs font-bold uppercase tracking-wide transition-all">
                    Berikutnya
                </button>
            </div>
        </div>

        {{-- Nama Hari --}}
        <div class="grid grid-cols-7 gap-2 mb-2 z-10 relative">
            <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="d">
                <div class="text-center text-[10px] font-extrabold uppercase tracking-widest text-gray-500 py-2" x-text="d"></div>
            </template>
        </div>

        {{-- Grid Tanggal --}}
        <div class="grid grid-cols-7 gap-2 z-10 relative">
            <template x-for="cell in cells" :key="cell.key">
                <template x-if="cell.empty">
                    <div class="h-14 rounded-xl"></div>
                </template>
                <template x-if="!cell.empty">
                    <button
                        @click="selectDate(cell)"
                        :disabled="cell.disabled"
                        :class="cellClasses(cell)"
                        class="h-14 rounded-xl border text-sm font-bold transition-all relative flex flex-col items-center justify-center"
                    >
                        <span x-text="cell.day"></span>
                        <span class="w-1.5 h-1.5 rounded-full mt-1"
                              :class="cell.status === 'full' ? 'bg-red-500' : (cell.status === 'few' ? 'bg-[#f2994a]' : (cell.status === 'available' ? 'bg-emerald-500' : 'bg-transparent'))">
                        </span>
                        <span x-show="cell.status === 'full'" class="absolute -top-1 -right-1 text-[7px] font-extrabold bg-red-500 text-white px-1.5 py-0.5 rounded-full">FULL</span>
                    </button>
                </template>
            </template>
        </div>

        {{-- Info Tanggal Terpilih --}}
        <div class="mt-6 p-4 rounded-2xl bg-white/5 border border-white/10 z-10 relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Tanggal Terpilih</p>
                    <p class="font-bold text-white" x-text="selected?.label || 'Belum ada tanggal terpilih'"></p>
                    <p class="text-xs text-gray-500" x-show="selected" x-text="selected ? 'Sisa ' + selected.available + ' dari ' + selected.max + ' slot' : ''"></p>
                </div>
            </div>
            <button
                @click="goBooking()"
                :disabled="!selected || selected.disabled"
                class="px-8 py-3.5 btn-premium text-sm disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:transform-none"
            >
                Booking Sekarang
            </button>
        </div>
    </div>

    {{-- Modal Guest (wajib login) --}}
    <div x-show="showLoginModal" x-transition.opacity class="fixed inset-0 z-[9999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" style="display:none">
        <div @click.outside="showLoginModal = false" class="bg-[#141414] border border-white/10 rounded-3xl p-8 max-w-md w-full text-center relative" style="box-shadow: 0 30px 80px rgba(0,0,0,0.7);">
            <button @click="showLoginModal = false" class="absolute top-4 right-4 px-3 py-1.5 rounded-xl bg-white/5 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-wide">Tutup</button>
            <h3 class="text-xl font-bold text-white mb-2">Masuk untuk Melanjutkan</h3>
            <p class="text-sm text-gray-400 mb-8">Silakan masuk atau daftar terlebih dahulu untuk memesan jadwal booking.</p>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('login') }}" class="btn-premium text-sm text-center">Masuk</a>
                <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl border border-white/15 text-sm font-bold text-white hover:border-[#f2994a] transition-all">Daftar Akun Baru</a>
            </div>
        </div>
    </div>
</div>

<script>
    function bookingCalendar() {
        const now = new Date();
        const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const DAYS = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        const todayStr = fmtDate(now);
        const MAX = 4;

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
            isLoggedIn: @json(auth()->check()),

            get monthLabel() {
                return `${MONTHS[this.month]} ${this.year}`;
            },

            async init() {
                await this.loadQuota();
                this.render();
            },

            async loadQuota() {
                const res = await fetch(`/api/booking/quota-month/${this.year}/${this.month + 1}`);
                if (res.ok) {
                    const json = await res.json();
                    this.quota = json.quota ?? {};
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
                const base = 'disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:border-white/10 ';
                if (cell.date === this.selected?.date) {
                    return base + 'border-[#f2994a] bg-[#f2994a]/20 text-white shadow-[0_0_20px_rgba(242,153,74,0.2)]';
                }
                if (cell.status === 'full' || cell.status === 'past') return base + 'border-white/10 bg-white/[0.02] text-gray-500 hover:border-white/20';
                if (cell.status === 'few') return base + 'border-[#f2994a]/40 bg-white/5 text-white hover:border-[#f2994a]';
                return base + 'border-white/10 bg-white/5 text-white hover:border-emerald-500';
            },

            selectDate(cell) {
                if (cell.disabled) return;
                this.selected = cell;
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

            async thisMonth() {
                this.year = now.getFullYear();
                this.month = now.getMonth();
                await this.loadQuota();
                this.render();
            },

            goBooking() {
                if (!this.selected || this.selected.disabled) return;
                if (this.isLoggedIn) {
                    window.location.href = `{{ route('booking.create') }}?date=${this.selected.date}`;
                } else {
                    this.pendingDate = this.selected.date;
                    this.showLoginModal = true;
                }
            },
        };
    }
</script>
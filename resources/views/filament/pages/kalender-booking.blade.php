<x-filament-panels::page>
    <div class="space-y-6" x-data="adminCalendarApp()" x-init="init()">

        {{-- ── STATS OVERVIEW CARD ── --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kuota Hari Ini</span>
                    <p class="text-2xl font-black text-primary-600 dark:text-primary-400 mt-1">
                        {{ $todayQuota['available'] }} <span class="text-sm font-normal text-gray-500">/ 5 Slot Tersedia</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-950 flex items-center justify-center text-primary-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Booking Aktif Hari Ini</span>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                        {{ $todayQuota['booked_count'] }} <span class="text-sm font-normal text-gray-500">Kendaraan</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Kuota Hari Ini</span>
                    <p class="text-lg font-black mt-1" :class="'{{ $todayQuota['is_full'] ? 'text-red-500' : ($todayQuota['available'] <= 1 ? 'text-amber-500' : 'text-emerald-500') }}'">
                        {{ $todayQuota['is_full'] ? 'PENUH (5/5 Slot)' : ($todayQuota['available'] <= 1 ? 'HAMPIR PENUH' : 'TERSEDIA') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>

        {{-- ── KALENDER UTAMA ADMIN ── --}}
        <div class="p-6 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm space-y-6">

            {{-- Top Controls --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="prevMonth()" class="px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-bold transition-all">
                        &larr; Prev
                    </button>
                    <h2 class="text-lg font-black text-gray-900 dark:text-white capitalize" x-text="monthLabel"></h2>
                    <button type="button" @click="nextMonth()" class="px-3 py-1.5 rounded-xl border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-bold transition-all">
                        Next &rarr;
                    </button>
                    <button type="button" @click="todayMonth()" class="px-3 py-1.5 rounded-xl bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 text-xs font-bold border border-primary-200 dark:border-primary-800">
                        Hari Ini
                    </button>
                </div>

                {{-- Mode View (Bulanan / Mingguan) --}}
                <div class="flex items-center gap-2">
                    <div class="flex items-center bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
                        <button type="button" @click="viewMode = 'month'"
                                :class="viewMode === 'month' ? 'bg-white dark:bg-gray-900 text-primary-600 font-black shadow-sm' : 'text-gray-500 font-bold'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all">
                            Bulanan
                        </button>
                        <button type="button" @click="viewMode = 'week'; renderWeek()"
                                :class="viewMode === 'week' ? 'bg-white dark:bg-gray-900 text-primary-600 font-black shadow-sm' : 'text-gray-500 font-bold'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all">
                            Mingguan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Legenda Kuota --}}
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Kuota Tersedia (&ge;2)</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-400"></span> Sisa 1 Slot (4/5)</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-500"></span> Penuh (5/5)</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Libur / Diblokir</span>
            </div>

            {{-- ── TAMPILAN BULANAN ── --}}
            <div x-show="viewMode === 'month'" class="space-y-2">
                <div class="grid grid-cols-7 gap-2">
                    <template x-for="d in ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']" :key="d">
                        <div class="text-center text-xs font-black uppercase text-gray-400 py-1" x-text="d"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7 gap-2">
                    <template x-for="cell in cells" :key="cell.key">
                        <template x-if="cell.empty">
                            <div class="h-20 rounded-2xl bg-gray-50/50 dark:bg-gray-950/20"></div>
                        </template>
                        <template x-if="!cell.empty">
                            <div @click="openDayDetail(cell.date)"
                                 class="h-20 rounded-2xl border p-2 flex flex-col justify-between cursor-pointer transition-all hover:scale-[1.02] shadow-sm relative group"
                                 :class="cellClasses(cell)">
                                <div class="flex items-center justify-between">
                                    <span class="font-black text-sm" x-text="cell.day"></span>
                                    <span x-show="cell.isToday" class="text-[9px] font-black px-1.5 py-0.5 rounded bg-primary-500 text-white">HARI INI</span>
                                </div>
                                <div>
                                    <template x-if="cell.isBlocked">
                                        <span class="text-[10px] font-bold text-gray-500 block truncate" x-text="cell.blockedReason || 'Tutup'"></span>
                                    </template>
                                    <template x-if="!cell.isBlocked">
                                        <div>
                                            <span class="text-[10px] font-black block"
                                                  :class="cell.used >= 5 ? 'text-rose-600 dark:text-rose-400' : (cell.used === 4 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400')"
                                                  x-text="cell.used + '/5 Booking'">
                                            </span>
                                            <span class="text-[9px] text-gray-400 block" x-text="cell.available + ' slot sisa'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            {{-- ── TAMPILAN MINGGUAN ── --}}
            <div x-show="viewMode === 'week'" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-7 gap-3">
                    <template x-for="w in weekCells" :key="w.date">
                        <div @click="openDayDetail(w.date)"
                             class="p-4 rounded-2xl border cursor-pointer transition-all hover:scale-[1.02] shadow-sm flex flex-col justify-between min-h-[140px]"
                             :class="cellClasses(w)">
                            <div>
                                <span class="text-[10px] font-black uppercase text-gray-400" x-text="w.dayName"></span>
                                <p class="text-xl font-black mt-1" x-text="w.dayNum"></p>
                                <p class="text-xs text-gray-400" x-text="w.monthShort"></p>
                            </div>
                            <div class="pt-2 border-t border-gray-200/50 dark:border-gray-800/50 mt-2">
                                <span class="text-xs font-black block"
                                      :class="w.used >= 5 ? 'text-rose-600' : (w.used === 4 ? 'text-amber-600' : 'text-emerald-600')"
                                      x-text="w.isBlocked ? 'TUTUP / LIBUR' : w.used + '/5 Terisi'">
                                </span>
                                <span class="text-[10px] text-gray-500 block" x-text="w.isBlocked ? (w.blockedReason || 'Libur') : w.available + ' slot sisa'"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        {{-- ── MODAL DETAIL BOOKING PER HARI ── --}}
        <div x-show="selectedDateDetail !== null"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div @click.away="selectedDateDetail = null"
                 class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
                    <div>
                        <span class="text-xs font-bold uppercase text-primary-500">Detail Jadwal &amp; Slot</span>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="selectedDateTitle"></h3>
                    </div>
                    <button type="button" @click="selectedDateDetail = null" class="p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Quota status pill --}}
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-gray-500 block">Total Digunakan:</span>
                        <span class="font-black text-sm text-gray-900 dark:text-white" x-text="(selectedDateQuota?.total_used ?? 0) + ' dari 5 Slot Maksimal'"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Slot Tersisa:</span>
                        <span class="font-black text-sm text-emerald-600 dark:text-emerald-400" x-text="(selectedDateQuota?.available ?? 5) + ' Slot'"></span>
                    </div>
                </div>

                {{-- List Slot (Booking + Pesanan) on that day --}}
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    <template x-if="daySlots.length === 0">
                        <p class="text-xs text-gray-400 text-center py-6">Belum ada booking/pesanan pada tanggal ini.</p>
                    </template>

                    <template x-for="(b, idx) in daySlots" :key="idx">
                        <div class="p-3.5 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-800 flex items-center justify-between gap-3 text-xs">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded"
                                          :class="b.slot_type === 'booking' ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700'"
                                          x-text="b.slot_type === 'booking' ? 'Booking' : 'Pesanan'"></span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase"
                                          :class="b.status === 'completed' ? 'bg-emerald-100 text-emerald-700' : (b.status === 'cancelled' || b.status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700')"
                                          x-text="b.status_label || b.status"></span>
                                    <span x-show="b.is_mine" class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700">Saya</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 font-semibold mt-1 truncate" x-text="b.layanan || 'Layanan'"></p>
                                <p class="text-[11px] text-gray-400" x-text="b.submitted_at || '-'"></p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="b.show_url">
                                    <a :href="b.show_url"
                                       class="px-2.5 py-1.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold text-[10px]">
                                        Buka
                                    </a>
                                </template>
                                <template x-if="!b.show_url">
                                    <span class="px-2.5 py-1.5 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-400 text-[10px] italic">Slot terisi</span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="button" @click="selectedDateDetail = null"
                            class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

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
                        // API mengembalikan 'slots' (booking + pesanan gabungan)
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
</x-filament-panels::page>

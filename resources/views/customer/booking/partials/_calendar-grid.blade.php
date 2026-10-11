{{-- ── AREA KALENDER LENGKAP WORKSHOP (BULANAN, MINGGUAN, HARIAN) ── --}}
<div x-show="showFullCalendar" x-transition class="space-y-4 border-t border-slate-100 pt-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-sm font-audiowide font-bold text-slate-900 tracking-wide">
                Kalender Lengkap Workshop
            </h3>
            <p class="text-xs font-questrial text-slate-500 mt-0.5">
                Navigasi bulan untuk reservasi tanggal di masa mendatang.
            </p>
        </div>

        {{-- Mode View Toggle (Bulanan / Mingguan / Harian) --}}
        <div class="flex items-center bg-slate-100 border border-slate-200 p-1 rounded-2xl w-fit self-start sm:self-auto shadow-sm">
            <button type="button" @click="setViewMode('month')"
                    :class="viewMode === 'month' ? 'bg-[#ff6b00] text-white font-montserrat font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-montserrat font-bold'"
                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                Bulanan
            </button>
            <button type="button" @click="setViewMode('week')"
                    :class="viewMode === 'week' ? 'bg-[#ff6b00] text-white font-montserrat font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-montserrat font-bold'"
                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                Mingguan
            </button>
            <button type="button" @click="setViewMode('day')"
                    :class="viewMode === 'day' ? 'bg-[#ff6b00] text-white font-montserrat font-extrabold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-montserrat font-bold'"
                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                Harian
            </button>
        </div>
    </div>

    {{-- Indikator Legenda Warna --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs bg-slate-50 border border-slate-200 p-3 rounded-2xl font-questrial">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shrink-0"></span>
            <span class="text-slate-700 text-[11px] truncate">Tersedia (&ge; 2 slot)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00] shadow-sm shrink-0"></span>
            <span class="text-slate-700 text-[11px] truncate">Hampir Penuh (4/5)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shrink-0"></span>
            <span class="text-slate-700 text-[11px] truncate">Penuh (5/5)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-400 shrink-0"></span>
            <span class="text-slate-500 text-[11px] truncate">Lewat / Tutup</span>
        </div>
    </div>

    {{-- ── VIEW 1: TAMPILAN BULANAN ── --}}
    <div x-show="viewMode === 'month'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 px-3 sm:px-4 py-2.5 sm:py-3 rounded-2xl shadow-sm">
            <button type="button" @click="prevCalMonth()"
                    class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all flex items-center gap-1 shadow-sm">
                <i class="ph-bold ph-caret-left text-sm"></i>
                <span class="hidden sm:inline">Bulan Sebelumnya</span>
            </button>
            <h3 class="text-sm sm:text-base font-audiowide font-bold text-slate-900 capitalize tracking-wide" x-text="calMonthLabel"></h3>
            <div class="flex items-center gap-1.5 sm:gap-2">
                <button type="button" @click="thisCalMonth()"
                        class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all shadow-sm">
                    Bulan Ini
                </button>
                <button type="button" @click="nextCalMonth()"
                        class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all flex items-center gap-1 shadow-sm">
                    <span class="hidden sm:inline">Bulan Berikutnya</span>
                    <i class="ph-bold ph-caret-right text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Header Nama Hari --}}
        <div class="grid grid-cols-7 gap-1 sm:gap-2">
            <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="d">
                <div class="text-center text-[10px] sm:text-[11px] font-montserrat font-bold uppercase text-slate-500 py-1" x-text="d"></div>
            </template>
        </div>

        {{-- Grid Tanggal Bulanan --}}
        <div class="grid grid-cols-7 gap-1 sm:gap-2">
            <template x-for="cell in calCells" :key="cell.key">
                <template x-if="cell.empty">
                    <div class="h-14 sm:h-16 rounded-xl sm:rounded-2xl bg-slate-50/50 border border-transparent"></div>
                </template>
                <template x-if="!cell.empty">
                    <button type="button"
                            @click="selectDate(cell.date)"
                            :disabled="cell.disabled"
                            :class="getCellClasses(cell)"
                            class="h-14 sm:h-16 rounded-xl sm:rounded-2xl border text-xs font-montserrat font-bold transition-all duration-200 relative flex flex-col items-center justify-between p-1.5 sm:p-2 text-center group">
                        <div class="w-full flex items-center justify-between text-[10px] sm:text-[11px]">
                            <span class="font-audiowide font-bold" x-text="cell.day"></span>
                            <span x-show="cell.isToday" class="text-[7px] sm:text-[8px] px-1 py-0.2 rounded bg-[#ff6b00] text-white font-montserrat font-black leading-tight shadow-sm">HARI INI</span>
                        </div>

                        <div class="w-full">
                            <template x-if="cell.status === 'past'">
                                <span class="text-[8px] sm:text-[9px] text-slate-400 font-medium block">Lewat</span>
                            </template>
                            <template x-if="cell.status === 'blocked'">
                                <span class="text-[8px] sm:text-[9px] text-slate-400 font-medium block truncate" x-text="cell.blockedReason || 'Tutup'"></span>
                            </template>
                            <template x-if="cell.status === 'full'">
                                <span class="text-[8px] sm:text-[9px] px-1 py-0.5 rounded-full bg-rose-50 text-rose-600 font-montserrat font-black border border-rose-200 block leading-tight">PENUH</span>
                            </template>
                            <template x-if="cell.status === 'few'">
                                <span class="text-[9px] sm:text-[10px] text-[#ff6b00] font-bold block" x-text="cell.used + '/5 booking'"></span>
                            </template>
                            <template x-if="cell.status === 'available'">
                                <span class="text-[9px] sm:text-[10px] text-emerald-600 font-bold block" x-text="cell.used + '/5 booking'"></span>
                            </template>
                        </div>
                    </button>
                </template>
            </template>
        </div>
    </div>

    {{-- ── VIEW 2: TAMPILAN MINGGUAN ── --}}
    <div x-show="viewMode === 'week'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 px-4 py-3 rounded-2xl shadow-sm">
            <button type="button" @click="prevWeek()" class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all shadow-sm">
                &larr; Minggu Sebelumnya
            </button>
            <h3 class="text-xs sm:text-sm font-audiowide font-bold text-slate-900 capitalize tracking-wide" x-text="weekRangeLabel"></h3>
            <button type="button" @click="nextWeek()" class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all shadow-sm">
                Minggu Berikutnya &rarr;
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-7 gap-2.5">
            <template x-for="day in weekDays" :key="day.date">
                <button type="button"
                        @click="selectDate(day.date)"
                        :disabled="day.disabled"
                        :class="getCellClasses(day)"
                        class="p-4 rounded-2xl border text-left transition-all duration-200 flex flex-col justify-between min-h-[110px] sm:min-h-[120px]">
                    <div>
                        <p class="text-[10px] font-montserrat font-bold uppercase text-slate-500" x-text="day.dayName"></p>
                        <p class="text-base sm:text-lg font-audiowide font-bold mt-0.5 text-slate-900" x-text="day.dayNum"></p>
                        <p class="text-[11px] font-questrial text-slate-500" x-text="day.monthShort"></p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 mt-2">
                        <span class="text-[10px] font-montserrat font-bold block"
                              :class="day.status === 'full' ? 'text-rose-600' : (day.status === 'few' ? 'text-[#ff6b00]' : (day.status === 'available' ? 'text-emerald-600' : 'text-slate-400'))"
                              x-text="day.statusText">
                        </span>
                        <span class="text-[9px] font-questrial text-slate-400 block mt-0.5" x-text="day.subText"></span>
                    </div>
                </button>
            </template>
        </div>
    </div>

    {{-- ── VIEW 3: TAMPILAN HARIAN ── --}}
    <div x-show="viewMode === 'day'" class="space-y-4">
        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 px-4 py-3 rounded-2xl shadow-sm">
            <button type="button" @click="prevDay()" class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all shadow-sm">
                &larr; Hari Sebelumnya
            </button>
            <h3 class="text-xs sm:text-sm font-audiowide font-bold text-slate-900 capitalize tracking-wide" x-text="formatDateFull(bookingDate)"></h3>
            <button type="button" @click="nextDay()" class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all shadow-sm">
                Hari Berikutnya &rarr;
            </button>
        </div>

        <div class="p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500">Status Kuota Hari Terpilih:</span>
                    <p class="text-lg sm:text-xl font-audiowide font-bold text-slate-900 mt-0.5" x-text="formatDateFull(bookingDate)"></p>
                </div>
                <div class="px-4 py-2 rounded-2xl border text-right self-start sm:self-auto"
                     :class="selectedDateQuota.is_full ? 'border-rose-200 bg-rose-50 text-rose-600' : (selectedDateQuota.available <= 1 ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700')">
                    <p class="text-[9px] font-montserrat font-bold uppercase tracking-widest">Ketersediaan Slot</p>
                    <p class="text-base sm:text-lg font-audiowide font-bold" x-text="(selectedDateQuota.available ?? 5) + ' Slot Tersedia (Maks 5)'"></p>
                </div>
            </div>
        </div>
    </div>
</div>

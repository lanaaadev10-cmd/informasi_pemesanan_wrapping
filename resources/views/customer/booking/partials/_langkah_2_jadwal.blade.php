{{-- ──────────────────────────────────────────
     LANGKAH 2: PILIH JADWAL & SHIFT KEDATANGAN (E-COMMERCE SCHEDULE PICKER)
     Deskripsi: Layout pemilihan tanggal & jam serah terima kendaraan ala e-commerce
                dengan visual quick-strip 14 hari, chips sesi waktu (Pagi/Siang/Sore),
                dan konfirmasi kuota workshop real-time.
────────────────────────────────────────── --}}
<section id="section-jadwal"
         class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-sm space-y-6">

    {{-- Header Seksi 2 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-sm font-montserrat font-bold shadow-sm shrink-0">
                2
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Jadwal Booking Pengerjaan
                </h2>
                <p class="text-xs font-questrial text-slate-500 mt-0.5">
                    Pilih tanggal reservasi dan perkiraan jam serah terima kendaraan di workshop (Maks. 5 kendaraan/hari)
                </p>
            </div>
        </div>

        {{-- Tombol Buka/Tutup Kalender Lengkap --}}
        <button type="button"
                @click="showFullCalendar = !showFullCalendar"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all w-fit self-start sm:self-auto shadow-sm">
            <i class="ph-bold ph-calendar text-[#ff6b00]"></i>
            <span x-text="showFullCalendar ? 'Sembunyikan Kalender Lengkap' : 'Buka Kalender Lengkap'"></span>
            <i class="ph-bold text-xs transition-transform duration-200"
               :class="showFullCalendar ? 'ph-caret-up rotate-180' : 'ph-caret-down'"></i>
        </button>
    </div>

    {{-- ── 1. STRIP KALENDER 14 HARI (HORIZONTAL QUICK PICKER) ── --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xs font-montserrat font-bold text-slate-800 uppercase tracking-wider">
                    Pilih Cepat Jadwal (14 Hari ke Depan)
                </span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-montserrat font-bold border border-emerald-500/20 hidden sm:inline-block">
                    Pantauan Slot Real-Time
                </span>
            </div>

            {{-- Panah Scroll Strip --}}
            <div class="flex items-center gap-1.5">
                <button type="button"
                        @click="scrollStrip('left')"
                        class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-[#ff6b00] hover:text-white border border-slate-200 flex items-center justify-center text-xs text-slate-600 transition-all active:scale-95 shadow-sm">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <button type="button"
                        @click="scrollStrip('right')"
                        class="w-7 h-7 rounded-xl bg-slate-100 hover:bg-[#ff6b00] hover:text-white border border-slate-200 flex items-center justify-center text-xs text-slate-600 transition-all active:scale-95 shadow-sm">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </div>

        {{-- Strip Cards Container --}}
        <div id="dateStripContainer"
             class="flex gap-2.5 sm:gap-3 overflow-x-auto pb-2 pt-1 scroll-smooth scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent -mx-1 px-1 relative z-10">
            @foreach($stripDays as $item)
                <button type="button"
                        id="strip-card-{{ $item['date_str'] }}"
                        @click="selectDate('{{ $item['date_str'] }}')"
                        :disabled="isStripDisabled('{{ $item['date_str'] }}')"
                        :class="getStripCardClasses('{{ $item['date_str'] }}')"
                        class="flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl border text-center min-w-[82px] sm:min-w-[92px] shrink-0 min-h-[110px] sm:min-h-[118px] relative transition-all duration-200 group focus:outline-none">

                    {{-- Badge Hari Ini --}}
                    @if($item['is_today'])
                        <span class="absolute top-1.5 left-1/2 -translate-x-1/2 text-[7px] sm:text-[8px] font-montserrat font-black uppercase text-white bg-[#ff6b00] px-1.5 py-0.5 rounded-full shadow-sm tracking-wider">
                            HARI INI
                        </span>
                    @endif

                    {{-- Hari & Tanggal --}}
                    <div class="space-y-0.5 {{ $item['is_today'] ? 'pt-2.5' : 'pt-1' }}">
                        <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500 group-hover:text-slate-700 transition-colors block">
                            {{ $item['day_name'] }}
                        </span>
                        <span class="text-xl sm:text-2xl font-audiowide font-bold block transition-transform group-hover:scale-110 text-slate-900">
                            {{ $item['day_num'] }}
                        </span>
                        <span class="text-[10px] font-questrial text-slate-500 block">
                            {{ $item['month'] }}
                        </span>
                    </div>

                    {{-- Indikator Kuota Slot --}}
                    <div class="pt-2 border-t border-slate-100 w-full">
                        <span class="text-[9px] sm:text-[10px] font-montserrat block truncate font-semibold"
                              :class="getStripSlotTextClass('{{ $item['date_str'] }}')"
                              x-text="getStripSlotLabel('{{ $item['date_str'] }}')">
                            5 Slot
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── 2. PILIHAN SESI WAKTU & JAM KEDATANGAN (INTERACTIVE TIME CHIPS) ── --}}
    <div class="space-y-3 pt-2 border-t border-slate-100">
        <div>
            <h3 class="text-xs sm:text-[13px] font-montserrat font-bold text-slate-800 uppercase tracking-wider">
                2. Pilih Jam Kedatangan (Shift Sesi)
            </h3>
            <p class="text-[11px] font-questrial text-slate-500 mt-0.5">
                Pilih perkiraan waktu kedatangan untuk serah terima unit kendaraan di workshop:
            </p>
        </div>

        {{-- Grid 3 Sesi Waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            {{-- Sesi Pagi --}}
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition-all space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-500 text-xs">
                            <i class="ph-bold ph-sun-horizon"></i>
                        </div>
                        <span class="text-xs font-montserrat font-bold text-slate-900">Sesi Pagi</span>
                    </div>
                    <span class="text-[9px] font-mono text-slate-500">08:30 - 11:00</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            @click="bookingTime = '08:30'"
                            :class="bookingTime === '08:30'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '08:30'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>08:30 WIB</span>
                    </button>
                    <button type="button"
                            @click="bookingTime = '10:00'"
                            :class="bookingTime === '10:00'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '10:00'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>10:00 WIB</span>
                    </button>
                </div>
                <p class="text-[10px] font-questrial text-slate-500 leading-tight">
                    *Rekomendasi terbaik untuk pengerjaan Full Wrap &amp; Paket Besar.
                </p>
            </div>

            {{-- Sesi Siang --}}
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition-all space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-yellow-500/15 border border-yellow-500/30 flex items-center justify-center text-yellow-600 text-xs">
                            <i class="ph-bold ph-sun"></i>
                        </div>
                        <span class="text-xs font-montserrat font-bold text-slate-900">Sesi Siang</span>
                    </div>
                    <span class="text-[9px] font-mono text-slate-500">11:30 - 14:30</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            @click="bookingTime = '11:30'"
                            :class="bookingTime === '11:30'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '11:30'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>11:30 WIB</span>
                    </button>
                    <button type="button"
                            @click="bookingTime = '13:30'"
                            :class="bookingTime === '13:30'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '13:30'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>13:30 WIB</span>
                    </button>
                </div>
                <p class="text-[10px] font-questrial text-slate-500 leading-tight">
                    *Cocok untuk Jasa Pasang, Kaca Film, Variasi &amp; Lampu Audio.
                </p>
            </div>

            {{-- Sesi Sore --}}
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-slate-300 transition-all space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-500 text-xs">
                            <i class="ph-bold ph-moon-stars"></i>
                        </div>
                        <span class="text-xs font-montserrat font-bold text-slate-900">Sesi Sore</span>
                    </div>
                    <span class="text-[9px] font-mono text-slate-500">15:00 - 17:00</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            @click="bookingTime = '15:00'"
                            :class="bookingTime === '15:00'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '15:00'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>15:00 WIB</span>
                    </button>
                    <button type="button"
                            @click="bookingTime = '16:30'"
                            :class="bookingTime === '16:30'
                                ? 'bg-[#ff6b00] text-white font-bold border-[#ff6b00] shadow-md scale-[1.02]'
                                : 'bg-white text-slate-700 hover:text-slate-900 border-slate-200 hover:border-[#ff6b00]/50 shadow-sm'"
                            class="py-2.5 px-2 rounded-xl border text-xs font-montserrat font-bold transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <i x-show="bookingTime === '16:30'" class="ph-bold ph-check-circle text-xs"></i>
                        <span>16:30 WIB</span>
                    </button>
                </div>
                <p class="text-[10px] font-questrial text-slate-500 leading-tight">
                    *Pilihan serah terima kendaraan sore hari untuk pengerjaan bertahap.
                </p>
            </div>
        </div>
    </div>

    {{-- ── 3. KARTU KONFIRMASI JADWAL TERPILIH ── --}}
    <div class="p-4 sm:p-5 rounded-2xl bg-orange-50/50 border border-[#ff6b00]/30 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-xl shrink-0 shadow-sm">
                <i class="ph-bold ph-calendar-check"></i>
            </div>
            <div class="space-y-0.5">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00] block">
                    Jadwal Kedatangan Terpilih
                </span>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm sm:text-base font-audiowide font-bold text-slate-900" x-text="formatDateFull(bookingDate)"></span>
                    <span class="text-xs px-2.5 py-0.5 rounded-lg bg-white text-slate-800 border border-slate-200 font-mono font-bold shadow-sm"
                          x-text="'Pukul ' + bookingTime + ' WIB'"></span>
                </div>
                <p class="text-[11px] font-questrial text-slate-600">
                    Kendaraan diserahterimakan langsung di workshop Dantie Stiker sesuai jadwal ini.
                </p>
            </div>
        </div>

        {{-- Status Kuota & Navigasi Cepat --}}
        <div class="flex flex-col sm:flex-row md:flex-col items-start sm:items-center md:items-end justify-between gap-2 shrink-0">
            <span :class="selectedDateQuota.is_full ? 'border-rose-500/40 bg-rose-50 text-rose-600' : 'border-emerald-500/40 bg-emerald-50 text-emerald-700'"
                  class="px-3 py-1 rounded-full text-xs font-montserrat font-bold border flex items-center gap-1.5 shadow-sm"
                  x-text="selectedDateQuota.is_full ? '❌ Tanggal Ini Penuh (0/5)' : '✅ Kuota Tersedia (' + (selectedDateQuota.available ?? 5) + '/5 Slot)'">
            </span>
            <span class="text-[10px] font-questrial text-slate-400">
                Terverifikasi Otomatis
            </span>
        </div>
    </div>

    {{-- ── 4. AREA KALENDER LENGKAP (COLLAPSIBLE / EXPANDABLE) ── --}}
    @include('customer.booking.partials._kisi_kalender')

    {{-- ── 5. WIZARD NAVIGATION STEP 2 ── --}}
    <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
        <button type="button"
                @click="prevStep()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all cursor-pointer shadow-sm">
            <i class="ph-bold ph-arrow-left text-sm"></i>
            <span>Kembali ke Pilihan Layanan</span>
        </button>

        <button type="button"
                @click="nextStep()"
                :disabled="!bookingDate || selectedDateQuota.is_full"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-[#ff6b00] hover:bg-[#e05d00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_4px_20px_rgba(255,107,0,0.3)] disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
            <span>Lanjut ke Data Kendaraan</span>
            <i class="ph-bold ph-arrow-right text-sm"></i>
        </button>
    </div>

</section>

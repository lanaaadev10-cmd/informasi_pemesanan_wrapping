{{-- ──────────────────────────────────────────
     LANGKAH 2: PILIH TANGGAL & WAKTU (KALENDER)
────────────────────────────────────────── --}}
<section x-show="currentStep === 2" x-transition.opacity
         class="bg-[#141416]/95 border border-white/10 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-6">

    {{-- Header Langkah 2 --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-5">
        <div class="flex items-center gap-2.5">
            <span class="w-3 h-3 rounded-full bg-[#ff6b00] shadow-[0_0_10px_rgba(255,107,0,0.8)]"></span>
            <div>
                <h2 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                    Langkah 2: Pilih Tanggal &amp; Waktu
                </h2>
                <p class="text-xs font-questrial text-gray-400 mt-0.5">
                    Batas kapasitas workshop 5 booking kendaraan per hari untuk menjaga kualitas terbaik.
                </p>
            </div>
        </div>

        {{-- Tombol Buka/Tutup Kalender Lengkap --}}
        <button type="button"
                @click="showFullCalendar = !showFullCalendar"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all w-fit self-start sm:self-auto">
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
                <span class="text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                    Pilih Cepat Jadwal (14 Hari ke Depan)
                </span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-montserrat font-bold border border-emerald-500/20">
                    Live Quota Slot
                </span>
            </div>

            {{-- Panah Scroll Strip --}}
            <div class="flex items-center gap-1.5">
                <button type="button"
                        @click="scrollStrip('left')"
                        class="w-7 h-7 rounded-xl bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 flex items-center justify-center text-xs text-gray-300 transition-all active:scale-95">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <button type="button"
                        @click="scrollStrip('right')"
                        class="w-7 h-7 rounded-xl bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 flex items-center justify-center text-xs text-gray-300 transition-all active:scale-95">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </div>

        {{-- Strip Cards Container --}}
        <div id="dateStripContainer"
             class="flex gap-2.5 sm:gap-3 overflow-x-auto pb-2 pt-1 scroll-smooth scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent -mx-1 px-1 relative z-10">
            @foreach($stripDays as $item)
                <button type="button"
                        id="strip-card-{{ $item['date_str'] }}"
                        @click="selectDate('{{ $item['date_str'] }}')"
                        :disabled="isStripDisabled('{{ $item['date_str'] }}')"
                        :class="getStripCardClasses('{{ $item['date_str'] }}')"
                        class="flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl border text-center min-w-[82px] sm:min-w-[92px] shrink-0 min-h-[110px] sm:min-h-[118px] relative transition-all duration-200 group focus:outline-none">

                    {{-- Badge Hari Ini --}}
                    @if($item['is_today'])
                        <span class="absolute top-1.5 left-1/2 -translate-x-1/2 text-[7px] sm:text-[8px] font-montserrat font-black uppercase text-black bg-[#ff6b00] px-1.5 py-0.5 rounded-full shadow-[0_0_8px_rgba(255,107,0,0.6)] tracking-wider">
                            HARI INI
                        </span>
                    @endif

                    {{-- Hari & Tanggal --}}
                    <div class="space-y-0.5 {{ $item['is_today'] ? 'pt-2.5' : 'pt-1' }}">
                        <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 group-hover:text-gray-200 transition-colors block">
                            {{ $item['day_name'] }}
                        </span>
                        <span class="text-xl sm:text-2xl font-audiowide font-bold block transition-transform group-hover:scale-110">
                            {{ $item['day_num'] }}
                        </span>
                        <span class="text-[10px] font-questrial text-gray-400 block">
                            {{ $item['month'] }}
                        </span>
                    </div>

                    {{-- Indikator Kuota Slot --}}
                    <div class="pt-2 border-t border-white/10 w-full">
                        <span class="text-[9px] sm:text-[10px] font-montserrat block truncate"
                              :class="getStripSlotTextClass('{{ $item['date_str'] }}')"
                              x-text="getStripSlotLabel('{{ $item['date_str'] }}')">
                            5 Slot
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── 2. TANGGAL TERPILIH & JAM KEDATANGAN ── --}}
    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-[#ff6b00]/15 via-[#1a1a1f] to-transparent border border-[#ff6b00]/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00] block mb-1">
                Tanggal Booking Terpilih
            </span>
            <span class="text-base sm:text-lg font-audiowide font-bold text-white block" x-text="formatDateFull(bookingDate)"></span>
            <span class="text-xs font-questrial mt-0.5 block"
                  :class="selectedDateQuota.is_full ? 'text-rose-400 font-bold' : (selectedDateQuota.available <= 1 ? 'text-[#ff6b00] font-bold' : 'text-emerald-400')"
                  x-text="selectedDateQuota.is_full ? 'Tanggal ini PENUH (5/5). Silakan pilih tanggal lain.' : 'Tersedia ' + (selectedDateQuota.available ?? 5) + ' dari 5 slot booking workshop.'">
            </span>
        </div>

        {{-- Pilihan Jam Kedatangan Cepat --}}
        <div class="w-full md:w-auto">
            <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                Jam Kedatangan Workshop
            </label>
            <div class="grid grid-cols-3 sm:flex sm:flex-wrap gap-2">
                <template x-for="timeSlot in ['08:30', '10:00', '11:30', '13:30', '15:00', '16:30']" :key="timeSlot">
                    <button type="button"
                            @click="bookingTime = timeSlot"
                            :class="bookingTime === timeSlot ? 'bg-[#ff6b00] text-black font-montserrat font-black border-[#ff6b00] shadow-md scale-105' : 'bg-white/5 text-gray-300 hover:text-white border-white/10 hover:border-white/25 font-montserrat font-bold'"
                            class="px-2.5 sm:px-3 py-2 rounded-xl border text-xs transition-all text-center">
                        <span x-text="timeSlot + ' WIB'"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- ── 3. AREA KALENDER LENGKAP (COLLAPSIBLE / EXPANDABLE) ── --}}
    @include('customer.booking.partials._calendar-grid')

</section>

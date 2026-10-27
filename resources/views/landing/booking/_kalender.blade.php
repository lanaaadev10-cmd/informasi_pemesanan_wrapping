{{-- ============================================
     JADWAL BOOKING (KALENDER KUOTA) - Executive Luxury Studio Edition
     Split 2-Column Professional Layout dengan Alpine.js
     ============================================ --}}
<div id="booking-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 scroll-mt-20" x-data="bookingCalendar()" x-init="init()">
    
    {{-- Section Header --}}
    <div class="text-center mb-12 sm:mb-16 space-y-3" data-aos="fade-up">
        <span class="text-xs font-serif italic text-stone-500 tracking-wider block">
            sistem reservasi slot online
        </span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif text-stone-900 tracking-tight leading-tight font-normal">
            Pilih Jadwal &amp; Amankan Slot<br>
            <span class="italic text-stone-500 font-light">pengerjaan wrapping Anda.</span>
        </h2>
        <p class="text-stone-600 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed font-light">
            Demi menjaga standar ketelitian sempurna tanpa kompromi, kami membatasi pengerjaan maksimal <strong class="text-stone-900 font-semibold">5 kendaraan per hari</strong> di dalam workshop ber-AC dengan filtrasi debu aktif.
        </p>
    </div>

    {{-- 2-Column Professional Booking Interface: Reordered on Mobile (Calendar First, Ticket Second) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        
        {{-- LEFT COLUMN: Reservation Brief & Dynamic Booking Ticket (lg:col-span-5, Order 2 on Mobile, Order 1 on Desktop) --}}
        <div class="order-2 lg:order-1 lg:col-span-5 space-y-6" data-aos="fade-right">
            
            {{-- Workshop Standards & Guarantees Box --}}
            <div class="bg-[#f8f7f4] border border-[#e8e6dc] rounded-[28px] p-5 sm:p-7 space-y-4 sm:space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-[#e2e0d5]">
                    <div class="w-10 h-10 rounded-2xl bg-[#ea580c]/10 text-[#ea580c] flex items-center justify-center text-xl shrink-0">
                        <i class="ph-bold ph-shield-star"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-stone-900 leading-tight">Standar Workshop Wapping</h4>
                        <p class="text-xs text-stone-500 font-light">Transparan, tepat waktu &amp; bergaransi</p>
                    </div>
                </div>

                {{-- Key Selling Points List --}}
                <div class="space-y-3 text-xs text-stone-700 font-light">
                    <div class="flex items-start gap-2.5">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-base shrink-0 mt-0.5"></i>
                        <span><strong>Kapasitas Terbatas:</strong> Hanya 5 bay steril aktif per hari untuk memastikan perhatian penuh pada setiap sudut mobil.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-base shrink-0 mt-0.5"></i>
                        <span><strong>Metode Knifeless Tape:</strong> Bodi dan cat asli orisinil terjamin 100% aman tanpa goresan mata pisau.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <i class="ph-bold ph-check-circle text-emerald-600 text-base shrink-0 mt-0.5"></i>
                        <span><strong>Garansi Pengerjaan:</strong> Sertifikat garansi resmi tertulis 1 hingga 3 tahun untuk peeling dan gelembung udara.</span>
                    </div>
                </div>
            </div>

            {{-- DYNAMIC TICKET CARD: Appears / Updates on Date Selection --}}
            <div class="bg-white border-2 border-stone-200 rounded-[28px] p-5 sm:p-7 shadow-lg space-y-4 sm:space-y-5 transition-all duration-300"
                 :class="selected ? 'border-[#ea580c]/40 ring-4 ring-[#ea580c]/5' : ''">
                
                <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                    <span class="text-[11px] font-mono uppercase tracking-wider font-bold text-stone-400">
                        Tiket Reservasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                          :class="selected ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-500'">
                        <span class="w-1.5 h-1.5 rounded-full" :class="selected ? 'bg-emerald-500 animate-pulse' : 'bg-stone-400'"></span>
                        <span x-text="selected ? 'Slot Dipilih' : 'Menunggu Pilihan'"></span>
                    </span>
                </div>

                <div class="space-y-2">
                    <p class="text-xs text-stone-500 font-light">Tanggal Pengerjaan:</p>
                    <h3 class="text-lg sm:text-xl font-bold text-stone-900 font-serif leading-snug"
                        x-text="selected?.label || 'Silakan pilih tanggal di kalender'"></h3>
                    
                    <div class="pt-1" x-show="selected">
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200"
                              x-text="'Tersedia: ' + selected?.available + ' dari ' + selected?.max + ' slot workshop'"></span>
                    </div>
                </div>

                {{-- CTA Button: Selalu Bisa Diklik dan Membuka Pop-up Elegan Jika Belum Login --}}
                <div class="pt-2">
                    <button
                        type="button"
                        @click="goBooking()"
                        class="w-full py-4 rounded-full font-bold text-xs uppercase tracking-wider bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white hover:brightness-105 transition-all shadow-lg shadow-orange-500/25 active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Lanjutkan Booking Slot</span>
                        <i class="ph-bold ph-arrow-up-right text-sm"></i>
                    </button>
                    
                    {{-- Alert jika sudah login tapi belum pilih tanggal --}}
                    <div x-show="showDateAlert" x-transition.opacity class="mt-2.5 text-center text-xs font-semibold text-amber-700 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                        <i class="ph-bold ph-warning-circle text-sm mr-1 inline-block align-middle"></i>
                        <span>Silakan klik salah satu tanggal pada kalender terlebih dahulu.</span>
                    </div>

                    <p class="text-[11px] text-stone-400 text-center mt-2 font-light">
                        Tanpa biaya pendaftaran. Estimasi waktu &amp; bahan dikonfirmasi saat konsultasi.
                    </p>
                </div>

            </div>

        </div>

        {{-- RIGHT COLUMN: The Precision Calendar (lg:col-span-7, Order 1 on Mobile, Order 2 on Desktop) --}}
        <div class="order-1 lg:order-2 lg:col-span-7" data-aos="fade-left">
            <div class="bg-white border border-stone-200 rounded-[28px] sm:rounded-[32px] p-4 sm:p-8 shadow-xl space-y-5 sm:space-y-6">
                
                {{-- Calendar Top Bar: Month & Year + Clean Pill Navigation --}}
                <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4 pb-4 border-b border-stone-100">
                    <div class="space-y-0.5">
                        <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400 font-semibold block">Bulan Aktif</span>
                        <h3 class="text-xl sm:text-2xl font-serif font-bold text-stone-900 capitalize" x-text="monthLabel"></h3>
                    </div>

                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <button @click="prevMonth()" 
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 flex items-center justify-center transition-all active:scale-95"
                                title="Bulan Sebelumnya">
                            <i class="ph-bold ph-caret-left text-sm sm:text-base"></i>
                        </button>
                        <button @click="thisMonth()" 
                                class="px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-full bg-stone-100 hover:bg-orange-50 hover:text-[#ea580c] text-xs font-semibold text-stone-700 transition-colors">
                            Bulan Ini
                        </button>
                        <button @click="nextMonth()" 
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 flex items-center justify-center transition-all active:scale-95"
                                title="Bulan Berikutnya">
                            <i class="ph-bold ph-caret-right text-sm sm:text-base"></i>
                        </button>
                    </div>
                </div>

                {{-- Status Legend (Clean & Compact) --}}
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[11px] sm:text-xs font-medium text-stone-600 bg-stone-50 p-2.5 sm:p-3 rounded-2xl border border-stone-100">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#ea580c]"></span> Sisa 1-2 Slot</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Penuh</span>
                    <span class="flex items-center gap-1.5 text-stone-400"><span class="w-2.5 h-2.5 rounded-full bg-stone-200"></span> Lewat</span>
                </div>

                {{-- Day Names Header --}}
                <div class="grid grid-cols-7 gap-1 sm:gap-2">
                    <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="d">
                        <div class="text-center text-[10px] sm:text-[11px] font-mono font-bold uppercase tracking-wider text-stone-400 py-1" x-text="d"></div>
                    </template>
                </div>

                {{-- Day Cells Grid --}}
                <div class="grid grid-cols-7 gap-1 sm:gap-2.5">
                    <template x-for="cell in cells" :key="cell.key">
                        <template x-if="cell.empty">
                            <div class="h-11 sm:h-16 rounded-xl sm:rounded-2xl"></div>
                        </template>
                        <template x-if="!cell.empty">
                            <button
                                @click="selectDate(cell)"
                                :disabled="cell.disabled"
                                :class="cellClasses(cell)"
                                class="h-11 sm:h-16 rounded-xl sm:rounded-2xl border transition-all duration-200 relative flex flex-col items-center justify-center cursor-pointer group"
                            >
                                <span class="text-xs sm:text-base font-bold" x-text="cell.day"></span>
                                <span class="w-1 sm:w-1.5 h-1 sm:h-1.5 rounded-full mt-0.5 sm:mt-1 transition-transform group-hover:scale-125"
                                      :class="cell.status === 'full' ? 'bg-red-500' : (cell.status === 'few' ? 'bg-[#ea580c]' : (cell.status === 'available' ? 'bg-emerald-500' : 'bg-transparent'))">
                                </span>
                            </button>
                        </template>
                    </template>
                </div>

            </div>
        </div>

    </div>

    {{-- MODAL POP-UP: Clean & Elegan untuk Arahan Login Sebelum Melakukan Booking --}}
    @include('landing.booking.partials._kalender_modal_login')

</div>

{{-- SKRIP LOGIKA KALENDER ALPINE.JS --}}
@include('landing.booking.partials._kalender_script')
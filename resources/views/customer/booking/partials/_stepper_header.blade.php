{{-- ============================================
    HEADER & STEPPER RESERVASI BOOKING
============================================ --}}
{{-- ════ 1. HEADER HALAMAN ════ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5 relative z-10">
    <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#ff6b00]/15 border border-[#ff6b00]/30 text-[#ff6b00] text-[11px] font-montserrat font-bold uppercase tracking-wider mb-2">
            <span>CHECKOUT RESERVASI</span>
        </div>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-audiowide font-bold text-slate-900 tracking-wide">
            Checkout &amp; Booking Jadwal
        </h1>
        <p class="text-xs sm:text-sm font-questrial text-slate-500 mt-1 max-w-xl">
            Konfirmasi paket layanan, tentukan jadwal kedatangan, dan selesaikan reservasi kendaraan Anda
        </p>
    </div>

    <div>
        <a href="{{ route('booking.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all">
            <i class="ph-bold ph-calendar-check text-base text-[#ff6b00]"></i>
            <span>Riwayat Booking</span>
        </a>
    </div>
</div>

{{-- Banner Konsultasi WA Khusus Jasa & Custom --}}
<div class="relative z-10 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200/90 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div class="flex items-start gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl shrink-0 shadow-sm shadow-emerald-500/20">
            <i class="ph-bold ph-whatsapp-logo"></i>
        </div>
        <div>
            <h4 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 flex items-center gap-2">
                <span>Ingin Jasa Pasang (Bahan Sendiri) atau Custom Wrapping?</span>
            </h4>
            <p class="text-xs font-questrial text-slate-600 mt-0.5 leading-relaxed max-w-2xl">
                Sesuai alur baru, layanan <strong>Jasa</strong> dan <strong>Custom</strong> dikonsultasikan langsung via WhatsApp resmi. Admin kami akan membuatkan pesanan langsung ke akun Anda setelah kesepakatan harga &amp; jadwal tercapai.
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2 w-full md:w-auto shrink-0 flex-wrap">
        <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-montserrat font-bold text-xs shadow-sm transition-all text-center">
            <i class="ph-bold ph-scissors"></i>
            <span>WA Jasa Pasang</span>
        </a>
        <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-montserrat font-bold text-xs shadow-sm transition-all text-center">
            <i class="ph-bold ph-paint-brush"></i>
            <span>WA Custom</span>
        </a>
    </div>
</div>

{{-- ════ 2. E-COMMERCE CHECKOUT INTERACTIVE STEPPER ════ --}}
<div class="relative z-10 bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 shadow-sm space-y-3">
    {{-- Mobile Progress Tracker --}}
    <div class="sm:hidden space-y-2">
        <div class="flex items-center justify-between text-xs font-montserrat font-bold text-slate-800">
            <span class="flex items-center gap-1.5 text-[#ff6b00]">
                <span class="w-2 h-2 rounded-full bg-[#ff6b00] animate-pulse"></span>
                <span>Langkah <span x-text="currentStep"></span> dari 4</span>
            </span>
            <span class="text-slate-700 font-bold text-xs"
                  x-text="['', 'Pilih Layanan', 'Jadwal & Shift', 'Data Kendaraan', 'Metode Pembayaran'][currentStep]"></span>
        </div>
        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/60 shadow-inner">
            <div class="h-full bg-gradient-to-r from-[#ff6b00] to-orange-400 rounded-full transition-all duration-300 shadow-sm"
                 :style="'width: ' + (currentStep * 25) + '%'"></div>
        </div>
    </div>

    {{-- Desktop / Tablet Stepper Buttons --}}
    <div class="hidden sm:flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
        {{-- Step 1 --}}
        <button type="button" @click="goToStep(1)"
                :class="currentStep === 1
                    ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                    : (currentStep > 1 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
            <span :class="currentStep === 1 ? 'bg-[#ff6b00] text-white' : (currentStep > 1 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                  class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                <template x-if="currentStep > 1">
                    <i class="ph-bold ph-check text-xs"></i>
                </template>
                <template x-if="currentStep <= 1">
                    <span>1</span>
                </template>
            </span>
            <span>Layanan Dipesan</span>
        </button>

        <i :class="currentStep > 1 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

        {{-- Step 2 --}}
        <button type="button" @click="goToStep(2)"
                :class="currentStep === 2
                    ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                    : (currentStep > 2 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
            <span :class="currentStep === 2 ? 'bg-[#ff6b00] text-white' : (currentStep > 2 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                  class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                <template x-if="currentStep > 2">
                    <i class="ph-bold ph-check text-xs"></i>
                </template>
                <template x-if="currentStep <= 2">
                    <span>2</span>
                </template>
            </span>
            <span>Jadwal &amp; Shift</span>
        </button>

        <i :class="currentStep > 2 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

        {{-- Step 3 --}}
        <button type="button" @click="goToStep(3)"
                :class="currentStep === 3
                    ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                    : (currentStep > 3 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
            <span :class="currentStep === 3 ? 'bg-[#ff6b00] text-white' : (currentStep > 3 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                  class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                <template x-if="currentStep > 3">
                    <i class="ph-bold ph-check text-xs"></i>
                </template>
                <template x-if="currentStep <= 3">
                    <span>3</span>
                </template>
            </span>
            <span>Data Kendaraan</span>
        </button>

        <i :class="currentStep > 3 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

        {{-- Step 4 --}}
        <button type="button" @click="goToStep(4)"
                :class="currentStep === 4
                    ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                    : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
            <span :class="currentStep === 4 ? 'bg-[#ff6b00] text-white' : 'bg-slate-200 text-slate-600'"
                  class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                <span>4</span>
            </span>
            <span>Metode Pembayaran</span>
        </button>
    </div>
</div>

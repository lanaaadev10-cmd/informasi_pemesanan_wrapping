{{-- ════ KOLOM KANAN (SIDEBAR RINGKASAN BOOKING PERSIS GAMBAR) ════ --}}
<aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-5">
    <div class="bg-[#141416]/95 border border-white/10 rounded-3xl p-6 shadow-2xl space-y-5 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full bg-[#ff6b00]/10 blur-3xl pointer-events-none"></div>

        {{-- Title Ringkasan Booking --}}
        <h3 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide border-b border-white/10 pb-4">
            Ringkasan Booking
        </h3>

        {{-- Nama & Harga Layanan Terpilih --}}
        <div>
            <div class="flex items-center gap-2 mb-1" x-show="selectedLayanan && selectedLayanan.tipe_paket">
                <span class="text-[9px] font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider bg-[#ff6b00]/15 px-2 py-0.5 rounded-md border border-[#ff6b00]/30"
                      x-text="selectedLayanan ? selectedLayanan.tipe_paket : ''"></span>
            </div>
            <p class="text-base sm:text-lg font-audiowide font-bold text-white leading-tight"
               x-text="selectedLayanan ? selectedLayanan.name : 'Belum dipilih'"></p>
            <p class="text-sm sm:text-base font-audiowide font-bold text-[#ff6b00] mt-1"
               x-text="selectedLayanan ? formatRupiah(selectedLayanan.price) : 'Rp 0'"></p>
        </div>

        {{-- Kotak Input / Tampilan Tanggal --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-400 uppercase tracking-wider">Tanggal</label>
            <button type="button" @click="goToStep(2)"
                    class="w-full px-3.5 py-3 rounded-xl bg-[#0f0f13] border border-white/10 text-xs sm:text-sm text-left flex items-center justify-between hover:border-white/25 transition-all text-gray-200">
                <span x-text="hasChosenDate ? formatDateShort(bookingDate) : 'Belum dipilih'"></span>
                <i class="ph-bold ph-calendar text-[#ff6b00]"></i>
            </button>
        </div>

        {{-- Kotak Dropdown Time Slot --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-400 uppercase tracking-wider">Time Slot</label>
            <div class="relative">
                <select x-model="bookingTime"
                        class="w-full px-3.5 py-3 rounded-xl bg-[#0f0f13] border border-white/10 text-xs sm:text-sm text-gray-200 font-questrial appearance-none cursor-pointer focus:border-[#ff6b00] outline-none pr-8">
                    <option value="08:30">08:30 WIB</option>
                    <option value="10:00">10:00 WIB</option>
                    <option value="11:30">11:30 WIB</option>
                    <option value="13:30">13:30 WIB</option>
                    <option value="15:00">15:00 WIB</option>
                    <option value="16:30">16:30 WIB</option>
                </select>
                <i class="ph-bold ph-caret-down text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
            </div>
        </div>

        {{-- Baris Quota dengan Pill Hijau Sesuai Gambar --}}
        <div class="flex items-center justify-between text-xs pt-1">
            <span class="text-gray-400 font-questrial font-medium">Quota</span>
            <span :class="quotaBadgeClass"
                  class="px-3 py-1 rounded-full text-[11px] font-montserrat font-bold border transition-all"
                  x-text="quotaBadgeText"></span>
        </div>

        {{-- Total Pembayaran (DP / Total) --}}
        <div class="pt-4 border-t border-white/10 space-y-1">
            <span class="text-xs font-questrial text-gray-400 block">Total Pembayaran</span>
            <div class="flex items-baseline gap-2">
                <span class="text-sm font-montserrat font-bold text-white" x-text="paymentType === 'dp' ? 'DP' : 'Total'"></span>
                <span class="text-xl sm:text-2xl font-audiowide font-bold text-[#ff6b00]"
                      x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))"></span>
            </div>
        </div>

        {{-- Tombol Utama Oranye Gradien Sesuai Desain (Lanjut ke Pilih Tanggal ->) --}}
        <div class="pt-2 space-y-2">
            <button type="button"
                    @click="handlePrimaryButton()"
                    :disabled="isSubmitting || (currentStep === 2 && selectedDateQuota.is_full)"
                    class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#ff6b00] to-[#ff8c00] hover:from-[#ea580c] hover:to-[#ff6b00] text-white font-montserrat font-bold text-xs sm:text-sm uppercase tracking-wider shadow-[0_8px_25px_rgba(255,107,0,0.35)] flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-40">
                <svg x-show="isSubmitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-text="primaryButtonLabel"></span>
                <i x-show="!isSubmitting" :class="primaryButtonIcon" class="text-sm"></i>
            </button>

            {{-- Tombol Kembali --}}
            <button type="button"
                    x-show="currentStep > 1"
                    @click="prevStep()"
                    class="w-full py-2.5 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white font-montserrat font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-1.5">
                <i class="ph-bold ph-arrow-left text-xs"></i>
                <span>Kembali ke Langkah Sebelumnya</span>
            </button>
        </div>

        <p class="text-[11px] font-questrial text-gray-500 text-center flex items-center justify-center gap-1.5 pt-1">
            <i class="ph-bold ph-shield-check text-emerald-400 text-sm"></i>
            Jadwal terkonfirmasi otomatis via sistem
        </p>
    </div>
</aside>

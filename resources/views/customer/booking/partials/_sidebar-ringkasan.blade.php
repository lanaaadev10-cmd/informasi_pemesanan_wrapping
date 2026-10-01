{{-- ════ KOLOM KANAN: RINCIAN RESERVASI & TAGIHAN TRANSPARAN (EXECUTIVE STANDARD) ════ --}}
<aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-5">
    <div class="bg-[#141416]/95 border border-white/10 rounded-3xl p-6 shadow-2xl space-y-5 relative overflow-hidden">
        {{-- Ambient decorative glow --}}
        <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full bg-[#ff6b00]/10 blur-3xl pointer-events-none"></div>

        {{-- 1. Header Rincian Reservasi --}}
        <div class="border-b border-white/10 pb-4 space-y-1.5">
            <div class="flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                    Rincian Reservasi
                </h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#ff6b00]/10 border border-[#ff6b00]/25 text-[#ff6b00] text-[9px] font-mono font-bold uppercase tracking-wider">
                    Sistem Aktif
                </span>
            </div>
            <p class="text-[11px] font-questrial text-gray-400">
                Pantauan paket pengerjaan &amp; jadwal kedatangan di workshop
            </p>
        </div>

        {{-- 2. Paket Layanan Terpilih --}}
        <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-wider">
                    Paket Pengerjaan
                </span>
                <span class="text-[9px] font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider bg-[#ff6b00]/15 px-2 py-0.5 rounded-md border border-[#ff6b00]/30"
                      x-show="selectedLayanan && selectedLayanan.tipe_paket"
                      x-text="selectedLayanan ? selectedLayanan.tipe_paket : ''"></span>
            </div>

            <div>
                <p class="text-base font-audiowide font-bold text-white leading-tight"
                   x-text="selectedLayanan ? selectedLayanan.name : 'Belum Memilih Layanan'"></p>
                <div class="flex items-center justify-between mt-1.5">
                    <span class="text-xs font-questrial text-gray-400">Tarif Dasar Paket:</span>
                    <span class="text-sm font-audiowide font-bold text-[#ff6b00]"
                          x-text="selectedLayanan ? formatRupiah(selectedLayanan.price) : 'Rp 0'"></span>
                </div>
            </div>

            <div class="flex items-center gap-1.5 pt-1 text-[11px] font-questrial text-gray-400 border-t border-white/5" x-show="selectedLayanan">
                <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                <span>Estimasi Durasi: <strong class="text-white font-medium" x-text="selectedLayanan?.estimasi || '2-3 Hari Kerja'"></strong></span>
            </div>
        </div>

        {{-- 3. Jadwal Tanggal Kedatangan --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider flex items-center justify-between">
                <span>Tanggal Pengerjaan</span>
                <span class="text-[10px] text-[#ff6b00] font-normal cursor-pointer hover:underline" @click="goToStep(2)" x-show="currentStep !== 2">
                    Ubah Tanggal
                </span>
            </label>
            <button type="button" @click="goToStep(2)"
                    class="w-full px-3.5 py-3 rounded-xl bg-[#0f0f13] border border-white/10 hover:border-[#ff6b00]/50 text-xs sm:text-sm text-left flex items-center justify-between transition-all text-gray-200 group">
                <div class="flex items-center gap-2.5 truncate">
                    <i class="ph-bold ph-calendar text-[#ff6b00] text-base shrink-0 group-hover:scale-110 transition-transform"></i>
                    <span class="truncate" x-text="hasChosenDate ? formatDateFull(bookingDate) : 'Pilih Tanggal di Kalender'"></span>
                </div>
                <i class="ph-bold ph-caret-right text-gray-500 text-xs shrink-0 group-hover:text-white transition-colors"></i>
            </button>
        </div>

        {{-- 4. Jam Serah Terima Kendaraan --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Jam Kedatangan di Workshop
            </label>
            <div class="relative">
                <select x-model="bookingTime"
                        class="w-full px-3.5 py-3 rounded-xl bg-[#0f0f13] border border-white/10 text-xs sm:text-sm text-gray-200 font-questrial appearance-none cursor-pointer focus:border-[#ff6b00] outline-none pr-8">
                    <option value="08:30">08:30 WIB (Pagi - Sesi 1)</option>
                    <option value="10:00">10:00 WIB (Pagi - Sesi 2)</option>
                    <option value="11:30">11:30 WIB (Siang - Sesi 3)</option>
                    <option value="13:30">13:30 WIB (Siang - Sesi 4)</option>
                    <option value="15:00">15:00 WIB (Sore - Sesi 5)</option>
                    <option value="16:30">16:30 WIB (Sore - Sesi 6)</option>
                </select>
                <i class="ph-bold ph-caret-down text-gray-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
            </div>
            <p class="text-[10px] font-questrial text-gray-500">
                Pilih perkiraan waktu Anda tiba untuk serah terima kendaraan.
            </p>
        </div>

        {{-- 5. Kapasitas Slot Harian Bengkel --}}
        <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between text-xs">
            <span class="text-gray-300 font-questrial flex items-center gap-1.5">
                <i class="ph-bold ph-garage text-gray-400"></i>
                Kapasitas Bengkel:
            </span>
            <span :class="quotaBadgeClass"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-montserrat font-bold border transition-all"
                  x-text="quotaBadgeText"></span>
        </div>

        {{-- 6. Transparansi Tagihan & Skema Pembayaran --}}
        <div class="pt-3 border-t border-white/10 space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-400 font-questrial">Skema Pembayaran:</span>
                <span class="font-montserrat font-bold text-white text-[11px]"
                      x-text="paymentType === 'dp' ? 'Uang Muka (DP 50%)' : 'Pelunasan Penuh (100%)'"></span>
            </div>

            {{-- Highlight Kotak Nominal Dibayar Sekarang --}}
            <div class="p-4 rounded-2xl bg-[#18181c] border border-[#ff6b00]/30 space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-gray-300">
                        Tagihan Saat Ini:
                    </span>
                    <span class="text-[10px] font-mono font-bold text-[#ff6b00]"
                          x-text="paymentType === 'dp' ? 'UANG MUKA (DP)' : 'LUNAS'"></span>
                </div>
                <div class="text-2xl font-audiowide font-bold text-[#ff6b00] tracking-tight"
                     x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))"></div>
            </div>

            {{-- Info Transparansi Sisa Pelunasan di Workshop (Khusus DP) --}}
            <div x-show="paymentType === 'dp'" class="p-3 rounded-xl bg-[#0f0f13] border border-white/5 space-y-1">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400 font-questrial">Sisa Pelunasan di Bengkel:</span>
                    <span class="font-audiowide font-bold text-white"
                          x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span>
                </div>
                <p class="text-[10px] text-gray-500 font-questrial italic leading-tight">
                    *Dilunasi saat serah terima kendaraan selesai dikerjakan di workshop.
                </p>
            </div>
        </div>

        {{-- 7. Tombol Aksi Utama --}}
        <div class="pt-2 space-y-2">
            <button type="button"
                    @click="handlePrimaryButton()"
                    :disabled="isSubmitting || (currentStep === 2 && selectedDateQuota.is_full)"
                    class="w-full py-4 px-6 rounded-2xl bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-[0_8px_25px_rgba(255,107,0,0.35)] flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-40">
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

        {{-- 8. Trust Badges & Jaminan Kualitas --}}
        <div class="pt-2 border-t border-white/5 space-y-1.5 text-center">
            <p class="text-[11px] font-questrial text-gray-400 flex items-center justify-center gap-1.5">
                <i class="ph-bold ph-shield-check text-[#ff6b00] text-sm"></i>
                <span>Garansi Resmi Pemasangan &amp; Material Orisinil</span>
            </p>
            <p class="text-[10px] font-questrial text-gray-500">
                Jadwal &amp; kuota terkonfirmasi otomatis secara langsung
            </p>
        </div>
    </div>
</aside>

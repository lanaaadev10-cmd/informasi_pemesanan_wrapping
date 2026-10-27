{{-- ════ KOLOM KANAN: RINCIAN RESERVASI & TAGIHAN TRANSPARAN (EXECUTIVE STANDARD) ════ --}}
<aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-5">
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-5 relative overflow-hidden">
        {{-- Ambient decorative glow --}}
        <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full bg-[#ff6b00]/5 blur-3xl pointer-events-none"></div>

        {{-- 1. Header Rincian Reservasi --}}
        <div class="border-b border-slate-100 pb-4 space-y-1.5">
            <div class="flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Rincian Reservasi
                </h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#ff6b00]/10 border border-[#ff6b00]/25 text-[#ff6b00] text-[9px] font-mono font-bold uppercase tracking-wider">
                    Sistem Aktif
                </span>
            </div>
            <p class="text-[11px] font-questrial text-slate-500">
                Pantauan paket pengerjaan &amp; kalkulasi tagihan transparan
            </p>
        </div>

        {{-- 2. Paket Layanan Terpilih --}}
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-wider">
                    Paket Pengerjaan
                </span>
                <div class="flex items-center gap-1" x-show="selectedLayanan">
                    <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border"
                          :class="selectedLayanan?.scope_class"
                          x-text="selectedLayanan?.scope_badge || selectedLayanan?.tipe_paket"></span>
                    <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider bg-white text-slate-800 px-2 py-0.5 rounded-md border border-slate-200 shadow-sm"
                          x-text="selectedLayanan?.kategori_label"></span>
                </div>
            </div>

            <div>
                <p class="text-base font-audiowide font-bold text-slate-900 leading-tight"
                   x-text="selectedLayanan ? selectedLayanan.name : 'Belum Memilih Layanan'"></p>
                <div class="flex items-center justify-between mt-1.5">
                    <span class="text-xs font-questrial text-slate-500"
                          x-text="selectedLayanan?.is_jasa_pasang ? 'Ongkos Jasa Pasang:' : (selectedLayanan?.jenis_layanan === 'custom' ? 'Estimasi Dasar:' : 'Tarif Paket:')"></span>
                    <span class="text-sm font-audiowide font-bold text-[#ff6b00]"
                          x-text="formatRupiah(basePrice)"></span>
                </div>
            </div>

            <div class="flex items-center gap-1.5 pt-1 text-[11px] font-questrial text-slate-500 border-t border-slate-200/60" x-show="selectedLayanan">
                <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                <span>Estimasi Durasi: <strong class="text-slate-800 font-semibold" x-text="selectedLayanan?.estimasi || '2-3 Hari Kerja'"></strong></span>
            </div>
        </div>

        {{-- 3. Identitas Kendaraan Terpilih (Kondisional jika diisi di Step 3) --}}
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5"
             x-show="vehicleName || selectedVehicleSize" x-transition.opacity>
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-wider">
                    Kendaraan
                </span>
                <template x-if="selectedVehicleSize">
                    <span class="text-[9px] font-mono font-bold px-2 py-0.5 rounded border uppercase"
                          :class="sizeBadge(selectedVehicleSize)"
                          x-text="selectedVehicleSize.toUpperCase()"></span>
                </template>
            </div>
            <p class="text-xs font-montserrat font-bold text-slate-900 truncate" x-text="vehicleName || 'Model Kendaraan'"></p>
            <div class="flex items-center justify-between text-[11px] font-questrial text-slate-500 pt-0.5">
                <span x-text="vehicleLicense ? 'Nopol: ' + vehicleLicense : ''"></span>
                <span x-text="vehicleColor ? 'Warna: ' + vehicleColor : ''"></span>
            </div>
        </div>

        {{-- 4. Rincian Layanan Tambahan (Add-ons) Terpilih --}}
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2"
             x-show="selectedAddonsList && selectedAddonsList.length > 0" x-transition.opacity>
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-wider">
                    Layanan Tambahan (Add-ons)
                </span>
                <span class="text-[10px] font-mono font-bold text-[#ff6b00]" x-text="selectedAddonsList.length + ' Item'"></span>
            </div>
            <div class="space-y-1.5 max-h-36 overflow-y-auto no-scrollbar">
                <template x-for="item in selectedAddonsList" :key="'sidebar-addon-' + item.id">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-700 font-questrial truncate pr-2" x-text="item.name"></span>
                        <span class="text-[#ff6b00] font-mono shrink-0" x-text="'+ ' + formatRupiah(item.price)"></span>
                    </div>
                </template>
            </div>
            <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-xs font-montserrat font-bold">
                <span class="text-slate-500">Total Add-ons:</span>
                <span class="text-slate-900" x-text="'+ ' + formatRupiah(addonsTotal)"></span>
            </div>
        </div>

        {{-- 5. Ringkasan Jadwal Kedatangan (Order Summary Real-Time) --}}
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-wider">
                    Jadwal Kedatangan
                </span>
                <button type="button" @click="goToStep(2)"
                        class="text-[10px] text-[#ff6b00] font-montserrat font-bold hover:underline flex items-center gap-1 cursor-pointer">
                    <span>Ubah</span>
                    <i class="ph-bold ph-pencil-simple text-xs"></i>
                </button>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-calendar-check text-[#ff6b00] text-sm shrink-0"></i>
                    <span class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 truncate"
                          x-text="formatDateFull(bookingDate)"></span>
                </div>

                <div class="flex items-center justify-between text-xs font-questrial text-slate-600 pt-1 border-t border-slate-200/60">
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-clock text-[#ff6b00]"></i>
                        <span>Pukul <strong class="text-slate-800 font-semibold" x-text="bookingTime + ' WIB'"></strong></span>
                    </span>
                    <span :class="quotaBadgeClass"
                          class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold border transition-all"
                          x-text="quotaBadgeText"></span>
                </div>
            </div>
        </div>

        {{-- 8. Transparansi Tagihan & Skema Pembayaran --}}
        <div class="pt-3 border-t border-slate-100 space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500 font-questrial">Total Keseluruhan:</span>
                <span class="font-audiowide font-bold text-slate-900 text-sm"
                      x-text="formatRupiah(grandTotal)"></span>
            </div>

            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500 font-questrial">Skema Pembayaran:</span>
                <span class="font-montserrat font-bold text-slate-800 text-[11px]"
                      x-text="paymentType === 'dp' ? 'Uang Muka (DP 50%)' : 'Pelunasan Penuh (100%)'"></span>
            </div>

            {{-- Highlight Kotak Nominal Dibayar Sekarang --}}
            <div class="p-4 rounded-2xl bg-orange-50/70 border border-[#ff6b00]/30 space-y-1 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-montserrat font-bold uppercase tracking-wider text-slate-700">
                        Tagihan Saat Ini:
                    </span>
                    <span class="text-[10px] font-mono font-bold text-[#ff6b00]"
                          x-text="paymentType === 'dp' ? 'UANG MUKA (DP)' : 'LUNAS'"></span>
                </div>
                <div class="text-2xl font-audiowide font-bold text-[#ff6b00] tracking-tight"
                     x-text="formatRupiah(currentPaymentAmount)"></div>
            </div>

            {{-- Info Transparansi Sisa Pelunasan di Workshop (Khusus DP) --}}
            <div x-show="paymentType === 'dp'" class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1 shadow-sm">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-questrial">Sisa Pelunasan di Bengkel:</span>
                    <span class="font-audiowide font-bold text-slate-900"
                          x-text="formatRupiah(remainingBalance)"></span>
                </div>
                <p class="text-[10px] text-slate-400 font-questrial italic leading-tight">
                    *Dilunasi saat serah terima kendaraan selesai dikerjakan di workshop.
                </p>
            </div>
        </div>

        {{-- 9. Tombol Aksi Utama Checkout --}}
        <div class="pt-2">
            <button type="button"
                    @click="handlePrimaryButton()"
                    :disabled="isSubmitting || (currentStep === 2 && selectedDateQuota.is_full)"
                    class="w-full py-4 px-6 rounded-2xl bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-[0_8px_25px_rgba(255,107,0,0.3)] flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-40 cursor-pointer">
                <svg x-show="isSubmitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-text="primaryButtonLabel"></span>
                <i x-show="!isSubmitting" :class="primaryButtonIcon" class="text-sm"></i>
            </button>
        </div>

        {{-- 10. Trust Badges & Jaminan Kualitas --}}
        <div class="pt-2 border-t border-slate-100 space-y-1.5 text-center">
            <p class="text-[11px] font-questrial text-slate-600 flex items-center justify-center gap-1.5">
                <i class="ph-bold ph-shield-check text-[#ff6b00] text-sm"></i>
                <span>Garansi Resmi Pemasangan &amp; Material Orisinil</span>
            </p>
            <p class="text-[10px] font-questrial text-slate-400">
                Jadwal &amp; kuota terkonfirmasi otomatis secara langsung
            </p>
        </div>
    </div>
</aside>

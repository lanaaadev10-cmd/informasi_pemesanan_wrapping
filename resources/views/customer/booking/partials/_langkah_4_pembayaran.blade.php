{{-- ──────────────────────────────────────────
     LANGKAH 4: SKEMA & METODE PEMBAYARAN
────────────────────────────────────────── --}}
<section id="section-pembayaran"
         class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-sm space-y-6">

    {{-- Header Seksi 4 --}}
    <div class="border-b border-slate-100 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-sm font-montserrat font-bold shadow-sm shrink-0">
                4
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Metode Pembayaran &amp; Bukti Transfer
                </h2>
                <p class="text-xs font-questrial text-slate-500 mt-0.5">
                    Kunci slot pengerjaan workshop dengan DP 50% atau langsung pelunasan 100%
                </p>
            </div>
        </div>
    </div>

    {{-- Skema DP vs Lunas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Opsi A: DP 50% --}}
        <label class="cursor-pointer block h-full">
            <input type="radio" name="payment_type_radio" value="dp" class="sr-only"
                   @change="paymentType = 'dp'" :checked="paymentType === 'dp'">
            <div class="p-4 sm:p-5 rounded-2xl border transition-all h-full flex flex-col justify-between"
                 :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-orange-50/70 shadow-sm ring-1 ring-[#ff6b00]/40' : 'border-slate-200 bg-white hover:border-slate-300 shadow-sm'">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-montserrat font-bold uppercase text-[#ff6b00] bg-[#ff6b00]/15 px-2.5 py-0.5 rounded-md border border-[#ff6b00]/30">Opsi Terpopuler</span>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                             :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-slate-300'">
                            <div x-show="paymentType === 'dp'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                    <h4 class="text-sm font-audiowide font-bold text-slate-900">DP 50% di Awal</h4>
                    <p class="text-[11px] font-questrial text-slate-500 mt-1 leading-relaxed">
                        Kunci antrean dengan DP 50%. Sisa 50% dibayar saat serah terima di workshop.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-questrial">Bayar Sekarang:</span>
                    <span class="font-audiowide font-bold text-[#ff6b00] text-sm"
                          x-text="formatRupiah(dpAmount)"></span>
                </div>
            </div>
        </label>

        {{-- Opsi B: Lunas 100% --}}
        <label class="cursor-pointer block h-full">
            <input type="radio" name="payment_type_radio" value="lunas" class="sr-only"
                   @change="paymentType = 'lunas'" :checked="paymentType === 'lunas'">
            <div class="p-4 sm:p-5 rounded-2xl border transition-all h-full flex flex-col justify-between"
                 :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-orange-50/70 shadow-sm ring-1 ring-[#ff6b00]/40' : 'border-slate-200 bg-white hover:border-slate-300 shadow-sm'">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-montserrat font-bold uppercase text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-md border border-emerald-300">Lunas &amp; Beres</span>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                             :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-slate-300'">
                            <div x-show="paymentType === 'lunas'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                    <h4 class="text-sm font-audiowide font-bold text-slate-900">Lunas 100%</h4>
                    <p class="text-[11px] font-questrial text-slate-500 mt-1 leading-relaxed">
                        Bayar penuh di awal. Langsung beres tanpa perlu transaksi ulang saat mobil selesai.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-questrial">Total Lunas:</span>
                    <span class="font-audiowide font-bold text-slate-900 text-sm"
                          x-text="formatRupiah(grandTotal)"></span>
                </div>
            </div>
        </label>
    </div>

    {{-- Bank Tujuan Selector --}}
    <div class="space-y-3">
        <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
            Bank Tujuan Transfer <span class="text-[#ff6b00]">*</span>
        </label>
        <div class="grid grid-cols-3 gap-2.5">
            <template x-for="bank in banks" :key="bank.id">
                <button type="button"
                        @click="selectedBankId = bank.id"
                        :class="selectedBankId === bank.id ? 'border-[#ff6b00] bg-orange-50 text-slate-900 shadow-sm ring-1 ring-[#ff6b00]/40' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-900 shadow-sm'"
                        class="py-3 px-2 rounded-2xl border text-xs font-montserrat font-extrabold flex flex-col items-center gap-1 transition-all">
                    <span class="text-sm font-bold" x-text="bank.code"></span>
                    <span class="text-[9px] font-questrial text-slate-500 font-normal" x-text="bank.shortName"></span>
                </button>
            </template>
        </div>

        {{-- Kotak Rekening Resmi & Tombol Salin --}}
        <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 shadow-sm">
            <div class="space-y-1 min-w-0">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500 block">Nomor Rekening Tujuan:</span>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-montserrat font-black border"
                          :class="currentBank.badgeBg"
                          x-text="currentBank.code"></span>
                    <span class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wider"
                          x-text="currentBank.formatted"></span>
                    <span class="text-xs font-questrial text-slate-500 truncate"
                          x-text="'a.n. ' + currentBank.holder"></span>
                </div>
            </div>
            <button type="button"
                    @click="copyRekening(currentBank.number)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-[#ff6b00] hover:text-white border border-slate-200 text-xs font-montserrat font-bold text-slate-700 transition-all shrink-0 active:scale-95 shadow-sm">
                <i :class="rekeningCopied ? 'ph-bold ph-check text-emerald-500' : 'ph-bold ph-copy'"></i>
                <span x-text="rekeningCopied ? 'Tersalin!' : 'Salin'"></span>
            </button>
        </div>
    </div>

    {{-- Upload Bukti Transfer --}}
    <div>
        <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
            Bukti Transfer <span class="text-slate-400 font-normal normal-case">(Opsional &mdash; bisa diunggah sekarang atau nanti)</span>
        </label>
        <label class="relative block border-2 border-dashed border-slate-200 hover:border-[#ff6b00]/60 rounded-2xl p-5 text-center transition-all bg-slate-50/50 cursor-pointer shadow-sm">
            <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                   @change="handleFile($event)"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
            <div class="flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#ff6b00]/10 flex items-center justify-center text-[#ff6b00] shrink-0">
                    <i class="ph-bold ph-upload-simple text-xl"></i>
                </div>
                <div class="text-left">
                    <template x-if="!proofFileName">
                        <div>
                            <p class="text-xs font-montserrat font-bold text-slate-900">Klik untuk Pilih File Bukti Transfer</p>
                            <p class="text-[10px] font-questrial text-slate-500">JPG, PNG, PDF &mdash; Maks. 5MB</p>
                        </div>
                    </template>
                    <template x-if="proofFileName">
                        <div class="flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#ff6b00]">
                            <i class="ph-bold ph-file-text"></i>
                            <span x-text="proofFileName" class="truncate max-w-[220px]"></span>
                        </div>
                    </template>
                </div>
            </div>
        </label>
    </div>

    {{-- ════ WIZARD NAVIGATION STEP 4 ════ --}}
    <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
        <button type="button"
                @click="prevStep()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all cursor-pointer shadow-sm">
            <i class="ph-bold ph-arrow-left text-sm"></i>
            <span>Kembali ke Data Kendaraan</span>
        </button>

        <button type="button"
                @click="handlePrimaryButton()"
                :disabled="isSubmitting"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-2xl bg-[#ff6b00] hover:bg-[#e05d00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_4px_20px_rgba(255,107,0,0.3)] disabled:opacity-40 cursor-pointer">
            <svg x-show="isSubmitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span x-text="isSubmitting ? 'Memproses Booking...' : 'Bayar & Konfirmasi Booking Sekarang'"></span>
            <i x-show="!isSubmitting" class="ph-bold ph-shield-check text-base"></i>
        </button>
    </div>

</section>

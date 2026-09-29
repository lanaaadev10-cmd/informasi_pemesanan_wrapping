{{-- ──────────────────────────────────────────
     LANGKAH 4: SKEMA & METODE PEMBAYARAN
────────────────────────────────────────── --}}
<section x-show="currentStep === 4" x-transition.opacity
         class="bg-[#141416]/95 border border-white/10 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-6">

    <div class="flex items-center gap-2.5 border-b border-white/10 pb-5">
        <span class="w-3 h-3 rounded-full bg-[#ff6b00] shadow-[0_0_10px_rgba(255,107,0,0.8)]"></span>
        <div>
            <h2 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                Langkah 4: Skema &amp; Metode Pembayaran
            </h2>
            <p class="text-xs font-questrial text-gray-400 mt-0.5">
                Kunci slot pengerjaan dengan DP 50% atau langsung pelunasan 100%.
            </p>
        </div>
    </div>

    {{-- Skema DP vs Lunas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Opsi A: DP 50% --}}
        <label class="cursor-pointer block h-full">
            <input type="radio" name="payment_type_radio" value="dp" class="sr-only"
                   @change="paymentType = 'dp'" :checked="paymentType === 'dp'">
            <div class="p-4 sm:p-5 rounded-2xl border transition-all h-full flex flex-col justify-between"
                 :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.15)] ring-1 ring-[#ff6b00]/40' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-montserrat font-bold uppercase text-[#ff6b00] bg-[#ff6b00]/15 px-2.5 py-0.5 rounded-md border border-[#ff6b00]/30">Opsi Terpopuler</span>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                             :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-white/30'">
                            <div x-show="paymentType === 'dp'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                        </div>
                    </div>
                    <h4 class="text-sm font-audiowide font-bold text-white">DP 50% di Awal</h4>
                    <p class="text-[11px] font-questrial text-gray-400 mt-1 leading-relaxed">
                        Kunci antrean dengan DP 50%. Sisa 50% dibayar saat serah terima di workshop.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                    <span class="text-gray-400 font-questrial">Bayar Sekarang:</span>
                    <span class="font-audiowide font-bold text-[#ff6b00] text-sm"
                          x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span>
                </div>
            </div>
        </label>

        {{-- Opsi B: Lunas 100% --}}
        <label class="cursor-pointer block h-full">
            <input type="radio" name="payment_type_radio" value="lunas" class="sr-only"
                   @change="paymentType = 'lunas'" :checked="paymentType === 'lunas'">
            <div class="p-4 sm:p-5 rounded-2xl border transition-all h-full flex flex-col justify-between"
                 :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.15)] ring-1 ring-[#ff6b00]/40' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-montserrat font-bold uppercase text-emerald-400 bg-emerald-500/15 px-2.5 py-0.5 rounded-md border border-emerald-500/30">Lunas &amp; Beres</span>
                        <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                             :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-white/30'">
                            <div x-show="paymentType === 'lunas'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                        </div>
                    </div>
                    <h4 class="text-sm font-audiowide font-bold text-white">Lunas 100%</h4>
                    <p class="text-[11px] font-questrial text-gray-400 mt-1 leading-relaxed">
                        Bayar penuh di awal. Langsung beres tanpa perlu transaksi ulang saat mobil selesai.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                    <span class="text-gray-400 font-questrial">Total Lunas:</span>
                    <span class="font-audiowide font-bold text-white text-sm"
                          x-text="formatRupiah(selectedLayanan ? selectedLayanan.price : 0)"></span>
                </div>
            </div>
        </label>
    </div>

    {{-- Bank Tujuan Selector --}}
    <div class="space-y-3">
        <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
            Bank Tujuan Transfer <span class="text-[#ff6b00]">*</span>
        </label>
        <div class="grid grid-cols-3 gap-2.5">
            <template x-for="bank in banks" :key="bank.id">
                <button type="button"
                        @click="selectedBankId = bank.id"
                        :class="selectedBankId === bank.id ? 'border-[#ff6b00] bg-[#ff6b00]/15 text-white shadow-[0_0_15px_rgba(255,107,0,0.25)] ring-1 ring-[#ff6b00]/40' : 'border-white/10 bg-white/[0.02] text-gray-400 hover:border-white/20 hover:text-white'"
                        class="py-3 px-2 rounded-2xl border text-xs font-montserrat font-extrabold flex flex-col items-center gap-1 transition-all">
                    <span class="text-sm" x-text="bank.code"></span>
                    <span class="text-[9px] font-questrial text-gray-400 font-normal" x-text="bank.shortName"></span>
                </button>
            </template>
        </div>

        {{-- Kotak Rekening Resmi & Tombol Salin --}}
        <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-[#0e0e11] border border-white/10">
            <div class="space-y-1 min-w-0">
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 block">Nomor Rekening Tujuan:</span>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[10px] font-montserrat font-black border"
                          :class="currentBank.badgeBg"
                          x-text="currentBank.code"></span>
                    <span class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wider"
                          x-text="currentBank.formatted"></span>
                    <span class="text-xs font-questrial text-gray-400 truncate"
                          x-text="'a.n. ' + currentBank.holder"></span>
                </div>
            </div>
            <button type="button"
                    @click="copyRekening(currentBank.number)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 text-xs font-montserrat font-bold text-gray-300 transition-all shrink-0 active:scale-95 shadow-sm">
                <i :class="rekeningCopied ? 'ph-bold ph-check text-emerald-400' : 'ph-bold ph-copy'"></i>
                <span x-text="rekeningCopied ? 'Tersalin!' : 'Salin'"></span>
            </button>
        </div>
    </div>

    {{-- Upload Bukti Transfer --}}
    <div>
        <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider mb-2">
            Bukti Transfer <span class="text-gray-500 font-normal normal-case">(Opsional &mdash; bisa diunggah sekarang atau nanti)</span>
        </label>
        <label class="relative block border-2 border-dashed border-white/15 hover:border-[#ff6b00]/50 rounded-2xl p-5 text-center transition-all bg-white/[0.01] cursor-pointer">
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
                            <p class="text-xs font-montserrat font-bold text-white">Klik untuk Pilih File Bukti Transfer</p>
                            <p class="text-[10px] font-questrial text-gray-500">JPG, PNG, PDF &mdash; Maks. 5MB</p>
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

</section>

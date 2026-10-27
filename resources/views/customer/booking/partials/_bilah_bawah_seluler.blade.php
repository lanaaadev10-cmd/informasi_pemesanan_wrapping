{{-- ════ 5. FLOATING MOBILE BAR (UNTUK TAMPILAN HP) ════ --}}
<div id="mobile-wizard-bar"
     class="fixed bottom-0 inset-x-0 z-[95] bg-white/95 backdrop-blur-xl border-t border-slate-200 px-4 py-3 shadow-[0_-8px_30px_rgba(0,0,0,0.12)] lg:hidden"
     style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
    <div class="max-w-xl mx-auto flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500 truncate"
               x-text="selectedLayanan ? selectedLayanan.name : 'Pilih Layanan'"></p>
            <div class="flex items-baseline gap-1">
                <span class="text-[10px] font-montserrat font-bold text-slate-700" x-text="paymentType === 'dp' ? 'DP:' : 'Total:'"></span>
                <span class="text-base font-audiowide font-bold text-[#ff6b00]"
                      x-text="formatRupiah(currentPaymentAmount)"></span>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <button type="button"
                    x-show="currentStep > 1"
                    @click="prevStep()"
                    class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 hover:text-slate-900 transition-all active:scale-95 cursor-pointer shadow-sm">
                <i class="ph-bold ph-arrow-left text-sm"></i>
            </button>

            <button type="button"
                    @click="handlePrimaryButton()"
                    :disabled="isSubmitting || (currentStep === 2 && selectedDateQuota.is_full)"
                    class="px-5 py-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider shadow-md shadow-[#ff6b00]/25 shrink-0 flex items-center gap-1.5 active:scale-95 disabled:opacity-40 cursor-pointer">
                <span x-text="isSubmitting ? 'Memproses...' : (currentStep < 4 ? 'Lanjut' : 'Bayar Sekarang')"></span>
                <i :class="currentStep < 4 ? 'ph-bold ph-arrow-right text-xs' : 'ph-bold ph-shield-check text-xs'"></i>
            </button>
        </div>
    </div>
</div>
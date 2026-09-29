{{-- ════ 5. FLOATING MOBILE BAR (UNTUK TAMPILAN HP) ════ --}}
<div class="fixed bottom-0 inset-x-0 z-40 bg-[#141416]/95 backdrop-blur-xl border-t border-white/10 px-4 py-3 shadow-[0_-8px_30px_rgba(0,0,0,0.8)] lg:hidden">
    <div class="max-w-xl mx-auto flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 truncate"
               x-text="selectedLayanan ? selectedLayanan.name : 'Pilih Layanan'"></p>
            <div class="flex items-baseline gap-1">
                <span class="text-[10px] font-montserrat font-bold text-gray-300" x-text="paymentType === 'dp' ? 'DP:' : 'Total:'"></span>
                <span class="text-base font-audiowide font-bold text-[#ff6b00]"
                      x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))"></span>
            </div>
        </div>

        <button type="button"
                @click="handlePrimaryButton()"
                :disabled="isSubmitting || (currentStep === 2 && selectedDateQuota.is_full)"
                class="px-5 py-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#ff6b00]/30 shrink-0 flex items-center gap-1.5 active:scale-95 disabled:opacity-40">
            <span x-text="currentStep === 4 ? 'Konfirmasi' : 'Lanjut'"></span>
            <i class="ph-bold ph-arrow-right text-xs"></i>
        </button>
    </div>
</div>

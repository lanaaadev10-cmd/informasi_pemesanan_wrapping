{{-- Right Side: Order Summary & Warranty Box --}}
<div class="lg:col-span-4 space-y-4">
    <div class="sticky top-24 space-y-6">
        
        @php
            $subtotalVal = $keranjang->details->sum('subtotal');
            $serviceCharge = 150000;
            $grandTotal = $subtotalVal + $serviceCharge;
        @endphp

        <!-- 1. ORDER SUMMARY CARD -->
        <div class="bg-[#0E0E10] border border-white/10 rounded-[32px] p-6 sm:p-8 text-white relative overflow-hidden shadow-xl">
            <!-- Decorative ambient glow orb inside panel -->
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#FF6B00]/10 blur-[70px] rounded-full pointer-events-none"></div>

            <h3 class="text-lg font-audiowide font-bold mb-8 flex items-center gap-2.5 relative z-10 text-white">
                <i class="ph-bold ph-receipt text-[#FF6B00]"></i> {{ $profil->section_ringkasan_pesanan ?? 'Ringkasan Pesanan' }}
            </h3>

            <!-- Detailed rows breakdown -->
            <div class="space-y-4 mb-8 relative z-10 text-xs">
                <div class="flex justify-between items-center text-[#8A8D93]">
                    <span class="font-bold uppercase tracking-widest text-[9px] font-mono">SUBTOTAL</span>
                    <span id="summary-subtotal" class="font-extrabold text-white text-sm">
                        Rp {{ number_format($subtotalVal, 0, ',', '.') }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center text-[#8A8D93]">
                    <span class="font-bold uppercase tracking-widest text-[9px] font-mono">BIAYA LAYANAN</span>
                    <span class="font-extrabold text-white text-sm">
                        Rp {{ number_format($serviceCharge, 0, ',', '.') }}
                    </span>
                </div>

                <div class="w-full h-px bg-white/5 my-2"></div>
                
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-[9px] font-bold text-[#8A8D93] uppercase tracking-widest mb-0.5 block">TOTAL HARGA</span>
                        <span id="summary-total" class="text-2xl font-audiowide font-bold text-[#FF6B00]">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            @php
                $firstDetail = $keranjang?->details?->first();
                $targetLayananId = $firstDetail?->id_paket;
            @endphp
            <!-- Direct Action Lanjut ke Booking Jadwal -->
            <a href="{{ route('booking.create', $targetLayananId ? ['layanan_id' => $targetLayananId] : []) }}" 
               class="relative z-10 w-full min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black py-3.5 rounded-xl font-montserrat font-extrabold text-center block text-xs tracking-wider uppercase transition-all shadow-[0_4px_16px_rgba(255,107,0,0.35)] active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-calendar-plus text-sm"></i>
                <span>Lanjut Booking Jadwal</span>
                <i class="ph-bold ph-arrow-right text-xs"></i>
            </a>

            <!-- Supported Payment Brands -->
            <div class="mt-6 pt-5 border-t border-white/5 space-y-2">
                <span class="text-[8px] font-bold text-gray-500 uppercase tracking-widest block text-center">Metode Pembayaran Tersedia</span>
                <div class="flex justify-center items-center gap-4 text-gray-400 text-lg opacity-40">
                    <i class="ph ph-credit-card"></i>
                    <i class="ph ph-bank"></i>
                    <i class="ph ph-wallet"></i>
                </div>
            </div>
        </div>

        <!-- 2. WARRANTY TRUST BOX -->
        <div class="bg-white/[0.01] border border-white/5 rounded-[24px] p-5 flex gap-4 items-start shadow-sm z-10 relative">
            <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0 border border-white/5">
                <i class="ph-bold ph-shield-check text-lg"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-[10px] font-bold text-white uppercase tracking-widest">Garansi Pemasangan</h4>
                <p class="text-[9px] font-medium text-gray-500 leading-relaxed italic">
                    Setiap layanan wrapping kami mencakup garansi 1 tahun untuk kerutan atau gelembung udara.
                </p>
            </div>
        </div>

    </div>
</div>

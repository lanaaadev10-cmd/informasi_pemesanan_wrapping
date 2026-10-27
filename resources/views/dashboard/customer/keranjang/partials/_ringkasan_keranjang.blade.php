{{-- Right Side: Order Summary & Warranty Box --}}
<div class="lg:col-span-4 space-y-4">
    <div class="sticky top-24 space-y-6">
        
        @php
            $selectedDetails = $keranjang->details->whereIn('id_detail', $selectedIds);
            $selectedSubtotalVal = $selectedDetails->sum('subtotal');
            $serviceChargeSetting = !empty($profil->keranjang_service_charge_amount) ? (float) $profil->keranjang_service_charge_amount : 0;
            $serviceCharge = $serviceChargeSetting;
            $grandTotal = $selectedSubtotalVal + ($selectedDetails->isNotEmpty() ? $serviceCharge : 0);
        @endphp

        <!-- 1. ORDER SUMMARY CARD -->
        <div class="bg-white border border-slate-200/80 rounded-[32px] p-6 sm:p-8 text-slate-900 relative overflow-hidden shadow-sm">
            <!-- Decorative ambient glow orb inside panel -->
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#FF6B00]/10 blur-[70px] rounded-full pointer-events-none"></div>

            <h3 class="text-lg font-audiowide font-bold mb-6 flex items-center gap-2.5 relative z-10 text-slate-900">
                <i class="ph-bold ph-receipt text-[#FF6B00]"></i> {{ $profil->section_ringkasan_pesanan ?? 'Ringkasan Pesanan' }}
            </h3>

            <!-- Statistik Pemilihan Barang -->
            <div class="p-3.5 mb-6 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs relative z-10">
                <span class="text-slate-500 font-medium">Barang Dipilih:</span>
                <span class="font-audiowide font-bold text-slate-900">
                    <span id="summary-selected-count" class="text-emerald-600">{{ count($selectedIds) }}</span> / 
                    <span id="summary-total-cart-count" class="text-slate-500">{{ $keranjang->details->count() }}</span> Unit
                </span>
            </div>

            <!-- Detailed rows breakdown -->
            <div class="space-y-4 mb-6 relative z-10 text-xs">
                <div class="flex justify-between items-center text-slate-500">
                    <span class="font-bold uppercase tracking-widest text-[9px] font-mono">SUBTOTAL TERPILIH</span>
                    <span id="summary-subtotal" class="font-extrabold text-slate-900 text-sm">
                        Rp {{ number_format($selectedSubtotalVal, 0, ',', '.') }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center text-slate-500">
                    <span class="font-bold uppercase tracking-widest text-[9px] font-mono">{{ $profil->keranjang_service_charge_label ?? 'BIAYA LAYANAN' }}</span>
                    @if($serviceCharge > 0)
                        <span id="summary-service-fee" class="font-extrabold text-slate-900 text-sm">
                            Rp {{ number_format($serviceCharge, 0, ',', '.') }}
                        </span>
                    @else
                        <span id="summary-service-fee" class="font-bold text-emerald-600 text-xs">
                            Gratis (Termasuk Paket)
                        </span>
                    @endif
                </div>

                <div class="w-full h-px bg-slate-100 my-2"></div>
                
                <div class="flex justify-between items-end">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 block">TOTAL PEMBAYARAN</span>
                        <span id="summary-total" class="text-2xl font-audiowide font-bold text-[#FF6B00]">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Checkout Form -->
            <form id="form-checkout-selected" action="{{ route('pesanan.checkout.form') }}" method="GET" class="relative z-10 space-y-3">
                <div id="hidden-selected-container">
                    @foreach($selectedIds as $sid)
                        <input type="hidden" name="selected[]" value="{{ $sid }}" class="hidden-selected-input">
                    @endforeach
                </div>

                <!-- Tombol Checkout Utama -->
                <button type="submit" 
                        id="btn-checkout-submit"
                        {{ count($selectedIds) === 0 ? 'disabled' : '' }}
                        class="w-full min-h-[48px] py-4 px-6 rounded-2xl font-montserrat font-extrabold text-center text-xs tracking-wider uppercase transition-all shadow-[0_8px_25px_rgba(255,107,0,0.25)] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none bg-[#FF6B00] hover:bg-[#E05D00] text-white active:scale-95">
                    <i class="ph-bold ph-shopping-bag text-base"></i>
                    <span id="btn-checkout-label">
                        {{ count($selectedIds) > 0 ? 'Checkout (' . count($selectedIds) . ' Barang)' : 'Pilih Barang Terlebih Dahulu' }}
                    </span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </button>

                @php
                    $firstSelectedDetail = $selectedDetails->first();
                    $directBookingId = $firstSelectedDetail?->id_paket;
                @endphp
                <!-- Pilihan Cepat: Booking Jadwal Langsung -->
                <a id="btn-direct-booking" 
                   href="{{ route('booking.create', $directBookingId ? ['layanan_id' => $directBookingId] : []) }}"
                   class="{{ count($selectedIds) === 0 ? 'hidden' : 'inline-flex' }} w-full items-center justify-center gap-1.5 py-2.5 text-[11px] font-bold font-montserrat text-[#FF6B00] hover:text-[#E05D00] hover:underline transition-all text-center">
                    <i class="ph-bold ph-calendar-check text-xs"></i>
                    <span>Atau Jadwalkan Booking Bengkel Sekarang &rarr;</span>
                </a>
            </form>

            <!-- Supported Payment Brands -->
            <div class="mt-6 pt-5 border-t border-slate-100 space-y-2">
                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block text-center">Metode Pembayaran Tersedia</span>
                <div class="flex justify-center items-center gap-4 text-slate-400 text-lg opacity-60">
                    <i class="ph ph-credit-card"></i>
                    <i class="ph ph-bank"></i>
                    <i class="ph ph-wallet"></i>
                </div>
            </div>
        </div>

        <!-- 2. WARRANTY TRUST BOX -->
        <div class="bg-white border border-slate-200/80 rounded-[24px] p-5 flex gap-4 items-start shadow-sm z-10 relative">
            <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#FF6B00] shrink-0">
                <i class="ph-bold ph-shield-check text-lg"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-[10px] font-bold text-slate-900 uppercase tracking-widest font-montserrat">Garansi Pemasangan</h4>
                <p class="text-[9px] font-medium text-slate-500 leading-relaxed italic font-questrial">
                    Setiap layanan wrapping kami mencakup garansi 1 tahun untuk kerutan atau gelembung udara.
                </p>
            </div>
        </div>

    </div>
</div>

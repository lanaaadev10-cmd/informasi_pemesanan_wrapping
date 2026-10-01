{{-- ═══════════════════════════════════════════════════════════
     BANNER PENGINGAT ULASAN (REVIEW REMINDER BANNER)
     Muncul otomatis di atas Aksi Cepat saat ada booking/pesanan selesai
═══════════════════════════════════════════════════════════ --}}
@if(isset($unreviewedBooking) && $unreviewedBooking)
    <div class="relative overflow-hidden rounded-3xl bg-[#141416] border border-[#FF6B00]/30 p-4 sm:p-5 shadow-[0_8px_30px_rgba(255,107,0,0.15)] backdrop-blur-md">
        {{-- Decorative Glow Circles --}}
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#FF6B00]/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#FF6B00] font-montserrat font-bold text-[9px] uppercase tracking-wider">
                            Pengerjaan Selesai
                        </span>
                        <span class="text-gray-600">&bull;</span>
                        <span class="text-[10px] font-mono text-gray-400">{{ $unreviewedBooking->booking_code }}</span>
                    </div>
                    <h4 class="text-sm sm:text-base font-montserrat font-extrabold text-white mt-1">
                        Bagaimana Hasil Wrapping {{ $unreviewedBooking->vehicle_name }} Anda?
                    </h4>
                    <p class="text-[11px] sm:text-xs font-questrial text-gray-300 mt-0.5 leading-relaxed">
                        Pengerjaan <span class="text-[#FFB800] font-semibold">{{ $unreviewedBooking->layanan->nama_layanan ?? 'Wrapping' }}</span> telah selesai. Bantu kami dengan memberikan ulasan & rating bintang.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 self-end sm:self-center w-full sm:w-auto">
                <button type="button"
                        onclick="window.openRatingModal({
                            bookingId: {{ $unreviewedBooking->id }},
                            serviceName: '{{ addslashes($unreviewedBooking->layanan->nama_layanan ?? 'Layanan Wrapping') }}',
                            orderCode: '{{ $unreviewedBooking->booking_code }}'
                        })"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider rounded-2xl transition-all shadow-[0_4px_18px_rgba(255,107,0,0.35)] active:scale-95">
                    <i class="ph-bold ph-star text-sm"></i>
                    <span>Beri Ulasan Sekarang</span>
                </button>
            </div>
        </div>
    </div>
@elseif(isset($unreviewedPesanan) && $unreviewedPesanan)
    <div class="relative overflow-hidden rounded-3xl bg-[#141416] border border-[#FF6B00]/30 p-4 sm:p-5 shadow-[0_8px_30px_rgba(255,107,0,0.15)] backdrop-blur-md">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#FF6B00] font-montserrat font-bold text-[9px] uppercase tracking-wider">
                            Pesanan Selesai
                        </span>
                        <span class="text-gray-600">&bull;</span>
                        <span class="text-[10px] font-mono text-gray-400">{{ $unreviewedPesanan->kode_pesanan }}</span>
                    </div>
                    <h4 class="text-sm sm:text-base font-montserrat font-extrabold text-white mt-1">
                        Pesanan Anda Telah Selesai!
                    </h4>
                    <p class="text-[11px] sm:text-xs font-questrial text-gray-300 mt-0.5 leading-relaxed">
                        Bagikan penilaian Anda tentang layanan dan hasil pengerjaan kami.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 self-end sm:self-center w-full sm:w-auto">
                <button type="button"
                        onclick="window.openRatingModal({
                            pesananId: {{ $unreviewedPesanan->id_pesanan }},
                            layananId: {{ $unreviewedPesanan->details->first()?->id_paket ?? 'null' }},
                            serviceName: '{{ addslashes($unreviewedPesanan->details->first()?->layanan->nama_layanan ?? 'Pesanan Wrapping') }}',
                            orderCode: '{{ $unreviewedPesanan->kode_pesanan }}'
                        })"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider rounded-2xl transition-all shadow-[0_4px_18px_rgba(255,107,0,0.35)] active:scale-95">
                    <i class="ph-bold ph-star text-sm"></i>
                    <span>Beri Ulasan Sekarang</span>
                </button>
            </div>
        </div>
    </div>
@endif

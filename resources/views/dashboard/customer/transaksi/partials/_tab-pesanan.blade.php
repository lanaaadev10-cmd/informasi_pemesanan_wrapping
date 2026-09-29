{{-- Konten Tab: Pesanan & Pembayaran --}}
<div class="space-y-6 z-10 relative" role="tabpanel" aria-labelledby="tab-pesanan">
    {{-- Filter Sub-Tabs Pesanan --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
        @php
            $pesananFilters = [
                '' => 'Semua Pesanan',
                'menunggu_pembayaran' => 'Tagihan / Menunggu Bayar',
                'berjalan' => 'Sedang Dikerjakan',
                'selesai' => 'Selesai',
            ];
        @endphp
        @foreach($pesananFilters as $fKey => $fLabel)
            @php
                $isActive = ((string) $pesananStatus === (string) $fKey);
            @endphp
            <a href="{{ route('transaksi.index', ['type' => 'pesanan', 'pesanan_status' => $fKey]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider whitespace-nowrap transition-all {{ $isActive ? 'bg-white text-black shadow-md font-black' : 'bg-[#0E0E10] text-[#8A8D93] hover:text-white hover:bg-white/5 border border-white/5' }}">
                <span>{{ $fLabel }}</span>
            </a>
        @endforeach
    </div>

    {{-- Daftar Kartu Pesanan --}}
    @if($pesanans->isEmpty())
        <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-12 sm:p-16 text-center shadow-xl">
            <div class="w-16 h-16 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] text-2xl mx-auto mb-4">
                <i class="ph-bold ph-receipt"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white mb-2">
                Tidak Ada Pesanan Layanan
            </h3>
            <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-md mx-auto mb-6">
                Belum ada pesanan pada kategori ini. Anda dapat menjelajahi paket layanan dan melakukan pemesanan langsung.
            </p>
            <a href="{{ route('katalog.user') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-lg active:scale-95 transition-all">
                <i class="ph-bold ph-storefront text-base"></i> Jelajahi Layanan
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($pesanans as $ps)
                @php
                    $stVal = $ps->status instanceof \App\Enums\OrderStatus ? $ps->status->value : $ps->status;
                    $isWaitingPay = in_array($stVal, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin', 'menunggu_verifikasi_pembayaran']);
                    $isDone = ($stVal === 'selesai');
                    $isWork = in_array($stVal, ['sedang_diproses', 'dikonfirmasi']);
                    $thumbnail = $ps->details->first()?->layanan?->foto_contoh;
                    $imageUrl = \App\Helpers\StaticContent::fotoUrl($thumbnail ?? '');
                @endphp
                <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/40 rounded-[24px] overflow-hidden flex flex-col md:flex-row group transition-all shadow-xl">
                    
                    <!-- Thumbnail Visual -->
                    <div class="md:w-56 h-40 md:h-auto relative shrink-0 bg-black/60 overflow-hidden">
                        <img src="{{ $imageUrl }}" alt="{{ $ps->form?->model_kendaraan ?? 'Mobil' }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-black/80 via-transparent to-transparent"></div>
                    </div>

                    <!-- Detail Content -->
                    <div class="p-5 sm:p-6 flex flex-col justify-between flex-grow gap-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                            <div class="space-y-1">
                                <!-- Header & Clean Text Status -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-[10px] font-mono font-bold text-[#8A8D93] uppercase tracking-widest">
                                        #{{ $ps->kode_pesanan }}
                                    </span>
                                    <span class="text-gray-600">&bull;</span>
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $isWork || $isWaitingPay ? 'text-[#FF6B00]' : ($isDone ? 'text-white' : 'text-[#8A8D93]') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isWork || $isWaitingPay ? 'bg-[#FF6B00] animate-pulse' : ($isDone ? 'bg-white' : 'bg-gray-500') }}"></span>
                                        {{ $ps->label_status }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-audiowide font-bold text-white leading-tight">
                                    {{ $ps->form?->model_kendaraan ?? 'Kendaraan Customer' }}
                                </h3>
                                <p class="text-xs font-questrial text-[#8A8D93]">
                                    {{ $ps->details->first()?->layanan?->nama_layanan ?? 'Full Wrapping' }}
                                    @if($ps->form?->warna_kendaraan)
                                        &bull; Warna: {{ $ps->form->warna_kendaraan }}
                                    @endif
                                </p>
                            </div>

                            <div class="text-left sm:text-right">
                                <span class="text-[9px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest block mb-0.5">Total Tagihan</span>
                                <span class="text-[#FF6B00] font-audiowide font-bold text-xl sm:text-2xl">
                                    Rp {{ number_format($ps->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions Bar -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-white/5">
                            <div class="flex items-center gap-4 text-xs font-montserrat font-bold text-[#8A8D93]">
                                <span>Dipesan: {{ \Carbon\Carbon::parse($ps->tanggal_pesan)->translatedFormat('d M Y') }}</span>
                            </div>

                            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                @if($isWaitingPay)
                                    <a href="{{ route('pesanan.show', $ps->id_pesanan) }}"
                                       class="w-full sm:w-auto text-center px-5 py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_14px_rgba(255,107,0,0.35)] flex items-center justify-center gap-1.5 active:scale-95">
                                        <span>Bayar Tagihan</span>
                                        <i class="ph-bold ph-arrow-right text-xs"></i>
                                    </a>
                                @else
                                    <a href="{{ route('pesanan.show', $ps->id_pesanan) }}"
                                       class="w-full sm:w-auto text-center px-5 py-2.5 min-h-[44px] bg-[#16161A] hover:bg-[#FF6B00] hover:text-black border border-white/10 hover:border-[#FF6B00] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                        <span>Lihat Rincian</span>
                                        <i class="ph-bold ph-arrow-right text-xs"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $pesanans->appends(['type' => 'pesanan', 'pesanan_status' => $pesananStatus])->links() }}
        </div>
    @endif
</div>

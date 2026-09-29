<!-- PANEL 3: REVIEW (Figma Design Layout) -->
<div id="step-panel-3" class="hidden animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Kiri: Rincian Kartu (Col 8) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Card 1: Layanan Terpilih -->
            <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-white/5 pb-4">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-stack text-[#f2994a] text-2xl"></i>
                        <h3 class="text-white font-medium text-lg">{{ $profil->section_layanan_terpilih ?? 'Layanan Terpilih' }}</h3>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @foreach($keranjang->details as $item)
                    <div class="flex gap-5 items-center">
                        <div class="w-24 h-24 bg-white/5 rounded-xl border border-white/5 overflow-hidden shrink-0 shadow-inner">
                            @if($item->layanan->foto_contoh)
                                <img src="{{ \App\Helpers\StaticContent::fotoUrl($item->layanan->foto_contoh) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="ph ph-car text-3xl text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-1.5 flex-grow">
                            <h4 class="text-white font-medium text-base">{{ $item->layanan->nama_layanan }}</h4>
                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                Kategori: {{ $item->layanan->kategori ?? 'Layanan Premium' }}<br>
                                Kuantitas: {{ $item->jumlah }} Unit
                            </p>
                            <div class="flex gap-2 mt-1">
                                <span class="text-[8px] bg-white/5 text-gray-400 px-2 py-1 rounded border border-white/10 uppercase tracking-widest font-bold">Garansi 2 Tahun</span>
                                <span class="text-[8px] bg-white/5 text-gray-400 px-2 py-1 rounded border border-white/10 uppercase tracking-widest font-bold">UV Protection</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Card 2: Detail Kendaraan -->
            <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-white/5 pb-4">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-car-profile text-[#f2994a] text-2xl"></i>
                        <h3 class="text-white font-medium text-lg">{{ $profil->section_detail_kendaraan ?? 'Detail Kendaraan' }}</h3>
                    </div>
                    <button type="button" onclick="goToStep(2)" class="flex items-center gap-1.5 text-gray-400 hover:text-white text-xs font-medium transition-colors">
                        <i class="ph-bold ph-pencil-simple"></i> {{ $profil->cta_edit ?? 'Edit' }}
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-y-8 gap-x-4">
                    <div>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_merk_kendaraan ?? 'Merk & Model' }}</span>
                        <span id="review-merk" class="text-gray-200 font-medium text-sm"></span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_nomor_polisi ?? 'Nomor Polisi' }}</span>
                        <span id="review-nopol" class="text-gray-200 font-medium text-sm"></span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_warna_kendaraan ?? 'Warna Dasar' }}</span>
                        <span id="review-warna" class="text-gray-200 font-medium text-sm"></span>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_tahun_produksi ?? 'Tahun Produksi' }}</span>
                        <span id="review-tahun" class="text-gray-200 font-medium text-sm"></span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Jadwal Sesi -->
            <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-white/5 pb-4">
                    <div class="flex items-center gap-3">
                        <i class="ph ph-calendar-blank text-[#f2994a] text-2xl"></i>
                        <h3 class="text-white font-medium text-lg">{{ $profil->section_jadwal_sesi ?? 'Jadwal Sesi' }}</h3>
                    </div>
                    <button type="button" onclick="goToStep(2)" class="flex items-center gap-1.5 text-gray-400 hover:text-white text-xs font-medium transition-colors">
                        <i class="ph-bold ph-pencil-simple"></i> {{ $profil->cta_edit ?? 'Edit' }}
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="flex items-start gap-3">
                        <i class="ph ph-calendar text-gray-400 text-xl mt-0.5"></i>
                        <div>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_tanggal_mulai ?? 'Tanggal Mulai' }}</span>
                            <span id="review-jadwal" class="text-gray-200 font-medium text-xs leading-relaxed block max-w-[150px]"></span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-clock text-gray-400 text-xl mt-0.5"></i>
                        <div>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">Estimasi Durasi</span>
                            <span class="text-gray-200 font-medium text-xs leading-relaxed block">4 - 5 Hari Kerja</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-map-pin text-gray-400 text-xl mt-0.5"></i>
                        <div>
                            <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">Workshop</span>
                            <span id="review-lokasi" class="text-gray-200 font-medium text-xs leading-relaxed block">Wapping Premium - HQ</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kanan: Rincian Biaya (Col 4) -->
        <div class="lg:col-span-4 relative">
            <div class="bg-[#121212] border border-white/5 rounded-[24px] p-6 sm:p-8 space-y-8 shadow-lg lg:sticky lg:top-24">
                <h3 class="text-white font-medium text-lg">{{ $profil->section_rincian_biaya ?? 'Rincian Biaya' }}</h3>
                
                <div class="space-y-4">
                    @foreach($keranjang->details as $item)
                        <div class="flex justify-between items-center text-xs text-gray-400">
                            <span class="truncate pr-4">{{ $item->layanan->nama_layanan }} ({{ $item->jumlah }}x)</span>
                            <span class="font-medium text-white shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center text-xs text-gray-400 pt-2">
                        <span>Biaya Layanan & Pemasangan</span>
                        <span class="font-medium text-white shrink-0">Rp 150.000</span>
                    </div>
                </div>

                @php
                    $subtotal = $keranjang->details->sum('subtotal');
                    $grandTotal = $subtotal + 150000;
                @endphp

                <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-5 flex justify-between items-center shadow-inner mt-4">
                    <span class="text-gray-400 text-xs font-medium">{{ $profil->label_total_tagihan ?? 'Total Pembayaran' }}</span>
                    <span class="text-[#f2994a] text-xl font-medium tracking-tight">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <div class="pt-2">
                    <button type="submit" onclick="prepareSubmit()" class="w-full py-4 bg-[#f2994a] hover:bg-[#e28a44] rounded-xl text-black font-medium text-sm transition-all hover:shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                        {{ $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan' }} <i class="ph-bold ph-arrow-right"></i>
                    </button>
                    <p class="text-center text-[9px] text-gray-500 leading-relaxed mt-5 px-2">
                        {{ $profil->checkout_terms_text ?? 'Dengan mengklik Konfirmasi, Anda menyetujui Syarat & Ketentuan serta Kebijakan Pembatalan kami.' }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Detail Transfer dan Ringkasan Pesanan --}}
<div class="lg:col-span-7 space-y-6">
    <div class="bg-[#121212] border border-white/5 rounded-3xl p-8 shadow-lg">
        <div class="flex items-center gap-3 mb-6">
            <i class="ph ph-bank text-[#f2994a] text-2xl"></i>
            <h2 class="text-xl font-bold text-white">Detail Transfer</h2>
        </div>
        
        <p class="text-xs text-gray-400 mb-8 leading-relaxed">
            Silakan selesaikan pembayaran Anda dengan mentransfer jumlah yang sesuai ke rekening bank resmi kami di bawah ini.
        </p>

        <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6 relative overflow-hidden mb-6">
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            
            <div class="grid grid-cols-3 gap-y-6 relative z-10">
                <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $profil->label_nama_bank ?? 'Nama Bank' }}</div>
                <div class="col-span-2 text-right text-xs font-bold text-white">BCA (Bank Central Asia)</div>
                
                <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest flex items-center">{{ $profil->label_no_rekening ?? 'No. Rekening' }}</div>
                <div class="col-span-2 text-right flex items-center justify-end gap-3">
                    <span class="text-xl md:text-2xl font-mono font-black text-white tracking-widest">123-456-7890</span>
                    <button onclick="copyToClipboard('1234567890')" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center transition-colors text-gray-400 hover:text-white" title="{{ $profil->cta_salin_rekening ?? 'Salin Nomor Rekening' }}">
                        <i class="ph ph-copy text-sm"></i>
                    </button>
                </div>

                <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $profil->label_atas_nama ?? 'Atas Nama' }}</div>
                <div class="col-span-2 text-right text-xs font-bold text-white">PT Wapping Indonesia</div>
            </div>
        </div>

        <div class="bg-[#241710] border border-[#f2994a]/30 rounded-2xl p-6 flex justify-between items-center mb-6">
            <div>
                <span class="text-[9px] font-bold text-[#f2994a]/70 uppercase tracking-widest block mb-1">{{ $profil->label_total_bayar ?? 'Total yang Harus Dibayar' }}</span>
                <span class="text-2xl font-bold text-[#f2994a]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 bg-[#f2994a]/10 rounded-xl flex items-center justify-center text-[#f2994a]">
                <i class="ph ph-wallet text-2xl"></i>
            </div>
        </div>

        <div class="flex gap-3 text-[10px] text-gray-400 bg-white/5 rounded-xl p-4 border border-white/5">
            <i class="ph-fill ph-info text-[#f2994a] text-lg shrink-0"></i>
            <p class="leading-relaxed">
                <strong class="text-gray-300">Penting:</strong> Pastikan Anda mentransfer jumlah yang sesuai. Pembayaran biasanya diverifikasi dalam 30 menit setelah upload bukti selama jam kerja (09:00 - 18:00).
            </p>
        </div>
    </div>

    <div class="bg-[#121212] border border-white/5 rounded-3xl p-6 shadow-sm">
        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest block mb-4">{{ $profil->section_ringkasan_pesanan ?? 'Ringkasan Pesanan' }}</span>
        
        @php
            $firstItem = $pesanan->details->first();
            $thumbnail = $firstItem?->layanan->foto_contoh;
            $imageUrl = \App\Helpers\StaticContent::fotoUrl($thumbnail ?? '');
        @endphp

        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-white/5 border border-white/10 shrink-0">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="text-sm font-bold text-white leading-tight mb-1">
                        {{ $pesanan->form->model_kendaraan ?? 'Kendaraan Wapping' }} - {{ $firstItem?->layanan->nama_layanan ?? 'Custom Wrap' }}
                    </h4>
                    <span class="text-[10px] text-gray-500 font-mono">Project ID: #{{ $pesanan->kode_pesanan }}</span>
                </div>
            </div>
            
            <div class="shrink-0 hidden sm:block">
                <span class="bg-[#f2994a]/10 border border-[#f2994a]/30 text-[#f2994a] text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full">
                    {{ $profil->status_menunggu_pembayaran ?? 'Menunggu Pembayaran' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terakhir Section (Clean Text & White-Black-Orange Palette) -->
<div class="space-y-4">
    <div class="flex items-end justify-between">
        <div class="relative inline-block">
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                Aktivitas Terakhir
            </h3>
            <span class="absolute -bottom-1.5 left-0 w-8 h-[3px] bg-[#ff6b00] rounded-full"></span>
        </div>
        <a href="{{ route('pesanan.index') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#ff6b00] hover:text-[#ea580c] transition-colors group">
            <span>Lihat Riwayat</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
    
    <div class="bg-[#121212] border border-white/10 rounded-[28px] divide-y divide-white/5 overflow-hidden shadow-xl">
        @if($latestOrders->isNotEmpty())
            @foreach($latestOrders as $order)
                @php
                    $icon = 'ph-credit-card';
                    $title = 'Pesanan';
                    $desc = '';
                    $rightCol = '';

                    switch($order->status) {
                        case 'menunggu_konfirmasi_admin':
                            $icon = 'ph-clock';
                            $title = $profil->status_menunggu_konfirmasi ?? 'Menunggu Konfirmasi';
                            $desc = 'Pesanan #' . $order->kode_pesanan . ' (' . ($order->form?->model_kendaraan ?? 'Kendaraan') . ') sedang ditinjau oleh admin';
                            $rightCol = '<span class="block text-xs font-bold text-[#ff6b00] font-mono">Rp ' . number_format($order->total_harga, 0, ',', '.') . '</span>';
                            break;
                        case 'menunggu_pembayaran':
                            $icon = 'ph-wallet';
                            $title = $profil->status_menunggu_pembayaran ?? 'Menunggu Pembayaran';
                            $desc = 'Silakan lakukan pembayaran untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<a href="' . route('pesanan.show', $order->id_pesanan) . '" class="block text-xs font-bold text-[#ff6b00] hover:underline uppercase tracking-wider font-montserrat">' . ($profil->cta_bayar_sekarang ?? 'Bayar Sekarang') . ' &rarr;</a>';
                            break;
                        case 'menunggu_verifikasi_pembayaran':
                            $icon = 'ph-hourglass-high';
                            $title = $profil->status_verifikasi_pembayaran ?? 'Verifikasi Pembayaran';
                            $desc = 'Bukti pembayaran pesanan #' . $order->kode_pesanan . ' sedang diverifikasi';
                            $rightCol = '<span class="block text-xs font-bold text-[#ff6b00] uppercase tracking-wider font-montserrat">Verifikasi</span>';
                            break;
                        case 'dikonfirmasi':
                            $icon = 'ph-check-circle';
                            $title = $profil->status_dikonfirmasi ?? 'Pesanan Dikonfirmasi';
                            $desc = 'Pembayaran terverifikasi untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<span class="block text-xs font-bold text-white uppercase tracking-wider font-montserrat">' . ($profil->status_lunas ?? 'Lunas') . '</span>';
                            break;
                        case 'sedang_diproses':
                            $icon = 'ph-wrench';
                            $title = $profil->status_dikerjakan ?? 'Sedang Dikerjakan';
                            $desc = 'Kendaraan pesanan #' . $order->kode_pesanan . ' (' . ($order->form?->model_kendaraan ?? 'Kendaraan') . ') sedang dikerjakan';
                            $rightCol = '<span class="block text-xs font-bold text-[#ff6b00] uppercase tracking-wider font-montserrat">Proses</span>';
                            break;
                        case 'selesai':
                            $icon = 'ph-check-square';
                            $title = $profil->status_pengerjaan_selesai ?? 'Pengerjaan Selesai';
                            $desc = 'Layanan selesai untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<span class="block text-xs font-bold text-white uppercase tracking-wider font-montserrat">' . ($profil->status_pesanan_selesai ?? 'Selesai') . '</span>';
                            break;
                        case 'ditolak':
                            $icon = 'ph-x-circle';
                            $title = $profil->status_pesanan_ditolak ?? 'Pesanan Ditolak';
                            $desc = 'Pesanan #' . $order->kode_pesanan . ' ditolak';
                            $rightCol = '<span class="block text-xs font-bold text-gray-500 uppercase tracking-wider font-montserrat">Ditolak</span>';
                            break;
                    }
                @endphp
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-white/[0.02] transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-white group-hover:text-[#ff6b00] group-hover:border-[#ff6b00]/30 transition-all shrink-0">
                            <i class="ph-bold {{ $icon }} text-xl"></i>
                        </div>
                        <div class="space-y-0.5 min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold font-montserrat text-white group-hover:text-[#ff6b00] transition-colors truncate">{{ $title }}</h4>
                            <p class="text-[11px] text-gray-400 font-questrial truncate max-w-sm sm:max-w-md">{{ $desc }}</p>
                        </div>
                    </div>
                    
                    <div class="text-left sm:text-right space-y-0.5 ml-15 sm:ml-0 shrink-0">
                        {!! $rightCol !!}
                        <span class="block text-[10px] text-gray-500 font-questrial">{{ $order->updated_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-8 text-center text-gray-500 text-xs font-questrial">
                Belum ada riwayat aktivitas pengerjaan.
            </div>
        @endif
    </div>
</div>

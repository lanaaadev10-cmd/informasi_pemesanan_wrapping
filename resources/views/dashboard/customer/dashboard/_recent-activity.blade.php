<!-- Aktivitas Terakhir Section -->
<div class="space-y-6">
    <h3 class="text-lg font-bold text-white tracking-tight">Aktivitas Terakhir</h3>
    
    <div class="bg-[#111111] border border-white/5 rounded-3xl divide-y divide-white/5 overflow-hidden shadow-xl">
        @if($latestOrders->isNotEmpty())
            @foreach($latestOrders as $order)
                @php
                    $icon = 'ph-credit-card';
                    $iconBg = 'bg-[#f2994a]/10 border-[#f2994a]/20 text-[#f2994a]';
                    $title = 'Pesanan';
                    $desc = '';
                    $rightCol = '';

                    switch($order->status) {
                        case 'menunggu_konfirmasi_admin':
                            $icon = 'ph-clock';
                            $iconBg = 'bg-yellow-500/10 border-yellow-500/20 text-yellow-500';
                            $title = 'Menunggu Konfirmasi';
                            $desc = 'Pesanan #' . $order->kode_pesanan . ' (' . ($order->form->model_kendaraan ?? 'Kendaraan') . ') sedang ditinjau oleh admin';
                            $rightCol = '<span class="block text-xs font-extrabold text-white font-mono">Rp ' . number_format($order->total_harga, 0, ',', '.') . '</span>';
                            break;
                        case 'menunggu_pembayaran':
                            $icon = 'ph-wallet';
                            $iconBg = 'bg-blue-500/10 border-blue-500/20 text-blue-500';
                            $title = 'Menunggu Pembayaran';
                            $desc = 'Silakan lakukan pembayaran untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<a href="' . route('pesanan.show', $order->id_pesanan) . '" class="block text-xs font-extrabold text-blue-400 hover:underline">Bayar Sekarang</a>';
                            break;
                        case 'menunggu_verifikasi_pembayaran':
                            $icon = 'ph-hourglass-high';
                            $iconBg = 'bg-orange-500/10 border-orange-500/20 text-orange-500';
                            $title = 'Verifikasi Pembayaran';
                            $desc = 'Bukti pembayaran pesanan #' . $order->kode_pesanan . ' sedang diverifikasi';
                            $rightCol = '<span class="block text-xs font-extrabold text-orange-400 font-mono">Menunggu</span>';
                            break;
                        case 'dikonfirmasi':
                            $icon = 'ph-check-circle';
                            $iconBg = 'bg-green-500/10 border-green-500/20 text-green-500';
                            $title = 'Pesanan Dikonfirmasi';
                            $desc = 'Pembayaran terverifikasi untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<span class="block text-xs font-extrabold text-green-500 font-mono">Lunas</span>';
                            break;
                        case 'sedang_diproses':
                            $icon = 'ph-wrench';
                            $iconBg = 'bg-amber-500/10 border-amber-500/20 text-amber-500';
                            $title = 'Sedang Dikerjakan';
                            $desc = 'Kendaraan pesanan #' . $order->kode_pesanan . ' (' . ($order->form->model_kendaraan ?? 'Kendaraan') . ') sedang dikerjakan';
                            $rightCol = '<span class="block text-xs font-extrabold text-amber-500 font-mono uppercase">Proses</span>';
                            break;
                        case 'selesai':
                            $icon = 'ph-check-square';
                            $iconBg = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-500';
                            $title = 'Pengerjaan Selesai';
                            $desc = 'Layanan selesai untuk pesanan #' . $order->kode_pesanan;
                            $rightCol = '<span class="block text-xs font-extrabold text-emerald-500 font-mono uppercase">Selesai</span>';
                            break;
                        case 'ditolak':
                            $icon = 'ph-x-circle';
                            $iconBg = 'bg-red-500/10 border-red-500/20 text-red-500';
                            $title = 'Pesanan Ditolak';
                            $desc = 'Pesanan #' . $order->kode_pesanan . ' ditolak';
                            $rightCol = '<span class="block text-xs font-extrabold text-red-500 font-mono uppercase">Ditolak</span>';
                            break;
                    }
                @endphp
                <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-white/[0.01] transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center">
                            <i class="ph-bold {{ $icon }} text-lg"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-white">{{ $title }}</h4>
                            <p class="text-[10px] text-gray-400 font-light">{{ $desc }}</p>
                        </div>
                    </div>
                    
                    <div class="text-left sm:text-right space-y-0.5 ml-14 sm:ml-0">
                        {!! $rightCol !!}
                        <span class="block text-[9px] text-gray-500 font-mono">{{ $order->updated_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        @else
            <div class="p-8 text-center text-gray-500 text-xs font-light">
                Belum ada riwayat aktivitas pengerjaan.
            </div>
        @endif
    </div>
</div>

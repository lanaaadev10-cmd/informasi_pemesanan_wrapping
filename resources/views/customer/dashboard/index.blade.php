@extends('layouts.dashboard_customer')

@section('title', 'User Dashboard')

@section('content')
<div class="space-y-10" data-aos="fade-up" data-aos-duration="1000">
    
    <!-- 1. Halo Greeting Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">
                {{ $profil->dashboard_title ?? 'Halo' }}, {{ Auth::user()->name }}
            </h1>
            <p class="text-xs text-gray-400 font-light mt-1">
                {{ $profil->dashboard_subtitle ?? 'Pantau status pengerjaan kendaraan premium Anda di sini.' }}
            </p>
        </div>
    </div>

    <!-- 2. Central Top row Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Column 1: Active Order Card (Dynamic) -->
        @if($latestOrder && !in_array($latestOrder->status, ['selesai', 'ditolak', 'dibatalkan']))
            @php
                $statusColorClass = match((string) $latestOrder->status) {
                    'menunggu_konfirmasi_admin' => 'bg-yellow-500 text-yellow-500',
                    'menunggu_pembayaran' => 'bg-blue-500 text-blue-500',
                    'menunggu_verifikasi_pembayaran' => 'bg-orange-500 text-orange-500',
                    'dikonfirmasi' => 'bg-emerald-500 text-emerald-500',
                    'sedang_diproses' => 'bg-amber-500 text-amber-500 animate-pulse',
                    default => 'bg-gray-500 text-gray-500'
                };
                $statusTextClass = match((string) $latestOrder->status) {
                    'menunggu_konfirmasi_admin' => 'text-yellow-500',
                    'menunggu_pembayaran' => 'text-blue-500',
                    'menunggu_verifikasi_pembayaran' => 'text-orange-500',
                    'dikonfirmasi' => 'text-emerald-500',
                    'sedang_diproses' => 'text-amber-500',
                    default => 'text-gray-500'
                };
                
                $estimasiSelesai = $latestOrder->form && $latestOrder->form->jadwal_pengerjaan 
                    ? $latestOrder->form->jadwal_pengerjaan->addDays(5)->translatedFormat('d M Y') 
                    : $latestOrder->created_at->addDays(5)->translatedFormat('d M Y');
            @endphp
            <div class="md:col-span-2 bg-[#111111] border border-white/5 rounded-3xl p-8 flex flex-col justify-between hover:border-[#f2994a]/20 transition-all duration-300 shadow-xl relative overflow-hidden">
                <!-- Glowing Accent Background -->
                <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-[#f2994a]/5 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="flex justify-between items-start w-full border-b border-white/5 pb-6 mb-6">
                    <div class="space-y-1">
                        <span class="inline-flex items-center gap-1.5 bg-[#f2994a]/10 border border-[#f2994a]/25 text-[#f2994a] text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg font-mono">
                            Active Order
                        </span>
                        <h3 class="text-2xl font-extrabold text-white mt-4">{{ $latestOrder->form->model_kendaraan ?? 'Kendaraan' }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="w-2 h-2 rounded-full {{ $statusColorClass }}"></span>
                            <span class="text-xs font-bold {{ $statusTextClass }} font-mono uppercase tracking-wider">{{ $latestOrder->label_status }}</span>
                        </div>
                    </div>

                    <div class="text-right space-y-1">
                        <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest">Estimasi Selesai</span>
                        <span class="block text-sm font-extrabold text-white font-mono">{{ $estimasiSelesai }}</span>
                    </div>
                </div>

                <!-- Parameters Grid -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Service</span>
                        <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->details->first()->layanan->nama_layanan ?? '-' }}</span>
                    </div>
                    <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Color</span>
                        <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->form->warna_kendaraan ?? '-' }}</span>
                    </div>
                    <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Material</span>
                        <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->details->first()->layanan->tipe_paket ?? 'Avery Dennison' }}</span>
                    </div>
                </div>
            </div>
        @else
            <!-- Column 1: No Active Order Placeholder Card -->
            <div class="md:col-span-2 bg-[#111111] border border-white/5 rounded-3xl p-8 flex flex-col justify-between hover:border-[#f2994a]/20 transition-all duration-300 shadow-xl relative overflow-hidden">
                <!-- Glowing Accent Background -->
                <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-[#f2994a]/5 rounded-full blur-[80px] pointer-events-none"></div>

                <div class="flex flex-col items-center justify-center text-center py-10 space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-center text-[#f2994a]">
                        <i class="ph-bold ph-car text-3xl"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-bold text-white">
                            {{ $profil->dashboard_empty_title ?? 'Tidak Ada Pengerjaan Aktif' }}
                        </h3>
                        <p class="text-xs text-gray-400 max-w-sm font-light leading-relaxed">
                            {{ $profil->dashboard_empty_desc ?? 'Anda tidak memiliki pesanan kendaraan yang sedang dikerjakan saat ini. Silakan buat pesanan baru melalui katalog layanan kami.' }}
                        </p>
                    </div>
                    <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95">
                        <i class="ph-bold ph-palette text-xs"></i> Pilih Layanan
                    </a>
                </div>
            </div>
        @endif

        <!-- Column 2: Member Status Card (Mockup Matching) -->
        <div class="bg-gradient-to-br from-[#f2994a]/25 to-[#e28a44]/5 border border-[#f2994a]/30 rounded-3xl p-8 flex flex-col justify-between hover:border-[#f2994a]/50 transition-all duration-300 shadow-xl relative overflow-hidden">
            <!-- Glowing Circle background -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#f2994a]/20 rounded-full blur-[60px] pointer-events-none"></div>

            <div class="space-y-6">
                <!-- Tag & Icon -->
                <div class="flex justify-between items-center">
                    <span class="text-[9px] font-bold text-[#f2994a] uppercase tracking-widest font-mono">Member Status</span>
                    <div class="w-8 h-8 rounded-lg bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a]">
                        <i class="ph-bold ph-crown text-base"></i>
                    </div>
                </div>

                <!-- Main Status -->
                <div class="space-y-1">
                    <h4 class="text-2xl font-extrabold text-white">
                        {{ $profil->dashboard_member_title ?? 'Premium Gold' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                        {{ $profil->dashboard_member_desc ?? 'Satu langkah lagi menuju Platinum' }}
                    </p>
                </div>

                <!-- Progress Bar -->
                @php
                    $progressVal = $profil->dashboard_member_progress ?? 85;
                @endphp
                <div class="space-y-2 pt-2">
                    <div class="flex justify-between items-center text-[10px] font-bold">
                        <span class="text-gray-400 uppercase tracking-wider">Progress</span>
                        <span class="text-white font-mono">{{ $progressVal }}%</span>
                    </div>
                    <div class="w-full h-2 bg-white/5 border border-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#e28a44] to-[#f2994a] rounded-full shadow-[0_0_10px_rgba(242,153,74,0.3)]" style="width: {{ $progressVal }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Benefits Footer -->
            <div class="pt-6 border-t border-white/5 mt-6">
                <p class="text-[9px] text-gray-400 leading-relaxed font-light">
                    @if($profil->dashboard_member_benefits)
                        {!! $profil->dashboard_member_benefits !!}
                    @else
                        Keuntungan Anda: <span class="text-[#f2994a] font-bold">Diskon 15% Detailing</span> &amp; Prioritas Antrean.
                    @endif
                </p>
            </div>
        </div>

    </div>

    <!-- 3. Layanan Cepat Section -->
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-white tracking-tight">Layanan Cepat</h3>
            <a href="{{ route('katalog.user') }}" class="text-[10px] font-bold text-[#f2994a] uppercase tracking-widest hover:underline flex items-center gap-1">
                Lihat Semua Layanan <i class="ph-bold ph-caret-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                    <i class="ph-bold {{ $profil->dashboard_service_1_icon ?? 'ph-shield' }} text-xl"></i>
                </div>
                <div class="space-y-1.5 mt-6">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider">
                        {{ $profil->dashboard_service_1_title ?? 'Paint Protection Film' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                        {{ $profil->dashboard_service_1_desc ?? 'Perlindungan cat maksimal dari goresan dan kotoran.' }}
                    </p>
                </div>
            </a>

            <!-- Card 2 -->
            <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                    <i class="ph-bold {{ $profil->dashboard_service_2_icon ?? 'ph-palette' }} text-xl"></i>
                </div>
                <div class="space-y-1.5 mt-6">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider">
                        {{ $profil->dashboard_service_2_title ?? 'Color Change' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                        {{ $profil->dashboard_service_2_desc ?? 'Transformasi warna total dengan bahan premium.' }}
                    </p>
                </div>
            </a>

            <!-- Card 3 -->
            <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                    <i class="ph-bold {{ $profil->dashboard_service_3_icon ?? 'ph-armchair' }} text-xl"></i>
                </div>
                <div class="space-y-1.5 mt-6">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider">
                        {{ $profil->dashboard_service_3_title ?? 'Interior Styling' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                        {{ $profil->dashboard_service_3_desc ?? 'Sentuhan elegan untuk kenyamanan kabin Anda.' }}
                    </p>
                </div>
            </a>

            <!-- Card 4 -->
            <a href="{{ route('katalog.user') }}" class="group bg-white/[0.01] border border-white/5 p-6 rounded-2xl hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between min-h-[160px] shadow-lg">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 group-hover:text-[#f2994a] group-hover:bg-[#f2994a]/10 transition-all">
                    <i class="ph-bold {{ $profil->dashboard_service_4_icon ?? 'ph-sparkles' }} text-xl"></i>
                </div>
                <div class="space-y-1.5 mt-6">
                    <h4 class="text-xs font-black text-white uppercase tracking-wider">
                        {{ $profil->dashboard_service_4_title ?? 'Detailing' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 font-light leading-relaxed">
                        {{ $profil->dashboard_service_4_desc ?? 'Pembersihan mendalam untuk kilau sempurna.' }}
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- 4. Aktivitas Terakhir Section -->
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
    
</div>
@endsection
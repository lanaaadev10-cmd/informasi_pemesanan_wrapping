<!-- Active Order Card (Dynamic) -->
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
                <h3 class="text-2xl font-extrabold text-white mt-4">{{ $latestOrder->form?->model_kendaraan ?? 'Kendaraan' }}</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-2 h-2 rounded-full {{ $statusColorClass }}"></span>
                    <span class="text-xs font-bold {{ $statusTextClass }} font-mono uppercase tracking-wider">{{ $latestOrder->label_status }}</span>
                </div>
            </div>

            <div class="text-right space-y-1">
                <span class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $profil->label_estimasi_selesai ?? 'Estimasi Selesai' }}</span>
                <span class="block text-sm font-extrabold text-white font-mono">{{ $estimasiSelesai }}</span>
            </div>
        </div>

        <!-- Parameters Grid -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Service</span>
                <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->details?->first()?->layanan?->nama_layanan ?? '-' }}</span>
            </div>
            <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Color</span>
                <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->form?->warna_kendaraan ?? '-' }}</span>
            </div>
            <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Material</span>
                <span class="text-xs font-bold text-white mt-2">{{ $latestOrder->details?->first()?->layanan?->tipe_paket ?? 'Avery Dennison' }}</span>
            </div>
        </div>
    </div>
@else
    <!-- No Active Order Placeholder Card -->
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
                <i class="ph-bold ph-palette text-xs"></i> {{ $profil->cta_pilih_layanan ?? 'Pilih Layanan' }}
            </a>
        </div>
    </div>
@endif

<!-- Active Order / Booking Card (Dynamic - White, Black, Orange Aesthetic) -->
@php
    $activePesanan = ($latestOrder && !in_array($latestOrder->status, ['selesai', 'ditolak'])) ? $latestOrder : null;
    $activeBooking = (!$activePesanan && isset($upcomingBookings) && $upcomingBookings->count() > 0) ? $upcomingBookings->first() : null;
@endphp

@if($activePesanan)
    @php
        $estimasiSelesai = $activePesanan->form && $activePesanan->form->jadwal_pengerjaan 
            ? $activePesanan->form->jadwal_pengerjaan->addDays(5)->translatedFormat('d M Y') 
            : $activePesanan->created_at->addDays(5)->translatedFormat('d M Y');
    @endphp
    <div class="bg-[#121212] border border-white/10 rounded-[28px] p-6 sm:p-8 flex flex-col justify-between hover:border-[#ff6b00]/40 transition-all duration-300 shadow-xl relative overflow-hidden">
        <!-- Glowing Orange Accent Background -->
        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-[#ff6b00]/10 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full border-b border-white/10 pb-6 mb-6 gap-4">
            <div class="space-y-1.5">
                <div class="text-xs font-montserrat font-bold text-[#ff6b00]">
                    Pesanan Aktif &bull; #{{ $activePesanan->kode_pesanan }}
                </div>
                
                <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white mt-1.5">{{ $activePesanan->form?->model_kendaraan ?? 'Kendaraan Pelanggan' }}</h3>
                
                <!-- Clean Text Status (No Pill Badge) -->
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Status Pengerjaan:</span>
                    <span class="text-xs font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider">{{ $activePesanan->label_status }}</span>
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-3 w-full sm:w-auto">
                <div class="text-left sm:text-right space-y-1">
                    <span class="block text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-widest">{{ $profil->label_estimasi_selesai ?? 'Estimasi Selesai' }}</span>
                    <span class="block text-sm sm:text-base font-audiowide font-bold text-white">{{ $estimasiSelesai }}</span>
                </div>
                <a href="{{ route('pesanan.show', $activePesanan->id_pesanan) }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-5 py-2.5 bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 hover:border-[#ff6b00] rounded-xl text-xs font-montserrat font-bold text-white transition-all shadow-md active:scale-95">
                    <span>Lihat Rincian</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Parameters Grid (Responsive 1 col mobile, 3 cols tablet & desktop) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 font-questrial">
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Layanan / Paket</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ $activePesanan->details?->first()?->layanan?->nama_layanan ?? '-' }}</span>
            </div>
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Warna Kendaraan</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ $activePesanan->form?->warna_kendaraan ?? '-' }}</span>
            </div>
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Tingkat Bahan / Material</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ $activePesanan->details?->first()?->layanan?->tipe_paket ?? 'Avery Dennison' }}</span>
            </div>
        </div>
    </div>
@elseif($activeBooking)
    @php
        $bkStatus = $activeBooking->status instanceof \App\Enums\BookingStatus 
            ? $activeBooking->status 
            : \App\Enums\BookingStatus::tryFrom($activeBooking->status);
        $bkStatusVal = $bkStatus?->value ?? (string) $activeBooking->status;
        $bkLabel = $bkStatus ? $bkStatus->label() : ucfirst(str_replace('_', ' ', $bkStatusVal));
        
        $jadwalPengerjaan = $activeBooking->booking_date 
            ? $activeBooking->booking_date->translatedFormat('d M Y') 
            : '-';
    @endphp
    <div class="bg-[#121212] border border-white/10 rounded-[28px] p-6 sm:p-8 flex flex-col justify-between hover:border-[#ff6b00]/40 transition-all duration-300 shadow-xl relative overflow-hidden">
        <!-- Glowing Orange Accent Background -->
        <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-[#ff6b00]/10 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full border-b border-white/10 pb-6 mb-6 gap-4">
            <div class="space-y-1.5">
                <div class="text-xs font-montserrat font-bold text-[#ff6b00]">
                    Booking Aktif &bull; {{ $activeBooking->booking_code }}
                </div>
                
                <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white mt-1.5">{{ $activeBooking->vehicle_name ?: 'Kendaraan Pelanggan' }}</h3>
                
                <!-- Clean Text Status (No Pill Badge) -->
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Status Jadwal:</span>
                    <span class="text-xs font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider">{{ $bkLabel }}</span>
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-3 w-full sm:w-auto">
                <div class="text-left sm:text-right space-y-1">
                    <span class="block text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-widest">Jadwal Pengerjaan</span>
                    <span class="block text-sm sm:text-base font-audiowide font-bold text-[#ff6b00]">{{ $jadwalPengerjaan }} &bull; {{ $activeBooking->booking_time ?: '09:00' }} WIB</span>
                </div>
                <a href="{{ route('booking.show', $activeBooking->id) }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-5 py-2.5 bg-[#ff6b00] hover:bg-[#ea580c] rounded-xl text-xs font-montserrat font-bold text-white transition-all shadow-[0_4px_15px_rgba(255,107,0,0.35)] active:scale-95">
                    <span>Lihat Rincian Booking</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Parameters Grid (Responsive 1 col mobile, 3 cols tablet & desktop) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 font-questrial">
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Layanan / Paket</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ $activeBooking->layanan?->nama_layanan ?? '-' }}</span>
            </div>
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Warna / Plat Nomor</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ $activeBooking->vehicle_color ?: '-' }} {{ $activeBooking->vehicle_license ? '(' . $activeBooking->vehicle_license . ')' : '' }}</span>
            </div>
            <div class="bg-black/60 border border-white/5 p-4 rounded-2xl flex flex-col justify-between">
                <span class="text-[10px] font-montserrat font-semibold text-gray-400 uppercase tracking-wider">Skema Pembayaran</span>
                <span class="text-xs sm:text-sm font-bold text-white mt-1">{{ strtoupper($activeBooking->payment_type ?? 'DP') }} ({{ $activeBooking->payment?->status ? ucfirst($activeBooking->payment->status) : 'Menunggu Bayar' }})</span>
            </div>
        </div>
    </div>
@endif

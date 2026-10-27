{{-- ============================================
    BAGIAN: Tampilan Ketika Semua Tagihan Lunas (Zero Unpaid)
============================================ --}}
@php
    $totalBookings = \App\Models\Booking::where('user_id', auth()->id())->count();
    $activeBookings = \App\Models\Booking::where('user_id', auth()->id())
        ->whereIn('status', ['approved', 'in_progress', 'confirmed'])
        ->count();
    $totalSpent = \App\Models\Booking::where('user_id', auth()->id())
        ->whereIn('status', ['approved', 'in_progress', 'completed', 'confirmed'])
        ->join('layanans', 'bookings.layanan_id', '=', 'layanans.id_layanan')
        ->sum('layanans.harga');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

    {{-- ── KOLOM KIRI (60%) ─────────────────────────── --}}
    <div class="lg:col-span-3 space-y-5">

        {{-- Judul tanpa icon/emoji --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm">
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-slate-900 mb-2">
                Semua Tagihan Lunas
            </h3>
            <p class="text-xs sm:text-sm font-questrial text-slate-500 leading-relaxed">
                Tidak ada pembayaran tertunda saat ini. Kendaraan Anda aman dalam antrean pengerjaan workshop kami.
            </p>
        </div>

        {{-- Kartu transaksi terakhir --}}
        @if(isset($latestPaidBooking) && $latestPaidBooking)
            <div class="bg-white border border-slate-200/80 hover:border-[#FF6B00]/40 rounded-3xl p-5 sm:p-6 text-left transition-all shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-slate-400">
                        Transaksi Terakhir
                    </span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $latestPaidBooking->booking_code }}</span>
                </div>

                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <h4 class="text-base font-audiowide font-bold text-slate-900">
                            {{ $latestPaidBooking->layanan?->nama_layanan ?? 'Layanan Wrapping' }}
                        </h4>
                        <p class="text-xs font-questrial text-[#FF6B00] mt-1">
                            {{ $latestPaidBooking->vehicle_name }}
                            &bull; {{ $latestPaidBooking->booking_date?->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] font-montserrat font-bold text-slate-400 uppercase tracking-wider block">Biaya</span>
                        <span class="text-lg font-black text-slate-900">
                            Rp {{ number_format($latestPaidBooking->layanan?->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2.5">
                    <a href="{{ route('booking.invoice', $latestPaidBooking->id) }}"
                       target="_blank"
                       class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
                        <i class="ph-bold ph-file-pdf text-sm"></i>
                        Unduh Invoice PDF
                    </a>
                    <a href="{{ route('booking.show', $latestPaidBooking->id) }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-95">
                        Detail
                    </a>
                </div>
            </div>

        @elseif(isset($latestPaidPesanan) && $latestPaidPesanan)
            <div class="bg-white border border-slate-200/80 hover:border-[#FF6B00]/40 rounded-3xl p-5 sm:p-6 text-left transition-all shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-slate-400">
                        Transaksi Terakhir
                    </span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $latestPaidPesanan->kode_pesanan }}</span>
                </div>

                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <h4 class="text-base font-audiowide font-bold text-slate-900">
                            {{ $latestPaidPesanan->details->first()?->layanan->nama_layanan ?? 'Pesanan Wrapping' }}
                        </h4>
                        <p class="text-xs font-questrial text-[#FF6B00] mt-1">
                            {{ $latestPaidPesanan->form?->model_kendaraan ?? 'Kendaraan' }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] font-montserrat font-bold text-slate-400 uppercase tracking-wider block">Total</span>
                        <span class="text-lg font-black text-slate-900">
                            Rp {{ number_format($latestPaidPesanan->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2.5">
                    <a href="{{ route('pesanan.invoice', $latestPaidPesanan->id_pesanan) }}"
                       target="_blank"
                       class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
                        <i class="ph-bold ph-file-pdf text-sm"></i>
                        Unduh Invoice PDF
                    </a>
                    <a href="{{ route('pesanan.show', $latestPaidPesanan->id_pesanan) }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-95">
                        Detail
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- ── KOLOM KANAN (40%) ─────────────────────────── --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Card: Buat Booking Baru --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm hover:border-[#FF6B00]/40 transition-all">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-200 text-[#FF6B00] flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-calendar-plus text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-montserrat font-bold text-slate-900">Buat Booking Baru</h4>
                    <p class="text-[11px] font-questrial text-slate-500 mt-0.5 leading-relaxed">
                        Pesan layanan wrapping atau variasi kendaraan berikutnya.
                    </p>
                </div>
            </div>
            <a href="{{ route('booking.create') }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-95">
                Mulai Booking
            </a>
        </div>

        {{-- Card: Riwayat Transaksi --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm hover:border-slate-300 transition-all">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-receipt text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-montserrat font-bold text-slate-900">Riwayat Transaksi</h4>
                    <p class="text-[11px] font-questrial text-slate-500 mt-0.5 leading-relaxed">
                        Lihat semua riwayat pembayaran dan booking Anda.
                    </p>
                </div>
            </div>
            <a href="{{ route('transaksi.index') }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-95">
                Lihat Semua Riwayat
            </a>
        </div>

        {{-- Mini Stats --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm">
            <h4 class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-slate-400 mb-3">
                Ringkasan Akun
            </h4>
            <div class="grid grid-cols-3 gap-3 divide-x divide-slate-100">
                <div class="text-center">
                    <div class="text-xl font-audiowide font-bold text-[#FF6B00]">{{ $activeBookings }}</div>
                    <div class="text-[9px] font-montserrat font-bold text-slate-400 uppercase tracking-wide mt-1">Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-xl font-audiowide font-bold text-slate-900">{{ $totalBookings }}</div>
                    <div class="text-[9px] font-montserrat font-bold text-slate-400 uppercase tracking-wide mt-1">Total</div>
                </div>
                <div class="text-center">
                    <div class="text-sm font-audiowide font-bold text-slate-900 leading-tight">
                        {{ $totalSpent >= 1000000 ? 'Rp ' . number_format($totalSpent / 1000000, 1, ',', '.') . 'Jt' : 'Rp ' . number_format($totalSpent / 1000, 0, ',', '.') . 'K' }}
                    </div>
                    <div class="text-[9px] font-montserrat font-bold text-slate-400 uppercase tracking-wide mt-1">Transaksi</div>
                </div>
            </div>
        </div>

    </div>
</div>

@extends('layouts.dashboard-customer')

@section('title', 'Bayar Tagihan - ' . ($profil->nama_perusahaan ?? 'Dantie Stiker'))

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto pb-16" data-aos="fade-up" data-aos-duration="600">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('transaksi.index') }}" class="text-xs font-montserrat font-bold text-gray-400 hover:text-[#FF6B00] flex items-center gap-1 transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Hub Transaksi
                </a>
                <span class="text-gray-600">/</span>
                <span class="text-xs font-montserrat font-bold text-[#FF6B00]">Pusat Tagihan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-audiowide font-bold text-white tracking-wide flex items-center gap-3">
                <i class="ph-bold ph-credit-card text-[#FF6B00]"></i>
                Bayar Tagihan
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-gray-400 mt-1 leading-relaxed">
                Kelola pembayaran uang muka (DP), pelunasan booking, dan unggah bukti transfer perbankan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('transaksi.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-white transition-all active:scale-95">
                <i class="ph-bold ph-receipt text-base text-[#FF6B00]"></i>
                <span>Semua Transaksi</span>
            </a>
        </div>
    </div>

    {{-- KONDISI 1: SEMUA TAGIHAN LUNAS (ZERO UNPAID) — Layout 2 Kolom --}}
    @if($totalUnpaidCount === 0)
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
                <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-xl">
                    <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white mb-2">
                        Semua Tagihan Lunas
                    </h3>
                    <p class="text-xs sm:text-sm font-questrial text-gray-400 leading-relaxed">
                        Tidak ada pembayaran tertunda saat ini. Kendaraan Anda aman dalam antrean pengerjaan workshop kami.
                    </p>
                </div>

                {{-- Kartu transaksi terakhir --}}
                @if(isset($latestPaidBooking) && $latestPaidBooking)
                    <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/30 rounded-3xl p-5 sm:p-6 text-left transition-all shadow-xl">
                        <div class="flex items-center justify-between border-b border-white/5 pb-3 mb-4">
                            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-500">
                                Transaksi Terakhir
                            </span>
                            <span class="text-xs font-mono font-bold text-gray-400">{{ $latestPaidBooking->booking_code }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 mb-5">
                            <div>
                                <h4 class="text-base font-audiowide font-bold text-white">
                                    {{ $latestPaidBooking->layanan?->nama_layanan ?? 'Layanan Wrapping' }}
                                </h4>
                                <p class="text-xs font-questrial text-[#FF6B00] mt-1">
                                    {{ $latestPaidBooking->vehicle_name }}
                                    &bull; {{ $latestPaidBooking->booking_date?->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] font-montserrat font-bold text-gray-500 uppercase tracking-wider block">Biaya</span>
                                <span class="text-lg font-black text-white">
                                    Rp {{ number_format($latestPaidBooking->layanan?->harga ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-2.5">
                            <a href="{{ route('booking.invoice', $latestPaidBooking->id) }}"
                               target="_blank"
                               class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_15px_rgba(255,107,0,0.25)] active:scale-95">
                                <i class="ph-bold ph-file-pdf text-sm"></i>
                                Unduh Invoice PDF
                            </a>
                            <a href="{{ route('booking.show', $latestPaidBooking->id) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95">
                                Detail
                            </a>
                        </div>
                    </div>

                @elseif(isset($latestPaidPesanan) && $latestPaidPesanan)
                    <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/30 rounded-3xl p-5 sm:p-6 text-left transition-all shadow-xl">
                        <div class="flex items-center justify-between border-b border-white/5 pb-3 mb-4">
                            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-500">
                                Transaksi Terakhir
                            </span>
                            <span class="text-xs font-mono font-bold text-gray-400">{{ $latestPaidPesanan->kode_pesanan }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-4 mb-5">
                            <div>
                                <h4 class="text-base font-audiowide font-bold text-white">
                                    {{ $latestPaidPesanan->details->first()?->layanan->nama_layanan ?? 'Pesanan Wrapping' }}
                                </h4>
                                <p class="text-xs font-questrial text-[#FF6B00] mt-1">
                                    {{ $latestPaidPesanan->form?->model_kendaraan ?? 'Kendaraan' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] font-montserrat font-bold text-gray-500 uppercase tracking-wider block">Total</span>
                                <span class="text-lg font-black text-white">
                                    Rp {{ number_format($latestPaidPesanan->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-2.5">
                            <a href="{{ route('pesanan.invoice', $latestPaidPesanan->id_pesanan) }}"
                               target="_blank"
                               class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_15px_rgba(255,107,0,0.25)] active:scale-95">
                                <i class="ph-bold ph-file-pdf text-sm"></i>
                                Unduh Invoice PDF
                            </a>
                            <a href="{{ route('pesanan.show', $latestPaidPesanan->id_pesanan) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95">
                                Detail
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── KOLOM KANAN (40%) ─────────────────────────── --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Card: Buat Booking Baru --}}
                <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 shadow-xl hover:border-[#FF6B00]/30 transition-all">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 text-[#FF6B00] flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-calendar-plus text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-montserrat font-bold text-white">Buat Booking Baru</h4>
                            <p class="text-[11px] font-questrial text-gray-500 mt-0.5 leading-relaxed">
                                Pesan layanan wrapping atau variasi kendaraan berikutnya.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('booking.create') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95">
                        Mulai Booking
                    </a>
                </div>

                {{-- Card: Riwayat Transaksi --}}
                <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 shadow-xl hover:border-white/20 transition-all">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 text-gray-400 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-receipt text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-montserrat font-bold text-white">Riwayat Transaksi</h4>
                            <p class="text-[11px] font-questrial text-gray-500 mt-0.5 leading-relaxed">
                                Lihat semua riwayat pembayaran dan booking Anda.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('transaksi.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95">
                        Lihat Semua Riwayat
                    </a>
                </div>

                {{-- Mini Stats --}}
                <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 shadow-xl">
                    <h4 class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-gray-500 mb-3">
                        Ringkasan Akun
                    </h4>
                    <div class="grid grid-cols-3 gap-3 divide-x divide-white/5">
                        <div class="text-center">
                            <div class="text-xl font-audiowide font-bold text-[#FF6B00]">{{ $activeBookings }}</div>
                            <div class="text-[9px] font-montserrat font-bold text-gray-500 uppercase tracking-wide mt-1">Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-audiowide font-bold text-white">{{ $totalBookings }}</div>
                            <div class="text-[9px] font-montserrat font-bold text-gray-500 uppercase tracking-wide mt-1">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm font-audiowide font-bold text-white leading-tight">
                                {{ $totalSpent >= 1000000 ? 'Rp ' . number_format($totalSpent / 1000000, 1, ',', '.') . 'Jt' : 'Rp ' . number_format($totalSpent / 1000, 0, ',', '.') . 'K' }}
                            </div>
                            <div class="text-[9px] font-montserrat font-bold text-gray-500 uppercase tracking-wide mt-1">Transaksi</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @else
        {{-- KONDISI 2: ADA TAGIHAN YANG HARUS DIBAYAR --}}
        
        {{-- Banner Pengingat Tagihan Aktif --}}
        <div class="relative overflow-hidden rounded-3xl bg-[#141416] border border-[#FF6B00]/30 p-5 sm:p-6 shadow-xl backdrop-blur-md">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#FF6B00]/15 border border-[#FF6B00]/30 text-[#FF6B00] flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-bell-ringing text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-montserrat font-bold text-white">
                        Menunggu Pembayaran ({{ $totalUnpaidCount }} Tagihan)
                    </h3>
                    <p class="text-xs font-questrial text-gray-300 mt-0.5 leading-relaxed">
                        Silakan selesaikan pembayaran ke rekening bank resmi di bawah ini, kemudian unggah bukti transfer agar pengerjaan segera diproses.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kotak Rekening Resmi Workshop --}}
        <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 sm:p-7 shadow-xl">
            <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-white/5">
                <i class="ph-bold ph-bank text-[#FF6B00] text-xl"></i>
                <h3 class="text-sm font-montserrat font-bold text-white uppercase tracking-wider">
                    Rekening Pembayaran Resmi Workshop
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Bank BCA --}}
                <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-widest block">Bank Central Asia (BCA)</span>
                        <span class="text-lg font-mono font-black text-white tracking-widest mt-0.5 block">123-456-7890</span>
                        <span class="text-[10px] font-questrial text-gray-500">a/n Dantie Stiker Workshop</span>
                    </div>
                    <button type="button"
                            onclick="copyText('1234567890', this)"
                            class="px-3 py-2 rounded-xl bg-white/5 hover:bg-[#FF6B00] hover:text-black border border-white/10 text-xs font-montserrat font-bold text-gray-300 transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="ph-bold ph-copy text-sm"></i>
                        <span>Salin</span>
                    </button>
                </div>

                {{-- Bank Mandiri --}}
                <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-widest block">Bank Mandiri</span>
                        <span class="text-lg font-mono font-black text-white tracking-widest mt-0.5 block">987-654-3210</span>
                        <span class="text-[10px] font-questrial text-gray-500">a/n Dantie Stiker Workshop</span>
                    </div>
                    <button type="button"
                            onclick="copyText('9876543210', this)"
                            class="px-3 py-2 rounded-xl bg-white/5 hover:bg-[#FF6B00] hover:text-black border border-white/10 text-xs font-montserrat font-bold text-gray-300 transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="ph-bold ph-copy text-sm"></i>
                        <span>Salin</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- DAFTAR TAGIHAN BOOKING JADWAL --}}
        @if($unpaidBookings->isNotEmpty())
            <div class="space-y-4">
                <h3 class="text-xs font-montserrat font-extrabold uppercase tracking-widest text-[#FF6B00] flex items-center gap-2">
                    <i class="ph-bold ph-calendar-check text-base"></i>
                    Tagihan Booking Pengerjaan ({{ $unpaidBookings->count() }})
                </h3>

                @foreach($unpaidBookings as $booking)
                    @php
                        $isUploaded = $booking->status === \App\Enums\BookingStatus::PAYMENT_UPLOADED || $booking->status === 'payment_uploaded';
                    @endphp
                    <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 sm:p-6 shadow-xl relative overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-4 mb-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-sm font-audiowide font-bold text-white">{{ $booking->booking_code }}</span>
                                    @include('customer.booking.partials.status-badge', ['statusValue' => is_string($booking->status) ? $booking->status : $booking->status->value])
                                </div>
                                <p class="text-xs font-montserrat font-bold text-gray-300 mt-1">
                                    {{ $booking->layanan?->nama_layanan ?? 'Wrapping' }} &bull; <span class="text-[#FF6B00]">{{ $booking->vehicle_name }}</span>
                                </p>
                                <p class="text-[11px] font-questrial text-gray-400 mt-0.5">
                                    Jadwal: {{ $booking->booking_date?->translatedFormat('l, d F Y') }} Pukul {{ $booking->booking_time ?: '09:00' }} WIB
                                </p>
                            </div>

                            <div class="text-right self-start sm:self-auto">
                                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 block">Skema Pembayaran</span>
                                <span class="text-base font-black text-[#FF6B00] block mt-0.5 uppercase">
                                    {{ $booking->payment_type === 'dp' ? 'Uang Muka (DP Rp 100.000)' : 'Pelunasan Penuh' }}
                                </span>
                            </div>
                        </div>

                        {{-- Form Upload Bukti Transfer --}}
                        <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4">
                            @if($isUploaded)
                                <div class="flex items-center justify-between gap-3 text-xs text-amber-400">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-clock text-lg"></i>
                                        <span>Bukti transfer telah dikirim dan sedang diverifikasi oleh admin bengkel.</span>
                                    </div>
                                    <a href="{{ route('booking.show', $booking->id) }}" class="text-xs font-montserrat font-bold text-white underline hover:text-[#FF6B00]">
                                        Lihat Status
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('booking.upload-bukti', $booking->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-montserrat font-bold text-gray-400 uppercase tracking-wider mb-1.5">
                                            Upload Bukti Transfer (JPG/PNG/PDF, maks 5MB)
                                        </label>
                                        <input type="file"
                                               name="proof_file"
                                               accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png"
                                               required
                                               class="w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-montserrat file:font-bold file:bg-[#FF6B00] file:text-black hover:file:bg-[#E05D00] file:cursor-pointer bg-white/5 border border-white/10 rounded-xl p-1">
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 pt-2 sm:pt-0">
                                        <button type="submit"
                                                class="w-full sm:w-auto px-5 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
                                            Kirim Bukti
                                        </button>
                                        <a href="{{ route('booking.show', $booking->id) }}"
                                           class="px-4 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95">
                                            Detail
                                        </a>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- DAFTAR TAGIHAN PESANAN --}}
        @if($unpaidPesanans->isNotEmpty())
            <div class="space-y-4 mt-6">
                <h3 class="text-xs font-montserrat font-extrabold uppercase tracking-widest text-[#FF6B00] flex items-center gap-2">
                    <i class="ph-bold ph-folder-simple text-base"></i>
                    Tagihan Pesanan Order ({{ $unpaidPesanans->count() }})
                </h3>

                @foreach($unpaidPesanans as $pesanan)
                    <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-5 sm:p-6 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-sm font-audiowide font-bold text-white">{{ $pesanan->kode_pesanan }}</span>
                            <p class="text-xs font-montserrat font-bold text-gray-300 mt-1">
                                {{ $pesanan->details->first()?->layanan->nama_layanan ?? 'Pesanan Wrapping' }}
                            </p>
                            <span class="text-base font-black text-[#FF6B00] block mt-1">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-6 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
                                Selesaikan Pembayaran &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>

<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ph-bold ph-check text-emerald-400"></i> <span class="text-emerald-400">Tersalin!</span>';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }).catch(err => {
        alert('Gagal menyalin: ' + text);
    });
}
</script>
@endsection

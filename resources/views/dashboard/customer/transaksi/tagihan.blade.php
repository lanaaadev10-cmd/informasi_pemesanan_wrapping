@extends('layouts.dashboard-customer')

@section('title', 'Bayar Tagihan - ' . ($profil->nama_perusahaan ?? 'Dantie Stiker'))

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto pb-16" data-aos="fade-up" data-aos-duration="600">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('transaksi.index') }}" class="text-xs font-montserrat font-bold text-slate-500 hover:text-[#FF6B00] flex items-center gap-1 transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Hub Transaksi
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-montserrat font-bold text-[#FF6B00]">Pusat Tagihan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-audiowide font-bold text-slate-900 tracking-wide flex items-center gap-3">
                <i class="ph-bold ph-credit-card text-[#FF6B00]"></i>
                Bayar Tagihan
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-slate-500 mt-1 leading-relaxed">
                Kelola pembayaran uang muka (DP), pelunasan booking, dan unggah bukti transfer perbankan.
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('transaksi.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all active:scale-95">
                <i class="ph-bold ph-receipt text-base text-[#FF6B00]"></i>
                <span>Semua Transaksi</span>
            </a>
        </div>
    </div>

    {{-- KONDISI 1: SEMUA TAGIHAN LUNAS (ZERO UNPAID) — Layout 2 Kolom --}}
    @if($totalUnpaidCount === 0)
        @include('dashboard.customer.transaksi.partials._tagihan_lunas')
    @else
        {{-- KONDISI 2: ADA TAGIHAN YANG HARUS DIBAYAR --}}
        
        {{-- Banner Pengingat Tagihan Aktif --}}
        <div class="relative overflow-hidden rounded-3xl bg-orange-50/80 border border-orange-200 p-5 sm:p-6 shadow-sm backdrop-blur-md">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-100 border border-orange-300 text-[#FF6B00] flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-bell-ringing text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-montserrat font-bold text-slate-900">
                        Menunggu Pembayaran ({{ $totalUnpaidCount }} Tagihan)
                    </h3>
                    <p class="text-xs font-questrial text-slate-600 mt-0.5 leading-relaxed">
                        Silakan selesaikan pembayaran ke rekening bank resmi di bawah ini, kemudian unggah bukti transfer agar pengerjaan segera diproses.
                    </p>
                </div>
            </div>
        </div>

        {{-- Kotak Rekening Resmi Workshop --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-sm">
            <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
                <i class="ph-bold ph-bank text-[#FF6B00] text-xl"></i>
                <h3 class="text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider">
                    Rekening Pembayaran Resmi Workshop
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Bank BCA --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-widest block">Bank Central Asia (BCA)</span>
                        <span class="text-lg font-mono font-black text-slate-900 tracking-widest mt-0.5 block">123-456-7890</span>
                        <span class="text-[10px] font-questrial text-slate-500">a/n Dantie Stiker Workshop</span>
                    </div>
                    <button type="button"
                            onclick="copyText('1234567890', this)"
                            class="px-3 py-2 rounded-xl bg-white hover:bg-[#FF6B00] hover:text-white border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="ph-bold ph-copy text-sm"></i>
                        <span>Salin</span>
                    </button>
                </div>

                {{-- Bank Mandiri --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-widest block">Bank Mandiri</span>
                        <span class="text-lg font-mono font-black text-slate-900 tracking-widest mt-0.5 block">987-654-3210</span>
                        <span class="text-[10px] font-questrial text-slate-500">a/n Dantie Stiker Workshop</span>
                    </div>
                    <button type="button"
                            onclick="copyText('9876543210', this)"
                            class="px-3 py-2 rounded-xl bg-white hover:bg-[#FF6B00] hover:text-white border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all active:scale-95 flex items-center gap-1.5">
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
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-4">
                            <div>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-sm font-audiowide font-bold text-slate-900">{{ $booking->booking_code }}</span>
                                    @include('customer.booking.partials._lencana_status', ['statusValue' => is_string($booking->status) ? $booking->status : $booking->status->value])
                                </div>
                                <p class="text-xs font-montserrat font-bold text-slate-700 mt-1">
                                    {{ $booking->layanan?->nama_layanan ?? 'Wrapping' }} &bull; <span class="text-[#FF6B00]">{{ $booking->vehicle_name }}</span>
                                </p>
                                <p class="text-[11px] font-questrial text-slate-500 mt-0.5">
                                    Jadwal: {{ $booking->booking_date?->translatedFormat('l, d F Y') }} Pukul {{ $booking->booking_time ?: '09:00' }} WIB
                                </p>
                            </div>

                            <div class="text-right self-start sm:self-auto">
                                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500 block">Skema Pembayaran</span>
                                <span class="text-base font-black text-[#FF6B00] block mt-0.5 uppercase">
                                    {{ $booking->payment_type === 'dp' ? 'Uang Muka (DP Rp 100.000)' : 'Pelunasan Penuh' }}
                                </span>
                            </div>
                        </div>

                        {{-- Form Upload Bukti Transfer --}}
                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                            @if($isUploaded)
                                <div class="flex items-center justify-between gap-3 text-xs text-amber-700 bg-amber-50 p-3 rounded-xl border border-amber-200">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-clock text-lg"></i>
                                        <span>Bukti transfer telah dikirim dan sedang diverifikasi oleh admin bengkel.</span>
                                    </div>
                                    <a href="{{ route('booking.show', $booking->id) }}" class="text-xs font-montserrat font-bold text-slate-900 underline hover:text-[#FF6B00]">
                                        Lihat Status
                                    </a>
                                </div>
                            @else
                                <form action="{{ route('booking.upload-bukti', $booking->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-montserrat font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                            Upload Bukti Transfer (JPG/PNG/PDF, maks 5MB)
                                        </label>
                                        <input type="file"
                                               name="proof_file"
                                               accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png"
                                               required
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-montserrat file:font-bold file:bg-[#FF6B00] file:text-white hover:file:bg-[#E05D00] file:cursor-pointer bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 pt-2 sm:pt-0">
                                        <button type="submit"
                                                class="w-full sm:w-auto px-5 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
                                            Kirim Bukti
                                        </button>
                                        <a href="{{ route('booking.show', $booking->id) }}"
                                           class="px-4 py-3 bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-95">
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
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-sm font-audiowide font-bold text-slate-900">{{ $pesanan->kode_pesanan }}</span>
                            <p class="text-xs font-montserrat font-bold text-slate-700 mt-1">
                                {{ $pesanan->details->first()?->layanan->nama_layanan ?? 'Pesanan Wrapping' }}
                            </p>
                            <span class="text-base font-black text-[#FF6B00] block mt-1">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-6 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95">
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

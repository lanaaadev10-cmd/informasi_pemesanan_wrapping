@extends('layouts.dashboard_customer')

@php
    $buy = $booking->status instanceof \App\Enums\BookingStatus ? $booking->status : \App\Enums\BookingStatus::from($booking->status);
    $statusVal = $buy->value;

    $allSteps = [
        'pending'          => ['label' => 'Ajukan'],
        'confirmed'        => ['label' => 'Dikonfirmasi'],
        'awaiting_payment' => ['label' => 'Menunggu Bayar'],
        'payment_uploaded' => ['label' => 'Bukti Diperiksa'],
        'approved'         => ['label' => 'Disetujui'],
        'in_progress'      => ['label' => 'Dikerjakan'],
        'completed'        => ['label' => 'Selesai'],
    ];
    $orderKeys = array_keys($allSteps);
    $currentIdx = array_search($statusVal, $orderKeys, true);
    $isRejected = $statusVal === 'rejected';
    $isCancelled = $statusVal === 'cancelled';

    // Copy ringkas nilai nominal (sinkron dengan calculateDpAmount di backend).
    $harga = (float) ($booking->layanan?->harga ?? 0);
    $dpAmount = round($harga * 0.5);
    $nominal = $booking->payment_type === 'dp' ? $dpAmount : $harga;
@endphp

@section('title', 'Detail Booking - ' . $booking->booking_code)

@section('content')
<div class="max-w-5xl mx-auto text-white space-y-8 relative overflow-hidden">
    {{-- Glow background --}}
    <div class="absolute top-0 right-0 w-[550px] h-[450px] bg-[#f2994a]/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

    {{-- Navigasi Top Bar --}}
    <div class="flex items-center justify-between gap-4 z-10 relative">
        <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[44px] bg-white/5 hover:bg-white/10 text-xs font-extrabold uppercase tracking-wider rounded-xl border border-white/10 transition-all text-gray-300 hover:text-white">
            Kembali ke Daftar Booking
        </a>
        <button x-data="{ copied: false }"
                @click="navigator.clipboard.writeText('{{ $booking->booking_code }}'); copied = true; setTimeout(() => copied = false, 1600)"
                class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[44px] bg-white/5 hover:bg-white/10 text-xs font-extrabold uppercase tracking-wider rounded-xl border border-white/10 transition-all text-gray-300 hover:text-white">
            <span x-text="copied ? 'Kode Tersalin!' : 'Salin Kode Booking'"></span>
        </button>
    </div>

    {{-- Hero Kartu Booking --}}
    <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 z-10 relative shadow-2xl overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#f2994a]/15 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-2">Kode Pendaftaran Booking</p>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">{{ $booking->booking_code }}</h1>
                    @include('customer.booking.partials.status-badge', ['statusValue' => $statusVal, 'extra' => 'text-xs px-3 py-1 font-black uppercase tracking-wider'])
                </div>
                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                    Diajukan pada {{ $booking->created_at?->translatedFormat('l, d F Y • H:i') }} WIB
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-right min-w-[180px]">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Jadwal Pengerjaan</p>
                    <p class="font-black text-lg text-[#f2994a] mt-0.5">{{ $booking->booking_date?->translatedFormat('l, d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Stepper --}}
    @if(!$isRejected && !$isCancelled)
        <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 z-10 relative shadow-2xl">
            <h2 class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-6 flex items-center gap-2">
                Progress Status Booking
            </h2>
            <div class="overflow-x-auto pb-2 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
                <div class="flex items-center gap-0 min-w-max mx-auto px-4">
                    @foreach($allSteps as $idx => $step)
                        @php
                            $done = $currentIdx !== false && $idx < $currentIdx;
                            $active = $idx === $currentIdx;
                        @endphp
                        <div class="flex items-center">
                            <div class="flex flex-col items-center w-24">
                                <div class="w-12 h-12 rounded-2xl border flex items-center justify-center transition-all {{ $active ? 'bg-gradient-to-br from-[#f2994a] to-[#e28a44] border-[#f2994a] text-black shadow-[0_0_25px_rgba(242,153,74,0.5)] scale-110' : ($done ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-400' : 'bg-white/5 border-white/10 text-gray-600') }}">
                                    <span class="text-sm font-black">{{ $idx + 1 }}</span>
                                </div>
                                <span class="text-[11px] font-extrabold mt-3 text-center leading-tight {{ $active ? 'text-[#f2994a]' : ($done ? 'text-emerald-400' : 'text-gray-500') }}">{{ $step['label'] }}</span>
                            </div>
                            @if($idx < count($allSteps) - 1)
                                <div class="w-8 md:w-14 h-1 rounded-full -mt-6 mx-1 {{ $done ? 'bg-emerald-500/50' : 'bg-white/10' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Callout status --}}
    @php
        $callouts = [
            'pending' => ['color' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-300', 'title' => 'Menunggu Konfirmasi Admin', 'desc' => 'Booking Anda sedang ditinjau tim admin. Konfirmasi verifikasi biasanya selesai dalam kurun waktu 1×24 jam.'],
            'confirmed' => ['color' => 'border-sky-500/30 bg-sky-500/10 text-sky-300', 'title' => 'Booking Dikonfirmasi Admin', 'desc' => 'Admin telah menyetujui jadwal booking Anda. Silakan selesaikan pembayaran dan unggah bukti di bawah ini.'],
            'payment_uploaded' => ['color' => 'border-sky-500/30 bg-sky-500/10 text-sky-300', 'title' => 'Bukti Transfer Sedang Diverifikasi', 'desc' => 'Bukti pembayaran Anda telah dikirim dan sedang diverifikasi admin. Status akan segera diperbarui.'],
            'approved' => ['color' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300', 'title' => 'Jadwal Pengerjaan Terkunci!', 'desc' => 'Booking resmi disetujui. Mohon hadirkan kendaraan Anda pada tanggal ' . $booking->booking_date?->translatedFormat('d M Y') . ' sesuai jadwal.' ],
            'in_progress' => ['color' => 'border-[#f2994a]/30 bg-[#f2994a]/10 text-[#f2994a]', 'title' => 'Proses Pengerjaan Wrapping Berlangsung', 'desc' => 'Tim teknisi profesional kami sedang melakukan proses instalasi wrapping kendaraan Anda.'],
            'completed' => ['color' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300', 'title' => 'Pengerjaan Selesai!', 'desc' => 'Instalasi wrapping kendaraan Anda telah rampung. Terima kasih telah mempercayai layanan wrapping kami!'],
        ];
        $callout = $callouts[$statusVal] ?? null;
    @endphp

    @if(($isRejected || $isCancelled))
        <div class="rounded-3xl border p-6 flex flex-col sm:flex-row items-center gap-5 z-10 relative shadow-xl {{ $isRejected ? 'border-red-500/30 bg-red-500/10' : 'border-gray-500/30 bg-gray-500/10' }}">
            <div class="text-center sm:text-left">
                <h2 class="text-base font-black {{ $isRejected ? 'text-red-300' : 'text-gray-200' }}">{{ $isRejected ? 'Booking Ditolak Admin' : 'Booking Dibatalkan' }}</h2>
                <p class="text-xs text-gray-400 mt-1 max-w-xl">{{ $booking->admin_notes ? 'Catatan Alasan: ' . $booking->admin_notes : 'Tidak ada catatan keterangan tambahan.' }}</p>
            </div>
            <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-5 py-3.5 min-h-[46px] bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-wider rounded-xl transition-all hover:scale-[1.02] ml-auto shrink-0 shadow-lg">
                Buat Booking Baru
            </a>
        </div>
    @elseif($statusVal === 'awaiting_payment')
        {{-- Form Upload Bukti Pembayaran --}}
        <div class="rounded-3xl border border-[#f2994a]/30 bg-gradient-to-br from-[#f2994a]/15 via-[#111111] to-[#111111] p-6 md:p-8 z-10 relative shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-white/10 pb-6">
                <div class="flex items-start gap-4">
                    <div>
                        <h2 class="text-lg font-black text-white">Unggah Bukti Pembayaran</h2>
                        <p class="text-xs text-gray-400 mt-1">Selesaikan transfer untuk mengunci slot tanggal pengerjaan kendaraan Anda.</p>
                    </div>
                </div>
                <div class="px-4 py-3 rounded-2xl bg-black/50 border border-white/10 text-right">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-gray-400 block">Nominal {{ $booking->payment_type === 'dp' ? 'DP 50%' : 'Lunas 100%' }}</span>
                    <span class="font-black text-xl text-[#f2994a]">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('booking.upload-bukti', $booking->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5" x-data="{ fileName: '' }">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Metode Transfer</label>
                        <select name="payment_method" class="w-full px-4 py-3.5 rounded-2xl bg-[#1a1a1a] border border-white/10 text-white font-medium focus:border-[#f2994a] focus:outline-none">
                            <option value="transfer_bank">Transfer Bank (BCA / Mandiri / BRI)</option>
                            <option value="transfer_e_wallet">Transfer E-Wallet (Gopay / OVO / Dana)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bukti Transfer (JPG / PNG / PDF, maks 5MB)</label>
                        <label class="cursor-pointer block">
                            <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png" required @change="fileName = $event.target.files[0]?.name || ''" class="sr-only">
                            <span class="flex items-center justify-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-white/5 border border-dashed border-white/20 text-gray-400 hover:border-[#f2994a] hover:text-white transition-all">
                                <span x-show="!fileName" class="text-xs font-bold uppercase tracking-wide">Klik untuk pilih file bukti</span>
                                <span x-show="fileName" class="text-xs font-extrabold text-white truncate" x-text="fileName"></span>
                            </span>
                        </label>
                    </div>
                </div>
                @error('proof_file') <p class="text-red-400 text-xs mt-2 flex items-center gap-1">{{ $message }}</p> @enderror
                @if(session('toast_error'))
                    <p class="text-xs font-bold text-red-300 bg-red-500/15 border border-red-500/30 rounded-xl px-4 py-3">{{ session('toast_error') }}</p>
                @endif
                <button type="submit" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 min-h-[50px] bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-widest rounded-2xl transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_8px_25px_rgba(242,153,74,0.3)]">
                    Kirim Bukti Pembayaran Now
                </button>
            </form>
        </div>
    @elseif($callout)
        <div class="rounded-3xl border p-6 flex items-start gap-5 z-10 relative shadow-xl backdrop-blur-xl {{ $callout['color'] }}">
            <div>
                <h2 class="text-base font-black text-white">{{ $callout['title'] }}</h2>
                <p class="text-xs mt-1 leading-relaxed opacity-90">{{ $callout['desc'] }}</p>
            </div>
        </div>
    @endif

    {{-- Detail Layanan & Kendaraan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 z-10 relative">
        <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
            <h2 class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-5 flex items-center gap-2">
                Detail Paket Layanan
            </h2>
            <dl class="space-y-4 text-xs">
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Nama Paket</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->layanan?->nama_layanan ?? '-' }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Estimasi Pengerjaan</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->layanan?->estimasi_waktu ?? '-' }}</dd>
                </div>
                <div class="border-t border-white/10 pt-4 space-y-2">
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-gray-400">Harga Paket</dt>
                        <dd class="font-extrabold text-gray-300">Rp {{ number_format($harga, 0, ',', '.') }}</dd>
                    </div>
                    @if($booking->payment_type === 'dp')
                        <div class="flex justify-between items-center gap-4 pt-1">
                            <dt class="text-gray-400">Nilai DP 50%</dt>
                            <dd class="font-black text-[#f2994a]">Rp {{ number_format($dpAmount, 0, ',', '.') }}</dd>
                        </div>
                    @endif
                </div>
            </dl>
        </div>

        <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl">
            <h2 class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-5 flex items-center gap-2">
                Detail Spesifikasi Kendaraan
            </h2>
            <dl class="space-y-4 text-xs">
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Model Kendaraan</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->vehicle_name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Warna Kendaraan</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->vehicle_color ?: '-' }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Nomor Polisi</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->vehicle_license ?: '-' }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Tanggal Booking</dt>
                    <dd class="font-black text-[#f2994a] text-right">{{ $booking->booking_date?->translatedFormat('d M Y') }}</dd>
                </div>
                @if($booking->notes)
                    <div class="border-t border-white/10 pt-3">
                        <dt class="text-gray-400 mb-1">Catatan Khusus</dt>
                        <dd class="text-xs text-gray-300 leading-relaxed">{{ $booking->notes }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Informasi Pembayaran --}}
    @if($booking->payment)
        @php
            $payStatus = $booking->payment->status ?? 'pending';
            $payPill = [
                'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30'],
                'verified' => ['label' => 'Terverifikasi (Lunas/DP)', 'class' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
                'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-500/15 text-red-300 border-red-500/30'],
            ][$payStatus] ?? ['label' => ucfirst($payStatus), 'class' => 'bg-gray-500/15 text-gray-300 border-gray-500/30'];
        @endphp
        <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 z-10 relative shadow-2xl">
            <h2 class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-6 flex items-center gap-2">
                Status Bukti Pembayaran
            </h2>
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-5 text-xs">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Metode Bayar</p>
                        <p class="font-extrabold text-white truncate">{{ ucwords(str_replace('_', ' ', $booking->payment->payment_method ?? '-')) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Status Bukti</p>
                        <span class="inline-flex px-3 py-1 text-[10px] font-black uppercase tracking-wider rounded-full border {{ $payPill['class'] }}">{{ $payPill['label'] }}</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Nominal Terbayar</p>
                        <p class="font-black text-white text-sm">Rp {{ number_format((float) $booking->payment->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="shrink-0">
                    @if($booking->payment->proof_file)
                        <a href="{{ Storage::url($booking->payment->proof_file) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 min-h-[46px] bg-white/5 border border-white/10 hover:border-[#f2994a] rounded-2xl text-xs font-black uppercase tracking-wider text-white transition-all shadow-md">
                            Lihat Lampiran Bukti
                        </a>
                    @else
                        <span class="text-xs text-gray-500">Belum mengunggah lampiran</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Tombol Batalkan --}}
    @if($buy->canBeCancelled())
        <div class="z-10 relative flex justify-end">
            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini? Slot tanggal ini akan dilepas untuk customer lain.');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 min-h-[46px] bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 font-extrabold text-xs uppercase tracking-widest rounded-2xl transition-all">
                    Batalkan Booking Ini
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
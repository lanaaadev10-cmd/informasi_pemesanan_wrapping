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

    {{-- Flash Toast Success Notification --}}
    @if(session('toast_success'))
        <div class="p-5 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-200 text-sm font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10 relative shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="font-black text-white text-base">Booking Berhasil Diajukan!</p>
                    <p class="text-xs text-emerald-300 mt-0.5">{{ session('toast_success') }}</p>
                </div>
            </div>
            <a href="{{ $booking->whatsapp_notification_url }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-black font-black text-xs uppercase tracking-wider transition-all shrink-0 shadow-md">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Kirim Bukti ke WhatsApp
            </a>
        </div>
    @endif

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
            <div class="flex items-center gap-3 flex-wrap">
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-right min-w-[200px]">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Jadwal & Jam Pengerjaan</p>
                    <p class="font-black text-lg text-[#f2994a] mt-0.5">{{ $booking->booking_date?->translatedFormat('l, d M Y') }}</p>
                    <p class="text-xs font-bold text-white mt-0.5">Pukul {{ $booking->booking_time ?: '09:00' }} WIB</p>
                </div>
                <a href="{{ $booking->whatsapp_notification_url }}" target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-4 rounded-2xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/40 text-emerald-300 font-black text-xs uppercase tracking-wider transition-all shadow-md">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    WhatsApp
                </a>
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
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Metode Transfer Bank Tujuan</label>
                        <select name="payment_method" class="w-full px-4 py-3.5 rounded-2xl bg-[#1a1a1a] border border-white/10 text-white font-medium focus:border-[#ff6b00] focus:outline-none">
                            <option value="Transfer BCA">Bank Central Asia (BCA) - 8720-9988-11</option>
                            <option value="Transfer BRI">Bank Rakyat Indonesia (BRI) - 0123-01-001234-53-0</option>
                            <option value="Transfer BSI">Bank Syariah Indonesia (BSI) - 7123-4567-89</option>
                            <option value="Transfer E-Wallet">Transfer E-Wallet (QRIS / Gopay / OVO / Dana)</option>
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
                            <dt class="text-gray-400">Nilai DP 50% (Ditransfer)</dt>
                            <dd class="font-black text-[#ff6b00]">Rp {{ number_format($dpAmount, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between items-center gap-4 pt-1 border-t border-dashed border-white/10 text-xs">
                            <dt class="text-gray-400">Pelunasan di Workshop (Opsi A)</dt>
                            <dd class="font-bold text-gray-200">Rp {{ number_format($dpAmount, 0, ',', '.') }}</dd>
                        </div>
                        <p class="text-[10px] text-[#ff6b00] leading-tight pt-1">*Sisa 50% dibayar langsung saat serah terima kendaraan di workshop.</p>
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
                    <dt class="text-gray-400">Nama Pemesan</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->pelanggan_nama }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Nomor WhatsApp</dt>
                    <dd class="font-extrabold text-[#f2994a] text-right">{{ $booking->pelanggan_phone }}</dd>
                </div>
                @if($booking->pelanggan_email && $booking->pelanggan_email !== '-')
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-gray-400">Email</dt>
                        <dd class="font-extrabold text-white text-right">{{ $booking->pelanggan_email }}</dd>
                    </div>
                @endif
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Model Kendaraan</dt>
                    <dd class="font-extrabold text-white text-right">{{ $booking->vehicle_name ?? '-' }}</dd>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <dt class="text-gray-400">Tanggal &amp; Jam</dt>
                    <dd class="font-black text-[#f2994a] text-right">{{ $booking->booking_date?->translatedFormat('d M Y') }} • {{ $booking->booking_time ?: '09:00' }} WIB</dd>
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
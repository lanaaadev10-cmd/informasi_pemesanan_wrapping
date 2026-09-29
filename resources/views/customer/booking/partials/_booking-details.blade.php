{{-- Detail Layanan, Kendaraan, dan Pembayaran --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 z-10 relative">
    {{-- Detail Paket Layanan --}}
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

    {{-- Detail Spesifikasi Kendaraan --}}
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

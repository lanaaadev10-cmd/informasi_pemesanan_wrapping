{{-- Detail Layanan, Kendaraan, dan Pembayaran --}}
<div class="space-y-6 z-10 relative">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Detail Paket Layanan & Keuangan --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#FF6B00] mb-5 flex items-center gap-2">
                    <i class="ph-bold ph-package text-sm"></i>
                    <span>Detail Paket Layanan &amp; Biaya</span>
                </h2>
                <dl class="space-y-3.5 text-xs">
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Nama Layanan</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $booking->service_name_snapshot ?? ($booking->layanan?->nama_layanan ?? '-') }}</dd>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Tipe Layanan</dt>
                        <dd class="font-bold text-slate-800 uppercase tracking-wider text-right">
                            @if(($booking->layanan?->jenis_layanan ?? 'paket') === 'jasa_pasang' || str_contains(strtolower($booking->layanan?->nama_layanan ?? ''), 'jasa pasang'))
                                <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">Jasa Pasang (Bawa Bahan)</span>
                            @elseif(($booking->layanan?->jenis_layanan ?? 'paket') === 'custom' || (float)$booking->quotation_amount > 0 || empty($booking->layanan_id))
                                <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">Proyek Custom (Deal WA)</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] bg-orange-50 text-[#ff6b00] border border-orange-200">Paket All-In Toko</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Estimasi Pengerjaan</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $booking->layanan?->estimasi_waktu ?? '2-3 Hari Kerja' }}</dd>
                    </div>

                    {{-- Rincian Biaya Dasar --}}
                    <div class="border-t border-slate-100 pt-3 space-y-2">
                        <div class="flex justify-between items-center gap-4">
                            <dt class="text-slate-500">Tarif Layanan Dasar</dt>
                            <dd class="font-bold text-slate-800">Rp {{ number_format($harga, 0, ',', '.') }}</dd>
                        </div>

                        {{-- Rincian Add-ons jika ada --}}
                        @if($booking->bookingAddons && $booking->bookingAddons->isNotEmpty())
                            <div class="pt-2 border-t border-dashed border-slate-200 space-y-1.5">
                                <dt class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Layanan Tambahan (Add-ons):</dt>
                                @foreach($booking->bookingAddons as $bAddon)
                                    <div class="flex justify-between items-center text-[11px] pl-2">
                                        <dd class="text-slate-700">• {{ $bAddon->addon_name }}</dd>
                                        <dd class="text-[#ff6b00] font-mono">+ Rp {{ number_format((float) $bAddon->price_snapshot, 0, ',', '.') }}</dd>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Total Keseluruhan --}}
                        <div class="flex justify-between items-center gap-4 pt-2 border-t border-slate-100">
                            <dt class="text-slate-700 font-bold">Total Keseluruhan</dt>
                            <dd class="font-bold text-slate-900 text-sm">Rp {{ number_format($grandTotal, 0, ',', '.') }}</dd>
                        </div>

                        {{-- Skema DP --}}
                        @if($booking->payment_type === 'dp')
                            <div class="flex justify-between items-center gap-4 pt-1">
                                <dt class="text-slate-500">Uang Muka (DP 50%) Ditransfer</dt>
                                <dd class="font-bold text-[#ff6b00]">Rp {{ number_format($dpAmount, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between items-center gap-4 pt-1 border-t border-dashed border-slate-200 text-xs">
                                <dt class="text-slate-500">Sisa Pelunasan di Workshop</dt>
                                <dd class="font-bold text-slate-800">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</dd>
                            </div>
                            <p class="text-[10px] text-[#ff6b00] leading-tight pt-1">*Sisa pelunasan dibayar langsung saat serah terima kendaraan selesai dikerjakan.</p>
                        @else
                            <div class="flex justify-between items-center gap-4 pt-1 text-emerald-700">
                                <dt class="font-bold">Skema Pembayaran</dt>
                                <dd class="font-bold">LUNAS 100%</dd>
                            </div>
                        @endif
                    </div>
                </dl>
            </div>
        </div>

        {{-- Detail Spesifikasi Kendaraan --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-widest text-[#FF6B00] mb-5 flex items-center gap-2">
                    <i class="ph-bold ph-car-profile text-sm"></i>
                    <span>Spesifikasi Kendaraan &amp; Jadwal</span>
                </h2>
                <dl class="space-y-3.5 text-xs">
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Nama Pemesan</dt>
                        <dd class="font-bold text-slate-900 text-right">{{ $booking->pelanggan_nama }}</dd>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Nomor WhatsApp</dt>
                        <dd class="font-bold text-[#FF6B00] text-right">{{ $booking->pelanggan_phone }}</dd>
                    </div>
                    @if($booking->pelanggan_email && $booking->pelanggan_email !== '-')
                        <div class="flex justify-between items-center gap-4">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="font-bold text-slate-900 text-right">{{ $booking->pelanggan_email }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Model Kendaraan</dt>
                        <dd class="font-bold text-slate-900 text-right flex items-center gap-1.5 justify-end">
                            <span>{{ $booking->vehicle_name ?? '-' }}</span>
                            @if($booking->vehicle_size)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-mono font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ strtoupper($booking->vehicle_size) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Warna / Plat Nomor</dt>
                        <dd class="font-bold text-slate-800 text-right">
                            {{ $booking->vehicle_color ?: '-' }} • {{ $booking->vehicle_license ?: '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <dt class="text-slate-500">Jadwal Kedatangan</dt>
                        <dd class="font-bold text-[#FF6B00] text-right">{{ $booking->booking_date?->translatedFormat('d M Y') }} • {{ $booking->booking_time ?: '09:00' }} WIB</dd>
                    </div>
                    @if($booking->notes)
                        <div class="border-t border-slate-100 pt-3">
                            <dt class="text-slate-500 mb-1">Catatan Pelanggan</dt>
                            <dd class="text-xs text-slate-700 leading-relaxed">{{ $booking->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Kartu Khusus Jasa Pasang & Custom Quotation --}}
    @include('customer.booking.partials._detail_jasa_custom')


</div>

{{-- Informasi Pembayaran --}}
@if($booking->payment)
    @php
        $payStatus = $booking->payment->status ?? 'pending';
        $payPill = [
            'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
            'verified' => ['label' => 'Terverifikasi (Lunas/DP)', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-50 text-red-700 border-red-200'],
        ][$payStatus] ?? ['label' => ucfirst($payStatus), 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
    @endphp
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 md:p-8 z-10 relative shadow-sm mt-6">
        <h2 class="text-xs font-bold uppercase tracking-widest text-[#FF6B00] mb-6 flex items-center gap-2">
            <i class="ph-bold ph-credit-card text-sm"></i>
            <span>Status Bukti Pembayaran</span>
        </h2>
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-5 text-xs">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Metode Bayar</p>
                    <p class="font-bold text-slate-900 truncate">{{ ucwords(str_replace('_', ' ', $booking->payment->payment_method ?? '-')) }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Bukti</p>
                    <span class="inline-flex px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border {{ $payPill['class'] }}">{{ $payPill['label'] }}</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nominal Terbayar</p>
                    <p class="font-bold text-slate-900 text-sm">Rp {{ number_format((float) $booking->payment->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="shrink-0">
                @if($booking->payment->proof_file)
                    <a href="{{ Storage::url($booking->payment->proof_file) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 min-h-[46px] bg-slate-50 border border-slate-200 hover:border-orange-300 rounded-2xl text-xs font-bold uppercase tracking-wider text-slate-700 transition-all shadow-sm">
                        <i class="ph-bold ph-file-text"></i>
                        <span>Lihat Lampiran Bukti</span>
                    </a>
                @else
                    <span class="text-xs text-slate-400">Belum mengunggah lampiran</span>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Tombol Batalkan --}}
@if($buy->canBeCancelled())
    <div class="z-10 relative flex justify-end mt-6">
        <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini? Slot tanggal ini akan dilepas untuk customer lain.');">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 min-h-[46px] bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 font-bold text-xs uppercase tracking-widest rounded-2xl transition-all">
                <i class="ph-bold ph-x-circle"></i>
                <span>Batalkan Booking Ini</span>
            </button>
        </form>
    </div>
@endif

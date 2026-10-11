{{-- Status Stepper, Callout, & Form Upload Bukti --}}
@if(!$isRejected && !$isCancelled)
    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 md:p-8 z-10 relative shadow-sm">
        <h2 class="text-xs font-bold uppercase tracking-widest text-[#FF6B00] mb-6 flex items-center gap-2">
            Progress Status Booking
        </h2>
        <div class="overflow-x-auto pb-2 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <div class="flex items-center gap-0 min-w-max mx-auto px-4">
                @foreach($allSteps as $statusKey => $step)
                    @php
                        $stepIndex = $loop->index;
                        $done = $currentIdx !== false && $stepIndex < $currentIdx;
                        $active = $stepIndex === $currentIdx;
                    @endphp
                    <div class="flex items-center">
                        <div class="flex flex-col items-center w-24">
                            <div class="w-12 h-12 rounded-2xl border flex items-center justify-center transition-all {{ $active ? 'bg-[#FF6B00] border-[#FF6B00] text-white shadow-md shadow-orange-500/30 scale-110 font-bold' : ($done ? 'bg-emerald-50 border-emerald-200 text-emerald-600' : 'bg-slate-100 border-slate-200 text-slate-400') }}">
                                <span class="text-sm font-bold">{{ $loop->iteration }}</span>
                            </div>
                            <span class="text-[11px] font-bold mt-3 text-center leading-tight {{ $active ? 'text-[#FF6B00]' : ($done ? 'text-emerald-600' : 'text-slate-500') }}">{{ $step['label'] }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="w-8 md:w-14 h-1 rounded-full -mt-6 mx-1 {{ $done ? 'bg-emerald-400' : 'bg-slate-200' }}"></div>
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
        'pending' => ['color' => 'border-amber-200 bg-amber-50 text-amber-800', 'title' => 'Menunggu Konfirmasi Admin', 'desc' => 'Booking Anda sedang ditinjau tim admin. Konfirmasi verifikasi biasanya selesai dalam kurun waktu 1×24 jam.'],
        'confirmed' => ['color' => 'border-sky-200 bg-sky-50 text-sky-800', 'title' => 'Booking Dikonfirmasi Admin', 'desc' => 'Admin telah menyetujui jadwal booking Anda. Silakan selesaikan pembayaran dan unggah bukti di bawah ini.'],
        'payment_uploaded' => ['color' => 'border-sky-200 bg-sky-50 text-sky-800', 'title' => 'Bukti Transfer Sedang Diverifikasi', 'desc' => 'Bukti pembayaran Anda telah dikirim dan sedang diverifikasi admin. Status akan segera diperbarui.'],
        'approved' => ['color' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'title' => 'Jadwal Pengerjaan Terkunci!', 'desc' => 'Booking resmi disetujui. Mohon hadirkan kendaraan Anda pada tanggal ' . $booking->booking_date?->translatedFormat('d M Y') . ' sesuai jadwal.' ],
        'in_progress' => ['color' => 'border-orange-200 bg-orange-50 text-orange-800', 'title' => 'Proses Pengerjaan Wrapping Berlangsung', 'desc' => 'Tim teknisi profesional kami sedang melakukan proses instalasi wrapping kendaraan Anda.'],
        'completed' => ['color' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'title' => 'Pengerjaan Selesai!', 'desc' => 'Instalasi wrapping kendaraan Anda telah rampung. Terima kasih telah mempercayai layanan wrapping kami!'],
    ];
    $callout = $callouts[$statusVal] ?? null;
@endphp

@if(($isRejected || $isCancelled))
    <div class="rounded-3xl border p-6 flex flex-col sm:flex-row items-center gap-5 z-10 relative shadow-sm {{ $isRejected ? 'border-red-200 bg-red-50 text-red-800' : 'border-slate-200 bg-slate-50 text-slate-800' }}">
        <div class="text-center sm:text-left">
            <h2 class="text-base font-bold {{ $isRejected ? 'text-red-700' : 'text-slate-800' }}">{{ $isRejected ? 'Booking Ditolak Admin' : 'Booking Dibatalkan' }}</h2>
            <p class="text-xs text-slate-600 mt-1 max-w-xl">{{ $booking->admin_notes ? 'Catatan Alasan: ' . $booking->admin_notes : 'Tidak ada catatan keterangan tambahan.' }}</p>
        </div>
        <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-5 py-3.5 min-h-[46px] bg-[#FF6B00] hover:bg-[#ea580c] text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 ml-auto shrink-0">
            Buat Booking Baru
        </a>
    </div>
@elseif($statusVal === 'awaiting_payment')
    {{-- Form Upload Bukti Pembayaran --}}
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 md:p-8 z-10 relative shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 pb-6">
            <div class="flex items-start gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Unggah Bukti Pembayaran</h2>
                    <p class="text-xs text-slate-500 mt-1">Selesaikan transfer untuk mengunci slot tanggal pengerjaan kendaraan Anda.</p>
                </div>
            </div>
            <div class="px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-right">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 block">Nominal {{ $booking->payment_type === 'dp' ? 'DP 50%' : 'Lunas 100%' }}</span>
                <span class="font-bold text-xl text-[#FF6B00]">Rp {{ number_format($nominal, 0, ',', '.') }}</span>
            </div>
        </div>

        <form action="{{ route('booking.upload-bukti', $booking->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5" x-data="{ fileName: '' }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Metode Transfer Bank Tujuan</label>
                    <select name="payment_method" class="w-full px-4 py-3.5 rounded-2xl bg-white border border-slate-200 text-slate-800 font-medium focus:border-[#ff6b00] focus:outline-none">
                        <option value="Transfer BCA">Bank Central Asia (BCA) - 8720-9988-11</option>
                        <option value="Transfer BRI">Bank Rakyat Indonesia (BRI) - 0123-01-001234-53-0</option>
                        <option value="Transfer BSI">Bank Syariah Indonesia (BSI) - 7123-4567-89</option>
                        <option value="Transfer E-Wallet">Transfer E-Wallet (QRIS / Gopay / OVO / Dana)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bukti Transfer (JPG / PNG / PDF, maks 5MB)</label>
                    <label class="cursor-pointer block">
                        <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png" required @change="fileName = $event.target.files[0]?.name || ''" class="sr-only">
                        <span class="flex items-center justify-center gap-3 w-full px-4 py-3.5 rounded-2xl bg-slate-50 border border-dashed border-slate-300 text-slate-500 hover:border-[#FF6B00] hover:text-[#FF6B00] transition-all">
                            <span x-show="!fileName" class="text-xs font-bold uppercase tracking-wide">Klik untuk pilih file bukti</span>
                            <span x-show="fileName" class="text-xs font-bold text-slate-900 truncate" x-text="fileName"></span>
                        </span>
                    </label>
                </div>
            </div>
            @error('proof_file') <p class="text-red-500 text-xs mt-2 flex items-center gap-1">{{ $message }}</p> @enderror
            @if(session('toast_error'))
                <p class="text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-3">{{ session('toast_error') }}</p>
            @endif
            <button type="submit" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 min-h-[50px] bg-[#FF6B00] hover:bg-[#ea580c] text-white font-bold text-xs uppercase tracking-widest rounded-2xl transition-all hover:scale-[1.02] active:scale-[0.98] shadow-md shadow-orange-500/20">
                Kirim Bukti Pembayaran Sekarang
            </button>
        </form>
    </div>
@elseif($callout)
    <div class="rounded-3xl border p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 z-10 relative shadow-sm {{ $callout['color'] }}">
        <div>
            <h2 class="text-base font-bold text-slate-900">{{ $callout['title'] }}</h2>
            <p class="text-xs mt-1 leading-relaxed opacity-90">{{ $callout['desc'] }}</p>
        </div>
        @if($statusVal === 'completed')
            <div class="shrink-0">
                @if($booking->rating)
                    <div class="inline-flex items-center gap-1.5 px-4 py-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-montserrat font-bold">
                        <i class="ph-fill ph-star text-base"></i>
                        <span>Ulasan Diberikan ({{ $booking->rating->rating }}/5)</span>
                    </div>
                @else
                    <button type="button"
                            onclick="window.openRatingModal({
                                bookingId: {{ $booking->id }},
                                serviceName: '{{ addslashes($booking->service_name_snapshot ?? ($booking->layanan?->nama_layanan ?? 'Layanan Wrapping')) }}',
                                orderCode: '{{ $booking->booking_code }}'
                            })"
                            class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-[#FF6B00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-orange-500/20 hover:scale-[1.02] active:scale-95">
                        <i class="ph-bold ph-star text-base"></i>
                        <span>★ Beri Ulasan Sekarang</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
@endif

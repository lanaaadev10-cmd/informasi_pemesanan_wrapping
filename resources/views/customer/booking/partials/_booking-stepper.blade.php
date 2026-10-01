{{-- Status Stepper, Callout, & Form Upload Bukti --}}
@if(!$isRejected && !$isCancelled)
    <div class="bg-[#111111]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 md:p-8 z-10 relative shadow-2xl">
        <h2 class="text-xs font-black uppercase tracking-widest text-[#f2994a] mb-6 flex items-center gap-2">
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
                            <div class="w-12 h-12 rounded-2xl border flex items-center justify-center transition-all {{ $active ? 'bg-[#FF6B00] border-[#FF6B00] text-black shadow-[0_0_20px_rgba(255,107,0,0.4)] scale-110' : ($done ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-400' : 'bg-white/5 border-white/10 text-gray-600') }}">
                                <span class="text-sm font-black">{{ $loop->iteration }}</span>
                            </div>
                            <span class="text-[11px] font-extrabold mt-3 text-center leading-tight {{ $active ? 'text-[#FF6B00]' : ($done ? 'text-emerald-400' : 'text-gray-500') }}">{{ $step['label'] }}</span>
                        </div>
                        @if(!$loop->last)
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
        <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 px-5 py-3.5 min-h-[46px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-black text-xs uppercase tracking-wider rounded-xl transition-all hover:scale-[1.02] ml-auto shrink-0 shadow-lg">
            Buat Booking Baru
        </a>
    </div>
@elseif($statusVal === 'awaiting_payment')
    {{-- Form Upload Bukti Pembayaran --}}
    <div class="rounded-3xl border border-white/10 bg-[#141416] p-6 md:p-8 z-10 relative shadow-2xl">
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
            <button type="submit" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 min-h-[50px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-black text-xs uppercase tracking-widest rounded-2xl transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_8px_25px_rgba(255,107,0,0.3)]">
                Kirim Bukti Pembayaran Sekarang
            </button>
        </form>
    </div>
@elseif($callout)
    <div class="rounded-3xl border p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-5 z-10 relative shadow-xl backdrop-blur-xl {{ $callout['color'] }}">
        <div>
            <h2 class="text-base font-black text-white">{{ $callout['title'] }}</h2>
            <p class="text-xs mt-1 leading-relaxed opacity-90">{{ $callout['desc'] }}</p>
        </div>
        @if($statusVal === 'completed')
            <div class="shrink-0">
                @if($booking->rating)
                    <div class="inline-flex items-center gap-1.5 px-4 py-3 rounded-2xl bg-[#FFB800]/15 border border-[#FFB800]/40 text-[#FFB800] text-xs font-montserrat font-bold">
                        <i class="ph-fill ph-star text-base"></i>
                        <span>Ulasan Diberikan ({{ $booking->rating->rating }}/5)</span>
                    </div>
                @else
                    <button type="button"
                            onclick="window.openRatingModal({
                                bookingId: {{ $booking->id }},
                                serviceName: '{{ addslashes($booking->layanan->nama_layanan ?? 'Layanan Wrapping') }}',
                                orderCode: '{{ $booking->booking_code }}'
                            })"
                            class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-black text-xs uppercase tracking-wider transition-all shadow-[0_4px_18px_rgba(255,107,0,0.35)] hover:scale-[1.02] active:scale-95">
                        <i class="ph-bold ph-star text-base"></i>
                        <span>★ Beri Ulasan Sekarang</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
@endif

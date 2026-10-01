{{-- Konten Tab: Jadwal Booking --}}
<div class="space-y-6 z-10 relative" role="tabpanel" aria-labelledby="tab-booking">
    {{-- Filter Sub-Tabs Booking --}}
    @php
        $bookingFilters = [
            'all' => ['label' => 'Semua', 'count' => $bookingStats['tab_all'] ?? 0],
            'unpaid' => ['label' => 'Menunggu Bayar', 'count' => $bookingStats['tab_unpaid'] ?? 0],
            'processing' => ['label' => 'Diproses', 'count' => $bookingStats['tab_processing'] ?? 0],
            'completed' => ['label' => 'Selesai', 'count' => $bookingStats['tab_completed'] ?? 0],
            'cancelled' => ['label' => 'Dibatalkan', 'count' => $bookingStats['tab_cancelled'] ?? 0],
        ];
    @endphp
    <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar">
        @foreach($bookingFilters as $key => $filter)
            @php
                $isActive = ($bookingTab === $key);
            @endphp
            <a href="{{ route('transaksi.index', ['type' => 'booking', 'booking_tab' => $key]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 min-h-[42px] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider whitespace-nowrap transition-all {{ $isActive ? 'bg-white text-black shadow-md font-black' : 'bg-[#0E0E10] text-[#8A8D93] hover:text-white hover:bg-white/5 border border-white/5' }}">
                <span>{{ $filter['label'] }}</span>
                <span class="text-[10px] font-mono px-1.5 py-0.5 rounded {{ $isActive ? 'bg-black/15 text-black' : 'bg-white/10 text-gray-300' }}">
                    {{ $filter['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Daftar Kartu Booking --}}
    @if($bookings->isEmpty())
        <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-12 sm:p-16 text-center shadow-xl">
            <div class="w-16 h-16 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/20 flex items-center justify-center text-[#FF6B00] text-2xl mx-auto mb-4">
                <i class="ph-bold ph-calendar-blank"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-audiowide font-bold text-white mb-2">
                Tidak Ada Jadwal Booking Ditemukan
            </h3>
            <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-md mx-auto mb-6">
                Belum ada reservasi pengerjaan pada filter ini. Amankan slot bengkel untuk jadwal Anda sekarang.
            </p>
            <a href="{{ route('booking.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-lg active:scale-95 transition-all">
                <i class="ph-bold ph-plus-circle text-base"></i> Buat Booking Baru
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $bk)
                @php
                    $statusVal = $bk->status instanceof \App\Enums\BookingStatus ? $bk->status->value : $bk->status;
                    $infoDate = $dateInfo($bk->booking_date?->toDateString());
                    $isPast = $bk->booking_date?->isPast();
                @endphp
                <div class="bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/40 rounded-[24px] p-5 sm:p-6 transition-all shadow-xl group">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start sm:items-center gap-4 min-w-0">
                            {{-- Chip Tanggal --}}
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border flex flex-col items-center justify-center shrink-0 shadow-md {{ $isPast ? 'bg-white/[0.02] border-white/5' : 'bg-[#FF6B00]/10 border-[#FF6B00]/30' }}">
                                <span class="text-xl sm:text-2xl font-audiowide font-bold leading-none {{ $isPast ? 'text-gray-500' : 'text-[#FF6B00]' }}">
                                    {{ $bk->booking_date?->format('d') }}
                                </span>
                                <span class="text-[8px] sm:text-[9px] font-montserrat font-bold uppercase tracking-widest mt-1 {{ $isPast ? 'text-gray-500' : 'text-gray-300' }}">
                                    {{ $bk->booking_date?->translatedFormat('M Y') }}
                                </span>
                            </div>

                            {{-- Rincian Data --}}
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-audiowide text-white text-sm sm:text-base tracking-wide">
                                        {{ $bk->booking_code }}
                                    </span>
                                    <span class="text-gray-600">&bull;</span>
                                    @include('customer.booking.partials.status-badge', ['statusValue' => $statusVal])
                                </div>

                                <p class="text-xs sm:text-sm font-montserrat font-bold text-white truncate">
                                    {{ $bk->layanan?->nama_layanan ?? 'Paket Wrapping' }}
                                    <span class="text-[#8A8D93] font-normal">({{ $bk->vehicle_name }})</span>
                                </p>

                                <div class="flex items-center gap-3 text-xs font-questrial text-[#8A8D93] flex-wrap">
                                    <span>Pukul {{ $bk->booking_time ?: '09:00' }} WIB</span>
                                    @if($infoDate)
                                        <span class="text-gray-600">&bull;</span>
                                        <span class="font-bold text-[#FF6B00]">{{ $infoDate }}</span>
                                    @endif
                                    <span class="text-gray-600">&bull;</span>
                                    @include('customer.booking.partials.payment-pill', ['paymentType' => $bk->payment_type])
                                </div>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center justify-end gap-2.5 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/5 shrink-0 flex-wrap">
                            @if($statusVal === 'completed')
                                @if($bk->rating)
                                    <span class="inline-flex items-center gap-1 px-3 py-2 text-[10px] font-montserrat font-bold text-[#FFB800] bg-[#FFB800]/10 border border-[#FFB800]/25 rounded-xl">
                                        <i class="ph-fill ph-star"></i> Telah Diulas
                                    </span>
                                @else
                                    <button type="button"
                                            onclick="window.openRatingModal({
                                                bookingId: {{ $bk->id }},
                                                serviceName: '{{ addslashes($bk->layanan?->nama_layanan ?? 'Layanan Wrapping') }}',
                                                orderCode: '{{ $bk->booking_code }}'
                                            })"
                                            class="inline-flex items-center justify-center gap-1 px-4 py-2.5 min-h-[44px] text-xs font-montserrat font-black uppercase tracking-wider text-black bg-[#FF6B00] hover:bg-[#E05D00] rounded-xl transition-all shadow-[0_4px_14px_rgba(255,107,0,0.3)] active:scale-95">
                                        <i class="ph-bold ph-star text-xs"></i> Beri Ulasan
                                    </button>
                                @endif
                            @endif
                            <a href="{{ route('booking.show', $bk->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-5 py-2.5 min-h-[44px] bg-[#16161A] hover:bg-[#FF6B00] hover:text-black border border-white/10 hover:border-[#FF6B00] rounded-xl text-xs font-montserrat font-bold uppercase tracking-wider text-white transition-all shadow-md active:scale-95">
                                <span>Rincian Booking</span>
                                <i class="ph-bold ph-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $bookings->appends(['type' => 'booking', 'booking_tab' => $bookingTab])->links() }}
        </div>
    @endif
</div>

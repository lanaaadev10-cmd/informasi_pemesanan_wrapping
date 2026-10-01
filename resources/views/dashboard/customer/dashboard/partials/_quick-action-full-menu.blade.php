{{-- ═══════════════════════════════════════════════════════════
     2. TAMPILAN LENGKAP: 3 GRUP SIMETRIS (3 + 4 + 4 = 11 FITUR)
     Tiap Grup Ditata Grid di Mobile & Desktop
═══════════════════════════════════════════════════════════ --}}
<div x-show="showAll"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="space-y-4 pt-1"
     style="display: none;">

    {{-- ── GRUP 1: LAYANAN & RESERVASI (4 FITUR SEIMBANG) ── --}}
    <div class="bg-[#0E0E10] border border-white/10 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-xl">
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-sparkle text-sm"></i> Layanan &amp; Reservasi
            </span>
            <span class="text-[9px] font-mono text-[#8A8D93]">3 Fitur</span>
        </div>

        <div class="grid grid-cols-3 gap-2 sm:gap-3">
            {{-- 1. Booking Baru --}}
            <a href="{{ route('booking.create') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-[#FF6B00]/15 text-[#FF6B00] border border-[#FF6B00]/30 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#FF6B00] group-hover:text-black transition-all">
                    <i class="ph-bold ph-calendar-plus text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Booking Baru</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Reservasi slot</p>
                </div>
            </a>

            {{-- 2. Katalog Layanan --}}
            <a href="{{ route('katalog.user') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-[#FF6B00] border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all">
                    <i class="ph-bold ph-storefront text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Katalog</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Paket &amp; harga</p>
                </div>
            </a>


            {{-- 4. Keranjang Belanja --}}
            <a href="{{ route('keranjang.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all relative">
                    <i class="ph-bold ph-shopping-cart text-lg text-white group-hover:text-[#FF6B00]"></i>
                    @if($cCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 bg-[#FF6B00] text-black text-[8px] font-extrabold rounded-full flex items-center justify-center shadow-md">
                            {{ $cCount > 9 ? '9+' : $cCount }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Keranjang</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Item belanjaan</p>
                </div>
            </a>
        </div>
    </div>

    {{-- ── GRUP 2: TRANSAKSI & PEMBAYARAN (4 FITUR DENGAN PERAN JELAS) ── --}}
    <div class="bg-[#0E0E10] border border-white/10 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-xl">
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-receipt text-sm"></i> Transaksi &amp; Pembayaran
            </span>
            <span class="text-[9px] font-mono text-[#8A8D93]">4 Fitur</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            {{-- 1. Riwayat Transaksi (Pusat Arsip Seluruh Order) --}}
            <a href="{{ route('transaksi.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-[#FF6B00]/15 text-[#FF6B00] border border-[#FF6B00]/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-all">
                    <i class="ph-bold ph-receipt text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Riwayat Order</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Arsip transaksi</p>
                </div>
            </a>

            {{-- 2. Jadwal Servis (Agenda Kedatangan Workshop) --}}
            <a href="{{ route('booking.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-[#FF6B00] border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all relative">
                    <i class="ph-bold ph-calendar-check text-lg"></i>
                    @if(isset($activeBookingsCount) && $activeBookingsCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 bg-emerald-500 text-black text-[8px] font-extrabold rounded-full flex items-center justify-center shadow-md">
                            {{ $activeBookingsCount }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Jadwal Servis</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Agenda workshop</p>
                </div>
            </a>

            {{-- 3. Bayar Tagihan (Pusat Aksi Pembayaran & Upload Bukti) --}}
            <a href="{{ route('transaksi.tagihan') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-[#FF6B00] border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all relative">
                    <i class="ph-bold ph-credit-card text-lg"></i>
                    @if(isset($unpaidTagihanCount) && $unpaidTagihanCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 bg-[#FF6B00] text-black text-[8px] font-black rounded-full flex items-center justify-center shadow-md animate-pulse">
                            {{ $unpaidTagihanCount }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Bayar Tagihan</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Menunggu bayar</p>
                </div>
            </a>

            {{-- 4. Beri Ulasan (Rating & Kepuasan Pelanggan) --}}
            @if(isset($unreviewedBooking) && $unreviewedBooking)
                <button type="button"
                        onclick="window.openRatingModal({
                            bookingId: {{ $unreviewedBooking->id }},
                            serviceName: '{{ addslashes($unreviewedBooking->layanan->nama_layanan ?? 'Wrapping') }}',
                            orderCode: '{{ $unreviewedBooking->booking_code }}'
                        })"
                        class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FFB800] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative w-full">
            @elseif(isset($unreviewedPesanan) && $unreviewedPesanan)
                <button type="button"
                        onclick="window.openRatingModal({
                            pesananId: {{ $unreviewedPesanan->id_pesanan }},
                            layananId: {{ $unreviewedPesanan->details->first()?->id_paket ?? 'null' }},
                            serviceName: '{{ addslashes($unreviewedPesanan->details->first()?->layanan->nama_layanan ?? 'Wrapping') }}',
                            orderCode: '{{ $unreviewedPesanan->kode_pesanan }}'
                        })"
                        class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FFB800] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative w-full">
            @else
                <a href="{{ route('testimoni.index') }}"
                   class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FFB800] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative">
            @endif
                <div class="w-10 h-10 rounded-xl bg-white/5 text-[#FFB800] border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FFB800]/50 transition-all relative">
                    <i class="ph-fill ph-star text-lg"></i>
                    @if(isset($unreviewedCount) && $unreviewedCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 bg-[#FFB800] text-black text-[8px] font-black rounded-full flex items-center justify-center shadow-md animate-bounce">
                            {{ $unreviewedCount }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FFB800] truncate">Beri Ulasan</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Rating kepuasan</p>
                </div>
            @if((isset($unreviewedBooking) && $unreviewedBooking) || (isset($unreviewedPesanan) && $unreviewedPesanan))
                </button>
            @else
                </a>
            @endif
        </div>
    </div>

    {{-- ── GRUP 3: WORKSHOP & BANTUAN (4 FITUR SEIMBANG) ── --}}
    <div class="bg-[#0E0E10] border border-white/10 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-xl">
        <div class="flex items-center justify-between border-b border-white/5 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-buildings text-sm"></i> Workshop &amp; Bantuan
            </span>
            <span class="text-[9px] font-mono text-[#8A8D93]">4 Fitur</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            {{-- 1. Galeri Portofolio --}}
            <a href="{{ route('galeri.user') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all">
                    <i class="ph-bold ph-images text-lg text-white group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Galeri</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Hasil wrapping</p>
                </div>
            </a>

            {{-- 2. Profil Workshop --}}
            <a href="{{ route('profil.perusahaan') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all">
                    <i class="ph-bold ph-buildings text-lg text-white group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Workshop</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Profil bengkel</p>
                </div>
            </a>

            {{-- 3. Akun Saya --}}
            <a href="{{ route('profile.edit') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-[#FF6B00] hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-[#FF6B00]/40 transition-all">
                    <i class="ph-bold ph-user-gear text-lg text-white group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-[#FF6B00] truncate">Akun Saya</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Pengaturan</p>
                </div>
            </a>

            {{-- 4. Konsultasi CS WhatsApp --}}
            <a href="{{ $waUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:border-emerald-500/60 hover:bg-white/[0.04] active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 flex items-center justify-center shrink-0 group-hover:scale-110 transition-all">
                    <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-white group-hover:text-emerald-400 truncate">Konsultasi</p>
                    <p class="text-[9px] font-questrial text-[#8A8D93] truncate">Chat Teknisi</p>
                </div>
            </a>
        </div>
    </div>
</div>

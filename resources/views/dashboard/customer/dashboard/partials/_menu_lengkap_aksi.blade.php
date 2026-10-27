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
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-sparkle text-sm"></i> Layanan &amp; Reservasi
            </span>
            <span class="text-[9px] font-mono text-slate-500">3 Fitur</span>
        </div>

        <div class="grid grid-cols-3 gap-2 sm:gap-3">
            {{-- 1. Booking Baru --}}
            <a href="{{ route('booking.create') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#FF6B00] group-hover:text-white transition-all">
                    <i class="ph-bold ph-calendar-plus text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Booking Baru</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Reservasi slot</p>
                </div>
            </a>

            {{-- 2. Katalog Layanan --}}
            <a href="{{ route('katalog.user') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-[#FF6B00] border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-storefront text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Katalog</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Paket &amp; harga</p>
                </div>
            </a>

            {{-- 3. Keranjang Belanja --}}
            <a href="{{ route('keranjang.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-slate-700 border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-shopping-cart text-lg text-slate-700 group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Keranjang</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">{{ $cCount > 0 ? $cCount . ' item di keranjang' : 'Item belanjaan' }}</p>
                </div>
            </a>
        </div>
    </div>

    {{-- ── GRUP 2: TRANSAKSI & PEMBAYARAN (4 FITUR DENGAN PERAN JELAS) ── --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-receipt text-sm"></i> Transaksi &amp; Pembayaran
            </span>
            <span class="text-[9px] font-mono text-slate-500">4 Fitur</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            {{-- 1. Riwayat Transaksi (Pusat Arsip Seluruh Order) --}}
            <a href="{{ route('transaksi.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] border border-orange-200 flex items-center justify-center shrink-0 group-hover:scale-110 transition-all">
                    <i class="ph-bold ph-receipt text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Riwayat Order</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Arsip transaksi</p>
                </div>
            </a>

            {{-- 2. Jadwal Servis (Agenda Kedatangan Workshop) --}}
            <a href="{{ route('booking.index') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-[#FF6B00] border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-calendar-check text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Jadwal Servis</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">{{ isset($activeBookingsCount) && $activeBookingsCount > 0 ? $activeBookingsCount . ' jadwal aktif' : 'Agenda workshop' }}</p>
                </div>
            </a>

            {{-- 3. Bayar Tagihan (Pusat Aksi Pembayaran & Upload Bukti) --}}
            <a href="{{ route('transaksi.tagihan') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-[#FF6B00] border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-credit-card text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Bayar Tagihan</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">{{ isset($unpaidTagihanCount) && $unpaidTagihanCount > 0 ? $unpaidTagihanCount . ' belum bayar' : 'Menunggu bayar' }}</p>
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
                        class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-amber-400 hover:bg-amber-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative w-full">
            @elseif(isset($unreviewedPesanan) && $unreviewedPesanan)
                <button type="button"
                        onclick="window.openRatingModal({
                            pesananId: {{ $unreviewedPesanan->id_pesanan }},
                            layananId: {{ $unreviewedPesanan->details->first()?->id_paket ?? 'null' }},
                            serviceName: '{{ addslashes($unreviewedPesanan->details->first()?->layanan->nama_layanan ?? 'Wrapping') }}',
                            orderCode: '{{ $unreviewedPesanan->kode_pesanan }}'
                        })"
                        class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-amber-400 hover:bg-amber-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px] relative w-full">
            @else
                <a href="{{ route('testimoni.index') }}"
                   class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-amber-400 hover:bg-amber-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
            @endif
                <div class="w-10 h-10 rounded-xl bg-white text-amber-500 border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-amber-400 transition-all">
                    <i class="ph-fill ph-star text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-amber-500 truncate">Beri Ulasan</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">{{ isset($unreviewedCount) && $unreviewedCount > 0 ? $unreviewedCount . ' perlu dinilai' : 'Rating kepuasan' }}</p>
                </div>
            @if((isset($unreviewedBooking) && $unreviewedBooking) || (isset($unreviewedPesanan) && $unreviewedPesanan))
                </button>
            @else
                </a>
            @endif
        </div>
    </div>

    {{-- ── GRUP 3: WORKSHOP & BANTUAN (4 FITUR SEIMBANG) ── --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 space-y-2.5 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#FF6B00] flex items-center gap-1.5">
                <i class="ph-bold ph-buildings text-sm"></i> Workshop &amp; Bantuan
            </span>
            <span class="text-[9px] font-mono text-slate-500">4 Fitur</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            {{-- 1. Galeri Portofolio --}}
            <a href="{{ route('galeri.user') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-slate-700 border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-images text-lg text-slate-700 group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Galeri</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Hasil wrapping</p>
                </div>
            </a>

            {{-- 2. Profil Workshop --}}
            <a href="{{ route('profil.perusahaan') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-slate-700 border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-buildings text-lg text-slate-700 group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Workshop</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Profil bengkel</p>
                </div>
            </a>

            {{-- 3. Akun Saya --}}
            <a href="{{ route('profile.edit') }}"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-orange-300 hover:bg-orange-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-white text-slate-700 border border-slate-200 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:border-orange-300 transition-all">
                    <i class="ph-bold ph-user-gear text-lg text-slate-700 group-hover:text-[#FF6B00]"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-[#FF6B00] truncate">Akun Saya</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Pengaturan</p>
                </div>
            </a>

            {{-- 4. Konsultasi CS WhatsApp --}}
            <a href="{{ $waUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="group flex items-center gap-3 p-2.5 sm:p-3 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/40 active:scale-95 transition-all text-left min-h-[68px] sm:min-h-[76px]">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0 group-hover:scale-110 transition-all">
                    <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-montserrat font-bold text-slate-800 group-hover:text-emerald-600 truncate">Konsultasi</p>
                    <p class="text-[9px] font-questrial text-slate-500 truncate">Chat Teknisi</p>
                </div>
            </a>
        </div>
    </div>
</div>

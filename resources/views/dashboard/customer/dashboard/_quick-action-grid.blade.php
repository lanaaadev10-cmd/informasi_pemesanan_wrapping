{{-- Aksi Cepat — Pusat Akses Seluruh Fitur User Dashboard (Simetris 4-4-4 & Responsive Grouped View) --}}
@php
    $rawWa = $profil->no_wa ?? '081234567890';
    $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($cleanWa, '0')) {
        $cleanWa = '62' . substr($cleanWa, 1);
    }
    $waUrl = "https://wa.me/{$cleanWa}?text=" . urlencode("Halo Admin Dantie Stiker, saya ingin konsultasi mengenai layanan & pengerjaan wrapping kendaraan.");

    $cCount = $cartCount ?? (auth()->check() ? \App\Models\Keranjang::where('id_user', auth()->id())->where('status', 'active')->first()?->details?->count() ?? 0 : 0);
@endphp

<div x-data="{ showAll: false }" class="space-y-3">
    {{-- Header Section: Judul + Trigger Teks "Semua Fitur" --}}
    <div class="flex items-center justify-between px-1">
        <div>
            <h3 class="text-sm sm:text-base font-montserrat font-bold text-white tracking-wide">
                Aksi Cepat
            </h3>
        </div>

        {{-- Trigger Teks: Buka / Tutup Semua Fitur per Grup --}}
        <button type="button"
                @click="showAll = !showAll"
                class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#E05D00] transition-all py-1 px-2.5 rounded-xl hover:bg-white/5 active:scale-95">
            <i class="ph-bold" :class="showAll ? 'ph-caret-up' : 'ph-squares-four'"></i>
            <span x-text="showAll ? 'Tutup Fitur' : 'Semua Fitur (12)'">Semua Fitur (12)</span>
            <i class="ph-bold text-[10px] transition-transform duration-200" :class="showAll ? 'ph-caret-up' : 'ph-caret-down'"></i>
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         1. TAMPILAN DEFAULT (4 FITUR UTAMA): Ringkas 1 Baris di HP
         Ditampilkan saat showAll = false
    ═══════════════════════════════════════════════════════════ --}}
    <div x-show="!showAll"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="grid grid-cols-4 gap-2 sm:gap-3">

        {{-- 1. Booking Baru --}}
        <a href="{{ route('booking.create') }}"
           class="group flex flex-col items-center justify-center p-2 sm:p-3 rounded-2xl bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00] active:scale-95 transition-all text-center min-h-[82px] sm:min-h-[96px] shadow-lg relative overflow-hidden">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-[#FF6B00]/15 text-[#FF6B00] border border-[#FF6B00]/30 flex items-center justify-center mb-1 sm:mb-1.5 group-hover:scale-110 group-hover:bg-[#FF6B00] group-hover:text-black transition-all">
                <i class="ph-bold ph-calendar-plus text-base sm:text-xl"></i>
            </div>
            <span class="text-[10px] sm:text-[11px] font-montserrat font-bold text-white group-hover:text-[#FF6B00] leading-tight truncate w-full">
                Booking
            </span>
            <span class="text-[8px] sm:text-[9px] font-questrial text-[#8A8D93] mt-0.5">
                Reservasi
            </span>
        </a>

        {{-- 2. Katalog Layanan --}}
        <a href="{{ route('katalog.user') }}"
           class="group flex flex-col items-center justify-center p-2 sm:p-3 rounded-2xl bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00] active:scale-95 transition-all text-center min-h-[82px] sm:min-h-[96px] shadow-lg relative overflow-hidden">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center mb-1 sm:mb-1.5 group-hover:scale-110 group-hover:border-[#FF6B00]/50 transition-all">
                <i class="ph-bold ph-storefront text-base sm:text-xl text-[#FF6B00]"></i>
            </div>
            <span class="text-[10px] sm:text-[11px] font-montserrat font-bold text-white group-hover:text-[#FF6B00] leading-tight truncate w-full">
                Katalog
            </span>
            <span class="text-[8px] sm:text-[9px] font-questrial text-[#8A8D93] mt-0.5">
                Paket &amp; Harga
            </span>
        </a>

        {{-- 3. Keranjang Belanja --}}
        <a href="{{ route('keranjang.index') }}"
           class="group flex flex-col items-center justify-center p-2 sm:p-3 rounded-2xl bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00] active:scale-95 transition-all text-center min-h-[82px] sm:min-h-[96px] shadow-lg relative overflow-hidden">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/5 text-white border border-white/10 flex items-center justify-center mb-1 sm:mb-1.5 group-hover:scale-110 group-hover:border-[#FF6B00]/50 transition-all relative">
                <i class="ph-bold ph-shopping-cart text-base sm:text-xl text-[#FF6B00]"></i>
                @if($cCount > 0)
                    <span class="absolute -top-1 -right-1 min-w-[16px] h-[16px] px-1 bg-[#FF6B00] text-black text-[8px] font-extrabold rounded-full flex items-center justify-center shadow-md">
                        {{ $cCount > 9 ? '9+' : $cCount }}
                    </span>
                @endif
            </div>
            <span class="text-[10px] sm:text-[11px] font-montserrat font-bold text-white group-hover:text-[#FF6B00] leading-tight truncate w-full">
                Keranjang
            </span>
            <span class="text-[8px] sm:text-[9px] font-questrial text-[#8A8D93] mt-0.5">
                {{ $cCount }} Item
            </span>
        </a>

        {{-- 4. Hub Transaksi --}}
        <a href="{{ route('transaksi.index') }}"
           class="group flex flex-col items-center justify-center p-2 sm:p-3 rounded-2xl bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00] active:scale-95 transition-all text-center min-h-[82px] sm:min-h-[96px] shadow-lg relative overflow-hidden">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-[#FF6B00]/15 text-[#FF6B00] border border-[#FF6B00]/30 flex items-center justify-center mb-1 sm:mb-1.5 group-hover:scale-110 transition-all">
                <i class="ph-bold ph-receipt text-base sm:text-xl"></i>
            </div>
            <span class="text-[10px] sm:text-[11px] font-montserrat font-bold text-white group-hover:text-[#FF6B00] leading-tight truncate w-full">
                Transaksi
            </span>
            <span class="text-[8px] sm:text-[9px] font-questrial text-[#8A8D93] mt-0.5">
                Hub Terpadu
            </span>
        </a>
    </div>

    {{-- Tampilan Lengkap: 12 Fitur (Grup 1, 2, 3) --}}
    @include('dashboard.customer.dashboard.partials._quick-action-full-menu')
</div>

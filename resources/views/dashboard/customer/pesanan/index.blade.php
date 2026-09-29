@extends('layouts.dashboard_customer')

@php
    $accentColor = '#ff6b00';
    $isPembayaranTab = request('status') === 'menunggu_pembayaran';
    $pageTitle = $isPembayaranTab ? 'Tagihan & Pembayaran' : ($profil->pesanan_page_title_all ?? 'Riwayat Pesanan');
    $pageDesc = $isPembayaranTab ? 'Pantau pesanan yang menunggu konfirmasi admin, pembayaran, atau verifikasi bukti transfer.' : ($profil->pesanan_page_desc_all ?? 'Kelola dan pantau riwayat pesanan layanan pembungkusan premium Anda yang telah terverifikasi.');
@endphp

<style>
    :root {
        --accent-color: {{ $accentColor }};
    }
    .accent-bg { background-color: var(--accent-color); }
</style>

@section('title', $pageTitle)

@section('content')
<div class="max-w-6xl mx-auto py-8 text-white space-y-8 relative overflow-hidden">
    <!-- Ambient glowing backdrop -->
    <div class="absolute -top-20 -left-20 w-[400px] h-[300px] bg-[#ff6b00]/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 z-10 relative">
        <div class="space-y-1">
            <h1 class="text-2xl sm:text-3xl font-audiowide font-bold tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-xs sm:text-sm font-questrial text-gray-400">{{ $pageDesc }}</p>
        </div>

        @if(!$isPembayaranTab)
        <div class="flex items-center bg-[#0E0E10] border border-white/10 rounded-xl p-1.5 shrink-0 overflow-x-auto">
            <a href="{{ route('pesanan.index') }}"
               class="px-5 py-2.5 rounded-lg text-xs font-montserrat font-bold uppercase tracking-wider transition-all {{ !request()->has('status') ? 'bg-[#FF6B00] text-black shadow-md' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                Semua
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'berjalan']) }}"
               class="px-5 py-2.5 rounded-lg text-xs font-montserrat font-bold uppercase tracking-wider transition-all {{ request('status') == 'berjalan' ? 'bg-[#FF6B00] text-black shadow-md' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                Berjalan
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'selesai']) }}"
               class="px-5 py-2.5 rounded-lg text-xs font-montserrat font-bold uppercase tracking-wider transition-all {{ request('status') == 'selesai' ? 'bg-[#FF6B00] text-black shadow-md' : 'text-[#8A8D93] hover:text-white hover:bg-white/5' }}">
                Selesai
            </a>
        </div>
        @endif
    </div>

    @if($pesanans->isEmpty())
        <div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-12 sm:p-16 text-center shadow-lg relative z-10">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-[#8A8D93] mx-auto mb-6">
                <i class="ph-bold ph-package text-3xl"></i>
            </div>
            <h3 class="text-xl font-audiowide font-bold mb-2">{{ $isPembayaranTab ? ($profil->empty_pesanan_title ?? 'Belum Ada Tagihan Pembayaran') : ($profil->empty_pesanan_title ?? 'Belum Ada Pesanan') }}</h3>
            <p class="text-xs font-questrial text-[#8A8D93] mb-6">{{ $isPembayaranTab ? ($profil->empty_pesanan_desc ?? 'Anda tidak memiliki pesanan yang menunggu pembayaran saat ini.') : ($profil->empty_pesanan_desc ?? 'Anda belum melakukan pesanan layanan pembungkusan.') }}</p>
            <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 px-6 py-3.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black rounded-xl font-montserrat font-extrabold text-xs uppercase tracking-wider transition-all shadow-[0_4px_15px_rgba(255,107,0,0.3)] active:scale-95">
                Mulai Proyek Baru &rarr;
            </a>
        </div>
    @else
        <div class="space-y-6 z-10 relative">
            @foreach($pesanans as $pesanan)
                @php
                    $statusVal = $pesanan->status instanceof \App\Enums\OrderStatus ? $pesanan->status->value : $pesanan->status;
                    $isMenungguPembayaran = in_array($statusVal, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin', 'menunggu_verifikasi_pembayaran']);
                    $isSelesai = $statusVal === 'selesai';
                    $isDitolak = $statusVal === 'ditolak';
                    $isProses = in_array($statusVal, ['sedang_diproses', 'dikonfirmasi']);

                    // Fallback visual car image mapping based on package if order form lacks specific photos
                    $thumbnail = $pesanan->details->first()?->layanan->foto_contoh;
                    $imageUrl = \App\Helpers\StaticContent::fotoUrl($thumbnail ?? '');
                @endphp

                <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] overflow-hidden flex flex-col md:flex-row group hover:border-[#FF6B00]/40 transition-all shadow-xl">

                    <!-- Left Image Section (No Badge Overlays) -->
                    <div class="md:w-64 h-48 md:h-auto relative shrink-0 overflow-hidden bg-black/60">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 md:from-transparent to-transparent md:bg-gradient-to-t md:from-black/80 md:to-transparent"></div>
                    </div>

                    <!-- Right Details Section -->
                    <div class="p-6 md:p-8 flex flex-col justify-between flex-grow">

                        <!-- Top Info -->
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div>
                                <!-- Clean Text Header & Status (No Pill Badges) -->
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="text-[10px] font-mono font-bold text-[#8A8D93] uppercase tracking-widest">#{{ $pesanan->kode_pesanan }}</span>
                                    <span class="text-white/20">&bull;</span>
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-montserrat font-bold uppercase tracking-wider {{ $isProses || $isMenungguPembayaran ? 'text-[#FF6B00]' : ($isSelesai ? 'text-white' : 'text-[#8A8D93]') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isProses || $isMenungguPembayaran ? 'bg-[#FF6B00] animate-pulse' : ($isSelesai ? 'bg-white' : 'bg-gray-500') }}"></span>
                                        {{ $pesanan->label_status }}
                                    </span>
                                </div>

                                <h3 class="text-xl font-audiowide font-bold text-white leading-tight">
                                    {{ $pesanan->form->model_kendaraan ?? 'Kendaraan Wrapping' }}
                                </h3>
                                <p class="text-[11px] font-questrial text-[#8A8D93] mt-2">
                                    @if($isSelesai)
                                        Selesai pada: {{ \Carbon\Carbon::parse($pesanan->updated_at)->translatedFormat('d M Y') }}
                                    @elseif($isMenungguPembayaran)
                                        Dipesan pada: {{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->translatedFormat('d M Y') }}
                                    @else
                                        {{ $profil->label_estimasi_selesai ?? 'Estimasi Selesai' }}: {{ \Carbon\Carbon::parse($pesanan->form->jadwal_pengerjaan ?? $pesanan->tanggal_pesan)->addDays(5)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[9px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest mb-1 block">{{ $profil->label_total_tagihan ?? 'Total Tagihan' }}</span>
                                <span class="text-[#FF6B00] font-audiowide font-bold text-xl sm:text-2xl">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Bottom Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-white/5">

                            <div class="flex items-center gap-6 w-full sm:w-auto text-[11px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-wider">
                                <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="flex items-center gap-1.5 hover:text-[#FF6B00] transition-colors">
                                    <i class="ph-bold ph-eye text-sm"></i> {{ $profil->cta_detail ?? 'Lihat Detail' }}
                                </a>
                                @if(!$isMenungguPembayaran && !$isDitolak && $statusVal !== 'menunggu_konfirmasi_admin')
                                    <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" class="flex items-center gap-1.5 hover:text-[#FF6B00] transition-colors">
                                        <i class="ph-bold ph-download-simple text-sm"></i> {{ $profil->cta_unduh_invoice ?? 'Unduh Invoice' }}
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                                @if($statusVal === 'menunggu_pembayaran')
                                    <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="w-full sm:w-auto text-center px-6 py-2.5 min-h-[44px] inline-flex items-center justify-center bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-[10px] uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-[0_4px_15px_rgba(255,107,0,0.35)]">
                                        {{ $profil->cta_bayar_sekarang ?? 'Bayar Sekarang' }}
                                    </a>
                                @elseif($isSelesai)
                                    <a href="{{ route('pesanan.rating.form', $pesanan->id_pesanan) }}" class="w-full sm:w-auto text-center inline-flex items-center justify-center gap-1.5 px-6 py-2.5 min-h-[44px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-extrabold text-[10px] uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-[0_4px_15px_rgba(255,107,0,0.35)]">
                                        <i class="ph-bold ph-star text-sm"></i> {{ $profil->cta_rating ?? 'Ulasan' }}
                                    </a>
                                    <a href="{{ route('katalog.user') }}" class="w-full sm:w-auto text-center px-6 py-2.5 min-h-[44px] inline-flex items-center justify-center border border-white/10 hover:border-[#FF6B00] text-white hover:text-[#FF6B00] font-montserrat font-bold text-[10px] uppercase tracking-wider rounded-xl transition-all active:scale-95 bg-white/5">
                                        {{ $profil->cta_pesan_lagi ?? 'Pesan Lagi' }}
                                    </a>
                                @elseif($isDitolak)
                                    <a href="{{ route('katalog.user') }}" class="w-full sm:w-auto text-center px-6 py-2.5 min-h-[44px] inline-flex items-center justify-center border border-white/10 hover:border-[#FF6B00] text-white hover:text-[#FF6B00] font-montserrat font-bold text-[10px] uppercase tracking-wider rounded-xl transition-all active:scale-95 bg-white/5">
                                        {{ $profil->cta_pesan_lagi ?? 'Pesan Lagi' }}
                                    </a>
                                @else
                                    <!-- Clean Status Text Indicator (No Pulse Pill Box) -->
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
                                        <span class="text-[10px] font-montserrat font-bold text-[#FF6B00] uppercase tracking-widest">Sedang Dikerjakan</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($pesanans instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6">
                {{ $pesanans->links() }}
            </div>
        @endif
    @endif
</div>
@endsection

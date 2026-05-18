@extends('layouts.dashboard_customer')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-6xl mx-auto py-8 text-white space-y-8 relative overflow-hidden">
    <!-- Ambient glowing backdrop -->
    <div class="absolute -top-20 -left-20 w-[400px] h-[300px] bg-[#f2994a]/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 z-10 relative">
        <div class="space-y-1">
            <h1 class="text-3xl font-bold tracking-tight">Riwayat Pesanan</h1>
            <p class="text-sm text-gray-400">Kelola dan pantau status layanan pembungkusan premium Anda.</p>
        </div>
        
        <div class="flex items-center bg-[#121212] border border-white/10 rounded-xl p-1.5 shrink-0">
            <a href="{{ route('pesanan.index') }}" 
               class="px-5 py-2.5 rounded-lg text-xs font-bold transition-all {{ !request()->has('status') ? 'bg-[#f2994a] text-black shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Semua
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'berjalan']) }}" 
               class="px-5 py-2.5 rounded-lg text-xs font-bold transition-all {{ request('status') == 'berjalan' ? 'bg-[#f2994a] text-black shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Berjalan
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'selesai']) }}" 
               class="px-5 py-2.5 rounded-lg text-xs font-bold transition-all {{ request('status') == 'selesai' ? 'bg-[#f2994a] text-black shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Selesai
            </a>
        </div>
    </div>

    @if($pesanans->isEmpty())
        <div class="bg-[#121212] border border-white/5 rounded-3xl p-16 text-center shadow-lg relative z-10">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-gray-500 mx-auto mb-6">
                <i class="ph-bold ph-package text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Belum Ada Pesanan</h3>
            <p class="text-xs text-gray-400 mb-6">Anda belum melakukan pesanan layanan pembungkusan.</p>
            <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-xl font-bold text-xs uppercase tracking-wider transition-all">
                Mulai Proyek Baru &rarr;
            </a>
        </div>
    @else
        <div class="space-y-6 z-10 relative">
            @foreach($pesanans as $pesanan)
                @php
                    $isMenungguPembayaran = in_array($pesanan->status, ['menunggu_pembayaran', 'menunggu_konfirmasi_admin', 'menunggu_verifikasi_pembayaran']);
                    $isSelesai = $pesanan->status === 'selesai';
                    $isDitolak = $pesanan->status === 'ditolak';
                    $isProses = in_array($pesanan->status, ['sedang_diproses', 'dikonfirmasi']);

                    // Fallback visual car image mapping based on package if order form lacks specific photos
                    $thumbnail = $pesanan->details->first()?->layanan->foto_contoh;
                    $imageUrl = $thumbnail ? asset('storage/' . $thumbnail) : 'https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=400';
                @endphp

                <div class="bg-[#121212] border border-white/5 rounded-[24px] overflow-hidden flex flex-col md:flex-row group hover:border-white/10 transition-all shadow-sm">

                    <!-- Left Image Section -->
                    <div class="md:w-64 h-48 md:h-auto relative shrink-0">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover grayscale-[30%] group-hover:grayscale-0 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/60 md:from-transparent to-transparent md:bg-gradient-to-t md:from-black/80 md:to-transparent"></div>

                        <!-- Status Badge Overlay -->
                        <div class="absolute top-4 left-4">
                            @if($pesanan->status === 'menunggu_konfirmasi_admin')
                                <span class="bg-blue-500/10 border border-blue-500/50 text-blue-400 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Konfirmasi Admin
                                </span>
                            @elseif($pesanan->status === 'menunggu_pembayaran')
                                <span class="bg-red-500/10 border border-red-500/50 text-red-400 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Menunggu Pembayaran
                                </span>
                            @elseif($pesanan->status === 'menunggu_verifikasi_pembayaran')
                                <span class="bg-yellow-500/10 border border-yellow-500/50 text-yellow-400 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Verifikasi Pembayaran
                                </span>
                            @elseif($isSelesai)
                                <span class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Selesai
                                </span>
                            @elseif($isDitolak)
                                <span class="bg-red-900/20 border border-red-600/50 text-red-400 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Ditolak
                                </span>
                            @else
                                <span class="bg-[#1a1105] border border-[#f2994a]/50 text-[#f2994a] text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full">
                                    Sedang Dikerjakan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right Details Section -->
                    <div class="p-6 md:p-8 flex flex-col justify-between flex-grow">

                        <!-- Top Info -->
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div>
                                <span class="text-[9px] font-mono text-gray-500 uppercase tracking-widest mb-1.5 block">#{{ $pesanan->kode_pesanan }}</span>
                                <h3 class="text-xl font-bold text-white leading-tight">
                                    {{ $pesanan->form->model_kendaraan ?? 'Kendaraan Wapping' }}
                                </h3>
                                <p class="text-[10px] text-gray-400 mt-2 font-medium">
                                    @if($isSelesai)
                                        Selesai pada: {{ \Carbon\Carbon::parse($pesanan->updated_at)->translatedFormat('d M Y') }}
                                    @elseif($isMenungguPembayaran)
                                        Dipesan pada: {{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->translatedFormat('d M Y') }}
                                    @else
                                        Estimasi Selesai: {{ \Carbon\Carbon::parse($pesanan->form->jadwal_pengerjaan ?? $pesanan->tanggal_pesan)->addDays(5)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Total Tagihan</span>
                                <span class="text-[#f2994a] font-bold text-xl">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Bottom Actions -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-white/5">

                            <div class="flex items-center gap-6 w-full sm:w-auto text-[11px] font-medium text-gray-400">
                                <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="flex items-center gap-1.5 hover:text-white transition-colors">
                                    <i class="ph-bold ph-eye text-sm"></i> Lihat Detail
                                </a>
                                @if(!$isMenungguPembayaran && !$isDitolak && $pesanan->status !== 'menunggu_konfirmasi_admin')
                                    <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" class="flex items-center gap-1.5 hover:text-white transition-colors">
                                        <i class="ph-bold ph-download-simple text-sm"></i> Unduh Invoice
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 w-full sm:w-auto shrink-0">
                                @if($pesanan->status === 'menunggu_pembayaran')
                                    <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-[#f2994a] hover:bg-[#e28a44] text-black font-bold text-[10px] uppercase tracking-wider rounded-lg transition-all active:scale-95 shadow-md">
                                        Bayar Sekarang
                                    </a>
                                @elseif($isSelesai || $isDitolak)
                                    <a href="{{ route('katalog.user') }}" class="flex-1 sm:flex-none text-center px-6 py-2.5 border border-white/10 hover:border-white/30 text-white font-bold text-[10px] uppercase tracking-wider rounded-lg transition-all active:scale-95 bg-white/5">
                                        Pesan Lagi
                                    </a>
                                @else
                                    <!-- Progress Dots Placeholder for Sedang Dikerjakan -->
                                    <div class="flex gap-1.5 items-center bg-white/5 px-4 py-2 rounded-full border border-white/10">
                                        <div class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse"></div>
                                        <div class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse delay-75"></div>
                                        <div class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse delay-150"></div>
                                        <span class="text-[9px] font-bold text-[#f2994a] uppercase tracking-widest ml-2">Proses</span>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

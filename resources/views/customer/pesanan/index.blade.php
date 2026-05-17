@extends('layouts.dashboard_customer')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="font-serif text-3.5xl md:text-5xl font-black text-stone-900 tracking-tight mb-2">
            Riwayat <span class="text-[#f2541b]">Pesanan.</span>
        </h1>
        <p class="text-stone-500 font-medium text-xs md:text-sm">Pantau status pengerjaan kendaraan Anda secara real-time.</p>
    </div>

    @if($pesanans->isEmpty())
    <div class="bg-white rounded-[32px] p-16 text-center border border-stone-200/50 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#f2541b]/5 blur-[60px] rounded-full"></div>
        <div class="relative z-10 max-w-md mx-auto">
            <div class="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center text-stone-300 mx-auto mb-6 shadow-inner">
                <i class="ph-fill ph-package text-3xl"></i>
            </div>
            <h3 class="font-serif text-2xl font-black text-stone-900 mb-2">Belum Ada Pesanan</h3>
            <p class="text-stone-400 text-xs font-medium mb-8 leading-relaxed">Mulai langkah pertama Anda untuk memberikan proteksi terbaik pada kendaraan Anda.</p>
            <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#151413] hover:bg-[#2a2927] text-white rounded-full font-bold text-xs tracking-wider uppercase transition-all shadow-md">
                Lihat Katalog &rarr;
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pesanans as $pesanan)
        <div class="bg-white rounded-[32px] overflow-hidden border border-stone-200/60 hover:border-[#f2541b]/30 hover:shadow-xl hover:shadow-[#f2541b]/5 hover:-translate-y-1 transition-all duration-300 relative flex flex-col p-6 md:p-8 group">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-[#f2541b]/5 blur-3xl rounded-full"></div>
            
            <div class="relative z-10 h-full flex flex-col justify-between">
                <!-- Status & ID -->
                <div class="flex justify-between items-start gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="text-[9px] font-black text-stone-400 uppercase tracking-widest">#{{ $pesanan->kode_pesanan }}</span>
                            <span class="w-1 h-1 rounded-full bg-stone-300"></span>
                            <span class="text-[9px] font-bold text-stone-400">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="font-serif text-lg md:text-xl font-black text-stone-900 group-hover:text-[#f2541b] transition-colors leading-tight line-clamp-1">
                            {{ $pesanan->form->model_kendaraan ?? 'Detail Kendaraan' }}
                        </h3>
                    </div>
                    @php
                        $statusStyles = [
                            'menunggu_verifikasi' => 'bg-amber-50/50 text-amber-700 border border-amber-200/50',
                            'perlu_diperbaiki'    => 'bg-red-50/50 text-red-700 border border-red-200/50',
                            'diverifikasi'       => 'bg-blue-50/50 text-blue-700 border border-blue-200/50',
                            'menunggu_pembayaran' => 'bg-[#f2541b]/10 text-[#f2541b] border border-[#f2541b]/30 shadow-sm shadow-[#f2541b]/5',
                            'dibayar'            => 'bg-green-50/50 text-green-700 border border-green-200/50',
                            'selesai'            => 'bg-[#151413] text-white',
                            'dibatalkan'         => 'bg-stone-100 text-stone-400',
                        ];
                        $statusLabels = [
                            'menunggu_verifikasi' => 'Verifikasi',
                            'perlu_diperbaiki'    => 'Revisi',
                            'diverifikasi'       => 'Verified',
                            'menunggu_pembayaran' => 'Bayar',
                            'dibayar'            => 'Proses',
                            'selesai'            => 'Selesai',
                            'dibatalkan'         => 'Batal',
                        ];
                    @endphp
                    <span class="{{ $statusStyles[$pesanan->status] ?? 'bg-stone-50 border border-stone-200' }} px-3.5 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-wider shrink-0 text-center">
                        {{ $statusLabels[$pesanan->status] ?? $pesanan->status }}
                    </span>
                </div>

                <!-- Package Details -->
                <div class="space-y-3 mb-6 flex-grow">
                    @foreach($pesanan->details as $item)
                    <div class="flex items-center gap-4 bg-stone-50/70 p-3.5 rounded-[22px] border border-stone-200/30 hover:bg-stone-50 transition-colors">
                        <!-- Thumbnail Image -->
                        <div class="w-14 h-14 rounded-[14px] overflow-hidden bg-stone-100 flex items-center justify-center border border-stone-200/60 shrink-0 relative shadow-sm">
                            @if($item->layanan->foto_contoh)
                                <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover" alt="{{ $item->layanan->nama_layanan }}">
                            @else
                                <div class="text-[8px] text-stone-400 font-bold text-center p-1 uppercase">No Image</div>
                            @endif
                            <!-- Count overlay -->
                            <div class="absolute -bottom-1.5 -right-1.5 bg-[#151413] text-white text-[8px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-md">
                                {{ $item->jumlah }}
                            </div>
                        </div>
                        <!-- Details -->
                        <div class="overflow-hidden flex-grow">
                            <span class="block text-xs font-bold text-stone-900 tracking-tight truncate">{{ $item->layanan->nama_layanan }}</span>
                            <span class="text-[8px] font-black text-[#f2541b] uppercase tracking-widest block mt-0.5">{{ $item->layanan->kategori ?? 'Standard' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer: Price & Action -->
                <div class="pt-5 border-t border-stone-100 flex items-center justify-between mt-auto">
                    <div>
                        <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest block mb-0.5">Total Biaya</span>
                        <span class="font-serif text-xl font-black text-stone-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#f2541b] hover:bg-[#d33d0a] text-white rounded-2xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95 group/btn">
                        <span>Detail</span>
                        <i class="ph-bold ph-caret-right text-[10px] group-hover/btn:translate-x-0.5 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection


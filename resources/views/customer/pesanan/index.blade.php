@extends('layouts.dashboard_customer')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    <!-- Header -->
    <div class="mb-16" data-aos="fade-down">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">Riwayat <span class="text-orange-600 italic">Pesanan.</span></h1>
        <p class="text-gray-400 mt-2 font-medium italic">Pantau status pengerjaan kendaraan Anda secara real-time.</p>
    </div>

    @if($pesanans->isEmpty())
    <div class="soft-card p-20 text-center" data-aos="zoom-in">
        <div class="w-32 h-32 bg-orange-50 text-orange-600 rounded-[3rem] flex items-center justify-center mx-auto mb-8">
            <i class="ph-fill ph-package text-6xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 mb-4">Belum Ada Pesanan</h3>
        <p class="text-gray-400 font-medium mb-10">Mulai langkah pertama Anda untuk memberikan proteksi terbaik pada kendaraan Anda.</p>
        <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-4 bg-gray-900 text-white px-10 py-5 rounded-[2rem] font-black hover:bg-orange-600 transition-all shadow-2xl">
            LIHAT KATALOG <i class="ph-bold ph-arrow-right"></i>
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @foreach($pesanans as $pesanan)
        <div class="soft-card group hover:scale-[1.02] transition-all duration-500 overflow-hidden relative border border-gray-100" data-aos="fade-up">
            <!-- Decorative Badge behind everything -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-600/5 blur-3xl rounded-full"></div>
            
            <div class="p-8 md:p-10 relative z-10 h-full flex flex-col">
                <!-- Status & ID -->
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Order #{{ $pesanan->kode_pesanan }}</span>
                        <h3 class="text-2xl font-black text-gray-900 italic tracking-tight group-hover:text-orange-600 transition-colors">
                            {{ $pesanan->form->model_kendaraan ?? 'Detail Kendaraan' }}
                        </h3>
                    </div>
                    @php
                        $statusStyles = [
                            'menunggu_verifikasi' => 'bg-amber-50 text-amber-600',
                            'perlu_diperbaiki'    => 'bg-red-50 text-red-600',
                            'diverifikasi'       => 'bg-blue-50 text-blue-600',
                            'menunggu_pembayaran' => 'bg-orange-600 text-white shadow-lg shadow-orange-200',
                            'dibayar'            => 'bg-green-50 text-green-600',
                            'selesai'            => 'bg-gray-900 text-white',
                            'dibatalkan'         => 'bg-red-500 text-white',
                        ];
                        $statusLabels = [
                            'menunggu_verifikasi' => 'Verifikasi',
                            'perlu_diperbaiki'    => 'Revisi',
                            'diverifikasi'       => 'Verified',
                            'menunggu_pembayaran' => 'Bayar Sekarang',
                            'dibayar'            => 'Proses',
                            'selesai'            => 'Selesai',
                            'dibatalkan'         => 'Batal',
                        ];
                    @endphp
                    <span class="{{ $statusStyles[$pesanan->status] ?? 'bg-gray-100' }} px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        {{ $statusLabels[$pesanan->status] ?? $pesanan->status }}
                    </span>
                </div>

                <!-- Package Details -->
                <div class="space-y-4 mb-10 flex-grow">
                    @foreach($pesanans->where('id_pesanan', $pesanan->id_pesanan)->first()->details as $item)
                    <div class="flex items-center gap-4 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-900 font-black shadow-sm text-xs">
                            {{ $item->jumlah }}x
                        </div>
                        <div>
                            <span class="block text-sm font-black text-gray-900 tracking-tight">{{ $item->layanan->nama_layanan }}</span>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $item->layanan->kategori ?? 'Standard' }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Footer: Price & Action -->
                <div class="pt-8 border-t border-gray-50 flex items-center justify-between mt-auto">
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Total Biaya</span>
                        <span class="text-3xl font-black text-gray-900 italic tracking-tighter">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="w-14 h-14 bg-gray-900 text-white rounded-2xl flex items-center justify-center hover:bg-orange-600 transition-all shadow-xl group/btn">
                        <i class="ph-bold ph-arrow-right text-2xl group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

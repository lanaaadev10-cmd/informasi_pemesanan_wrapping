@extends('layouts.tampilan_utama')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">📋 Riwayat Pesanan</h1>
            <a href="{{ route('katalog') }}" class="btn-primary px-4 py-2 rounded-xl text-sm">
                + Pesanan Baru
            </a>
        </div>

        @if(session('success'))
        <div class="alert-success mb-6 px-4 py-3 rounded-xl text-green-200 bg-green-900/40 border border-green-500/30">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($pesanans->isEmpty())
        <div class="glass-card text-center py-20">
            <div class="text-6xl mb-4">📄</div>
            <p class="text-gray-400 text-lg">Anda belum memiliki riwayat pesanan.</p>
        </div>
        @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($pesanans as $pesanan)
            <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" 
               class="glass-card p-6 flex flex-col md:flex-row justify-between items-start md:items-center hover:border-yellow-400/50 transition group"
               data-aos="fade-up">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-white font-bold text-lg">{{ $pesanan->kode_pesanan }}</span>
                        <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider 
                            {{ $pesanan->status === 'selesai' ? 'bg-green-500/20 text-green-400' : 
                               ($pesanan->status === 'dibatalkan' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                            {{ str_replace('_', ' ', $pesanan->status) }}
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm">Dipesan pada {{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d M Y') }}</p>
                </div>
                
                <div class="text-left md:text-right">
                    <p class="text-gray-400 text-xs mb-1">Total Harga</p>
                    <p class="text-white font-bold text-xl group-hover:text-yellow-400 transition">
                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </p>
                    <span class="text-yellow-400 text-sm flex items-center gap-1 md:justify-end mt-2">
                        Lihat Detail <i class="ph ph-arrow-right"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

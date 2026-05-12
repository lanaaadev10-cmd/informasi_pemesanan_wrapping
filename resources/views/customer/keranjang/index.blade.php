@extends('layouts.tampilan_utama')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">🛒 Keranjang Belanja</h1>
            @if($keranjang && $keranjang->details->count() > 0)
            <form action="{{ route('keranjang.kosongkan') }}" method="POST"
                  onsubmit="return confirm('Yakin ingin mengosongkan keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger text-sm px-4 py-2 rounded-xl">
                    🗑️ Kosongkan
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="alert-success mb-6 px-4 py-3 rounded-xl text-green-200 bg-green-900/40 border border-green-500/30">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(!$keranjang || $keranjang->details->isEmpty())
        {{-- KOSONG --}}
        <div class="glass-card text-center py-20">
            <div class="text-6xl mb-4">🛒</div>
            <p class="text-gray-400 text-lg mb-6">Keranjang Anda masih kosong.</p>
            <a href="{{ route('katalog.user') }}"
               class="btn-primary px-6 py-3 rounded-xl inline-block">
                Lihat Katalog Layanan
            </a>
        </div>
        @else
        {{-- DAFTAR ITEM --}}
        <div class="space-y-4 mb-8">
            @php $total = 0 @endphp
            @foreach($keranjang->details as $item)
            @php $total += $item->subtotal @endphp
            <div class="glass-card flex items-center justify-between p-5 gap-4"
                 data-aos="fade-up">
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-lg">
                        {{ $item->layanan->nama_paket ?? '-' }}
                    </h3>
                    <p class="text-gray-400 text-sm mt-1">
                        {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </p>
                    @if($item->catatan_custom)
                    <p class="text-yellow-400 text-xs mt-1 italic">
                        📝 {{ $item->catatan_custom }}
                    </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-white font-bold text-lg">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                    <form action="{{ route('keranjang.hapus', $item->id_detail) }}"
                          method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-red-400 hover:text-red-300 text-sm transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- RINGKASAN TOTAL --}}
        <div class="glass-card p-6">
            <div class="flex justify-between items-center text-xl font-bold text-white mb-6">
                <span>Total Harga</span>
                <span class="text-yellow-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('pesanan.checkout.form') }}"
               class="btn-primary w-full text-center block py-4 rounded-xl text-lg font-semibold">
                Lanjut ke Checkout →
            </a>
        </div>
        @endif

    </div>
</div>
@endsection

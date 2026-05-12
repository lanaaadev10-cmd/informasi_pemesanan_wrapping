@extends('layouts.tampilan_utama')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">📦 Konfirmasi Pesanan</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- FORM DATA PENGIRIMAN --}}
            <div class="glass-card p-6" data-aos="fade-right">
                <h2 class="text-xl font-semibold text-white mb-6 flex items-center gap-2">
                    <i class="ph ph-user-circle text-yellow-400"></i> Data Pengiriman
                </h2>
                <form action="{{ route('pesanan.checkout.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Nama Penerima</label>
                            <input type="text" name="nama_pemesan" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition"
                                   value="{{ auth()->user()->name }}">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" required
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition"
                                   placeholder="Contoh: 08123456789">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Alamat Lengkap</label>
                            <textarea name="alamat_pengiriman" required rows="3"
                                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition"
                                      placeholder="Nama jalan, RT/RW, Kecamatan, Kota"></textarea>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="keterangan_tambahan" rows="2"
                                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition"
                                      placeholder="Catatan khusus untuk pesanan Anda"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- RINGKASAN PESANAN --}}
            <div class="space-y-6" data-aos="fade-left">
                <div class="glass-card p-6">
                    <h2 class="text-xl font-semibold text-white mb-6">Ringkasan Item</h2>
                    <div class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($keranjang->details as $item)
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <p class="text-white font-medium">{{ $item->layanan->nama_paket }}</p>
                                <p class="text-gray-400 text-xs">{{ $item->jumlah }}x @ Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-white font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-white/10 mt-6 pt-6">
                        <div class="flex justify-between items-center text-xl font-bold text-white">
                            <span>Total Pembayaran</span>
                            <span class="text-yellow-400">Rp{{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <button type="submit" form="checkout-form"
                        class="btn-primary w-full py-4 rounded-xl text-lg font-bold shadow-lg shadow-yellow-400/20">
                    Buat Pesanan Sekarang
                </button>
                <p class="text-center text-gray-500 text-xs">
                    *Dengan menekan tombol, Anda menyetujui syarat & ketentuan layanan kami.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

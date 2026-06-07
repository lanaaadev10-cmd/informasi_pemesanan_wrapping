@extends('layouts.dashboard_customer')

@section('title', 'Edit Pesanan Offline')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="mb-8">
        <a href="{{ route('admin.offline.orders.index') }}" class="inline-flex items-center gap-1 text-[10px] text-gray-500 hover:text-[#f2994a] transition-colors font-medium mb-4">
            <i class="ph-bold ph-arrow-left text-sm"></i> Kembali
        </a>
        <h1 class="text-3xl font-black text-white tracking-tight">
            Edit <span class="text-[#f2994a]">Pesanan</span> Offline
        </h1>
        <p class="text-gray-400 mt-1">Kode: <span class="font-mono text-white font-bold">{{ $order->kode_pesanan }}</span></p>
    </div>

    <form method="POST" action="{{ route('admin.offline.orders.update', $order->id_pesanan) }}" class="space-y-8">
        @csrf @method('PUT')

        <div class="bg-[#121212] border border-[#1f2937] rounded-3xl p-8 space-y-5">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <i class="ph-bold ph-user-circle text-[#f2994a]"></i> Data Pelanggan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Pelanggan *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required
                           class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor WhatsApp *</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $order->whatsapp_number) }}" required
                           class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat</label>
                <textarea name="address" rows="2"
                          class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">{{ old('address', $order->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Catatan Pesanan</label>
                <textarea name="catatan_pesanan" rows="2"
                          class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">{{ old('catatan_pesanan', $order->catatan_admin) }}</textarea>
            </div>
        </div>

        <div class="bg-[#121212] border border-[#1f2937] rounded-3xl p-8 space-y-5">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <i class="ph-bold ph-currency-circle-dollar text-[#f2994a]"></i> Pembayaran
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Pemesanan *</label>
                    <input type="date" name="tanggal_pemesanan" value="{{ old('tanggal_pemesanan', \Carbon\Carbon::parse($order->tanggal_pesan)->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Total Harga *</label>
                    <input type="number" name="total_harga" value="{{ old('total_harga', $order->total_harga) }}" required min="0" step="0.01"
                           class="w-full px-4 py-3 bg-[#1c1c1c] border border-white/10 rounded-xl text-white text-sm focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] outline-none">
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit"
                    class="px-8 py-4 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all active:scale-95 flex items-center gap-2 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                <i class="ph-bold ph-check-circle text-sm"></i> Perbarui Pesanan
            </button>
            <a href="{{ route('admin.offline.orders.index') }}"
               class="px-8 py-4 bg-white/5 text-gray-300 font-bold text-xs uppercase tracking-widest rounded-xl border border-white/10 hover:bg-white/10 transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

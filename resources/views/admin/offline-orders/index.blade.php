@extends('layouts.dashboard_customer')

@section('title', 'Pemesanan Offline')

@section('content')
<div class="max-w-7xl mx-auto py-8 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Pemesanan <span class="text-[#f2994a]">Offline</span></h1>
            <p class="text-gray-400 mt-1 text-sm">Kelola pesanan yang dibuat secara manual oleh admin</p>
        </div>
        <a href="{{ route('admin.offline.orders.create') }}"
           class="px-6 py-3 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all flex items-center gap-2 shadow-lg">
            <i class="ph-bold ph-plus-circle text-sm"></i> Buat Pesanan
        </a>
    </div>

    @if(session('toast_success'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl text-sm font-bold">
            {{ session('toast_success') }}
        </div>
    @endif

    <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#0c0c0c] border-b border-white/5">
                    <th class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kode</th>
                    <th class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">WhatsApp</th>
                    <th class="px-6 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Total</th>
                    <th class="px-6 py-4 text-center text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($orders as $index => $order)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-white font-bold">{{ $order->kode_pesanan }}</td>
                    <td class="px-6 py-4">
                        <span class="text-white font-bold text-sm">{{ $order->customer_name }}</span>
                        @if($order->user)
                            <span class="text-[10px] text-gray-500 block">@ {{ $order->user->name }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $order->whatsapp_number }}</td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right font-extrabold text-white">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $warna = match($order->status) {
                                'selesai' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30',
                                'menunggu_pembayaran' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/30',
                                'ditolak' => 'text-red-400 bg-red-500/10 border-red-500/30',
                                default => 'text-blue-400 bg-blue-500/10 border-blue-500/30',
                            };
                        @endphp
                        <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border {{ $warna }}">
                            {{ $order->label_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.offline.orders.edit', $order->id_pesanan) }}"
                               class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-gray-400 hover:text-white text-[10px] font-bold transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.offline.orders.destroy', $order->id_pesanan) }}"
                                  onsubmit="return confirm('Hapus pesanan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-500/10 border border-red-500/30 rounded-lg text-red-400 hover:text-red-300 text-[10px] font-bold transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ph-bold ph-handshake text-2xl text-gray-600"></i>
                        </div>
                        <p class="font-bold text-white mb-1">Belum Ada Pesanan Offline</p>
                        <p class="text-xs">Buat pesanan offline pertama dengan klik tombol "Buat Pesanan"</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

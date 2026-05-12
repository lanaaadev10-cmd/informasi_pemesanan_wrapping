@extends('layouts.tampilan_utama')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('pesanan.index') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2 mb-4">
                    <i class="ph ph-arrow-left"></i> Kembali ke Riwayat
                </a>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    Pesanan {{ $pesanan->kode_pesanan }}
                </h1>
            </div>
            <div class="px-4 py-2 rounded-xl border border-white/10 bg-white/5">
                <p class="text-gray-400 text-xs mb-1 uppercase tracking-widest">Status Pesanan</p>
                <span class="font-bold text-yellow-400 uppercase">{{ str_replace('_', ' ', $pesanan->status) }}</span>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-success mb-6 px-4 py-3 rounded-xl text-green-200 bg-green-900/40 border border-green-500/30">
            ✅ {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                {{-- DETAIL ITEM --}}
                <div class="glass-card p-6" data-aos="fade-up">
                    <h2 class="text-xl font-semibold text-white mb-6">Item Pesanan</h2>
                    <div class="space-y-4">
                        @foreach($pesanan->details as $item)
                        <div class="flex justify-between items-start border-b border-white/5 pb-4 last:border-0 last:pb-0">
                            <div>
                                <h3 class="text-white font-medium">{{ $item->layanan->nama_paket }}</h3>
                                <p class="text-gray-400 text-sm">{{ $item->jumlah }}x @ Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                @if($item->catatan_custom)
                                <p class="text-yellow-400 text-xs mt-1 italic">📝 {{ $item->catatan_custom }}</p>
                                @endif
                            </div>
                            <p class="text-white font-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- DATA PENGIRIMAN --}}
                <div class="glass-card p-6" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-xl font-semibold text-white mb-6">Informasi Pengiriman</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">Nama Penerima</p>
                            <p class="text-white font-medium">{{ $pesanan->form->nama_pemesan }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase mb-1">No. WhatsApp</p>
                            <p class="text-white font-medium">{{ $pesanan->form->no_hp }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-gray-400 text-xs uppercase mb-1">Alamat Lengkap</p>
                            <p class="text-white font-medium">{{ $pesanan->form->alamat_pengiriman }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- TOTAL & PEMBAYARAN --}}
                <div class="glass-card p-6" data-aos="fade-left">
                    <h2 class="text-xl font-semibold text-white mb-6">Pembayaran</h2>
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-gray-400">Total Tagihan</span>
                        <span class="text-2xl font-bold text-yellow-400">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>

                    @if($pesanan->status === 'diverifikasi' || $pesanan->status === 'menunggu_pembayaran')
                        {{-- FORM UPLOAD BUKTI --}}
                        <div class="border-t border-white/10 pt-6 mt-6">
                            <p class="text-white text-sm font-medium mb-4">Upload Bukti Transfer</p>
                            <form action="{{ route('pesanan.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <select name="metode_pembayaran" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-white text-sm">
                                        <option value="" disabled selected>Pilih Bank/E-Wallet</option>
                                        <option value="BCA">BCA (1234567890)</option>
                                        <option value="Mandiri">Mandiri (0987654321)</option>
                                        <option value="DANA">DANA (08123456789)</option>
                                    </select>
                                    <input type="file" name="bukti_transfer" required accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-yellow-400 file:text-black hover:file:bg-yellow-500">
                                    <button type="submit" class="btn-primary w-full py-3 rounded-xl text-sm font-bold">Kirim Bukti Bayar</button>
                                </div>
                            </form>
                        </div>
                    @elseif($pesanan->pembayaran)
                        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                            <p class="text-gray-400 text-xs uppercase mb-2">Bukti Pembayaran Terkirim</p>
                            <p class="text-white text-sm">Metode: {{ $pesanan->pembayaran->metode_pembayaran }}</p>
                            <p class="text-white text-sm">Status: <span class="text-yellow-400 uppercase font-bold">{{ $pesanan->pembayaran->verifikasi_admin }}</span></p>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm italic">Silakan tunggu verifikasi admin sebelum melakukan pembayaran.</p>
                    @endif
                </div>

                {{-- NOTIFIKASI / CATATAN ADMIN --}}
                @if($pesanan->catatan_admin)
                <div class="glass-card p-6 border-l-4 border-yellow-400" data-aos="fade-left" data-aos-delay="100">
                    <h3 class="text-yellow-400 font-bold text-sm uppercase mb-2">Catatan Admin</h3>
                    <p class="text-white text-sm leading-relaxed">{{ $pesanan->catatan_admin }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

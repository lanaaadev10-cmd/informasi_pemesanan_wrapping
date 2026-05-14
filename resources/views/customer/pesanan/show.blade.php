@extends('layouts.dashboard_customer')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<div class="max-w-6xl mx-auto pb-20" data-aos="fade-up">
    <!-- Breadcrumb & Title -->
    <div class="mb-12">
        <a href="{{ route('pesanan.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-orange-600 transition-colors mb-6">
            <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-2">Order <span class="text-orange-600">Detail.</span></h1>
                <p class="text-xs font-bold text-gray-400">ID Transaksi: <span class="text-gray-900">#{{ $pesanan->kode_pesanan }}</span> • {{ $pesanan->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="w-12 h-12 bg-white border border-gray-100 rounded-2xl flex items-center justify-center text-gray-400 hover:text-orange-600 hover:border-orange-200 transition-all shadow-sm">
                    <i class="ph-bold ph-printer text-xl"></i>
                </button>
                <a href="https://wa.me/08567890889?text=Halo Admin, saya ingin menanyakan pesanan #{{ $pesanan->kode_pesanan }}" target="_blank" class="flex items-center gap-3 px-6 py-3 bg-red-50 text-red-600 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:bg-red-100 transition-all border border-red-100">
                    <i class="ph-bold ph-warning-circle text-lg"></i> LAPOR KENDALA
                </a>
            </div>
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="soft-card p-8 mb-10 overflow-x-auto">
        <div class="flex items-center justify-between min-w-[600px] relative px-4">
            {{-- Line Background --}}
            <div class="absolute top-1/2 left-0 w-full h-[2px] bg-gray-100 -translate-y-1/2 z-0"></div>
            
            @php
                $statuses = [
                    ['id' => 'menunggu_verifikasi', 'label' => 'Verifikasi', 'icon' => 'ph-magnifying-glass'],
                    ['id' => 'menunggu_pembayaran', 'label' => 'Pembayaran', 'icon' => 'ph-credit-card'],
                    ['id' => 'menunggu_konfirmasi', 'label' => 'Validasi', 'icon' => 'ph-clock-clockwise'],
                    ['id' => 'dibayar', 'label' => 'Proses', 'icon' => 'ph-gear'],
                    ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'ph-check-circle'],
                ];
                $currentIdx = collect($statuses)->search(fn($s) => $s['id'] === $pesanan->status);
                if ($pesanan->status === 'dibatalkan') $currentIdx = -1;
            @endphp

            @foreach($statuses as $index => $status)
                @php
                    $isCompleted = $currentIdx > $index || ($pesanan->status === 'selesai');
                    $isActive = $currentIdx === $index;
                @endphp
                <div class="relative z-10 flex flex-col items-center gap-3 bg-white px-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500 {{ $isCompleted ? 'bg-green-500 text-white shadow-lg shadow-green-100' : ($isActive ? 'bg-orange-600 text-white shadow-lg shadow-orange-200' : 'bg-gray-50 text-gray-300') }}">
                        <i class="ph-bold {{ $status['icon'] }} text-xl"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $isActive ? 'text-orange-600' : 'text-gray-400' }}">{{ $status['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left Column: Details -->
        <div class="lg:col-span-8 space-y-10">
            
            <!-- Items Table -->
            <div class="soft-card overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-3">
                        <i class="ph-fill ph-shopping-cart text-orange-600 text-xl"></i> Daftar Layanan
                    </h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                                <th class="px-8 py-5">Layanan</th>
                                <th class="px-8 py-5 text-center">Qty</th>
                                <th class="px-8 py-5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($pesanan->details as $item)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="font-black text-gray-900 text-base mb-1">{{ $item->layanan->nama_layanan }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Premium Wrapping Service</p>
                                </td>
                                <td class="px-8 py-6 text-center font-black text-gray-900">{{ $item->jumlah }}x</td>
                                <td class="px-8 py-6 text-right font-black text-gray-900 text-lg italic">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-8 bg-gray-900 text-white flex justify-between items-center">
                    <p class="text-xs font-black uppercase tracking-[0.3em] opacity-60">Total Pembayaran</p>
                    <p class="text-3xl font-black italic tracking-tighter">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Customer & Vehicle Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="soft-card p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                            <i class="ph-fill ph-user-circle text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Info Pemesan</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">Customer Identity</p>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex justify-between border-b border-gray-50 pb-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama</span>
                            <span class="text-xs font-bold text-gray-900">{{ $pesanan->form->nama_pemesan }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-4">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">WhatsApp</span>
                            <span class="text-xs font-bold text-gray-900">{{ $pesanan->form->no_hp }}</span>
                        </div>
                    </div>
                </div>

                <div class="soft-card p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <i class="ph-fill ph-moped text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Info Kendaraan</h4>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">Vehicle Details</p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-700 leading-relaxed italic whitespace-pre-line">
                            {!! nl2br(e($pesanan->form->keterangan_tambahan)) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment & Support -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Dynamic Payment Section -->
            <div class="soft-card p-8 relative overflow-hidden">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-600/5 blur-3xl rounded-full"></div>
                
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-8 flex items-center gap-3">
                    <i class="ph-fill ph-shield-check text-orange-600 text-xl"></i> Panel Pembayaran
                </h3>

                @if($pesanan->status === 'menunggu_pembayaran')
                    <div class="space-y-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Metode Transfer:</p>
                        
                        {{-- BCA --}}
                        <div class="p-6 bg-gradient-to-br from-white to-gray-50 border border-gray-100 rounded-3xl flex justify-between items-center group hover:border-orange-500 transition-all shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-lg shadow-blue-100 italic">BCA</div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 tracking-tight">12345678</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase">Dantie Stiker</p>
                                </div>
                            </div>
                            <button onclick="copyToClipboard('12345678')" class="w-10 h-10 rounded-xl bg-white text-orange-600 hover:bg-orange-600 hover:text-white transition-all shadow-sm flex items-center justify-center">
                                <i class="ph-bold ph-copy text-lg"></i>
                            </button>
                        </div>

                        <form action="{{ route('pesanan.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="pt-6 border-t border-gray-50 space-y-4">
                            @csrf
                            <div class="relative group">
                                <input type="file" name="bukti_transfer" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center group-hover:border-orange-500 transition-all">
                                    <i class="ph-bold ph-cloud-arrow-up text-3xl text-gray-300"></i>
                                    <p class="text-[10px] font-black text-gray-400 mt-2 uppercase">Unggah Bukti</p>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-[10px] tracking-widest uppercase shadow-xl shadow-orange-100 hover:bg-orange-500 transition-all">
                                KONFIRMASI BAYAR
                            </button>
                        </form>
                    </div>

                @elseif($pesanan->status === 'menunggu_konfirmasi')
                    <div class="text-center py-10 space-y-4">
                        <div class="w-20 h-20 bg-orange-50 rounded-[2rem] flex items-center justify-center mx-auto text-orange-600">
                            <i class="ph-fill ph-hourglass-high text-4xl animate-spin-slow"></i>
                        </div>
                        <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Memvalidasi Bukti</h4>
                        <p class="text-[10px] font-bold text-gray-400 leading-relaxed px-6">Admin sedang mengecek transfer Anda. Mohon tunggu sejenak.</p>
                    </div>

                @elseif($pesanan->status === 'dibayar' || $pesanan->status === 'selesai')
                    <div class="space-y-4">
                        <div class="p-6 bg-green-50 rounded-3xl border border-green-100 text-center">
                            <i class="ph-fill ph-check-circle text-green-600 text-4xl mb-4"></i>
                            <h4 class="text-xs font-black text-green-800 uppercase tracking-widest mb-1">Pembayaran Terverifikasi</h4>
                            <p class="text-[10px] font-bold text-green-700 opacity-70 italic">Terima kasih atas kepercayaannya.</p>
                        </div>
                        <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] tracking-widest uppercase hover:bg-orange-600 transition-all shadow-xl">
                            <i class="ph-bold ph-file-pdf text-xl text-orange-500"></i> UNDUH STRUK RESMI
                        </a>
                    </div>
                @elseif($pesanan->status === 'menunggu_verifikasi')
                    <div class="text-center py-10 space-y-4 opacity-60">
                        <i class="ph-fill ph-shield-check text-4xl text-gray-300"></i>
                        <p class="text-[10px] font-bold text-gray-400 italic px-6 leading-relaxed">Pintu pembayaran akan terbuka setelah Admin memverifikasi detail pesanan Anda.</p>
                    </div>
                @endif
            </div>

            <!-- Enhanced Support Card -->
            <div class="soft-card p-8 bg-gray-900 text-white relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-orange-600/10 rounded-full blur-3xl group-hover:bg-orange-600/20 transition-all duration-700"></div>
                <h4 class="text-sm font-black uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                    <i class="ph-fill ph-headset text-orange-600"></i> Bantuan Kilat
                </h4>
                <p class="text-[10px] font-bold text-gray-400 leading-relaxed mb-8 opacity-80 italic">Menemukan kesalahan pada data pesanan? Atau ingin mengganti jadwal? Tim kami siap membantu Anda 24/7 via WhatsApp.</p>
                <a href="https://wa.me/08567890889?text=Halo Admin, ada kendala pada pesanan #{{ $pesanan->kode_pesanan }}" target="_blank" class="flex items-center justify-center gap-4 w-full py-4 bg-white/5 border border-white/10 rounded-2xl font-black text-[10px] tracking-widest uppercase hover:bg-orange-600 hover:border-orange-500 transition-all relative z-10">
                    <i class="ph-fill ph-whatsapp-logo text-xl text-green-500"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

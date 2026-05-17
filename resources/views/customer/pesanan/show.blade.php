@extends('layouts.dashboard_customer')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <style>
        .debit-card-glow {
            background: linear-gradient(135deg, #1e3c72 0%, #1a2a6c 50%, #240b36 100%);
            box-shadow: 0 12px 25px -5px rgba(26, 42, 108, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.2);
        }
        .debit-card-glow::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }
        .success-glow {
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }
        .luxury-shadow {
            box-shadow: 0 15px 35px -8px rgba(0, 0, 0, 0.04), 0 4px 12px -2px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        .luxury-shadow:hover {
            box-shadow: 0 25px 45px -12px rgba(242, 84, 27, 0.08), 0 5px 15px -3px rgba(0, 0, 0, 0.02), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        .pulse-ring {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            animation: pulse-ring-anim 2s infinite;
        }
        @keyframes pulse-ring-anim {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>

    <!-- Header Panel with Soft Background Radial Glow -->
    <div class="relative bg-white rounded-[32px] border border-stone-200/50 p-6 md:p-8 luxury-shadow overflow-hidden mb-8 transition-all duration-500">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-[#f2541b]/5 blur-[70px] rounded-full"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 blur-[70px] rounded-full"></div>
        
        <a href="{{ route('pesanan.index') }}" class="relative z-10 inline-flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-stone-400 hover:text-[#f2541b] transition-colors mb-4">
            &larr; Kembali ke Daftar Pesanan
        </a>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="font-serif text-3.5xl md:text-4xl font-black text-stone-900 tracking-tight mb-2">
                    Detail <span class="text-[#f2541b]">Pesanan.</span>
                </h1>
                <p class="text-xs font-bold text-stone-500 flex items-center gap-2 flex-wrap">
                    <span>ID Transaksi:</span> 
                    <span class="bg-stone-50 text-stone-950 font-mono px-2 py-1 rounded-lg border border-stone-200/40 shadow-inner">#{{ $pesanan->kode_pesanan }}</span> 
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-300"></span> 
                    <span>{{ $pesanan->created_at->format('d M Y, H:i') }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button onclick="window.print()" class="w-12 h-12 bg-white border border-stone-200/80 rounded-2xl flex items-center justify-center text-stone-400 hover:text-[#f2541b] hover:border-[#f2541b]/30 transition-all shadow-md active:scale-95">
                    <i class="ph-bold ph-printer text-lg"></i>
                </button>
                <a href="https://wa.me/08567890889?text=Halo Admin, saya ingin menanyakan pesanan #{{ $pesanan->kode_pesanan }}" target="_blank" class="flex items-center gap-2 px-5 py-3 bg-red-50 text-red-600 rounded-2xl font-bold text-[9px] tracking-widest uppercase hover:bg-red-100 transition-all border border-red-200/30 shadow-sm active:scale-95">
                    ⚠️ LAPOR KENDALA
                </a>
            </div>
        </div>
    </div>

    <!-- Status Timeline (Desktop: Horizontal) -->
    <div class="hidden md:block bg-white rounded-[32px] border border-stone-200/60 p-8 mb-8 luxury-shadow transition-all duration-500 overflow-x-auto">
        <div class="flex items-center justify-between min-w-[700px] relative px-6">
            {{-- Line Background --}}
            <div class="absolute top-1/2 left-0 w-full h-[3px] bg-stone-100/80 -translate-y-1/2 z-0"></div>
            
            @php
                $statuses = [
                    ['id' => 'menunggu_verifikasi', 'label' => 'Verifikasi', 'icon' => 'ph-magnifying-glass', 'desc' => 'Menunggu Admin memverifikasi pesanan Anda.'],
                    ['id' => 'menunggu_pembayaran', 'label' => 'Pembayaran', 'icon' => 'ph-credit-card', 'desc' => 'Menunggu bukti transfer pembayaran Anda.'],
                    ['id' => 'menunggu_konfirmasi', 'label' => 'Validasi', 'icon' => 'ph-clock-clockwise', 'desc' => 'Bukti pembayaran sedang divalidasi oleh admin.'],
                    ['id' => 'dibayar', 'label' => 'Proses', 'icon' => 'ph-gear', 'desc' => 'Pengerjaan wrapping sedang berlangsung di bengkel.'],
                    ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'ph-check-circle', 'desc' => 'Pesanan selesai dan garansi wrapping telah aktif.'],
                ];
                $currentIdx = collect($statuses)->search(fn($s) => $s['id'] === $pesanan->status);
                if ($pesanan->status === 'dibatalkan') $currentIdx = -1;
            @endphp

            @foreach($statuses as $index => $status)
                @php
                    $isCompleted = $currentIdx > $index || ($pesanan->status === 'selesai');
                    $isActive = $currentIdx === $index;
                @endphp
                <div class="relative z-10 flex flex-col items-center gap-3 bg-white px-5">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500 {{ $isCompleted ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20 ring-4 ring-emerald-500/10' : ($isActive ? 'bg-[#f2541b] text-white shadow-lg shadow-[#f2541b]/30 ring-4 ring-[#f2541b]/20 scale-110' : 'bg-stone-50 text-stone-300 border border-stone-200 shadow-inner') }}">
                        <i class="ph-bold {{ $status['icon'] }} text-base"></i>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $isActive ? 'text-[#f2541b]' : 'text-stone-400' }}">{{ $status['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status Timeline (Mobile: Vertical & Ultra-Professional) -->
    <div class="block md:hidden bg-white rounded-[32px] border border-stone-200/60 p-6 mb-8 luxury-shadow transition-all duration-500">
        <h3 class="text-[10px] font-black text-stone-900 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-stone-100 pb-3">
            <span>📌</span> Status Pesanan Anda
        </h3>
        <div class="space-y-6 relative pl-6">
            {{-- Vertical Connecting Line --}}
            <div class="absolute left-[11px] top-2 bottom-2 w-[2px] bg-stone-100"></div>

            @foreach($statuses as $index => $status)
                @php
                    $isCompleted = $currentIdx > $index || ($pesanan->status === 'selesai');
                    $isActive = $currentIdx === $index;
                @endphp
                <div class="flex gap-4 items-start relative">
                    {{-- Indicator Dot --}}
                    <div class="absolute -left-[20px] w-6 h-6 rounded-full flex items-center justify-center transition-all duration-500 z-10 {{ $isCompleted ? 'bg-emerald-600 text-white shadow-md ring-4 ring-emerald-500/10' : ($isActive ? 'bg-[#f2541b] text-white shadow-lg ring-4 ring-[#f2541b]/20 scale-105' : 'bg-stone-50 text-stone-300 border border-stone-200 shadow-inner') }}">
                        <i class="ph-bold {{ $status['icon'] }} text-[9px]"></i>
                    </div>
                    <div class="flex-grow pl-2">
                        <span class="text-[10px] font-black uppercase tracking-widest {{ $isActive ? 'text-[#f2541b]' : 'text-stone-900' }}">{{ $status['label'] }}</span>
                        @if($isActive)
                            <p class="text-[9px] font-medium text-stone-500 mt-1 leading-relaxed bg-stone-50/50 p-2.5 rounded-xl border border-stone-200/30">
                                @if($pesanan->status === 'menunggu_verifikasi')
                                    Sedang dicek oleh admin. Konfirmasi dan instruksi bayar segera kami kirim ke WA Anda.
                                @elseif($pesanan->status === 'menunggu_pembayaran')
                                    Pesanan disetujui! Silakan transfer pembayaran Anda ke rekening di panel bawah.
                                @elseif($pesanan->status === 'menunggu_konfirmasi')
                                    Bukti bayar diterima! Admin sedang mencocokkan mutasi rekening kami.
                                @elseif($pesanan->status === 'dibayar')
                                    Pembayaran terverifikasi! Kendaraan Anda telah masuk antrean pengerjaan di workshop kami.
                                @elseif($pesanan->status === 'selesai')
                                    Pengerjaan selesai dengan sempurna! Garansi stiker/wrapping Anda telah aktif.
                                @else
                                    {{ $status['desc'] }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Details -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Items Table & List -->
            <div class="bg-white rounded-[32px] overflow-hidden border border-stone-200/50 luxury-shadow transition-all duration-500">
                <div class="p-6 border-b border-stone-100 bg-stone-50/40 flex justify-between items-center">
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-widest flex items-center gap-2">
                        <span>🛒</span> Daftar Layanan
                    </h3>
                </div>
                
                <!-- Desktop Table (Hidden on Mobile) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[9px] font-black text-stone-400 uppercase tracking-wider border-b border-stone-100 bg-stone-50/20">
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach($pesanan->details as $item)
                            <tr class="group hover:bg-stone-50/30 transition-all duration-300">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Service Thumbnail -->
                                        <div class="w-14 h-14 rounded-[16px] overflow-hidden bg-stone-100 flex items-center justify-center border border-stone-200/60 shrink-0 shadow-sm relative group-hover:scale-105 transition-transform duration-500">
                                            @if($item->layanan->foto_contoh)
                                                <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover" alt="{{ $item->layanan->nama_layanan }}">
                                            @else
                                                <div class="text-[8px] text-stone-400 font-bold uppercase">No Image</div>
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="font-bold text-stone-900 text-sm mb-0.5 group-hover:text-[#f2541b] transition-colors">{{ $item->layanan->nama_layanan }}</p>
                                            <p class="text-[8px] font-bold text-[#f2541b] uppercase tracking-widest">{{ $item->layanan->kategori ?? 'Premium' }} Service</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-stone-900 text-xs">{{ $item->jumlah }}x</td>
                                <td class="px-6 py-5 text-right font-serif font-black text-stone-900 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Service List (Hidden on Desktop) -->
                <div class="block sm:hidden divide-y divide-stone-100 p-5 space-y-4">
                    @foreach($pesanan->details as $item)
                    <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                        <div class="w-14 h-14 rounded-[14px] overflow-hidden bg-stone-100 flex items-center justify-center border border-stone-200/60 shrink-0 relative shadow-sm">
                            @if($item->layanan->foto_contoh)
                                <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover" alt="{{ $item->layanan->nama_layanan }}">
                            @else
                                <div class="text-[8px] text-stone-400 font-bold uppercase">No Image</div>
                            @endif
                            <div class="absolute -bottom-1.5 -right-1.5 bg-[#151413] text-white text-[8px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-md">
                                {{ $item->jumlah }}
                            </div>
                        </div>
                        <div class="overflow-hidden flex-grow">
                            <span class="block text-xs font-bold text-stone-900 tracking-tight truncate">{{ $item->layanan->nama_layanan }}</span>
                            <span class="text-[8px] font-black text-[#f2541b] uppercase tracking-widest block mt-0.5">{{ $item->layanan->kategori ?? 'Premium' }} Service</span>
                            <span class="block text-xs font-serif font-black text-stone-950 mt-1">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="p-6 bg-[#151413] text-white flex justify-between items-center bg-gradient-to-r from-stone-950 to-stone-900 border-t border-white/5">
                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-60">Total Pembayaran</p>
                    <p class="font-serif text-2xl font-black text-white">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Customer & Vehicle Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Info Pemesan -->
                <div class="bg-white rounded-[32px] border border-stone-200/50 overflow-hidden luxury-shadow transition-all duration-500">
                    <div class="flex items-center gap-3 bg-gradient-to-br from-stone-50 to-stone-100/40 border-b border-stone-200/60 p-5">
                        <div class="w-10 h-10 rounded-xl bg-white border border-stone-200/60 flex items-center justify-center text-[#f2541b] shadow-sm">
                            <i class="ph-fill ph-user-circle text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-stone-900 uppercase tracking-widest">Info Pemesan</h4>
                            <p class="text-[8px] font-bold text-stone-400 uppercase tracking-wider mt-0.5">Customer Identity</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-4 bg-white">
                        <div class="flex justify-between border-b border-stone-100 pb-3">
                            <span class="text-[9px] font-bold text-stone-400 uppercase tracking-widest">Nama</span>
                            <span class="text-xs font-semibold text-stone-900">{{ $pesanan->form->nama_pemesan }}</span>
                        </div>
                        <div class="flex justify-between border-b border-stone-100 pb-3">
                            <span class="text-[9px] font-bold text-stone-400 uppercase tracking-widest">WhatsApp</span>
                            <span class="text-xs font-semibold text-stone-900">{{ $pesanan->form->no_hp }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Kendaraan -->
                <div class="bg-white rounded-[32px] border border-stone-200/50 overflow-hidden luxury-shadow transition-all duration-500">
                    <div class="flex items-center gap-3 bg-gradient-to-br from-stone-50 to-stone-100/40 border-b border-stone-200/60 p-5">
                        <div class="w-10 h-10 rounded-xl bg-white border border-stone-200/60 flex items-center justify-center text-stone-600 shadow-sm">
                            <i class="ph-fill ph-moped text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-stone-900 uppercase tracking-widest">Info Kendaraan</h4>
                            <p class="text-[8px] font-bold text-stone-400 uppercase tracking-wider mt-0.5">Vehicle Details</p>
                        </div>
                    </div>
                    <div class="p-6 bg-white space-y-2.5">
                        <div class="p-4 bg-stone-50/60 rounded-[22px] border border-stone-200/30 space-y-2.5">
                            @php
                                $lines = explode("\n", $pesanan->form->keterangan_tambahan);
                            @endphp
                            @foreach($lines as $line)
                                @if(trim($line))
                                    @php
                                        $parts = explode(":", $line, 2);
                                    @endphp
                                    <div class="flex justify-between items-start py-1 border-b border-stone-200/40 last:border-0">
                                        <span class="text-[9px] font-bold text-stone-400 uppercase tracking-widest">{{ isset($parts[0]) ? trim($parts[0]) : 'Info' }}</span>
                                        <span class="text-xs font-semibold text-stone-900 text-right">{{ isset($parts[1]) ? trim($parts[1]) : trim($line) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment & Support -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Dynamic Payment Section -->
            <div class="bg-white rounded-[32px] border border-stone-200/60 p-6 relative overflow-hidden luxury-shadow transition-all duration-500">
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#f2541b]/5 blur-3xl rounded-full"></div>
                
                <h3 class="text-[10px] font-black text-stone-900 uppercase tracking-widest mb-6 flex items-center gap-2 border-b border-stone-100 pb-3">
                    <span>🛡️</span> Panel Pembayaran
                </h3>

                @if($pesanan->status === 'menunggu_pembayaran')
                    <div class="space-y-5">
                        <p class="text-[9px] font-bold text-stone-400 uppercase tracking-widest mb-1">Metode Transfer:</p>
                        
                        {{-- BCA Debit Card Replica --}}
                        <div class="relative debit-card-glow rounded-2xl p-5 text-white flex flex-col justify-between aspect-[1.6/1] shadow-lg group overflow-hidden transition-transform duration-500 hover:scale-[1.02]">
                            <!-- Chip & BCA Logo -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-7 rounded bg-gradient-to-br from-amber-300 via-yellow-400 to-amber-500 border border-amber-600/50 shadow-inner flex items-center justify-center relative">
                                    <div class="w-full h-[1px] bg-stone-900/10 absolute top-1/2 left-0"></div>
                                    <div class="h-full w-[1px] bg-stone-900/10 absolute left-1/2 top-0"></div>
                                </div>
                                <span class="text-sm font-black italic tracking-wider text-blue-100">BCA</span>
                            </div>
                            <!-- Card Number -->
                            <div class="mb-4">
                                <span class="text-[9px] font-bold text-blue-200/70 block uppercase tracking-widest mb-0.5">Nomor Rekening</span>
                                <span class="text-lg font-mono font-black tracking-widest">1234 5678</span>
                            </div>
                            <!-- Card Holder -->
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-[8px] font-bold text-blue-200/70 block uppercase tracking-widest">Nama Rekening</span>
                                    <span class="text-xs font-bold tracking-wide">Dantie Stiker</span>
                                </div>
                                <button onclick="copyToClipboard('12345678')" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 border border-white/20 text-white transition-all shadow-sm flex items-center justify-center active:scale-90">
                                    <i class="ph-bold ph-copy text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <form action="{{ route('pesanan.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="pt-4 border-t border-stone-100 space-y-4">
                            @csrf
                            <div class="relative group">
                                <input type="file" name="bukti_transfer" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="w-full bg-stone-50 border-2 border-dashed border-stone-300 rounded-[20px] p-5 text-center group-hover:border-[#f2541b] group-hover:bg-[#f2541b]/5 transition-all">
                                    <i class="ph-bold ph-cloud-arrow-up text-3xl text-stone-300 group-hover:text-[#f2541b] transition-colors mb-1.5 block"></i>
                                    <p class="text-[10px] font-bold text-stone-400 group-hover:text-stone-900 transition-colors uppercase tracking-wider">Unggah Bukti Transfer</p>
                                    <p class="text-[8px] text-stone-400 mt-0.5">PNG, JPG, JPEG (Max 2MB)</p>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3.5 bg-[#f2541b] hover:bg-[#d33d0a] text-white rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md shadow-[#f2541b]/10 active:scale-95">
                                KONFIRMASI BAYAR
                            </button>
                        </form>
                    </div>

                @elseif($pesanan->status === 'menunggu_konfirmasi')
                    <div class="text-center py-8 space-y-4">
                        <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto text-[#f2541b] border border-orange-200/50 shadow-inner">
                            <i class="ph-fill ph-hourglass-high text-2xl animate-spin"></i>
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-stone-900 uppercase tracking-widest mb-1">Memvalidasi Bukti</h4>
                            <p class="text-[9px] font-medium text-stone-400 leading-relaxed px-4">Kami sedang mencocokkan transfer Anda dengan mutasi rekening kami. Mohon tunggu sejenak.</p>
                        </div>
                    </div>

                @elseif($pesanan->status === 'dibayar' || $pesanan->status === 'selesai')
                    <div class="space-y-4">
                        <div class="success-glow p-5 bg-gradient-to-br from-emerald-50 via-emerald-100/30 to-emerald-50/10 rounded-[22px] border border-emerald-200 text-center relative overflow-hidden">
                            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-600/5 rounded-full flex items-center justify-center select-none pointer-events-none">
                                <i class="ph-fill ph-check-circle text-5xl"></i>
                            </div>
                            <i class="ph-fill ph-check-circle text-emerald-600 text-4xl mb-3 block"></i>
                            <h4 class="text-[10px] font-black text-emerald-800 uppercase tracking-widest mb-1">Pembayaran Terverifikasi</h4>
                            <p class="text-[9px] font-medium text-emerald-700 opacity-80 italic">Terima kasih atas kepercayaannya.</p>
                        </div>
                        <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3.5 bg-[#151413] hover:bg-[#2c2b29] text-white rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                            <i class="ph-bold ph-file-pdf text-lg text-[#f2541b]"></i> UNDUH STRUK RESMI
                        </a>
                    </div>
                @elseif($pesanan->status === 'menunggu_verifikasi')
                    <div class="text-center py-10 space-y-4 opacity-75">
                        <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mx-auto text-stone-400 border border-stone-200/60 shadow-inner">
                            <i class="ph-fill ph-shield-check text-2xl"></i>
                        </div>
                        <p class="text-[9px] font-medium text-stone-400 italic px-4 leading-relaxed">Pintu pembayaran akan terbuka setelah Admin memverifikasi detail pesanan Anda.</p>
                    </div>
                @endif
            </div>

            <!-- Enhanced Support Card (Midnight Metal design) -->
            <div class="bg-gradient-to-br from-[#1c1b19] via-[#121110] to-[#0d0c0b] border border-white/5 rounded-[32px] p-6 text-white relative overflow-hidden group shadow-2xl transition-all duration-500 hover:scale-[1.01]">
                <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-[#f2541b]/10 rounded-full blur-3xl group-hover:bg-[#f2541b]/20 transition-all duration-700"></div>
                <h4 class="text-xs font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-headset text-[#f2541b] text-base"></i> Bantuan Kilat
                </h4>
                <p class="text-[10px] font-medium text-stone-400 leading-relaxed mb-6 opacity-80 italic">Menemukan kesalahan pada data pesanan? Atau ingin mengganti jadwal? Tim kami siap membantu Anda via WhatsApp.</p>
                <a href="https://wa.me/08567890889?text=Halo Admin, ada kendala pada pesanan #{{ $pesanan->kode_pesanan }}" target="_blank" class="pulse-ring flex items-center justify-center gap-2 w-full py-3.5 bg-white/5 border border-white/10 hover:border-[#f2541b]/40 rounded-xl font-bold text-[9px] tracking-widest uppercase hover:bg-[#f2541b] transition-all relative z-10 active:scale-95">
                    <i class="ph-fill ph-whatsapp-logo text-base text-emerald-500"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            if (typeof showToast === 'function') {
                showToast('Nomor rekening disalin!', 'success');
            } else {
                alert('Nomor rekening disalin!');
            }
        });
    }
</script>
@endsection

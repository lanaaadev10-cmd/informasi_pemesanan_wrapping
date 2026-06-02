@extends('layouts.dashboard_customer')

@section('title', 'Keranjang Belanja')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';
    $keranjangTitle = $profil->keranjang_title ?? 'Keranjang Belanja';
    $keranjangSubtitle = $profil->keranjang_subtitle ?? 'Tinjau pilihan layanan premium Anda sebelum melakukan pembayaran.';
@endphp

<style>
    :root {
        --accent-color: {{ $accentColor }};
    }
    .accent-bg { background-color: var(--accent-color); }
    .accent-color { color: var(--accent-color); }
</style>

@section('content')
<div class="max-w-6xl mx-auto py-6 space-y-8 relative overflow-hidden">

    <!-- Ambient glowing backdrop orb -->
    <div class="absolute top-10 left-1/3 -translate-x-1/2 w-[400px] h-[200px] rounded-full blur-[100px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative">
        <div>
            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">{{ $profil->keranjang_hero_text ?? 'YOUR SELECTION' }}</span>
            <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1">
                {{ $keranjangTitle }}
            </h1>
            <p class="text-gray-400 text-xs sm:text-sm font-light mt-1">{{ $keranjangSubtitle }}</p>
        </div>
        
        @if($keranjang && $keranjang->details->isNotEmpty())
            <div class="flex items-center shrink-0">
                <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh isi keranjang?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/10 hover:bg-red-500/15 border border-red-500/20 hover:border-red-500/35 text-red-400 hover:text-red-300 rounded-2xl font-bold text-[10px] tracking-wider uppercase transition-all active:scale-95 shadow-sm">
                        <i class="ph-bold ph-trash-simple text-xs"></i>
                        <span>Kosongkan Keranjang</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Main Grid Content -->
    @if(!$keranjang || $keranjang->details->isEmpty())
        <!-- Empty State in gorgeous dark premium layout -->
        <div class="bg-white/[0.01] border border-white/5 rounded-[32px] p-16 text-center shadow-lg relative overflow-hidden z-10">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#f2994a]/5 blur-[80px] rounded-full"></div>
            <div class="relative z-10 max-w-md mx-auto space-y-6">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-gray-400 mx-auto shadow-inner border border-white/5">
                    <i class="ph-bold ph-shopping-bag text-3xl text-gray-500"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-white">Keranjang Kosong</h3>
                    <p class="text-gray-400 text-xs font-light leading-relaxed">Sepertinya Anda belum memilih layanan wrapping premium terbaik untuk kendaraan Anda.</p>
                </div>
                <a href="{{ route('katalog.user') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-2xl font-extrabold text-xs tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
                    Explore Layanan &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 z-10 relative">
            
            <!-- Left Side: List of items -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($keranjang->details as $item)
                    @php
                        // Premium visual fallback selector based on catalog categories
                        $itemImage = $item->layanan->foto_contoh ? asset('storage/' . $item->layanan->foto_contoh) : asset('images/placeholder.svg');
                    @endphp

                    <div class="bg-white/[0.01] border border-white/5 rounded-[28px] overflow-hidden p-5 flex flex-col sm:flex-row items-center gap-6 group hover:border-[#f2994a]/25 hover:bg-white/[0.02] transition-all duration-300 relative shadow-md">
                        
                        <!-- Rounded visual thumbnail -->
                        <div class="w-24 h-24 rounded-2xl overflow-hidden bg-white/5 flex items-center justify-center shrink-0 border border-white/5 shadow-inner">
                            <img src="{{ $itemImage }}" alt="{{ $item->layanan->nama_layanan }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700">
                        </div>

                        <!-- Product details -->
                        <div class="flex-grow flex flex-col justify-between self-stretch py-1">
                            <div class="flex justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-bold text-[#f2994a] uppercase tracking-widest block font-mono">
                                        {{ $item->layanan->kategori ?? 'Layanan Premium' }}
                                    </span>
                                    <h3 class="text-base font-bold text-white group-hover:text-[#f2994a] transition-colors leading-tight line-clamp-1">
                                        {{ $item->layanan->nama_layanan }}
                                    </h3>
                                </div>

                                <!-- Delete button from figma -->
                                <form action="{{ route('keranjang.hapus', $item->id_detail) }}" method="POST" class="shrink-0" onsubmit="return confirm('Hapus layanan ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-1.5 text-xs text-red-500/80 hover:text-red-400 font-bold transition-all px-3 py-1.5 rounded-xl hover:bg-red-500/5 active:scale-95">
                                        <i class="ph ph-trash-simple text-sm"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Bottom control panel -->
                            <div class="flex flex-wrap items-end justify-between gap-4 mt-4">
                                <div class="flex items-center gap-6">
                                    
                                    <!-- Dynamic quantity buttons block -->
                                    <div class="space-y-1.5">
                                        <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest block">Jumlah Unit</span>
                                        <div class="flex items-center bg-black/40 p-1 rounded-xl border border-white/5">
                                            <!-- Decrease Button -->
                                            <button type="button" 
                                                    id="btn-dec-{{ $item->id_detail }}" 
                                                    onclick="changeQty({{ $item->id_detail }}, -1)" 
                                                    {{ $item->jumlah <= 1 ? 'disabled' : '' }} 
                                                    class="w-7 h-7 bg-white/5 hover:bg-white/10 rounded-lg flex items-center justify-center text-gray-300 hover:text-white transition-all disabled:opacity-20 disabled:cursor-not-allowed">
                                                <i class="ph ph-minus text-[10px]"></i>
                                            </button>
                                            
                                            <!-- Current Qty -->
                                            <span id="qty-{{ $item->id_detail }}" 
                                                  class="w-8 text-center text-xs font-bold text-white">
                                                {{ $item->jumlah }}
                                            </span>
                                            
                                            <!-- Increase Button -->
                                            <button type="button" 
                                                    id="btn-inc-{{ $item->id_detail }}" 
                                                    onclick="changeQty({{ $item->id_detail }}, 1)" 
                                                    class="w-7 h-7 bg-white/5 hover:bg-white/10 rounded-lg flex items-center justify-center text-gray-300 hover:text-white transition-all">
                                                <i class="ph ph-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="w-px h-8 bg-white/5 self-end"></div>

                                    <div class="space-y-1.5">
                                        <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest block">Harga Satuan</span>
                                        <span class="text-xs text-gray-400 font-medium block pb-1.5">
                                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Subtotal calculation display -->
                                <div class="text-right">
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest block mb-0.5">Subtotal</span>
                                    <span id="subtotal-{{ $item->id_detail }}" 
                                          class="text-[#f2994a] text-lg font-black"
                                          data-unit-price="{{ $item->harga_satuan }}">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if($item->catatan_custom)
                                <div class="mt-4 p-3 bg-white/[0.01] border border-white/5 rounded-2xl flex items-start gap-2.5">
                                    <span class="text-xs">📝</span>
                                    <p class="text-[10px] font-bold text-gray-400 leading-relaxed italic">{{ $item->catatan_custom }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Dynamic Dashed Add Service Shortcut -->
                <a href="{{ route('katalog.user') }}" 
                   class="border-2 border-dashed border-white/5 hover:border-[#f2994a]/25 bg-white/[0.01] hover:bg-white/[0.02] rounded-[28px] p-6 transition-all cursor-pointer flex flex-col items-center justify-center gap-2 group shadow-sm z-10 relative">
                    <div class="w-10 h-10 rounded-full bg-[#f2994a]/5 group-hover:bg-[#f2994a]/10 flex items-center justify-center text-[#f2994a] transition-all">
                        <i class="ph ph-plus text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-white tracking-wider uppercase">Tambah Layanan Lainnya</span>
                </a>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="lg:col-span-4 space-y-4">
                <div class="sticky top-24 space-y-6">
                    
                    @php
                        // Premium custom calculations for order summary realistic look
                        $subtotalVal = $keranjang->details->sum('subtotal');
                        $serviceCharge = 150000;
                        // Dynamic discount based on subtotal (5% up to 1.5M if total is over 10M)
                        $discount = $subtotalVal > 10000000 ? min(1500000, (int)($subtotalVal * 0.05)) : 0;
                        $grandTotal = $subtotalVal + $serviceCharge - $discount;
                    @endphp

                    <!-- 1. ORDER SUMMARY CARD -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-[32px] p-8 text-white relative overflow-hidden shadow-xl">
                        <!-- Decorative ambient glow orb inside panel -->
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#f2994a]/5 blur-[70px] rounded-full pointer-events-none"></div>

                        <h3 class="text-lg font-bold mb-8 flex items-center gap-2.5 relative z-10">
                            <span class="text-lg">🧾</span> Ringkasan Pesanan
                        </h3>

                        <!-- Detailed rows breakdown -->
                        <div class="space-y-4 mb-8 relative z-10 text-xs">
                            <div class="flex justify-between items-center text-gray-400">
                                <span class="font-bold uppercase tracking-widest text-[9px] font-mono">SUBTOTAL</span>
                                <span id="summary-subtotal" class="font-extrabold text-white text-sm">
                                    Rp {{ number_format($subtotalVal, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center text-gray-400">
                                <span class="font-bold uppercase tracking-widest text-[9px] font-mono">BIAYA LAYANAN</span>
                                <span class="font-extrabold text-white text-sm">
                                    Rp {{ number_format($serviceCharge, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-gray-400">
                                <span class="font-bold uppercase tracking-widest text-[9px] font-mono">DISKON MEMBER</span>
                                <span id="summary-discount" class="font-extrabold text-[#f2994a] text-sm">
                                    - Rp {{ number_format($discount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="w-full h-px bg-white/5 my-2"></div>
                            
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-0.5 block">TOTAL HARGA</span>
                                    <span id="summary-total" class="text-2xl font-black text-white">
                                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Promotional Code Box -->
                        <div class="space-y-1.5 mb-6">
                            <label class="text-[8px] font-bold text-gray-500 uppercase tracking-widest block font-mono">KODE PROMO</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                       placeholder="Masukkan kode..." 
                                       class="flex-grow bg-black/40 border border-white/5 rounded-xl px-4 py-2.5 text-xs text-white placeholder-gray-600 focus:outline-none focus:border-[#f2994a]/30 transition-all">
                                <button type="button" 
                                        class="bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold px-4 rounded-xl active:scale-95 transition-all text-gray-300">
                                    Terapkan
                                </button>
                            </div>
                        </div>

                        <!-- Direct Checkout Form Action -->
                        <a href="{{ route('pesanan.checkout.form') }}" 
                           class="relative z-10 w-full bg-[#f2994a] hover:bg-[#e28a44] text-black py-4 rounded-2xl font-extrabold text-center block text-xs tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                            Checkout Sekarang <i class="ph ph-arrow-right text-xs"></i>
                        </a>

                        <!-- Supported Payment Brands -->
                        <div class="mt-6 pt-5 border-t border-white/5 space-y-2">
                            <span class="text-[8px] font-bold text-gray-500 uppercase tracking-widest block text-center">Metode Pembayaran Tersedia</span>
                            <div class="flex justify-center items-center gap-4 text-gray-400 text-lg opacity-40">
                                <i class="ph ph-credit-card"></i>
                                <i class="ph ph-bank"></i>
                                <i class="ph ph-wallet"></i>
                            </div>
                        </div>
                    </div>

                    <!-- 2. WARRANTY TRUST BOX -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-[24px] p-5 flex gap-4 items-start shadow-sm z-10 relative">
                        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0 border border-white/5">
                            <i class="ph-bold ph-shield-check text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-[10px] font-bold text-white uppercase tracking-widest">Garansi Pemasangan</h4>
                            <p class="text-[9px] font-medium text-gray-500 leading-relaxed italic">
                                Setiap layanan wrapping kami mencakup garansi 1 tahun untuk kerutan atau gelembung udara.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    @endif
</div>

<!-- Quantity Adjustment Controller -->
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    async function changeQty(idDetail, delta) {
        const qtySpan = document.getElementById(`qty-${idDetail}`);
        const decBtn = document.getElementById(`btn-dec-${idDetail}`);
        const subtotalSpan = document.getElementById(`subtotal-${idDetail}`);
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryDiscount = document.getElementById('summary-discount');
        const summaryTotal = document.getElementById('summary-total');

        let currentQty = parseInt(qtySpan.textContent);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        // Optimistic UI updates
        qtySpan.textContent = newQty;
        decBtn.disabled = (newQty <= 1);

        try {
            // Update quantity via patch Ajax request
            const response = await fetch(`/api/keranjang/${idDetail}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ jumlah: newQty })
            });

            const data = await response.json();

            if (response.ok && data.status === 'ok') {
                const subtotal = data.data.subtotal;
                const totalSum = data.data.total; // Sum of details in db

                // Realistic dynamic totals computation
                const serviceFee = 150000;
                const discount = totalSum > 10000000 ? Math.min(1500000, Math.floor(totalSum * 0.05)) : 0;
                const grandTotal = totalSum + serviceFee - discount;

                // Bind updated state back to DOM displays
                qtySpan.textContent = data.data.jumlah;
                subtotalSpan.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                
                if (summarySubtotal) {
                    summarySubtotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalSum);
                }
                if (summaryDiscount) {
                    summaryDiscount.textContent = '- Rp ' + new Intl.NumberFormat('id-ID').format(discount);
                }
                if (summaryTotal) {
                    summaryTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
                }

                showToast('Keranjang berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (err) {
            // Revert state if Ajax fails
            qtySpan.textContent = currentQty;
            decBtn.disabled = (currentQty <= 1);
            showToast('Gagal memperbarui keranjang: ' + err.message, 'error');
            console.error('Cart increment adjustment error:', err);
        }
    }

    // Dynamic clean toast notifications
    function showToast(message, type = 'info') {
        const existing = document.querySelector('[data-toast]');
        if (existing) existing.remove();

        const colors = {
            success: 'bg-[#f2994a]/10 border-[#f2994a]/30 text-white',
            error: 'bg-red-500/10 border-red-500/30 text-red-300',
            info: 'bg-white/5 border-white/10 text-gray-300',
        };

        const toast = document.createElement('div');
        toast.setAttribute('data-toast', '');
        toast.className = `fixed bottom-6 right-6 p-4 rounded-2xl border ${colors[type]} max-w-sm z-50 shadow-lg animate-slide-in-up backdrop-blur-md`;
        toast.innerHTML = `
            <div class="flex items-center justify-between gap-4">
                <p class="text-xs font-bold tracking-wide">${message}</p>
                <button onclick="this.closest('[data-toast]').remove()" class="text-lg opacity-50 hover:opacity-100">&times;</button>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 4000);
    }

    // Load styles dynamically
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slide-in-up {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-in-up {
            animation: slide-in-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection

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
                <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirmEmptyCart(event, this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/10 hover:bg-red-500/15 border border-red-500/20 hover:border-red-500/35 text-red-400 hover:text-red-300 rounded-2xl font-bold text-[10px] tracking-wider uppercase transition-all active:scale-95 shadow-sm">
                        <i class="ph-bold ph-trash-simple text-xs"></i>
                            <span>{{ $profil->cta_kosongkan ?? 'Kosongkan Keranjang' }}</span>
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
                    <h3 class="text-xl font-bold text-white">{{ $profil->empty_keranjang_title ?? 'Keranjang Kosong' }}</h3>
                    <p class="text-gray-400 text-xs font-light leading-relaxed">{{ $profil->empty_keranjang_desc ?? 'Sepertinya Anda belum memilih layanan wrapping premium terbaik untuk kendaraan Anda.' }}</p>
                </div>
                <a href="{{ route('katalog.user') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-2xl font-extrabold text-xs tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
                    {{ $profil->cta_explore_layanan ?? 'Explore Layanan' }} &rarr;
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
                        $itemImage = \App\Helpers\StaticContent::fotoUrl($item->layanan->foto_contoh ?? '');
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
                                <form action="{{ route('keranjang.hapus', $item->id_detail) }}" method="POST" class="shrink-0" onsubmit="return confirmDeleteItem(event, this, '{{ $item->layanan->nama_layanan }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center gap-1.5 text-xs text-red-500/80 hover:text-red-400 font-bold transition-all px-3 py-1.5 rounded-xl hover:bg-red-500/5 active:scale-95">
                                        <i class="ph ph-trash-simple text-sm"></i>
                                        <span>{{ $profil->cta_hapus ?? 'Hapus' }}</span>
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
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest block mb-0.5">{{ $profil->label_subtotal ?? 'Subtotal' }}</span>
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
                    <span class="text-xs font-bold text-white tracking-wider uppercase">{{ $profil->cta_tambah_lainnya ?? 'Tambah Layanan Lainnya' }}</span>
                </a>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="lg:col-span-4 space-y-4">
                <div class="sticky top-24 space-y-6">
                    
                    @php
                        // Premium custom calculations for order summary realistic look
                        $subtotalVal = $keranjang->details->sum('subtotal');
                        $serviceCharge = 150000;
                        $grandTotal = $subtotalVal + $serviceCharge;
                    @endphp

                    <!-- 1. ORDER SUMMARY CARD -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-[32px] p-8 text-white relative overflow-hidden shadow-xl">
                        <!-- Decorative ambient glow orb inside panel -->
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#f2994a]/5 blur-[70px] rounded-full pointer-events-none"></div>

                        <h3 class="text-lg font-bold mb-8 flex items-center gap-2.5 relative z-10">
                            <span class="text-lg">🧾</span> {{ $profil->section_ringkasan_pesanan ?? 'Ringkasan Pesanan' }}
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
            const summaryTotal = document.getElementById('summary-total');

        let currentQty = parseInt(qtySpan.textContent);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        // Optimistic UI updates
        qtySpan.textContent = newQty;
        decBtn.disabled = (newQty <= 1);

        try {
            // Update quantity via patch Ajax request ke route web (session + CSRF)
            const response = await fetch('{{ route('keranjang.update', '__ID__') }}'.replace('__ID__', idDetail), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ jumlah: newQty })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // data.subtotal & data.total_payment sudah berformat "Rp ..."
                const subtotalStr = data.subtotal;
                const totalStr = data.total_payment;
                const totalSum = parseRupiah(totalStr);

                // Realistic dynamic totals computation
                const serviceFee = 150000;
                const grandTotal = totalSum + serviceFee;

                // Bind updated state back to DOM displays
                qtySpan.textContent = data.jumlah;
                subtotalSpan.textContent = subtotalStr;

                if (summarySubtotal) {
                    summarySubtotal.textContent = totalStr;
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

    // Ubah string "Rp 1.500.000" menjadi angka
    function parseRupiah(str) {
        const num = parseInt(String(str || '0').replace(/[^0-9]/g, ''));
        return isNaN(num) ? 0 : num;
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
        @keyframes modal-in {
            from { transform: scale(0.9) translateY(20px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }
        .animate-modal-in {
            animation: modal-in 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    `;
    document.head.appendChild(style);
</script>

{{-- Bridge: tampilkan flash message via showToast() styled, cegah dobel dengan toast layout --}}
@if(session('toast_success') || session('toast_error'))
    <script>
        document.getElementById('floating-toast')?.remove();
        document.getElementById('floating-toast-error')?.remove();
@if(session('toast_success'))
        showToast(@js(session('toast_success')), 'success');
@endif
@if(session('toast_error'))
        showToast(@js(session('toast_error')), 'error');
@endif
    </script>
@endif

{{-- Custom Confirmation Modal (pengganti confirm() native browser) --}}
<div id="confirm-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div id="modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-[#121212] border border-white/10 rounded-[28px] p-8 max-w-sm w-full mx-4 shadow-2xl animate-modal-in">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#f2994a]/5 blur-[60px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 text-center space-y-6">
            <div class="w-16 h-16 mx-auto rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                <i class="ph-bold ph-trash-simple text-2xl text-red-400"></i>
            </div>
            <div class="space-y-2">
                <h3 id="modal-title" class="text-lg font-extrabold text-white">Konfirmasi</h3>
                <p id="modal-message" class="text-xs text-gray-400 leading-relaxed"></p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="modal-cancel" class="flex-1 px-5 py-3 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white rounded-2xl font-extrabold text-[10px] tracking-wider uppercase transition-all active:scale-95">
                    Batal
                </button>
                <button type="button" id="modal-confirm" class="flex-1 px-5 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-extrabold text-[10px] tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(239,68,68,0.3)] active:scale-95">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let confirmResolve = null;

    function showConfirmModal(title, message, confirmText = 'Ya, Hapus') {
        return new Promise((resolve) => {
            confirmResolve = resolve;
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').innerHTML = message;
            document.getElementById('modal-confirm').textContent = confirmText;
            document.getElementById('confirm-modal').classList.remove('hidden');
        });
    }

    document.getElementById('modal-confirm').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(true);
    });

    document.getElementById('modal-cancel').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(false);
    });

    document.getElementById('modal-overlay').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(false);
    });

    async function confirmEmptyCart(event, form) {
        event.preventDefault();
        const confirmed = await showConfirmModal(
            'Kosongkan Keranjang',
            @js($profil->alert_konfirmasi_kosongkan ?? 'Apakah Anda yakin ingin mengosongkan seluruh isi keranjang? Tindakan ini tidak dapat dibatalkan.'),
            'Ya, Kosongkan'
        );
        if (confirmed) form.submit();
    }

    async function confirmDeleteItem(event, form, itemName) {
        event.preventDefault();
        const confirmed = await showConfirmModal(
            'Hapus Item',
            `<strong>${itemName}</strong><br>` + @js($profil->alert_hapus_keranjang ?? 'Apakah Anda yakin ingin menghapus layanan ini dari keranjang?'),
            'Ya, Hapus'
        );
        if (confirmed) form.submit();
    }
</script>
@endsection

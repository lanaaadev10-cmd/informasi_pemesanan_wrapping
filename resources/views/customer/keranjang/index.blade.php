@extends('layouts.dashboard_customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="font-serif text-3.5xl md:text-5xl font-black text-stone-900 tracking-tight mb-2 flex flex-wrap items-center gap-3">
                Keranjang <span class="text-[#f2541b]">Belanja.</span>
                @if($keranjang && $keranjang->details->count() > 0)
                    <span class="inline-flex items-center justify-center px-3.5 py-1 bg-[#f2541b]/10 text-[#f2541b] rounded-full text-xs font-black tracking-normal self-center">
                        {{ $keranjang->details->count() }} Item
                    </span>
                @endif
            </h1>
            <p class="text-stone-500 font-medium text-xs md:text-sm">Tinjau layanan pilihan Anda sebelum memproses pengerjaan.</p>
        </div>
        
        @if($keranjang && $keranjang->details->count() > 0)
        <div class="flex items-center shrink-0">
            <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh isi keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100/75 text-red-650 hover:text-red-700 rounded-xl font-bold text-xs tracking-wider uppercase border border-red-100/50 transition-all active:scale-95 shadow-sm">
                    <i class="ph-bold ph-trash-simple text-sm"></i>
                    <span>Kosongkan</span>
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(!$keranjang || $keranjang->details->isEmpty())
        <div class="bg-white rounded-[32px] p-16 text-center border border-stone-200/50 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#f2541b]/5 blur-[60px] rounded-full"></div>
            <div class="relative z-10 max-w-md mx-auto">
                <div class="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center text-stone-300 mx-auto mb-6 shadow-inner">
                    <i class="ph-fill ph-shopping-cart-simple text-3xl"></i>
                </div>
                <h3 class="font-serif text-2xl font-black text-stone-900 mb-2">Belum Ada Layanan.</h3>
                <p class="text-stone-400 text-xs font-medium mb-8 leading-relaxed">Sepertinya Anda belum memilih layanan wrapping terbaik untuk kendaraan Anda.</p>
                <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#151413] hover:bg-[#2a2927] text-white rounded-full font-bold text-xs tracking-wider uppercase transition-all shadow-md">
                    Explore Layanan &rarr;
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Items List -->
            <div class="lg:col-span-8 space-y-6">
                @foreach($keranjang->details as $item)
                <div class="bg-white rounded-[28px] overflow-hidden border border-stone-200/60 hover:border-[#f2541b]/30 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300 flex flex-col sm:flex-row p-5 gap-6 group relative">
                    {{-- Product Image --}}
                    <div class="w-full sm:w-36 aspect-[4/3] sm:aspect-square rounded-[20px] overflow-hidden bg-[#e9e8e4] flex items-center justify-center text-stone-400 font-bold shrink-0 relative">
                        @if($item->layanan->foto_contoh)
                            <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="text-[10px] uppercase tracking-wider text-stone-500 font-serif">[ Layout ]</div>
                        @endif
                    </div>

                    <div class="flex-grow flex flex-col justify-between py-1">
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <div>
                                <span class="text-[9px] font-bold text-[#f2541b] uppercase tracking-widest mb-1.5 block">{{ $item->layanan->kategori ?? 'Premium Service' }}</span>
                                <h3 class="font-serif text-lg font-black text-stone-900 group-hover:text-[#f2541b] transition-colors line-clamp-1">{{ $item->layanan->nama_layanan }}</h3>
                            </div>
                            <form action="{{ route('keranjang.hapus', $item->id_detail) }}" method="POST" class="shrink-0" onsubmit="return confirm('Hapus layanan ini dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-xl text-stone-400 hover:text-red-500 hover:bg-stone-50 border border-transparent hover:border-stone-200 transition-all flex items-center justify-center">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mt-auto">
                            <div class="flex items-center gap-6">
                                {{-- Interactive E-commerce Quantity Adjuster --}}
                                <div class="flex flex-col gap-1">
                                    <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest block">Jumlah Unit</span>
                                    <div class="flex items-center bg-stone-50 p-1 rounded-xl border border-stone-200/50">
                                        <!-- Decrease -->
                                        <button type="button" id="btn-dec-{{ $item->id_detail }}" onclick="changeQty({{ $item->id_detail }}, -1)" {{ $item->jumlah <= 1 ? 'disabled' : '' }} class="w-7 h-7 bg-white rounded-lg flex items-center justify-center text-stone-600 hover:text-stone-900 border border-stone-200 shadow-sm transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                                            <i class="ph-bold ph-minus text-[10px]"></i>
                                        </button>
                                        
                                        <!-- Display -->
                                        <span id="qty-{{ $item->id_detail }}" class="w-8 text-center text-xs font-black text-stone-950">{{ $item->jumlah }}</span>
                                        
                                        <!-- Increase -->
                                        <button type="button" id="btn-inc-{{ $item->id_detail }}" onclick="changeQty({{ $item->id_detail }}, 1)" class="w-7 h-7 bg-white rounded-lg flex items-center justify-center text-stone-600 hover:text-stone-900 border border-stone-200 shadow-sm transition-all">
                                            <i class="ph-bold ph-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="w-px h-8 bg-stone-200 self-end"></div>
                                <div class="flex flex-col justify-end h-full">
                                    <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest block">Harga Satuan</span>
                                    <span class="font-bold text-xs text-stone-500 mt-1">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest block mb-0.5">Subtotal</span>
                                <span id="subtotal-{{ $item->id_detail }}" class="font-serif text-lg md:text-xl font-black text-stone-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($item->catatan_custom)
                        <div class="mt-4 p-3 bg-stone-50 rounded-xl flex items-start gap-2 border border-stone-200/40">
                            <span class="text-xs">📝</span>
                            <p class="text-[10px] font-bold text-stone-500 leading-relaxed italic">{{ $item->catatan_custom }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-6">
                    <div class="bg-[#151413] rounded-[32px] p-8 text-white relative overflow-hidden shadow-xl shadow-stone-900/10">
                        {{-- Decorative Elements --}}
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#f2541b]/20 blur-[80px] rounded-full"></div>

                        <h3 class="font-serif text-lg font-black mb-8 flex items-center gap-3 relative z-10">
                            <span class="text-lg">🧾</span> Ringkasan Belanja
                        </h3>

                        <div class="space-y-4 mb-8 relative z-10">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-stone-500 uppercase tracking-widest">SUBTOTAL</span>
                                <span id="summary-subtotal" class="font-serif font-black text-base">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-stone-500 uppercase tracking-widest">PAJAK (0%)</span>
                                <span class="font-serif font-black text-base text-[#f2541b]">Free</span>
                            </div>
                            <div class="w-full h-px bg-white/10 my-2"></div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest mb-0.5 block">TOTAL PEMBAYARAN</span>
                                    <span id="summary-total" class="font-serif text-2xl font-black text-white">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('pesanan.checkout.form') }}" class="relative z-10 w-full bg-[#f2541b] hover:bg-[#d33d0a] text-white py-4 rounded-xl font-bold text-center block text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                            LANJUT KE CHECKOUT &rarr;
                        </a>
                    </div>

                    {{-- Trust Badge --}}
                    <div class="bg-white rounded-[24px] p-5 border border-dashed border-stone-300">
                        <div class="flex gap-4 items-center">
                            <div class="w-10 h-10 rounded-xl bg-stone-50 flex items-center justify-center text-[#f2541b] shrink-0 shadow-sm">
                                <i class="ph-fill ph-shield-check text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-stone-900 uppercase tracking-widest">Garansi Pengerjaan</h4>
                                <p class="text-[9px] font-medium text-stone-400 leading-relaxed italic">Proteksi maksimal untuk kendaraan kesayangan Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Include API client
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

        // Optimistic UI update
        qtySpan.textContent = newQty;
        decBtn.disabled = (newQty <= 1);

        try {
            // Use API endpoint or fallback to form submission
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
                const subtotal = data.data?.subtotal || (newQty * parseInt(subtotalSpan.dataset.unitPrice));
                const total = data.data?.total || summaryTotal.dataset.total;

                // Update display with backend values
                qtySpan.textContent = data.data?.jumlah || newQty;
                subtotalSpan.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
                if (summarySubtotal) summarySubtotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                if (summaryTotal) summaryTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);

                showToast('Keranjang diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (err) {
            // Rollback on error
            qtySpan.textContent = currentQty;
            decBtn.disabled = (currentQty <= 1);
            showToast('Gagal memperbarui keranjang: ' + err.message, 'error');
            console.error('Cart update error:', err);
        }
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existing = document.querySelector('[data-toast]');
        if (existing) existing.remove();

        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800',
        };

        const toast = document.createElement('div');
        toast.setAttribute('data-toast', '');
        toast.className = `fixed bottom-6 right-6 p-4 rounded-lg border ${colors[type]} max-w-sm z-50 shadow-lg animate-slide-in-up`;
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium">${message}</p>
                <button onclick="this.closest('[data-toast]').remove()" class="ml-4 text-lg opacity-50 hover:opacity-100">×</button>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }

    // Add CSS for animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slide-in-up {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-in-up {
            animation: slide-in-up 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection

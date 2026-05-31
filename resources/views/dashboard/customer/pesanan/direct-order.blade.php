@extends('layouts.dashboard_customer')

@section('title', 'Pesan Langsung - ' . ($package->nama_layanan ?? 'Pesanan Baru'))

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-8">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-gray-400">
        <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
        <i class="ph-bold ph-caret-right text-[10px]"></i>
        <span class="text-white">Pesan Langsung</span>
    </div>

    <!-- Header -->
    <div class="space-y-2">
        <h1 class="text-3xl font-extrabold text-white">Pesan Layanan</h1>
        <p class="text-gray-400 text-sm">Lanjutkan pemesanan untuk paket pilihan Anda</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Package Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Package Card -->
            <div class="bg-white/[0.01] border border-white/5 rounded-2xl overflow-hidden shadow-lg">
                <!-- Image -->
                <div class="relative h-64 bg-gradient-to-br from-[#f2994a]/20 to-transparent overflow-hidden">
                    @if($package->foto_contoh)
                        <img src="{{ str_starts_with($package->foto_contoh, 'http') ? $package->foto_contoh : asset('storage/' . $package->foto_contoh) }}"
                             alt="{{ $package->nama_layanan }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#f2994a]/30 to-[#f2994a]/10 flex items-center justify-center">
                            <i class="ph-bold ph-package text-6xl text-[#f2994a]/40"></i>
                        </div>
                    @endif

                    @if($package->tipe_paket)
                        <div class="absolute top-4 right-4 bg-[#f2994a]/90 backdrop-blur-sm px-3 py-1 rounded-full">
                            <p class="text-[10px] font-bold text-white uppercase tracking-wider">
                                {{ $package->tipe_paket }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block mb-2">
                            {{ $package->kategori ?? 'Layanan Premium' }}
                        </span>
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $package->nama_layanan }}</h2>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $package->deskripsi ?? 'Deskripsi layanan' }}</p>
                    </div>

                    <!-- Features -->
                    @if($package->fitur && is_array($package->fitur) && count($package->fitur) > 0)
                    <div class="pt-4 border-t border-white/5">
                        <h3 class="text-xs font-bold text-gray-300 uppercase tracking-widest mb-3">Fitur Layanan</h3>
                        <div class="space-y-2">
                            @foreach($package->fitur as $fitur)
                            <div class="flex items-start gap-2">
                                <i class="ph-bold ph-check-circle text-[#f2994a] text-sm mt-0.5 shrink-0"></i>
                                <span class="text-xs text-gray-300">{{ is_array($fitur) ? ($fitur['nama_fitur'] ?? '') : $fitur }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Info -->
                    <div class="pt-4 border-t border-white/5 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest block">Harga</span>
                            <p class="text-2xl font-bold text-[#f2994a] mt-1">
                                Rp {{ number_format($package->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        @if($package->estimasi_waktu)
                        <div>
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest block">Estimasi Waktu</span>
                            <p class="text-lg font-bold text-white mt-1">{{ $package->estimasi_waktu }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Order Summary & Actions -->
        <div class="space-y-4">

            <!-- Order Summary -->
            <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-6 shadow-lg sticky top-24">

                <h3 class="text-lg font-bold text-white mb-6">Ringkasan Pesanan</h3>

                <div class="space-y-4 mb-6 pb-6 border-b border-white/5">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Paket</span>
                        <span class="text-sm font-bold text-white">1x {{ $package->nama_layanan }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Harga Satuan</span>
                        <span class="text-sm font-bold text-white">Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex justify-between items-end">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Total</span>
                        <div class="text-right">
                            <span class="text-2xl font-black text-[#f2994a]">
                                Rp {{ number_format($package->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Add to Cart -->
                    <button id="add-to-cart-btn"
                            class="w-full py-3 px-4 bg-[#f2994a]/20 border border-[#f2994a] text-[#f2994a] rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-[#f2994a]/30 transition-all duration-200">
                        <i class="ph-bold ph-shopping-cart-simple mr-2"></i> Tambah ke Keranjang
                    </button>

                    <!-- Direct Checkout -->
                    <form action="{{ route('pesanan.checkout.store') }}" method="POST" id="direct-checkout-form">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id_layanan }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="direct_order" value="1">

                        <button type="submit"
                                class="w-full py-3 px-4 bg-[#f2994a] text-black rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-[#e28a44] transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-lightning-fill"></i> Checkout Sekarang
                        </button>
                    </form>

                    <!-- Back Button -->
                    <a href="{{ route('dashboard') }}"
                       class="w-full py-2 px-4 bg-white/5 border border-white/10 text-white rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-white/10 transition-all text-center">
                        Kembali
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-3">
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-shield-check text-[#f2994a] text-lg shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-white uppercase tracking-widest">Aman & Terpercaya</p>
                            <p class="text-[10px] text-gray-400 mt-1">Pembayaran aman dengan enkripsi tingkat bank</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-check-circle text-[#f2994a] text-lg shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-white uppercase tracking-widest">Garansi Kepuasan</p>
                            <p class="text-[10px] text-gray-400 mt-1">Garansi 1 tahun untuk semua layanan</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.getElementById('add-to-cart-btn');

        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', async function(e) {
                e.preventDefault();

                try {
                    const response = await fetch('/api/keranjang/item', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            id_layanan: {{ $package->id_layanan }},
                            quantity: 1
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showToast('✓ Paket ditambahkan ke keranjang!', 'success');
                        addToCartBtn.disabled = true;
                        addToCartBtn.classList.add('opacity-50');

                        // Redirect ke keranjang setelah 2 detik
                        setTimeout(() => {
                            window.location.href = '{{ route("keranjang.index") }}';
                        }, 2000);
                    } else if (response.status === 422) {
                        showToast('⚠ ' + data.message, 'warning');
                    } else {
                        showToast('✗ Gagal menambahkan ke keranjang', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('✗ Terjadi kesalahan', 'error');
                }
            });
        }

        const showToast = (message, type = 'info') => {
            const container = document.getElementById('toast-container');
            const toastEl = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-green-500/90' :
                           type === 'error' ? 'bg-red-500/90' :
                           type === 'warning' ? 'bg-yellow-500/90' :
                           'bg-blue-500/90';

            toastEl.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg backdrop-blur-sm mb-2 animate-slide-in`;
            toastEl.textContent = message;

            container.appendChild(toastEl);

            setTimeout(() => {
                toastEl.classList.add('animate-slide-out');
                setTimeout(() => toastEl.remove(), 300);
            }, 3000);
        };
    });
</script>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }

    .animate-slide-out {
        animation: slide-out 0.3s ease-out;
    }
</style>
@endpush

@endsection

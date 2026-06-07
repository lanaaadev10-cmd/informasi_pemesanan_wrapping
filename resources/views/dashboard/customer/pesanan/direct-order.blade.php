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
                        <img src="{{ asset('storage/' . $package->foto_contoh) }}"
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
                                <span class="text-xs text-gray-300">{{ $fitur['nama_fitur'] }}</span>
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

                <h3 class="text-lg font-bold text-white mb-6">{{ $profil->section_ringkasan_pesanan ?? 'Ringkasan Pesanan' }}</h3>

                <!-- Quantity Selector -->
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/5">
                    <span class="text-xs text-gray-400">Jumlah</span>
                    <div class="flex items-center gap-3 bg-white/5 rounded-xl p-1">
                        <button type="button" id="qty-dec"
                                class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#f2994a] text-white hover:text-black flex items-center justify-center font-bold text-sm transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                                onclick="ubahJumlah(-1)" disabled>
                            <i class="ph-bold ph-minus"></i>
                        </button>
                        <span id="qty-display" class="text-lg font-bold text-white w-8 text-center">1</span>
                        <button type="button" id="qty-inc"
                                class="w-8 h-8 rounded-lg bg-white/10 hover:bg-[#f2994a] text-white hover:text-black flex items-center justify-center font-bold text-sm transition-all"
                                onclick="ubahJumlah(1)">
                            <i class="ph-bold ph-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-white/5">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Paket</span>
                        <span class="text-sm font-bold text-white"><span id="qty-label">1</span>x {{ $package->nama_layanan }}</span>
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
                            <span class="text-2xl font-black text-[#f2994a]" id="total-harga">
                                Rp {{ number_format($package->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Add to Cart -->
                    <form action="{{ route('keranjang.tambah') }}" method="POST" id="form-cart">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $package->id_layanan }}">
                        <input type="hidden" name="jumlah" id="input-jumlah-cart" value="1">
                        <button type="submit"
                                class="w-full py-3 px-4 bg-[#f2994a]/20 border border-[#f2994a] text-[#f2994a] rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-[#f2994a]/30 transition-all duration-200">
                            <i class="ph-bold ph-shopping-cart-simple mr-2"></i> {{ $profil->cta_tambah_keranjang ?? 'Tambah ke Keranjang' }}
                        </button>
                    </form>

                    <!-- Direct Checkout -->
                    <form action="{{ route('keranjang.tambah') }}" method="POST" id="form-checkout">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $package->id_layanan }}">
                        <input type="hidden" name="jumlah" id="input-jumlah-checkout" value="1">
                        <input type="hidden" name="direct_checkout" value="1">

                        <button type="submit"
                                class="w-full py-3 px-4 bg-[#f2994a] text-black rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-[#e28a44] transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-lightning-fill"></i> Checkout Sekarang
                        </button>
                    </form>

                    <!-- Back Button -->
                    <a href="{{ route('dashboard') }}"
                       class="w-full py-2 px-4 bg-white/5 border border-white/10 text-white rounded-lg font-bold text-xs uppercase tracking-wide hover:bg-white/10 transition-all text-center">
                        {{ $profil->cta_kembali ?? 'Kembali' }}
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-3">
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-shield-check text-[#f2994a] text-lg shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-white uppercase tracking-widest">Aman & Terpercaya</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $profil->layanan_pembayaran_aman ?? 'Pembayaran aman dengan enkripsi tingkat bank' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-check-circle text-[#f2994a] text-lg shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-white uppercase tracking-widest">Garansi Kepuasan</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $profil->layanan_garansi_text ?? 'Garansi 1 tahun untuk semua layanan' }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>

<script>
    let jumlah = 1;
    const hargaSatuan = {{ $package->harga }};

    function ubahJumlah(delta) {
        jumlah = Math.max(1, jumlah + delta);
        document.getElementById('qty-display').textContent = jumlah;
        document.getElementById('qty-label').textContent = jumlah;
        document.getElementById('input-jumlah-cart').value = jumlah;
        document.getElementById('input-jumlah-checkout').value = jumlah;
        document.getElementById('qty-dec').disabled = jumlah <= 1;

        const total = hargaSatuan * jumlah;
        document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>

@endsection

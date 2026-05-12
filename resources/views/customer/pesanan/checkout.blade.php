@extends('layouts.tampilan_utama')

@section('title', 'Checkout Step-by-Step')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- STEP INDICATOR --}}
        <div class="mb-12">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 w-full h-0.5 bg-white/10 -z-10"></div>
                
                {{-- Step 1 --}}
                <div class="step-item flex flex-col items-center gap-2">
                    <div id="circle-1" class="w-10 h-10 rounded-full bg-yellow-400 text-black flex items-center justify-center font-bold transition-all duration-300">1</div>
                    <span class="text-xs text-white font-medium uppercase tracking-wider">Kontak</span>
                </div>
                
                {{-- Step 2 --}}
                <div class="step-item flex flex-col items-center gap-2">
                    <div id="circle-2" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center font-bold transition-all duration-300">2</div>
                    <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Alamat</span>
                </div>
                
                {{-- Step 3 --}}
                <div class="step-item flex flex-col items-center gap-2">
                    <div id="circle-3" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center font-bold transition-all duration-300">3</div>
                    <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Konfirmasi</span>
                </div>
            </div>
        </div>

        <form action="{{ route('pesanan.checkout.store') }}" method="POST" id="multi-step-form">
            @csrf

            {{-- STEP 1: INFORMASI KONTAK --}}
            <div id="step-1-content" class="glass-card p-8 transition-all duration-500">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="ph ph-identification-card text-yellow-400"></i> Informasi Kontak
                </h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Nama Lengkap Pemesan</label>
                        <input type="text" name="nama_pemesan" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition outline-none"
                               value="{{ auth()->user()->name }}">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Nomor WhatsApp Aktif</label>
                        <input type="text" name="no_hp" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition outline-none"
                               placeholder="Contoh: 0812XXXXXXXX">
                        <p class="text-[10px] text-gray-500 mt-2 italic">*Admin akan menghubungi Anda melalui nomor ini.</p>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="button" onclick="nextStep(2)" class="btn-primary px-8 py-3 rounded-xl font-bold flex items-center gap-2">
                        Lanjut ke Alamat <i class="ph ph-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2: ALAMAT PENGIRIMAN --}}
            <div id="step-2-content" class="glass-card p-8 hidden transition-all duration-500">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="ph ph-map-pin text-yellow-400"></i> Alamat Pengiriman
                </h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Alamat Lengkap</label>
                        <textarea name="alamat_pengiriman" required rows="4"
                                  class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition outline-none"
                                  placeholder="Nama jalan, Nomor rumah, RT/RW, Kecamatan, Kota, Kode Pos..."></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2">Keterangan Tambahan (Opsional)</label>
                        <textarea name="keterangan_tambahan" rows="2"
                                  class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-yellow-400 transition outline-none"
                                  placeholder="Warna stiker, detail custom, atau instruksi pengiriman..."></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-between">
                    <button type="button" onclick="nextStep(1)" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                        <i class="ph ph-arrow-left"></i> Kembali
                    </button>
                    <button type="button" onclick="nextStep(3)" class="btn-primary px-8 py-3 rounded-xl font-bold flex items-center gap-2">
                        Lihat Ringkasan <i class="ph ph-arrow-right"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 3: KONFIRMASI AKHIR --}}
            <div id="step-3-content" class="glass-card p-8 hidden transition-all duration-500">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="ph ph-check-circle text-yellow-400"></i> Konfirmasi Pesanan
                </h2>
                
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10 mb-8">
                    <h3 class="text-gray-400 text-xs uppercase tracking-widest font-bold mb-4">Item yang Dipesan</h3>
                    <div class="space-y-3">
                        @foreach($keranjang->details as $item)
                        <div class="flex justify-between items-center">
                            <span class="text-white text-sm">{{ $item->layanan->nama_paket }} (x{{ $item->jumlah }})</span>
                            <span class="text-white font-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-white/10 mt-4 pt-4 flex justify-between items-center text-xl">
                        <span class="text-white font-bold">Total</span>
                        <span class="text-yellow-400 font-extrabold">Rp{{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button type="button" onclick="nextStep(2)" class="text-gray-400 hover:text-white transition flex items-center gap-2">
                        <i class="ph ph-arrow-left"></i> Kembali
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-black px-10 py-4 rounded-xl font-extrabold shadow-lg shadow-green-500/20 transition-all flex items-center gap-2">
                        SELESAI & BUAT PESANAN <i class="ph ph-paper-plane-tilt"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function nextStep(step) {
        // Hide all contents
        document.getElementById('step-1-content').classList.add('hidden');
        document.getElementById('step-2-content').classList.add('hidden');
        document.getElementById('step-3-content').classList.add('hidden');

        // Show active content
        document.getElementById('step-' + step + '-content').classList.remove('hidden');

        // Update indicators
        const circles = [1, 2, 3];
        circles.forEach(c => {
            const circle = document.getElementById('circle-' + c);
            if (c < step) {
                // Completed
                circle.innerHTML = '✓';
                circle.className = 'w-10 h-10 rounded-full bg-green-500 text-black flex items-center justify-center font-bold transition-all duration-300';
            } else if (c === step) {
                // Active
                circle.innerHTML = c;
                circle.className = 'w-10 h-10 rounded-full bg-yellow-400 text-black flex items-center justify-center font-bold transition-all duration-300';
            } else {
                // Future
                circle.innerHTML = c;
                circle.className = 'w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center font-bold transition-all duration-300';
            }
        });
    }
</script>
@endsection

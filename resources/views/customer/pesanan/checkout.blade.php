@extends('layouts.dashboard_customer')

@section('title', 'Proses Pemesanan')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-6">
    <!-- Header -->
    <div class="mb-16" data-aos="fade-down">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">Selesaikan <span class="text-orange-600 italic">Pendaftaran.</span></h1>
        <p class="text-gray-400 mt-2 font-medium italic">Ikuti langkah-langkah di bawah untuk memproses pesanan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 space-y-4">
            <div id="step-nav-1" class="step-nav flex items-center gap-4 p-5 rounded-2xl bg-gray-900 text-white shadow-2xl transition-all duration-300">
                <div class="step-dot w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold shadow-lg shadow-orange-900/20">1</div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Langkah 01</span>
                    <span class="font-bold text-sm tracking-tight">Detail Kendaraan</span>
                </div>
            </div>
            <div id="step-nav-2" class="step-nav flex items-center gap-4 p-5 rounded-2xl bg-white text-gray-400 border border-gray-100 transition-all duration-300">
                <div class="step-dot w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold">2</div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Langkah 02</span>
                    <span class="font-bold text-sm tracking-tight">Kontak & Jadwal</span>
                </div>
            </div>
            <div id="step-nav-3" class="step-nav flex items-center gap-4 p-5 rounded-2xl bg-white text-gray-400 border border-gray-100 transition-all duration-300">
                <div class="step-dot w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold">3</div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">Langkah 03</span>
                    <span class="font-bold text-sm tracking-tight">Konfirmasi Akhir</span>
                </div>
            </div>
        </div>

        <!-- Form Panels -->
        <div class="lg:col-span-9">
            <form action="{{ route('pesanan.checkout.store') }}" method="POST" id="checkout-form">
                @csrf

                <!-- PANEL 1 -->
                <div id="step-panel-1" class="step-panel space-y-8">
                    <div class="soft-card p-10 relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] rounded-full"></div>
                        <h3 class="text-2xl font-black text-gray-900 mb-10 flex items-center gap-4">
                            <i class="ph-fill ph-car text-orange-600 bg-orange-50 p-4 rounded-2xl"></i> Detail Kendaraan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Model / Brand Kendaraan</label>
                                <input type="text" name="model_kendaraan" placeholder="Contoh: Toyota GR Supra" required
                                       class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Warna Saat Ini</label>
                                <input type="text" name="warna_kendaraan" placeholder="Contoh: Matte Black" required
                                       class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Alamat Pengerjaan (Lengkap)</label>
                                <textarea name="alamat_pengiriman" rows="4" placeholder="Masukkan alamat lengkap..." required
                                          class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-10 mt-10 border-t border-gray-100">
                            <button type="button" onclick="goToStep(2)" class="group px-8 py-4 bg-orange-600 rounded-xl text-white font-black text-xs tracking-widest uppercase transition-all hover:scale-105 active:scale-95 shadow-lg">
                                VERIFIKASI DETAIL <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2 -->
                <div id="step-panel-2" class="step-panel hidden space-y-8">
                    <div class="soft-card p-10 relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/5 blur-[100px] rounded-full"></div>
                        <h3 class="text-2xl font-black text-gray-900 mb-10 flex items-center gap-4">
                            <i class="ph-fill ph-calendar-check text-orange-600 bg-orange-50 p-4 rounded-2xl"></i> Kontak & Jadwal
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" value="{{ auth()->user()->name }}" required
                                       class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">WhatsApp Aktif</label>
                                <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" required
                                       class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Lokasi Pengerjaan</label>
                                <select name="lokasi_pengerjaan" required
                                        class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-black focus:ring-2 focus:ring-orange-500 outline-none transition-all appearance-none">
                                    <option value="toko">Datang ke Toko</option>
                                    <option value="rumah">Panggil ke Rumah (Home Service)</option>
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Rencana Jadwal</label>
                                <input type="datetime-local" name="jadwal_pengerjaan" required
                                       class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-3">
                                <label class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 px-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="keterangan_tambahan" rows="3" placeholder="Contoh: Ingin warna doff, pengerjaan pagi hari, dll..."
                                          class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-gray-900 font-bold focus:ring-2 focus:ring-orange-500 outline-none transition-all"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-10 mt-10 border-t border-gray-100">
                            <button type="button" onclick="goToStep(1)" class="flex items-center gap-2 text-gray-400 font-bold text-xs hover:text-orange-600 transition-all">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </button>
                            <button type="button" onclick="goToStep(3)" class="group px-8 py-4 bg-orange-600 rounded-xl text-white font-black text-xs tracking-widest uppercase transition-all hover:scale-105 active:scale-95 shadow-lg">
                                LANJUT KE KONFIRMASI <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 3 -->
                <div id="step-panel-3" class="step-panel hidden space-y-8">
                    <div class="soft-card p-10 bg-gray-900 text-white relative overflow-hidden shadow-2xl">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/30 blur-[100px] rounded-full"></div>
                        <h3 class="text-2xl font-black mb-12 flex items-center gap-4 relative z-10">
                            <i class="ph-fill ph-receipt text-orange-500 bg-white/5 p-4 rounded-2xl"></i> Tinjauan Pesanan
                        </h3>

                        <div class="space-y-6 relative z-10 mb-12">
                            @foreach($keranjang->details as $item)
                            <div class="flex justify-between items-center py-6 border-b border-white/5 gap-6 group">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 overflow-hidden flex-shrink-0 border border-white/10 group-hover:border-orange-500 transition-colors">
                                        @if($item->layanan->foto_contoh)
                                            <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white/20">
                                                <i class="ph-bold ph-sketch-logo text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-lg italic text-white group-hover:text-orange-500 transition-colors">{{ $item->layanan->nama_layanan }}</span>
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $item->jumlah }}x Unit</span>
                                    </div>
                                </div>
                                <span class="font-black text-orange-500 italic text-xl">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            
                            <div class="pt-6 flex justify-between items-center">
                                <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Total Pembayaran</span>
                                <span class="text-3xl font-black text-white italic">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-10 mt-10 border-t border-white/10 relative z-10">
                            <button type="button" onclick="goToStep(2)" class="text-gray-500 font-bold text-xs hover:text-white transition-all">
                                Kembali ke Kontak
                            </button>
                            <button type="submit" class="group px-10 py-4 bg-white rounded-xl text-orange-600 font-black text-sm tracking-widest uppercase hover:scale-105 active:scale-95 shadow-xl shadow-orange-900/20">
                                SELESAIKAN PENDAFTARAN <i class="ph-bold ph-rocket-launch ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function goToStep(step) {
        // Hide all panels
        document.querySelectorAll('.step-panel').forEach(p => p.classList.add('hidden'));
        
        // Show target panel
        const target = document.getElementById('step-panel-' + step);
        if(target) {
            target.classList.remove('hidden');
            
            // Show floating text if step is 2 or 3
            if(step === 2) {
                showToast('Silahkan lanjut ke langkah kedua', 'success');
            } else if(step === 3) {
                showToast('Hampir selesai! Silahkan tinjau pesanan Anda', 'success');
            }
        }

        // Update Nav Sidebar
        document.querySelectorAll('.step-nav').forEach((nav, index) => {
            const i = index + 1;
            const dot = nav.querySelector('.step-dot');
            
            if (i < step) {
                nav.className = "step-nav flex items-center gap-4 p-5 rounded-2xl bg-orange-50 text-orange-600 border border-orange-100 transition-all duration-300 shadow-sm";
                dot.className = "step-dot w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold shadow-md";
                dot.innerHTML = '✓';
            } else if (i === step) {
                nav.className = "step-nav flex items-center gap-4 p-5 rounded-2xl bg-gray-900 text-white shadow-2xl transition-all duration-300";
                dot.className = "step-dot w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center font-bold shadow-lg shadow-orange-900/20";
                dot.innerHTML = i;
            } else {
                nav.className = "step-nav flex items-center gap-4 p-5 rounded-2xl bg-white text-gray-400 border border-gray-100 transition-all duration-300";
                dot.className = "step-dot w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center font-bold";
                dot.innerHTML = i;
            }
        });

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }
</script>
@endsection

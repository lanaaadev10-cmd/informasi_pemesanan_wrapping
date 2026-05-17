@extends('layouts.dashboard_customer')

@section('title', 'Proses Pemesanan')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="font-serif text-3.5xl md:text-5xl font-black text-stone-900 tracking-tight mb-2">
            Selesaikan <span class="text-[#f2541b]">Pendaftaran.</span>
        </h1>
        <p class="text-stone-400 font-medium text-xs md:text-sm">Ikuti langkah-langkah di bawah untuk memproses pesanan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-3 flex flex-row lg:flex-col gap-3 lg:gap-4 overflow-x-auto lg:overflow-visible pb-4 lg:pb-0 scrollbar-none shrink-0">
            <div id="step-nav-1" class="step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-[#151413] text-white shadow-xl transition-all duration-300 flex-1 min-w-[180px] lg:min-w-0 shrink-0">
                <div class="step-dot w-9 h-9 rounded-xl bg-[#f2541b] text-white flex items-center justify-center font-bold shadow-md shadow-[#f2541b]/20 shrink-0">1</div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Langkah 01</span>
                    <span class="font-bold text-xs tracking-tight">Detail Kendaraan</span>
                </div>
            </div>
            <div id="step-nav-2" class="step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-white text-stone-400 border border-stone-200/50 transition-all duration-300 flex-1 min-w-[180px] lg:min-w-0 shrink-0">
                <div class="step-dot w-9 h-9 rounded-xl bg-stone-100 flex items-center justify-center font-bold shrink-0">2</div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Langkah 02</span>
                    <span class="font-bold text-xs tracking-tight">Kontak & Jadwal</span>
                </div>
            </div>
            <div id="step-nav-3" class="step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-white text-stone-400 border border-stone-200/50 transition-all duration-300 flex-1 min-w-[180px] lg:min-w-0 shrink-0">
                <div class="step-dot w-9 h-9 rounded-xl bg-stone-100 flex items-center justify-center font-bold shrink-0">3</div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Langkah 03</span>
                    <span class="font-bold text-xs tracking-tight">Konfirmasi Akhir</span>
                </div>
            </div>
        </div>

        <!-- Form Panels -->
        <div class="lg:col-span-9">
            <form action="{{ route('pesanan.checkout.store') }}" method="POST" id="checkout-form">
                @csrf

                <!-- PANEL 1 -->
                <div id="step-panel-1" class="step-panel space-y-6">
                    <div class="bg-white rounded-[32px] p-8 md:p-10 border border-stone-200/50 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-48 h-48 bg-[#f2541b]/5 blur-[60px] rounded-full"></div>
                        <h3 class="font-serif text-xl font-black text-stone-900 mb-8 flex items-center gap-3">
                            <span class="text-lg">🚗</span> Detail Kendaraan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Model / Brand Kendaraan</label>
                                <input type="text" name="model_kendaraan" placeholder="Contoh: Toyota GR Supra" required
                                       class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Warna Saat Ini</label>
                                <input type="text" name="warna_kendaraan" placeholder="Contoh: Matte Black" required
                                       class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Alamat Pengerjaan (Lengkap)</label>
                                <textarea name="alamat_pengiriman" rows="4" placeholder="Masukkan alamat lengkap..." required
                                          class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 mt-8 border-t border-stone-100">
                            <button type="button" onclick="goToStep(2)" class="px-6 py-3.5 bg-[#f2541b] hover:bg-[#d33d0a] rounded-xl text-white font-bold text-xs tracking-wider uppercase transition-all shadow-md">
                                VERIFIKASI DETAIL &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2 -->
                <div id="step-panel-2" class="step-panel hidden space-y-6">
                    <div class="bg-white rounded-[32px] p-8 md:p-10 border border-stone-200/50 shadow-sm relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-48 h-48 bg-[#f2541b]/5 blur-[60px] rounded-full"></div>
                        <h3 class="font-serif text-xl font-black text-stone-900 mb-8 flex items-center gap-3">
                            <span class="text-lg">📅</span> Kontak & Jadwal
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Nama Pemesan</label>
                                <input type="text" name="nama_pemesan" value="{{ auth()->user()->name }}" required
                                       class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">WhatsApp Aktif</label>
                                <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" required
                                       class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Lokasi Pengerjaan</label>
                                <select name="lokasi_pengerjaan" required
                                        class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                                    <option value="toko">Datang ke Toko</option>
                                    <option value="rumah">Panggil ke Rumah (Home Service)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Rencana Jadwal</label>
                                <input type="datetime-local" name="jadwal_pengerjaan" required
                                       class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-wider text-stone-400 px-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="keterangan_tambahan" rows="3" placeholder="Contoh: Ingin warna doff, pengerjaan pagi hari, dll..."
                                          class="w-full bg-[#fcfbfa] border border-stone-200/60 rounded-xl px-5 py-4 text-stone-900 font-bold focus:ring-1 focus:ring-[#f2541b] focus:border-[#f2541b] outline-none transition-all"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-8 mt-8 border-t border-stone-100">
                            <button type="button" onclick="goToStep(1)" class="flex items-center gap-1.5 text-stone-400 font-bold text-xs hover:text-[#f2541b] transition-all">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </button>
                            <button type="button" onclick="goToStep(3)" class="px-6 py-3.5 bg-[#f2541b] hover:bg-[#d33d0a] rounded-xl text-white font-bold text-xs tracking-wider uppercase transition-all shadow-md">
                                LANJUT KE KONFIRMASI &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL 3 -->
                <div id="step-panel-3" class="step-panel hidden space-y-6">
                    <div class="bg-[#151413] rounded-[32px] p-8 md:p-10 text-white relative overflow-hidden shadow-xl shadow-stone-900/10">
                        <div class="absolute -top-20 -right-20 w-48 h-48 bg-[#f2541b]/20 blur-[80px] rounded-full"></div>
                        <h3 class="font-serif text-xl font-black mb-8 flex items-center gap-3 relative z-10">
                            <span class="text-lg">💬</span> Tinjauan Pesanan
                        </h3>

                        <div class="space-y-5 relative z-10 mb-8">
                            @foreach($keranjang->details as $item)
                            <div class="flex justify-between items-center py-4 border-b border-stone-800 gap-6 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white/5 overflow-hidden flex-shrink-0 border border-white/5 group-hover:border-[#f2541b] transition-colors flex items-center justify-center">
                                        @if($item->layanan->foto_contoh)
                                            <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                        @else
                                            <span class="text-[10px] uppercase text-stone-600 font-serif">IMG</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-serif font-black text-sm text-white group-hover:text-[#f2541b] transition-colors">{{ $item->layanan->nama_layanan }}</span>
                                        <span class="text-[9px] font-bold text-stone-500 uppercase tracking-widest">{{ $item->jumlah }}X UNIT</span>
                                    </div>
                                </div>
                                <span class="font-serif font-black text-[#f2541b] text-base">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            
                            <div class="pt-6 flex justify-between items-center">
                                <span class="text-stone-400 font-bold uppercase tracking-widest text-[10px]">TOTAL PEMBAYARAN</span>
                                <span class="font-serif text-2xl font-black text-white">Rp {{ number_format($keranjang->details->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-8 mt-8 border-t border-stone-850 relative z-10">
                            <button type="button" onclick="goToStep(2)" class="text-stone-500 font-bold text-xs hover:text-white transition-all">
                                &larr; Kembali ke Kontak
                            </button>
                            <button type="submit" class="px-6 py-3.5 bg-white hover:bg-stone-100 rounded-xl text-[#f2541b] font-black text-xs tracking-wider uppercase transition-all shadow-md">
                                SELESAIKAN PENDAFTARAN &rarr;
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
                nav.className = "step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-orange-50/50 text-[#f2541b] border border-orange-100 transition-all duration-300 shadow-sm flex-1 min-w-[180px] lg:min-w-0 shrink-0";
                dot.className = "step-dot w-9 h-9 rounded-xl bg-[#f2541b] text-white flex items-center justify-center font-bold shadow-sm shrink-0";
                dot.innerHTML = '✓';
            } else if (i === step) {
                nav.className = "step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-[#151413] text-white shadow-xl transition-all duration-300 flex-1 min-w-[180px] lg:min-w-0 shrink-0";
                dot.className = "step-dot w-9 h-9 rounded-xl bg-[#f2541b] text-white flex items-center justify-center font-bold shadow-md shadow-[#f2541b]/20 shrink-0";
                dot.innerHTML = i;
            } else {
                nav.className = "step-nav flex items-center gap-4 p-4.5 rounded-2xl bg-white text-stone-400 border border-stone-200/50 transition-all duration-300 flex-1 min-w-[180px] lg:min-w-0 shrink-0";
                dot.className = "step-dot w-9 h-9 rounded-xl bg-stone-100 text-stone-400 flex items-center justify-center font-bold shrink-0";
                dot.innerHTML = i;
            }
        });

        window.scrollTo({ top: 100, behavior: 'smooth' });
    }
</script>
@endsection

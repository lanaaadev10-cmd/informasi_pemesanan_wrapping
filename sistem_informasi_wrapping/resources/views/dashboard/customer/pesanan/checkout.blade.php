@extends('layouts.dashboard_customer')

@section('title', $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan')

@php
    $step1Label = $profil->checkout_step_1_label ?? 'Pilih Layanan';
    $step2Label = $profil->checkout_step_2_label ?? 'Data Kendaraan';
    $step3Label = $profil->checkout_step_3_label ?? 'Tinjauan';
    $step4Label = $profil->checkout_step_4_label ?? 'Pembayaran';
@endphp

@section('content')
<div class="max-w-6xl mx-auto py-8 space-y-12 relative overflow-hidden text-white">

    <div class="flex items-center justify-between z-10 relative">
        <h1 class="text-xl sm:text-2xl font-bold tracking-wide font-serif" style="color:var(--accent)">{{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }}</h1>
    </div>

    <div class="flex items-center justify-center w-full max-w-3xl mx-auto px-4 z-10 relative">
        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div class="w-10 h-10 rounded-full text-black font-bold flex items-center justify-center text-sm transition-all" style="background-color:var(--accent);box-shadow:0 0 15px color-mix(in srgb,var(--accent) 40%,transparent)">
                <i class="ph-bold ph-check"></i>
            </div>
            <span class="text-[10px] font-bold transition-all text-center" style="color:var(--accent)">{{ $step1Label }}</span>
        </div>
        <div class="flex-grow h-px bg-[#f2994a] mx-2 shadow-[0_0_10px_rgba(242,153,74,0.5)]"></div>

        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div id="step-circle-2" class="w-10 h-10 rounded-full bg-[#f2994a] text-black font-bold flex items-center justify-center text-sm shadow-[0_0_15px_rgba(242,153,74,0.4)] transition-all scale-110">
                <i class="ph-bold ph-pencil-simple text-lg"></i>
            </div>
            <span id="step-label-2" class="text-[10px] font-bold text-[#f2994a] transition-all text-center">{{ $step2Label }}</span>
        </div>
        <div id="step-line-2" class="flex-grow h-px bg-white/10 mx-2 transition-all duration-500"></div>

        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div id="step-circle-3" class="w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10 transition-all">3</div>
            <span id="step-label-3" class="text-[10px] font-bold text-gray-500 transition-all text-center">{{ $step3Label }}</span>
        </div>
        <div class="flex-grow h-px bg-white/10 mx-2"></div>

        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div class="w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10">4</div>
            <span class="text-[10px] font-bold text-gray-500 transition-all text-center">{{ $step4Label }}</span>
        </div>
    </div>

    <!-- Page Title -->
    <div class="z-10 relative space-y-1">
        <h2 id="page-title" class="text-3xl font-medium tracking-tight text-white">{{ $profil->section_data_kendaraan ?? 'Data Kendaraan & Jadwal' }}</h2>
        <p id="page-subtitle" class="text-sm text-gray-400">{{ $profil->checkout_lengkapi_prompt ?? 'Harap lengkapi informasi kendaraan dan jadwal penyerahan sebelum tinjauan.' }}</p>
    </div>

    <!-- Main Content Area -->
    <form id="checkout-form" action="{{ route('pesanan.checkout.store') }}" method="POST">
        @csrf
        
        <!-- HIDDEN KETERANGAN (Menerima input dari Data Tambahan seperti Nopol) -->
        <input type="hidden" name="keterangan_tambahan" id="hidden_keterangan">

        <!-- PANEL 2: DATA KENDARAAN (Form Pengisian) -->
        <div id="step-panel-2" class="transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] max-w-4xl">
            <div class="bg-[#121212] border border-white/5 rounded-[24px] p-8 space-y-8 shadow-lg relative overflow-hidden">
                <!-- Ambient Glow inside panel -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#f2994a]/5 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <!-- Kolom 1 -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_merk_kendaraan ?? 'Merk & Model Kendaraan *' }}</label>
                            <input type="text" id="input_merk" name="model_kendaraan" placeholder="Contoh: Porsche 911 GT3 (992)" required
                                   class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_warna_kendaraan ?? 'Warna Dasar Kendaraan *' }}</label>
                            <input type="text" id="input_warna" name="warna_kendaraan" placeholder="Contoh: Chalk White" required
                                   class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_nomor_polisi ?? 'Nomor Polisi *' }}</label>
                                <input type="text" id="input_nopol" name="nomor_polisi" placeholder="B 911 RSR" required
                                       class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner uppercase">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_tahun_produksi ?? 'Tahun Produksi *' }}</label>
                                <input type="number" id="input_tahun" name="tahun_produksi" placeholder="2023" required
                                       class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_nama_pemesan ?? 'Nama Pemesan *' }}</label>
                            <input type="text" id="input_nama" name="nama_pemesan" value="{{ auth()->user()->name }}" required
                                   class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_lokasi_pengerjaan ?? 'Lokasi Pengerjaan (Workshop) *' }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group h-full">
                                    <input type="radio" name="lokasi_pengerjaan" value="toko" class="peer hidden" checked>
                                    <div class="h-full border border-white/10 peer-checked:border-[#f2994a] bg-[#1c1c1c] peer-checked:bg-[#f2994a]/5 rounded-xl px-4 py-3 flex items-center justify-between transition-all hover:bg-white/5">
                                        <span class="text-xs font-medium text-gray-400 peer-checked:text-white">{{ $profil->form_studio_hq ?? 'Studio HQ' }}</span>
                                        <i class="ph-bold ph-check-circle text-[#f2994a] opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_alamat_pengerjaan ?? 'Alamat Pengerjaan / Penjemputan *' }}</label>
                            <textarea id="input_alamat" name="alamat_pengiriman" rows="2" placeholder="{{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }} - Jakarta Selatan (Atau alamat lengkap Anda)..." required
                                      class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_tanggal_mulai ?? 'Tanggal Mulai Sesi *' }}</label>
                            <input type="datetime-local" id="input_jadwal" name="jadwal_pengerjaan" required
                                   class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner [color-scheme:dark]">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_whatsapp ?? 'WhatsApp *' }}</label>
                            <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" required
                                   class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                        </div>
                        <div class="space-y-2 hidden">
                            <!-- Field asli catatan tambahan disembunyikan karena digunakan JS untuk menyimpan custom Nopol/Tahun -->
                            <input type="text" id="input_ket" value="">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-4 border-t border-white/5 relative z-10">
                    <button type="button" onclick="goToStep(3)" class="px-8 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] rounded-xl text-black font-medium text-sm transition-all hover:shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-[1.02] active:scale-95 flex items-center gap-2">
                        {{ $profil->cta_lanjutkan_review ?? 'Lanjutkan ke Tinjauan' }} <i class="ph-bold ph-arrow-right text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- PANEL 3: REVIEW (Figma Design Layout) -->
        <div id="step-panel-3" class="hidden transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Kiri: Rincian Kartu (Col 8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Card 1: Layanan Terpilih -->
                    <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex justify-between items-center border-b border-white/5 pb-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-stack text-[#f2994a] text-2xl"></i>
                                <h3 class="text-white font-medium text-lg">{{ $profil->section_layanan_terpilih ?? 'Layanan Terpilih' }}</h3>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($keranjang->details as $item)
                            <div class="flex gap-5 items-center">
                                <div class="w-24 h-24 bg-white/5 rounded-xl border border-white/5 overflow-hidden shrink-0 shadow-inner">
                                    @if($item->layanan->foto_contoh)
                                        <img src="{{ asset('storage/' . $item->layanan->foto_contoh) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="ph ph-car text-3xl text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-1.5 flex-grow">
                                    <h4 class="text-white font-medium text-base">{{ $item->layanan->nama_layanan }}</h4>
                                    <p class="text-[11px] text-gray-400 leading-relaxed">
                                        Kategori: {{ $item->layanan->kategori ?? 'Layanan Premium' }}<br>
                                        Kuantitas: {{ $item->jumlah }} Unit
                                    </p>
                                    <div class="flex gap-2 mt-1">
                                        <span class="text-[8px] bg-white/5 text-gray-400 px-2 py-1 rounded border border-white/10 uppercase tracking-widest font-bold">Garansi {{ $item->layanan->garansi ?? ($profil->layanan_garansi_text ?? '1 Tahun') }}</span>
                                        <span class="text-[8px] bg-white/5 text-gray-400 px-2 py-1 rounded border border-white/10 uppercase tracking-widest font-bold">UV Protection</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Card 2: Detail Kendaraan -->
                    <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex justify-between items-center border-b border-white/5 pb-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-car-profile text-[#f2994a] text-2xl"></i>
                                <h3 class="text-white font-medium text-lg">{{ $profil->section_detail_kendaraan ?? 'Detail Kendaraan' }}</h3>
                            </div>
                            <button type="button" onclick="goToStep(2)" class="flex items-center gap-1.5 text-gray-400 hover:text-white text-xs font-medium transition-colors">
                                <i class="ph-bold ph-pencil-simple"></i> {{ $profil->cta_edit ?? 'Edit' }}
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-y-8 gap-x-4">
                            <div>
                                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_merk_kendaraan ?? 'Merk & Model' }}</span>
                                <span id="review-merk" class="text-gray-200 font-medium text-sm"></span>
                            </div>
                            <div>
                                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_nomor_polisi ?? 'Nomor Polisi' }}</span>
                                <span id="review-nopol" class="text-gray-200 font-medium text-sm"></span>
                            </div>
                            <div>
                                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_warna_kendaraan ?? 'Warna Dasar' }}</span>
                                <span id="review-warna" class="text-gray-200 font-medium text-sm"></span>
                            </div>
                            <div>
                                <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_tahun_produksi ?? 'Tahun Produksi' }}</span>
                                <span id="review-tahun" class="text-gray-200 font-medium text-sm"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Jadwal Sesi -->
                    <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex justify-between items-center border-b border-white/5 pb-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-calendar-blank text-[#f2994a] text-2xl"></i>
                                <h3 class="text-white font-medium text-lg">{{ $profil->section_jadwal_sesi ?? 'Jadwal Sesi' }}</h3>
                            </div>
                            <button type="button" onclick="goToStep(2)" class="flex items-center gap-1.5 text-gray-400 hover:text-white text-xs font-medium transition-colors">
                                <i class="ph-bold ph-pencil-simple"></i> {{ $profil->cta_edit ?? 'Edit' }}
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="flex items-start gap-3">
                                <i class="ph ph-calendar text-gray-400 text-xl mt-0.5"></i>
                                <div>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->form_tanggal_mulai ?? 'Tanggal Mulai' }}</span>
                                    <span id="review-jadwal" class="text-gray-200 font-medium text-xs leading-relaxed block max-w-[150px]"></span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-clock text-gray-400 text-xl mt-0.5"></i>
                                <div>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->checkout_estimasi_durasi_label ?? 'Estimasi Durasi' }}</span>
                                    <span class="text-gray-200 font-medium text-xs leading-relaxed block">{{ $keranjang->details->first()->layanan->estimasi_waktu ?? '4 - 5 Hari Kerja' }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-map-pin text-gray-400 text-xl mt-0.5"></i>
                                <div>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest block mb-1.5">{{ $profil->checkout_lokasi_pengerjaan_label ?? 'Workshop' }}</span>
                                    <span id="review-lokasi" class="text-gray-200 font-medium text-xs leading-relaxed block">{{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }} - HQ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kanan: Rincian Biaya (Col 4) -->
                <div class="lg:col-span-4 relative">
                    <div class="bg-[#121212] border border-white/5 rounded-[24px] p-6 sm:p-8 space-y-8 shadow-lg lg:sticky lg:top-24">
                        <h3 class="text-white font-medium text-lg">{{ $profil->section_rincian_biaya ?? 'Rincian Biaya' }}</h3>
                        
                        <div class="space-y-4">
                            @foreach($keranjang->details as $item)
                                <div class="flex justify-between items-center text-xs text-gray-400">
                                    <span class="truncate pr-4">{{ $item->layanan->nama_layanan }} ({{ $item->jumlah }}x)</span>
                                    <span class="font-medium text-white shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            @php
                                $biayaLayanan = $keranjang->details->sum(fn($d) => $d->layanan->biaya_layanan ?? 150000);
                                $biayaLabel = $keranjang->details->first()->layanan->biaya_layanan_label ?? 'Biaya Layanan & Pemasangan';
                            @endphp
                            <div class="flex justify-between items-center text-xs text-gray-400 pt-2">
                                <span>{{ $biayaLabel }}</span>
                                <span class="font-medium text-white shrink-0">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @php
                            $subtotal = $keranjang->details->sum('subtotal');
                            $grandTotal = $subtotal + $biayaLayanan;
                        @endphp

                        <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-5 flex justify-between items-center shadow-inner mt-4">
                            <span class="text-gray-400 text-xs font-medium">{{ $profil->label_total_tagihan ?? 'Total Pembayaran' }}</span>
                            <span class="text-[#f2994a] text-xl font-medium tracking-tight">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-2">
                            <button type="submit" onclick="prepareSubmit()" class="w-full py-4 bg-[#f2994a] hover:bg-[#e28a44] rounded-xl text-black font-medium text-sm transition-all hover:shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2">
                                {{ $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan' }} <i class="ph-bold ph-arrow-right"></i>
                            </button>
                            <p class="text-center text-[9px] text-gray-500 leading-relaxed mt-5 px-2">
                                {{ $profil->checkout_terms_text ?? 'Dengan mengklik Konfirmasi, Anda menyetujui Syarat & Ketentuan serta Kebijakan Pembatalan kami.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>



<script>
    // ====== DATA PRESERVATION (localStorage) ======
    const STORAGE_KEY = 'checkout_form_data';
    
    function saveFormData() {
        const data = {
            model_kendaraan: document.getElementById('input_merk')?.value || '',
            warna_kendaraan: document.getElementById('input_warna')?.value || '',
            nomor_polisi: document.getElementById('input_nopol')?.value || '',
            tahun_produksi: document.getElementById('input_tahun')?.value || '',
            nama_pemesan: document.getElementById('input_nama')?.value || '',
            lokasi_pengerjaan: document.querySelector('input[name="lokasi_pengerjaan"]:checked')?.value || 'toko',
            alamat_pengiriman: document.getElementById('input_alamat')?.value || '',
            jadwal_pengerjaan: document.getElementById('input_jadwal')?.value || '',
            no_hp: document.querySelector('input[name="no_hp"]')?.value || '',
        };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }
    
    function restoreFormData() {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return;
        try {
            const data = JSON.parse(raw);
            const el = id => document.getElementById(id);
            if (el('input_merk')) el('input_merk').value = data.model_kendaraan || '';
            if (el('input_warna')) el('input_warna').value = data.warna_kendaraan || '';
            if (el('input_nopol')) el('input_nopol').value = data.nomor_polisi || '';
            if (el('input_tahun')) el('input_tahun').value = data.tahun_produksi || '';
            if (el('input_nama')) el('input_nama').value = data.nama_pemesan || '';
            const lokasiRadio = document.querySelector(`input[name="lokasi_pengerjaan"][value="${data.lokasi_pengerjaan}"]`);
            if (lokasiRadio) lokasiRadio.checked = true;
            if (el('input_alamat')) el('input_alamat').value = data.alamat_pengiriman || '';
            if (el('input_jadwal')) el('input_jadwal').value = data.jadwal_pengerjaan || '';
            const hp = document.querySelector('input[name="no_hp"]');
            if (hp) hp.value = data.no_hp || '';
        } catch(e) {}
    }
    
    function clearFormData() {
        localStorage.removeItem(STORAGE_KEY);
    }
    
    // Auto-save on any input change
    document.addEventListener('DOMContentLoaded', function() {
        restoreFormData();
        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('input', saveFormData);
            form.addEventListener('change', saveFormData);
        }
    });

    function updateLokasiLabel() {
        const almt = document.getElementById('input_alamat');
        almt.placeholder = "Contoh: Menggunakan Studio {{ addslashes($profil->nama_perusahaan ?? 'Dantie Wrapping') }} (Otomatis terisi jika kosong)";
    }

    function formatTanggal(isoString) {
        if(!isoString) return "-";
        const date = new Date(isoString);
        return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' }) + ' WIB';
    }

    function goToStep(step) {
        // Validation check before going to step 3
        if (step === 3) {
            const currentPanel = document.getElementById('step-panel-2');
            const requiredInputs = currentPanel.querySelectorAll('input[required], textarea[required]');
            let allValid = true;
            requiredInputs.forEach(input => {
                if (!input.value) {
                    allValid = false;
                    input.classList.add('border-red-500');
                    input.classList.remove('border-white/10');
                } else {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-white/10');
                }
            });
            if (!allValid) {
                alert('{{ $profil->alert_lengkapi_data ?? 'Harap lengkapi semua data wajib bertanda bintang merah (*).' }}');
                return; // Stop if validation fails
            }

            // Populate Review Card Data
            document.getElementById('review-merk').innerText = document.getElementById('input_merk').value;
            document.getElementById('review-warna').innerText = document.getElementById('input_warna').value;
            document.getElementById('review-nopol').innerText = document.getElementById('input_nopol').value.toUpperCase();
            document.getElementById('review-tahun').innerText = document.getElementById('input_tahun').value;
            
            document.getElementById('review-jadwal').innerText = formatTanggal(document.getElementById('input_jadwal').value);
            
            document.getElementById('review-lokasi').innerHTML = '{{ addslashes($profil->nama_perusahaan ?? 'Dantie Wrapping') }} HQ<br><span class="text-[9px] text-gray-500 leading-tight">{{ addslashes($profil->checkout_lokasi_pengerjaan_label ?? 'Studio Jakarta Selatan') }}</span>';
        }

        document.querySelectorAll('[id^="step-panel-"]').forEach(p => p.classList.add('hidden'));
        const target = document.getElementById('step-panel-' + step);
        if(target) target.classList.remove('hidden');

        // Update Nav Stepper visual styling
        const title = document.getElementById('page-title');
        const subtitle = document.getElementById('page-subtitle');
        
        if (step === 3) {
            title.innerText = "{{ $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan' }}";
            subtitle.innerText = "{{ $profil->checkout_review_prompt ?? 'Harap tinjau kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.' }}";
            
            // Step 2 becomes completed
            document.getElementById('step-circle-2').innerHTML = '<i class="ph-bold ph-check"></i>';
            document.getElementById('step-circle-2').classList.remove('scale-110');
            document.getElementById('step-line-2').classList.add('bg-[#f2994a]', 'shadow-[0_0_10px_rgba(242,153,74,0.5)]');
            document.getElementById('step-line-2').classList.remove('bg-white/10');
            
            // Step 3 becomes active
            document.getElementById('step-circle-3').className = "w-10 h-10 rounded-full bg-[#f2994a] text-black font-bold flex items-center justify-center text-sm shadow-[0_0_15px_rgba(242,153,74,0.4)] transition-all scale-110";
            document.getElementById('step-label-3').className = "text-[10px] font-bold text-[#f2994a] transition-all text-center";
        } else if (step === 2) {
            title.innerText = "{{ $profil->section_data_kendaraan ?? 'Data Kendaraan & Jadwal' }}";
            subtitle.innerText = "{{ $profil->checkout_lengkapi_prompt ?? 'Harap lengkapi informasi kendaraan dan jadwal penyerahan sebelum tinjauan.' }}";
            
            // Step 2 becomes active again
            document.getElementById('step-circle-2').innerHTML = '<i class="ph-bold ph-pencil-simple text-lg"></i>';
            document.getElementById('step-circle-2').classList.add('scale-110');
            document.getElementById('step-line-2').classList.remove('bg-[#f2994a]', 'shadow-[0_0_10px_rgba(242,153,74,0.5)]');
            document.getElementById('step-line-2').classList.add('bg-white/10');
            
            // Step 3 becomes inactive
            document.getElementById('step-circle-3').className = "w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10 transition-all";
            document.getElementById('step-label-3').className = "text-[10px] font-bold text-gray-500 transition-all text-center";
        }

        // Smooth scroll to top of form area
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function prepareSubmit() {
        // Compile custom form fields (Nopol, Tahun) into the hidden keterangan_tambahan expected by DB
        const nopol = document.getElementById('input_nopol').value;
        const tahun = document.getElementById('input_tahun').value;
        
        let customData = `Nomor Polisi: ${nopol.toUpperCase()} | Tahun Produksi: ${tahun}`;
        
        document.getElementById('hidden_keterangan').value = customData;
        clearFormData();
    }
</script>
@endsection

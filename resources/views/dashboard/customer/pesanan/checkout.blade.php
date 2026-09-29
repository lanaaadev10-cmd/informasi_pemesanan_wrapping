@extends('layouts.dashboard-customer')

@section('title', $profil->cta_konfirmasi_pemesanan ?? 'Konfirmasi Pemesanan')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';
    $step1Label = $profil->checkout_step_1_label ?? 'Pilih Layanan';
    $step2Label = $profil->checkout_step_2_label ?? 'Data Kendaraan';
    $step3Label = $profil->checkout_step_3_label ?? 'Review';
    $step4Label = $profil->checkout_step_4_label ?? 'Pembayaran';
@endphp

<style>
    :root {
        --accent-color: {{ $accentColor }};
    }
    .accent-bg { background-color: var(--accent-color); }
    .accent-color { color: var(--accent-color); }
    .accent-shadow { box-shadow: 0 0 15px color-mix(in srgb, var(--accent-color) 40%, transparent); }
    .accent-border { border-color: var(--accent-color); }
</style>

@section('content')
<div class="max-w-6xl mx-auto py-8 space-y-12 relative overflow-hidden text-white">

    <!-- Top Header -->
    <div class="flex items-center justify-between z-10 relative">
        <h1 class="text-xl sm:text-2xl font-bold tracking-wide font-serif accent-color">{{ $profil->nama_perusahaan ?? 'Wapping Premium' }}</h1>
    </div>

    <!-- Stepper (4 Steps) -->
    <div class="flex items-center justify-center w-full max-w-3xl mx-auto px-4 z-10 relative">
        <!-- Step 1: Pilih Layanan (Completed) -->
        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div class="w-10 h-10 rounded-full accent-bg text-black font-bold flex items-center justify-center text-sm accent-shadow transition-all">
                <i class="ph-bold ph-check"></i>
            </div>
            <span class="text-[10px] font-bold accent-color transition-all text-center">{{ $step1Label }}</span>
        </div>
        <div class="flex-grow h-px bg-[#f2994a] mx-2 shadow-[0_0_10px_rgba(242,153,74,0.5)]"></div>

        <!-- Step 2: Data Kendaraan (Dynamic) -->
        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div id="step-circle-2" class="w-10 h-10 rounded-full bg-[#f2994a] text-black font-bold flex items-center justify-center text-sm shadow-[0_0_15px_rgba(242,153,74,0.4)] transition-all scale-110">
                <i class="ph-bold ph-pencil-simple text-lg"></i>
            </div>
            <span id="step-label-2" class="text-[10px] font-bold text-[#f2994a] transition-all text-center">Data Kendaraan</span>
        </div>
        <div id="step-line-2" class="flex-grow h-px bg-white/10 mx-2 transition-all duration-500"></div>

        <!-- Step 3: Review -->
        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div id="step-circle-3" class="w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10 transition-all">
                3
            </div>
            <span id="step-label-3" class="text-[10px] font-bold text-gray-500 transition-all text-center">Review</span>
        </div>
        <div class="flex-grow h-px bg-white/10 mx-2"></div>

        <!-- Step 4: Pembayaran -->
        <div class="flex flex-col items-center gap-3 relative z-10 w-28">
            <div class="w-10 h-10 rounded-full bg-[#202020] text-gray-400 font-bold flex items-center justify-center text-sm border border-white/10">
                4
            </div>
            <span class="text-[10px] font-bold text-gray-500 transition-all text-center">Pembayaran</span>
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
        
        <input type="hidden" name="keterangan_tambahan" id="hidden_keterangan">

        <!-- PANEL 2: DATA KENDARAAN (Form Pengisian) -->
        <div id="step-panel-2" class="animate-fade-in max-w-4xl">
            <div class="bg-[#121212] border border-white/5 rounded-[24px] p-8 space-y-8 shadow-lg relative overflow-hidden">
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
                                       class="w-full bg-[#1c1c1c] border border-white/10 rounded-xl px-4 py-3.5 text-white text-sm focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
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
                        <div class="space-y-2 hidden">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_lokasi_pengerjaan ?? 'Lokasi Pengerjaan (Workshop) *' }}</label>
                            <input type="hidden" name="lokasi_pengerjaan" value="toko">
                            <div class="flex items-center gap-3 p-4 border border-[#f2994a]/30 bg-[#f2994a]/5 rounded-xl">
                                <i class="ph-bold ph-buildings text-[#f2994a] text-lg"></i>
                                <span class="text-sm font-medium text-white">{{ $profil->form_studio_hq ?? 'Di Bengkel' }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-400 px-1">{{ $profil->form_alamat_pengerjaan ?? 'Alamat Anda *' }}</label>
                            <textarea id="input_alamat" name="alamat_pengiriman" rows="2" placeholder="Masukkan alamat lengkap Anda..." required
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
                    </div>
                </div>

                <div class="flex justify-end pt-6 mt-4 border-t border-white/5 relative z-10">
                    <button type="button" onclick="goToStep(3)" class="px-8 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] rounded-xl text-black font-medium text-sm transition-all hover:shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-[1.02] active:scale-95 flex items-center gap-2">
                        {{ $profil->cta_lanjutkan_review ?? 'Lanjutkan ke Review' }} <i class="ph-bold ph-arrow-right text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- PANEL 3: REVIEW (Step 3) -->
        @include('dashboard.customer.pesanan.partials._checkout-step-review')

    </form>
</div>

<!-- Scripts & Animations -->
@include('dashboard.customer.pesanan.partials._checkout-scripts')

@endsection

@extends('layouts.dashboard_customer')

@php
    $statusVal = $pesanan->status instanceof \App\Enums\OrderStatus ? $pesanan->status->value : $pesanan->status;
@endphp

@section('title', 'Payment Verification - ' . $pesanan->kode_pesanan)

@section('content')
<div class="max-w-6xl mx-auto py-8 text-white space-y-8 relative overflow-hidden">
    <!-- Ambient glowing backdrop -->
    <div class="absolute top-0 right-10 w-[500px] h-[400px] bg-[#f2994a]/5 rounded-full blur-[130px] pointer-events-none z-0"></div>

    <!-- Header & Step Indicator -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 z-10 relative border-b border-white/5 pb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#f2994a]">Payment Verification</h1>
        </div>
        
        <!-- Step 4 Indicator -->
        <div class="flex items-center gap-4">
            <span class="text-[10px] text-gray-500 font-medium">Step 4 of 4</span>
            <div class="flex gap-1.5">
                <div class="w-8 h-1 bg-[#f2994a] rounded-full"></div>
                <div class="w-8 h-1 bg-[#f2994a] rounded-full"></div>
                <div class="w-8 h-1 bg-[#f2994a] rounded-full"></div>
                <div class="w-12 h-1 bg-[#f2994a] rounded-full shadow-[0_0_8px_rgba(242,153,74,0.6)]"></div>
            </div>
            <span class="text-[10px] text-[#f2994a] font-bold">Manual Payment</span>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 z-10 relative">
        
        <!-- LEFT COLUMN: Transfer Details -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-[#121212] border border-white/5 rounded-3xl p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <i class="ph ph-bank text-[#f2994a] text-2xl"></i>
                    <h2 class="text-xl font-bold text-white">Transfer Details</h2>
                </div>
                
                <p class="text-xs text-gray-400 mb-8 leading-relaxed">
                    Please complete your payment by transferring the exact amount to our official bank account below.
                </p>

                <!-- Bank Info Box -->
                <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6 relative overflow-hidden mb-6">
                    <!-- Subtle pattern or glow inside -->
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    
                    <div class="grid grid-cols-3 gap-y-6 relative z-10">
                        <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest">Bank Name</div>
                        <div class="col-span-2 text-right text-xs font-bold text-white">BCA (Bank Central Asia)</div>
                        
                        <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest flex items-center">Account Number</div>
                        <div class="col-span-2 text-right flex items-center justify-end gap-3">
                            <span class="text-xl md:text-2xl font-mono font-black text-white tracking-widest">123-456-7890</span>
                            <button onclick="copyToClipboard('1234567890')" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center transition-colors text-gray-400 hover:text-white" title="Copy Account Number">
                                <i class="ph ph-copy text-sm"></i>
                            </button>
                        </div>

                        <div class="col-span-1 text-[9px] font-bold text-gray-500 uppercase tracking-widest">Account Holder</div>
                        <div class="col-span-2 text-right text-xs font-bold text-white">PT Wapping Indonesia</div>
                    </div>
                </div>

                <!-- Total Amount Box -->
                <div class="bg-[#241710] border border-[#f2994a]/30 rounded-2xl p-6 flex justify-between items-center mb-6">
                    <div>
                        <span class="text-[9px] font-bold text-[#f2994a]/70 uppercase tracking-widest block mb-1">Total Amount to Pay</span>
                        <span class="text-2xl font-bold text-[#f2994a]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-12 h-12 bg-[#f2994a]/10 rounded-xl flex items-center justify-center text-[#f2994a]">
                        <i class="ph ph-wallet text-2xl"></i>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="flex gap-3 text-[10px] text-gray-400 bg-white/5 rounded-xl p-4 border border-white/5">
                    <i class="ph-fill ph-info text-[#f2994a] text-lg shrink-0"></i>
                    <p class="leading-relaxed">
                        <strong class="text-gray-300">Important:</strong> Ensure you transfer the exact amount. Payments are typically verified within 30 minutes of proof upload during working hours (09:00 - 18:00).
                    </p>
                </div>
            </div>

            <!-- Order Summary Box -->
            <div class="bg-[#121212] border border-white/5 rounded-3xl p-6 shadow-sm">
                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest block mb-4">Order Summary</span>
                
                @php
                    $firstItem = $pesanan->details->first();
                    $thumbnail = $firstItem?->layanan->foto_contoh;
                    $imageUrl = $thumbnail ? asset('storage/' . $thumbnail) : 'https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=200';
                @endphp

                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-white/5 border border-white/10 shrink-0">
                            <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white leading-tight mb-1">
                                {{ $pesanan->form->model_kendaraan ?? 'Kendaraan Wapping' }} - {{ $firstItem?->layanan->nama_layanan ?? 'Custom Wrap' }}
                            </h4>
                            <span class="text-[10px] text-gray-500 font-mono">Project ID: #{{ $pesanan->kode_pesanan }}</span>
                        </div>
                    </div>
                    
                    <div class="shrink-0 hidden sm:block">
                        <span class="bg-[#f2994a]/10 border border-[#f2994a]/30 text-[#f2994a] text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full">
                            Waiting Payment
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Proof of Payment Form -->
        <div class="lg:col-span-5 relative">
            <div class="bg-[#121212] border border-white/5 rounded-3xl p-8 shadow-lg lg:sticky lg:top-8">

                @if($statusVal === 'menunggu_pembayaran')
                    <h2 class="text-xl font-bold text-white mb-2">Proof of Payment</h2>
                    <p class="text-xs text-gray-400 mb-6">Upload a screenshot or photo of your bank transfer receipt.</p>

                    <form action="{{ route('pesanan.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Drag & Drop Upload Area -->
                        <div class="relative group">
                            <input type="file" name="bukti_transfer" id="bukti-input" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFile()">
                            <div id="drop-zone" class="w-full aspect-[4/3] bg-[#1a1a1a] border-2 border-dashed border-white/10 rounded-2xl flex flex-col items-center justify-center p-6 text-center group-hover:border-[#f2994a]/50 group-hover:bg-[#f2994a]/5 transition-all">
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mb-4 group-hover:bg-[#f2994a]/20 group-hover:text-[#f2994a] transition-colors">
                                    <i class="ph ph-cloud-arrow-up text-2xl text-gray-400 group-hover:text-[#f2994a]"></i>
                                </div>
                                <p class="text-[11px] font-medium text-gray-300 mb-1">Drag and drop file here<br>or browse files from device</p>
                                <p class="text-[9px] text-gray-500 uppercase tracking-widest">PNG, JPG, GIF (Max 5MB)</p>
                            </div>

                            <!-- Preview Image Container (Hidden by default) -->
                            <div id="preview-container" class="hidden absolute inset-0 w-full h-full bg-[#1a1a1a] rounded-2xl border-2 border-[#f2994a] overflow-hidden z-20">
                                <img id="preview-image" class="w-full h-full object-cover">
                                <button type="button" onclick="removeFile()" class="absolute top-2 right-2 w-8 h-8 bg-black/70 rounded-lg text-white flex items-center justify-center hover:bg-red-50 transition-colors">
                                    <i class="ph-bold ph-x"></i>
                                </button>
                                <div class="absolute bottom-0 inset-x-0 bg-black/70 p-2 text-center text-[10px] text-white">Bukti Terlampir</div>
                            </div>
                        </div>

                        <!-- Optional Fields -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest px-1">Metode Pembayaran (Manual Transfer)</label>
                            <select name="metode_pembayaran" required class="w-full bg-[#1a1a1a] border border-white/5 rounded-xl px-4 py-3.5 text-white text-xs focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                                <option value="transfer_bank" selected>Transfer Bank (Manual)</option>
                                <option value="transfer_e_wallet">E-Wallet Transfer (Manual)</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="pt-4 space-y-4">
                            <button type="submit" class="w-full py-4 bg-[#f2994a] hover:bg-[#e28a44] text-black font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] active:scale-95 flex justify-center items-center gap-2">
                                <i class="ph-bold ph-check-circle text-base"></i> Confirm Payment
                            </button>
                            <a href="{{ route('pesanan.index') }}" class="block text-center text-[10px] text-gray-400 hover:text-white transition-colors">
                                Cancel & Return to Dashboard
                            </a>
                        </div>
                    </form>

                @elseif($statusVal === 'menunggu_konfirmasi_admin')
                    <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-400 shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                            <i class="ph-fill ph-hourglass-high text-4xl animate-spin-slow"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Menunggu Konfirmasi Admin</h2>
                        <p class="text-xs text-gray-400 leading-relaxed px-4">Admin sedang memproses pesanan Anda. Anda akan menerima notifikasi segera setelah disetujui.</p>
                        <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">Kembali ke Dashboard</a>
                    </div>

                @elseif($statusVal === 'menunggu_verifikasi_pembayaran')
                    <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 bg-yellow-500/10 rounded-full flex items-center justify-center text-yellow-400 shadow-[0_0_20px_rgba(234,179,8,0.2)]">
                            <i class="ph-fill ph-clock text-4xl animate-pulse"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Bukti Pembayaran Diterima</h2>
                        <p class="text-xs text-gray-400 leading-relaxed px-4">Admin sedang memverifikasi bukti pembayaran Anda. Biasanya selesai dalam 30 menit.</p>
                        <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">Kembali ke Dashboard</a>
                    </div>

                @elseif($statusVal === 'sedang_diproses' || $statusVal === 'dikonfirmasi')
                    <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                            <i class="ph-fill ph-check-circle text-4xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Pembayaran Berhasil Diverifikasi</h2>
                        <p class="text-xs text-gray-400 leading-relaxed px-4">Pembayaran Anda telah dikonfirmasi. Kendaraan Anda masuk antrean pengerjaan.</p>
                        <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 bg-white border border-gray-200 text-black hover:bg-gray-100 rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                            <i class="ph-bold ph-file-pdf text-lg"></i> Unduh Invoice PDF
                        </a>
                        <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">Kembali ke Dashboard</a>
                    </div>

                @elseif($statusVal === 'selesai')
                    <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                            <i class="ph-fill ph-check-circle text-4xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Pesanan Selesai</h2>
                        <p class="text-xs text-gray-400 leading-relaxed px-4">Pengerjaan pesanan Anda telah selesai. Silakan ambil kendaraan Anda.</p>
                        <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 bg-white border border-gray-200 text-black hover:bg-gray-100 rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                            <i class="ph-bold ph-file-pdf text-lg"></i> Unduh Invoice PDF
                        </a>
                        <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">Kembali ke Dashboard</a>
                    </div>

                @elseif($statusVal === 'ditolak')
                    <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center text-red-400 shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                            <i class="ph-fill ph-x-circle text-4xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white">Pesanan Ditolak</h2>
                        <p class="text-xs text-gray-400 leading-relaxed px-4">{{ $pesanan->catatan_admin ?? 'Pesanan Anda telah ditolak oleh admin. Silakan hubungi customer service untuk info lebih lanjut.' }}</p>
                        <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">Kembali ke Dashboard</a>
                    </div>
                @endif

                <!-- Bottom Support Info -->
                <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-between text-[9px] text-gray-500 uppercase tracking-widest font-mono">
                    <div class="flex items-center gap-1.5 hover:text-white cursor-pointer transition-colors">
                        <i class="ph-bold ph-info text-sm"></i> Help Center
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-lock-key"></i> Secure Payment
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor Rekening berhasil disalin: ' + text);
        });
    }

    function previewFile() {
        const input = document.getElementById('bukti-input');
        const container = document.getElementById('preview-container');
        const image = document.getElementById('preview-image');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFile() {
        const input = document.getElementById('bukti-input');
        const container = document.getElementById('preview-container');
        input.value = "";
        container.classList.add('hidden');
    }
</script>

<!-- Premium Floating Success Toast -->
@if(session('toast_success') || session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-500 ease-out max-w-sm w-full bg-[#121c15]/95 border border-emerald-500/20 rounded-2xl p-4 shadow-[0_8px_32px_rgba(16,185,129,0.15)] backdrop-blur-md flex gap-4 pointer-events-auto">
    <!-- Pulse glowing bubble for the checkmark -->
    <div class="relative flex items-center justify-center shrink-0">
        <div class="absolute inset-0 bg-emerald-500/20 rounded-xl blur animate-pulse"></div>
        <div class="relative w-10 h-10 bg-emerald-500/10 rounded-xl border border-emerald-500/30 flex items-center justify-center text-emerald-400">
            <i class="ph-bold ph-check text-xl"></i>
        </div>
    </div>
    <!-- Toast Content -->
    <div class="flex-1 space-y-1">
        <h4 class="text-xs font-bold text-white tracking-wide">Success Action</h4>
        <p class="text-[11px] text-gray-400 leading-relaxed">{{ session('toast_success') ?? session('success') }}</p>
    </div>
    <!-- Close Button -->
    <button onclick="dismissToast()" class="shrink-0 w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
        <i class="ph ph-x text-xs"></i>
    </button>
    
    <!-- Countdown progress bar -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-950 rounded-b-2xl overflow-hidden">
        <div id="toast-progress" class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 w-full transition-all duration-5000 ease-linear"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('success-toast');
        const progress = document.getElementById('toast-progress');
        if (toast) {
            // Trigger entry animation
            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 100);

            // Progress bar animation
            setTimeout(() => {
                if (progress) progress.style.width = '0%';
            }, 200);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                dismissToast();
            }, 5000);
        }
    });

    function dismissToast() {
        const toast = document.getElementById('success-toast');
        if (toast) {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }
</script>
@endif

<style>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
    .duration-5000 {
        transition-duration: 5000ms;
    }
</style>
@endsection

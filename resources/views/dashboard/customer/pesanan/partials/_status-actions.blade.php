{{-- Kolom Status dan Aksi Upload / Detail Pesanan --}}
<div class="lg:col-span-5 relative">
    <div class="bg-[#121212] border border-white/5 rounded-3xl p-8 shadow-lg lg:sticky lg:top-8">

        @if($statusVal === 'menunggu_pembayaran')
            <h2 class="text-xl font-bold text-white mb-2">{{ $profil->section_upload_bukti ?? 'Upload Bukti Pembayaran' }}</h2>
            <p class="text-xs text-gray-400 mb-6">Upload screenshot atau foto bukti transfer bank Anda.</p>

            <form action="{{ route('pesanan.upload-bukti', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Drag & Drop Upload Area -->
                <div class="relative group">
                    <input type="file" name="bukti_transfer" id="bukti-input" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFile()">
                    <div id="drop-zone" class="w-full aspect-[4/3] bg-[#1a1a1a] border-2 border-dashed border-white/10 rounded-2xl flex flex-col items-center justify-center p-6 text-center group-hover:border-[#f2994a]/50 group-hover:bg-[#f2994a]/5 transition-all">
                        <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mb-4 group-hover:bg-[#f2994a]/20 group-hover:text-[#f2994a] transition-colors">
                            <i class="ph ph-cloud-arrow-up text-2xl text-gray-400 group-hover:text-[#f2994a]"></i>
                        </div>
                        <p class="text-[11px] font-medium text-gray-300 mb-1">Seret file ke sini<br>atau klik untuk memilih file</p>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest">PNG, JPG, GIF (Maks 5MB)</p>
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
                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest px-1">{{ $profil->label_metode_pembayaran ?? 'Metode Pembayaran' }} (Manual Transfer)</label>
                    <select name="metode_pembayaran" required class="w-full bg-[#1a1a1a] border border-white/5 rounded-xl px-4 py-3.5 text-white text-xs focus:outline-none focus:border-[#f2994a]/50 transition-all shadow-inner">
                        <option value="transfer_bank" selected>Transfer Bank (Manual)</option>
                        <option value="transfer_e_wallet">E-Wallet Transfer (Manual)</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="pt-4 space-y-4">
                    <button type="submit" class="w-full py-4 bg-[#f2994a] hover:bg-[#e28a44] text-black font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] active:scale-95 flex justify-center items-center gap-2">
                        <i class="ph-bold ph-check-circle text-base"></i> {{ $profil->cta_konfirmasi_pembayaran ?? 'Konfirmasi Pembayaran' }}
                    </button>
                    <a href="{{ route('pesanan.index') }}" class="block text-center text-[10px] text-gray-400 hover:text-white transition-colors">
                        {{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}
                    </a>
                </div>
            </form>

        @elseif($statusVal === 'menunggu_konfirmasi_admin')
            <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                <div class="w-20 h-20 bg-blue-500/10 rounded-full flex items-center justify-center text-blue-400 shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                    <i class="ph-fill ph-hourglass-high text-4xl animate-spin-slow"></i>
                </div>
                <h2 class="text-lg font-bold text-white">{{ $profil->status_menunggu_konfirmasi ?? 'Menunggu Konfirmasi Admin' }}</h2>
                <p class="text-xs text-gray-400 leading-relaxed px-4">Admin sedang memproses pesanan Anda. Anda akan menerima notifikasi segera setelah disetujui.</p>
                <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">{{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}</a>
            </div>

        @elseif($statusVal === 'menunggu_verifikasi_pembayaran')
            <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                <div class="w-20 h-20 bg-yellow-500/10 rounded-full flex items-center justify-center text-yellow-400 shadow-[0_0_20px_rgba(234,179,8,0.2)]">
                    <i class="ph-fill ph-clock text-4xl animate-pulse"></i>
                </div>
                <h2 class="text-lg font-bold text-white">{{ $profil->status_bukti_diterima ?? 'Bukti Pembayaran Diterima' }}</h2>
                <p class="text-xs text-gray-400 leading-relaxed px-4">Admin sedang memverifikasi bukti pembayaran Anda. Biasanya selesai dalam 30 menit.</p>
                <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">{{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}</a>
            </div>

        @elseif($statusVal === 'sedang_diproses' || $statusVal === 'dikonfirmasi')
            <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                    <i class="ph-fill ph-check-circle text-4xl"></i>
                </div>
                <h2 class="text-lg font-bold text-white">{{ $profil->status_pembayaran_terverifikasi ?? 'Pembayaran Berhasil Diverifikasi' }}</h2>
                <p class="text-xs text-gray-400 leading-relaxed px-4">Pembayaran Anda telah dikonfirmasi. Kendaraan Anda masuk antrean pengerjaan.</p>
                <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 bg-white border border-gray-200 text-black hover:bg-gray-100 rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                    <i class="ph-bold ph-file-pdf text-lg"></i> {{ $profil->cta_unduh_invoice ?? 'Unduh Invoice PDF' }}
                </a>
                <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">{{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}</a>
            </div>

        @elseif($statusVal === 'selesai')
            <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                    <i class="ph-fill ph-check-circle text-4xl"></i>
                </div>
                <h2 class="text-lg font-bold text-white">{{ $profil->status_pesanan_selesai ?? 'Pesanan Selesai' }}</h2>
                <p class="text-xs text-gray-400 leading-relaxed px-4">Pengerjaan pesanan Anda telah selesai. Silakan ambil kendaraan Anda.</p>
                <a href="{{ route('pesanan.rating.form', $pesanan->id_pesanan) }}" class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] active:scale-95">
                    <i class="ph-bold ph-star text-lg"></i> {{ $profil->cta_rating ?? 'Beri Rating' }}
                </a>
                <a href="{{ route('pesanan.invoice', $pesanan->id_pesanan) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3.5 bg-white border border-gray-200 text-black hover:bg-gray-100 rounded-xl font-bold text-xs tracking-wider uppercase transition-all shadow-md active:scale-95">
                    <i class="ph-bold ph-file-pdf text-lg"></i> {{ $profil->cta_unduh_invoice ?? 'Unduh Invoice PDF' }}
                </a>
                <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">{{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}</a>
            </div>

        @elseif($statusVal === 'ditolak')
            <div class="flex flex-col items-center justify-center text-center py-12 space-y-4">
                <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center text-red-400 shadow-[0_0_20px_rgba(239,68,68,0.2)]">
                    <i class="ph-fill ph-x-circle text-4xl"></i>
                </div>
                <h2 class="text-lg font-bold text-white">{{ $profil->status_pesanan_ditolak ?? 'Pesanan Ditolak' }}</h2>
                <p class="text-xs text-gray-400 leading-relaxed px-4">{{ $pesanan->catatan_admin ?? 'Pesanan Anda telah ditolak oleh admin. Silakan hubungi customer service untuk info lebih lanjut.' }}</p>
                <a href="{{ route('pesanan.index') }}" class="mt-4 inline-block px-6 py-2 border border-white/10 rounded-lg text-[10px] text-gray-400 hover:text-white transition-colors">{{ $profil->cta_kembali ?? 'Kembali ke Dashboard' }}</a>
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

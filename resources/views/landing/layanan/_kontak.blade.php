{{-- ═══════════════════════════════════════════════════════════════════
     KONTAK & KONSULTASI WORKSHOP (Mengadaptasi Seksi "Contact Me" pada Gambar Referensi)
     Grid 2 Kolom Seimbang:
     - Kartu Kiri: Dark Container dengan Media Showcase & Pill Channels (WhatsApp, IG, TikTok, Maps)
     - Kartu Kanan: Form Konsultasi Cepat Terhubung ke WhatsApp Workshop
═══════════════════════════════════════════════════════════════════ --}}
<div class="space-y-10 sm:space-y-14" id="layanan-kontak-section">

    {{-- Variasi Heading Contact Me: Left-Aligned Editorial Treatment --}}
    <div class="space-y-1.5 text-left max-w-2xl border-b border-slate-200 pb-5">
        <span class="text-xs font-black uppercase tracking-widest text-[#ea580c] block">
            03 / Hubungi Workshop
        </span>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
            Contact Me
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed">
            Konsultasikan desain striping, pilihan warna bahan, atau jadwalkan kedatangan langsung ke workshop kami.
        </p>
    </div>

    {{-- Grid 2 Kolom Sesuai Referensi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 items-stretch">

        {{-- Kartu Kiri: Dark Container dengan Info & Channels (Clean, Tanpa Badge Pill) --}}
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 flex flex-col justify-between border border-slate-800 shadow-sm relative overflow-hidden">
            <div class="space-y-4 relative z-10">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Workshop Buka Setiap Hari</span>
                </div>

                <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    {{ \App\Helpers\StaticContent::APP_NAME }}
                </h3>

                <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-normal">
                    {{ \App\Helpers\StaticContent::COMPANY_DESCRIPTION }}
                </p>

                <div class="pt-2 space-y-2 text-xs text-slate-300 font-normal">
                    <div class="flex items-start gap-2.5">
                        <i class="ph-bold ph-map-pin text-sm text-[#ea580c] shrink-0 mt-0.5"></i>
                        <span>{{ \App\Helpers\StaticContent::COMPANY_ADDRESS }}</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="ph-bold ph-clock text-sm text-[#ea580c] shrink-0"></i>
                        <span>Senin - Sabtu: 09.00 - 18.00 WIB</span>
                    </div>
                </div>
            </div>

            {{-- Pill Buttons Channels (Sesuai Referensi "Connect with me on Social Media") --}}
            <div class="pt-6 sm:pt-8 border-t border-white/10 mt-6 relative z-10 space-y-3">
                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                    Kanal Resmi Workshop:
                </p>

                <div class="grid grid-cols-2 gap-2.5">
                    <a href="{{ \App\Helpers\StaticContent::COMPANY_WHATSAPP }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-emerald-600 text-white text-xs font-bold transition-all border border-white/10 shadow-sm active:scale-95">
                        <i class="ph-bold ph-whatsapp-logo text-sm text-emerald-400"></i>
                        <span>WhatsApp</span>
                    </a>

                    <a href="{{ \App\Helpers\StaticContent::INSTAGRAM_URL }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-pink-600 text-white text-xs font-bold transition-all border border-white/10 shadow-sm active:scale-95">
                        <i class="ph-bold ph-instagram-logo text-sm text-pink-400"></i>
                        <span>Instagram</span>
                    </a>

                    <a href="{{ \App\Helpers\StaticContent::TIKTOK_URL }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-slate-700 text-white text-xs font-bold transition-all border border-white/10 shadow-sm active:scale-95">
                        <i class="ph-bold ph-tiktok-logo text-sm"></i>
                        <span>TikTok</span>
                    </a>

                    <a href="{{ \App\Helpers\StaticContent::COMPANY_MAPS_URL }}"
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-blue-600 text-white text-xs font-bold transition-all border border-white/10 shadow-sm active:scale-95">
                        <i class="ph-bold ph-compass text-sm text-blue-400"></i>
                        <span>Google Maps</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Kartu Kanan: Form Konsultasi Minimalis (Sesuai Sisi Kanan Contact Me) --}}
        <div class="bg-slate-50 border border-slate-200/90 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm">
            <div class="space-y-4">
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                        Tanya Konsultasi / Estimasi Pengerjaan
                    </h3>
                    <p class="text-xs text-slate-500 font-normal leading-relaxed mt-1">
                        Tuliskan kebutuhan modifikasi kendaraan Anda. Tombol di bawah akan langsung menghubungkan pesan ke WhatsApp teknisi.
                    </p>
                </div>

                {{-- Form Fields Minimalis --}}
                <div class="space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Nama Anda:
                        </label>
                        <input type="text" id="consult-name"
                               placeholder="Contoh: Budi Santoso"
                               class="w-full bg-white border border-slate-200 focus:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Tipe Kendaraan / Motor / Mobil:
                        </label>
                        <input type="text" id="consult-vehicle"
                               placeholder="Contoh: Honda Vario 160 / Toyota Innova Reborn"
                               class="w-full bg-white border border-slate-200 focus:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Kebutuhan / Rencana Pengerjaan:
                        </label>
                        <textarea id="consult-message" rows="3"
                                  placeholder="Contoh: Mau tanya estimasi pasang full body wrap Oracal hitam doff dan durasi pengerjaan..."
                                  class="w-full bg-white border border-slate-200 focus:border-slate-800 rounded-xl p-3.5 text-xs text-slate-900 placeholder-slate-400 focus:outline-none transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-5 mt-4 border-t border-slate-200/80">
                <button type="button" onclick="sendQuickWhatsAppConsultation()"
                        class="w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 text-white font-montserrat font-medium text-xs sm:text-sm tracking-wide transition-all shadow-md shadow-orange-500/20 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-95 cursor-pointer">
                    <i class="ph-bold ph-paper-plane-tilt text-sm text-white"></i>
                    <span>Kirim Pesan ke WhatsApp Workshop</span>
                </button>
            </div>
        </div>

    </div>

</div>

<script>
    function sendQuickWhatsAppConsultation() {
        const name = (document.getElementById('consult-name')?.value || '').trim();
        const vehicle = (document.getElementById('consult-vehicle')?.value || '').trim();
        const msg = (document.getElementById('consult-message')?.value || '').trim();

        let text = 'Halo Dantie Stiker, saya ingin konsultasi layanan wrapping:\n';
        if (name) text += '- Nama: ' + name + '\n';
        if (vehicle) text += '- Kendaraan: ' + vehicle + '\n';
        if (msg) text += '- Kebutuhan: ' + msg + '\n';

        const phone = '{{ \App\Helpers\StaticContent::COMPANY_PHONE }}';
        const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(text);
        window.open(url, '_blank');
    }
</script>

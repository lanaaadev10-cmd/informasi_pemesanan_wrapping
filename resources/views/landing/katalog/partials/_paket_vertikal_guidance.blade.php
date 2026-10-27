{{-- PANEL KHUSUS: ARAHAN KONSULTASI CUSTOM VIA WHATSAPP (MUNCUL KETIKA FILTER CUSTOM AKTIF) --}}
@php
    $rawWaGuidance = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
    if (str_starts_with($rawWaGuidance, '0')) {
        $rawWaGuidance = '62' . substr($rawWaGuidance, 1);
    }
@endphp
<div id="catalog-custom-guidance"
     class="hidden bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden space-y-4.5 my-2">

    <!-- Subtle Racing Top Accent Line -->
    <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-[#ff6b00]/60 to-transparent"></div>

    {{-- Header Panel --}}
    <div class="border-b border-slate-100 pb-3.5 space-y-1">
        <h3 id="custom-guidance-title" class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
            Alur Pemesanan Custom 
        </h3>
        <p id="custom-guidance-desc" class="text-xs sm:text-sm font-questrial text-slate-600 leading-relaxed max-w-2xl">
            Pengerjaan custom dibuat khusus sesuai konsep dan ukuran kendaraan Anda. Ikuti 3 langkah mudah berikut untuk memesan:
        </p>
    </div>

    {{-- 3 Langkah Alur Proyek Custom --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">1</span>
                <h4 class="text-xs font-montserrat font-bold text-slate-900">Konsultasi Desain</h4>
            </div>
            <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                Kirim foto kendaraan dan contoh desain yang Anda inginkan ke WhatsApp kami.
            </p>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">2</span>
                <h4 class="text-xs font-montserrat font-bold text-slate-900">Estimasi Biaya &amp; Jadwal</h4>
            </div>
            <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                Tim kami akan menghitung perkiraan biaya, pilihan bahan, dan waktu pengerjaan.
            </p>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">3</span>
                <h4 class="text-xs font-montserrat font-bold text-slate-900">Pesanan Diproses</h4>
            </div>
            <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                Setelah Anda setuju, admin akan langsung membuatkan data pesanan dan jadwal pengerjaan Anda.
            </p>
        </div>
    </div>

    {{-- Tombol Aksi WhatsApp & Navigasi --}}
    <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2.5">
        <a id="custom-guidance-wa-btn"
           href="https://api.whatsapp.com/send?phone={{ $rawWaGuidance }}&text={{ urlencode('Halo Tim Dantie Stiker, saya ingin konsultasi desain & estimasi biaya untuk proyek Custom kendaraan saya...') }}"
           target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 hover:shadow-lg transition-all active:scale-95">
            <i class="ph-bold ph-whatsapp-logo text-base"></i>
            <span>Chat WhatsApp &amp; Konsultasi Sekarang</span>
        </a>

        <button type="button" onclick="setServiceFilter('all')"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-montserrat font-semibold text-xs active:scale-95 transition-all">
            <i class="ph-bold ph-arrow-left text-xs"></i>
            <span>Kembali ke Pilihan Paket</span>
        </button>
    </div>
</div>

<!-- Empty State ketika filter pencarian tidak ditemukan -->
<div id="catalog-empty-state"
     class="hidden w-full py-14 px-6 flex flex-col items-center justify-center text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl shadow-sm my-4">
    <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#ff6b00] mb-3 text-xl">
        <i class="ph-bold ph-archive-box"></i>
    </div>
    <h4 class="text-sm font-audiowide font-bold text-slate-900 tracking-wide">
        Layanan Tidak Ditemukan
    </h4>
    <p class="text-xs font-questrial text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
        Tidak ada paket yang sesuai dengan filter atau kata kunci pencarian Anda saat ini.
    </p>
    <div class="mt-3">
        <button type="button"
                onclick="resetAllKatalogFilters()"
                class="px-4 py-2 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 active:scale-95">
            Tampilkan Semua Layanan
        </button>
    </div>
</div>

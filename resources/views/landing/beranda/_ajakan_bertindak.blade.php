{{-- ============================================
    BAGIAN: 9. Headline, Sub Headline & Ask for the Sale
    Sesuai Struktur Wireframe Mockup "Elements of a Website"
============================================ --}}
<section class="py-14 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-10" id="ask-sale-section" data-aos="fade-up" data-aos-duration="1000">

    <div class="bg-gradient-to-br from-slate-50 via-white to-orange-50/50 border border-slate-200/90 rounded-[32px] p-8 sm:p-12 lg:p-16 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            {{-- Left Column: Headline, Sub Headline, Trust Guarantees --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="space-y-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#ea580c] block">
                        AMANKAN SLOT ANDA SEKARANG
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif text-stone-900 tracking-tight leading-tight font-normal">
                        Kenyamanan &amp; keanggunan mobil impian Anda<br>
                        <span class="italic text-stone-500 font-light">hanya selangkah lagi.</span>
                    </h2>
                    <p class="text-stone-600 text-sm sm:text-base leading-relaxed font-light">
                        Jangan biarkan bodi kendaraan Anda tergores kerikil atau pudar terpapar cuaca. Konsultasikan ide warna Anda dan tentukan jadwal pengerjaan paling nyaman.
                    </p>
                </div>

                {{-- Guarantee Icons Row (Matching wireframe icons) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#ea580c] flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-shield-check text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-slate-900">Garansi 1-3 Thn</span>
                            <span class="block text-[10px] text-slate-500">Bebas gelembung</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-scissors text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-slate-900">Knifeless Tape</span>
                            <span class="block text-[10px] text-slate-500">Cat asli tanpa lecet</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-medal text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-slate-900">Oracal Jerman</span>
                            <span class="block text-[10px] text-slate-500">100% Original</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Ask for the Sale Box (Price, Direct Booking CTA) --}}
            <div class="lg:col-span-5">
                <div class="bg-white border-2 border-orange-500/30 rounded-[28px] p-6 sm:p-8 shadow-xl space-y-6 text-center relative overflow-hidden">
                    <div class="space-y-1">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block">Reservasi Prioritas</span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">Booking Slot Wrapping</h3>
                        <p class="text-xs text-slate-500">Pilih tanggal dan amankan slot pengerjaan Anda hari ini</p>
                    </div>

                    <div class="py-4 px-6 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-around">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Jasa Mulai</span>
                            <span class="text-xl sm:text-2xl font-black text-[#ea580c]">
                                {{ (isset($minHarga) && $minHarga > 0) ? 'Rp ' . number_format($minHarga / 1000, 0, ',', '.') . 'rb' : 'Rp 100rb' }}
                            </span>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400 block">Estimasi</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-800">1-3 Hari</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ auth()->check() ? route('katalog.user') : route('register') }}" 
                           class="w-full py-4 rounded-2xl text-xs font-black uppercase tracking-wider text-white bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2 active:scale-95">
                            <i class="ph-bold ph-calendar-plus text-base"></i>
                            <span>Pesan Layanan Sekarang</span>
                        </a>

                        @php
                            $waBase = $profil->whatsapp_link ?? 'https://wa.me/6283117163605';
                            $waText = urlencode('Halo ' . ($profil->nama_perusahaan ?? 'Dantie Stiker') . ', saya ingin konsultasi harga dan booking slot wrapping...');
                            $waFullUrl = str_contains($waBase, '?') ? "{$waBase}&text={$waText}" : "{$waBase}?text={$waText}";
                        @endphp
                        <a href="{{ $waFullUrl }}" 
                           target="_blank" rel="noopener"
                           class="w-full py-3 rounded-2xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                            <i class="ph-bold ph-whatsapp-logo text-base text-emerald-600"></i>
                            <span>Konsultasi Cepat via WhatsApp</span>
                        </a>
                    </div>

                    <p class="text-[10px] text-slate-400">
                        *Tanpa biaya tersembunyi. Kontrol gratis 1 bulan pasca pengerjaan.
                    </p>
                </div>
            </div>

        </div>
    </div>

</section>

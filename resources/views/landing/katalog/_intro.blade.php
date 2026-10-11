{{-- ============================================
    BAGIAN: Intro & Vehicle-First Filters (Senior UX Architecture)
    Deskripsi: Alur berbasis kendaraan (Vehicle-First):
                Langkah 1: Pilih Kendaraan (Semua / Mobil / Motor)
                Langkah 2: Pilih Kelompok Layanan (Full Wrap, Jasa Pasang, Custom, Aksesoris)
============================================ --}}
<div class="space-y-4 sm:space-y-5">

    {{-- ════ LANGKAH 1: PILIH JENIS KENDARAAN ════ --}}
    <div class="space-y-2.5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-[#ff6b00]/15 text-[#ff6b00] border border-[#ff6b00]/30 uppercase tracking-wider">
                    Langkah 1
                </span>
                <h2 class="text-xs sm:text-sm font-audiowide font-bold text-white tracking-wide">
                    Pilih Jenis Kendaraan Anda:
                </h2>
            </div>
            <span class="text-[11px] font-questrial text-gray-400 hidden sm:inline">
                Klik jenis kendaraan untuk memfilter paket yang relevan
            </span>
        </div>

        {{-- 3 Kartu Pilihan Kendaraan Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
            {{-- Kartu 1: Semua Kendaraan --}}
            <div onclick="setVehicleFilter('all')"
                 id="vehicle-card-all"
                 data-vehicle="all"
                 role="button"
                 tabindex="0"
                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); setVehicleFilter('all'); }"
                 class="vehicle-card cursor-pointer p-4 sm:p-5 rounded-2xl bg-[#161619] border border-white/10 hover:border-white/20 transition-all duration-300 group relative overflow-hidden active:scale-[0.99]">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center text-xl shadow-sm">
                        🏁
                    </span>
                    <span class="vehicle-card-status text-[10px] font-montserrat font-bold px-2 py-0.5 rounded-full bg-white/5 text-gray-400 border border-white/10 transition-all">
                        Semua
                    </span>
                </div>
                <h3 class="text-sm sm:text-base font-audiowide font-bold text-white group-hover:text-[#ff6b00] transition-colors">
                    Semua Kendaraan
                </h3>
                <p class="text-[11px] font-questrial text-gray-400 mt-1 leading-relaxed">
                    Tampilkan seluruh paket katalog untuk mobil dan motor tanpa batasan kendaraan.
                </p>
            </div>

            {{-- Kartu 2: Mobil --}}
            <div onclick="setVehicleFilter('mobil')"
                 id="vehicle-card-mobil"
                 data-vehicle="mobil"
                 role="button"
                 tabindex="0"
                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); setVehicleFilter('mobil'); }"
                 class="vehicle-card cursor-pointer p-4 sm:p-5 rounded-2xl bg-[#161619] border border-white/10 hover:border-[#ff6b00]/60 transition-all duration-300 group relative overflow-hidden active:scale-[0.99]">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-10 h-10 rounded-xl bg-[#ff6b00]/20 text-[#ff6b00] border border-[#ff6b00]/30 flex items-center justify-center text-xl shadow-sm">
                        🚗
                    </span>
                    <span class="vehicle-card-status text-[10px] font-montserrat font-bold px-2 py-0.5 rounded-full bg-white/5 text-gray-400 border border-white/10 transition-all">
                        Pilih Mobil
                    </span>
                </div>
                <h3 class="text-sm sm:text-base font-audiowide font-bold text-white group-hover:text-[#ff6b00] transition-colors">
                    Mobil (Roda 4)
                </h3>
                <p class="text-[11px] font-questrial text-gray-400 mt-1 leading-relaxed">
                    Full body wrap, jasa pasang panel, custom (atap/kap), kaca film, lampu Bi-LED, &amp; audio.
                </p>
            </div>

            {{-- Kartu 3: Motor --}}
            <div onclick="setVehicleFilter('motor')"
                 id="vehicle-card-motor"
                 data-vehicle="motor"
                 role="button"
                 tabindex="0"
                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); setVehicleFilter('motor'); }"
                 class="vehicle-card cursor-pointer p-4 sm:p-5 rounded-2xl bg-[#161619] border border-white/10 hover:border-[#ff6b00]/60 transition-all duration-300 group relative overflow-hidden active:scale-[0.99]">
                <div class="flex items-center justify-between mb-2">
                    <span class="w-10 h-10 rounded-xl bg-[#ff6b00]/20 text-[#ff6b00] border border-[#ff6b00]/30 flex items-center justify-center text-xl shadow-sm">
                        🛵
                    </span>
                    <span class="vehicle-card-status text-[10px] font-montserrat font-bold px-2 py-0.5 rounded-full bg-white/5 text-gray-400 border border-white/10 transition-all">
                        Pilih Motor
                    </span>
                </div>
                <h3 class="text-sm sm:text-base font-audiowide font-bold text-white group-hover:text-[#ff6b00] transition-colors">
                    Motor (Roda 2)
                </h3>
                <p class="text-[11px] font-questrial text-gray-400 mt-1 leading-relaxed">
                    Full wrap Oracal bergaransi, jasa pasang decal stiker sendiri (matic/sport), dan striping.
                </p>
            </div>
        </div>
    </div>

    {{-- ════ LANGKAH 2: PILIH LAYANAN UNTUK KENDARAAN TERPILIH ════ --}}
    <div class="bg-[#121215] border border-white/10 rounded-2xl p-3.5 sm:p-4 shadow-lg space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-white/5 pb-2.5">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-[#ff6b00]/15 text-[#ff6b00] border border-[#ff6b00]/30 uppercase tracking-wider">
                    Langkah 2
                </span>
                <span class="text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider" id="service-section-title">
                    Pilihan Layanan:
                </span>
            </div>

            {{-- Tombol Reset --}}
            <button type="button" onclick="resetAllKatalogFilters()"
                    class="self-start sm:self-auto text-xs font-montserrat font-bold text-gray-400 hover:text-[#ff6b00] transition-colors flex items-center gap-1.5">
                <i class="ph-bold ph-arrow-counter-clockwise text-sm text-[#ff6b00]"></i>
                <span>Reset Pilihan</span>
            </button>
        </div>

        {{-- Opsi Layanan untuk "SEMUA KENDARAAN" (Muncul saat kendaraan = all) --}}
        <div id="service-pills-all" class="flex items-center gap-2 flex-wrap">
            <button type="button" onclick="setServiceFilter('all')" data-service-pill="all"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold bg-[#ff6b00] text-black shadow-md transition-all">
                🏁 Semua Layanan
            </button>
            <button type="button" onclick="setServiceFilter('wrapping')" data-service-pill="wrapping" data-category="wrapping"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                📦 Full Body Wrap (Bahan Toko)
            </button>
            <button type="button" onclick="setServiceFilter('jasa-pasang')" data-service-pill="jasa-pasang" data-category="jasa-pasang"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                ✂️ Jasa Pasang Saja (Bawa Bahan)
            </button>
            <button type="button" onclick="setServiceFilter('custom')" data-service-pill="custom"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                🎨 Custom Decal &amp; Striping
            </button>
            <button type="button" onclick="setServiceFilter('aksesoris')" data-service-pill="aksesoris"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                💡 Aksesoris &amp; Upgrade
            </button>
        </div>

        {{-- Opsi Layanan untuk "MOBIL" (Muncul saat kendaraan = mobil) --}}
        <div id="service-pills-mobil" class="hidden flex items-center gap-2 flex-wrap">
            <button type="button" onclick="setServiceFilter('all')" data-service-pill="all"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold bg-[#ff6b00] text-black shadow-md transition-all">
                🏁 Semua Layanan Mobil
            </button>
            <button type="button" onclick="setServiceFilter('wrapping')" data-service-pill="wrapping"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                📦 Full Body Wrap (Bahan Toko)
            </button>
            <button type="button" onclick="setServiceFilter('jasa-pasang')" data-service-pill="jasa-pasang"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                ✂️ Jasa Pasang (Bawa Bahan Sendiri)
            </button>
            <button type="button" onclick="setServiceFilter('custom')" data-service-pill="custom"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                🎨 Custom Decal Mobil
            </button>
            <button type="button" onclick="setServiceFilter('aksesoris')" data-service-pill="aksesoris"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                💡 Aksesoris (Lampu &amp; Audio)
            </button>
        </div>

        {{-- Opsi Layanan untuk "MOTOR" (Muncul saat kendaraan = motor) --}}
        <div id="service-pills-motor" class="hidden flex items-center gap-2 flex-wrap">
            <button type="button" onclick="setServiceFilter('all')" data-service-pill="all"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold bg-[#ff6b00] text-black shadow-md transition-all">
                🏁 Semua Layanan Motor
            </button>
            <button type="button" onclick="setServiceFilter('wrapping')" data-service-pill="wrapping"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                📦 Full Wrap Motor (Bahan Toko + Garansi)
            </button>
            <button type="button" onclick="setServiceFilter('jasa-pasang')" data-service-pill="jasa-pasang"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                ✂️ Jasa Pasang Decal (Bawa Bahan Sendiri)
            </button>
            <button type="button" onclick="setServiceFilter('custom')" data-service-pill="custom"
                    class="service-pill px-3 py-1.5 rounded-xl text-xs font-montserrat font-bold text-gray-400 hover:text-white bg-[#18181c] border border-white/10 transition-all">
                🎨 Striping &amp; Custom Decal
            </button>
        </div>
    </div>

    {{-- ════ 3. ACTIVE FILTERS SUMMARY & LIVE COUNT ════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-1 text-xs">
        <div class="flex items-center gap-2 flex-wrap text-gray-400 font-questrial">
            <span>Menampilkan <strong id="catalog-visible-count" class="text-[#ff6b00] font-montserrat font-bold">0</strong> layanan untuk:</span>
            <div id="catalog-active-chips" class="inline-flex items-center gap-1.5 flex-wrap">
                <!-- Diisi via JavaScript -->
            </div>
        </div>

        <button type="button" 
                id="catalog-clear-all-btn" 
                onclick="resetAllKatalogFilters()"
                class="hidden text-[#ff6b00] hover:underline text-[11px] font-montserrat font-bold self-start sm:self-auto">
            Hapus Semua Filter ×
        </button>
    </div>

</div>

{{-- ──────────────────────────────────────────
     SEKSI 1: LAYANAN YANG DIPESAN (E-COMMERCE ORDER ITEM)
     Deskripsi: Menampilkan paket layanan terpilih dalam kartu produk ringkas ala e-commerce,
                lengkap dengan tombol 'Ubah Layanan' (modal popup) & form bahan jika jasa pasang.
────────────────────────────────────────── --}}
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6283117163605');
    if (empty($whatsappNumber)) {
        $whatsappNumber = '6283117163605';
    }
    $whatsappConsultUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode("Halo Tim Dantie Stiker, saya ingin konsultasi mengenai pilihan jasa pasang / wrapping untuk kendaraan saya...");
@endphp

<section id="section-layanan"
         class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-sm space-y-5">

    {{-- 1. Section Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#ff6b00] text-sm font-montserrat font-bold shadow-sm shrink-0">
                1
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Layanan yang Dipesan
                </h2>
                <p class="text-xs font-questrial text-slate-500 mt-0.5">
                    Paket pengerjaan variasi &amp; wrapping kendaraan pilihan Anda
                </p>
            </div>
        </div>

        {{-- Tombol Ubah Layanan (Buka Modal Katalog Ringkas) --}}
        <button type="button"
                @click="showServiceModal = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 hover:border-[#ff6b00] text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all w-fit active:scale-95">
            <i class="ph-bold ph-arrows-clockwise text-sm"></i>
            <span>Ubah Layanan</span>
        </button>
    </div>

    {{-- 2. Kartu Produk Terpilih (E-Commerce Item Card) --}}
    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5 relative group shadow-sm"
         x-show="selectedLayanan">

        {{-- Foto Showcase Layanan --}}
        <div class="w-full sm:w-36 h-28 sm:h-24 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative">
            <img :src="selectedLayanan?.foto"
                 :alt="selectedLayanan?.name"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
            <div class="absolute top-2 left-2">
                <span class="text-[9px] font-montserrat font-bold uppercase px-2 py-0.5 rounded-md backdrop-blur-md bg-white/90 text-slate-800 border border-slate-200 shadow-sm"
                      x-text="selectedLayanan?.kategori_label"></span>
            </div>
        </div>

        {{-- Detail Informasi Layanan --}}
        <div class="flex-1 space-y-1.5 min-w-0">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border"
                      :class="selectedLayanan?.scope_class"
                      x-text="selectedLayanan?.scope_badge || selectedLayanan?.tipe_paket"></span>
                <template x-if="selectedLayanan?.compatibility">
                    <span class="text-[10px] font-questrial text-slate-500 truncate hidden md:inline"
                          x-text="selectedLayanan?.compatibility"></span>
                </template>
            </div>

            <h3 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 truncate"
                x-text="selectedLayanan?.name"></h3>

            <div class="flex flex-wrap items-center gap-4 text-xs font-questrial text-slate-500 pt-1">
                <div class="flex items-center gap-1.5">
                    <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                    <span>Estimasi Durasi: <strong class="text-slate-800 font-semibold" x-text="selectedLayanan?.estimasi || '2-3 Hari Kerja'"></strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <i class="ph-bold ph-shield-check text-emerald-600"></i>
                    <span>Garansi Workshop Resmi</span>
                </div>
            </div>
        </div>

        {{-- Kolom Harga & Tombol Ganti --}}
        <div class="text-left sm:text-right shrink-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-200 flex sm:flex-col justify-between sm:justify-center items-center sm:items-end">
            <div>
                <span class="text-[10px] font-questrial text-slate-500 block"
                      x-text="selectedLayanan?.is_jasa_pasang ? 'Tarif Jasa Pasang:' : 'Tarif Paket:'"></span>
                <span class="text-lg sm:text-xl font-audiowide font-bold text-[#ff6b00] block"
                      x-text="formatRupiah(basePrice)"></span>
            </div>
        </div>
    </div>

    {{-- Fallback jika belum memilih layanan --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-300 text-center space-y-3"
         x-show="!selectedLayanan">
        <p class="text-sm font-questrial text-slate-500">Belum ada layanan yang dipilih untuk checkout.</p>
        <button type="button" @click="showServiceModal = true"
                class="px-5 py-2.5 rounded-xl bg-[#ff6b00] hover:bg-[#e05d00] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md">
            Pilih Layanan Sekarang
        </button>
    </div>

    {{-- ════ 3. FORM KHUSUS JASA PASANG (BAWA BAHAN SENDIRI) ════ --}}
    <div x-show="isJasaPasang" x-transition.opacity
         class="p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-4">
        <div class="flex items-center gap-2.5 border-b border-emerald-200 pb-3">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm">
                <i class="ph-bold ph-scissors"></i>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-montserrat font-bold text-emerald-900">
                    Spesifikasi Bahan Konsumen (Layanan Jasa Pasang Saja)
                </h4>
                <p class="text-[11px] font-questrial text-slate-600">
                    Mohon lengkapi rincian bahan stiker/vinyl yang Anda bawa sendiri
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Merek Bahan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Merek / Tipe Bahan Vinyl
                </label>
                <input type="text" name="material_brand" x-model="materialBrand"
                       placeholder="Contoh: Oracal 651, Maxdecal 7500, Derek, Avery"
                       class="field-input w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-xs shadow-sm">
            </div>

            {{-- Estimasi Ukuran --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Ukuran Bahan yang Dibawa
                </label>
                <input type="text" name="material_size" x-model="materialSize"
                       placeholder="Contoh: 1 Roll (18 Meter) atau Lembaran Potongan"
                       class="field-input w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-xs shadow-sm">
            </div>

            {{-- Kondisi Bahan --}}
            <div class="sm:col-span-2 space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Kondisi Bahan &amp; Catatan Khusus
                </label>
                <textarea name="material_condition" x-model="materialCondition" rows="2"
                          placeholder="Jelaskan kondisi stiker yang Anda bawa (misal: roll baru segel, bahan sisa proyek, ada lipatan, dll)..."
                          class="field-input w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-xs shadow-sm"></textarea>
            </div>

            {{-- Upload Foto Bahan --}}
            <div class="sm:col-span-2 space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Foto Fisik Bahan Stiker (Opsional)
                </label>
                <div class="flex items-center gap-3">
                    <label class="cursor-pointer px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 flex items-center gap-2 shadow-sm">
                        <i class="ph-bold ph-upload-simple"></i>
                        <span>Pilih Foto Bahan</span>
                        <input type="file" name="material_photo" accept="image/*" class="sr-only" @change="handleMaterialPhoto($event)">
                    </label>
                    <span class="text-xs font-questrial text-slate-500 truncate" x-text="materialPhotoName || 'Belum ada foto terpilih'"></span>
                </div>
            </div>

            {{-- Disclaimer Jasa Pasang --}}
            <div class="sm:col-span-2 pt-2 border-t border-emerald-200">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="material_disclaimer_agreed" value="1"
                           x-model="materialDisclaimerAgreed"
                           class="mt-1 w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 bg-white border-slate-300">
                    <span class="text-xs font-questrial text-slate-700 leading-relaxed">
                        <strong class="text-emerald-700 font-bold">Pernyataan Tanggung Jawab Bahan:</strong>
                        Saya memahami bahwa layanan ini adalah <em>jasa pasang</em> dengan material bawaan sendiri. Dantie Stiker menjamin kerapian teknik pemasangan profesional, namun kerusakan adhesif / kegagalan lem bawaan material di luar kendali workshop bukan tanggung jawab garansi material.
                    </span>
                </label>
            </div>
        </div>
    </div>

    {{-- ════ 3.5. WIZARD NAVIGATION STEP 1 ════ --}}
    <div class="pt-5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500 font-questrial flex items-center gap-2">
            <i class="ph-bold ph-shield-check text-[#ff6b00] text-base shrink-0"></i>
            <span>Langkah 1 dari 4: Paket pengerjaan &amp; jaminan spesifikasi workshop.</span>
        </div>
        <button type="button"
                @click="nextStep()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-[#ff6b00] hover:bg-[#e05d00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98] shadow-md cursor-pointer">
            <span>Lanjut ke Jadwal &amp; Shift</span>
            <i class="ph-bold ph-arrow-right text-sm"></i>
        </button>
    </div>

    {{-- ════ 4. MODAL DIALOG PEMILIHAN LAYANAN (MINIMALIS POPUP) ════ --}}
    <div x-show="showServiceModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
         style="display: none;"
         @keydown.escape.window="showServiceModal = false">

        <div @click.away="showServiceModal = false"
             class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden relative">

            {{-- Modal Header --}}
            <div class="p-5 sm:p-6 border-b border-slate-200 flex items-center justify-between gap-4 bg-slate-50">
                <div>
                    <h3 class="text-base sm:text-lg font-audiowide font-bold text-slate-900">
                        Pilih Paket / Layanan Lain
                    </h3>
                    <p class="text-xs font-questrial text-slate-500 mt-0.5">
                        Pilih layanan yang ingin Anda reservasi untuk kendaraan Anda
                    </p>
                </div>
                <button type="button" @click="showServiceModal = false"
                        class="w-9 h-9 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-400 hover:text-slate-900 flex items-center justify-center transition-colors shadow-sm">
                    <i class="ph-bold ph-x text-base"></i>
                </button>
            </div>

            {{-- Asisten Pintar Bar --}}
            <div class="px-5 py-3 bg-orange-50/60 border-b border-orange-200/60 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 flex-wrap">
                    <i class="ph-bold ph-sparkle text-[#ff6b00]"></i>
                    <span class="font-montserrat font-bold text-slate-800">Asisten Pintar Pilihan Layanan:</span>
                    <span class="text-slate-500 font-medium">Tipe Kendaraan:</span>
                    <span class="font-bold text-slate-700">Mobil / Motor</span>
                    <span class="text-slate-400 hidden sm:inline">|</span>
                    <span class="text-slate-500 font-medium">Jenis Kebutuhan:</span>
                    <button type="button" @click="searchQuery = 'Jasa Pasang'" class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 hover:text-[#ff6b00] font-bold text-[11px] shadow-sm">Jasa Pasang</button>
                    <button type="button" @click="searchQuery = 'Full Wrap'" class="px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700 hover:text-[#ff6b00] font-bold text-[11px] shadow-sm">Full Wrap All-In</button>
                </div>
                <a href="{{ $whatsappConsultUrl }}" target="_blank" class="inline-flex items-center gap-1.5 text-[#ff6b00] hover:text-[#ea580c] font-bold shrink-0">
                    <i class="ph-bold ph-whatsapp-logo"></i>
                    <span>Tanya Teknisi via WA</span>
                </a>
            </div>

            {{-- Filter Bar di Modal --}}
            <div class="p-4 sm:p-5 border-b border-slate-200 bg-white flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                {{-- Filter Tipe Kendaraan --}}
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="setVehicleFilter('semua')"
                            :class="selectedVehicleType === 'semua' ? 'bg-[#ff6b00] text-white font-bold shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-xl text-xs font-montserrat transition-all">
                        Semua
                    </button>
                    <button type="button" @click="setVehicleFilter('mobil')"
                            :class="selectedVehicleType === 'mobil' ? 'bg-[#ff6b00] text-white font-bold shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-xl text-xs font-montserrat transition-all flex items-center gap-1">
                        <span>🚗</span><span>Mobil</span>
                    </button>
                    <button type="button" @click="setVehicleFilter('motor')"
                            :class="selectedVehicleType === 'motor' ? 'bg-[#ff6b00] text-white font-bold shadow-sm' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
                            class="px-3 py-1.5 rounded-xl text-xs font-montserrat transition-all flex items-center gap-1">
                        <span>🛵</span><span>Motor</span>
                    </button>
                </div>

                {{-- Search Box --}}
                <div class="relative flex-1 sm:max-w-xs">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama layanan..."
                           class="w-full pl-8 pr-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-900 placeholder-slate-400 font-questrial focus:border-[#ff6b00] outline-none shadow-sm">
                </div>
            </div>

            {{-- List Layanan di Modal (Scrollable) --}}
            <div class="p-4 sm:p-6 overflow-y-auto space-y-3 flex-1 scrollbar-thin scrollbar-thumb-slate-200">
                <template x-for="item in filteredLayanans" :key="'modal-layanan-' + item.id">
                    <div class="p-3.5 sm:p-4 rounded-2xl border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3 group"
                         :class="String(item.id) === String(layananId) ? 'bg-orange-50 border-[#ff6b00] shadow-sm ring-1 ring-[#ff6b00]/40' : 'bg-slate-50 border-slate-200 hover:border-[#ff6b00]/40 hover:bg-white'">

                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-16 h-14 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                                <img :src="item.foto" :alt="item.name" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-0.5 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider px-2 py-0.5 rounded"
                                          :class="item.scope_class" x-text="item.scope_badge"></span>
                                    <span class="text-[9px] font-montserrat font-bold text-slate-500" x-text="item.kategori_label"></span>
                                </div>
                                <h4 class="text-sm font-audiowide font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                <p class="text-[11px] font-questrial text-slate-500" x-text="'Estimasi: ' + item.estimasi"></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                            <span class="text-sm sm:text-base font-audiowide font-bold text-[#ff6b00]" x-text="formatRupiah(item.price)"></span>
                            <button type="button"
                                    @click="selectLayanan(item.id)"
                                    :class="String(item.id) === String(layananId) ? 'bg-[#ff6b00] text-white font-extrabold shadow-sm' : 'bg-white text-slate-700 hover:bg-[#ff6b00] hover:text-white border border-slate-200 font-bold shadow-sm'"
                                    class="px-4 py-2 rounded-xl text-xs font-montserrat transition-all active:scale-95">
                                <span x-text="String(item.id) === String(layananId) ? 'Terpilih ✓' : 'Pilih Ini'"></span>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="filteredLayanans.length === 0" class="py-12 text-center text-slate-500 text-xs font-questrial">
                    Tidak ada layanan yang sesuai dengan pencarian Anda.
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                <button type="button" @click="showServiceModal = false"
                        class="px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</section>

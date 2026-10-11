{{-- ──────────────────────────────────────────
     LANGKAH 3: DATA KONTAK, SPESIFIKASI KENDARAAN, & LAYANAN
────────────────────────────────────────── --}}
<section id="section-kendaraan"
         class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-7 shadow-sm space-y-7">

    {{-- Header Seksi 3 --}}
    <div class="border-b border-slate-100 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-sm font-montserrat font-bold shadow-sm shrink-0">
                3
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Data Kontak &amp; Spesifikasi Kendaraan
                </h2>
                <p class="text-xs font-questrial text-slate-500 mt-0.5">
                    Lengkapi identitas pemesan dan data kendaraan yang akan dikerjakan di workshop
                </p>
            </div>
        </div>
        <div class="shrink-0 flex items-center gap-2" x-show="selectedLayanan">
            <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg border"
                  :class="selectedLayanan?.scope_class"
                  x-text="selectedLayanan?.scope_badge || selectedLayanan?.tipe_paket"></span>
        </div>
    </div>

    {{-- ════ 1. DATA KONTAK PELANGGAN ════ --}}
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-xs font-bold">1</div>
            <h3 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider">
                Informasi Kontak Pemesan
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            {{-- Nama Lengkap --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-user text-sm"></i>
                    </span>
                    <input type="text" name="customer_name" x-model="customerName" :required="currentStep === 3"
                           placeholder="Contoh: Budi Santoso"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
                @error('customer_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-emerald-600">
                        <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                    </span>
                    <input type="tel" name="customer_phone" x-model="customerPhone" :required="currentStep === 3"
                           placeholder="Contoh: 081234567890"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
                <p class="text-[10px] font-questrial text-slate-500">Notifikasi status pengerjaan &amp; serah terima akan dikirim ke nomor ini.</p>
                @error('customer_phone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="sm:col-span-2 space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Email <span class="text-slate-400 font-normal">(Opsional untuk invoice digital)</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-envelope-simple text-sm"></i>
                    </span>
                    <input type="email" name="customer_email" x-model="customerEmail"
                           placeholder="Contoh: pelanggan@gmail.com"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
            </div>
        </div>
    </div>

    {{-- ════ 2. SPESIFIKASI & MASTER KENDARAAN ════ --}}
    <div class="space-y-4 pt-4 border-t border-slate-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-xs font-bold">2</div>
                <h3 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider">
                    Spesifikasi &amp; Tipe Kendaraan
                </h3>
            </div>
            <span class="text-[10px] text-slate-500 font-questrial">Cascading Brand &amp; Size Detection</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            {{-- Dropdown Merk / Brand --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Merk Kendaraan
                </label>
                <div class="relative">
                    <select name="brand_id" x-model="selectedBrandId" @change="onBrandChange()"
                            class="field-input w-full px-3.5 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm appearance-none cursor-pointer pr-9 focus:border-[#ff6b00] shadow-sm">
                        <option value="">-- Pilih Merk Kendaraan --</option>
                        <template x-for="brand in vehicleBrands" :key="brand.id">
                            <option :value="brand.id" x-text="brand.name + (brand.vehicle_type ? ' (' + brand.vehicle_type.toUpperCase() + ')' : '')"></option>
                        </template>
                    </select>
                    <i class="ph-bold ph-caret-down text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Dropdown Seri / Model Kendaraan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Model / Seri Kendaraan
                </label>
                <div class="relative">
                    <select name="model_id" x-model="selectedModelId" @change="onModelChange()"
                            :disabled="!selectedBrandId"
                            class="field-input w-full px-3.5 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm appearance-none cursor-pointer pr-9 focus:border-[#ff6b00] disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                        <option value="">-- Pilih Model / Seri --</option>
                        <template x-for="model in availableModels" :key="model.id">
                            <option :value="model.id" x-text="model.name + ' (' + model.vehicle_size.toUpperCase() + ')'"></option>
                        </template>
                    </select>
                    <i class="ph-bold ph-caret-down text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Ukuran Kendaraan (Deteksi Otomatis & Manual Override) --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                        Kategori Ukuran
                    </label>
                    <template x-if="selectedVehicleSize">
                        <span class="text-[9px] font-mono font-bold px-2 py-0.5 rounded border uppercase"
                              :class="sizeBadge(selectedVehicleSize)"
                              x-text="selectedVehicleSize.toUpperCase()"></span>
                    </template>
                </div>
                <div class="relative">
                    <select name="vehicle_size" x-model="selectedVehicleSize"
                            class="field-input w-full px-3.5 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm appearance-none cursor-pointer pr-9 focus:border-[#ff6b00] shadow-sm">
                        <option value="">-- Auto Detect / Pilih Ukuran --</option>
                        <option value="small">Small (S) - Motor / Hatchback Kecil</option>
                        <option value="medium">Medium (M) - Sedan / Compact SUV</option>
                        <option value="large">Large (L) - Medium SUV / MPV Keluarga</option>
                        <option value="xlarge">X-Large (XL) - Big SUV / Premium Van</option>
                    </select>
                    <i class="ph-bold ph-caret-down text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                </div>
            </div>

            {{-- Nama / Seri Lengkap Kendaraan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Nama / Tipe Lengkap Kendaraan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#ff6b00]">
                        <i class="ph-bold ph-car-profile text-sm"></i>
                    </span>
                    <input type="text" name="vehicle_name" x-model="vehicleName"
                           placeholder="Contoh: Honda Civic Turbo / Pajero Sport"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
            </div>

            {{-- Warna Kendaraan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Warna Asli Kendaraan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-paint-brush text-sm"></i>
                    </span>
                    <input type="text" name="vehicle_color" x-model="vehicleColor"
                           placeholder="Contoh: Hitam Glossy / Putih Mutiara"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
            </div>

            {{-- Plat Nomor / Nopol --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Nomor Polisi / Plat Kendaraan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-identification-card text-sm"></i>
                    </span>
                    <input type="text" name="vehicle_license" x-model="vehicleLicense"
                           placeholder="Contoh: B 1234 XYZ"
                           class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-[#ff6b00]">
                </div>
            </div>
        </div>
    </div>

    {{-- ════ 3. KONDISIONAL A: DEKLARASI MATERIAL VINYL (KHUSUS JASA PASANG) ════ --}}
    <div x-show="isJasaPasang" x-transition.opacity
         class="space-y-5 pt-4 border-t border-slate-100 bg-emerald-50/60 border border-emerald-200 rounded-2xl p-5 relative overflow-hidden">
        {{-- Background glow --}}
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-emerald-200 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-scissors text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-montserrat font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-2">
                        <span>Deklarasi Material Vinyl Pelanggan</span>
                        <span class="text-[9px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full border border-emerald-300 font-mono font-bold">Bawa Bahan Sendiri</span>
                    </h3>
                    <p class="text-[11px] font-questrial text-slate-600">
                        Mohon berikan informasi material vinyl Anda untuk inspeksi kelayakan perekat dan teknik instalasi teknisi kami.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Merk Bahan Vinyl --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Merk / Brand Bahan Vinyl <span class="text-emerald-600">*</span>
                </label>
                <input type="text" name="material_brand" x-model="materialBrand"
                       placeholder="Contoh: Oracal 651 / Teckwrap / Derek / Maxdecal"
                       class="field-input w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-emerald-500">
            </div>

            {{-- Estimasi Panjang / Ukuran Bahan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Estimasi Panjang / Dimensi
                </label>
                <input type="text" name="material_size" x-model="materialSize"
                       placeholder="Contoh: 1 Roll (18 Meter) / Potongan 5 Meter"
                       class="field-input w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm shadow-sm focus:border-emerald-500">
            </div>

            {{-- Kondisi Fisik Bahan --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Kondisi Fisik Bahan
                </label>
                <div class="relative">
                    <select name="material_condition" x-model="materialCondition"
                            class="field-input w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm appearance-none cursor-pointer pr-9 focus:border-emerald-500 shadow-sm">
                        <option value="baru">Baru / Masih Disegel</option>
                        <option value="sisa_gulungan">Sisa Gulungan (Pernah Terbuka)</option>
                        <option value="bekas_copotan">Bahan Bekas Copotan</option>
                    </select>
                    <i class="ph-bold ph-caret-down text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>

        {{-- Upload Foto Fisik Bahan --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                Foto Bahan Vinyl <span class="text-slate-400 font-normal">(Opsional &mdash; foto label merk atau gulungan)</span>
            </label>
            <label class="relative block border border-dashed border-emerald-300 hover:border-emerald-500 rounded-xl p-3.5 text-center transition-all bg-white cursor-pointer shadow-sm">
                <input type="file" name="material_photo" accept=".jpg,.jpeg,.png"
                       @change="handleMaterialPhoto($event)"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="flex items-center justify-center gap-2.5">
                    <i class="ph-bold ph-camera text-emerald-600 text-lg"></i>
                    <template x-if="!materialPhotoName">
                        <span class="text-xs text-slate-600 font-questrial">Klik untuk unggah foto fisik bahan vinyl (JPG/PNG maks. 5MB)</span>
                    </template>
                    <template x-if="materialPhotoName">
                        <span class="text-xs text-emerald-700 font-bold truncate max-w-xs" x-text="materialPhotoName"></span>
                    </template>
                </div>
            </label>
        </div>

        {{-- Kotak Persetujuan Disclaimer Material --}}
        <div class="p-3.5 rounded-xl bg-white border border-emerald-200 shadow-sm space-y-2">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="material_disclaimer_agreed" value="1"
                       x-model="materialDisclaimerAgreed"
                       class="mt-1 w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 bg-white">
                <div class="text-xs font-questrial text-slate-600 leading-relaxed">
                    <span class="font-montserrat font-bold text-slate-900 block mb-0.5">Pernyataan Tanggung Jawab &amp; Batasan Garansi (Disclaimer):</span>
                    Saya menyatakan bahwa saya membawa material vinyl sendiri. Saya memahami dan menyetujui bahwa Dantie Stiker hanya menjual ongkos jasa pemasangan. Segala risiko akibat kualitas material bawaan (seperti lem kurang rekat, gelembung akibat struktur pori-pori bahan, atau ketahanan daya tahan warna di luar workshop) sepenuhnya menjadi tanggung jawab pemilik kendaraan.
                </div>
            </label>
        </div>
    </div>

    {{-- ════ 3. KONDISIONAL B: SPESIFIKASI PROYEK CUSTOM (KHUSUS CUSTOM QUOTATION) ════ --}}
    <div x-show="isCustom" x-transition.opacity
         class="space-y-4 pt-4 border-t border-slate-100 bg-amber-50/60 border border-amber-200 rounded-2xl p-5 relative overflow-hidden">
        <div class="flex items-center gap-2.5 border-b border-amber-200 pb-3">
            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0">
                <i class="ph-bold ph-sketch-logo text-base"></i>
            </div>
            <div>
                <h3 class="text-xs sm:text-sm font-montserrat font-bold text-amber-900 uppercase tracking-wider flex items-center gap-2">
                    <span>Spesifikasi Desain &amp; Proyek Custom</span>
                    <span class="text-[9px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full border border-amber-300 font-mono font-bold">Quotation Review</span>
                </h3>
                <p class="text-[11px] font-questrial text-slate-600">
                    Proyek custom akan di-review terlebih dahulu oleh tim perancang Dantie Stiker untuk penerbitan penawaran harga resmi (Quotation).
                    <span class="text-amber-800 font-semibold">Sudah deal di WhatsApp?</span> Tuliskan kesepakatan harga &amp; konsep pada catatan di bawah.
                </p>
            </div>
        </div>

        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                    Catatan / Spesifikasi Custom Yang Diinginkan
                </label>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '6283117163605') }}?text={{ urlencode('Halo Tim Dantie Stiker, saya ingin konsultasi konsep desain & estimasi harga proyek Custom...') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1 text-[10px] text-emerald-600 hover:text-emerald-700 font-bold font-montserrat transition-colors">
                    <i class="ph-fill ph-whatsapp-logo text-xs"></i>
                    <span>Belum deal? Chat WA ➔</span>
                </a>
            </div>
            <textarea name="custom_request_notes" x-model="customRequestNotes" rows="3"
                      placeholder="Jelaskan konsep desain, bagian bodi kendaraan yang ingin dibungkus, atau nominal harga deal di WA..."
                      class="field-input w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm resize-none shadow-sm focus:border-amber-500"></textarea>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
                Foto Referensi Desain / Sketsa
            </label>
            <label class="relative block border border-dashed border-amber-300 hover:border-amber-500 rounded-xl p-3.5 text-center transition-all bg-white cursor-pointer shadow-sm">
                <input type="file" name="custom_reference_photo" accept=".jpg,.jpeg,.png"
                       @change="handleCustomPhoto($event)"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="flex items-center justify-center gap-2.5">
                    <i class="ph-bold ph-image text-amber-600 text-lg"></i>
                    <template x-if="!customPhotoName">
                        <span class="text-xs text-slate-600 font-questrial">Unggah foto referensi atau mock-up desain (JPG/PNG maks. 5MB)</span>
                    </template>
                    <template x-if="customPhotoName">
                        <span class="text-xs text-amber-700 font-bold truncate max-w-xs" x-text="customPhotoName"></span>
                    </template>
                </div>
            </label>
        </div>
    </div>

    {{-- ════ 4. PILIHAN LAYANAN TAMBAHAN (ADD-ONS WORKSHOP) ════ --}}
    <div class="space-y-4 pt-4 border-t border-slate-100" x-show="availableAddons && availableAddons.length > 0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-[#ff6b00]/15 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] text-xs font-bold">3</div>
                <div>
                    <h3 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span>Layanan Tambahan (Add-ons Workshop)</span>
                        <span class="text-[9px] bg-slate-100 px-2 py-0.5 rounded-full text-slate-600 font-normal normal-case">Opsional</span>
                    </h3>
                </div>
            </div>
            <span class="text-[10px] text-[#ff6b00] font-mono font-bold"
                  x-show="addonsTotal > 0"
                  x-text="'+ ' + formatRupiah(addonsTotal)"></span>
        </div>
        <p class="text-xs font-questrial text-slate-500">
            Tingkatkan proteksi dan ketahanan hasil pengerjaan dengan layanan finishing pendukung:
        </p>

        {{-- Hidden Form Inputs for Add-ons --}}
        <template x-for="addonId in selectedAddonIds" :key="'hidden-addon-' + addonId">
            <input type="hidden" name="addons[]" :value="addonId">
        </template>

        {{-- Grid Kartu Add-ons --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <template x-for="addon in availableAddons" :key="addon.id">
                <div @click="toggleAddon(addon.id)"
                     class="p-4 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between group relative overflow-hidden"
                     :class="isAddonSelected(addon.id) ? 'bg-orange-50/70 border-[#ff6b00] shadow-sm ring-1 ring-[#ff6b00]/50' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-slate-100/60'">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-mono font-bold uppercase tracking-wider px-2 py-0.5 rounded"
                                  :class="addon.category === 'prep' ? 'bg-blue-100 text-blue-700 border border-blue-200' : (addon.category === 'protection' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-purple-100 text-purple-700 border border-purple-200')"
                                  x-text="addon.category ? addon.category.toUpperCase() : 'FINISHING'"></span>
                            <h4 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 group-hover:text-[#ff6b00] transition-colors"
                                x-text="addon.name"></h4>
                        </div>
                        <div class="w-5 h-5 rounded-lg border flex items-center justify-center shrink-0 transition-all"
                             :class="isAddonSelected(addon.id) ? 'bg-[#ff6b00] border-[#ff6b00] text-white' : 'border-slate-300 bg-white text-transparent'">
                            <i class="ph-bold ph-check text-xs"></i>
                        </div>
                    </div>
                    <p class="text-[11px] font-questrial text-slate-600 leading-relaxed mb-3"
                       x-text="addon.description || 'Layanan finishing workshop profesional.'"></p>
                    <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                        <span class="text-[10px] text-slate-500 font-questrial">Biaya Tambahan:</span>
                        <span class="text-xs font-audiowide font-bold text-[#ff6b00]"
                              x-text="'+ ' + formatRupiah(addon.price)"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ════ 5. CATATAN KHUSUS WORKSHOP ════ --}}
    <div class="space-y-1.5 pt-4 border-t border-slate-100">
        <label class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider">
            Catatan Khusus / Permintaan Pengerjaan
        </label>
        <textarea name="notes" x-model="notes" rows="3"
                  placeholder="Tuliskan catatan tambahan untuk teknisi kami (contoh: tolong pasang list hitam doff di grill depan, ada baret halus di pintu kanan, dsb)..."
                  class="field-input w-full px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-questrial text-sm resize-none shadow-sm focus:border-[#ff6b00]"></textarea>
    </div>

    {{-- ════ 6. WIZARD NAVIGATION STEP 3 ════ --}}
    <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
        <button type="button"
                @click="prevStep()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 hover:text-slate-900 transition-all cursor-pointer shadow-sm">
            <i class="ph-bold ph-arrow-left text-sm"></i>
            <span>Kembali ke Jadwal</span>
        </button>

        <button type="button"
                @click="nextStep()"
                :disabled="!customerName || !customerPhone"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-[#ff6b00] hover:bg-[#e05d00] text-white font-montserrat font-extrabold text-xs uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98] shadow-[0_4px_20px_rgba(255,107,0,0.3)] disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
            <span>Lanjut ke Pembayaran</span>
            <i class="ph-bold ph-arrow-right text-sm"></i>
        </button>
    </div>

</section>

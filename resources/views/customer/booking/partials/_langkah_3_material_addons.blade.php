{{-- ============================================
     SUB-KOMPONEN LANGKAH 3:
     - Deklarasi Material Vinyl (Jasa Pasang)
     - Spesifikasi Proyek Custom (Quotation)
     - Add-ons Workshop (Layanan Tambahan)
============================================ --}}

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

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

    {{-- ════ 3 & 4. DEKLARASI MATERIAL, PROYEK CUSTOM, & ADD-ONS ════ --}}
    @include('customer.booking.partials._langkah_3_material_addons')


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

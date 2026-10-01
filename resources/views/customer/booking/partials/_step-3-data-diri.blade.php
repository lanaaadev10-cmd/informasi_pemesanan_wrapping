{{-- ──────────────────────────────────────────
     LANGKAH 3: DATA DIRI & KENDARAAN
────────────────────────────────────────── --}}
<section x-show="currentStep === 3" x-transition.opacity
         class="bg-[#141416]/95 border border-white/10 rounded-3xl p-5 sm:p-7 shadow-2xl space-y-6">

    <div class="border-b border-white/10 pb-5">
        <h2 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
            Langkah 3: Data Kontak &amp; Kendaraan
        </h2>
        <p class="text-xs font-questrial text-gray-400 mt-0.5">
            Informasi detail kendaraan untuk persiapan bahan dan teknisi workshop.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
        {{-- Nama Lengkap --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Nama Lengkap <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <i class="ph-bold ph-user text-sm"></i>
                </span>
                <input type="text" name="customer_name" x-model="customerName" required
                       placeholder="Contoh: Budi Santoso"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
            @error('customer_name') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
        </div>

        {{-- Nomor WhatsApp --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Nomor WhatsApp <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-emerald-400">
                    <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                </span>
                <input type="tel" name="customer_phone" x-model="customerPhone" required
                       placeholder="Contoh: 081234567890"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
            <p class="text-[10px] font-questrial text-gray-500">Notifikasi status pengerjaan akan dikirim ke nomor ini.</p>
            @error('customer_phone') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Email <span class="text-gray-500 font-normal">(Opsional)</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <i class="ph-bold ph-envelope-simple text-sm"></i>
                </span>
                <input type="email" name="customer_email" x-model="customerEmail"
                       placeholder="Contoh: pelanggan@gmail.com"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
        </div>

        {{-- Jenis / Model Kendaraan --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Model Kendaraan
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#ff6b00]">
                    <i class="ph-bold ph-car-profile text-sm"></i>
                </span>
                <input type="text" name="vehicle_name" x-model="vehicleName"
                       placeholder="Contoh: Honda Civic Turbo / Pajero Sport"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
        </div>

        {{-- Warna Kendaraan --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Warna Kendaraan
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <i class="ph-bold ph-paint-brush text-sm"></i>
                </span>
                <input type="text" name="vehicle_color" x-model="vehicleColor"
                       placeholder="Contoh: Hitam Glossy"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
        </div>

        {{-- Plat Nomor --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Nomor Polisi / Plat
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <i class="ph-bold ph-identification-card text-sm"></i>
                </span>
                <input type="text" name="vehicle_license" x-model="vehicleLicense"
                       placeholder="Contoh: B 1234 XYZ"
                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
            </div>
        </div>

        {{-- Catatan Khusus --}}
        <div class="sm:col-span-2 space-y-1.5">
            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                Catatan Khusus / Permintaan Khusus
            </label>
            <textarea name="notes" x-model="notes" rows="3"
                      placeholder="Tuliskan catatan khusus atau permintaan spesifikasi wrapping yang Anda inginkan..."
                      class="field-input w-full px-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm resize-none"></textarea>
        </div>
    </div>
</section>

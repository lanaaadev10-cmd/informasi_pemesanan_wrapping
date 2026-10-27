{{-- ============================================
    SECTION 5: "Thoughtful spaces and experiences for complete relaxation"
    (Editorial Headline + Video Preview Thumbnail + Numbered Accordion FAQ)
============================================ --}}
<section class="py-14 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="faq-section">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start" x-data="{ openFaq: 1 }">
        
        {{-- Left Column: Headline, Video/Workshop Card & Caption --}}
        <div class="lg:col-span-5 space-y-6" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif text-stone-900 tracking-tight leading-[1.15] font-normal">
                Standar pengerjaan tinggi<br>
                dan pengalaman<br>
                <span class="italic text-stone-600 font-light">tanpa kompromi.</span>
            </h2>

            {{-- Workshop Showcase Photo (Murni Foto Bersih Tanpa Ikon/Tombol Video) --}}
            <div class="relative rounded-3xl overflow-hidden aspect-video bg-stone-100 border border-stone-200 shadow-md group">
                <img src="{{ asset('images/layanan/jasa-pasang-mobil.webp') }}" 
                     alt="Ruang Workshop Pemasangan Wrapping Presisi" 
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                     loading="lazy"
                     decoding="async"
                     width="640"
                     height="360">
            </div>

            <p class="text-stone-500 text-xs sm:text-sm font-light leading-relaxed">
                Ruang workshop berpendingin udara dengan filtrasi debu aktif untuk memastikan penempelan stiker sempurna tanpa partikel debu yang terjebak.
            </p>
        </div>

        {{-- Right Column: Numbered Accordion FAQ List (01 to 05) --}}
        <div class="lg:col-span-7 space-y-3 pt-2" data-aos="fade-left" data-aos-duration="1000">
            
            {{-- Item 01 --}}
            <div class="border-b border-stone-200 pb-4 transition-all">
                <button type="button" @click="openFaq = openFaq === 1 ? 0 : 1" class="w-full flex items-center justify-between text-left py-2 group">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-stone-400 font-bold">01</span>
                        <span class="text-base sm:text-lg font-medium text-stone-900 group-hover:text-[#ea580c] transition-colors">
                            Jaminan Garansi Resmi 1 Hingga 3 Tahun
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 shrink-0">
                        <i class="ph-bold text-sm" :class="openFaq === 1 ? 'ph-minus' : 'ph-plus'"></i>
                    </div>
                </button>
                <div x-show="openFaq === 1" x-collapse class="pt-3 pb-2 pl-9 pr-2">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-8 text-stone-600 text-xs sm:text-sm leading-relaxed font-light space-y-2">
                            <p>
                                Kami memberikan kartu garansi tertulis yang mencakup perbaikan jika terjadi pengelupasan (peeling), gelembung udara berlebih, atau kerutan pada bagian lipatan tepi tanpa biaya tambahan.
                            </p>
                            <a href="{{ route('layanan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-900 hover:text-[#ea580c] transition-colors underline underline-offset-4">
                                Pelajari Ketentuan Garansi
                            </a>
                        </div>
                        <div class="sm:col-span-4 rounded-2xl overflow-hidden aspect-video border border-stone-200">
                            <img src="{{ asset('images/hero_racing_car.webp') }}" 
                                 alt="Garansi Wapping" 
                                 class="w-full h-full object-cover"
                                 loading="lazy"
                                 decoding="async"
                                 width="320"
                                 height="180">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Item 02 --}}
            <div class="border-b border-stone-200 pb-4 transition-all">
                <button type="button" @click="openFaq = openFaq === 2 ? 0 : 2" class="w-full flex items-center justify-between text-left py-2 group">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-stone-400 font-bold">02</span>
                        <span class="text-base sm:text-lg font-medium text-stone-900 group-hover:text-[#ea580c] transition-colors">
                            Teknik Knifeless Tape: Apakah bodi cat aman dari goresan?
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 shrink-0">
                        <i class="ph-bold text-sm" :class="openFaq === 2 ? 'ph-minus' : 'ph-plus'"></i>
                    </div>
                </button>
                <div x-show="openFaq === 2" x-collapse class="pt-3 pb-2 pl-9 pr-2 text-stone-600 text-xs sm:text-sm leading-relaxed font-light">
                    Sangat aman 100%. Kami menggunakan pita pemotong berfilamen benang khusus (Knifeless Tape) dari 3M untuk memotong stiker langsung pada lekukan bodi mobil, sehingga mata pisau cutter sama sekali tidak pernah menyentuh cat orisinil kendaraan Anda.
                </div>
            </div>

            {{-- Item 03 --}}
            <div class="border-b border-stone-200 pb-4 transition-all">
                <button type="button" @click="openFaq = openFaq === 3 ? 0 : 3" class="w-full flex items-center justify-between text-left py-2 group">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-stone-400 font-bold">03</span>
                        <span class="text-base sm:text-lg font-medium text-stone-900 group-hover:text-[#ea580c] transition-colors">
                            Berapa lama estimasi waktu pengerjaan Full Body Wrap?
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 shrink-0">
                        <i class="ph-bold text-sm" :class="openFaq === 3 ? 'ph-minus' : 'ph-plus'"></i>
                    </div>
                </button>
                <div x-show="openFaq === 3" x-collapse class="pt-3 pb-2 pl-9 pr-2 text-stone-600 text-xs sm:text-sm leading-relaxed font-light">
                    Pengerjaan Full Body Wrap standar membutuhkan waktu 2 hingga 3 hari kerja. Waktu ini mencakup tahapan deep cleaning, decontaminating cat, pelepasan part eksterior ringan (handle, spion), instalasi stiker, hingga proses post-heating thermal curing.
                </div>
            </div>

            {{-- Item 04 --}}
            <div class="border-b border-stone-200 pb-4 transition-all">
                <button type="button" @click="openFaq = openFaq === 4 ? 0 : 4" class="w-full flex items-center justify-between text-left py-2 group">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-stone-400 font-bold">04</span>
                        <span class="text-base sm:text-lg font-medium text-stone-900 group-hover:text-[#ea580c] transition-colors">
                            Apakah stiker wrapping dapat merusak cat asli saat dilepas?
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 shrink-0">
                        <i class="ph-bold text-sm" :class="openFaq === 4 ? 'ph-minus' : 'ph-plus'"></i>
                    </div>
                </button>
                <div x-show="openFaq === 4" x-collapse class="pt-3 pb-2 pl-9 pr-2 text-stone-600 text-xs sm:text-sm leading-relaxed font-light">
                    Tidak sama sekali, asalkan cat mobil masih merupakan cat pabrikan orisinil. Bahan stiker premium seperti Oracal 970 atau TeckWrap memiliki lem akrilik ramah cat yang justru melindungi cat asli dari sinar UV matahari, goresan kerikil jalanan, dan kotoran burung.
                </div>
            </div>

            {{-- Item 05 --}}
            <div class="border-b border-stone-200 pb-4 transition-all">
                <button type="button" @click="openFaq = openFaq === 5 ? 0 : 5" class="w-full flex items-center justify-between text-left py-2 group">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-stone-400 font-bold">05</span>
                        <span class="text-base sm:text-lg font-medium text-stone-900 group-hover:text-[#ea580c] transition-colors">
                            Perawatan apa saja yang disarankan setelah kendaraan selesai di-wrap?
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 shrink-0">
                        <i class="ph-bold text-sm" :class="openFaq === 5 ? 'ph-minus' : 'ph-plus'"></i>
                    </div>
                </button>
                <div x-show="openFaq === 5" x-collapse class="pt-3 pb-2 pl-9 pr-2 text-stone-600 text-xs sm:text-sm leading-relaxed font-light">
                    Hindari mencuci kendaraan dengan alat steam jet bertekanan tinggi langsung pada jarak kurang dari 30cm pada bagian tepi sambungan stiker. Gunakan sampo mobil dengan pH netral dan lap microfiber halus untuk menjaga keawetan warna.
                </div>
            </div>

        </div>

    </div>
</section>

{{-- ============================================
    SECTION 4: "Reserve your perfect getaway"
    (Dark Luxury Container with Floating Light Reservation Pass)
============================================ --}}
<section class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="reserve-showcase">
    <div class="relative w-full rounded-[28px] sm:rounded-[44px] overflow-hidden bg-[#161513] border border-stone-800 p-5 sm:p-10 lg:p-14 shadow-2xl">
        
        {{-- Luxury background image with dark vignette --}}
        <img src="{{ asset('images/banner_exclusive_car.webp') }}" 
             alt="Luxury Wrap Background" 
             loading="lazy"
             decoding="async"
             width="1280"
             height="640"
             class="absolute inset-0 w-full h-full object-cover object-center opacity-30 mix-blend-luminosity filter blur-[1px]">
        
        <div class="absolute inset-0 bg-gradient-to-r from-[#161513] via-[#161513]/90 to-[#161513]/70"></div>
        
        {{-- Inner Grid: Left Floating Card + Right Copy & CTA --}}
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            {{-- Left Column (Order 2 on Mobile, Order 1 on Desktop): Floating Light Reservation Card (Ticket Look) --}}
            <div class="order-2 lg:order-1 lg:col-span-5 flex justify-center" data-aos="zoom-in" data-aos-duration="1000">
                <div class="w-full max-w-sm bg-white rounded-3xl p-5 sm:p-7 shadow-2xl border border-stone-200 text-stone-900 space-y-4 sm:space-y-5">
                    
                    {{-- Header of ticket --}}
                    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="ph-bold ph-check text-xs"></i>
                                Kuota Siap Dipesan
                            </span>
                            <h4 class="text-base sm:text-lg font-bold text-stone-900 tracking-tight leading-snug">
                                Jadwal Terkonfirmasi!
                            </h4>
                        </div>
                        <div class="w-12 h-12 rounded-2xl overflow-hidden border border-stone-200 shrink-0">
                            <img src="{{ asset('images/hero_racing_car.webp') }}" 
                                 alt="Mobil Thumbnail" 
                                 loading="lazy"
                                 decoding="async"
                                 width="48"
                                 height="48"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>

                    {{-- Ticket Details --}}
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between py-1 border-b border-stone-100">
                            <span class="text-stone-500 font-light">Tanggal Masuk:</span>
                            <span class="font-bold text-stone-900">Senin, 18 Okt 2026</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-stone-100">
                            <span class="text-stone-500 font-light">Estimasi Selesai:</span>
                            <span class="font-bold text-stone-900">Rabu, 20 Okt 2026</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-stone-100">
                            <span class="text-stone-500 font-light">Pilihan Layanan:</span>
                            <span class="font-bold text-stone-900">Full Body Satin Wrap</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-stone-100">
                            <span class="text-stone-500 font-light">Slot Workshop:</span>
                            <span class="font-bold text-amber-600">Bay 01 Steril</span>
                        </div>
                    </div>

                    {{-- Location Pin --}}
                    <div class="flex items-center gap-2 text-xs text-stone-600 bg-stone-50 p-2.5 rounded-xl border border-stone-100">
                        <i class="ph-fill ph-map-pin text-[#ea580c] text-sm shrink-0"></i>
                        <span class="truncate font-medium">{{ $profil->alamat_perusahaan ?? ($profil->alamat ?? \App\Helpers\StaticContent::COMPANY_ADDRESS) }}</span>
                    </div>

                    {{-- CTA on Card --}}
                    <a href="#booking-section" class="w-full py-3 rounded-full text-xs font-bold text-center bg-stone-900 text-white hover:bg-stone-800 transition-all block shadow-md active:scale-95">
                        Lihat Slot Kalender Tersedia
                    </a>

                </div>
            </div>

            {{-- Right Column (Order 1 on Mobile, Order 2 on Desktop): Headline, Subtitle, WhatsApp VIP & Action --}}
            <div class="order-1 lg:order-2 lg:col-span-7 space-y-5 sm:space-y-6 text-white" data-aos="fade-left" data-aos-duration="1000">
                
                <h3 class="text-2xl sm:text-4xl lg:text-5xl font-serif text-white tracking-tight leading-[1.18] font-normal">
                    Reservasi jadwal<br>
                    pengerjaan <span class="italic text-stone-300 font-light">eksklusif Anda</span>
                </h3>

                <p class="text-stone-300 text-xs sm:text-base leading-relaxed font-light max-w-lg">
                    Untuk memastikan tingkat ketelitian dan presisi sempurna, kami membatasi jumlah pengerjaan setiap minggunya. Segera amankan tanggal pengerjaan favorit Anda sekarang.
                </p>

                {{-- VIP Community Consultation Pill --}}
                <div class="pt-1">
                    <a href="{{ $profil->whatsapp_link ?? \App\Helpers\StaticContent::COMPANY_WHATSAPP }}" target="_blank" rel="noopener noreferrer" 
                       class="inline-flex items-center gap-2.5 sm:gap-3 px-3.5 sm:px-4 py-2 rounded-full bg-white/10 hover:bg-white/15 border border-white/15 text-xs text-stone-200 transition-all group">
                        <div class="flex -space-x-2">
                            <img class="inline-block h-6 w-6 rounded-full ring-1 ring-stone-900 object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Consultant">
                            <img class="inline-block h-8 w-8 rounded-full ring-1 ring-stone-900 object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Consultant">
                        </div>
                        <span class="font-medium text-[11px] sm:text-xs">Konsultasi Khusus via WhatsApp</span>
                        <i class="ph-bold ph-arrow-up-right text-amber-400 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                    </a>
                </div>

                {{-- Pagination Indicator & Action Pill Button --}}
                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 sm:gap-6 border-t border-white/10">
                    <div class="text-xs font-mono tracking-widest text-stone-400">
                        <span class="text-white font-bold">01</span> —— 03
                    </div>

                    <a href="#booking-section" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full text-xs sm:text-sm font-montserrat font-medium bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white hover:brightness-105 transition-all shadow-xl shadow-orange-500/25 hover:scale-105 active:scale-95 text-center">
                        <span>Pilih Jadwal Sekarang</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

{{-- ============================================
    HERO SECTION: Sesuai Inspirasi Layout Gambar
    (Cinematic Rounded Hero with Editorial Serif Typography - Bersih Tanpa Dial/Widget)
============================================ --}}
<section class="pt-24 sm:pt-28 pb-8 sm:pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="hero-section">
    <div class="relative w-full min-h-[480px] sm:min-h-[580px] lg:min-h-[640px] rounded-[28px] sm:rounded-[44px] overflow-hidden shadow-2xl border border-stone-800/80 flex flex-col justify-center p-5 sm:p-12 lg:p-16 group" data-aos="fade-up" data-aos-duration="1000">
        
        {{-- Background Image with cinematic luxury tint (LCP Candidate: High Priority & WebP) --}}
        <picture>
            <source srcset="{{ asset('images/banner_exclusive_car.webp') }}" type="image/webp">
            <img src="{{ asset('images/banner_exclusive_car.jpg') }}" 
                 alt="Luxury Automotive Wrap Studio" 
                 width="1280"
                 height="640"
                 fetchpriority="high"
                 decoding="async"
                 class="absolute inset-0 w-full h-full object-cover object-center transform scale-100 group-hover:scale-105 transition-transform duration-1000">
        </picture>
        
        {{-- Vignette & Dark Luxury Gradients (Dark on text side, clear on car side) --}}
        <div class="absolute inset-0 bg-gradient-to-r from-stone-950/95 via-stone-950/75 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/85 via-transparent to-stone-950/20"></div>

        {{-- Hero Main Content Grid --}}
        <div class="relative z-20 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center my-auto">
            
            {{-- Left Column: Large Editorial Serif Headline & Subtitle --}}
            <div class="lg:col-span-8 xl:col-span-7 space-y-4 sm:space-y-6">
                
                <p class="text-[11px] sm:text-xs font-semibold tracking-widest uppercase text-amber-400">
                    Studio Wrapping &amp; Proteksi Kendaraan
                </p>

                <h1 class="text-3xl sm:text-5xl md:text-6xl xl:text-7xl font-serif text-white tracking-tight leading-[1.12] drop-shadow-lg font-normal">
                    Presisi.<br>
                    Kemewahan. <span class="italic text-stone-300 font-light">Anda.</span>
                </h1>
                
                <p class="text-stone-300 text-xs sm:text-base md:text-lg max-w-xl font-light leading-relaxed drop-shadow">
                    Transformasi estetika dan proteksi cat berstandar internasional. Dirancang khusus untuk memancarkan aura eksklusif dan karakter sejati setiap kendaraan Anda.
                </p>

                <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="#booking-section" class="inline-flex items-center justify-center gap-3 px-6 sm:px-7 py-3.5 rounded-full text-xs sm:text-sm font-montserrat font-medium bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white hover:brightness-105 transition-all shadow-xl shadow-orange-500/25 hover:scale-105 active:scale-95 text-center">
                        <span>Pesan Jadwal Anda</span>
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-arrow-up-right text-xs text-white"></i>
                        </div>
                    </a>

                    <a href="{{ route('layanan') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full text-xs sm:text-sm font-montserrat font-medium text-stone-200 hover:text-white border border-white/20 hover:border-[#ea580c] hover:bg-white/10 transition-all text-center">
                        <span>Katalog Layanan</span>
                        <i class="ph-bold ph-caret-right text-xs text-stone-300"></i>
                    </a>
                </div>
            </div>

            {{-- Right Column: Dibiarkan bersih tanpa dial/widget agar keindahan foto mobil terlihat maksimal --}}
            <div class="hidden lg:block lg:col-span-4 xl:col-span-5"></div>

        </div>

    </div>
</section>

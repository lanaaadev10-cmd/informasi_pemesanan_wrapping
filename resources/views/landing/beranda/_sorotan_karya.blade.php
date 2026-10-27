@php
    $gallerySlides = (isset($galeris) && $galeris->isNotEmpty())
        ? $galeris->map(function($g) {
            $foto = $g->foto 
                ? \App\Helpers\StaticContent::fotoUrl($g->foto)
                : asset('images/banner_exclusive_car.webp');
            return [
                'tag'   => $g->badge_text ?: (ucfirst($g->kategori) ?: 'Karya Unggulan'),
                'car'   => $g->sub_judul ?: $g->judul,
                'title' => $g->judul,
                'desc'  => $g->deskripsi ?: 'Pengerjaan presisi knifeless tape dengan garansi resmi.',
                'image' => $foto,
            ];
        })->values()->toArray()
        : [
            [
                'tag'   => 'Full Body Wrap',
                'car'   => 'McLaren Senna GTR',
                'title' => 'Satin Black & Orange Accent',
                'desc'  => 'Pengerjaan presisi knifeless tape dengan garansi resmi 3 tahun.',
                'image' => asset('images/banner_exclusive_car.webp')
            ],
            [
                'tag'   => 'PPF Ultimate',
                'car'   => 'Porsche 911 GT3 RS',
                'title' => 'Self-Healing Paint Film',
                'desc'  => 'Proteksi cat orisinil dari lontaran kerikil dan sinar matahari ekstrem.',
                'image' => asset('images/hero_racing_car.webp')
            ],
            [
                'tag'   => 'Custom Finish',
                'car'   => 'Lamborghini Revuelto',
                'title' => 'Midnight Chameleon Tint',
                'desc'  => 'Efek pantulan gradasi warna yang berubah memukau di bawah cahaya.',
                'image' => asset('images/hero_car.webp')
            ],
            [
                'tag'   => 'EV Luxury',
                'car'   => 'Tesla Model S Plaid',
                'title' => 'Satin Battleship Grey',
                'desc'  => 'Sentuhan modern minimalis bebas silau untuk kendaraan listrik premium.',
                'image' => asset('images/tesla_model_s.webp')
            ]
        ];
@endphp

<section class="py-14 sm:py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="galeri-showcase"
         x-data="{
             active: 0,
             progress: 0,
             progressTimer: null,
             slides: {{ Js::from($gallerySlides) }},
             init() {
                 this.startAutoPlay();
             },
             startAutoPlay() {
                 this.stopAutoPlay();
                 this.progress = 0;
                 const intervalMs = 50;
                 const totalMs = 5000;
                 const step = (intervalMs / totalMs) * 100;

                 this.progressTimer = setInterval(() => {
                     this.progress += step;
                     if (this.progress >= 100) {
                         this.next();
                     }
                 }, intervalMs);
             },
             stopAutoPlay() {
                 if (this.progressTimer) {
                     clearInterval(this.progressTimer);
                     this.progressTimer = null;
                 }
             },
             select(index) {
                 this.active = index;
                 this.startAutoPlay();
             },
             next() {
                 this.active = (this.active + 1) % this.slides.length;
                 this.progress = 0;
             },
             prev() {
                 this.active = (this.active - 1 + this.slides.length) % this.slides.length;
                 this.progress = 0;
             }
         }"
         @mouseenter="stopAutoPlay()"
         @mouseleave="startAutoPlay()">
         
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
        
        {{-- Left Column: Headline, Description & Navigation Controls --}}
        <div class="lg:col-span-4 space-y-6" data-aos="fade-right" data-aos-duration="1000">
            <div class="space-y-3">
                <span class="text-xs font-serif italic text-stone-500 tracking-wider block">
                    portofolio unggulan
                </span>

                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif text-stone-900 tracking-tight leading-[1.15] font-normal">
                    Temukan pesona<br>
                    karya transformasi<br>
                    <span class="italic text-stone-500 font-light">eksterior kami.</span>
                </h2>
            </div>

            <p class="text-stone-600 text-sm sm:text-base leading-relaxed font-light max-w-md">
                Klik pada kartu untuk melihat detail karya. Dari keanggunan satin matte yang berwibawa hingga kilau glossy layaknya kristal, setiap mobil dikerjakan dengan presisi tinggi.
            </p>

            {{-- Circular Navigation Controls + 5-Second Timer Bar (Desktop & Tablet only, Mobile has its dedicated bar below card) --}}
            <div class="hidden sm:block space-y-3 pt-2">
                <div class="flex items-center gap-3">
                    <button type="button" 
                            @click="prev()"
                            class="w-12 h-12 rounded-full border border-stone-300 hover:border-stone-900 hover:bg-stone-900 hover:text-white transition-all flex items-center justify-center text-stone-700 active:scale-95 shadow-sm"
                            aria-label="Previous Slide">
                        <i class="ph-bold ph-arrow-left text-base"></i>
                    </button>
                    <button type="button" 
                            @click="next()"
                            class="w-12 h-12 rounded-full border border-stone-300 hover:border-stone-900 hover:bg-stone-900 hover:text-white transition-all flex items-center justify-center text-stone-700 active:scale-95 shadow-sm"
                            aria-label="Next Slide">
                        <i class="ph-bold ph-arrow-right text-base"></i>
                    </button>
                    
                    {{-- Counter & Auto-slide Timer Indicator --}}
                    <div class="pl-2 flex items-center gap-2 text-xs text-stone-500 font-mono">
                        <span class="font-bold text-stone-900" x-text="String(active + 1).padStart(2, '0')"></span>
                        <span>/</span>
                        <span x-text="String(slides.length).padStart(2, '0')"></span>
                        <span class="text-[11px] text-stone-400 font-sans ml-1">(Otomatis 5 dtk)</span>
                    </div>
                </div>

                {{-- Visual 5-Second Progress Indicator --}}
                <div class="flex items-center gap-2 pt-1 max-w-xs">
                    <template x-for="(slide, idx) in slides" :key="idx">
                        <button type="button" 
                                @click="select(idx)" 
                                class="h-1.5 rounded-full transition-all duration-300 overflow-hidden bg-stone-200"
                                :class="active === idx ? 'w-12' : 'w-4 hover:bg-stone-300'"
                                :title="'Slide ' + (idx + 1)">
                            <div class="h-full bg-[#ea580c] transition-all duration-75"
                                 :style="active === idx ? 'width: ' + progress + '%;' : 'width: 0%;'"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Right Column: Interactive Expanding Horizontal Accordion Carousel (Desktop/Tablet) & Cinematic Card Slider (Mobile) --}}
        <div class="lg:col-span-8 overflow-hidden" data-aos="fade-left" data-aos-duration="1000">
            
            {{-- MOBILE VIEW (< 640px): Dedicated Single Active Card with Smooth Transitions --}}
            <div class="flex flex-col sm:hidden space-y-4 select-none">
                <div class="relative rounded-3xl overflow-hidden shadow-lg border border-stone-200 bg-stone-950 aspect-[4/3] w-full">
                    <template x-for="(slide, idx) in slides" :key="'mob-' + idx">
                        <div x-show="active === idx"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute inset-0">
                            
                            {{-- Image --}}
                            <img :src="slide.image" 
                                 :alt="slide.title"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-full h-full object-cover">
                            
                            {{-- Dark Gradient --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/40 to-transparent"></div>

                            {{-- Content Info --}}
                            <div class="absolute bottom-0 inset-x-0 p-5 text-white space-y-1.5 z-10">
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400"
                                          x-text="slide.tag"></span>
                                    <span class="text-xs text-stone-400">/</span>
                                    <span class="text-[11px] font-mono text-stone-300 truncate" x-text="slide.car"></span>
                                </div>

                                <h3 class="text-lg font-bold leading-tight drop-shadow-md text-white font-serif"
                                    x-text="slide.title"></h3>

                                <p class="text-stone-300 text-xs font-light leading-relaxed line-clamp-2"
                                   x-text="slide.desc"></p>

                                <div class="pt-1">
                                    <a href="{{ route('layanan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-white transition-colors">
                                        <span>Konsultasi Paket Serupa</span>
                                        <i class="ph-bold ph-arrow-up-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Mobile Pagination Dots & Arrow Controls --}}
                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center gap-1.5">
                        <template x-for="(slide, idx) in slides" :key="'dot-' + idx">
                            <button type="button" 
                                    @click="select(idx)" 
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="active === idx ? 'w-8 bg-[#ea580c]' : 'w-2 bg-stone-300'"
                                    :title="'Slide ' + (idx + 1)"></button>
                        </template>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="prev()"
                                class="w-9 h-9 rounded-full border border-stone-300 bg-white hover:bg-stone-900 hover:text-white transition-all flex items-center justify-center text-stone-700 active:scale-90 shadow-sm"
                                aria-label="Previous Slide">
                            <i class="ph-bold ph-arrow-left text-xs"></i>
                        </button>
                        <button type="button" 
                                @click="next()"
                                class="w-9 h-9 rounded-full border border-stone-300 bg-white hover:bg-stone-900 hover:text-white transition-all flex items-center justify-center text-stone-700 active:scale-90 shadow-sm"
                                aria-label="Next Slide">
                            <i class="ph-bold ph-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- TABLET & DESKTOP VIEW (>= 640px): Expanding Horizontal Accordion Carousel --}}
            <div class="hidden sm:flex gap-3 sm:gap-4 h-[450px] sm:h-[490px] w-full items-stretch select-none">
                
                <template x-for="(slide, idx) in slides" :key="'desk-' + idx">
                    <div @click="select(idx)"
                         class="relative rounded-3xl overflow-hidden shadow-md border border-stone-200 bg-stone-950 transition-all cursor-pointer group"
                         :style="'flex: ' + (active === idx ? '3.5 1 0%' : '1 1 0%') + '; transition: flex 0.65s cubic-bezier(0.25, 1, 0.5, 1), transform 0.3s ease;'">
                        
                        {{-- Background Image --}}
                        <img :src="slide.image" 
                             :alt="slide.title"
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover transform scale-100 transition-transform duration-700"
                             :class="active === idx ? 'group-hover:scale-105' : 'filter brightness-75 group-hover:brightness-90'">
                        
                        {{-- Dark Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/30 to-transparent transition-opacity duration-500"
                             :class="active === idx ? 'opacity-100' : 'opacity-70 group-hover:opacity-40'"></div>

                        {{-- Active State: Full Details Visible (Membesar) --}}
                        <div x-show="active === idx" 
                             x-transition:enter="transition ease-out duration-500 delay-150"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute bottom-0 inset-x-0 p-6 sm:p-7 text-white space-y-2 z-10">
                            
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-400"
                                      x-text="slide.tag"></span>
                                <span class="text-xs text-stone-300">/</span>
                                <span class="text-xs font-mono text-stone-300" x-text="slide.car"></span>
                            </div>

                            <h3 class="text-xl sm:text-2xl font-bold leading-tight drop-shadow-md text-white font-serif"
                                x-text="slide.title"></h3>

                            <p class="text-stone-300 text-xs sm:text-sm font-light leading-relaxed max-w-md line-clamp-2"
                               x-text="slide.desc"></p>

                            <div class="pt-2">
                                <a href="{{ route('layanan') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-white transition-colors group/link">
                                    <span>Konsultasi Paket Serupa</span>
                                    <i class="ph-bold ph-arrow-up-right text-xs group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Inactive State: Vertical Strip Info (Mengecil) --}}
                        <div x-show="active !== idx" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="absolute inset-0 flex flex-col justify-between p-4 z-10 pointer-events-none">
                            
                            {{-- Number badge at top --}}
                            <div class="w-7 h-7 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-[11px] font-mono font-bold mx-auto border border-white/20"
                                 x-text="String(idx + 1).padStart(2, '0')"></div>

                            {{-- Vertical Text Label at Center --}}
                            <div class="flex-1 flex items-center justify-center">
                                <span class="text-xs font-bold uppercase tracking-widest text-stone-200 whitespace-nowrap transform -rotate-90 drop-shadow select-none group-hover:text-amber-400 transition-colors"
                                      x-text="slide.tag"></span>
                            </div>

                            {{-- Hint icon at bottom --}}
                            <div class="text-center text-stone-400 text-xs group-hover:text-white transition-colors">
                                <i class="ph-bold ph-arrows-out-simple"></i>
                            </div>
                        </div>

                    </div>
                </template>

            </div>
        </div>

    </div>
</section>

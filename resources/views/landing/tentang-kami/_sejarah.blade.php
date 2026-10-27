{{-- ============================================
    BAGIAN: Sejarah Kami
    Deskripsi: Cerita sejarah pendirian dan milestones 10 tahun pengerjaan
============================================ --}}
@if($showHistory)
    <div class="z-10 relative" data-aos="fade-up">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            <!-- Left: Clean Description -->
            <div class="space-y-4 lg:col-span-5">
                <span class="text-xs sm:text-sm font-black uppercase tracking-widest text-[#ea580c] block">
                    Sejarah &amp; Perjalanan
                </span>
                
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight font-montserrat tracking-tight">
                    {{ \App\Helpers\StaticContent::SEJARAH_TITLE }}
                </h2>
                
                <div class="text-slate-600 space-y-4 leading-relaxed text-sm sm:text-base font-normal">
                    <p>
                        Sejak didirikan pada tahun 2014, Dantie Stiker telah menjadi pioneer dalam teknologi stiker dan proteksi kendaraan premium. Dari workshop berstandar tinggi, kami melayani ribuan pelanggan dengan komitmen yang tak pernah pudar terhadap presisi detail dan kepuasan pelanggan.
                    </p>
                    <p>
                        Kini, kami bangga menjadi workshop wrapping terpilih yang dipercaya untuk memproteksi dan mempercantik kualitas kendaraan dari berbagai merek. Setiap pengerjaan adalah karya seni yang kami selesaikan dengan ketelitian tertinggi.
                    </p>
                </div>
            </div>

            <!-- Right: Clean Bento Grid -->
            <div class="grid grid-cols-12 gap-3 sm:gap-4 lg:col-span-7">
                <div class="col-span-7 flex flex-col gap-3 sm:gap-4">
                    <!-- Shop photo -->
                    <div class="rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 h-36 sm:h-56 relative group bg-slate-100">
                        <img src="{{ asset('images/banner_exclusive_car.webp') }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             alt="Workshop Studio Dantie Stiker"
                             loading="lazy"
                             decoding="async"
                             width="640"
                             height="360">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                    </div>

                    <!-- Stats card -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-8 flex flex-col justify-center h-32 sm:h-40 relative overflow-hidden shadow-sm">
                        <span class="text-3xl sm:text-5xl font-black font-montserrat text-[#ea580c] tracking-tight">500+</span>
                        <span class="text-[10px] sm:text-xs uppercase font-bold tracking-wider text-slate-400 mt-1 sm:mt-2">Project Selesai</span>
                    </div>
                </div>
                
                <!-- 10th Anniversary vertical card -->
                <div class="col-span-5 bg-slate-900 border border-slate-800 rounded-2xl sm:rounded-3xl overflow-hidden relative group flex flex-col justify-between h-[284px] sm:h-[416px] shadow-sm">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent z-10"></div>
                    <img src="{{ asset('images/hero_racing_car.webp') }}" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90" 
                         alt="Supercar"
                         loading="lazy"
                         decoding="async"
                         width="400"
                         height="416">
                    <div class="relative z-20 p-4 sm:p-6 flex flex-col justify-between h-full">
                        <div class="bg-slate-900/80 backdrop-blur-md border border-white/10 rounded-full py-0.5 sm:py-1 px-2.5 sm:px-3 self-start text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-[#ea580c]">
                            Anniversary
                        </div>
                        <div>
                            <span class="text-3xl sm:text-4xl font-black font-montserrat text-white tracking-tight block">10th</span>
                            <span class="text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-slate-300 mt-0.5 sm:mt-1 block">Dedikasi &amp; Inovasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

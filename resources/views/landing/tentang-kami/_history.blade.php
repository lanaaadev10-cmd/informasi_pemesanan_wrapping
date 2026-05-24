{{-- ============================================
    BAGIAN: Sejarah Kami
    Deskripsi: Cerita sejarah pendirian dan milestones 10 tahun pengerjaan
============================================ --}}
@if($showHistory)
    <div class="z-10 relative" data-aos="fade-up">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            <!-- Left: Description -->
            <div class="space-y-6 lg:col-span-5">
                <span class="text-xs uppercase font-extrabold tracking-widest text-[#f2994a]">Sejarah Kami</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Satu Dekade Dedikasi pada Perfeksi.
                </h2>
                <div class="text-gray-400 space-y-4 leading-relaxed text-sm sm:text-base">
                    @if($profil->sejarah)
                        {!! $profil->sejarah !!}
                    @else
                        <p>
                            Sejak didirikan pada tahun 2014, Wrapping Mobil telah menjadi pioneer dalam teknologi stiker kendaraan premium. Dari garasi kecil, kami kini melayani ribuan pelanggan dengan komitmen yang tak pernah pudar terhadap detail dan kepuasan pelanggan.
                        </p>
                        <p>
                            Kini, kami bangga menjadi workshop wrapping terpilih yang dipercaya untuk memproteksi dan mempercantik kualitas kendaraan mewah dari berbagai merek. Setiap proyek adalah karya seni yang kami kerjakan dengan ketelitian tertinggi.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Right: Asymmetrical Grid from mockup -->
            <div class="grid grid-cols-12 gap-4 lg:col-span-7">
                <div class="col-span-7 flex flex-col gap-4">
                    <!-- Shop photo -->
                    <div class="rounded-[24px] overflow-hidden shadow-2xl border border-white/5 h-48 sm:h-56 relative group">
                        <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=800&auto=format&fit=crop" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                             alt="Detailing Shop">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/80 via-transparent to-transparent"></div>
                    </div>
                    <!-- Stats card -->
                    <div class="bg-[#121212]/90 border border-white/5 rounded-[24px] p-6 sm:p-8 flex flex-col justify-center h-36 sm:h-40 relative overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-300">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-500 bg-[#f2994a]/5"></div>
                        <span class="text-4xl sm:text-5xl font-extrabold text-[#f2994a] tracking-tight">500+</span>
                        <span class="text-xs uppercase font-bold tracking-wider text-gray-400 mt-2">Project Selesai</span>
                    </div>
                </div>
                
                <!-- 10th Anniversary vertical card -->
                <div class="col-span-5 bg-[#121212]/90 border border-white/5 rounded-[24px] overflow-hidden relative group hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between h-[360px] sm:h-[416px]">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         alt="Supercar">
                    <div class="relative z-20 p-6 flex flex-col justify-between h-full">
                        <div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-xl py-2 px-3 self-start text-[9px] font-extrabold uppercase tracking-widest text-[#f2994a]">
                            Anniversary
                        </div>
                        <div>
                            <span class="text-4xl font-extrabold text-[#f2994a] tracking-tight block">10th</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-300 mt-1 block">Dedikasi & Inovasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

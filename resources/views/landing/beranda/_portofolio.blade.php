{{-- ============================================
    BAGIAN: Portofolio Section
    Deskripsi: Showcase galeri hasil wrapping
============================================ --}}
<section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="mahakarya">
    <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-[#f2994a]/5 rounded-full blur-[110px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block">{{ $profil->home_section_portofolio_badge ?? 'Showcase Portofolio' }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">{{ $profil->home_section_portofolio_title ?? 'Mahakarya Kami' }}</h2>
                <p class="text-gray-500 text-sm max-w-lg">{{ $profil->home_section_portofolio_desc ?? 'Berikut adalah hasil pengerjaan car wrapping premium dari tim ahli profesional kami.' }}</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group">
                    {{ $profil->cta_lihat_semua ?? 'Lihat Semua' }} <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($galeris as $galeri)
                @php
                    $fotoUrl = $galeri->foto ? asset('storage/' . $galeri->foto) : 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=800&auto=format&fit=crop';
                @endphp
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" @if($loop->index > 0) data-aos-delay="{{ $loop->index * 100 }}" @endif>
                    <div class="relative h-64 overflow-hidden">
                        @if($galeri->badge_text || $galeri->is_featured)
                            <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">
                                {{ $galeri->badge_text ?? 'Unggulan' }}
                            </span>
                        @endif
                        <img src="{{ $fotoUrl }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="{{ $galeri->judul }}">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">{{ $galeri->judul }}</h3>
                        <p class="text-gray-400 text-sm">{{ $galeri->sub_judul ?? $galeri->deskripsi }}</p>
                    </div>
                </div>
            @empty
                <!-- Fallback: Tesla Model S -->
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up">
                    <div class="relative h-64 overflow-hidden">
                        <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">Varian Favorit</span>
                        <img src="{{ asset('images/tesla_model_s.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Tesla Model S Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Tesla Model S</h3>
                        <p class="text-gray-400 text-sm">Luxury Matte Grey / Blue</p>
                    </div>
                </div>

                <!-- Fallback: Range Rover -->
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-64 overflow-hidden">
                        <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">Sangat Direkomendasikan</span>
                        <img src="{{ asset('images/range_rover.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Range Rover Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Range Rover Sport</h3>
                        <p class="text-gray-400 text-sm">Satin Liquid Silver Wrap</p>
                    </div>
                </div>

                <!-- Fallback: Ferrari F8 -->
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('images/ferrari_f8.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Ferrari F8 Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Ferrari F8</h3>
                        <p class="text-gray-400 text-sm">Satin Metallic Gold Yellow</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

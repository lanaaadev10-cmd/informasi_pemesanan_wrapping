{{-- ============================================
    BAGIAN: Portofolio Section
    Deskripsi: Showcase galeri hasil wrapping
============================================ --}}
<section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="mahakarya">
    <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-[#f2994a]/5 rounded-full blur-[110px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block">Showcase Portofolio</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Mahakarya Kami</h2>
                <p class="text-gray-500 text-sm max-w-lg">Berikut adalah hasil pengerjaan car wrapping premium dari tim ahli profesional kami.</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group">
                    Lihat Semua <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
                $portfolioItems = $portfolio ?? config('site.landing.portfolio', []);
            @endphp
            @forelse($portfolioItems as $item)
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up">
                    <div class="relative h-64 overflow-hidden">
                        <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">
                            {{ $item['tag'] ?? 'Unggulan' }}
                        </span>
                        <img src="{{ asset($item['image'] ?? 'images/landing/hero_car.png') }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="{{ $item['title'] ?? 'Profil Pekerjaan' }}">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">{{ $item['title'] ?? 'Detail Project' }}</h3>
                        <p class="text-gray-400 text-sm">{{ $item['subtitle'] ?? ($item['description'] ?? '') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-1 text-center text-white/70 py-20">
                    <p class="text-lg font-semibold">Portofolio belum tersedia.</p>
                    <p class="text-gray-500 text-sm mt-2">Silakan tambahkan item portofolio pada konfigurasi situs.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

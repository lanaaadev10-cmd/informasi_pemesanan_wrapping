{{-- ============================================
    BAGIAN: Portofolio Section
    Deskripsi: Showcase galeri hasil wrapping
============================================ --}}
<section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="mahakarya">
    <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-[#f2994a]/5 rounded-full blur-[110px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block">{{ \App\Helpers\StaticContent::PORTOFOLIO_BADGE }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">{{ \App\Helpers\StaticContent::PORTOFOLIO_TITLE }}</h2>
                <p class="text-gray-500 text-sm max-w-lg">{{ \App\Helpers\StaticContent::PORTOFOLIO_DESC }}</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group">
                    {{ \App\Helpers\StaticContent::CTA_LIHAT_SEMUA }} <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($galeris as $item)
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="relative h-64 overflow-hidden">
                        @if($item->badge_text && $item->badge_text !== 'Featured Project')
                            <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">{{ $item->badge_text }}</span>
                        @endif
                        <img src="{{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="{{ $item->judul }}">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">{{ $item->judul }}</h3>
                        <p class="text-gray-400 text-sm">{{ $item->deskripsi }}</p>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40">
                    <p class="text-gray-500 text-sm">Belum ada portofolio untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

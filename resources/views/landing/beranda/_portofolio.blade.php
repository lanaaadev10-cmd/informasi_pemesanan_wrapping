{{-- ============================================
    BAGIAN: Portofolio Section
    Deskripsi: Showcase galeri hasil wrapping
============================================ --}}
<section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="mahakarya">
    <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-[#f2994a]/5 rounded-full blur-[110px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16" data-aos="fade-up">
            <div class="space-y-3">
                <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block">{{ $profil->home_section_portofolio_badge ?: 'Showcase Portofolio' }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">{{ $profil->home_section_portofolio_title ?: 'Mahakarya Kami' }}</h2>
                <p class="text-gray-500 text-sm max-w-lg">{{ $profil->home_section_portofolio_desc ?: 'Berikut adalah hasil pengerjaan car wrapping premium dari tim ahli profesional kami.' }}</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group">
                    {{ $profil->home_portofolio_lihat_semua ?: 'Lihat Semua' }} <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="portofolio-grid">
            @forelse($galeris as $galeri)
                @php
                    $fotoUrl = $galeri->foto ? asset('storage/' . $galeri->foto) : 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=800&auto=format&fit=crop';
                @endphp
                <div onclick="openPortoLb({{ $loop->index }})" class="block bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md cursor-pointer" data-aos="fade-up" @if($loop->index > 0) data-aos-delay="{{ $loop->index * 100 }}" @endif>
                    <div class="relative h-64 overflow-hidden">
                        @if($galeri->badge_text || $galeri->is_featured)
                            <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">
                                {{ $galeri->badge_text ?? 'Unggulan' }}
                            </span>
                        @endif
                        <img src="{{ $fotoUrl }}" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="{{ $galeri->judul }}">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-50 group-hover:scale-100">
                                <i class="ph-bold ph-magnifying-glass-plus text-xl"></i>
                            </div>
                        </div>
                        @if($galeri->kategori)
                        <div class="absolute bottom-4 left-4 z-20">
                            <span class="text-[9px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full bg-[#f2994a]/20 text-[#f2994a] border border-[#f2994a]/30 backdrop-blur-sm">
                                {{ $galeri->kategori }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">{{ $galeri->judul }}</h3>
                        <p class="text-gray-400 text-sm">{{ \Illuminate\Support\Str::limit($galeri->deskripsi ?: $galeri->sub_judul, 120) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-gray-500">
                    <i class="ph-bold ph-image-square text-4xl block mb-4"></i>
                    <p class="text-sm">Belum ada portofolio tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<div id="porto-lightbox-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm" onclick="closePortoLb(event)">
    <button onclick="closePortoLb()" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-x text-xl"></i>
    </button>
    <button onclick="navigatePortoLb(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-left text-xl"></i>
    </button>
    <button onclick="navigatePortoLb(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-right text-xl"></i>
    </button>
    <div class="relative max-w-5xl max-h-[90vh] mx-4 flex flex-col items-center" onclick="event.stopPropagation()">
        <img id="porto-lightbox-image" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl" src="" alt="">
        <div id="porto-lightbox-caption" class="mt-4 text-center">
            <h3 id="porto-lightbox-title" class="text-white text-lg font-bold"></h3>
            <p id="porto-lightbox-desc" class="text-gray-400 text-sm mt-1"></p>
        </div>
    </div>
</div>

<script>
    const portoItems = [
        @foreach($galeris as $galeri)
        {
            src: '{{ $galeri->foto ? asset("storage/" . $galeri->foto) : "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=800&auto=format&fit=crop" }}',
            title: '{{ $galeri->judul }}',
            desc: '{{ $galeri->sub_judul ?? $galeri->deskripsi }}'
        },
        @endforeach
    ];

    let portoIndex = 0;

    function openPortoLb(index) {
        if (!portoItems.length) return;
        portoIndex = index;
        updatePortoLb();
        document.getElementById('porto-lightbox-overlay').classList.remove('hidden');
        document.getElementById('porto-lightbox-overlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePortoLb(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('porto-lightbox-overlay').classList.add('hidden');
        document.getElementById('porto-lightbox-overlay').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function navigatePortoLb(dir) {
        portoIndex += dir;
        if (portoIndex < 0) portoIndex = portoItems.length - 1;
        if (portoIndex >= portoItems.length) portoIndex = 0;
        updatePortoLb();
    }

    function updatePortoLb() {
        const item = portoItems[portoIndex];
        document.getElementById('porto-lightbox-image').src = item.src;
        document.getElementById('porto-lightbox-title').textContent = item.title;
        document.getElementById('porto-lightbox-desc').textContent = item.desc;
    }

    document.addEventListener('keydown', function(e) {
        const overlay = document.getElementById('porto-lightbox-overlay');
        if (!overlay || overlay.classList.contains('hidden')) return;
        if (e.key === 'Escape') closePortoLb();
        if (e.key === 'ArrowLeft') navigatePortoLb(-1);
        if (e.key === 'ArrowRight') navigatePortoLb(1);
    });
</script>

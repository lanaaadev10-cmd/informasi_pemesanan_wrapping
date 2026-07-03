<section class="mb-16">
    @if($galeri && $galeri->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8" id="galeri-grid">
            @foreach($galeri as $userItem)
                <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square cursor-pointer"
                     data-category="all {{ strtolower($userItem->kategori ?? '') }}" data-aos="fade-up" data-aos-duration="1000"
                     onclick="openLightbox({{ $loop->index }})">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                    <img src="{{ asset('storage/' . $userItem->foto) }}"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                         alt="{{ $userItem->judul }}">
                    <div class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white">
                            <i class="ph-bold ph-magnifying-glass-plus text-lg"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                        <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">
                            @php
                                $catLabel = $userItem->kategori;
                                foreach (($galeriFilterCategories ?? []) as $cat) {
                                    if (($cat['slug'] ?? '') === $userItem->kategori) {
                                        $catLabel = $cat['label'];
                                        break;
                                    }
                                }
                            @endphp
                            {{ $catLabel ?? 'Portfolio' }}
                        </span>
                        <h3 class="text-white text-lg font-bold group-hover:text-[#f2994a] transition-colors leading-tight">{{ $userItem->judul }}</h3>
                        <p class="text-gray-400 text-xs font-light leading-relaxed line-clamp-2">{{ $userItem->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/5 mb-6 border border-white/10">
                <svg class="w-10 h-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-white/70 text-lg font-semibold mb-2">Belum Ada Galeri</h3>
            <p class="text-gray-500 text-sm max-w-md mx-auto">{{ $profil->galeri_empty_state_title ?? 'Admin belum menambahkan karya galeri.' }}</p>
        </div>
    @endif
</section>

@if($galeri && $galeri->count() > 0)
<div id="lightbox-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm" onclick="closeLightbox(event)">
    <button onclick="closeLightbox()" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-x text-xl"></i>
    </button>

    <button onclick="navigateLightbox(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-left text-xl"></i>
    </button>
    <button onclick="navigateLightbox(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-right text-xl"></i>
    </button>

    <div class="relative max-w-5xl max-h-[90vh] mx-4 flex flex-col items-center" onclick="event.stopPropagation()">
        <img id="lightbox-image" class="max-w-full max-h-[80vh] object-contain rounded-2xl shadow-2xl" src="" alt="">
        <div id="lightbox-caption" class="mt-4 text-center">
            <h3 id="lightbox-title" class="text-white text-lg font-bold"></h3>
            <p id="lightbox-desc" class="text-gray-400 text-sm mt-1"></p>
        </div>
    </div>
</div>

<script>
    const galeriItems = [
        @foreach($galeri as $item)
        {
            src: '{{ asset("storage/" . $item->foto) }}',
            title: '{{ $item->judul }}',
            desc: '{{ $item->deskripsi }}'
        },
        @endforeach
    ];

    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        updateLightbox();
        document.getElementById('lightbox-overlay').classList.remove('hidden');
        document.getElementById('lightbox-overlay').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById('lightbox-overlay').classList.add('hidden');
        document.getElementById('lightbox-overlay').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function navigateLightbox(direction) {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = galeriItems.length - 1;
        if (currentIndex >= galeriItems.length) currentIndex = 0;
        updateLightbox();
    }

    function updateLightbox() {
        const item = galeriItems[currentIndex];
        document.getElementById('lightbox-image').src = item.src;
        document.getElementById('lightbox-title').textContent = item.title;
        document.getElementById('lightbox-desc').textContent = item.desc;
    }

    document.addEventListener('keydown', function(e) {
        const overlay = document.getElementById('lightbox-overlay');
        if (overlay.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });
</script>
@endif

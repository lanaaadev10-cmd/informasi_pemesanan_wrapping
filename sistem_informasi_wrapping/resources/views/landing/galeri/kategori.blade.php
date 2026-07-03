@extends('layouts.tampilan_utama')

@section('title', $kategoriLabel . ' - ' . ($profil->nama_perusahaan ?? 'Dantie Wrapping'))

@php
    $accentColor = $profil->accent_color ?? '#f2994a';
    $namaPerusahaan = $profil->nama_perusahaan ?? 'Dantie Wrapping';
@endphp

@section('content')
<div class="min-h-screen bg-[#0a0a0a]">
    <div class="absolute inset-x-0 top-0 h-[400px] pointer-events-none z-0"
         style="background:radial-gradient(ellipse 60% 35% at 50% 0, {{$accentColor}}15, transparent 70%);">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-24">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-gray-500 mb-8" data-aos="fade-down">
            <a href="{{ route('home') }}" class="hover:text-[#f2994a] transition-colors">Beranda</a>
            <i class="ph-bold ph-caret-right text-[10px]"></i>
            <a href="{{ route('galeri.user') }}" class="hover:text-[#f2994a] transition-colors">Galeri</a>
            <i class="ph-bold ph-caret-right text-[10px]"></i>
            <span class="text-white font-medium">{{ ucfirst($kategoriSlug) }}</span>
        </nav>

        {{-- Header --}}
        <div class="mb-12" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-4"
                 style="background-color: color-mix(in srgb, {{$accentColor}} 10%, transparent); border: 1px solid color-mix(in srgb, {{$accentColor}} 20%, transparent);">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: {{$accentColor}};"></span>
                <span class="text-xs font-bold tracking-wider uppercase font-mono" style="color: {{$accentColor}};">{{ $kategoriLabel }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-3">{{ $kategoriLabel }}</h1>
            <p class="text-gray-400 text-sm sm:text-base max-w-2xl">Jelajahi hasil wrapping {{ $kategoriSlug }} terbaik dari tim profesional kami.</p>
            <p class="text-[#f2994a] text-sm font-bold mt-2" id="photo-count">{{ $allCount }} Foto</p>
        </div>

        {{-- Filter Jenis --}}
        @if($jenisList && $jenisList->count() > 0)
        <div class="mb-10" data-aos="fade-up" data-aos-delay="100">
            <div class="flex flex-wrap gap-3" id="filter-buttons">
                <button onclick="filterJenis('all')" class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] active:scale-95" style="background-color:{{$accentColor}};color:#000;border:1px solid {{$accentColor}};" data-jenis="all">
                    Semua
                </button>
                @foreach($jenisList as $jenis)
                    <button onclick="filterJenis('{{ $jenis }}')" class="px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] active:scale-95 bg-white/5 text-gray-400 border border-white/10 hover:border-[{{$accentColor}}]/30 hover:text-white" data-jenis="{{ $jenis }}">
                        {{ str_replace('_', ' ', ucfirst($jenis)) }}
                    </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid Galeri --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="galeri-grid">
            @forelse($galeris as $item)
            <div class="group rounded-2xl overflow-hidden border border-white/5 bg-[#121212] hover:border-[{{$accentColor}}]/30 transition-all duration-[400ms] ease-[cubic-bezier(0.16,1,0.3,1)] cursor-pointer"
                 data-jenis="{{ $item->jenis ?? '' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 50 }}"
                 onclick="openLightbox({{ $loop->index }})">
                <div class="relative aspect-[4/3] overflow-hidden bg-[#1c1c1c]">
                    <img data-src="{{ asset('storage/' . $item->foto) }}"
                         src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%231c1c1c' width='400' height='300'/%3E%3C/svg%3E"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 opacity-50 transition-opacity duration-300"
                         alt="{{ $item->judul }}">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                        <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-50 group-hover:scale-100">
                            <i class="ph-bold ph-magnifying-glass-plus text-lg"></i>
                        </div>
                    </div>
                    @if($item->jenis)
                    <div class="absolute top-3 left-3 z-10">
                        <span class="text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full"
                              style="background-color: color-mix(in srgb, {{$accentColor}} 20%, transparent); color: {{$accentColor}}; border: 1px solid color-mix(in srgb, {{$accentColor}} 30%, transparent);">
                            {{ str_replace('_', ' ', $item->jenis) }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="p-4 space-y-1.5">
                    <h3 class="text-sm font-bold text-white group-hover:text-[{{$accentColor}}] transition-colors leading-tight">{{ $item->judul }}</h3>
                    @if($item->deskripsi)
                    <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">{{ $item->deskripsi }}</p>
                    @endif
                    <a href="{{ route('layanan') }}" class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider mt-2 opacity-0 group-hover:opacity-100 transition-all duration-300" style="color: {{$accentColor}};">
                        Pesan Sekarang <i class="ph-bold ph-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4 border border-white/10">
                    <i class="ph-bold ph-image text-2xl text-gray-500"></i>
                </div>
                <h3 class="text-white font-bold text-lg mb-1">Belum Ada Galeri</h3>
                <p class="text-gray-500 text-sm">Belum ada hasil wrapping {{ $kategoriSlug }} yang ditambahkan.</p>
                <a href="{{ route('galeri.user') }}" class="inline-block mt-6 px-6 py-3 rounded-full text-xs font-extrabold uppercase tracking-wider" style="background:{{$accentColor}};color:#000;">
                    Kembali ke Galeri
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($galeris->hasPages())
        <div class="mt-12" data-aos="fade-up">
            {{ $galeris->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
        @endif

        {{-- Tombol Kembali --}}
        <div class="mt-12 text-center" data-aos="fade-up">
            <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-xs font-extrabold uppercase tracking-wider transition-all duration-300 hover:scale-105 active:scale-95 bg-white/5 text-gray-300 border border-white/10 hover:bg-white/10">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Galeri
            </a>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lb-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 backdrop-blur-sm" onclick="closeLightbox(event)">
    <button onclick="closeLightbox()" class="absolute top-4 sm:top-6 right-4 sm:right-6 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-x text-xl"></i>
    </button>
    <button onclick="navigateLb(-1)" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-left text-xl"></i>
    </button>
    <button onclick="navigateLb(1)" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all z-10">
        <i class="ph-bold ph-caret-right text-xl"></i>
    </button>
    <div class="relative max-w-6xl max-h-[90vh] mx-4 flex flex-col lg:flex-row items-center gap-6" onclick="event.stopPropagation()">
        <div class="flex-1 max-w-4xl">
            <img id="lb-image" class="w-full max-h-[70vh] object-contain rounded-2xl shadow-2xl" src="" alt="">
        </div>
        <div id="lb-sidebar" class="lg:w-72 w-full text-center lg:text-left space-y-4 p-4">
            <h3 id="lb-title" class="text-white text-lg sm:text-xl font-bold"></h3>
            <p id="lb-desc" class="text-gray-400 text-sm leading-relaxed"></p>
            <div id="lb-jenis" class="text-xs font-bold uppercase tracking-wider" style="color:{{$accentColor}};"></div>
            <a id="lb-order-btn" href="{{ route('layanan') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all hover:scale-105 active:scale-95" style="background:{{$accentColor}};color:#000;">
                <i class="ph-bold ph-shopping-cart-simple"></i> Pesan Layanan Ini
            </a>
        </div>
    </div>
</div>

<script>
const galeriData = [
    @foreach($galeris as $item)
    {
        src: '{{ asset("storage/" . $item->foto) }}',
        title: '{{ addslashes($item->judul) }}',
        desc: '{{ addslashes($item->deskripsi ?? "") }}',
        jenis: '{{ $item->jenis ?? "" }}',
    },
    @endforeach
];

let lbIndex = 0;

function openLightbox(index) {
    if (!galeriData.length) return;
    lbIndex = index;
    updateLightbox();
    document.getElementById('lb-overlay').classList.remove('hidden');
    document.getElementById('lb-overlay').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('lb-overlay').classList.add('hidden');
    document.getElementById('lb-overlay').classList.remove('flex');
    document.body.style.overflow = '';
}

function navigateLb(dir) {
    lbIndex += dir;
    if (lbIndex < 0) lbIndex = galeriData.length - 1;
    if (lbIndex >= galeriData.length) lbIndex = 0;
    updateLightbox();
}

function updateLightbox() {
    const item = galeriData[lbIndex];
    document.getElementById('lb-image').src = item.src;
    document.getElementById('lb-title').textContent = item.title;
    document.getElementById('lb-desc').textContent = item.desc;
    const jenisEl = document.getElementById('lb-jenis');
    if (item.jenis) {
        jenisEl.textContent = item.jenis.replace(/_/g, ' ');
        jenisEl.style.display = 'block';
    } else {
        jenisEl.style.display = 'none';
    }
}

document.addEventListener('keydown', function(e) {
    const overlay = document.getElementById('lb-overlay');
    if (!overlay || overlay.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLb(-1);
    if (e.key === 'ArrowRight') navigateLb(1);
});

// Filter
function filterJenis(jenis) {
    const cards = document.querySelectorAll('.galeri-card');
    const btns = document.querySelectorAll('.filter-jenis-btn');
    const accent = '{{ $accentColor }}';
    let visibleCount = 0;

    btns.forEach(btn => {
        const btnJenis = btn.getAttribute('data-jenis');
        if (btnJenis === jenis) {
            btn.style.backgroundColor = accent;
            btn.style.color = '#000';
            btn.style.borderColor = accent;
            btn.className = btn.className.replace(/bg-white\/5 text-gray-400/g, '');
        } else {
            btn.style.backgroundColor = 'rgba(255,255,255,0.05)';
            btn.style.color = '#9ca3af';
            btn.style.borderColor = 'rgba(255,255,255,0.1)';
        }
    });

    cards.forEach(card => {
        const cardJenis = (card.getAttribute('data-jenis') || '').toLowerCase();
        const match = jenis === 'all' || cardJenis === jenis;
        if (match) {
            card.style.display = 'block';
            card.style.opacity = '0';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            }, 50);
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const countEl = document.getElementById('photo-count');
    if (countEl) {
        countEl.textContent = visibleCount + ' Foto' + (jenis !== 'all' ? ' (difilter)' : '');
    }
}

// Lazy Loading with IntersectionObserver
document.addEventListener('DOMContentLoaded', function() {
    const lazyImages = document.querySelectorAll('.lazy-img');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.onload = () => { img.classList.remove('opacity-50'); img.classList.add('opacity-100'); };
                        img.onerror = () => { img.src = img.dataset.src; img.classList.remove('opacity-50'); img.classList.add('opacity-100'); };
                        observer.unobserve(img);
                    }
                }
            });
        }, { rootMargin: '100px' });
        lazyImages.forEach(img => observer.observe(img));
    } else {
        lazyImages.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.classList.remove('opacity-50'); img.classList.add('opacity-100');
            }
        });
    }

    // Smooth initial load for visible images
    setTimeout(() => {
        document.querySelectorAll('[data-src]').forEach(img => {
            const rect = img.getBoundingClientRect();
            if (rect.top < window.innerHeight) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.onload = () => { img.classList.remove('opacity-50'); img.classList.add('opacity-100'); };
                }
            }
        });
    }, 500);
});
</script>
@endsection

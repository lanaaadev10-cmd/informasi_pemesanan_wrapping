{{-- ============================================
    BAGIAN: Hero Layanan
    Deskripsi: Header judul "Layanan & Paket" + Deskripsi
============================================ --}}
<div class="text-center space-y-5 max-w-3xl mx-auto">
    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest"
         style="background: rgba(242,153,74,0.1); border: 1px solid rgba(242,153,74,0.2); color: var(--accent);">
        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--accent);"></span>
        Layanan & Paket
    </div>

    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]" style="font-style: italic;">
        {{ $profil?->layanan_hero_title ?? 'Precision in Every Layer.' }}
    </h1>

    @if($profil?->layanan_hero_desc)
        <p class="layanan-hero-desc" style="font-size: 1rem; line-height: 1.75; max-width: 42rem; margin: 0 auto; color: var(--accent);">
            {!! $profil->layanan_hero_desc !!}
        </p>
    @else
        <p class="layanan-hero-desc" style="font-size: 1rem; line-height: 1.75; max-width: 42rem; margin: 0 auto; color: var(--accent);">
            Pilih paket perlindungan dan estetika terbaik untuk kendaraan Anda. Menggunakan material grade premium dengan pemasangan yang sangat mendetail.
        </p>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     PORTOFOLIO SHOWCASE (Mengadaptasi Seksi "Portfolio" pada Gambar Referensi)
     Struktur Staggered 4 Kartu Vertikal dengan Offset Berjenjang (Alternating Height)
     Menggunakan Data Existing dari Model Galeri
═══════════════════════════════════════════════════════════════════ --}}
@php
    $portfolioItems = [];
    if (!empty($galeris) && count($galeris) > 0) {
        $portfolioItems = $galeris->all();
    } else {
        // Fallback dari layanan existing jika galeri kosong
        $portfolioItems = array_slice($services, 0, 4);
    }
@endphp

@if(count($portfolioItems) > 0)
    <div class="space-y-10 sm:space-y-14" id="layanan-portfolio-section">

        {{-- Variasi Heading Portfolio: Split Layout (Beda dari section lain) --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-5 gap-3">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-[#ea580c] block mb-1">
                    02 / Dokumentasi Karya
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">
                    Portfolio
                </h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md font-normal leading-relaxed">
                Transformasi estetika dan kerapian instalasi pengerjaan stiker nyata oleh teknisi profesional workshop kami.
            </p>
        </div>

        {{-- Grid 4 Kolom dengan Alternating Staggered Layout Sesuai Referensi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 items-start">
            @foreach($portfolioItems as $pIdx => $item)
                @php
                    $isGaleriModel = is_object($item);
                    $pJudul = $isGaleriModel ? ($item->judul ?? 'Project ' . ($pIdx + 1)) : ($item['nama'] ?? 'Layanan');
                    $pDesc = $isGaleriModel ? ($item->deskripsi ?? '') : ($item['deskripsi'] ?? '');
                    $pFoto = $isGaleriModel ? ($item->foto ?? null) : ($item['gambar'] ?? null);
                    $pImgSrc = !empty($pFoto) ? \App\Helpers\StaticContent::fotoUrl($pFoto) : $fallbackImages[$pIdx % count($fallbackImages)];
                    $pCategory = $isGaleriModel ? strtoupper($item->kategori ?? 'Wrap') : 'Spesialis';

                    // Alternating layout: Card 0 & 2 offset turun, Card 1 & 3 offset naik (Sesuai Visual Referensi)
                    $isOdd = ($pIdx % 2 !== 0);
                @endphp

                <div class="bg-slate-900 text-white rounded-3xl overflow-hidden border border-slate-800 shadow-sm flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl group {{ $isOdd ? 'sm:-translate-y-3' : 'sm:translate-y-3' }}">
                    
                    {{-- Header Top Label jika posisi ganjil (Clean Text, No Pill Badges) --}}
                    @if($isOdd)
                        <div class="p-4 sm:p-5 pb-3 flex items-center justify-between border-b border-white/10">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                {{ $pCategory }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#ea580c]"></span>
                        </div>
                    @endif

                    {{-- Foto Portfolio (Vertikal Ratio Sesuai Referensi - Clean Tanpa Overlay Badge) --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-800">
                        <img src="{{ $pImgSrc }}"
                             alt="{{ $pJudul }}"
                             class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    </div>

                    {{-- Label / Info di Bawah Foto --}}
                    <div class="p-4 sm:p-5 space-y-1.5 {{ $isOdd ? '' : 'border-t border-white/10' }}">
                        <h4 class="text-sm font-black text-white group-hover:text-[#ea580c] transition-colors leading-snug">
                            {{ $pJudul }}
                        </h4>
                        @if(!empty($pDesc))
                            <p class="text-slate-400 text-xs font-normal line-clamp-2 leading-relaxed">
                                {{ strip_tags($pDesc) }}
                            </p>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    </div>
@endif

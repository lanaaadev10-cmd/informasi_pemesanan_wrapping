@php
    $galleryList = isset($galeris) && $galeris->isNotEmpty() ? $galeris->values() : collect();
    $defaultImages = [
        asset('images/hero_racing_car.webp'),
        asset('images/layanan/wrapping-mobil.webp'),
        asset('images/tesla_model_s.webp'),
        asset('images/layanan/jasa-pasang-mobil.webp'),
        asset('images/layanan/kaca-film.webp'),
    ];

    $collagePhotos = [];
    for ($i = 0; $i < 5; $i++) {
        if (isset($galleryList[$i])) {
            $g = $galleryList[$i];
            $foto = $g->foto 
                ? \App\Helpers\StaticContent::fotoUrl($g->foto)
                : $defaultImages[$i];
            $collagePhotos[$i] = [
                'src' => $foto,
                'alt' => $g->judul,
            ];
        } else {
            $collagePhotos[$i] = [
                'src' => $defaultImages[$i],
                'alt' => 'Koleksi Portofolio ' . ($i + 1),
            ];
        }
    }

    $instagramUrl = $profil->instagram_url ?? \App\Helpers\StaticContent::INSTAGRAM_URL;
    $tiktokUrl = $profil->tiktok_url ?? 'https://tiktok.com';
    $facebookUrl = $profil->facebook_url ?? 'https://facebook.com';
    $whatsappUrl = $profil->whatsapp_link ?? \App\Helpers\StaticContent::COMPANY_WHATSAPP;
@endphp

<section class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="gallery-container">
    <div class="relative w-full rounded-[32px] sm:rounded-[44px] bg-[#f4f2eb] border border-[#e5e3d7] p-6 sm:p-10 lg:p-12 shadow-sm space-y-8">
        
        {{-- Header: Leaf / Logo Icon + Serif Title "Gallery" --}}
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-2 text-stone-700">
                <i class="ph-bold ph-sketch-logo text-[#ea580c] text-xl"></i>
                <h3 class="text-3xl sm:text-4xl font-serif text-stone-900 tracking-tight font-normal">Galeri Karya</h3>
            </div>
        </div>

        {{-- Main Layout: Left Social Icons + Center/Right 5-Photo Collage --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            {{-- Left: Follow Us + Social Icons --}}
            <div class="lg:col-span-2 space-y-4">
                <span class="text-xs font-medium text-stone-600 block">Ikuti Kami:</span>
                <div class="flex lg:flex-col gap-3">
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" 
                       class="w-10 h-10 rounded-full border border-stone-300 bg-white/70 hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all flex items-center justify-center text-stone-700 text-sm shadow-sm"
                       aria-label="Instagram">
                        <i class="ph-bold ph-instagram-logo"></i>
                    </a>
                    <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" 
                       class="w-10 h-10 rounded-full border border-stone-300 bg-white/70 hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all flex items-center justify-center text-stone-700 text-sm shadow-sm"
                       aria-label="TikTok">
                        <i class="ph-bold ph-tiktok-logo"></i>
                    </a>
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" 
                       class="w-10 h-10 rounded-full border border-stone-300 bg-white/70 hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all flex items-center justify-center text-stone-700 text-sm shadow-sm"
                       aria-label="Facebook">
                        <i class="ph-bold ph-facebook-logo"></i>
                    </a>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" 
                       class="w-10 h-10 rounded-full border border-stone-300 bg-white/70 hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all flex items-center justify-center text-stone-700 text-sm shadow-sm"
                       aria-label="WhatsApp">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </a>
                </div>
            </div>

            {{-- Center & Right: 5-Photo Collage Grid --}}
            <div class="lg:col-span-8">
                <div class="grid grid-cols-2 sm:grid-cols-12 gap-3 sm:gap-4">
                    
                    {{-- Photo 0: Wide Hero Feature Banner on Mobile (col-span-2), Tall Vertical on Tablet/Desktop (sm:col-span-4) --}}
                    <div class="col-span-2 sm:col-span-4 rounded-2xl overflow-hidden aspect-[16/10] sm:aspect-auto sm:h-full bg-stone-900 border border-stone-300/80 shadow-sm relative group">
                        <img src="{{ $collagePhotos[0]['src'] }}" alt="{{ $collagePhotos[0]['alt'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                    </div>

                    {{-- 2 Stacked Photos: Left Column on Mobile (col-span-1), Center Column on Tablet/Desktop (sm:col-span-4) --}}
                    <div class="col-span-1 sm:col-span-4 space-y-3 sm:space-y-4">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-stone-900 border border-stone-300/80 shadow-sm relative group">
                            <img src="{{ $collagePhotos[1]['src'] }}" alt="{{ $collagePhotos[1]['alt'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                        </div>
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-stone-900 border border-stone-300/80 shadow-sm relative group">
                            <img src="{{ $collagePhotos[2]['src'] }}" alt="{{ $collagePhotos[2]['alt'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                        </div>
                    </div>

                    {{-- 2 Stacked Photos: Right Column on Mobile (col-span-1), Right Column on Tablet/Desktop (sm:col-span-4) --}}
                    <div class="col-span-1 sm:col-span-4 space-y-3 sm:space-y-4">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-stone-900 border border-stone-300/80 shadow-sm relative group">
                            <img src="{{ $collagePhotos[3]['src'] }}" alt="{{ $collagePhotos[3]['alt'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                        </div>
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-stone-900 border border-stone-300/80 shadow-sm relative group">
                            <img src="{{ $collagePhotos[4]['src'] }}" alt="{{ $collagePhotos[4]['alt'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right Note / Caption --}}
            <div class="lg:col-span-2 text-stone-500 text-xs font-light leading-relaxed">
                Setiap pantulan kilau, lekukan presisi, dan kepuasan pelanggan diabadikan dengan bangga.
            </div>

        </div>

        {{-- Center Bottom: Pill Button "See More Photos" --}}
        <div class="pt-4 text-center">
            <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full text-xs font-montserrat font-medium text-stone-900 bg-white border border-stone-300 hover:border-[#ea580c] hover:bg-orange-50 hover:text-[#ea580c] transition-all shadow-sm active:scale-95">
                <span>Lihat Semua Foto Galeri</span>
                <i class="ph-bold ph-arrow-up-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

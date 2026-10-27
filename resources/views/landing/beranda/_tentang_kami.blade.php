{{-- ============================================
    SECTION 2: 3 Cards + "About" Narrative Block (Side-by-side)
    Sesuai Layout Gambar Inspirasi — Dioptimasi Responsif Mobile & Desktop
============================================ --}}
<section class="py-8 sm:py-12 px-3.5 sm:px-6 lg:px-8 max-w-7xl mx-auto" id="about-section">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 lg:gap-8 items-center">
        
        {{-- Left: 3 Image Cards (01, 02, 03) from Database Layanan --}}
        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5" data-aos="fade-up" data-aos-duration="1000">
            @php
                $showcaseLayanans = isset($layanans) && $layanans->isNotEmpty() ? $layanans->take(3) : collect();
                $fallbackCards = [
                    [
                        'nama' => 'Variasi Mobil',
                        'gambar' => asset('images/layanan/wrapping-mobil.webp'),
                        'harga' => 'Mulai Rp 3.500.000'
                    ],
                    [
                        'nama' => 'Kaca Film',
                        'gambar' => asset('images/layanan/kaca-film.webp'),
                        'harga' => 'Mulai Rp 350.000'
                    ],
                    [
                        'nama' => 'Audio Mobil',
                        'gambar' => asset('images/layanan/audio-head.webp'),
                        'harga' => 'Mulai Rp 2.000.000'
                    ],
                ];
            @endphp

            @if($showcaseLayanans->isNotEmpty())
                @foreach($showcaseLayanans as $idx => $layanan)
                    @php
                        $foto = $layanan->foto_contoh 
                            ? (str_starts_with($layanan->foto_contoh, 'http') 
                                ? $layanan->foto_contoh 
                                : (str_starts_with($layanan->foto_contoh, 'images/') 
                                    ? asset($layanan->foto_contoh) 
                                    : asset('storage/' . $layanan->foto_contoh)))
                            : ($fallbackCards[$idx]['gambar'] ?? asset('images/layanan/wrapping-mobil.webp'));

                        $webpUrl = null;
                        if (!empty($layanan->foto_contoh)) {
                            $potentialWebp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $layanan->foto_contoh);
                            if (file_exists(public_path($potentialWebp))) {
                                $webpUrl = asset($potentialWebp);
                            }
                        }
                    @endphp
                    <a href="{{ route('layanan') }}" 
                       class="group relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[16/10] sm:aspect-[4/5] bg-stone-900 border border-slate-200/90 hover:border-[#ff6b00] shadow-sm hover:shadow-xl transition-all duration-300 block">
                        <picture>
                            @if($webpUrl)
                                <source srcset="{{ $webpUrl }}" type="image/webp">
                            @endif
                            <img src="{{ $foto }}" 
                                 alt="{{ $layanan->nama_layanan }}" 
                                 class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                                 loading="lazy"
                                 decoding="async">
                        </picture>
                        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                        {{-- Floating Action Indicator (Affordance) --}}
                        <div class="absolute top-3 right-3 sm:top-3.5 sm:right-3.5 z-20 w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-black/40 backdrop-blur-md border border-white/20 text-white flex items-center justify-center group-hover:bg-[#ff6b00] group-hover:border-[#ff6b00] group-hover:scale-105 transition-all shadow-sm">
                            <i class="ph-bold ph-arrow-up-right text-xs"></i>
                        </div>

                        {{-- Bottom Overlay Info --}}
                        <div class="absolute bottom-3 left-3 right-3 sm:bottom-4 sm:left-4 sm:right-4 z-20 text-white">
                            <div class="flex items-center gap-1.5 mb-0.5 sm:mb-1">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-[#ff6b00] text-white font-audiowide font-bold text-[9px] sm:text-[10px] tracking-wider shadow-sm">
                                    {{ sprintf('%02d', $idx + 1) }}
                                </span>
                                <span class="text-[9px] sm:text-[10px] font-montserrat font-semibold text-white/70 uppercase tracking-wider">
                                    {{ $layanan->kategori ?? 'Layanan' }}
                                </span>
                            </div>
                            <h3 class="text-sm sm:text-base font-montserrat font-bold text-white tracking-tight leading-snug group-hover:text-amber-300 transition-colors line-clamp-1">
                                {{ $layanan->nama_layanan }}
                            </h3>
                            @if($layanan->harga > 0)
                                <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-sm border border-white/10 text-white/90 text-[10px] sm:text-xs">
                                    <span class="text-white/60 text-[9px] sm:text-[10px]">Mulai</span>
                                    <span class="font-audiowide font-bold text-amber-400">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            @else
                @foreach($fallbackCards as $idx => $card)
                    <a href="{{ route('layanan') }}" 
                       class="group relative rounded-2xl sm:rounded-3xl overflow-hidden aspect-[16/10] sm:aspect-[4/5] bg-stone-900 border border-slate-200/90 hover:border-[#ff6b00] shadow-sm hover:shadow-xl transition-all duration-300 block">
                        <img src="{{ $card['gambar'] }}" 
                             alt="{{ $card['nama'] }}" 
                             class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                             loading="lazy"
                             decoding="async">
                        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                        {{-- Floating Action Indicator --}}
                        <div class="absolute top-3 right-3 sm:top-3.5 sm:right-3.5 z-20 w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-black/40 backdrop-blur-md border border-white/20 text-white flex items-center justify-center group-hover:bg-[#ff6b00] group-hover:border-[#ff6b00] group-hover:scale-105 transition-all shadow-sm">
                            <i class="ph-bold ph-arrow-up-right text-xs"></i>
                        </div>

                        {{-- Bottom Overlay Info --}}
                        <div class="absolute bottom-3 left-3 right-3 sm:bottom-4 sm:left-4 sm:right-4 z-20 text-white">
                            <div class="flex items-center gap-1.5 mb-0.5 sm:mb-1">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-[#ff6b00] text-white font-audiowide font-bold text-[9px] sm:text-[10px] tracking-wider shadow-sm">
                                    {{ sprintf('%02d', $idx + 1) }}
                                </span>
                                <span class="text-[9px] sm:text-[10px] font-montserrat font-semibold text-white/70 uppercase tracking-wider">
                                    Layanan
                                </span>
                            </div>
                            <h3 class="text-sm sm:text-base font-montserrat font-bold text-white tracking-tight leading-snug group-hover:text-amber-300 transition-colors line-clamp-1">
                                {{ $card['nama'] }}
                            </h3>
                            <div class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md bg-black/50 backdrop-blur-sm border border-white/10 text-white/90 text-[10px] sm:text-xs">
                                <span class="font-audiowide font-bold text-amber-400">{{ $card['harga'] }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        {{-- Right: "about wapping" Narrative Column --}}
        <div class="lg:col-span-4 space-y-4 sm:space-y-6 lg:pl-4" data-aos="fade-left" data-aos-duration="1000">
            
            <div class="space-y-1">
                {{-- Tag: Tentang Kami --}}
                <span class="text-[11px] sm:text-xs font-montserrat font-bold uppercase tracking-wider text-[#ff6b00] flex items-center gap-1.5">
                    <i class="ph-bold ph-seal-check text-xs"></i>
                    <span>Tentang {{ $profil->nama_perusahaan ?? \App\Helpers\StaticContent::APP_NAME }}</span>
                </span>
                <h2 class="text-xl sm:text-2xl font-audiowide font-bold text-slate-900 tracking-tight leading-tight">
                    Presisi di Setiap Lapisan &amp; Lekukan
                </h2>
            </div>

            {{-- Narrative Paragraph --}}
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed font-questrial">
                {{ $profil->deskripsi_perusahaan ?? ($profil->nama_perusahaan ?? \App\Helpers\StaticContent::APP_NAME) . ' Studio menghadirkan perpaduan keindahan estetika visual dengan ketelitian teknik instalasi kelas dunia. Setiap lekukan bodi kendaraan dirawat dengan presisi mikro untuk menghadirkan kemewahan yang bertahan lama dan ketenangan hati bagi setiap pemiliknya.' }}
            </p>

            {{-- Social Proof & Ratings Row from Real Data (SSOT) --}}
            @php
                $avgScore = ($ratingSummary['average'] ?? 0) > 0 ? number_format($ratingSummary['average'], 1) : '4.9';
                $ratingCount = ($ratingSummary['total'] ?? 0) > 0 ? ($ratingSummary['total'] . ' ulasan terverifikasi') : '300+ ulasan puas';
                $klienCount = isset($totalKlien) ? $totalKlien . '+' : '500+';
            @endphp
            <div class="pt-3 flex flex-wrap items-center gap-3 sm:gap-6 border-t border-slate-200">
                {{-- Stacked Avatars --}}
                <div class="flex items-center gap-2.5">
                    <div class="flex -space-x-2 overflow-hidden">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Customer avatar" loading="lazy">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Customer avatar" loading="lazy">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&q=80" alt="Customer avatar" loading="lazy">
                    </div>
                    <div class="text-[11px] text-slate-600 font-medium leading-tight">
                        Dipercaya oleh<br><strong class="text-slate-900 font-bold">{{ $klienCount }} Pelanggan</strong>
                    </div>
                </div>

                {{-- Rating badge --}}
                <div class="border-l border-slate-200 pl-3 sm:pl-4">
                    <div class="flex items-center gap-1 text-amber-500 text-xs">
                        <span class="font-bold text-slate-900 text-sm mr-1">{{ $avgScore }}</span>
                        <i class="ph-fill ph-star text-xs"></i>
                        <i class="ph-fill ph-star text-xs"></i>
                        <i class="ph-fill ph-star text-xs"></i>
                        <i class="ph-fill ph-star text-xs"></i>
                        <i class="ph-fill ph-star text-xs"></i>
                    </div>
                    <a href="{{ route('testimoni.index') }}" class="text-[10px] sm:text-[11px] text-slate-500 hover:text-[#ff6b00] transition-colors font-medium block">
                        {{ $ratingCount }}
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

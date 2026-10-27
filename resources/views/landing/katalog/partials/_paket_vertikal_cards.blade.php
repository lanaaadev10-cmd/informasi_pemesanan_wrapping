<!-- GRID PRODUK E-COMMERCE MINIMALIS (3 Kolom di Laptop/Desktop, 2 Kolom di Layar Mobile) -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-2.5 sm:gap-4 items-stretch" id="catalog-grid">
    @foreach($layanan as $idx => $item)
        @php
            $catSlug = strtolower(trim($item->tipe_paket ?? ''));
            $catType = strtolower(trim($item->kategori ?? 'mobil'));
            $catName = strtolower(trim($item->nama_layanan ?? ''));

            $isJasaPasang = ($item->jenis_layanan === 'jasa_pasang')
                || str_contains($catSlug, 'jasa-pasang')
                || str_contains($catName, 'jasa pasang')
                || str_contains($catName, 'bahan sendiri');

            $hasFixedPrice = (!empty($item->harga) && (float)$item->harga > 0);
            $isAksesoris = in_array($catSlug, ['window-film', 'lighting', 'audio'])
                || str_contains($catName, 'kaca film')
                || str_contains($catName, 'lampu')
                || str_contains($catName, 'audio');
            $isParsial = ($catSlug === 'partial') || str_contains($catSlug, 'partial');
            $isStriping = ($catSlug === 'striping') || str_contains($catName, 'striping');
            $isDecal = str_contains($catSlug, 'decal') || str_contains($catName, 'decal');

            $isCustom = !$isJasaPasang && !$isAksesoris && (
                (!$hasFixedPrice)
                || str_contains($catSlug, 'custom')
                || str_contains($catName, 'custom quote')
                || str_contains($catName, 'livery')
            );

            $tagList = ['all', $catType, $catName];
            if (!empty($catSlug)) $tagList[] = $catSlug;
            if ($isJasaPasang) $tagList[] = 'jasa-pasang';
            if ($catSlug === 'wrapping' || str_contains($catName, 'full wrap') || str_contains($catName, 'variasi mobil')) {
                $tagList[] = 'wrapping';
            }
            if ($isAksesoris) $tagList[] = 'aksesoris';
            if ($catType === 'motor') $tagList[] = 'motor';
            else $tagList[] = 'mobil';

            if ($isCustom || $isParsial || $isStriping || $isDecal) {
                $tagList[] = 'custom';
                $tagList[] = 'partial';
                $tagList[] = 'striping';
            }

            $tagString = implode(' ', array_unique($tagList));

            $fotoPath = $item->getOriginal('foto_contoh') ?? $item->foto_contoh ?? null;
            $imgSrc = !empty($fotoPath)
                ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
                : asset($fallbackImages[$idx % count($fallbackImages)]);

            $webpSrc = null;
            if (!empty($fotoPath)) {
                $potentialWebp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $fotoPath);
                if (file_exists(public_path($potentialWebp))) {
                    $webpSrc = asset($potentialWebp);
                }
            }
            $isAboveFold = ($idx < 6);

            $svcSummary = $ratingSummary[$item->id_layanan] ?? null;
            $itemRating = $svcSummary ? (float)$svcSummary['avg'] : 5.0;
            $itemReviewsCount = $svcSummary ? (int)$svcSummary['count'] : 0;
        @endphp

        <!-- Item Kartu Produk Minimalis & Clean -->
        <div class="katalog-item bg-white border border-slate-200/90 hover:border-orange-400 rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group shadow-sm relative"
             data-category="{{ $tagString }}"
             data-vehicle="{{ $catType }}"
             data-price="{{ $item->harga ?? 0 }}"
             data-rating="{{ $itemRating }}"
             data-name="{{ $item->nama_layanan }}"
             data-index="{{ $idx }}">

            <div>
                <!-- Foto Layanan dengan Rasio Aspek Ramping (4:3) -->
                <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 flex-shrink-0 group/img">
                    <picture>
                        @if($webpSrc)
                            <source srcset="{{ $webpSrc }}" type="image/webp">
                        @endif
                        <img src="{{ $imgSrc }}" 
                             alt="{{ $item->nama_layanan }}" 
                             class="w-full h-full object-cover object-center transition-transform duration-500 group-hover/img:scale-105"
                             @if($isAboveFold)
                                 loading="eager"
                                 fetchpriority="high"
                             @else
                                 loading="lazy"
                                 decoding="async"
                             @endif>
                    </picture>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    
                    <!-- Overlay Badge Jenis Kendaraan (Top-Left) -->
                    <div class="absolute top-2 left-2 flex items-center gap-1">
                        <span class="px-1.5 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[9px] sm:text-[10px] font-montserrat font-bold text-white uppercase tracking-wider flex items-center gap-1 shadow-sm">
                            <span>{{ $catType === 'motor' ? '🛵 MOTOR' : '🚗 MOBIL' }}</span>
                        </span>
                    </div>

                    <!-- Overlay Badge Kategori / Keunggulan (Top-Right) -->
                    <div class="absolute top-2 right-2">
                        @if($isJasaPasang)
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-montserrat font-bold bg-emerald-500 text-white shadow-sm">
                                Jasa Pasang
                            </span>
                        @elseif($isCustom)
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-montserrat font-bold bg-purple-600 text-white shadow-sm">
                                Custom
                            </span>
                        @elseif($isAksesoris)
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-montserrat font-bold bg-amber-500 text-white shadow-sm">
                                Aksesoris
                            </span>
                        @else
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-montserrat font-bold bg-[#ff6b00] text-white shadow-sm">
                                Full Wrap
                            </span>
                        @endif
                    </div>

                    <!-- Overlay Estimasi Waktu (Bottom-Left) -->
                    @if($item->estimasi_waktu)
                        <div class="absolute bottom-2 left-2 flex items-center gap-1 text-[9px] sm:text-[10px] font-montserrat font-medium text-white/95 bg-black/50 backdrop-blur-sm px-1.5 py-0.5 rounded shadow-sm">
                            <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                            <span>{{ $item->estimasi_waktu }}</span>
                        </div>
                    @endif
                </div>

                <!-- Detail Konten Layanan -->
                <div class="p-2.5 sm:p-4 space-y-1.5 sm:space-y-2">
                    <!-- Rating & Ulasan E-Commerce -->
                    <div class="flex items-center gap-1 text-[10px] sm:text-xs">
                        <i class="ph-fill ph-star text-[#ff6b00]"></i>
                        <span class="font-montserrat font-bold text-slate-800">
                            {{ number_format($itemRating, 1, ',', '.') }}
                        </span>
                        @if($itemReviewsCount > 0)
                            <span class="text-slate-400 font-questrial">
                                ({{ $itemReviewsCount }})
                            </span>
                        @else
                            <span class="text-slate-400 font-questrial hidden sm:inline">
                                (Workshop Resmi)
                            </span>
                        @endif
                    </div>

                    <!-- Judul Layanan (Line Clamp 2 agar Tinggi Sejajar) -->
                    <h4 class="katalog-title text-xs sm:text-sm font-montserrat font-bold text-slate-900 group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem]">
                        {{ $item->nama_layanan }}
                    </h4>

                    <!-- Deskripsi Singkat Layanan -->
                    @if(!empty($item->deskripsi))
                        <p class="text-[10px] sm:text-xs font-questrial text-slate-500 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($item->deskripsi), 65) }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Bagian Bawah: Box Harga & Tombol Aksi Transaksi -->
            <div class="p-2.5 sm:p-4 pt-0 space-y-2">
                <!-- Box Harga -->
                <div class="pt-1.5 border-t border-slate-100">
                    <span class="text-[9px] sm:text-[10px] font-montserrat font-semibold uppercase tracking-wider block text-slate-400">
                        @if($isJasaPasang)
                            Ongkos Jasa Pasang
                        @elseif($isCustom)
                            Estimasi Bahan &amp; Desain
                        @else
                            Tarif Paket Workshop
                        @endif
                    </span>
                    <div class="text-xs sm:text-base font-audiowide font-bold text-[#ff6b00] leading-tight truncate">
                        @if($hasFixedPrice && !$isCustom)
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        @else
                            Custom Quote
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi Berdasarkan Tipe Layanan -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    @if($isJasaPasang)
                        <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}"
                           target="_blank" rel="noopener noreferrer"
                           title="Konsultasikan bahan Anda via WhatsApp"
                           class="w-full py-1.5 sm:py-2 px-2 sm:px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-montserrat font-bold text-[10px] sm:text-xs uppercase tracking-wider rounded-lg sm:rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/20 active:scale-95 text-center">
                            <i class="ph-bold ph-whatsapp-logo text-xs sm:text-sm"></i>
                            <span>Chat WA</span>
                        </a>
                    @elseif($isCustom)
                        <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}"
                           target="_blank" rel="noopener noreferrer"
                           title="Konsultasikan desain Anda via WhatsApp"
                           class="w-full py-1.5 sm:py-2 px-2 sm:px-3 bg-purple-600 hover:bg-purple-700 text-white font-montserrat font-bold text-[10px] sm:text-xs uppercase tracking-wider rounded-lg sm:rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm shadow-purple-600/20 active:scale-95 text-center">
                            <i class="ph-bold ph-paint-brush text-xs sm:text-sm"></i>
                            <span>Custom WA</span>
                        </a>
                    @else
                        <a href="{{ route('booking.create', ['layanan_id' => $item->id_layanan]) }}"
                           class="flex-1 py-1.5 sm:py-2 px-2 sm:px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-[10px] sm:text-xs uppercase tracking-wider rounded-lg sm:rounded-xl transition-all duration-200 flex items-center justify-center gap-1 shadow-sm shadow-orange-500/20 active:scale-95 text-center">
                            <i class="ph-bold ph-calendar-plus text-xs sm:text-sm"></i>
                            <span>Booking</span>
                        </a>

                        <form action="{{ route('keranjang.tambah') }}" method="POST" class="shrink-0">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                            <input type="hidden" name="jumlah" value="1">
                            <button type="submit" 
                                    title="Tambah ke Keranjang"
                                    class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-orange-50 hover:bg-[#ff6b00] border border-orange-200 text-[#ff6b00] hover:text-white flex items-center justify-center transition-all active:scale-95 shadow-sm">
                                <i class="ph-bold ph-shopping-bag text-xs sm:text-base"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    @endforeach
</div>

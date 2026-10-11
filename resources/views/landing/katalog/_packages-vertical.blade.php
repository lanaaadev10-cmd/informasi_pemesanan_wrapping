{{-- ============================================
    BAGIAN: Grid Produk Katalog E-Commerce Minimalis
    Deskripsi: Toolbar sortir & pencarian ramping dengan grid produk
               bergaya e-commerce minimalis (clean, compact, & proporsional)
============================================ --}}
<div class="space-y-4">

    <!-- 1. TOOLBAR E-COMMERCE RAMPIS -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-4 shadow-sm space-y-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">

            <!-- Kiri: Mobile Filter Button & Counter -->
            <div class="flex items-center gap-2.5">
                <!-- Tombol Buka Filter (Mobile < 768px) -->
                <button type="button"
                        onclick="toggleMobileFilter(true)"
                        class="md:hidden inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-50 border border-orange-200 text-[#ff6b00] text-xs font-montserrat font-bold active:scale-95 transition-all">
                    <i class="ph-bold ph-sliders-horizontal text-sm"></i>
                    <span>Filter</span>
                </button>

                <!-- Counter Hasil -->
                <div class="text-xs font-questrial text-slate-500">
                    Menampilkan <strong id="catalog-visible-count" class="text-slate-900 font-montserrat font-bold text-sm">{{ $layanan->count() }}</strong> layanan
                </div>
            </div>

            <!-- Kanan: Search Bar Cepat & Dropdown Urutkan -->
            <div class="flex items-center gap-2 flex-1 sm:flex-initial justify-end">
                <!-- Search Input Ringkas -->
                <div class="relative flex-1 sm:w-56 md:w-60">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text"
                           id="catalog-search"
                           oninput="searchCatalog()"
                           placeholder="Cari layanan..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-7 py-2 text-xs font-montserrat text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#ff6b00] focus:bg-white transition-all">
                    <button type="button"
                            id="catalog-search-clear"
                            onclick="clearCatalogQuery()"
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 hidden">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                </div>

                <!-- Dropdown Sortir E-Commerce -->
                <div class="relative shrink-0">
                    <select id="catalog-sort-select"
                            onchange="sortCatalog(this.value)"
                            aria-label="Urutkan katalog layanan"
                            class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2 text-xs font-montserrat font-semibold text-slate-700 hover:text-slate-900 focus:outline-none focus:border-[#ff6b00] focus:bg-white cursor-pointer transition-all pr-7 appearance-none">
                        <option value="default">Paling Sesuai</option>
                        <option value="price-asc">Harga: Termurah</option>
                        <option value="price-desc">Harga: Termahal</option>
                        <option value="rating-desc">Rating Tertinggi</option>
                        <option value="name-asc">Nama: A - Z</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                        <i class="ph-bold ph-caret-down text-xs"></i>
                    </span>
                </div>
            </div>

        </div>

        <!-- Baris 2: Active Filter Chips Bar -->
        <div id="catalog-chips-wrapper" class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 flex-wrap text-xs">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-[10px] font-questrial text-slate-400">Filter aktif:</span>
                <div id="catalog-active-chips" class="inline-flex items-center gap-1.5 flex-wrap">
                    <!-- Diisi dinamis oleh JavaScript -->
                </div>
            </div>

            <button type="button"
                    id="catalog-clear-all-btn"
                    onclick="resetAllKatalogFilters()"
                    class="hidden text-[#ff6b00] hover:underline text-[11px] font-montserrat font-bold">
                Hapus Semua Filter ×
            </button>
        </div>
    </div>

    @if($layanan->isNotEmpty())
        @php
            $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
        @endphp

        {{-- 2. PANEL KHUSUS: ARAHAN KONSULTASI CUSTOM VIA WHATSAPP (MUNCUL KETIKA FILTER CUSTOM AKTIF) --}}
        @php
            $rawWaGuidance = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
            if (str_starts_with($rawWaGuidance, '0')) {
                $rawWaGuidance = '62' . substr($rawWaGuidance, 1);
            }
        @endphp
        <div id="catalog-custom-guidance"
             style="display: none;"
             class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden space-y-4.5 my-2">

            <!-- Subtle Racing Top Accent Line -->
            <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-[#ff6b00]/60 to-transparent"></div>

            {{-- Header Panel --}}
            <div class="border-b border-slate-100 pb-3.5 space-y-1">
                <h3 id="custom-guidance-title" class="text-base sm:text-lg font-audiowide font-bold text-slate-900 tracking-wide">
                    Alur Pemesanan Custom 
                </h3>
                <p id="custom-guidance-desc" class="text-xs sm:text-sm font-questrial text-slate-600 leading-relaxed max-w-2xl">
                    Pengerjaan custom dibuat khusus sesuai konsep dan ukuran kendaraan Anda. Ikuti 3 langkah mudah berikut untuk memesan:
                </p>
            </div>

            {{-- 3 Langkah Alur Proyek Custom --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">1</span>
                        <h4 class="text-xs font-montserrat font-bold text-slate-900">Konsultasi Desain</h4>
                    </div>
                    <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                        Kirim foto kendaraan dan contoh desain yang Anda inginkan ke WhatsApp kami.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">2</span>
                        <h4 class="text-xs font-montserrat font-bold text-slate-900">Estimasi Biaya &amp; Jadwal</h4>
                    </div>
                    <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                        Tim kami akan menghitung perkiraan biaya, pilihan bahan, dan waktu pengerjaan.
                    </p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-orange-300 transition-all space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-orange-50 border border-orange-200 text-[#ff6b00] font-mono font-bold text-[10px] flex items-center justify-center">3</span>
                        <h4 class="text-xs font-montserrat font-bold text-slate-900">Pesanan Diproses</h4>
                    </div>
                    <p class="text-[11px] font-questrial text-slate-600 leading-relaxed pt-0.5">
                        Setelah Anda setuju, admin akan langsung membuatkan data pesanan dan jadwal pengerjaan Anda.
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi WhatsApp & Navigasi --}}
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2.5">
                <a id="custom-guidance-wa-btn"
                   href="https://api.whatsapp.com/send?phone={{ $rawWaGuidance }}&text={{ urlencode('Halo Tim Dantie Stiker, saya ingin konsultasi desain & estimasi biaya untuk proyek Custom kendaraan saya...') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 hover:shadow-lg transition-all active:scale-95">
                    <i class="ph-bold ph-whatsapp-logo text-base"></i>
                    <span>Chat WhatsApp &amp; Konsultasi Sekarang</span>
                </a>

                <button type="button" onclick="setServiceFilter('all')"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-montserrat font-semibold text-xs active:scale-95 transition-all">
                    <i class="ph-bold ph-arrow-left text-xs"></i>
                    <span>Kembali ke Pilihan Paket</span>
                </button>
            </div>
        </div>

        <!-- 2. GRID PRODUK E-COMMERCE MINIMALIS (3 Kolom di Laptop/Desktop, 2 Kolom di Layar Kecil) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-3.5 sm:gap-4 items-stretch" id="catalog-grid">
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
                    $isParsial = ($catSlug === 'partial')
                        || str_contains($catSlug, 'partial');

                    $isStriping = ($catSlug === 'striping') || str_contains($catName, 'striping');
                    $isDecal = str_contains($catSlug, 'decal') || str_contains($catName, 'decal');

                    $isCustom = !$isJasaPasang && !$isAksesoris && (
                        (!$hasFixedPrice)
                        || $isStriping
                        || $isDecal
                        || str_contains($catSlug, 'custom')
                        || str_contains($catName, 'custom')
                        || str_contains($catName, 'custom quote')
                        || str_contains($catName, 'livery')
                    );

                    if ($isCustom) {
                        continue;
                    }

                    $tagList = ['all', $catType, $catName];
                    if (!empty($catSlug)) $tagList[] = $catSlug;
                    if ($isJasaPasang) $tagList[] = 'jasa-pasang';
                    if ($catSlug === 'wrapping' || str_contains($catName, 'full wrap') || str_contains($catName, 'variasi mobil')) {
                        $tagList[] = 'wrapping';
                    }
                    if ($isAksesoris) $tagList[] = 'aksesoris';
                    if ($catType === 'motor') $tagList[] = 'motor';
                    else $tagList[] = 'mobil';

                    $categoryData = implode(' ', array_unique($tagList));

                    $fotoPath = $item->getOriginal('foto_contoh') ?? $item->foto_contoh ?? null;
                    $imgSrc = !empty($fotoPath)
                        ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
                        : asset($fallbackImages[$idx % count($fallbackImages)]);

                    $svcSummary = $ratingSummary[$item->id_layanan] ?? null;
                    $itemRating = $svcSummary ? (float)$svcSummary['avg'] : 5.0;
                    $itemReviewsCount = $svcSummary ? (int)$svcSummary['count'] : 0;
                    $rawPrice = (float)($item->harga ?? 0);
                @endphp

                <!-- KARTU PRODUK E-COMMERCE MINIMALIS -->
                <div class="katalog-item bg-white border border-slate-200/90 hover:border-orange-400 rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group shadow-sm relative"
                     data-category="{{ $categoryData }}"
                     data-vehicle="{{ $catType }}"
                     data-price="{{ $rawPrice }}"
                     data-rating="{{ $itemRating }}"
                     data-name="{{ strtolower($item->nama_layanan) }}"
                     data-index="{{ $idx }}">

                    <div>
                        <!-- 1. Foto Showcase Produk (Aspek 4:3) -->
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 flex-shrink-0 group/img">
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $item->nama_layanan }}"
                                 class="w-full h-full object-cover object-center transition-transform duration-500 group-hover/img:scale-105"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            <!-- Badge Kendaraan (Kiri Atas) -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="text-[9px] font-montserrat font-bold uppercase px-2 py-0.5 rounded-md backdrop-blur-md bg-black/75 text-white border border-white/20 shadow-sm">
                                    {{ $catType === 'motor' ? ' Motor' : ' Mobil' }}
                                </span>
                            </div>

                            <!-- Badge Tipe Layanan (Kanan Atas) -->
                            <div class="absolute top-2 right-2 z-10">
                                @if($isJasaPasang)
                                    <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-emerald-500/40 bg-emerald-950/85 text-emerald-300 shadow-sm">
                                         Jasa Pasang
                                    </span>
                                @elseif($isAksesoris)
                                    <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-cyan-500/40 bg-cyan-950/85 text-cyan-300 shadow-sm">
                                         Aksesoris
                                    </span>
                                @elseif($isCustom || $isStriping || $isDecal)
                                    <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-purple-500/40 bg-purple-950/85 text-purple-300 shadow-sm">
                                         Custom Decal
                                    </span>
                                @else
                                    <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-orange-500/40 bg-orange-950/85 text-orange-300 shadow-sm">
                                         Full Wrap
                                    </span>
                                @endif
                            </div>

                            <!-- Estimasi Waktu (Kiri Bawah Foto) -->
                            @if($item->estimasi_waktu)
                            <div class="absolute bottom-2 left-2 z-10">
                                <span class="inline-flex items-center gap-1 text-[9px] font-montserrat font-bold text-white bg-black/70 backdrop-blur-md px-1.5 py-0.5 rounded-md border border-white/10">
                                    <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                                    <span>{{ $item->estimasi_waktu }}</span>
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- 2. Informasi Detail Produk Minimalis -->
                        <div class="p-3 sm:p-3.5 space-y-1.5">
                            <!-- Kategori Subtitle -->
                            <div class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-slate-500">
                                {{ $catType === 'motor' ? 'MOTOR' : 'MOBIL' }} • {{ $isJasaPasang ? 'JASA PASANG' : ($isCustom ? 'CUSTOM DECAL' : ($isAksesoris ? 'AKSESORIS' : 'FULL WRAP')) }}
                            </div>

                            <!-- Judul Produk (Maksimal 2 baris clean) -->
                            <h4 class="katalog-title text-xs sm:text-sm font-montserrat font-bold text-slate-900 group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-2 min-h-[2.25rem]">
                                {{ $item->nama_layanan }}
                            </h4>

                            <!-- Rating & Ulasan E-Commerce -->
                            <div class="flex items-center gap-1.5 pt-0.5">
                                @if($itemReviewsCount > 0)
                                    <div class="flex items-center gap-0.5 text-xs text-[#ff6b00] font-montserrat font-bold">
                                        <i class="ph-fill ph-star text-xs"></i>
                                        <span>{{ number_format((float) $svcSummary['avg'], 1, ',', '.') }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-questrial">
                                        ({{ $itemReviewsCount }} ulasan)
                                    </span>
                                @else
                                    <div class="flex items-center gap-1 text-[10px] text-slate-500 font-questrial">
                                        <i class="ph-fill ph-seal-check text-[#ff6b00] text-xs"></i>
                                        <span>Garansi Toko</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. Bottom Bar: Harga & Tombol Aksi E-Commerce -->
                    <div class="p-3 sm:p-3.5 pt-0 space-y-2.5">
                        <!-- Box Harga (Full Width - Tidak Terpotong) -->
                        <div class="pt-2.5 border-t border-slate-100 space-y-2">
                            <div>
                                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider block text-slate-500 mb-0.5">
                                    {{ $isJasaPasang ? 'Ongkos Pasang' : ($isCustom ? 'Estimasi Proyek' : 'Tarif Paket') }}
                                </span>
                                <div class="text-base sm:text-lg font-audiowide font-bold text-[#ff6b00] leading-tight">
                                    @if($hasFixedPrice && !$isCustom)
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    @else
                                        Custom Quote
                                    @endif
                                </div>
                            </div>

                            @if($isCustom || !$hasFixedPrice)
                                <!-- Keterangan info pengalihan ke WhatsApp -->
                                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-800 font-questrial flex items-start gap-2 leading-relaxed">
                                    <i class="ph-bold ph-info text-sm shrink-0 mt-0.5 text-emerald-600"></i>
                                    <span>Pengerjaan custom tidak menggunakan paket baku. Klik tombol di bawah untuk konsultasi konsep &amp; estimasi harga via WhatsApp.</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Aksi E-Commerce -->
                        <div class="flex items-center gap-2">
                            @if($isCustom || !$hasFixedPrice)
                                @php
                                    $rawWa = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
                                    if (str_starts_with($rawWa, '0')) {
                                        $rawWa = '62' . substr($rawWa, 1);
                                    }
                                    $vehicleLabel = ($catType === 'motor') ? 'Motor' : 'Mobil';
                                    $waMsg = "Halo Tim Dantie Stiker, saya ingin konsultasi estimasi proyek Custom {$vehicleLabel} untuk *{$item->nama_layanan}*...";
                                    $waLink = "https://api.whatsapp.com/send?phone={$rawWa}&text=" . urlencode($waMsg);
                                @endphp
                                <a href="{{ $waLink }}" target="_blank"
                                   class="w-full py-2.5 px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/20 active:scale-95">
                                    <i class="ph-bold ph-whatsapp-logo text-base"></i>
                                    <span>Konsultasi WA</span>
                                </a>
                            @else
                                <a href="{{ route('booking.create', ['layanan_id' => $item->id_layanan]) }}"
                                   class="flex-1 py-2 px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/20 active:scale-95">
                                    <i class="ph-bold ph-calendar-plus text-sm"></i>
                                    <span>Booking</span>
                                </a>

                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit"
                                            title="Tambah ke Keranjang"
                                            class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-orange-300 flex items-center justify-center text-slate-600 hover:text-[#ff6b00] transition-all active:scale-95 shadow-sm">
                                        <i class="ph-bold ph-shopping-bag-open text-base"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- 3. Empty State (Ketika Filter Pencarian Tidak Ditemukan) -->
        <div id="catalog-empty-state"
             style="display: none;"
             class="w-full py-14 px-6 flex flex-col items-center justify-center text-center bg-slate-50 border border-dashed border-slate-200 rounded-2xl shadow-sm my-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-200 flex items-center justify-center text-[#ff6b00] mb-3 text-xl">
                <i class="ph-bold ph-archive-box"></i>
            </div>
            <h4 class="text-sm font-audiowide font-bold text-slate-900 tracking-wide">
                Layanan Tidak Ditemukan
            </h4>
            <p class="text-xs font-questrial text-slate-500 max-w-md mx-auto mt-1 leading-relaxed">
                Tidak ada paket yang sesuai dengan filter atau kata kunci pencarian Anda saat ini.
            </p>
            <div class="mt-3">
                <button type="button"
                        onclick="resetAllKatalogFilters()"
                        class="px-4 py-2 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 active:scale-95">
                    Tampilkan Semua Layanan
                </button>
            </div>
        </div>
    @else
        <!-- Empty State jika memang belum ada data layanan -->
        <div class="py-14 px-6 flex flex-col items-center justify-center text-center bg-slate-50 border border-slate-200 rounded-2xl shadow-sm">
            <h4 class="text-sm font-audiowide font-bold text-slate-900 tracking-wide">
                Belum Ada Layanan
            </h4>
            <p class="text-xs font-questrial text-slate-500 max-w-md mx-auto mt-1">
                Layanan workshop sedang dalam pembaruan berkala.
            </p>
        </div>
    @endif

</div>

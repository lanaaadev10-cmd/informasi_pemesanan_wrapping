{{-- ==============================================================
     BAGIAN 3: GRID PRODUK YANG DINAMIS
     - Mobile: 2 Kolom dengan Jarak Samping Tepat 5px (gap-x-[5px] px-[5px])
     - Tablet: 3 Kolom (md:grid-cols-3)
     - Desktop: 4 Kolom (xl:grid-cols-4)
============================================================== --}}
<div class="px-[5px] sm:px-0">
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-[5px] gap-y-2.5 sm:gap-5 lg:gap-6 items-stretch" id="dashboard-picks-grid">
        @forelse($layanans as $idx => $item)
            @php
                $vehicleType = strtolower(trim($item->kategori ?? 'mobil'));
                $pillarType  = $item->pillar;
                $isWaOnly    = $item->isWhatsappOnly();

                // Setup Gambar & WebP Optimasi
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
                $isAboveFold = ($idx < 4);

                // Rating & Ringkasan Ulasan
                $svcSummary = $ratingSummary[$item->id_layanan] ?? null;
                $itemRating = $svcSummary ? (float)$svcSummary['avg'] : 5.0;
                $itemReviewsCount = $svcSummary ? (int)$svcSummary['count'] : 0;
                $hasFixedPrice = (!empty($item->harga) && (float)$item->harga > 0);

                // WhatsApp link generator untuk Jasa Pasang dan Custom
                if ($pillarType === 'jasa-pasang') {
                    $waDirectUrl = \App\Helpers\WhatsAppHelper::getJasaUrl();
                } else {
                    $waDirectUrl = \App\Helpers\WhatsAppHelper::getCustomUrl();
                }
            @endphp

            <!-- Card Komponen Terintegrasi -->
            <div class="dash-pick-card bg-white border border-slate-200/90 hover:border-orange-400 rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-lg group shadow-sm relative"
                 data-vehicle="{{ $vehicleType }}"
                 data-pillar="{{ $pillarType }}">

                <div>
                    <!-- Foto Layanan dengan Rasio Aspek Konsisten (4:3) -->
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
                        <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
                        
                        <!-- Overlay Badge Jenis Kendaraan (Top-Left) -->
                        <div class="absolute top-2 left-2 flex items-center gap-1">
                            <span class="px-1.5 py-0.5 rounded-md bg-black/70 backdrop-blur-md text-[9px] sm:text-[10px] font-montserrat font-bold text-white uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                <span>{{ $vehicleType === 'motor' ? '🛵 MOTOR' : '🚗 MOBIL' }}</span>
                            </span>
                        </div>

                        <!-- Overlay Badge Pilar Kebutuhan (Top-Right) -->
                        <div class="absolute top-2 right-2">
                            <span class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-montserrat font-bold shadow-sm {{ $item->pillar_badge_color }}">
                                {{ $item->pillar_label }}
                            </span>
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
                        <h4 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900 group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem]">
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

                <!-- Bagian Bawah: Box Harga & Tombol Tindakan Berdasarkan Alur Bisnis -->
                <div class="p-2.5 sm:p-4 pt-0 space-y-2">
                    <!-- Box Harga -->
                    <div class="pt-1.5 border-t border-slate-100">
                        <span class="text-[9px] sm:text-[10px] font-montserrat font-semibold uppercase tracking-wider block text-slate-400">
                            @if($pillarType === 'jasa-pasang')
                                Ongkos Jasa Pasang
                            @elseif($pillarType === 'custom')
                                Estimasi Desain &amp; Bahan
                            @elseif($pillarType === 'aksesoris')
                                Tarif Produk &amp; Pasang
                            @else
                                Tarif Paket Wrapping
                            @endif
                        </span>
                        <div class="text-xs sm:text-base font-audiowide font-bold text-[#ff6b00] leading-tight truncate">
                            @if($hasFixedPrice && $pillarType !== 'custom')
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            @else
                                Custom Quote
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Tindakan: Mengikuti Ketentuan Alur Bisnis -->
                    @if($isWaOnly)
                        {{-- ALUR KHUSUS JASA PASANG & CUSTOM: Direct ke WhatsApp Admin --}}
                        <a href="{{ $waDirectUrl }}"
                           target="_blank" 
                           rel="noopener noreferrer"
                           title="Konsultasikan spesifikasi dan slot via WhatsApp"
                           class="w-full py-1.5 sm:py-2 px-2 sm:px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-montserrat font-bold text-[10px] sm:text-xs uppercase tracking-wider rounded-lg sm:rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/20 active:scale-95 text-center">
                            <i class="ph-bold ph-whatsapp-logo text-xs sm:text-sm"></i>
                            <span>{{ $pillarType === 'custom' ? 'Desain via WA' : 'Konsultasi WA' }}</span>
                        </a>
                    @else
                        {{-- ALUR STANDAR FULL WRAP & AKSESORIS: Booking Langsung + Tambah ke Keranjang --}}
                        <div class="flex items-center gap-1.5 sm:gap-2">
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
                                    <i class="ph-bold ph-shopping-bag text-xs sm:text-sm"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full py-10 text-center bg-slate-50 border border-slate-200 rounded-2xl p-6">
                <p class="text-xs font-questrial text-slate-500">Belum ada paket layanan yang aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Kotak Informasi Konsultasi Alur WhatsApp: Jasa Pasang (Bahan Sendiri) -->
<div id="dash-filter-jasa-info" class="hidden py-8 px-5 sm:px-6 text-center bg-gradient-to-b from-emerald-50/70 to-white border border-emerald-200/90 rounded-2xl max-w-xl mx-auto space-y-3 shadow-sm">
    <div class="w-12 h-12 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
        <i class="ph-bold ph-wrench"></i>
    </div>
    <h4 class="text-sm sm:text-base font-montserrat font-bold text-slate-900">
        Layanan Jasa Pemasangan Wrapping (Bawa Bahan Sendiri)
    </h4>
    <p class="text-xs font-questrial text-slate-600 leading-relaxed max-w-md mx-auto">
        Sudah memiliki bahan stiker decal, PPF, atau scotlite sendiri? Teknisi workshop resmi kami siap melayani jasa pasang presisi, rapi, dan bergaransi pengerjaan untuk kendaraan Anda.
    </p>
    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-2.5">
        <a href="{{ \App\Helpers\WhatsAppHelper::getJasaUrl() }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm shadow-emerald-600/20 active:scale-95 text-center">
            <i class="ph-bold ph-whatsapp-logo text-base"></i>
            <span>Konsultasi Jasa Pasang via WA</span>
        </a>
        <button type="button" 
                onclick="resetCascadingFilters()" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-slate-500 hover:text-slate-800 text-xs font-montserrat font-medium transition-all">
            <i class="ph-bold ph-arrows-counter-clockwise text-xs"></i>
            <span>Reset Filter</span>
        </button>
    </div>
</div>

<!-- Kotak Informasi Konsultasi Alur WhatsApp: Custom Wrapping & Livery Desain Khusus -->
<div id="dash-filter-custom-info" class="hidden py-8 px-5 sm:px-6 text-center bg-gradient-to-b from-purple-50/70 to-white border border-purple-200/90 rounded-2xl max-w-xl mx-auto space-y-3 shadow-sm">
    <div class="w-12 h-12 mx-auto rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-sm">
        <i class="ph-bold ph-paint-brush"></i>
    </div>
    <h4 class="text-sm sm:text-base font-montserrat font-bold text-slate-900">
        Konsultasi Desain &amp; Wrapping Kustom (Custom Request)
    </h4>
    <p class="text-xs font-questrial text-slate-600 leading-relaxed max-w-md mx-auto">
        Punya konsep livery balap, motif striping unik, atau kombinasi bahan spesial (Satin, Chrome, Carbon 5D, Textured)? Diskusikan ide Anda langsung dengan tim desainer dan aplikator kami.
    </p>
    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-2.5">
        <a href="{{ \App\Helpers\WhatsAppHelper::getCustomUrl() }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm shadow-emerald-600/20 active:scale-95 text-center">
            <i class="ph-bold ph-whatsapp-logo text-base"></i>
            <span>Konsultasikan Desain via WA</span>
        </a>
        <button type="button" 
                onclick="resetCascadingFilters()" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-slate-500 hover:text-slate-800 text-xs font-montserrat font-medium transition-all">
            <i class="ph-bold ph-arrows-counter-clockwise text-xs"></i>
            <span>Reset Filter</span>
        </button>
    </div>
</div>

<!-- Empty State saat Kombinasi Filter tidak menghasilkan produk -->
<div id="dash-filter-empty" class="hidden py-10 text-center bg-slate-50 border border-slate-200 rounded-2xl p-6">
    <div class="w-12 h-12 mx-auto rounded-full bg-orange-100 flex items-center justify-center text-[#ff6b00] text-xl mb-3">
        <i class="ph-bold ph-magnifying-glass"></i>
    </div>
    <h4 class="text-sm font-montserrat font-bold text-slate-800">
        Tidak Ditemukan Layanan yang Cocok
    </h4>
    <p class="text-xs font-questrial text-slate-500 mt-1 max-w-md mx-auto" id="empty-filter-desc">
        Kombinasi jenis kendaraan dan pilar kebutuhan yang Anda pilih belum memiliki paket tersedia.
    </p>
    <button type="button" 
            onclick="resetCascadingFilters()" 
            class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white text-xs font-montserrat font-bold transition-all shadow-sm shadow-orange-500/20 active:scale-95">
        <i class="ph-bold ph-arrows-counter-clockwise text-xs"></i>
        <span>Reset ke Semua Layanan</span>
    </button>
</div>

<!-- Banner Menuju Fitur Layanan Lengkap (Katalog) -->
<div class="p-4 sm:p-5 rounded-2xl bg-orange-50/70 border border-orange-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white border border-orange-200 shadow-sm flex items-center justify-center text-[#ff6b00] text-xl shrink-0">
            <i class="ph-bold ph-squares-four"></i>
        </div>
        <div>
            <h4 class="text-xs sm:text-sm font-montserrat font-bold text-slate-900">
                Katalog Lengkap {{ $layanans->count() }} Paket Layanan Workshop
            </h4>
            <p class="text-[11px] sm:text-xs font-questrial text-slate-600 mt-0.5 leading-relaxed">
                Filter lengkap per tipe kendaraan (Mobil &amp; Motor), jasa pasang hemat, dan custom wrapping bergaransi resmi.
            </p>
        </div>
    </div>

    <a href="{{ route('katalog.user') }}"
       class="w-full sm:w-auto shrink-0 px-4 py-2 sm:py-2.5 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all text-center">
        <span>Buka Fitur Layanan</span>
        <i class="ph-bold ph-arrow-right text-xs"></i>
    </a>
</div>

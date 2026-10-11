{{-- ============================================
    BAGIAN: Paket Layanan Pilihan (Highlight 3 Layanan Teratas)
    Deskripsi: Menampilkan 3 paket unggulan workshop di dashboard pelanggan
               dan langsung mengarahkan pengguna ke Fitur Katalog Layanan lengkap.
============================================ --}}
<div class="space-y-6">
    
    <!-- Section Header: "Paket Layanan Pilihan" with orange accent & direct link to Katalog -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200 pb-3 gap-2">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-orange-50 border border-orange-200 text-[#ff6b00] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                <i class="ph-bold ph-star text-xs"></i>
                <span>Highlight Workshop</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-slate-900 tracking-wide">
                <span>Paket Layanan Pilihan</span>
            </h3>
            <p class="text-xs font-questrial text-slate-500">
                Pilihan paket pengerjaan terlaris dan paling diminati langsung dari workshop kami.
            </p>
        </div>
        
        <a href="{{ route('katalog.user') }}" 
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-orange-50 border border-slate-200 hover:border-orange-300 text-xs font-montserrat font-bold text-[#FF6B00] transition-all group shrink-0 active:scale-95 shadow-sm">
            <span>Buka Fitur Layanan Lengkap</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    @php
        $featuredLayanans = $layanans->take(3);
        $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
    @endphp

    <!-- Cards Container: Tepat 3 Kolom Responsif E-Commerce -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 items-stretch" id="top-picks-grid">
        @forelse($featuredLayanans as $idx => $item)
            @php
                $catSlug = strtolower(trim($item->tipe_paket ?? ''));
                $catType = strtolower(trim($item->kategori ?? 'mobil'));
                $catName = strtolower(trim($item->nama_layanan ?? ''));
                $isJasaPasang = ($item->jenis_layanan === 'jasa_pasang') || str_contains($catSlug, 'jasa-pasang') || str_contains($catName, 'jasa pasang');
                $isWrapping   = str_contains($catSlug, 'wrapping') || str_contains($catName, 'full wrap');
                $isCustom     = str_contains($catSlug, 'custom') || str_contains($catSlug, 'partial') || str_contains($catSlug, 'striping');
                $isAksesoris  = in_array($catSlug, ['window-film', 'lighting', 'audio']) || str_contains($catName, 'film') || str_contains($catName, 'biled') || str_contains($catName, 'audio');

                $fotoPath = $item->getOriginal('foto_contoh') ?? $item->foto_contoh ?? null;
                $imgSrc = !empty($fotoPath)
                    ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
                    : asset($fallbackImages[$idx % count($fallbackImages)]);

                $svcSummary = $ratingSummary[$item->id_layanan] ?? null;
                $itemRating = $svcSummary ? (float)$svcSummary['avg'] : 5.0;
                $itemReviewsCount = $svcSummary ? (int)$svcSummary['count'] : 0;
                $hasFixedPrice = (!empty($item->harga) && (float)$item->harga > 0);
            @endphp

            <div class="top-pick-card bg-white border border-slate-200/80 hover:border-orange-400 rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg group shadow-sm relative">

                <div>
                    <!-- Foto Layanan E-Commerce (Aspek 4:3) -->
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100 flex-shrink-0 group/img">
                        <img src="{{ $imgSrc }}" 
                             alt="{{ $item->nama_layanan }}" 
                             class="w-full h-full object-cover object-center transition-transform duration-500 group-hover/img:scale-105"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        <!-- Badges Kiri Atas: Kendaraan -->
                        <div class="absolute top-2.5 left-2.5 z-10">
                            <span class="text-[10px] font-montserrat font-bold uppercase px-2 py-0.5 rounded-md backdrop-blur-md bg-black/75 text-white border border-white/20 shadow-sm">
                                {{ $catType === 'motor' ? '🛵 Motor' : '🚗 Mobil' }}
                            </span>
                        </div>

                        <!-- Badges Kanan Atas: Tipe Layanan -->
                        <div class="absolute top-2.5 right-2.5 z-10">
                            @if($isJasaPasang)
                                <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-emerald-500/40 bg-emerald-950/85 text-emerald-300 shadow-sm">
                                    ✂️ Jasa Pasang
                                </span>
                            @elseif($isAksesoris)
                                <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-cyan-500/40 bg-cyan-950/85 text-cyan-300 shadow-sm">
                                    💡 Aksesoris
                                </span>
                            @elseif($isCustom)
                                <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-purple-500/40 bg-purple-950/85 text-purple-300 shadow-sm">
                                    🎨 Custom
                                </span>
                            @else
                                <span class="text-[9px] font-mono font-bold tracking-wider uppercase px-2 py-0.5 rounded-md backdrop-blur-md border border-orange-500/40 bg-orange-950/85 text-orange-300 shadow-sm">
                                    📦 Full Wrap
                                </span>
                            @endif
                        </div>

                        <!-- Estimasi Waktu -->
                        @if($item->estimasi_waktu)
                        <div class="absolute bottom-2 left-2 z-10">
                            <span class="inline-flex items-center gap-1 text-[10px] font-montserrat font-bold text-white bg-black/70 backdrop-blur-md px-2 py-0.5 rounded-md border border-white/10">
                                <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                                <span>{{ $item->estimasi_waktu }}</span>
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Detail Konten Layanan -->
                    <div class="p-4 sm:p-4.5 space-y-2">
                        <!-- Subtitle Kategori -->
                        <div class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-slate-500">
                            {{ $catType === 'motor' ? 'MOTOR' : 'MOBIL' }} • {{ $isJasaPasang ? 'JASA PASANG' : ($isCustom ? 'CUSTOM' : ($isAksesoris ? 'AKSESORIS' : 'FULL WRAP')) }}
                        </div>

                        <!-- Judul Layanan -->
                        <h4 class="text-sm sm:text-base font-montserrat font-bold text-slate-900 group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-2 min-h-[2.5rem]">
                            {{ $item->nama_layanan }}
                        </h4>

                        <!-- Rating & Ulasan -->
                        <div class="flex items-center gap-1.5 pt-0.5">
                            @if($itemReviewsCount > 0)
                                <div class="flex items-center gap-1 text-xs text-[#ff6b00] font-montserrat font-bold">
                                    <i class="ph-fill ph-star text-xs"></i>
                                    <span>{{ number_format((float) $svcSummary['avg'], 1, ',', '.') }}</span>
                                </div>
                                <span class="text-xs text-slate-500 font-questrial">
                                    ({{ $itemReviewsCount }} ulasan)
                                </span>
                            @else
                                <div class="flex items-center gap-1 text-xs text-slate-500 font-questrial">
                                    <i class="ph-fill ph-seal-check text-[#ff6b00] text-xs"></i>
                                    <span>Garansi Resmi Workshop</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bagian Bawah: Harga Penuh & Tombol Aksi -->
                <div class="p-4 sm:p-4.5 pt-0 space-y-2.5">
                    <!-- Box Harga (Full Width - Tidak Pernah Terpotong) -->
                    <div class="pt-2.5 border-t border-slate-100">
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

                    <!-- Tombol Aksi E-Commerce -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('booking.create', ['layanan_id' => $item->id_layanan]) }}"
                           class="flex-1 py-2.5 px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/20 active:scale-95">
                            <i class="ph-bold ph-calendar-plus text-sm"></i>
                            <span>Booking</span>
                        </a>

                        <form action="{{ route('keranjang.tambah') }}" method="POST" class="shrink-0">
                            @csrf
                            <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                            <input type="hidden" name="jumlah" value="1">
                            <button type="submit" 
                                    title="Tambah ke Keranjang"
                                    class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-orange-50 border border-slate-200 hover:border-orange-300 flex items-center justify-center text-slate-600 hover:text-[#ff6b00] transition-all active:scale-95 shadow-sm">
                                <i class="ph-bold ph-shopping-bag-open text-base"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-10 text-center bg-slate-50 border border-slate-200 rounded-2xl p-6">
                <p class="text-xs font-questrial text-slate-500">Belum ada paket layanan yang aktif saat ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Banner Menuju Fitur Layanan Lengkap (Katalog) -->
    <div class="p-5 sm:p-6 rounded-2xl bg-orange-50/70 border border-orange-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-white border border-orange-200 shadow-sm flex items-center justify-center text-[#ff6b00] text-2xl shrink-0">
                <i class="ph-bold ph-squares-four"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-base font-montserrat font-bold text-slate-900">
                    Lihat Semua {{ $layanans->count() }} Paket Layanan Workshop
                </h4>
                <p class="text-xs font-questrial text-slate-600 mt-0.5 leading-relaxed">
                    Filter lengkap per tipe kendaraan (Mobil &amp; Motor), jasa pasang hemat, dan custom wrapping bergaransi.
                </p>
            </div>
        </div>

        <a href="{{ route('katalog.user') }}"
           class="shrink-0 px-5 py-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider flex items-center gap-2 shadow-md shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all">
            <span>Buka Fitur Layanan</span>
            <i class="ph-bold ph-arrow-right text-sm"></i>
        </a>
    </div>

</div>

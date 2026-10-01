{{-- ============================================
    BAGIAN: Grid Paket Layanan Katalog
    Deskripsi: Grid kartu layanan responsif (1 kol HP, 2 kol tablet, 4 kol desktop)
    Desain diselaraskan 100% dengan landing page /layanan
============================================ --}}
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-white/5 pb-4">
        <div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                {{ $profil->section_paket_kami ?? 'Pilihan Paket Layanan' }}
            </h3>
            <p class="text-xs font-questrial text-gray-400 mt-0.5">
                Pilih paket pengerjaan yang sesuai dengan kebutuhan kendaraan Anda.
            </p>
        </div>
        <div class="text-xs font-montserrat font-bold text-[#ff6b00]">
            <span>Total: {{ $layanan->count() }} Paket Tersedia</span>
        </div>
    </div>

    @if($layanan->isNotEmpty())
        @php
            $paketLabels = [
                'wrapping'    => 'Variasi',
                'window-film' => 'Kaca Film',
                'audio'       => 'Audio',
                'lighting'    => 'Lampu',
                'striping'    => 'Striping',
            ];
            $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
        @endphp

        <!-- 4-Column Responsive Grid Identik dengan /layanan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch" id="catalog-grid">
            @foreach($layanan as $idx => $item)
                @php
                    $catSlug = strtolower(trim($item->tipe_paket ?? ''));
                    $catType = strtolower(trim($item->kategori ?? ''));
                    $catName = strtolower(trim($item->nama_layanan ?? ''));
                    $categoryData = "all {$catSlug} {$catType} {$catName}";

                    $fotoPath = $item->getOriginal('foto_contoh') ?? $item->foto_contoh ?? null;
                    $imgSrc = !empty($fotoPath)
                        ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
                        : asset($fallbackImages[$idx % count($fallbackImages)]);

                    $badge = strtoupper($paketLabels[$item->tipe_paket] ?? ($item->tipe_paket ?: ($item->kategori ?: 'LAYANAN')));

                    $fiturArr = [];
                    if (!empty($item->fitur)) {
                        if (is_array($item->fitur)) {
                            foreach ($item->fitur as $f) {
                                $fiturArr[] = is_array($f) ? ($f['nama_fitur'] ?? ($f[0] ?? '')) : $f;
                            }
                        } elseif (is_string($item->fitur)) {
                            $decoded = json_decode($item->fitur, true);
                            $fiturArr = is_array($decoded) ? $decoded : [$item->fitur];
                        }
                    }

                    $svcSummary = $ratingSummary[$item->id_layanan] ?? null;
                    $isFeatured = ($idx === 0);
                @endphp

                <div class="katalog-item bg-gradient-to-b from-[#161616] to-[#0f0f0f] border border-white/[0.08] rounded-2xl overflow-hidden flex flex-col transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(255,107,0,0.18)] hover:border-[rgba(255,107,0,0.4)] group shadow-xl {{ $isFeatured ? 'ring-1 ring-[#ff6b00]/25' : '' }}"
                     data-category="{{ $categoryData }}">

                    <!-- Image Section -->
                    <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0 group/img">
                        <img src="{{ $imgSrc }}"
                             alt="{{ $item->nama_layanan }}"
                             class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] group-hover/img:scale-105"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-transparent to-transparent"></div>

                        <!-- Badge Tipe Paket -->
                        <div class="absolute top-3.5 right-3.5 z-10">
                            <span class="text-[0.62rem] font-montserrat font-bold tracking-[0.12em] uppercase px-3 py-1.5 rounded-full backdrop-blur-md border border-[#ff6b00]/30 bg-[#ff6b00]/15 text-[#ff6b00] shadow-sm">
                                {{ $badge }}
                            </span>
                        </div>

                        <!-- Estimasi Waktu -->
                        @if($item->estimasi_waktu)
                        <div class="absolute bottom-3 left-3 z-10">
                            <span class="inline-flex items-center gap-1 text-[10px] font-montserrat font-bold text-gray-300 bg-black/75 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/10 shadow-sm">
                                <i class="ph-bold ph-clock text-[#ff6b00] text-xs"></i>
                                <span>{{ $item->estimasi_waktu }}</span>
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="flex flex-col flex-1 p-5 sm:p-6 gap-3.5 justify-between">
                        <div class="space-y-3">
                            <!-- Title -->
                            <h4 class="katalog-title text-base sm:text-lg font-audiowide font-bold text-white group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-1">
                                {{ $item->nama_layanan }}
                            </h4>

                            <!-- Rating & Ulasan -->
                            @if($svcSummary && $svcSummary['count'] > 0)
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[#ff6b00] font-montserrat font-bold text-xs flex items-center gap-1">
                                        <i class="ph-fill ph-star text-xs"></i> {{ number_format((float) $svcSummary['avg'], 1, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-questrial">({{ $svcSummary['count'] }} ulasan)</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1 text-[11px] text-gray-500 font-questrial">
                                    <i class="ph-fill ph-star text-amber-500/60 text-xs"></i> Layanan Unggulan
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-lg sm:text-xl font-black font-audiowide text-[#ff6b00]">
                                    @if($item->tipe_layanan == 'fix' || $item->harga > 0)
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    @else
                                        Custom Quote
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500 font-medium font-questrial">/unit</span>
                            </div>

                            <!-- Description -->
                            @if(!empty($item->deskripsi))
                                <p class="katalog-desc text-gray-400 text-xs leading-relaxed line-clamp-3 font-questrial">
                                    {!! strip_tags($item->deskripsi) !!}
                                </p>
                            @endif

                            <div class="border-t border-white/[0.06] my-1"></div>

                            <!-- Features List (Checklist Centang Oranye) -->
                            @if(!empty($fiturArr))
                                <ul class="space-y-2 font-questrial">
                                    @foreach(array_slice($fiturArr, 0, 4) as $fitur)
                                        @if(!empty($fitur))
                                            <li class="flex items-start gap-2 text-[0.78rem] text-gray-300">
                                                <svg class="w-3.5 h-3.5 text-[#ff6b00] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="truncate">{{ $fitur }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- CTA Actions (Dual Button) -->
                        <div class="flex items-center gap-2 pt-3 border-t border-white/5 mt-4">
                            @auth
                                <a href="{{ route('booking.create', ['layanan_id' => $item->id_layanan]) }}"
                                   class="flex-1 py-3 px-3.5 bg-[#ff6b00] hover:bg-[#e05d00] text-black font-montserrat font-extrabold text-[11px] sm:text-xs tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-[0_4px_16px_rgba(255,107,0,0.3)] active:scale-95">
                                    <i class="ph-bold ph-calendar-plus text-sm"></i>
                                    <span>Booking Jadwal</span>
                                </a>

                                <form action="{{ route('keranjang.tambah') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit"
                                            title="Tambah ke Keranjang"
                                            class="w-11 h-11 rounded-xl bg-white/5 hover:bg-[#ff6b00]/15 border border-white/10 hover:border-[#ff6b00]/40 flex items-center justify-center text-gray-300 hover:text-[#ff6b00] transition-all active:scale-95">
                                        <i class="ph-bold ph-shopping-bag-open text-base"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" onclick="showRegisterPrompt()"
                                        class="flex-1 py-3 px-3.5 bg-[#ff6b00] hover:bg-[#e05d00] text-black font-montserrat font-extrabold text-[11px] sm:text-xs tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-[0_4px_16px_rgba(255,107,0,0.3)] active:scale-95">
                                    <i class="ph-bold ph-calendar-plus text-sm"></i>
                                    <span>Booking Jadwal</span>
                                </button>
                                <button type="button" onclick="showRegisterPrompt()"
                                        title="Tambah ke Keranjang"
                                        class="w-11 h-11 rounded-xl bg-white/5 hover:bg-[#ff6b00]/15 border border-white/10 hover:border-[#ff6b00]/40 flex items-center justify-center text-gray-300 hover:text-[#ff6b00] transition-all active:scale-95">
                                    <i class="ph-bold ph-shopping-bag-open text-base"></i>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State (Ketika Filter Pencarian Tidak Ditemukan) -->
        <div id="catalog-empty-state"
             style="display: none;"
             class="w-full py-12 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl sm:rounded-3xl shadow-xl my-4">
            <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-3">
                <i class="ph-bold ph-archive-box text-2xl text-[#FF6B00]"></i>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span>Layanan Tidak Ditemukan</span>
            </div>
            <h4 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                Paket Layanan Belum Tersedia
            </h4>
            <p class="text-xs font-questrial text-[#8A8D93] max-w-md mx-auto mt-1 leading-relaxed">
                Tidak ada paket layanan yang cocok dengan kata kunci atau filter kategori yang Anda pilih.
            </p>
            <div class="mt-4 flex items-center gap-2.5 flex-wrap justify-center">
                <button type="button"
                        onclick="filterKatalog('all')"
                        class="px-4 py-2 min-h-[40px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#FF6B00]/25 active:scale-95">
                    Lihat Semua Layanan
                </button>
            </div>
        </div>
    @else
        <!-- Empty State ketika memang belum ada paket sama sekali di database -->
        <div class="py-12 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl sm:rounded-3xl shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-3">
                <i class="ph-bold ph-archive-box text-2xl text-[#FF6B00]"></i>
            </div>
            <h4 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                Belum Ada Paket Layanan
            </h4>
            <p class="text-xs font-questrial text-[#8A8D93] max-w-md mx-auto mt-1 leading-relaxed">
                Admin belum menambahkan paket layanan saat ini atau stok material sedang habis di workshop kami.
            </p>
        </div>
    @endif
</div>

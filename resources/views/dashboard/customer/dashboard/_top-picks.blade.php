{{-- ============================================
    BAGIAN: Paket Layanan Grid di User Dashboard
    Deskripsi: Grid kartu layanan responsif (1 kol HP, 2 kol tablet, 4 kol desktop)
    Desain diselaraskan 100% dengan landing page /layanan & katalog
============================================ --}}
<div class="space-y-5">
    
    <!-- Section Header: "Paket Layanan" with orange accent & "Lihat Semua ->" -->
    <div class="flex items-end justify-between border-b border-white/5 pb-3">
        <div class="space-y-1">
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                <span>Paket Layanan Pilihan</span>
            </h3>
            <p class="text-xs font-questrial text-gray-400">
                Pilih paket pengerjaan atau modifikasi kendaraan favorit Anda langsung dari workshop.
            </p>
        </div>
        
        <a href="{{ route('katalog.user') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#E05D00] transition-colors group">
            <span>Buka Katalog Lengkap</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <!-- Filter Kategori (Di Atas Kartu Paket Layanan) -->
    @include('dashboard.customer.dashboard._search-categories')

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

    <!-- Cards Container: 4-Column Responsive Grid Identik dengan /layanan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch pt-1" id="top-picks-grid">
        @forelse($layanans as $idx => $item)
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

            <div class="top-pick-card bg-[#121212] border border-white/[0.08] rounded-2xl overflow-hidden flex flex-col transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(255,107,0,0.18)] hover:border-[rgba(255,107,0,0.4)] group shadow-xl {{ $isFeatured ? 'ring-1 ring-[#ff6b00]/25' : '' }}"
                 data-category="{{ $categoryData }}">

                <!-- Card Image Box -->
                <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0 group/img">
                    <img src="{{ $imgSrc }}" 
                         alt="{{ $item->nama_layanan }}" 
                         class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] group-hover/img:scale-105"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-transparent to-transparent"></div>

                    <!-- Kategori Tipe Paket (Clean Text) -->
                    <div class="absolute top-3.5 right-3.5 z-10">
                        <span class="text-[0.65rem] font-montserrat font-bold tracking-[0.12em] uppercase text-[#ff6b00] drop-shadow-md">
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

                <!-- Card Content -->
                <div class="flex flex-col flex-1 p-5 sm:p-6 gap-3.5 justify-between">
                    <div class="space-y-3">
                        <!-- Title -->
                        <h4 class="text-base sm:text-lg font-audiowide font-bold text-white group-hover:text-[#ff6b00] transition-colors leading-snug line-clamp-1">
                            {{ $item->nama_layanan }}
                        </h4>

                        <!-- Rating Summary -->
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
                            <p class="text-gray-400 text-xs leading-relaxed line-clamp-3 font-questrial">
                                {!! strip_tags($item->deskripsi) !!}
                            </p>
                        @endif

                        <div class="border-t border-white/[0.06] my-1"></div>

                        <!-- Key Features Checklist (Orange Checkmarks) -->
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

                    <!-- Dual Actions: Booking Jadwal + Keranjang -->
                    <div class="flex items-center gap-2 pt-3 border-t border-white/5 mt-4">
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
                    </div>
                </div>

            </div>
        @empty
            <div class="w-full col-span-full py-10 text-center bg-[#0E0E10] border border-white/10 rounded-2xl p-6">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <span>Stok Habis / Belum Diisi</span>
                </div>
                <h4 class="text-sm font-montserrat font-bold text-white mb-1">Belum Ada Paket Layanan</h4>
                <p class="text-xs font-questrial text-[#8A8D93]">Admin belum menambahkan paket layanan atau stok material saat ini sedang habis.</p>
            </div>
        @endforelse

        <!-- Empty State (Stok Habis) Saat Kategori Filter Kosong / Belum Diisi -->
        <div id="top-picks-empty-state"
             style="display: none;"
             class="w-full col-span-full py-10 sm:py-12 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl shadow-xl transition-all my-2">
            <div class="w-12 h-12 rounded-xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-2.5">
                <i class="ph-bold ph-archive-box text-xl text-[#FF6B00]"></i>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[9px] font-montserrat font-bold uppercase tracking-wider mb-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span>Stok Habis / Belum Diisi</span>
            </div>
            <p class="text-xs font-montserrat font-bold text-white">
                Paket Kategori Ini Belum Tersedia
            </p>
            <p class="text-[11px] font-questrial text-[#8A8D93] mt-0.5 max-w-sm mx-auto leading-relaxed">
                Layanan untuk kategori ini saat ini belum diisi atau stok material sedang habis di workshop kami.
            </p>
            <button type="button"
                    onclick="applyCategoryFilter('all', document.querySelector('.cat-filter-btn'))"
                    class="mt-3 px-4 py-2 text-[10px] font-montserrat font-bold uppercase tracking-wider text-black bg-[#FF6B00] hover:bg-[#E05D00] rounded-xl transition-all active:scale-95 shadow-md shadow-[#FF6B00]/25">
                Lihat Semua Paket
            </button>
        </div>

    </div>
</div>

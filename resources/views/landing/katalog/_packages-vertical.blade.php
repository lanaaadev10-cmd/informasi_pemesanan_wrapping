{{-- ============================================
    BAGIAN: Paket Layanan Horizontal Carousel
    Deskripsi: Replikasi kartu layanan dashboard (gradient, tombol navigasi hover,
    harga inline) + scroll vertikal internal untuk deskripsi & fitur
    Digunakan untuk halaman katalog (guest & login)
============================================ --}}
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">{{ $profil->section_paket_kami ?? 'Paket Layanan Kami' }}</h3>
        <a href="{{ route('katalog.user') }}" class="text-xs font-montserrat font-bold text-[#ff6b00] uppercase tracking-wider hover:underline flex items-center gap-1">
            Lihat Semua <i class="ph-bold ph-caret-right text-xs"></i>
        </a>
    </div>

    @if($layanan->isNotEmpty())
    <!-- Carousel Container -->
    <div class="relative group">
        <!-- Slider Wrapper -->
        <div class="overflow-hidden rounded-2xl">
            <div class="packages-carousel-wrapper flex gap-4 sm:gap-6 pb-4 scroll-smooth overflow-x-auto snap-x snap-mandatory"
                 style="scroll-behavior: smooth;">

                @forelse($layanan as $index => $package)
                @php
                    $fotoPath = $package->getOriginal('foto_contoh') ?? null;
                    $imgSrc = null;
                    if (!empty($fotoPath) && (file_exists(public_path($fotoPath)) || file_exists(public_path('storage/' . $fotoPath)) || str_starts_with($fotoPath, 'http'))) {
                        $imgSrc = \App\Helpers\StaticContent::fotoUrl($fotoPath);
                    } else {
                        $localFallbacks = [
                            'variasi mobil' => asset('images/layanan/wrapping-mobil.jpg'),
                            'wrapping'      => asset('images/layanan/wrapping-mobil.jpg'),
                            'kaca film'     => asset('images/layanan/kaca-film.jpg'),
                            'audio'         => asset('images/layanan/audio-head.jpg'),
                            'audio mobil'   => asset('images/layanan/audio-head.jpg'),
                            'lampu biled'   => asset('images/layanan/lampu-biled.jpg'),
                            'striping'      => asset('images/layanan/layanan-striping.jpg'),
                        ];
                        $matchKey = strtolower(trim($package->nama_layanan ?? ''));
                        $catKey   = strtolower(trim($package->kategori ?? ''));
                        $imgSrc   = $localFallbacks[$matchKey] ?? ($localFallbacks[$catKey] ?? asset('images/layanan/wrapping-mobil.jpg'));
                    }
                    $packageSummary = $ratingSummary[$package->id_layanan] ?? null;
                @endphp
                <div class="packages-carousel-item katalog-item flex-shrink-0 w-[82vw] max-w-[320px] sm:w-80 snap-start" data-category="{{ strtolower(trim(($package->tipe_paket ?? '') . ' ' . ($package->kategori ?? ''))) }}">
                    <!-- Card Package -->
                    <div class="h-full bg-[#141414] border border-white/10 rounded-2xl overflow-hidden group/card hover:border-[#ff6b00]/50 transition-all duration-300 shadow-xl hover:shadow-[0_8px_30px_rgba(255,107,0,0.15)] flex flex-col justify-between">

                        <!-- Image Section -->
                        <div class="relative h-40 sm:h-48 bg-gradient-to-br from-[#ff6b00]/20 to-transparent overflow-hidden">
                            <img src="{{ $imgSrc }}"
                                 alt="{{ $package->nama_layanan }}"
                                 class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-500">

                            <!-- Badge Tipe Paket -->
                            @if($package->tipe_paket)
                            <div class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 bg-[#ff6b00] px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full shadow-md">
                                <p class="text-[9px] font-montserrat font-bold text-white uppercase tracking-wider">
                                    {{ $package->tipe_paket }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-3.5 sm:p-5 flex flex-col justify-between flex-1">
                            <div>
                                <!-- Title -->
                                <h4 class="katalog-title text-sm sm:text-base font-audiowide font-bold text-white group-hover/card:text-[#ff6b00] transition-colors tracking-wide truncate">
                                    {{ $package->nama_layanan }}
                                </h4>

                                <!-- Rating Summary -->
                                @if($packageSummary && $packageSummary['count'] > 0)
                                <div class="flex items-center gap-1.5 mt-1 sm:mt-2">
                                    <span class="text-[#ff6b00] font-montserrat font-bold text-xs flex items-center gap-1">
                                        <i class="ph-fill ph-star text-xs"></i> {{ number_format((float) $packageSummary['avg'], 1, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] font-questrial text-gray-500">({{ $packageSummary['count'] }} ulasan)</span>
                                </div>
                                @endif

                                <!-- Deskripsi & Fitur — Scroll Vertikal Internal -->
                                <div class="katalog-scroll-area mt-2.5 sm:mt-3 max-h-[100px] sm:max-h-[120px] overflow-y-auto pr-1 space-y-1.5 sm:space-y-2">
                                    <p class="katalog-desc text-xs font-questrial text-gray-400 leading-relaxed line-clamp-2 sm:line-clamp-none">
                                        {!! $package->deskripsi ?? 'Deskripsi layanan premium wrapping dan modifikasi estetika kendaraan.' !!}
                                    </p>

                                    <!-- Features -->
                                    @if($package->fitur && is_array($package->fitur) && count($package->fitur) > 0)
                                    <div class="space-y-1 sm:space-y-1.5 pt-2 border-t border-white/5 font-questrial">
                                        @foreach(array_slice($package->fitur, 0, 4) as $fitur)
                                        <div class="flex items-center gap-1.5 sm:gap-2">
                                            <i class="ph-bold ph-check-circle text-[#ff6b00] text-xs shrink-0"></i>
                                            <span class="text-[11px] text-gray-300 truncate">{{ is_array($fitur) ? ($fitur['nama_fitur'] ?? ($fitur[0] ?? '')) : $fitur }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Price & Buttons -->
                            <div class="space-y-2.5 sm:space-y-3 border-t border-white/10 pt-3 sm:pt-4 mt-3 sm:mt-4">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-lg sm:text-2xl font-audiowide font-bold text-[#ff6b00] truncate">
                                        @if($package->tipe_layanan == 'fix')
                                             Rp {{ number_format($package->harga, 0, ',', '.') }}
                                        @else
                                            {{ $profil->katalog_harga_custom_label ?? 'Custom Pricing' }}
                                        @endif
                                    </span>
                                    @if($package->estimasi_waktu)
                                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 bg-white/5 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
                                        <i class="ph-bold ph-clock text-xs text-[#ff6b00]"></i>
                                        <span>{{ $package->estimasi_waktu }}</span>
                                    </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    @auth
                                        <form action="{{ route('keranjang.tambah') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="id_paket" value="{{ $package->id_layanan }}">
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit"
                                                    class="w-full py-2 sm:py-2.5 px-2 sm:px-3 bg-[#ff6b00]/10 border border-[#ff6b00]/30 text-[#ff6b00] rounded-xl text-[11px] sm:text-xs font-montserrat font-bold uppercase tracking-wider hover:bg-[#ff6b00]/20 transition-all duration-200 flex items-center justify-center gap-1 active:scale-95 min-h-[40px] sm:min-h-[44px]">
                                                <i class="ph-bold ph-shopping-cart-simple text-sm shrink-0"></i> <span>Keranjang</span>
                                            </button>
                                        </form>
                                        <a href="{{ route('pesanan.direct-order', ['package_id' => $package->id_layanan]) }}"
                                           class="flex-1 py-2 sm:py-2.5 px-2 sm:px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white rounded-xl text-[11px] sm:text-xs font-montserrat font-bold uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1 shadow-md shadow-[#ff6b00]/25 active:scale-95 min-h-[40px] sm:min-h-[44px]">
                                            <i class="ph-bold ph-lightning text-sm shrink-0"></i> <span>{{ $profil->cta_pesan ?? 'Pesan' }}</span>
                                        </a>
                                    @else
                                        <button type="button" onclick="showRegisterPrompt()"
                                                class="flex-1 py-2 sm:py-2.5 px-2 sm:px-3 bg-[#ff6b00]/10 border border-[#ff6b00]/30 text-[#ff6b00] rounded-xl text-[11px] sm:text-xs font-montserrat font-bold uppercase tracking-wider hover:bg-[#ff6b00]/20 transition-all duration-200 flex items-center justify-center gap-1 active:scale-95 min-h-[40px] sm:min-h-[44px]">
                                            <i class="ph-bold ph-shopping-cart-simple text-sm shrink-0"></i> <span>Keranjang</span>
                                        </button>
                                        <button type="button" onclick="showRegisterPrompt()"
                                                class="flex-1 py-2 sm:py-2.5 px-2 sm:px-3 bg-[#ff6b00] hover:bg-[#ea580c] text-white rounded-xl text-[11px] sm:text-xs font-montserrat font-bold uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-1 shadow-md shadow-[#ff6b00]/25 active:scale-95 min-h-[40px] sm:min-h-[44px]">
                                            <i class="ph-bold ph-lightning text-sm shrink-0"></i> <span>{{ $profil->cta_pesan ?? 'Pesan' }}</span>
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full py-12 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl sm:rounded-3xl shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-3">
                        <i class="ph-bold ph-archive-box text-2xl text-[#FF6B00]"></i>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <span>Stok Habis / Belum Diisi</span>
                    </div>
                    <h4 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                        Katalog Layanan Belum Tersedia
                    </h4>
                    <p class="text-xs font-questrial text-[#8A8D93] max-w-md mx-auto mt-1 leading-relaxed">
                        Belum ada paket layanan yang terdaftar saat ini atau stok material sedang habis di workshop kami.
                    </p>
                </div>
                @endforelse

                <!-- Empty State (Stok Habis) Ketika Kategori Terfilter Belum Diisi / Kosong -->
                <div id="catalog-empty-state"
                     style="display: none;"
                     class="w-full py-10 sm:py-14 px-5 sm:px-8 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl sm:rounded-3xl shadow-xl transition-all my-2">
                    <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-3">
                        <i class="ph-bold ph-archive-box text-2xl text-[#FF6B00]"></i>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span>Stok Habis / Belum Diisi</span>
                    </div>
                    <h4 class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wide">
                        Layanan Belum Tersedia
                    </h4>
                    <p class="text-xs font-questrial text-[#8A8D93] max-w-md mx-auto mt-1 leading-relaxed">
                        Katalog layanan untuk kategori ini saat ini belum diisi atau stok material sedang habis di workshop kami.
                    </p>
                    <div class="mt-4 flex items-center gap-2.5 flex-wrap justify-center">
                        <button type="button"
                                onclick="filterKatalog('all')"
                                class="px-4 py-2 min-h-[40px] bg-[#FF6B00] hover:bg-[#E05D00] text-black font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#FF6B00]/25 active:scale-95">
                            Lihat Semua Layanan
                        </button>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}"
                           target="_blank"
                           class="px-4 py-2 min-h-[40px] bg-[#16161A] hover:bg-white/10 border border-white/10 text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-xl transition-all active:scale-95 inline-flex items-center gap-1.5">
                            <i class="ph-bold ph-whatsapp-logo text-emerald-400 text-sm"></i>
                            <span>Tanya Stok CS</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        @if($layanan->count() > 1)
        <button class="carousel-prev hidden sm:flex items-center justify-center absolute -left-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-[#141414] border border-white/20 hover:border-[#ff6b00] text-white hover:text-[#ff6b00] rounded-full transition-all duration-200 shadow-xl hover:scale-110 active:scale-95"
                aria-label="Scroll left">
            <i class="ph-bold ph-caret-left text-base"></i>
        </button>
        <button class="carousel-next hidden sm:flex items-center justify-center absolute -right-3 top-1/2 -translate-y-1/2 z-10 w-9 h-9 bg-[#141414] border border-white/20 hover:border-[#ff6b00] text-white hover:text-[#ff6b00] rounded-full transition-all duration-200 shadow-xl hover:scale-110 active:scale-95"
                aria-label="Scroll right">
            <i class="ph-bold ph-caret-right text-base"></i>
        </button>
        @endif
    </div>
    @else
    <!-- Empty State (fallback) -->
    <div class="py-12 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl sm:rounded-3xl shadow-xl">
        <div class="w-14 h-14 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-3">
            <i class="ph-bold ph-archive-box text-2xl text-[#FF6B00]"></i>
        </div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
            <span>Stok Habis / Belum Diisi</span>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle carousel navigation
        document.querySelectorAll('.packages-carousel-wrapper').forEach(wrapper => {
            const parentGroup = wrapper.closest('.relative.group');
            if (!parentGroup) return;

            const prevBtn = parentGroup.querySelector('.carousel-prev');
            const nextBtn = parentGroup.querySelector('.carousel-next');

            if (!prevBtn || !nextBtn) return;

            const scroll = (direction) => {
                const scrollAmount = 350;
                wrapper.scrollBy({
                    left: direction === 'next' ? scrollAmount : -scrollAmount,
                    behavior: 'smooth'
                });
            };

            prevBtn.addEventListener('click', () => scroll('prev'));
            nextBtn.addEventListener('click', () => scroll('next'));
        });
    });
</script>

<style>
    .packages-carousel-wrapper {
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .packages-carousel-wrapper::-webkit-scrollbar {
        display: none;
    }

    .packages-carousel-item {
        scroll-snap-align: start;
    }

    /* Scrollbar oranye tipis untuk area deskripsi & fitur dalam kartu */
    .katalog-scroll-area {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 107, 0, 0.35) transparent;
    }
    .katalog-scroll-area::-webkit-scrollbar {
        width: 4px;
    }
    .katalog-scroll-area::-webkit-scrollbar-track {
        background: transparent;
    }
    .katalog-scroll-area::-webkit-scrollbar-thumb {
        background: rgba(255, 107, 0, 0.35);
        border-radius: 3px;
    }
</style>
@endpush

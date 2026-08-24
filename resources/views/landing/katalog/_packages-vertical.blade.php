{{-- ============================================
    BAGIAN: Paket Layanan Horizontal Carousel
    Deskripsi: Replikasi kartu layanan dashboard (gradient, tombol navigasi hover,
    harga inline) + scroll vertikal internal untuk deskripsi & fitur
    Digunakan untuk halaman katalog (guest & login)
============================================ --}}
<div class="space-y-4">
    @php
        // Helper untuk fallback image
        if (!function_exists('getFallbackImage')) {
            function getFallbackImage($index) {
                $fallbacks = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;
                return $fallbacks[$index % count($fallbacks)];
            }
        }
    @endphp

    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->section_paket_kami ?? 'Paket Layanan Kami' }}</h3>
        <a href="{{ route('katalog.user') }}" class="text-[10px] font-bold text-[#f2994a] uppercase tracking-widest hover:underline flex items-center gap-1">
            Lihat Semua <i class="ph-bold ph-caret-right text-xs"></i>
        </a>
    </div>

    @if($layanan->isNotEmpty())
    <!-- Carousel Container -->
    <div class="relative group">
        <!-- Slider Wrapper -->
        <div class="overflow-hidden rounded-2xl">
            <div class="packages-carousel-wrapper flex gap-6 pb-2 scroll-smooth overflow-x-auto"
                 style="scroll-behavior: smooth;">

                @forelse($layanan as $index => $package)
                @php
                    $packageImage = $package->foto_contoh ? asset('storage/' . $package->foto_contoh) : getFallbackImage($index);
                @endphp
                <div class="packages-carousel-item katalog-item flex-shrink-0 w-80" data-category="{{ strtolower($package->kategori) }}">
                    <!-- Card Package -->
                    <div class="h-full bg-gradient-to-br from-white/[0.08] to-white/[0.02] border border-white/10 rounded-2xl overflow-hidden group/card hover:border-[#f2994a]/50 transition-all duration-300 shadow-lg hover:shadow-xl flex flex-col">

                        <!-- Image Section -->
                        <div class="relative h-48 bg-gradient-to-br from-[#f2994a]/20 to-transparent overflow-hidden">
                            <img src="{{ $packageImage }}"
                                 alt="{{ $package->nama_layanan }}"
                                 class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-300">

                            <!-- Badge Tipe Paket -->
                            @if($package->tipe_paket)
                            <div class="absolute top-3 right-3 bg-[#f2994a]/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                <p class="text-[10px] font-bold text-white uppercase tracking-wider">
                                    {{ $package->tipe_paket }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-4 flex flex-col justify-between flex-1">
                            <!-- Title -->
                            <h4 class="katalog-title text-sm font-bold text-white group-hover/card:text-[#f2994a] transition-colors">
                                {{ $package->nama_layanan }}
                            </h4>

                            <!-- Deskripsi & Fitur — Scroll Vertikal Internal -->
                            <div class="katalog-scroll-area mt-2 max-h-[130px] overflow-y-auto pr-1 space-y-2">
                                <p class="katalog-desc text-xs text-gray-400 leading-relaxed">
                                    {!! $package->deskripsi ?? 'Deskripsi layanan' !!}
                                </p>

                                <!-- Features -->
                                @if($package->fitur && is_array($package->fitur) && count($package->fitur) > 0)
                                <div class="space-y-1 pt-1 border-t border-white/5">
                                    @foreach($package->fitur as $fitur)
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-check-circle text-[#f2994a] text-xs"></i>
                                        <span class="text-[10px] text-gray-300">{{ $fitur }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <!-- Price & Buttons -->
                            <div class="space-y-3 border-t border-white/10 pt-3 mt-auto">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold text-[#f2994a]">
                                        @if($package->tipe_layanan == 'fix')
                                            Rp {{ number_format($package->harga, 0, ',', '.') }}
                                        @else
                                            {{ $profil->katalog_harga_custom_label ?? 'Custom Pricing' }}
                                        @endif
                                    </span>
                                    @if($package->estimasi_waktu)
                                    <span class="text-[10px] text-gray-400 ml-auto flex items-center gap-1">
                                        <i class="ph-bold ph-clock-fill"></i>
                                        {{ $package->estimasi_waktu }}
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
                                                    class="w-full py-2 px-3 bg-[#f2994a]/20 border border-[#f2994a] text-[#f2994a] rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-[#f2994a]/30 transition-all duration-200">
                                                <i class="ph-bold ph-shopping-cart-simple mr-1"></i> Keranjang
                                            </button>
                                        </form>
                                        <a href="{{ route('pesanan.direct-order', ['package_id' => $package->id_layanan]) }}"
                                           class="flex-1 py-2 px-3 bg-[#f2994a] text-white rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-[#f2994a]/90 transition-all duration-200 flex items-center justify-center">
                                            <i class="ph-bold ph-lightning-fill mr-1"></i> {{ $profil->cta_pesan ?? 'Pesan' }}
                                        </a>
                                    @else
                                        <button type="button" onclick="showRegisterPrompt()"
                                                class="flex-1 py-2 px-3 bg-[#f2994a]/20 border border-[#f2994a] text-[#f2994a] rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-[#f2994a]/30 transition-all duration-200">
                                            <i class="ph-bold ph-shopping-cart-simple mr-1"></i> Keranjang
                                        </button>
                                        <button type="button" onclick="showRegisterPrompt()"
                                                class="flex-1 py-2 px-3 bg-[#f2994a] text-white rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-[#f2994a]/90 transition-all duration-200 flex items-center justify-center">
                                            <i class="ph-bold ph-lightning-fill mr-1"></i> {{ $profil->cta_pesan ?? 'Pesan' }}
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="w-full py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-white/[0.02]">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
                        <i class="ph-bold ph-package text-2xl text-[#f2994a]"></i>
                    </div>
                    <h4 class="text-base font-bold text-white mb-1">{{ $profil->katalog_empty_state_title ?? 'Belum Ada Paket Layanan' }}</h4>
                    <p class="text-xs text-gray-500 font-light">{{ $profil->katalog_empty_state_desc ?? 'Admin belum menambahkan paket layanan saat ini.' }}</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Navigation Buttons -->
        @if($layanan->count() > 1)
        <button hidden class="carousel-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-[#f2994a] hover:bg-[#f2994a]/90 text-white rounded-full p-2 transition-all duration-200 opacity-0 group-hover:opacity-100"
                aria-label="Scroll left">
            <i class="ph-bold ph-caret-left text-lg"></i>
        </button>
        <button hidden class="carousel-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-[#f2994a] hover:bg-[#f2994a]/90 text-white rounded-full p-2 transition-all duration-200 opacity-0 group-hover:opacity-100"
                aria-label="Scroll right">
            <i class="ph-bold ph-caret-right text-lg"></i>
        </button>
        @endif
    </div>
    @else
    <!-- Empty State (fallback) -->
    <div class="py-16 text-center border border-dashed border-white/10 rounded-2xl bg-white/[0.01]">
        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
            <i class="ph-bold ph-package text-2xl text-[#f2994a]"></i>
        </div>
        <h4 class="text-base font-bold text-white mb-1">{{ $profil->katalog_empty_state_title ?? 'Belum Ada Paket Layanan' }}</h4>
        <p class="text-xs text-gray-500 font-light">{{ $profil->katalog_empty_state_desc ?? 'Admin belum menambahkan paket layanan saat ini.' }}</p>
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
        scrollbar-color: rgba(242, 153, 74, 0.35) transparent;
    }
    .katalog-scroll-area::-webkit-scrollbar {
        width: 4px;
    }
    .katalog-scroll-area::-webkit-scrollbar-track {
        background: transparent;
    }
    .katalog-scroll-area::-webkit-scrollbar-thumb {
        background: rgba(242, 153, 74, 0.35);
        border-radius: 3px;
    }
</style>
@endpush

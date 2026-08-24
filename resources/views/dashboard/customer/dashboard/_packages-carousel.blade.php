<!-- Paket Layanan Section dengan Carousel -->
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->section_paket_kami ?? 'Paket Layanan Kami' }}</h3>
        <a href="{{ route('katalog.user') }}" class="text-[10px] font-bold text-[#f2994a] uppercase tracking-widest hover:underline flex items-center gap-1">
            Lihat Semua <i class="ph-bold ph-caret-right text-xs"></i>
        </a>
    </div>

    <!-- Carousel Container -->
    <div class="relative group">
        <!-- Slider Wrapper -->
        <div class="overflow-hidden rounded-2xl">
            <div class="packages-carousel-wrapper flex gap-6 pb-2 scroll-smooth overflow-x-auto"
                 style="scroll-behavior: smooth;">

                @forelse($layanans as $package)
                <div class="packages-carousel-item flex-shrink-0 w-80" data-package-id="{{ $package->id_layanan }}">
                    <!-- Card Package -->
                    <div class="h-full bg-gradient-to-br from-white/[0.08] to-white/[0.02] border border-white/10 rounded-2xl overflow-hidden group/card hover:border-[#f2994a]/50 transition-all duration-300 shadow-lg hover:shadow-xl flex flex-col">

                        <!-- Image Section -->
                        <div class="relative h-48 bg-gradient-to-br from-[#f2994a]/20 to-transparent overflow-hidden">
                            @if($package->foto_contoh)
                            <img src="{{ asset('storage/' . $package->foto_contoh) }}"
                                 alt="{{ $package->nama_layanan }}"
                                 class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-300">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-[#f2994a]/30 to-[#f2994a]/10 flex items-center justify-center">
                                <i class="ph-bold ph-package text-5xl text-[#f2994a]/40"></i>
                            </div>
                            @endif

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
                            <!-- Title & Description -->
                            <div class="space-y-2 mb-4">
                                <h4 class="text-sm font-bold text-white line-clamp-2 group-hover/card:text-[#f2994a] transition-colors">
                                    {{ $package->nama_layanan }}
                                </h4>
                                <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ $package->deskripsi ?? 'Deskripsi layanan' }}
                                </p>
                            </div>

                            <!-- Features (if exists) -->
                            @if($package->fitur && is_array($package->fitur) && count($package->fitur) > 0)
                            <div class="mb-4 space-y-1">
                                @foreach(array_slice($package->fitur, 0, 2) as $fitur)
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-check-circle text-[#f2994a] text-xs"></i>
                                    <span class="text-[10px] text-gray-300">{{ $fitur }}</span>
                                </div>
                                @endforeach
                                @if(count($package->fitur) > 2)
                                <p class="text-[9px] text-gray-500 italic">+{{ count($package->fitur) - 2 }} fitur lainnya</p>
                                @endif
                            </div>
                            @endif

                            <!-- Price & Buttons -->
                            <div class="space-y-3 border-t border-white/10 pt-3 mt-auto">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold text-[#f2994a]">
                                        Rp {{ number_format($package->harga, 0, ',', '.') }}
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
                    <h4 class="text-base font-bold text-white mb-1">admin belum menambahkan</h4>
                    <p class="text-xs text-gray-500 font-light">Admin belum menambahkan paket layanan saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
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
    @keyframes slide-in {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }

    .animate-slide-out {
        animation: slide-out 0.3s ease-out;
    }

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
</style>
@endpush

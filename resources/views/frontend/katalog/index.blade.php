@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Katalog Layanan')

@section('content')
<div class="{{ auth()->check() ? 'max-w-6xl mx-auto' : '' }}">
    <!-- Hero Section (Dinamis berdasarkan login) -->
    <section class="max-w-7xl mx-auto px-6 pt-12 pb-16 text-center" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">Premium Selection</span>
        <h1 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tighter mb-8">
            {!! $profil->katalog_title ? nl2br(e($profil->katalog_title)) : 'Katalog <span class="text-orange-600 italic">Layanan.</span>' !!}
        </h1>
        <p class="max-w-2xl mx-auto text-gray-500 text-lg leading-relaxed font-medium italic">
            "{{ $profil->katalog_subtitle ?? 'Temukan paket wrapping dan stiker terbaik yang dirancang khusus untuk meningkatkan estetika kendaraan Anda.' }}"
        </p>
    </section>

    <!-- Category Filter Premium -->
    <section class="max-w-7xl mx-auto px-6 mb-16 flex justify-center gap-4 flex-wrap" data-aos="fade-up" data-aos-delay="100">
        <button onclick="filterKatalog('all')" class="filter-btn group relative px-8 py-3 rounded-2xl bg-gray-900 text-white font-bold text-sm transition-all shadow-xl shadow-gray-200" data-category="all">
            <span class="relative z-10">Semua Layanan</span>
        </button>
        @foreach(['Mobil', 'Motor', 'Sepeda'] as $cat)
        <button onclick="filterKatalog('{{ $cat }}')" class="filter-btn group relative px-8 py-3 rounded-2xl bg-white text-gray-500 font-bold text-sm border border-gray-100 hover:border-orange-500 hover:text-orange-600 transition-all shadow-sm" data-category="{{ $cat }}">
            <span class="relative z-10">{{ $cat }}</span>
        </button>
        @endforeach
    </section>

    <!-- Catalog Grid -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12" id="katalog-grid">
            @forelse($layanan as $item)
                <div class="katalog-item group relative bg-white rounded-[2.5rem] border border-gray-100 hover:border-orange-500/30 transition-all duration-700 hover:-translate-y-3 hover:shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden" 
                     data-aos="fade-up" 
                     data-category="{{ $item->kategori }}">
                    
                    <!-- Premium Image Wrapper -->
                    <div class="relative aspect-[1/1] overflow-hidden m-4 rounded-[2rem]">
                        @if($item->foto_contoh)
                            <img src="{{ asset('storage/' . $item->foto_contoh) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="{{ $item->nama_layanan }}">
                        @else
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-200">
                                <i class="ph-fill ph-sketch-logo text-7xl"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge (Glass) -->
                        <div class="absolute top-5 left-5 z-20">
                            <span class="px-5 py-2 bg-white/20 backdrop-blur-xl border border-white/30 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-full shadow-2xl">
                                {{ $item->kategori ?? 'Masterpiece' }}
                            </span>
                        </div>

                        <!-- Hover Overlay Detail -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                             <div class="transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
                                <p class="text-white/70 text-xs font-medium italic leading-relaxed mb-2">
                                    "{{ $item->deskripsi }}"
                                </p>
                             </div>
                        </div>
                    </div>

                    <!-- Enhanced Content Area -->
                    <div class="p-8 pt-2 flex flex-col flex-grow">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight group-hover:text-orange-600 transition-colors">{{ $item->nama_layanan }}</h3>
                            <div class="flex items-center gap-3">
                                <div class="h-[1px] w-8 bg-orange-600/30"></div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Premium Package</span>
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="mt-auto">
                            <div class="flex items-end justify-between mb-8">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Starting Price</span>
                                    <span class="text-3xl font-black text-gray-900 italic tracking-tighter">
                                        @if($item->tipe_layanan == 'fix')
                                            <span class="text-orange-600 text-lg not-italic font-bold mr-1">Rp</span>{{ number_format($item->harga, 0, ',', '.') }}
                                        @else
                                            Custom
                                        @endif
                                    </span>
                                </div>
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-orange-600 hover:text-white hover:shadow-xl hover:shadow-orange-200 transition-all group/cart">
                                        <i class="ph-bold ph-shopping-cart-simple text-2xl group-hover/cart:animate-bounce"></i>
                                    </button>
                                </form>
                            </div>

                            <form action="{{ route('keranjang.tambah') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                <input type="hidden" name="jumlah" value="1">
                                <input type="hidden" name="direct_checkout" value="1">
                                <button type="submit" class="w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-[11px] tracking-[0.2em] uppercase hover:bg-orange-600 hover:shadow-2xl hover:shadow-orange-200 transition-all flex items-center justify-center gap-3 active:scale-95">
                                    BOOK THIS SERVICE <i class="ph-bold ph-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-40 text-center">
                    <div class="w-32 h-32 bg-gray-50 rounded-[3rem] flex items-center justify-center text-gray-100 mx-auto mb-8 shadow-inner">
                        <i class="ph-fill ph-sketch-logo text-7xl"></i>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tighter">Belum Ada Koleksi.</h3>
                    <p class="text-gray-400 italic font-medium">Kami sedang meramu paket terbaik untuk kendaraan Anda.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<script>
    function filterKatalog(category) {
        const items = document.querySelectorAll('.katalog-item');
        const buttons = document.querySelectorAll('.filter-btn');

        buttons.forEach(btn => {
            const btnCategory = (btn.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();

            if (btnCategory === filterCategory) {
                btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-100');
                btn.classList.add('bg-gray-900', 'text-white', 'shadow-xl', 'shadow-gray-200');
            } else {
                btn.classList.add('bg-white', 'text-gray-500', 'border-gray-100');
                btn.classList.remove('bg-gray-900', 'text-white', 'shadow-xl', 'shadow-gray-200');
            }
        });

        items.forEach(item => {
            const itemCategory = (item.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();
            
            if (category === 'all' || itemCategory === filterCategory) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
        
        if (typeof AOS !== 'undefined') AOS.refresh();
    }
</script>

<style>
    .text-gradient {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

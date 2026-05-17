@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Katalog Layanan')

@section('content')
<div class="{{ auth()->check() ? 'max-w-6xl mx-auto py-8 px-4 sm:px-0' : 'max-w-7xl mx-auto px-6 py-12' }}">
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- Hero Section -->
    <section class="max-w-3xl mx-auto text-center mb-10 px-2">
        <span class="text-[#f2541b] font-bold text-xs uppercase tracking-[0.3em] mb-4 block">Premium Selection</span>
        <h1 class="font-serif text-3xl sm:text-4xl md:text-6xl font-black text-stone-900 tracking-tight mb-4 leading-tight">
            {!! $profil->katalog_title ? nl2br(e($profil->katalog_title)) : 'Katalog <span class="text-[#f2541b]">Layanan.</span>' !!}
        </h1>
        <p class="text-stone-500 text-xs md:text-sm font-medium leading-relaxed">
            "{{ $profil->katalog_subtitle ?? 'Temukan paket wrapping dan stiker terbaik yang dirancang khusus untuk meningkatkan estetika kendaraan Anda.' }}"
        </p>
    </section>

    <!-- Category Filter Premium -->
    <section class="mb-10 px-2">
        <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-3 pb-3 md:justify-center no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
            <button onclick="filterKatalog('all')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-[#151413] text-white font-bold text-xs border border-[#151413] transition-all duration-300 shadow-md shadow-stone-900/15 flex items-center gap-2 active:scale-95" data-category="all">
                <i class="ph-bold ph-grid-four text-sm"></i>
                Semua Layanan
            </button>
            @php
                $categories = [
                    'Mobil' => 'ph-car',
                    'Motor' => 'ph-motorcycle',
                    'Sepeda' => 'ph-bicycle'
                ];
            @endphp
            @foreach($categories as $cat => $icon)
            <button onclick="filterKatalog('{{ $cat }}')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white text-stone-500 font-bold text-xs border border-stone-200/80 hover:border-[#f2541b]/30 hover:text-[#f2541b] transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="{{ $cat }}">
                <i class="ph-bold {{ $icon }} text-sm"></i>
                {{ $cat }}
            </button>
            @endforeach
        </div>
    </section>

    <!-- Catalog Grid -->
    <section class="mb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="katalog-grid">
            @forelse($layanan as $item)
                <div class="katalog-item group bg-white rounded-[28px] overflow-hidden border border-stone-200/60 hover:border-[#f2541b]/30 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300 flex flex-col p-4" 
                     data-category="{{ $item->kategori }}">
                    
                    <!-- Premium Image Wrapper -->
                    <div class="relative aspect-[4/3] rounded-[20px] overflow-hidden bg-[#e9e8e4] flex items-center justify-center text-stone-400 font-bold shrink-0 mb-4">
                        @if($item->foto_contoh)
                            <img src="{{ asset('storage/' . $item->foto_contoh) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $item->nama_layanan }}">
                        @else
                            <div class="text-[10px] uppercase tracking-wider text-stone-500 font-serif">[ Gambar Layanan ]</div>
                        @endif
                        
                        <!-- Status Badge (Glass) -->
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3.5 py-1.5 bg-[#151413]/70 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-wider rounded-lg">
                                {{ $item->kategori ?? 'Masterpiece' }}
                            </span>
                        </div>
                    </div>

                    <!-- Enhanced Content Area -->
                    <div class="flex flex-col flex-grow">
                        <div class="mb-4 flex-grow">
                            <h3 class="font-serif text-lg font-black text-stone-900 mb-1.5 group-hover:text-[#f2541b] transition-colors line-clamp-1">{{ $item->nama_layanan }}</h3>
                            <p class="text-stone-500 text-xs font-medium leading-relaxed line-clamp-3">{{ $item->deskripsi }}</p>
                        </div>

                        <!-- Price and Quick Checkout Footer -->
                        <div class="mt-auto pt-4 border-t border-stone-100 flex items-center justify-between gap-4">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-stone-400 uppercase tracking-widest block">Mulai Dari</span>
                                <span class="font-serif text-sm md:text-base font-black text-[#f2541b]">
                                    @if($item->tipe_layanan == 'fix')
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    @else
                                        Custom
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="w-9 h-9 bg-stone-50 text-stone-600 rounded-xl flex items-center justify-center hover:bg-[#151413] hover:text-white transition-all border border-stone-200/60 shadow-sm active:scale-95" title="Tambah ke Keranjang">
                                        <i class="ph-bold ph-shopping-cart text-sm"></i>
                                    </button>
                                </form>
                                
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <input type="hidden" name="direct_checkout" value="1">
                                    <button type="submit" class="px-3.5 py-2.5 bg-stone-900 hover:bg-[#2c2a28] text-white rounded-xl font-bold text-[9px] tracking-wider uppercase transition-all flex items-center gap-1 active:scale-95">
                                        Pesan <i class="ph-bold ph-arrow-right text-[9px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center">
                    <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center text-stone-300 mx-auto mb-6 shadow-sm">
                        <i class="ph-fill ph-sketch-logo text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-serif font-black text-stone-900 mb-2">Belum Ada Koleksi.</h3>
                    <p class="text-stone-400 text-xs font-medium">Kami sedang meramu paket terbaik untuk kendaraan Anda.</p>
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
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-[#151413] text-white font-bold text-xs border border-[#151413] transition-all duration-300 shadow-md shadow-stone-900/15 flex items-center gap-2 active:scale-95";
            } else {
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white text-stone-500 font-bold text-xs border border-stone-200/80 hover:border-[#f2541b]/30 hover:text-[#f2541b] transition-all duration-300 flex items-center gap-2 active:scale-95";
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
    }
</script>
@endsection


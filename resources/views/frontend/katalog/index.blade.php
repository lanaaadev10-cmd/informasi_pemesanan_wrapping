@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Katalog Layanan')

@section('content')
    @if(!auth()->check())
        <!-- Spacer untuk Public View agar tidak tertutup Navbar -->
        <div class="h-28"></div>
    @endif

    <!-- Container Utama -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-10 relative overflow-hidden">

        @php
            $accentColor = $profil->accent_color ?? '#f2994a';
            $heroTitle = $profil->katalog_hero_title ?? 'Wrap Catalog';
            $heroDesc = $profil->katalog_hero_desc ?? 'Choose Your Finish';
            $introText = $profil->katalog_intro_text ?? 'Elevate your vehicle\'s aesthetic with our curated collection of high-performance wraps and protective finishes.';
        @endphp

        <style>
            :root {
                --accent-color: {{ $accentColor }};
            }
            .accent-color { color: var(--accent-color); }
            .accent-bg { background-color: var(--accent-color); }
        </style>

        <!-- Ambient Glowing Core di Background -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

        <!-- 1. HEADER SECTION (Wrap Catalog Title + Search Bar) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative">
            <div>
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">PREMIUM FINISHES</span>
                <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1">
                    {{ $heroTitle }}
                </h1>
            </div>

            <!-- Dynamic Search input matching mockup -->
            <div class="relative max-w-xs w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-500">
                    <i class="ph ph-magnifying-glass text-sm"></i>
                </span>
                <input type="text" id="catalog-search" oninput="searchCatalog()"
                       placeholder="Search catalog..."
                       class="w-full bg-[#121212]/80 border border-white/5 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none transition-all shadow-inner" style="focus:border-color: var(--accent-color);">
            </div>
        </div>

        <!-- 2. INTRO SECTION (Choose Your Finish Header + Filters) -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pt-4 z-10 relative border-t border-white/5">
            <div class="max-w-2xl space-y-2">
                <span class="accent-color text-[10px] font-bold uppercase tracking-widest block font-mono">PREMIUM SELECTION</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $heroDesc }}</h2>
                <p class="text-gray-400 text-xs sm:text-sm font-light leading-relaxed">
                    {{ $introText }}
                </p>
            </div>

            <!-- Finish category filters matching figma -->
            <div class="flex flex-wrap gap-2.5">
                <button onclick="filterKatalog('all')"
                        class="filter-btn px-5 py-2.5 rounded-full accent-bg text-black font-extrabold text-xs border transition-all duration-300 shadow-md active:scale-95"
                        style="border-color: var(--accent-color); box-shadow: 0 0 10px color-mix(in srgb, var(--accent-color) 10%, transparent);"
                        data-category="all">
                    All
                </button>
                @foreach(['Matte', 'Gloss', 'Satin', 'PPF'] as $finishType)
                    <button onclick="filterKatalog('{{ strtolower($finishType) }}')"
                            class="filter-btn px-5 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 active:scale-95"
                            data-category="{{ strtolower($finishType) }}">
                        {{ $finishType }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- 3. CATALOG DYNAMIC GRID -->
        <div class="z-10 relative space-y-6">
            @if($layanan->isNotEmpty())
                
                @php
                    // 🚀 Split layout dynamic variables
                    $wideItem = $layanan->first();
                    $mediumItem = $layanan->skip(1)->first();
                    $gridItems = $layanan->skip(2);

                    // Dynamic finish classifications helper
                    function getFinishType($item, $index) {
                        $name = strtolower($item->nama_layanan);
                        $desc = strtolower($item->deskripsi);
                        if (str_contains($name, 'ppf') || str_contains($desc, 'ppf')) return 'ppf';
                        if (str_contains($name, 'matte') || str_contains($desc, 'matte')) return 'matte';
                        if (str_contains($name, 'gloss') || str_contains($desc, 'gloss')) return 'gloss';
                        if (str_contains($name, 'satin') || str_contains($desc, 'satin')) return 'satin';
                        if (str_contains($name, 'interior') || str_contains($desc, 'interior')) return 'interior';
                        return ['matte', 'gloss', 'satin', 'ppf'][$index % 4];
                    }
                @endphp

                <!-- Row 1: Staggered (Wide + Medium card) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- 1. WIDE CARD (Left, e.g. Signature Full Body Wrap style) -->
                    @if($wideItem)
                        @php 
                            $wideFinish = getFinishType($wideItem, 0); 
                            $wideImage = $wideItem->foto_contoh ? asset('storage/' . $wideItem->foto_contoh) : 'https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=800&auto=format&fit=crop';
                        @endphp
                        <div class="katalog-item lg:col-span-2 bg-[#121212]/40 border border-white/5 rounded-[32px] overflow-hidden relative min-h-[380px] lg:min-h-[420px] flex flex-col justify-end p-8 sm:p-10 group shadow-[0_15px_30px_rgba(0,0,0,0.5)] transition-all duration-500"
                             data-category="{{ $wideFinish }} {{ strtolower($wideItem->kategori) }}">
                            
                            <!-- Card background image -->
                            <img src="{{ $wideImage }}" 
                                 width="800" height="420"
                                 class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-[1.02] transition-transform duration-1000 ease-out z-0" 
                                 alt="{{ $wideItem->nama_layanan }}">
                            
                            <!-- Overlays -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/35 to-transparent z-10"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-transparent to-transparent z-10"></div>

                            <!-- Card Body -->
                            <div class="z-20 space-y-4">
                                <div class="flex items-center gap-2">
                                    <span class="bg-[#f2994a]/15 border border-[#f2994a]/30 px-3 py-1 rounded-full text-[9px] font-extrabold text-[#f2994a] uppercase tracking-wider">
                                        POPULAR
                                    </span>
                                    <span class="bg-white/5 border border-white/10 px-3 py-1 rounded-full text-[9px] font-extrabold text-gray-300 uppercase tracking-wider">
                                        {{ $wideItem->kategori ?? 'Mobil' }}
                                    </span>
                                    @if($wideItem->estimasi_waktu)
                                        <span class="bg-blue-500/15 border border-blue-500/30 px-3 py-1 rounded-full text-[9px] font-extrabold text-blue-400 uppercase tracking-wider flex items-center gap-1">
                                            <i class="ph ph-clock"></i> {{ $wideItem->estimasi_waktu }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="katalog-title text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                    {{ $wideItem->nama_layanan }}
                                </h3>
                                <p class="katalog-desc text-gray-400 text-xs sm:text-sm font-light max-w-lg leading-relaxed">
                                    {{ $wideItem->deskripsi }}
                                </p>

                                <!-- Footer row pricing & actions -->
                                <div class="flex flex-wrap items-end justify-between gap-6 pt-6 border-t border-white/5 mt-4">
                                    <div class="space-y-1">
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">STARTING FROM</span>
                                        <span class="text-xl sm:text-2xl font-black text-[#f2994a]">
                                            @if($wideItem->tipe_layanan == 'fix')
                                                Rp {{ number_format($wideItem->harga, 0, ',', '.') }}
                                            @else
                                                Custom Pricing
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Add to cart or Checkout CTA -->
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_paket" value="{{ $wideItem->id_layanan }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <input type="hidden" name="direct_checkout" value="1">
                                        <button type="submit" 
                                                class="flex items-center gap-2 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-2xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
                                            <i class="ph-bold ph-phone text-xs"></i> Book Discovery Call
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2. MEDIUM CARD (Right, e.g. Bespoke Interior style) -->
                    @if($mediumItem)
                        @php 
                            $mediumFinish = getFinishType($mediumItem, 1); 
                            $mediumImage = $mediumItem->foto_contoh ? asset('storage/' . $mediumItem->foto_contoh) : 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=400&auto=format&fit=crop';
                        @endphp
                        <div class="katalog-item lg:col-span-1 bg-[#121212]/40 border border-white/5 rounded-[32px] overflow-hidden relative min-h-[380px] lg:min-h-[420px] flex flex-col justify-end p-8 group shadow-[0_15px_30px_rgba(0,0,0,0.5)] transition-all duration-500"
                             data-category="{{ $mediumFinish }} {{ strtolower($mediumItem->kategori) }}">
                            
                            <!-- Card background image -->
                            <img src="{{ $mediumImage }}" 
                                 width="400" height="420"
                                 class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-[1.03] transition-transform duration-700 ease-out z-0" 
                                 alt="{{ $mediumItem->nama_layanan }}">
                            
                            <!-- Overlays -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/35 to-transparent z-10"></div>

                            <!-- Card Body -->
                            <div class="z-20 space-y-4">
                                <div class="flex items-center gap-2">
                                    <span class="bg-white/5 border border-white/10 px-3 py-1 rounded-full text-[9px] font-extrabold text-gray-300 uppercase tracking-wider inline-block">
                                        {{ $mediumItem->kategori ?? 'Interior' }}
                                    </span>
                                    @if($mediumItem->estimasi_waktu)
                                        <span class="bg-blue-500/15 border border-blue-500/30 px-3 py-1 rounded-full text-[9px] font-extrabold text-blue-400 uppercase tracking-wider flex items-center gap-1">
                                            <i class="ph ph-clock"></i> {{ $mediumItem->estimasi_waktu }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="katalog-title text-xl font-extrabold text-white tracking-tight">
                                    {{ $mediumItem->nama_layanan }}
                                </h3>
                                <p class="katalog-desc text-gray-400 text-xs font-light leading-relaxed line-clamp-2">
                                    {{ $mediumItem->deskripsi }}
                                </p>

                                <!-- Footer row pricing & actions -->
                                <div class="flex items-center justify-between pt-5 border-t border-white/5 mt-2">
                                    <span class="text-lg font-black text-white">
                                        @if($mediumItem->tipe_layanan == 'fix')
                                            Rp {{ number_format($mediumItem->harga, 0, ',', '.') }}
                                        @else
                                            Custom Price
                                        @endif
                                    </span>

                                    <!-- Add to Cart Circular Plus Button -->
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_paket" value="{{ $mediumItem->id_layanan }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <button type="submit" 
                                                class="w-11 h-11 bg-white/5 text-gray-300 rounded-2xl flex items-center justify-center hover:bg-[#f2994a] hover:text-black transition-all border border-white/10 active:scale-95" 
                                                title="Add to Cart">
                                            <i class="ph-bold ph-plus text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Row 2: Standard 3-Column Grid for subsequent items -->
                @if($gridItems->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
                        @foreach($gridItems as $index => $item)
                            @php 
                                $itemFinish = getFinishType($item, $index + 2); 
                                $itemImage = $item->foto_contoh ? asset('storage/' . $item->foto_contoh) : '';
                                if(!$itemImage) {
                                    // Stagger fallback images dynamically to look extremely premium
                                    $fallbacks = [
                                        'https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=400&auto=format&fit=crop',
                                        'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?q=80&w=400&auto=format&fit=crop',
                                        'https://images.unsplash.com/photo-1507136566006-cfc505b114fc?q=80&w=400&auto=format&fit=crop'
                                    ];
                                    $itemImage = $fallbacks[$index % count($fallbacks)];
                                }
                            @endphp

                            <!-- Card item standard -->
                            <div class="katalog-item bg-white/[0.01] border border-white/5 rounded-[32px] overflow-hidden p-6 hover:border-[#f2994a]/25 hover:bg-white/[0.02] transition-all duration-300 group flex flex-col justify-between shadow-lg relative"
                                 data-category="{{ $itemFinish }} {{ strtolower($item->kategori) }}">
                                
                                <div class="space-y-4">
                                    <!-- Image Header aspect aspect-[4/3] -->
                                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-white/5 border border-white/5 shadow-inner shrink-0">
                                        <img src="{{ $itemImage }}" 
                                             width="400" height="300"
                                             class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                                             alt="{{ $item->nama_layanan }}">
                                        
                                        <!-- Dynamic pill overlay -->
                                        <span class="absolute top-3 right-3 bg-[#0a0a0a]/80 backdrop-blur-md px-3 py-1 rounded-lg text-[8px] font-extrabold text-gray-300 uppercase tracking-widest border border-white/5">
                                            {{ $item->kategori ?? 'Standard' }}
                                        </span>
                                        @if($item->estimasi_waktu)
                                            <span class="absolute top-3 left-3 bg-blue-600/80 backdrop-blur-md px-3 py-1 rounded-lg text-[8px] font-extrabold text-white uppercase tracking-widest border border-white/5 flex items-center gap-1">
                                                <i class="ph ph-clock"></i> {{ $item->estimasi_waktu }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Content body -->
                                    <div class="space-y-2">
                                        <h4 class="katalog-title text-lg font-bold text-white group-hover:text-[#f2994a] transition-colors duration-300 leading-tight">
                                            {{ $item->nama_layanan }}
                                        </h4>
                                        <p class="katalog-desc text-gray-400 text-xs font-light leading-relaxed line-clamp-3">
                                            {{ $item->deskripsi }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Card Footer pricing & checkout plus -->
                                <div class="flex items-center justify-between pt-5 mt-5 border-t border-white/5 shrink-0">
                                    <span class="text-[#f2994a] font-extrabold text-base">
                                        @if($item->tipe_layanan == 'fix')
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        @else
                                            Custom Price
                                        @endif
                                    </span>

                                    <!-- Add to Cart Circular Plus Button -->
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                                        <input type="hidden" name="jumlah" value="1">
                                        <button type="submit" 
                                                class="w-10 h-10 bg-white/5 text-gray-400 rounded-xl flex items-center justify-center hover:bg-[#f2994a] hover:text-black border border-white/10 transition-all active:scale-95" 
                                                title="Add to Cart">
                                            <i class="ph-bold ph-plus text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="py-24 text-center border border-dashed border-white/10 rounded-[32px] bg-white/[0.01]">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                    <h4 class="text-base font-bold text-white mb-1">Catalog Empty</h4>
                    <p class="text-xs text-gray-500 font-light">We are drafting premium finishes collections at the moment.</p>
                </div>
            @endif
        </div>

        <!-- 4. BOTTOM FEATURES BAR -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-12 border-t border-white/5 mt-16 z-10 relative">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
                    <i class="ph ph-seal-check text-lg"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Premium Films</h5>
                    <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">We only use top-tier 3M, Avery, and Inozetek materials.</p>
                </div>
            </div>
            
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
                    <i class="ph ph-wrench text-lg"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Expert Installers</h5>
                    <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">Certified technicians with 10+ years of collective experience.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
                    <i class="ph ph-shield-check text-lg"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Warranty</h5>
                    <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">Full warranty on both material and labor defects.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] shrink-0">
                    <i class="ph ph-leaf text-lg"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider">Eco-Friendly</h5>
                    <p class="text-[10px] text-gray-500 mt-1 leading-normal font-light">Sustainable practices and non-toxic application methods.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- 5. FLOATING CART ACTION BUTTON (Dynamically counts active items) -->
    @auth
        @php
            $cartCount = \App\Models\Keranjang::where('id_user', auth()->id())
                ->where('status', 'active')
                ->first()?->details?->count() ?? 0;
        @endphp
        @if($cartCount > 0)
            <a href="{{ route('keranjang.index') }}" 
               class="fixed bottom-8 right-8 z-[9999] w-14 h-14 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-full hidden md:flex items-center justify-center shadow-[0_6px_20px_rgba(242,153,74,0.4)] transition-all hover:scale-110 active:scale-95 group">
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border border-black shadow">
                    {{ $cartCount }}
                </span>
                <i class="ph-bold ph-shopping-cart text-xl"></i>
            </a>
        @endif
    @endauth

    <!-- Scripting for live searching & dynamic filtering -->
    <script>
        // Javascript Live Search
        function searchCatalog() {
            const query = document.getElementById('catalog-search').value.toLowerCase();
            const items = document.querySelectorAll('.katalog-item');
            
            items.forEach(item => {
                const title = item.querySelector('.katalog-title').textContent.toLowerCase();
                const desc = item.querySelector('.katalog-desc').textContent.toLowerCase();
                
                if (title.includes(query) || desc.includes(query)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Javascript Category Filter Pills
        function filterKatalog(category) {
            const items = document.querySelectorAll('.katalog-item');
            const buttons = document.querySelectorAll('.filter-btn');

            // Toggle active styles on buttons
            buttons.forEach(btn => {
                const btnCategory = btn.getAttribute('data-category').toLowerCase();
                if (btnCategory === category.toLowerCase()) {
                    btn.className = "filter-btn px-5 py-2.5 rounded-full bg-[#f2994a] text-black font-extrabold text-xs border border-[#f2994a] transition-all duration-300 shadow-md shadow-[#f2994a]/10 active:scale-95";
                } else {
                    btn.className = "filter-btn px-5 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/20 hover:text-white transition-all duration-300 active:scale-95";
                }
            });

            // Toggle items visibility
            items.forEach(item => {
                const categories = item.getAttribute('data-category').toLowerCase().split(' ');
                
                if (category === 'all' || categories.includes(category.toLowerCase())) {
                    item.style.display = '';
                    // entrance fade-in micro-animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transition = 'opacity 0.4s ease';
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection

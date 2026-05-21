@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Galeri Karya')

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

    @php
        $accentColor = $profil->accent_color ?? '#f2994a';
        $galeriTitle = $profil->galeri_hero_title ?? 'Precision Mastery Gallery';
        $galeriDesc = $profil->galeri_hero_desc ?? 'Explore our curated selection of high-end automotive transformations. From matte finishes to protective layers, witness the art of precision in every detail.';
    @endphp

    <style>
        :root {
            --accent-color: {{ $accentColor }};
        }
    </style>

    <!-- 1. Hero / Header Section -->
    <section class="max-w-3xl mx-auto text-center mb-12 px-2 pt-8" data-aos="fade-down" data-aos-duration="1000">
        <!-- Premium Tag -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: var(--accent-color);"></span>
            <span class="text-xs font-bold tracking-wider uppercase font-mono" style="color: var(--accent-color);">{{ $galeriTitle }}</span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">
            {{ $galeriTitle }}
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl mx-auto font-light">
            {{ $galeriDesc }}
        </p>
    </section>

    <!-- 2. Category Filter Pills (Mockup Matching) -->
    <section class="mb-12 px-2" data-aos="fade-up" data-aos-duration="1000">
        <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-3 pb-3 md:justify-center no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0">
            <button onclick="filterGaleri('all')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full text-black font-extrabold text-xs border transition-all duration-300 shadow-lg flex items-center gap-2 active:scale-95" style="background-color: var(--accent-color); border-color: var(--accent-color); box-shadow: 0 0 15px color-mix(in srgb, var(--accent-color) 10%, transparent);" data-category="all">
                All Works
            </button>
            <button onclick="filterGaleri('sports-cars')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="sports-cars">
                Sports Cars
            </button>
            <button onclick="filterGaleri('luxury-sedans')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="luxury-sedans">
                Luxury Sedans
            </button>
            <button onclick="filterGaleri('suvs-trucks')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="suvs-trucks">
                SUVs & Trucks
            </button>
            <button onclick="filterGaleri('satin-finish')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="satin-finish">
                Satin Finish
            </button>
            <button onclick="filterGaleri('matte-black')" class="filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/30 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95" data-category="matte-black">
                Matte Black
            </button>
        </div>
    </section>

    <!-- 3. Asymmetric Masonry / Portfolio Gallery Grid (Mockup Matching) -->
    <section class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8" id="galeri-grid">
            <!-- ROW 1: Wide Porsche (Cols 8) | Classic Coupe (Cols 4) -->
            <!-- Card 1: Porsche 911 -->
            <div class="galeri-item group relative md:col-span-8 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl h-[300px] md:h-[450px]" 
                 data-category="sports-cars satin-finish all" data-aos="fade-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=1200&auto=format&fit=crop" 
                     width="800" height="450"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Porsche 911 Grey Satin">
                <div class="absolute bottom-0 inset-x-0 p-8 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Satin Finish</span>
                    <h3 class="text-white text-2xl font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Porsche 911 Satin Metallic Grey</h3>
                    <p class="text-gray-400 text-sm font-light">Pengerjaan premium wrap satin grey dengan presisi tingkat tinggi di setiap lekukan bodi.</p>
                </div>
            </div>
            
            <!-- Card 2: Sunset Coupe -->
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl h-[300px] md:h-[450px]" 
                 data-category="sports-cars all" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=600&auto=format&fit=crop" 
                     width="400" height="450"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Classic Coupe Sunset">
                <div class="absolute bottom-0 inset-x-0 p-8 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Sports Cars</span>
                    <h3 class="text-white text-xl font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Classic Coupe Sunset</h3>
                    <p class="text-gray-400 text-sm font-light">Eksklusivitas tinggi classic coupe wrap under sunset golden hour.</p>
                </div>
            </div>

            <!-- ROW 2: BMW Front (Cols 4) | Showroom Exhibition (Cols 8) -->
            <!-- Card 3: BMW Front -->
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl h-[300px] md:h-[480px]" 
                 data-category="luxury-sedans matte-black all" data-aos="fade-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?q=80&w=600&auto=format&fit=crop" 
                     width="400" height="480"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="BMW Front Green">
                <div class="absolute bottom-0 inset-x-0 p-8 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Matte Black</span>
                    <h3 class="text-white text-xl font-bold leading-tight group-hover:text-[#f2994a] transition-colors">BMW M5 Matte Forest Green</h3>
                    <p class="text-gray-400 text-sm font-light">Finishing matte gagah dengan proteksi bodi penuh.</p>
                </div>
            </div>

            <!-- Card 4: Showroom Blue -->
            <div class="galeri-item group relative md:col-span-8 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl h-[300px] md:h-[480px]" 
                 data-category="luxury-sedans satin-finish all" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=1200&auto=format&fit=crop" 
                     width="800" height="480"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Showroom Wrapped Cars">
                <div class="absolute bottom-0 inset-x-0 p-8 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Satin Finish</span>
                    <h3 class="text-white text-2xl font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Elite Showroom Exhibition</h3>
                    <p class="text-gray-400 text-sm font-light">Koleksi mobil-mobil premium berbalut stiker terbaik kami di bawah pencahayaan studio.</p>
                </div>
            </div>

            <!-- ROW 3: Detailing Mirror (Cols 4) | reflection silver hood (Cols 4) | Audi R8 rear (Cols 4) -->
            <!-- Card 5: Carbon side mirror -->
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square" 
                 data-category="sports-cars matte-black all" data-aos="fade-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=600&auto=format&fit=crop" 
                     width="400" height="400"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Carbon side mirror">
                <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Matte Black</span>
                    <h3 class="text-white text-lg font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Carbon Fiber Detailing</h3>
                    <p class="text-gray-400 text-xs font-light">Detailing serat karbon berkualitas tinggi pada spion samping.</p>
                </div>
            </div>

            <!-- Card 6: Silver reflection hood -->
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square" 
                 data-category="luxury-sedans satin-finish all" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=600&auto=format&fit=crop" 
                     width="400" height="400"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Silver hood reflection">
                <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Satin Finish</span>
                    <h3 class="text-white text-lg font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Chrome Hood Reflection</h3>
                    <p class="text-gray-400 text-xs font-light">Refleksi chrome cair yang memukau di bawah lampu overhead.</p>
                </div>
            </div>

            <!-- Card 7: Audi R8 rear green -->
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square" 
                 data-category="sports-cars all" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?q=80&w=600&auto=format&fit=crop" 
                     width="400" height="400"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                     alt="Green Audi R8 Rear">
                <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">Sports Cars</span>
                    <h3 class="text-white text-lg font-bold leading-tight group-hover:text-[#f2994a] transition-colors">Audi R8 Green Beast</h3>
                    <p class="text-gray-400 text-xs font-light">Finishing rear satin emerald green yang memukau mata.</p>
                </div>
            </div>
            
            <!-- Dynamic User Uploaded Gallery Loop (Rendered cleanly as elegant standard blocks below the main collection if present) -->
            @foreach($galeri as $userItem)
                <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square" 
                     data-category="all {{ strtolower($userItem->kategori ?? 'sports-cars') }}" data-aos="fade-up" data-aos-duration="1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                    <img src="{{ asset('storage/' . $userItem->foto) }}" 
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700" 
                         alt="{{ $userItem->judul }}">
                    <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                        <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">{{ $userItem->kategori ?? 'Portfolio' }}</span>
                        <h3 class="text-white text-lg font-bold group-hover:text-[#f2994a] transition-colors leading-tight">{{ $userItem->judul }}</h3>
                        <p class="text-gray-400 text-xs font-light leading-relaxed line-clamp-2">{{ $userItem->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. Discover More Button -->
    <section class="text-center pt-8 mb-24" data-aos="fade-up" data-aos-duration="1000">
        <button onclick="window.location.href='#mahakarya'" class="inline-flex flex-col items-center gap-3 text-gray-400 hover:text-white font-bold text-xs uppercase tracking-widest transition-colors duration-300">
            Discover More Works
            <div class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center bg-white/5 animate-bounce">
                <i class="ph-bold ph-caret-down text-xs text-[#f2994a]"></i>
            </div>
        </button>
    </section>
</div>

<!-- Filters JavaScript -->
<script>
    function filterGaleri(category) {
        const items = document.querySelectorAll('.galeri-item');
        const buttons = document.querySelectorAll('.filter-btn');

        buttons.forEach(btn => {
            const btnCategory = (btn.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();

            if (btnCategory === filterCategory) {
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-[#f2994a] text-black font-extrabold text-xs border border-[#f2994a] transition-all duration-300 shadow-lg shadow-[#f2994a]/10 flex items-center gap-2 active:scale-95";
            } else {
                btn.className = "filter-btn shrink-0 px-6 py-2.5 rounded-full bg-white/5 text-gray-400 font-bold text-xs border border-white/10 hover:border-[#f2994a]/30 hover:text-white transition-all duration-300 flex items-center gap-2 active:scale-95";
            }
        });

        items.forEach(item => {
            const itemCategoriesString = (item.getAttribute('data-category') || '').toLowerCase();
            const filterCategory = category.toLowerCase();
            
            if (filterCategory === 'all' || itemCategoriesString.includes(filterCategory)) {
                // Restore responsive layouts cleanly on match
                if (item.classList.contains('md:col-span-8')) {
                    item.style.display = 'block';
                } else if (item.classList.contains('md:col-span-4')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'block';
                }
                
                item.style.opacity = '0';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transition = 'opacity 0.4s ease';
                }, 50);
            } else {
                item.style.display = 'none';
            }
        });
        
        if (typeof AOS !== 'undefined') AOS.refresh();
    }
</script>
@endsection

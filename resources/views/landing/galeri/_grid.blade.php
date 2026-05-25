{{-- ============================================
    BAGIAN: Gallery Grid
    Deskripsi: Grid masonry galeri karya wrapping
============================================ --}}
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

        <!-- ROW 3: 3 equal cards -->
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

        <!-- Dynamic User Uploaded Gallery -->
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

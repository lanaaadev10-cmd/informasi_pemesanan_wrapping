{{-- ============================================
    BAGIAN: Gallery Grid
    Deskripsi: Grid masonry galeri karya wrapping
============================================ --}}
<section class="mb-16">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8" id="galeri-grid">
        @php
            $staticGaleri = [
                ['judul' => 'Tesla Model S', 'foto' => 'images/galeri/tesla-model-s.jpg', 'deskripsi' => 'Luxury Matte Grey / Blue — full body satin wrap with gloss black accents.', 'kategori' => 'matte', 'badge_text' => 'Varian Favorit'],
                ['judul' => 'Range Rover Sport', 'foto' => 'images/galeri/range-rover-sport.jpg', 'deskripsi' => 'Satin Liquid Silver Wrap — premium finish with ceramic coating protection.', 'kategori' => 'satin', 'badge_text' => 'Sangat Direkomendasikan'],
                ['judul' => 'Ferrari F8 Tributo', 'foto' => 'images/galeri/ferrari-f8.jpg', 'deskripsi' => 'Satin Metallic Gold Yellow — a head-turning transformation.', 'kategori' => 'satin', 'badge_text' => ''],
                ['judul' => 'Porsche 911 GT3', 'foto' => 'images/galeri/porsche-911.jpg', 'deskripsi' => 'Matte Racing Green — aggressive yet elegant, track-ready aesthetic.', 'kategori' => 'matte', 'badge_text' => 'Unggulan'],
                ['judul' => 'Mercedes-Benz S-Class', 'foto' => 'images/galeri/mercedes-s-class.jpg', 'deskripsi' => 'Gloss Diamond White — mirror finish that exudes pure luxury.', 'kategori' => 'glossy', 'badge_text' => ''],
                ['judul' => 'Lamborghini Urus', 'foto' => 'images/galeri/lamborghini-urus.jpg', 'deskripsi' => 'Satin Armour Grey — stealthy SUV wrap with custom carbon accents.', 'kategori' => 'satin', 'badge_text' => 'Best Seller'],
            ];
        @endphp
        @foreach($staticGaleri as $item)
            <div class="galeri-item group relative md:col-span-4 rounded-3xl overflow-hidden border border-white/5 bg-white/[0.01] hover:border-[#f2994a]/30 transition-all duration-500 shadow-xl aspect-square"
                 data-category="all {{ $item['kategori'] }}" data-aos="fade-up" data-aos-duration="1000">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 opacity-80 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img src="{{ asset($item['foto']) }}"
                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700"
                     alt="{{ $item['judul'] }}">
                <div class="absolute bottom-0 inset-x-0 p-6 z-20 space-y-2">
                    <span class="text-[#f2994a] text-xs font-black uppercase tracking-widest font-mono">
                        {{ ucfirst($item['kategori']) }}
                    </span>
                    <h3 class="text-white text-lg font-bold group-hover:text-[#f2994a] transition-colors leading-tight">{{ $item['judul'] }}</h3>
                    <p class="text-gray-400 text-xs font-light leading-relaxed line-clamp-2">{{ $item['deskripsi'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

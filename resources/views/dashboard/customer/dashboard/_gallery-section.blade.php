<!-- Galeri Portofolio Section -->
<div class="space-y-4">
    <div class="flex items-end justify-between">
        <div class="space-y-1">
            <div class="relative inline-block">
                <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                    Galeri Portofolio
                </h3>
                <span class="absolute -bottom-1.5 left-0 w-8 h-[3px] bg-[#ff6b00] rounded-full"></span>
            </div>
        </div>
        <a href="{{ route('galeri.user') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#ff6b00] hover:text-[#ea580c] transition-colors group">
            <span>Lihat Semua</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    @if($galeris->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 pt-1">
            @foreach($galeris as $item)
                <div class="group relative rounded-[20px] overflow-hidden aspect-square bg-[#0E0E10] border border-white/10 hover:border-[#FF6B00]/60 transition-all duration-300 shadow-md">
                    <img src="{{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}" 
                         alt="{{ $item->judul }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 inset-x-0 p-3 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 opacity-0 group-hover:opacity-100">
                        <p class="text-white text-xs font-montserrat font-bold truncate">{{ $item->judul }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-8 text-center bg-[#0E0E10] border border-white/5 rounded-2xl">
            <p class="text-[#8A8D93] font-questrial text-sm">Belum ada portofolio untuk ditampilkan.</p>
        </div>
    @endif
</div>

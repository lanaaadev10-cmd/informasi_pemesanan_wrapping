<div class="mt-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-extrabold text-white">Galeri Portofolio</h2>
        <a href="{{ route('galeri.user') }}" class="text-xs font-bold text-[#f2994a] hover:text-[#e28a44] transition-colors uppercase tracking-wider">
            Lihat Semua
        </a>
    </div>
    @if($galeris->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($galeris as $item)
                <div class="group relative rounded-2xl overflow-hidden aspect-square bg-[#121212] border border-white/5 hover:border-[#f2994a]/30 transition-all duration-300">
                    <img src="{{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 inset-x-0 p-3 translate-y-2 group-hover:translate-y-0 transition-transform duration-300 opacity-0 group-hover:opacity-100">
                        <p class="text-white text-xs font-bold truncate">{{ $item->judul }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-sm">Belum ada portofolio untuk ditampilkan.</p>
    @endif
</div>

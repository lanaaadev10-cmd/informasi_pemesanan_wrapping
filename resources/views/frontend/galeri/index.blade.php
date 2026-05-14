@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Galeri Karya')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-12 pb-16 text-center" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">Our Masterpieces</span>
        <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tighter mb-8">
            {!! $profil->galeri_title ? nl2br(e($profil->galeri_title)) : 'Galeri <span class="text-gradient italic">Karya Kami.</span>' !!}
        </h1>
        <p class="max-w-2xl mx-auto text-gray-500 text-lg leading-relaxed font-medium italic">
            "{{ $profil->galeri_subtitle ?? 'Kumpulan hasil pengerjaan terbaik kami dari berbagai jenis kendaraan dan kebutuhan wrapping stiker. Kualitas presisi adalah janji kami.' }}"
        </p>
    </section>

    <!-- Category Filter Premium -->
    <section class="max-w-7xl mx-auto px-6 mb-16 flex justify-center gap-4 flex-wrap" data-aos="fade-up" data-aos-delay="100">
        <button onclick="filterGaleri('all')" class="filter-btn group relative px-8 py-3 rounded-2xl bg-gray-900 text-white font-bold text-sm transition-all shadow-xl shadow-gray-200" data-category="all">
            <span class="relative z-10">Semua Karya</span>
        </button>
        @foreach(['Mobil', 'Motor', 'Sepeda'] as $cat)
        <button onclick="filterGaleri('{{ $cat }}')" class="filter-btn group relative px-8 py-3 rounded-2xl bg-white text-gray-500 font-bold text-sm border border-gray-100 hover:border-orange-500 hover:text-orange-600 transition-all shadow-sm" data-category="{{ $cat }}">
            <span class="relative z-10">{{ $cat }}</span>
        </button>
        @endforeach
    </section>

    <!-- Gallery Grid -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="galeri-grid">
            @forelse($galeri as $item)
                <div class="galeri-item soft-card group overflow-hidden" data-aos="fade-up" data-category="{{ $item->kategori }}">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('storage/' . $item->foto) }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                             alt="{{ $item->judul }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                            <span class="text-orange-400 text-xs font-bold uppercase tracking-widest mb-2">{{ $item->kategori ?? 'Portfolio' }}</span>
                            <h3 class="text-white text-xl font-bold mb-1">{{ $item->judul }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-black uppercase px-3 py-1 rounded-full tracking-widest">
                                {{ $item->tanggal_upload ? \Carbon\Carbon::parse($item->tanggal_upload)->format('M Y') : 'Recent' }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm line-clamp-2 leading-relaxed font-medium italic">
                            "{{ $item->deskripsi }}"
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400 shadow-inner">
                        <i class="ph-bold ph-image-square text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada karya</h3>
                    <p class="text-gray-500">Hasil pengerjaan kami akan segera tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- CTA Section -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="bg-gray-900 rounded-[48px] p-12 md:p-20 text-center text-white relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/20 blur-[100px] rounded-full"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">Ingin kendaraan Anda tampil seperti ini?</h2>
                <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto font-medium italic leading-relaxed">
                    "Hubungi spesialis kami untuk konsultasi desain dan penawaran harga terbaik."
                </p>
                <a href="https://wa.me/{{ $profil->nomor_telepon ?? '' }}" class="btn-premium inline-block px-12 py-4 rounded-2xl font-bold text-lg hover:scale-105 transition-transform shadow-2xl shadow-orange-900/50">
                    Hubungi Sekarang
                </a>
            </div>
        </div>
    </section>

    <script>
        function filterGaleri(category) {
            const items = document.querySelectorAll('.galeri-item');
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
                    item.style.display = 'block';
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

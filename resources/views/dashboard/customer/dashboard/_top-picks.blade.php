<!-- Top Picks Section (Using Actual User Layanan Data) -->
<div class="space-y-4">
    
    <!-- Section Header: "Paket Layanan" with orange accent & "Lihat Semua ->" -->
    <div class="flex items-end justify-between">
        <div class="space-y-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF6B00]"></span>
                <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#FF6B00]">
                    Pilihan Favorit
                </span>
            </div>
            <h3 class="text-xl sm:text-2xl font-audiowide font-bold text-white tracking-wide">
                Paket Layanan
            </h3>
        </div>
        
        <a href="{{ route('katalog.user') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#FF6B00] hover:text-[#E05D00] transition-colors group">
            <span>Lihat Semua</span>
            <i class="ph-bold ph-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <!-- Filter Kategori (Di Atas Kartu Paket Layanan Sesuai Permintaan) -->
    @include('dashboard.customer.dashboard._search-categories')

    <!-- Cards Container: Peek Carousel on Mobile, Grid/Scroll on Tablet/Desktop -->
    <div class="flex gap-3.5 sm:gap-5 overflow-x-auto no-scrollbar pb-3 pt-1 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x snap-mandatory scroll-smooth">
        
        @forelse($layanans as $item)
            @php
                $catSlug = strtolower(trim($item->tipe_paket ?? ''));
                $catName = strtolower(trim($item->nama_layanan ?? ''));
                
                // Actual Features from database
                $fiturList = is_array($item->fitur) ? $item->fitur : [];
                $fitur1 = isset($fiturList[0]) ? (is_array($fiturList[0]) ? ($fiturList[0]['nama_fitur'] ?? '') : $fiturList[0]) : 'Material Grade A';
                $fitur2 = isset($fiturList[1]) ? (is_array($fiturList[1]) ? ($fiturList[1]['nama_fitur'] ?? '') : $fiturList[1]) : 'Garansi Resmi';

                // Image handling
                $imgUrl = $item->foto_contoh 
                    ? \App\Helpers\StaticContent::fotoUrl($item->foto_contoh)
                    : asset('images/hero_car.png');
            @endphp
            
            <div class="top-pick-card w-[82vw] max-w-[280px] sm:w-72 shrink-0 snap-start bg-[#0E0E10] border border-white/10 rounded-[26px] p-3.5 hover:border-[#FF6B00]/60 transition-all duration-300 shadow-xl flex flex-col justify-between group"
                 data-category="all {{ $catSlug }} {{ $catName }}">
                
                <!-- Card Image Box -->
                <div class="relative h-44 rounded-[20px] overflow-hidden bg-black/60">
                    <img src="{{ $imgUrl }}" 
                         alt="{{ $item->nama_layanan }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20"></div>

                    <!-- Heart Outline Wishlist Button (Top-Right) -->
                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                        @csrf
                        <input type="hidden" name="id_paket" value="{{ $item->id_layanan }}">
                        <input type="hidden" name="jumlah" value="1">
                        <button type="submit" 
                                title="Tambah ke Keranjang"
                                class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-md border border-white/15 flex items-center justify-center text-white hover:text-[#ff6b00] hover:scale-110 active:scale-90 transition-all shadow-md">
                            <i class="ph-bold ph-heart text-sm"></i>
                        </button>
                    </form>

                </div>

                <!-- Card Content -->
                <div class="pt-3 pb-1 flex flex-col flex-1 justify-between">
                    <div>
                        <!-- Category Clean Text (No Badge Container) -->
                        <span class="text-[9px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00] block mb-1">
                            {{ strtoupper($item->tipe_paket ?? ($item->kategori ?? 'PAKET WRAPPING')) }}
                        </span>

                        <!-- Title -->
                        <h4 class="text-sm font-montserrat font-bold text-white group-hover:text-[#ff6b00] transition-colors truncate">
                            {{ $item->nama_layanan }}
                        </h4>
                        <!-- Description/Subtitle -->
                        <p class="text-xs font-questrial text-gray-400 truncate mt-0.5">
                            {{ $item->deskripsi ? Str::limit($item->deskripsi, 40) : ucfirst($item->tipe_paket ?? 'Layanan Kendaraan') }}
                        </p>

                        <!-- Key Specs Row (Using Real Database Fields) -->
                        <div class="flex items-center justify-between text-[10px] font-questrial text-gray-400 mt-3 pt-2.5 border-t border-white/5">
                            <span class="flex items-center gap-1" title="Estimasi Pengerjaan">
                                <i class="ph-bold ph-timer text-[#ff6b00]"></i>
                                {{ $item->estimasi_waktu ?? '1-3 Hari' }}
                            </span>
                            <span class="flex items-center gap-1 truncate max-w-[90px]" title="{{ $fitur2 }}">
                                <i class="ph-bold ph-shield-check text-[#ff6b00]"></i>
                                {{ Str::limit($fitur2, 13) }}
                            </span>
                            <span class="flex items-center gap-1 truncate max-w-[90px]" title="{{ $fitur1 }}">
                                <i class="ph-bold ph-check-circle text-[#ff6b00]"></i>
                                {{ Str::limit($fitur1, 13) }}
                            </span>
                        </div>
                    </div>

                    <!-- Bottom Price & Action Row -->
                    <div class="flex items-center justify-between mt-4 pt-2">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-questrial text-gray-500 uppercase tracking-wider">Mulai dari</span>
                            <span class="text-base sm:text-lg font-audiowide font-bold text-[#ff6b00]">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Circular Orange Button with Arrow -->
                        <a href="{{ route('pesanan.direct-order', ['package_id' => $item->id_layanan]) }}" 
                           title="Pesan Layanan Sekarang"
                           class="w-9 h-9 rounded-full bg-[#ff6b00] hover:bg-[#ea580c] text-white flex items-center justify-center shadow-lg shadow-[#ff6b00]/30 group-hover:scale-105 active:scale-95 transition-all">
                            <i class="ph-bold ph-arrow-right text-sm"></i>
                        </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="w-full py-10 text-center bg-[#0E0E10] border border-white/10 rounded-2xl p-6">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-montserrat font-bold uppercase tracking-wider mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <span>Stok Habis / Belum Diisi</span>
                </div>
                <h4 class="text-sm font-montserrat font-bold text-white mb-1">Belum Ada Paket Layanan</h4>
                <p class="text-xs font-questrial text-[#8A8D93]">Admin belum menambahkan paket layanan atau stok material saat ini sedang habis.</p>
            </div>
        @endforelse

        <!-- Empty State (Stok Habis) Saat Kategori Filter Kosong / Belum Diisi -->
        <div id="top-picks-empty-state"
             style="display: none;"
             class="w-full py-8 px-6 flex flex-col items-center justify-center text-center bg-[#0E0E10] border border-white/10 rounded-2xl shadow-xl transition-all my-1">
            <div class="w-12 h-12 rounded-xl bg-white/[0.03] border border-white/10 flex items-center justify-center text-[#8A8D93] mb-2.5">
                <i class="ph-bold ph-archive-box text-xl text-[#FF6B00]"></i>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-[9px] font-montserrat font-bold uppercase tracking-wider mb-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                <span>Stok Habis / Belum Diisi</span>
            </div>
            <p class="text-xs font-montserrat font-bold text-white">
                Paket Kategori Ini Belum Tersedia
            </p>
            <p class="text-[11px] font-questrial text-[#8A8D93] mt-0.5 max-w-sm mx-auto leading-relaxed">
                Layanan untuk kategori ini saat ini belum diisi atau stok material sedang habis di workshop kami.
            </p>
            <button type="button"
                    onclick="applyCategoryFilter('all', document.querySelector('.cat-filter-btn'))"
                    class="mt-3 px-3.5 py-1.5 text-[10px] font-montserrat font-bold uppercase tracking-wider text-black bg-[#FF6B00] hover:bg-[#E05D00] rounded-xl transition-all active:scale-95 shadow-md shadow-[#FF6B00]/25">
                Lihat Semua Paket
            </button>
        </div>

    </div>
</div>

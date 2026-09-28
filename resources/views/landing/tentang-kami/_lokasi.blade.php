{{-- ============================================
    BAGIAN: Lokasi Kami (Google Maps)
    Deskripsi: Menampilkan lokasi perusahaan dari field
    maps_url pada Pengaturan Perusahaan (diisi admin).
    Section otomatis tersembunyi jika maps_url kosong.
============================================ --}}
@if(!empty($profil->maps_url))
    <div class="rounded-[32px] border border-white/5 overflow-hidden shadow-2xl z-10 relative bg-[#121212]" data-aos="zoom-in">
        <div class="p-6 sm:p-8 flex flex-wrap justify-between items-center gap-4" style="background: linear-gradient(135deg, rgba(242, 153, 74, 0.06) 0%, rgba(0, 0, 0, 0) 60%), #0c0c0c;">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-[#f2994a]/10 flex items-center justify-center text-[#f2994a]">
                    <i class="ph-bold ph-map-trifold text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-white">{{ $profil->label_temukan_kami ?? 'Temukan Kami' }}</h4>
                    <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $profil->alamat ?? 'Klik peta untuk navigasi langsung' }}</p>
                </div>
            </div>
            <a href="https://maps.google.com/?q={{ urlencode($profil->alamat ?? '') }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-bold text-black bg-gradient-to-r from-[#e28a44] to-[#f2994a] hover:opacity-90 transition-all hover:scale-105 active:scale-95">
                <i class="ph-bold ph-navigation text-sm"></i> Buka Google Maps
            </a>
        </div>
        <div class="w-full h-[380px] sm:h-[440px] bg-[#0b0b0b]">
            <iframe
                src="{{ $profil->maps_url }}"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full h-full"></iframe>
        </div>
    </div>
@endif
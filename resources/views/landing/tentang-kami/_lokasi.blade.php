{{-- ============================================
    BAGIAN: Lokasi Kami (Google Maps)
    Deskripsi: Menampilkan lokasi workshop dari field maps_url
============================================ --}}
@php
    $mapsEmbed = !empty($profil->maps_url) 
        ? $profil->maps_url 
        : 'https://maps.google.com/maps?q=' . urlencode($profil->alamat ?? \App\Helpers\StaticContent::COMPANY_ADDRESS) . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
@endphp
<div class="rounded-3xl border border-slate-200/90 overflow-hidden shadow-lg z-10 relative bg-white" data-aos="zoom-in">
    <div class="p-6 sm:p-8 flex flex-wrap justify-between items-center gap-4 bg-slate-900 text-white">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-500/15 border border-orange-500/30 flex items-center justify-center text-orange-400">
                <i class="ph-bold ph-map-pin text-2xl"></i>
            </div>
            <div>
                <h4 class="text-base sm:text-lg font-bold font-montserrat text-white">
                    {{ $profil->label_temukan_kami ?? 'Studio Workshop Kami' }}
                </h4>
                <p class="text-xs text-slate-400 font-medium mt-0.5">
                    {{ $profil->alamat ?? \App\Helpers\StaticContent::COMPANY_ADDRESS }}
                </p>
            </div>
        </div>
        
        <a href="https://maps.google.com/?q={{ urlencode($profil->alamat ?? \App\Helpers\StaticContent::COMPANY_ADDRESS) }}" target="_blank" rel="noopener"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-xs font-montserrat font-medium uppercase tracking-wider text-white bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 transition-all hover:scale-105 active:scale-95 shadow-md shadow-orange-500/20 text-center">
            <i class="ph-bold ph-navigation-arrow text-sm"></i>
            <span>Petunjuk Arah Google Maps</span>
        </a>
    </div>
    <div class="w-full h-[320px] sm:h-[400px] bg-slate-100">
        <iframe
            src="{{ $mapsEmbed }}"
            width="100%"
            height="100%"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            class="w-full h-full border-0"></iframe>
    </div>
</div>
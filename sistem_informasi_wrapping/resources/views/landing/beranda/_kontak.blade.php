<section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="kontak">
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-[#f2994a]/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block mb-3">{{ $profil->home_section_kontak_badge ?: 'Kontak & Lokasi' }}</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                {{ $profil->home_section_kontak_title ?: 'Temukan Kami' }}
            </h2>
            <p class="text-gray-400 text-sm mt-3 max-w-lg mx-auto">{{ $profil->home_section_kontak_desc ?: 'Kunjungi workshop kami atau hubungi melalui kontak di bawah ini.' }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-stretch">
            <div class="bg-[#121212] border border-white/5 rounded-[32px] p-8 sm:p-10 space-y-8" data-aos="fade-right">
                <div class="space-y-6">
                    @if($profil->alamat)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a] flex-shrink-0">
                                <i class="ph-bold ph-map-pin text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">{{ $profil->home_kontak_alamat_label ?: 'Alamat' }}</h4>
                                <p class="text-gray-400 text-sm leading-relaxed">{{ $profil->alamat }}</p>
                                @if($profil->maps_url)
                                    <a href="{{ $profil->maps_url }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-[#f2994a] hover:text-[#e28a44] mt-2 transition-colors">
                                        <i class="ph-bold ph-map-trifold"></i> {{ $profil->cta_buka_maps ?? 'Buka Google Maps' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($profil->nomor_telepon)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a] flex-shrink-0">
                                <i class="ph-bold ph-phone text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">{{ $profil->home_kontak_telepon_label ?: 'Telepon' }}</h4>
                                <p class="text-gray-400 text-sm">{{ $profil->nomor_telepon }}</p>
                            </div>
                        </div>
                    @endif

                    @if($profil->email)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a] flex-shrink-0">
                                <i class="ph-bold ph-envelope text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">{{ $profil->home_kontak_email_label ?: 'Email' }}</h4>
                                <p class="text-gray-400 text-sm">{{ $profil->email }}</p>
                            </div>
                        </div>
                    @endif

                    @if($profil->jam_operasional)
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-[#f2994a] flex-shrink-0">
                                <i class="ph-bold ph-clock text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">{{ $profil->home_kontak_jam_label ?: 'Jam Operasional' }}</h4>
                                <p class="text-gray-400 text-sm">{{ $profil->jam_operasional }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3 pt-4 border-t border-white/5">
                    @if($profil->instagram_url)
                        <a href="{{ $profil->instagram_url }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] transition-all">
                            <i class="ph-bold ph-instagram-logo"></i>
                        </a>
                    @endif
                    @if($profil->facebook_url)
                        <a href="{{ $profil->facebook_url }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] transition-all">
                            <i class="ph-bold ph-facebook-logo"></i>
                        </a>
                    @endif
                    @if($profil->tiktok_url)
                        <a href="{{ $profil->tiktok_url }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-[#f2994a] hover:border-[#f2994a] transition-all">
                            <i class="ph-bold ph-tiktok-logo"></i>
                        </a>
                    @endif
                </div>
            </div>

            @if($profil->maps_url)
                @php
                    $embedSrc = $profil->maps_url;
                    if (!str_contains($profil->maps_url, 'embed')) {
                        preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $profil->maps_url, $matches);
                        if (!empty($matches[1]) && !empty($matches[2])) {
                            $embedSrc = 'https://www.google.com/maps/embed?q=' . $matches[1] . ',' . $matches[2];
                        } elseif ($profil->alamat) {
                            $embedSrc = 'https://www.google.com/maps/embed?q=' . urlencode($profil->alamat);
                        }
                    }
                @endphp
                <div class="rounded-[32px] overflow-hidden border border-white/5 h-full min-h-[300px]" data-aos="fade-left">
                    <iframe src="{{ $embedSrc }}"
                            width="100%" height="100%" style="min-height:400px" loading="lazy"
                            allowfullscreen referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full object-cover">
                    </iframe>
                </div>
            @else
                <div class="rounded-[32px] overflow-hidden border border-white/5 bg-[#121212] flex items-center justify-center h-full min-h-[300px]" data-aos="fade-left">
                    <p class="text-gray-500 text-sm">{{ $profil->home_kontak_no_map ?: 'Peta belum tersedia' }}</p>
                </div>
            @endif
        </div>
    </div>
</section>

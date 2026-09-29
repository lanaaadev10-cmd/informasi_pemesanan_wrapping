{{-- Footer Utama (Layout Central) --}}
@if($is_frontend)
    <footer class="bg-racing-black pt-20 pb-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 text-center">
            {{-- Brand Logo/Name --}}
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 justify-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-racing-orangeHover to-racing-orangeLight rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="ph-bold ph-sketch-logo text-2xl"></i>
                    </div>
                    <span class="font-extrabold text-2xl tracking-wider text-white uppercase">{{ \App\Helpers\StaticContent::APP_NAME }}</span>
                </a>
            </div>

            {{-- Horizontal Nav Links --}}
            <div class="flex flex-wrap justify-center gap-8 md:gap-12 mb-10 text-sm font-medium text-gray-400">
                <a href="{{ route('profil.perusahaan') }}" class="hover:text-racing-orangeLight transition-all">{{ \App\Helpers\StaticContent::FOOTER_TENTANG }}</a>
                <a href="{{ route('layanan') }}" class="hover:text-racing-orangeLight transition-all">{{ \App\Helpers\StaticContent::FOOTER_LAYANAN }}</a>
                <a href="{{ route('kebijakan-privasi') }}" class="hover:text-racing-orangeLight transition-all">{{ \App\Helpers\StaticContent::FOOTER_PRIVASI }}</a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" class="hover:text-racing-orangeLight transition-all">{{ \App\Helpers\StaticContent::FOOTER_HUBUNGI }}</a>
            </div>

            {{-- Social Icons --}}
            <div class="flex justify-center gap-6 mb-10">
                <a href="{{ \App\Helpers\StaticContent::INSTAGRAM_URL }}" target="_blank" aria-label="{{ \App\Helpers\StaticContent::FOOTER_INSTAGRAM }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-racing-orangeLight hover:border-racing-orangeLight hover:bg-white/10 transition-all duration-300">
                    <i class="ph-bold ph-instagram-logo text-lg"></i>
                </a>
                <a href="mailto:{{ \App\Helpers\StaticContent::COMPANY_EMAIL }}" aria-label="Email" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-racing-orangeLight hover:border-racing-orangeLight hover:bg-white/10 transition-all duration-300">
                    <i class="ph-bold ph-envelope text-lg"></i>
                </a>
                <a href="{{ \App\Helpers\StaticContent::COMPANY_WHATSAPP }}" target="_blank" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-300 hover:text-racing-orangeLight hover:border-racing-orangeLight hover:bg-white/10 transition-all duration-300">
                    <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                </a>
            </div>

            {{-- Copyright Notice --}}
            <div class="pt-8 border-t border-white/5 text-gray-500 text-xs font-medium">
                <p>{!! \App\Helpers\StaticContent::FOOTER_COPYRIGHT !!}</p>
            </div>
        </div>
    </footer>
@else
    <footer class="bg-gray-50 pt-24 pb-12 border-t border-gray-100 mt-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 md:col-span-1">
                    <h5 class="font-bold text-xl text-gray-900 mb-8 uppercase">{{ $profil->nama_perusahaan ?? 'Dantie' }}</h5>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        {{ $profil->deskripsi ?? 'Solusi stiker terbaik untuk kendaraan dan bisnis Anda.' }}
                    </p>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_navigasi ?? 'Navigasi' }}</h5>
                    <ul class="space-y-4 text-sm font-medium text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-racing-orange transition-all">{{ $profil->nav_beranda ?? 'Beranda' }}</a></li>
                        <li><a href="{{ route('profil.perusahaan') }}" class="hover:text-racing-orange transition-all">{{ $profil->nav_profil_perusahaan ?? 'Profil Perusahaan' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_hubungi_kami ?? 'Hubungi Kami' }}</h5>
                    <ul class="space-y-4 text-sm font-medium text-gray-500">
                        <li class="flex items-center gap-3">
                            <i class="ph ph-envelope-simple text-racing-orange text-lg"></i>
                            {{ $profil->email ?? '-' }}
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="ph ph-phone text-racing-orange text-lg"></i>
                            {{ $profil->nomor_telepon ?? '-' }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-gray-900 mb-8 tracking-widest uppercase text-xs">{{ $profil->footer_lokasi ?? 'Lokasi Kami' }}</h5>
                    <p class="text-gray-500 text-sm leading-relaxed italic">
                        {{ $profil->alamat ?? 'Alamat belum diatur' }}
                    </p>
                </div>
            </div>
            
            <div class="pt-12 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-gray-400 font-medium">
                <p>{{ $profil->footer_copyright ?? '&copy; 2026 Dantie Sticker. All rights reserved.' }}</p>
                <div class="flex gap-6 items-center">
                    @if(!empty($profil->instagram_url))
                        <a href="{{ $profil->instagram_url }}" target="_blank" class="hover:text-racing-orange transition-colors flex items-center gap-1"><i class="ph-bold ph-instagram-logo"></i> {{ $profil->footer_instagram ?? 'Instagram' }}</a>
                    @endif
                    @if(!empty($profil->facebook_url))
                        <a href="{{ $profil->facebook_url }}" target="_blank" class="hover:text-racing-orange transition-colors flex items-center gap-1"><i class="ph-bold ph-facebook-logo"></i> {{ $profil->footer_facebook ?? 'Facebook' }}</a>
                    @endif
                    @if(!empty($profil->tiktok_url))
                        <a href="{{ $profil->tiktok_url }}" target="_blank" class="hover:text-racing-orange transition-colors flex items-center gap-1"><i class="ph-bold ph-tiktok-logo"></i> TikTok</a>
                    @endif
                    @if(!empty($profil->whatsapp_link))
                        <a href="{{ $profil->whatsapp_link }}" target="_blank" class="hover:text-racing-orange transition-colors flex items-center gap-1"><i class="ph-bold ph-whatsapp-logo"></i> WhatsApp</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
@endif

{{-- Footer Utama (Layout Central) - Clean Modern White --}}
<footer class="bg-slate-50 pt-16 pb-12 border-t border-slate-200/90 text-slate-600 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center space-y-8">
        {{-- Brand Logo/Name --}}
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 justify-center">
                <div class="w-10 h-10 bg-gradient-to-br from-[#ea580c] to-[#f97316] rounded-xl flex items-center justify-center text-white shadow-md">
                    <i class="ph-bold ph-sketch-logo text-2xl"></i>
                </div>
                <span class="font-black text-2xl tracking-wider text-slate-950 uppercase">{{ $profil->nama_perusahaan ?? \App\Helpers\StaticContent::APP_NAME }}</span>
            </a>
            <p class="text-xs text-slate-500 mt-2 max-w-md mx-auto">
                Studio wrapping kendaraan presisi tinggi, perlindungan cat orisinil, dan modifikasi estetika bergaransi resmi.
            </p>
            @php
                $footerPhone = $profil->nomor_telepon ?? ($profil->telepon ?? null);
                $footerAddress = $profil->alamat_perusahaan ?? ($profil->alamat ?? null);
            @endphp
            @if(!empty($footerPhone))
            <p class="text-xs text-slate-500 mt-2 font-medium">
                <i class="ph-bold ph-phone mr-1"></i> {{ $footerPhone }}
                @if(!empty($footerAddress))
                    <span class="mx-2">•</span> <i class="ph-bold ph-map-pin mr-1"></i> {{ $footerAddress }}
                @endif
            </p>
            @endif
        </div>

        {{-- Horizontal Nav Links --}}
        <div class="flex flex-wrap justify-center gap-3.5 sm:gap-6 md:gap-10 text-xs sm:text-sm font-bold text-slate-700">
            <a href="{{ route('home') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::NAV_BERANDA }}</a>
            <a href="{{ route('layanan') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::FOOTER_LAYANAN }}</a>
            <a href="{{ route('galeri.user') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::NAV_GALERI }}</a>
            <a href="{{ route('tentang-kami') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::FOOTER_TENTANG }}</a>
            <a href="{{ route('testimoni.index') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::NAV_TESTIMONI }}</a>
            <a href="{{ route('kebijakan-privasi') }}" class="hover:text-[#ea580c] transition-colors">{{ \App\Helpers\StaticContent::FOOTER_PRIVASI }}</a>
        </div>

        {{-- Social Icons --}}
        <div class="flex justify-center gap-4">
            <a href="{{ $profil->instagram_url ?? \App\Helpers\StaticContent::INSTAGRAM_URL }}" target="_blank" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-white hover:bg-[#ea580c] hover:border-[#ea580c] flex items-center justify-center transition-all duration-300 shadow-sm">
                <i class="ph-bold ph-instagram-logo text-lg"></i>
            </a>
            <a href="mailto:{{ $profil->email_perusahaan ?? ($profil->email ?? \App\Helpers\StaticContent::COMPANY_EMAIL) }}" aria-label="Email" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-white hover:bg-[#ea580c] hover:border-[#ea580c] flex items-center justify-center transition-all duration-300 shadow-sm">
                <i class="ph-bold ph-envelope text-lg"></i>
            </a>
            <a href="{{ $profil->whatsapp_link ?? \App\Helpers\StaticContent::COMPANY_WHATSAPP }}" target="_blank" aria-label="WhatsApp" class="w-10 h-10 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-white hover:bg-[#ea580c] hover:border-[#ea580c] flex items-center justify-center transition-all duration-300 shadow-sm">
                <i class="ph-bold ph-whatsapp-logo text-lg"></i>
            </a>
        </div>

        {{-- Copyright Notice --}}
        <div class="pt-8 border-t border-slate-200 text-slate-500 text-xs font-medium">
            <p>{!! \App\Helpers\StaticContent::FOOTER_COPYRIGHT !!}</p>
        </div>
    </div>
</footer>

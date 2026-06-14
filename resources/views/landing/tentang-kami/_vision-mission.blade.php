{{-- ============================================
    BAGIAN: Visi & Misi
    Deskripsi: Kolom visi dan misi perusahaan dengan desain premium card
============================================ --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 z-10 relative" data-aos="fade-up">
    <!-- VISI -->
    <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group">
        <div class="space-y-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 bg-[#f2994a]/10 border border-[#f2994a]/20">
                <i class="ph-bold ph-eye text-2xl text-[#f2994a]"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ $profil->tentang_kami_visi_title ?? 'Visi Kami' }}</h3>
            <div class="text-gray-400 text-sm sm:text-base leading-relaxed prose prose-invert max-w-none">
                @if(!empty($profil->visi))
                    {!! $profil->visi !!}
                @else
                    <p>Menjadi studio wrapping terdepan yang dikenal dengan kualitas premium dan inovasi berkelanjutan dalam industri modifikasi estetika kendaraan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- MISI -->
    <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group">
        <div class="space-y-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 bg-[#f2994a]/10 border border-[#f2994a]/20">
                <i class="ph-bold ph-target text-2xl text-[#f2994a]"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ $profil->tentang_kami_misi_title ?? 'Misi Kami' }}</h3>
            <div class="text-gray-400 text-sm sm:text-base leading-relaxed prose prose-invert max-w-none">
                @if(!empty($profil->misi))
                    {!! $profil->misi !!}
                @else
                    <p>Memberikan solusi wrapping berkualitas tinggi dengan menggunakan material premium dunia, teknik instalasi presisi, dan komitmen layanan pelanggan yang tiada tanding.</p>
                @endif
            </div>
        </div>
    </div>
</div>

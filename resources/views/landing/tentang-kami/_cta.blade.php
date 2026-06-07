{{-- ============================================
    BAGIAN: Call To Action (CTA)
    Deskripsi: Ajakan untuk menghubungi WhatsApp atau melihat portofolio
============================================ --}}
<div class="rounded-[32px] p-8 sm:p-12 lg:p-16 text-center space-y-6 z-10 relative overflow-hidden shadow-2xl border border-white/5 hover:border-[#f2994a]/20 transition-all duration-500" 
     style="background: linear-gradient(135deg, rgba(242, 153, 74, 0.08) 0%, rgba(0, 0, 0, 0) 100%), #121212;"
     data-aos="zoom-in">
    <div class="absolute top-0 left-0 w-64 h-64 bg-[#f2994a]/5 blur-[80px] rounded-full pointer-events-none"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto space-y-6">
        <h3 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
            {{ $profil->tentang_kami_cta_title ?? 'Siap Mengubah Tampilan Kendaraan Anda?' }}
        </h3>
        <p class="text-gray-400 text-sm sm:text-base leading-relaxed">
            {{ $profil->tentang_kami_cta_desc ?? 'Jadikan kendaraan Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim kami yang berpengalaman.' }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" 
               target="_blank"
               class="inline-flex items-center justify-center px-8 py-4 text-black font-extrabold rounded-2xl hover:opacity-90 transition-all duration-300 shadow-lg group bg-gradient-to-r from-[#e28a44] to-[#f2994a] hover:scale-105 active:scale-95">
                <span>{{ $profil->tentang_kami_cta_primary_button ?? 'Hubungi Kami Sekarang' }}</span>
                <i class="ph-bold ph-arrow-right text-base ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            <a href="{{ route('galeri.user') }}" 
               class="inline-flex items-center justify-center px-8 py-4 font-bold rounded-2xl transition-all duration-300 border border-white/10 hover:bg-white/5 text-white hover:scale-105 active:scale-95">
                <span>{{ $profil->tentang_kami_cta_secondary_button ?? 'Lihat Portofolio' }}</span>
            </a>
        </div>
    </div>
</div>

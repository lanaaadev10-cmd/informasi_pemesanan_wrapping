{{-- ============================================
    BAGIAN: Call To Action (CTA) - Clean & Minimalist
    Deskripsi: Ajakan konsultasi gratis & portofolio yang rapi dan terfokus
============================================ --}}
<div class="rounded-3xl p-8 sm:p-14 text-center space-y-6 z-10 relative overflow-hidden shadow-lg border border-slate-800 bg-slate-900 text-white" 
     data-aos="zoom-in">
    <div class="absolute -top-24 -left-24 w-72 h-72 bg-[#ea580c]/10 blur-[100px] rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-[#ea580c]/10 blur-[100px] rounded-full pointer-events-none"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto space-y-5">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-montserrat text-white tracking-tight leading-snug">
            {{ \App\Helpers\StaticContent::CTA_TENTANG_TITLE }}
        </h2>
        
        <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-normal">
            {{ \App\Helpers\StaticContent::CTA_TENTANG_DESC }}
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
            <a href="{{ $profil->whatsapp_link }}" target="_blank" rel="noopener"
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-white font-montserrat font-medium text-xs uppercase tracking-wider rounded-full shadow-md bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 transition-all hover:scale-105 active:scale-95">
                <span>{{ \App\Helpers\StaticContent::CTA_TENTANG_BTN }}</span>
                <i class="ph-bold ph-arrow-right text-sm"></i>
            </a>
            
            <a href="{{ route('galeri.user') }}" 
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 font-montserrat font-medium text-xs uppercase tracking-wider rounded-full transition-all border border-slate-700 hover:border-slate-500 bg-slate-800/80 text-slate-200 hover:text-white hover:scale-105 active:scale-95">
                <span>{{ \App\Helpers\StaticContent::CTA_TENTANG_SEC }}</span>
            </a>
        </div>
    </div>
</div>


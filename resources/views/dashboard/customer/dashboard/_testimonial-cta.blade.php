<!-- Kartu CTA Beri Testimoni (Alur 2 — rating tanpa pesanan) -->
<div class="bg-[#0E0E10] border border-white/10 rounded-3xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-[#FF6B00]/40 transition-all duration-300 shadow-xl relative overflow-hidden">
    <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-[#FF6B00]/10 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="flex items-start gap-5 relative z-10">
        <div class="w-14 h-14 rounded-2xl bg-[#FF6B00]/10 border border-[#FF6B00]/30 flex items-center justify-center text-[#FF6B00] shrink-0">
            <i class="ph-bold ph-star text-2xl"></i>
        </div>
        <div class="space-y-1.5">
            <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->testimoni_cta_title ?? 'Beri Testimoni' }}</h3>
            <p class="text-xs text-gray-400 font-light leading-relaxed max-w-md">
                {{ $profil->testimoni_cta_desc ?? 'Sudah pernah menggunakan layanan kami sebelumnya? Bagikan pengalaman Anda untuk membantu pelanggan lain.' }}
            </p>
        </div>
    </div>

    <a href="{{ route('rating.layanan.form') }}" class="inline-flex items-center justify-center gap-2 bg-[#FF6B00] hover:bg-[#E05D00] text-black font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl transition-all shadow-[0_4px_16px_rgba(255,107,0,0.35)] hover:scale-105 active:scale-95 shrink-0 relative z-10">
        <i class="ph-bold ph-star text-sm"></i> {{ $profil->cta_rating ?? 'Tulis Ulasan' }}
    </a>
</div>
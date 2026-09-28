<!-- Kartu CTA Beri Testimoni (Alur 2 — rating tanpa pesanan) -->
<div class="bg-gradient-to-br from-[#f2994a]/15 to-[#e28a44]/5 border border-[#f2994a]/25 rounded-3xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-[#f2994a]/50 transition-all duration-300 shadow-xl relative overflow-hidden">
    <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-[#f2994a]/15 rounded-full blur-[80px] pointer-events-none"></div>

    <div class="flex items-start gap-5 relative z-10">
        <div class="w-14 h-14 rounded-2xl bg-[#f2994a]/15 border border-[#f2994a]/30 flex items-center justify-center text-[#f2994a] shrink-0">
            <i class="ph-bold ph-star text-2xl"></i>
        </div>
        <div class="space-y-1.5">
            <h3 class="text-lg font-bold text-white tracking-tight">{{ $profil->testimoni_cta_title ?? 'Beri Testimoni' }}</h3>
            <p class="text-xs text-gray-400 font-light leading-relaxed max-w-md">
                {{ $profil->testimoni_cta_desc ?? 'Sudah pernah menggunakan layanan kami sebelumnya? Bagikan pengalaman Anda untuk membantu pelanggan lain.' }}
            </p>
        </div>
    </div>

    <a href="{{ route('rating.layanan.form') }}" class="inline-flex items-center justify-center gap-2 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95 shrink-0 relative z-10">
        <i class="ph-bold ph-star text-sm"></i> {{ $profil->cta_rating ?? 'Tulis Ulasan' }}
    </a>
</div>
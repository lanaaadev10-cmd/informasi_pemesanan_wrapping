<!-- Empty State in gorgeous dark premium layout -->
<div class="bg-white/[0.01] border border-white/5 rounded-[32px] p-16 text-center shadow-lg relative overflow-hidden z-10">
    <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#f2994a]/5 blur-[80px] rounded-full"></div>
    <div class="relative z-10 max-w-md mx-auto space-y-6">
        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center text-gray-400 mx-auto shadow-inner border border-white/5">
            <i class="ph-bold ph-shopping-bag text-3xl text-gray-500"></i>
        </div>
        <div class="space-y-2">
            <h3 class="text-xl font-bold text-white">Keranjang Kosong</h3>
            <p class="text-gray-400 text-xs font-light leading-relaxed">Sepertinya Anda belum memilih layanan wrapping premium terbaik untuk kendaraan Anda.</p>
        </div>
        <a href="{{ route('katalog.user') }}" 
           class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black rounded-2xl font-extrabold text-xs tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
            Explore Layanan &rarr;
        </a>
    </div>
</div>

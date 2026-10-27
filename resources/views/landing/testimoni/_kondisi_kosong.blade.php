{{-- ============================================
    BAGIAN: Kondisi Kosong (Empty State) Testimoni
    Deskripsi: Tampilan saat belum ada ulasan publik di sistem
============================================ --}}
<div class="bg-white border border-slate-200/90 rounded-3xl p-10 sm:p-14 text-center max-w-xl mx-auto shadow-sm space-y-4" data-aos="fade-up">
    <div class="w-16 h-16 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center mx-auto text-[#ea580c] text-2xl shadow-sm">
        <i class="ph-fill ph-star"></i>
    </div>
    
    <h2 class="text-xl sm:text-2xl font-bold font-montserrat text-slate-900 tracking-tight">
        Belum Ada Ulasan Publik
    </h2>
    
    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto font-normal leading-relaxed">
        Jadilah yang pertama berbagi pengalaman Anda! Ulasan pelanggan akan otomatis ditampilkan di sini setelah menyelesaikan pesanan di workshop kami.
    </p>

    <div class="pt-2">
        <a href="{{ route('layanan') }}" 
           class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 text-white font-montserrat font-medium text-xs shadow-md shadow-orange-500/20 transition-all hover:scale-105 active:scale-95">
            <i class="ph-bold ph-arrow-right text-white"></i>
            <span>Jelajahi Paket Layanan</span>
        </a>
    </div>
</div>

{{-- ============================================
    BAGIAN: Filter & Pengurutan Ulasan Testimoni
    Deskripsi: Toolbar filter bintang dan tombol sortir terbaru / tertinggi
============================================ --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/80 border border-slate-200/80 p-3 rounded-2xl" data-aos="fade-up">
    {{-- Filter Bintang --}}
    <div class="flex flex-wrap gap-1.5 items-center flex-1">
        <a href="{{ route('testimoni.index', ['sort' => $sort]) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-medium transition-all {{ !$bintang ? 'bg-[#ea580c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80' }}">
           Semua Ulasan
        </a>
        @for($i = 5; $i >= 1; $i--)
            <a href="{{ route('testimoni.index', ['bintang' => $i, 'sort' => $sort]) }}" 
               class="px-2.5 py-1.5 rounded-xl text-xs font-montserrat font-medium transition-all flex items-center gap-1 {{ $bintang === $i ? 'bg-[#ea580c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80' }}">
                <span>{{ $i }}</span>
                <i class="ph-fill ph-star {{ $bintang === $i ? 'text-white' : 'text-amber-400' }}"></i>
            </a>
        @endfor
    </div>

    {{-- Sort Order --}}
    <div class="flex items-center gap-1.5 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60 w-full sm:w-auto justify-end sm:justify-start">
        <a href="{{ route('testimoni.index', ['bintang' => $bintang ?: null, 'sort' => 'terbaru']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-medium transition-all {{ $sort === 'terbaru' ? 'bg-[#ea580c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80' }}">
           Terbaru
        </a>
        <a href="{{ route('testimoni.index', ['bintang' => $bintang ?: null, 'sort' => 'tertinggi']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-montserrat font-medium transition-all {{ $sort === 'tertinggi' ? 'bg-[#ea580c] text-white shadow-sm' : 'bg-white text-slate-600 hover:text-slate-900 border border-slate-200/80' }}">
           Tertinggi
        </a>
    </div>
</div>

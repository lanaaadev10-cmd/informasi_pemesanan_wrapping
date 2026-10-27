{{-- ============================================
    BAGIAN: Ringkasan Statistik Rating & Ulasan
    Deskripsi: Kartu metrik rata-rata rating dan breakdown per layanan
============================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
    {{-- Kartu Metrik Utama --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Rata-rata Penilaian</span>
        <p class="text-3xl sm:text-4xl font-black font-montserrat text-slate-900">
            {{ number_format($summary['average'], 1, ',', '.') }}<span class="text-base font-normal text-slate-400"> / 5.0</span>
        </p>
        <div class="text-amber-500 mt-2 text-sm flex items-center gap-1">
            @for($i = 1; $i <= 5; $i++)
                <i class="ph-fill ph-star {{ $i <= round($summary['average']) ? 'text-amber-400' : 'text-slate-200' }}"></i>
            @endfor
        </div>
        <p class="text-xs text-slate-500 mt-2 font-medium">Berdasarkan {{ $summary['total'] }} ulasan terverifikasi</p>
    </div>

    {{-- Kartu Rating Per Layanan --}}
    @foreach($summary['per_layanan']->take(3) as $layanan)
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 truncate">{{ $layanan['nama_layanan'] }}</p>
                <p class="text-2xl font-black font-montserrat text-slate-900 mt-1">
                    {{ number_format($layanan['avg'], 1, ',', '.') }}<span class="text-xs font-normal text-slate-400"> / 5.0</span>
                </p>
            </div>
            <div>
                <div class="text-amber-400 text-xs flex items-center gap-0.5 mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ph-fill ph-star {{ $i <= round($layanan['avg']) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                    @endfor
                </div>
                <p class="text-[11px] text-slate-500 mt-1.5 font-medium">{{ $layanan['count'] }} ulasan</p>
            </div>
        </div>
    @endforeach
</div>

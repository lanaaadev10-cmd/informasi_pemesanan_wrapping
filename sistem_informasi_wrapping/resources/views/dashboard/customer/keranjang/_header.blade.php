<!-- Header Section -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 z-10 relative">
    <div>
        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">{{ $profil->keranjang_hero_text ?? 'YOUR SELECTION' }}</span>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mt-1">
            {{ $keranjangTitle }}
        </h1>
        <p class="text-gray-400 text-xs sm:text-sm font-light mt-1">{{ $keranjangSubtitle }}</p>
    </div>
    
    @if($keranjang && $keranjang->details->isNotEmpty())
        <div class="flex items-center shrink-0">
            <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('{{ $profil->alert_konfirmasi_kosongkan ?? 'Apakah Anda yakin ingin mengosongkan seluruh isi keranjang?' }}')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500/10 hover:bg-red-500/15 border border-red-500/20 hover:border-red-500/35 text-red-400 hover:text-red-300 rounded-2xl font-bold text-[10px] tracking-wider uppercase transition-all active:scale-95 shadow-sm">
                    <i class="ph-bold ph-trash-simple text-xs"></i>
                    <span>{{ $profil->cta_kosongkan ?? 'Kosongkan Keranjang' }}</span>
                </button>
            </form>
        </div>
    @endif
</div>

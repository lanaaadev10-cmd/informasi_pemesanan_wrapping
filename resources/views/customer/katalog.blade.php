<div class="px-4 py-6 max-w-3xl mx-auto">

    <h2 class="text-xl font-semibold text-gray-900 mb-1">Katalog Layanan</h2>
    <p class="text-sm text-gray-500 mb-5">Pilih layanan yang sesuai kebutuhan Anda</p>

    @foreach($layanan as $item)
    <div class="flex items-start justify-between gap-4 bg-white border border-gray-100 rounded-2xl px-5 py-4 mb-3 hover:border-gray-200 transition-colors">

        {{-- Kiri: Ikon + Info --}}
        <div class="flex items-start gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-content-center shrink-0">
                {{-- Ganti dengan ikon sesuai kategori layanan --}}
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 mb-0.5 flex items-center gap-2">
                    {{ $item->nama_layanan }}

                    {{-- Badge tipe layanan --}}
                    @if($item->tipe_layanan == 'fix')
                        <span class="text-[11px] font-semibold uppercase tracking-wide bg-sky-100 text-sky-700 px-2.5 py-0.5 rounded-full">Fix</span>
                    @else
                        <span class="text-[11px] font-semibold uppercase tracking-wide bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full">Custom</span>
                    @endif
                </p>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $item->deskripsi }}</p>
            </div>
        </div>

        {{-- Kanan: Harga --}}
        <div class="text-right shrink-0">
            @if($item->tipe_layanan == 'fix')
                <p class="text-base font-semibold text-gray-900">Rp {{ number_format($item->harga) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">harga tetap</p>
            @else
                <p class="text-sm font-medium text-gray-400 italic">Harga menyesuaikan</p>
                <p class="text-xs text-gray-400 mt-0.5">hubungi kami</p>
            @endif
        </div>

    </div>
    @endforeach

</div>
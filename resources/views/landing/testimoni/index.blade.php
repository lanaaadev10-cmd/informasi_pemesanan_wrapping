@extends('layouts.tampilan_utama')

@php
    $accentColor = '#f2994a';
    $sort = request('sort') === 'tertinggi' ? 'tertinggi' : 'terbaru';
@endphp

@section('title', 'Testimoni Pelanggan')

@section('content')
<div class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 pb-24 pt-10">
    <!-- HEADER -->
    <div class="text-center mb-10">
        <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#f2994a]/10 text-[#f2994a] border border-[#f2994a]/20 mb-6">Testimoni</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-4">Apa Kata Pelanggan</h1>
        <p class="text-gray-400 leading-relaxed max-w-2xl mx-auto">
            Penilaian jujur dari pelanggan untuk setiap layanan pembungkusan premium kami.
        </p>
    </div>

    @if($summary['total'] > 0)
        <!-- SUMMARY -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-6">
                <p class="text-4xl font-extrabold text-white">{{ number_format($summary['average'], 1, ',', '.') }}<span class="text-lg text-gray-500">/5</span></p>
                <p class="text-amber-400 mt-1 text-sm tracking-widest">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= round($summary['average']) ? '' : 'opacity-25' }}">&#9733;</span>
                    @endfor
                </p>
                <p class="text-xs text-gray-500 mt-2">{{ $summary['total'] }} ulasan</p>
            </div>

            @foreach($summary['per_layanan']->take(3) as $layanan)
                <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500">{{ $layanan['nama_layanan'] }}</p>
                    <p class="text-2xl font-extrabold text-white mt-2">{{ number_format($layanan['avg'], 1, ',', '.') }}<span class="text-sm text-gray-500">/5</span></p>
                    <p class="text-amber-400 mt-1 text-sm text-[10px] tracking-widest">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= round($layanan['avg']) ? '' : 'opacity-25' }}">&#9733;</span>
                        @endfor
                    </p>
                    <p class="text-xs text-gray-500 mt-2">{{ $layanan['count'] }} ulasan</p>
                </div>
            @endforeach
        </div>

        <!-- FILTERS -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('testimoni.index', ['sort' => $sort]) }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ !$bintang ? 'bg-[#f2994a] text-black' : 'bg-white/5 text-gray-400 hover:text-white' }}">Semua</a>
                @for($i = 5; $i >= 1; $i--)
                    <a href="{{ route('testimoni.index', ['bintang' => $i, 'sort' => $sort]) }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $bintang === $i ? 'bg-[#f2994a] text-black' : 'bg-white/5 text-gray-400 hover:text-white' }}">
                        {{ $i }} &#9733;
                    </a>
                @endfor
            </div>
            <div class="sm:ml-auto flex gap-2">
                {{-- [DISABLED] Alur 2 — CTA beri rating (tanpa pesanan) dimatikan.
                <a href="{{ route('rating.layanan.form') }}" class="inline-flex items-center justify-center gap-2 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs uppercase tracking-wider px-6 py-2.5 rounded-full transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
                    <i class="ph-bold ph-star text-sm"></i> {{ auth()->check() ? 'Tulis Ulasan Anda' : 'Beri Rating' }}
                </a>
                --}}
                <a href="{{ route('testimoni.index', ['bintang' => $bintang ?: null, 'sort' => 'terbaru']) }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $sort === 'terbaru' ? 'bg-white/15 text-white' : 'bg-white/5 text-gray-400 hover:text-white' }}">Terbaru</a>
                <a href="{{ route('testimoni.index', ['bintang' => $bintang ?: null, 'sort' => 'tertinggi']) }}" class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $sort === 'tertinggi' ? 'bg-white/15 text-white' : 'bg-white/5 text-gray-400 hover:text-white' }}">Tertinggi</a>
            </div>
        </div>

        <!-- LIST -->
        @if($ratings->isEmpty())
            <div class="text-center py-16 border border-white/10 rounded-2xl bg-white/[0.02]">
                <p class="text-gray-500 text-sm">Belum ada ulasan dengan filter ini.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($ratings as $rating)
                    <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-bold text-white text-sm">{{ $rating->user?->name ?? 'Pengguna' }}</p>
                            <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full bg-[#f2994a]/10 text-[#f2994a] border border-[#f2994a]/20">{{ $rating->layanan?->nama_layanan }}</span>
                        </div>
                        <p class="text-amber-400 text-sm mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $rating->rating ? '' : 'opacity-25' }}">&#9733;</span>
                            @endfor
                        </p>
                        @if($rating->ulasan)
                            <p class="text-sm text-gray-400 leading-relaxed flex-1">"{{ $rating->ulasan }}"</p>
                        @else
                            <p class="text-sm text-gray-600 italic flex-1">Tanpa ulasan tertulis.</p>
                        @endif

                        @if($rating->balasan_admin)
                            <div class="mt-4 p-4 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-[#f2994a] mb-1">Balasan Wrapping Premium</p>
                                <p class="text-sm text-gray-300 leading-relaxed">"{{ $rating->balasan_admin }}"</p>
                                @if($rating->dibalas_at)
                                    <p class="text-[11px] text-gray-500 mt-2">Dibalas {{ $rating->dibalas_at->translatedFormat('d M Y') }}</p>
                                @endif
                            </div>
                        @endif

                        @if($rating->medias->isNotEmpty())
                            <div class="flex gap-2 mt-4">
                                @foreach($rating->medias as $media)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-white/10">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" class="w-full h-full object-cover" alt="Foto hasil {{ $rating->layanan?->nama_layanan }}">
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <p class="text-[11px] text-gray-600 mt-4">
                            {{ $rating->created_at?->translatedFormat('d M Y') }}
                            @if($rating->pesanan)
                                &middot; {{ $rating->pesanan->kode_pesanan }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="text-center py-20 border border-white/10 rounded-2xl bg-white/[0.02]">
            <p class="text-3xl mb-4 text-amber-400">&#9733;</p>
            <h2 class="text-xl font-bold text-white mb-2">Belum Ada Testimoni</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto mb-8">Jadilah yang pertama berbagi pengalaman Anda menggunakan layanan kami.</p>

            {{-- [DISABLED] Alur 2 — tombol "Beri Rating" (tanpa pesanan) dimatikan.
            <a href="{{ route('rating.layanan.form') }}" class="inline-flex items-center justify-center gap-2 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-xs uppercase tracking-wider px-8 py-4 rounded-xl transition-all shadow-[0_4px_15px_rgba(242,153,74,0.3)] hover:scale-105 active:scale-95">
                <i class="ph-bold ph-star text-sm"></i> {{ auth()->check() ? 'Tulis Ulasan Anda' : 'Beri Rating' }}
            </a>
            --}}
        </div>
    @endif
</div>
@endsection
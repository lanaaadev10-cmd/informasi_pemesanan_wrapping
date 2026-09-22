@extends('layouts.dashboard_customer')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';
    $pageTitle = 'Beri Rating';
    $pageDesc = 'Bagikan pengalaman Anda tentang setiap layanan dari pesanan #' . ($pesanan->kode_pesanan ?? $pesanan->id_pesanan) . '.';
@endphp

<style>
    :root { --accent-color: {{ $accentColor }}; }
    .accent-bg { background-color: var(--accent-color); }
</style>

@section('title', $pageTitle)

@section('content')
<div class="max-w-4xl mx-auto py-8 text-white space-y-8 relative">
    <div class="relative z-10">
        <h1 class="text-3xl font-bold tracking-tight">{{ $pageTitle }}</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $pageDesc }}</p>
        <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-white mt-3 transition-colors">
            &larr; Kembali ke detail pesanan
        </a>
    </div>

    @foreach($layanans as $layanan)
        @php
            $existing = $existingRatings->get($layanan->id_layanan);
        @endphp

        <div class="bg-[#121212] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-lg relative z-10">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-lg font-bold">{{ $layanan->nama_layanan }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $existing ? 'Ulasan Anda sudah ada — perbarui di bawah.' : 'Belum ada ulasan Anda untuk layanan ini.' }}
                    </p>
                </div>
                @if($existing)
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $existing->rating ? '' : 'opacity-25' }}">&#9733;</span>
                        @endfor
                    </span>
                @endif
            </div>

            <form action="{{ route('pesanan.rating.store', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-5">
                @csrf
                <input type="hidden" name="id_layanan" value="{{ $layanan->id_layanan }}">

                {{-- Star picker --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rating</label>
                    <div class="flex gap-2 text-3xl rating-stars" data-input="rating-{{ $layanan->id_layanan }}">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" class="rating-star transition-colors {{ $existing && $i <= $existing->rating ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }}">&#9733;</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-{{ $layanan->id_layanan }}" value="{{ $existing?->rating ?? '' }}" required>
                    @error('rating')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Ulasan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Ulasan <span class="text-gray-600 normal-case">(opsional)</span></label>
                    <textarea name="ulasan" rows="3" maxlength="500" placeholder="Ceritakan hasil wrapping Anda..." class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[{{ $accentColor }}] resize-none">{{ old('ulasan', $existing?->ulasan ?? '') }}</textarea>
                    <p class="text-right text-[11px] text-gray-600 mt-1">Maks. 500 karakter</p>
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Foto Hasil <span class="text-gray-600 normal-case">(opsional, maks. 2 foto — jpg/png/webp, maks. 5MB)</span></label>
                    <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-white/10 file:text-white file:text-xs file:font-bold hover:file:bg-white/20 cursor-pointer">
                    @error('foto')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('foto.*')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror

                    @if($existing && $existing->medias->isNotEmpty())
                        <div class="flex gap-3 mt-3">
                            @foreach($existing->medias as $media)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" target="_blank" class="block w-24 h-24 rounded-xl overflow-hidden border border-white/10">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" class="w-full h-full object-cover" alt="Foto rating">
                                </a>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-gray-500 mt-2">Foto lama akan diganti bila Anda mengunggah foto baru.</p>
                    @endif
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 accent-bg hover:opacity-90 text-black rounded-xl font-bold text-xs uppercase tracking-wider transition-all">
                    {{ $existing ? 'Perbarui Rating' : 'Kirim Rating' }}
                </button>
            </form>
        </div>
    @endforeach

    @if($layanans->isEmpty())
        <div class="bg-[#121212] border border-white/5 rounded-3xl p-16 text-center shadow-lg relative z-10">
            <h3 class="text-xl font-bold mb-2">Tidak ada layanan untuk dirating</h3>
            <p class="text-xs text-gray-400">Detail layanan tidak ditemukan pada pesanan ini.</p>
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.rating-stars').forEach(function (el) {
        const input = document.getElementById(el.dataset.input);
        el.querySelectorAll('.rating-star').forEach(function (star) {
            star.addEventListener('click', function () {
                const val = parseInt(star.dataset.value, 10);
                input.value = val;
                el.querySelectorAll('.rating-star').forEach(function (s, i) {
                    s.classList.toggle('text-yellow-500', i < val);
                    s.classList.toggle('text-gray-600', i >= val);
                });
            });
        });
    });
</script>
@endsection
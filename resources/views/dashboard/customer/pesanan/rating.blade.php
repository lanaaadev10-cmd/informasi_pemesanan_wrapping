@extends('layouts.dashboard-customer')

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
<div class="max-w-4xl mx-auto py-8 text-slate-900 space-y-8 relative">
    <div class="relative z-10">
        <h1 class="text-3xl font-audiowide font-bold tracking-tight text-slate-900">{{ $pageTitle }}</h1>
        <p class="text-sm font-questrial text-slate-500 mt-1">{{ $pageDesc }}</p>
        <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="inline-flex items-center gap-1.5 text-xs font-montserrat font-bold text-slate-500 hover:text-[#FF6B00] mt-3 transition-colors">
            &larr; Kembali ke detail pesanan
        </a>
    </div>

    @foreach($layanans as $layanan)
        @php
            $existing = $existingRatings->get($layanan->id_layanan);
        @endphp

        <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm relative z-10">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-lg font-montserrat font-bold text-slate-900">{{ $layanan->nama_layanan }}</h2>
                    <p class="text-xs font-questrial text-slate-500 mt-0.5">
                        {{ $existing ? 'Ulasan Anda sudah ada — perbarui di bawah.' : 'Belum ada ulasan Anda untuk layanan ini.' }}
                    </p>
                </div>
                @if($existing)
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $existing->rating ? 'text-amber-500' : 'text-slate-300' }}">&#9733;</span>
                        @endfor
                    </span>
                @endif
            </div>

            <form action="{{ route('pesanan.rating.store', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-5">
                @csrf
                <input type="hidden" name="id_layanan" value="{{ $layanan->id_layanan }}">

                {{-- Star picker --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Rating</label>
                    <div class="flex gap-2 text-3xl rating-stars" data-input="rating-{{ $layanan->id_layanan }}">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" class="rating-star transition-colors {{ $existing && $i <= $existing->rating ? 'text-amber-500' : 'text-slate-300 hover:text-amber-500' }}">&#9733;</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-{{ $layanan->id_layanan }}" value="{{ $existing?->rating ?? '' }}" required>
                    @error('rating')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Ulasan --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Ulasan <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
                    <textarea name="ulasan" rows="3" maxlength="500" placeholder="Ceritakan hasil wrapping Anda..." class="w-full bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#FF6B00] shadow-sm resize-none">{{ old('ulasan', $existing?->ulasan ?? '') }}</textarea>
                    <p class="text-right text-[11px] text-slate-400 mt-1">Maks. 500 karakter</p>
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Foto Hasil <span class="text-slate-400 normal-case font-normal">(opsional, maks. 2 foto — jpg/png/webp, maks. 5MB)</span></label>
                    <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-[#FF6B00] file:text-white file:text-xs file:font-bold hover:file:bg-[#E05D00] cursor-pointer bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('foto.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    @if($existing && $existing->medias->isNotEmpty())
                        <div class="flex gap-3 mt-3">
                            @foreach($existing->medias as $media)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" target="_blank" class="block w-24 h-24 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($media->path) }}" class="w-full h-full object-cover" alt="Foto rating">
                                </a>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Foto lama akan diganti bila Anda mengunggah foto baru.</p>
                    @endif
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
                    {{ $existing ? 'Perbarui Rating' : 'Kirim Rating' }}
                </button>
            </form>
        </div>
    @endforeach

    @if($layanans->isEmpty())
        <div class="bg-white border border-slate-200/80 rounded-3xl p-16 text-center shadow-sm relative z-10">
            <h3 class="text-xl font-audiowide font-bold text-slate-900 mb-2">Tidak ada layanan untuk dirating</h3>
            <p class="text-xs font-questrial text-slate-500">Detail layanan tidak ditemukan pada pesanan ini.</p>
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
                    s.classList.toggle('text-amber-500', i < val);
                    s.classList.toggle('text-slate-300', i >= val);
                });
            });
        });
    });
</script>
@endsection
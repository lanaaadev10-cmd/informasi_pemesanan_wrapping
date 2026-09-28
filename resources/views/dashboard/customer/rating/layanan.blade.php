@extends('layouts.dashboard_customer')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';
    $pageTitle = 'Beri Rating Layanan';
    $pageDesc = 'Sudah pernah berlangganan sebelum sistem ini? Beri penilaian untuk layanan yang pernah Anda pakai.';
@endphp

<style>
    :root { --accent-color: {{ $accentColor }}; }
    .accent-bg { background-color: var(--accent-color); }
</style>

@section('title', $pageTitle)

@section('content')
<div class="max-w-3xl mx-auto py-8 text-white relative">
    <div class="space-y-1 relative z-10">
        <h1 class="text-3xl font-bold tracking-tight">{{ $pageTitle }}</h1>
        <p class="text-sm text-gray-400">{{ $pageDesc }}</p>
    </div>

    <div class="bg-[#121212] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-lg mt-6 relative z-10">
        @if($layanans->isEmpty())
            <p class="text-sm text-gray-400">Belum ada layanan yang tersedia untuk dirating.</p>
        @else
            <form action="{{ route('rating.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Pilih layanan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Pilih Layanan</label>
                    <select name="id_layanan" id="layanan-select" class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-sm text-white focus:outline-none focus:border-[{{ $accentColor }}]">
                        <option value="" disabled selected>-- Pilih layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" class="text-gray-900">
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_layanan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Info: sudah pernah rating --}}
                <div id="already-rated-hint" class="hidden rounded-xl border border-green-500/20 bg-green-500/5 text-green-400 p-3 text-xs">
                    Anda sudah memberikan ulasan untuk layanan ini. Mengirim lagi akan <strong>memperbarui</strong> ulasan sebelumnya.
                </div>

                {{-- Star picker --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rating</label>
                    <div class="flex gap-2 text-3xl" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" class="star-btn text-gray-600 hover:text-yellow-500 transition-colors">&#9733;</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                    @error('rating')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Ulasan --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Ulasan <span class="text-gray-600 normal-case">(opsional)</span></label>
                    <textarea name="ulasan" rows="4" maxlength="500" placeholder="Ceritakan pengalaman Anda..." class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[{{ $accentColor }}] resize-none">{{ old('ulasan') }}</textarea>
                    <p class="text-right text-[11px] text-gray-600 mt-1">Maks. 500 karakter</p>
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Foto Hasil <span class="text-gray-600 normal-case">(opsional, maks. 2 foto — jpg/png/webp, maks. 5MB)</span></label>
                    <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-white/10 file:text-white file:text-xs file:font-bold hover:file:bg-white/20 cursor-pointer">
                    @error('foto')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('foto.*')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 accent-bg hover:opacity-90 text-black rounded-xl font-bold text-xs uppercase tracking-wider transition-all">
                    Kirim Rating
                </button>
            </form>
        @endif
    </div>
</div>

<script>
    (function () {
        const picker = document.getElementById('star-picker');
        const input = document.getElementById('rating-input');
        const select = document.getElementById('layanan-select');
        const hint = document.getElementById('already-rated-hint');
        const myRatings = @json($myRatings->map(fn ($r, $k) => $r->rating)->toBase());

        picker.querySelectorAll('.star-btn').forEach(function (star) {
            star.addEventListener('click', function () {
                const val = parseInt(star.dataset.value, 10);
                input.value = val;
                picker.querySelectorAll('.star-btn').forEach(function (s, i) {
                    s.classList.toggle('text-yellow-500', i < val);
                    s.classList.toggle('text-gray-600', i >= val);
                });
            });
        });

        if (select) {
            select.addEventListener('change', function () {
                hint.classList.toggle('hidden', !(this.value && myRatings[this.value]));
            });
        }
    })();
</script>
@endsection
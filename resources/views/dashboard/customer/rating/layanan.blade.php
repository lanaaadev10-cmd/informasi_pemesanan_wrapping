@extends('layouts.dashboard-customer')

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
<div class="max-w-3xl mx-auto py-8 text-slate-900 relative">
    <div class="space-y-1 relative z-10">
        <h1 class="text-3xl font-audiowide font-bold tracking-tight text-slate-900">{{ $pageTitle }}</h1>
        <p class="text-sm font-questrial text-slate-500">{{ $pageDesc }}</p>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm mt-6 relative z-10">
        @if($layanans->isEmpty())
            <p class="text-sm text-slate-500">Belum ada layanan yang tersedia untuk dirating.</p>
        @else
            <form action="{{ route('rating.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Pilih layanan --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Pilih Layanan</label>
                    <select name="id_layanan" id="layanan-select" class="w-full bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-900 focus:outline-none focus:border-[#FF6B00] shadow-sm">
                        <option value="" disabled selected>-- Pilih layanan --</option>
                        @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id_layanan }}" class="text-slate-900">
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_layanan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Info: sudah pernah rating --}}
                <div id="already-rated-hint" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 p-3 text-xs">
                    Anda sudah memberikan ulasan untuk layanan ini. Mengirim lagi akan <strong>memperbarui</strong> ulasan sebelumnya.
                </div>

                {{-- Star picker --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Rating</label>
                    <div class="flex gap-2 text-3xl" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" class="star-btn text-slate-300 hover:text-amber-500 transition-colors">&#9733;</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" required>
                    @error('rating')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Ulasan --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Ulasan <span class="text-slate-400 normal-case font-normal">(opsional)</span></label>
                    <textarea name="ulasan" rows="4" maxlength="500" placeholder="Ceritakan pengalaman Anda..." class="w-full bg-white border border-slate-200 rounded-xl p-4 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#FF6B00] shadow-sm resize-none">{{ old('ulasan') }}</textarea>
                    <p class="text-right text-[11px] text-slate-400 mt-1">Maks. 500 karakter</p>
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-montserrat font-bold uppercase tracking-widest text-slate-500 mb-2">Foto Hasil <span class="text-slate-400 normal-case font-normal">(opsional, maks. 2 foto — jpg/png/webp, maks. 5MB)</span></label>
                    <input type="file" name="foto[]" multiple accept=".jpg,.jpeg,.png,.webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-[#FF6B00] file:text-white file:text-xs file:font-bold hover:file:bg-[#E05D00] cursor-pointer bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('foto.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-[#FF6B00] hover:bg-[#E05D00] text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md">
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
                    s.classList.toggle('text-amber-500', i < val);
                    s.classList.toggle('text-slate-300', i >= val);
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
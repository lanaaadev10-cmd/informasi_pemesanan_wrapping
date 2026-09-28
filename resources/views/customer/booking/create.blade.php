@extends('layouts.dashboard_customer')

@php
    $accentColor = $profil->accent_color ?? '#f2994a';

    $selectableLayanans = $layanans->map(fn ($l) => [
        'id'       => $l->id_layanan,
        'name'     => $l->nama_layanan,
        'price'    => (int) round((float) ($l->harga ?? 0)),
        'estimasi' => $l->estimasi_waktu,
        'deskripsi'=> $l->deskripsi,
    ])->values();
@endphp

@section('title', 'Booking Jadwal Pengerjaan')

@push('styles')
<style>
    /* ── date input: force black text on all browsers ── */
    input[type="date"] {
        color-scheme: normal;
        background-color: #f0f0f0 !important;
        color: #000 !important;
    }
    input[type="date"]::-webkit-datetime-edit            { color: #000 !important; padding: 0; }
    input[type="date"]::-webkit-datetime-edit-container  { color: #000 !important; }
    input[type="date"]::-webkit-datetime-edit-fields-wrapper { color: #000 !important; background: transparent; }
    input[type="date"]::-webkit-datetime-edit-text        { color: rgba(0,0,0,0.5) !important; }
    input[type="date"]::-webkit-datetime-edit-day-field,
    input[type="date"]::-webkit-datetime-edit-month-field,
    input[type="date"]::-webkit-datetime-edit-year-field  { color: #000 !important; }
    input[type="date"]::-webkit-datetime-edit-day-field:focus,
    input[type="date"]::-webkit-datetime-edit-month-field:focus,
    input[type="date"]::-webkit-datetime-edit-year-field:focus {
        background: rgba(242,153,74,0.25) !important;
        color: #000 !important;
        border-radius: 3px;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(50%) sepia(80%) saturate(500%) hue-rotate(350deg);
        cursor: pointer;
        opacity: 0.9;
    }

    /* ── custom select arrow ── */
    .select-dark {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23f2994a' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px !important;
    }
    .select-dark option { background: #1a1a1e; color: #fff; }

    /* ── shake animation for error ── */
    @keyframes shake {
        0%,100%{transform:translateX(0)} 20%{transform:translateX(-6px)} 40%{transform:translateX(6px)} 60%{transform:translateX(-4px)} 80%{transform:translateX(4px)}
    }
    .animate-shake { animation: shake .4s ease; }

    /* ── input focus ring ── */
    .field-input { transition: border-color .2s, box-shadow .2s; }
    .field-input:focus {
        border-color: #f2994a;
        box-shadow: 0 0 0 3px rgba(242,153,74,.15);
        outline: none;
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto text-white relative space-y-8"
     x-data="bookingWizard({{ json_encode($selectableLayanans) }}, '{{ old('payment_type', 'dp') }}', '{{ old('booking_date', $selectedDate) }}', '{{ old('layanan_id') }}', {{ $errors->hasAny(['vehicle_name', 'proof_file', 'payment_type']) ? 2 : 1 }})"
     x-init="initCalendar()">

    {{-- ════ TOP HEADER ════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#f2994a]/15 border border-[#f2994a]/30 text-[#f2994a] text-[11px] font-black uppercase tracking-widest mb-3 shadow-[0_0_18px_rgba(242,153,74,0.2)]">
                Fitting &amp; Wrapping Professional
            </div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight">Booking Jadwal Pengerjaan</h1>
            <p class="text-sm text-gray-400 mt-1">Kuota harian maksimal 5 slot. Pilih tanggal pengerjaan dari kalender di bawah untuk mengamankan jadwal Anda.</p>
        </div>
        <a href="{{ route('booking.index') }}"
           class="inline-flex items-center px-5 py-2.5 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-extrabold text-gray-300 hover:text-white transition-all w-fit">
            Booking Saya
        </a>
    </div>

    {{-- ════ ERROR TOAST ════ --}}
    @if(session('toast_error'))
        <div class="p-4 rounded-2xl bg-red-500/12 border border-red-500/30 text-red-300 text-sm font-semibold flex items-center gap-3 animate-shake">
            {{ session('toast_error') }}
        </div>
    @endif

    {{-- ════ STEP INDICATOR ════ --}}
    <div class="bg-[#141417] border border-white/10 rounded-3xl p-4 md:p-5 shadow-xl">
        <div class="grid grid-cols-2 gap-3 relative">
            {{-- connector line --}}
            <div class="absolute inset-y-0 left-1/2 w-px bg-white/10 -translate-x-1/2 hidden md:block"></div>

            {{-- Step 1 --}}
            <button type="button" @click="goToStep(1)"
                    class="flex items-center gap-3 p-3.5 rounded-2xl transition-all text-left"
                    :class="currentStep === 1
                        ? 'bg-[#f2994a]/12 border border-[#f2994a]/50 shadow-[0_0_20px_rgba(242,153,74,0.12)]'
                        : 'bg-white/[0.02] border border-white/8 hover:border-white/20'">
                <div class="min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-widest"
                       :class="currentStep === 1 ? 'text-[#f2994a]' : 'text-gray-500'">Langkah 1</p>
                    <p class="text-sm font-extrabold text-white truncate">Paket &amp; Jadwal</p>
                </div>
            </button>

            {{-- Step 2 --}}
            <button type="button" @click="goToStep(2)"
                    class="flex items-center gap-3 p-3.5 rounded-2xl transition-all text-left"
                    :class="currentStep === 2
                        ? 'bg-[#f2994a]/12 border border-[#f2994a]/50 shadow-[0_0_20px_rgba(242,153,74,0.12)]'
                        : 'bg-white/[0.02] border border-white/8 hover:border-white/20'">
                <div class="min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-widest"
                       :class="currentStep === 2 ? 'text-[#f2994a]' : 'text-gray-500'">Langkah 2</p>
                    <p class="text-sm font-extrabold text-white truncate">Kendaraan &amp; Bayar</p>
                </div>
            </button>
        </div>
    </div>

    {{-- ════ MAIN FORM ════ --}}
    <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ── FORM AREA ── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ══════════ STEP 1 ══════════ --}}
                <div x-show="currentStep === 1"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">

                    {{-- § Pilih Paket Layanan --}}
                    <section class="bg-[#141417] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl space-y-5">
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <h2 class="flex items-center gap-2.5 text-sm font-black uppercase tracking-widest text-[#f2994a]">
                                Pilih Paket Layanan
                            </h2>
                            <span class="text-[11px] font-bold text-gray-400 bg-white/5 px-3 py-1 rounded-full"
                                  x-text="selected ? selected.name : 'Belum dipilih'"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($layanans as $layanan)
                                <label class="cursor-pointer group block h-full">
                                    <input type="radio" name="layanan_id"
                                           value="{{ $layanan->id_layanan }}"
                                           class="sr-only" required x-model="layananId">
                                    <div class="p-5 rounded-2xl border transition-all duration-200 h-full flex flex-col justify-between relative overflow-hidden"
                                         :class="String(layananId) === '{{ $layanan->id_layanan }}'
                                            ? 'border-[#f2994a] bg-[#f2994a]/8 shadow-[0_0_28px_rgba(242,153,74,0.2)]'
                                            : 'border-white/10 bg-[#1a1a1e] hover:border-white/25 hover:bg-white/[0.03]'">

                                        {{-- glow accent saat terpilih --}}
                                        <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full blur-2xl transition-opacity duration-300 pointer-events-none"
                                             :class="String(layananId) === '{{ $layanan->id_layanan }}' ? 'opacity-30 bg-[#f2994a]' : 'opacity-0'"
                                             style="background: radial-gradient(circle, #f2994a, transparent)"></div>

                                        <div class="flex items-start justify-between gap-3 mb-3 relative">
                                            <div>
                                                <h3 class="font-black text-white text-base leading-tight">{{ $layanan->nama_layanan }}</h3>
                                                @if($layanan->estimasi_waktu)
                                                    <span class="inline-flex items-center text-[11px] font-semibold text-gray-400 mt-1.5 px-2 py-0.5 rounded-full bg-white/5 border border-white/10">
                                                        {{ $layanan->estimasi_waktu }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($layanan->deskripsi)
                                            <p class="text-[12px] text-gray-400 line-clamp-2 leading-relaxed mb-4 relative">{{ $layanan->deskripsi }}</p>
                                        @endif

                                        <div class="pt-3 border-t border-white/10 flex items-center justify-between mt-auto relative">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Harga Paket</span>
                                            <span class="text-lg font-black text-[#f2994a]">Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('layanan_id')
                            <p class="flex items-center gap-1.5 text-red-400 text-xs font-semibold mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </section>

                    {{-- § Tanggal Pengerjaan (Kalender Visual Interaktif) --}}
                    <section class="bg-[#141417] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-white/10 pb-4">
                            <div>
                                <h2 class="flex items-center gap-2.5 text-sm font-black uppercase tracking-widest text-[#f2994a]">
                                    Pilih Tanggal Pengerjaan
                                </h2>
                                <p class="text-xs text-gray-400 mt-0.5">Klik tanggal di kalender atau pilih manual di bawah.</p>
                            </div>
                            <span class="inline-flex items-center text-[11px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full w-fit">
                                Sisa slot hari ini: {{ $todayQuota['available'] }}/{{ $todayQuota['max'] }}
                            </span>
                        </div>

                        {{-- Legend --}}
                        <div class="flex flex-wrap items-center gap-4 text-xs pt-1">
                            <span class="flex items-center gap-1.5 text-gray-300"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia</span>
                            <span class="flex items-center gap-1.5 text-gray-300"><span class="w-2.5 h-2.5 rounded-full bg-[#f2994a]"></span> Sisa Sedikit</span>
                            <span class="flex items-center gap-1.5 text-gray-300"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Penuh</span>
                        </div>

                        {{-- Kalender Component Container --}}
                        <div class="bg-[#111114] border border-white/10 rounded-2xl p-4 md:p-5 relative overflow-hidden">
                            {{-- Header Navigasi Bulan --}}
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" @click="prevCalMonth()" class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-gray-300 hover:text-white text-xs font-bold transition-all">
                                    &larr; Prev
                                </button>
                                <h3 class="text-sm font-black text-white capitalize tracking-wide" x-text="calMonthLabel"></h3>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="thisCalMonth()" class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-[11px] font-bold text-gray-300 hover:text-white transition-all">Bulan Ini</button>
                                    <button type="button" @click="nextCalMonth()" class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 hover:border-[#f2994a] text-gray-300 hover:text-white text-xs font-bold transition-all">
                                        Next &rarr;
                                    </button>
                                </div>
                            </div>

                            {{-- Nama Hari --}}
                            <div class="grid grid-cols-7 gap-1.5 mb-1.5">
                                <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="d">
                                    <div class="text-center text-[10px] font-extrabold uppercase text-gray-500 py-1" x-text="d"></div>
                                </template>
                            </div>

                            {{-- Grid Tanggal --}}
                            <div class="grid grid-cols-7 gap-1.5">
                                <template x-for="cell in calCells" :key="cell.key">
                                    <template x-if="cell.empty">
                                        <div class="h-11 rounded-lg"></div>
                                    </template>
                                    <template x-if="!cell.empty">
                                        <button type="button"
                                            @click="selectCalDate(cell)"
                                            :disabled="cell.disabled"
                                            :class="calCellClasses(cell)"
                                            class="h-11 rounded-xl border text-xs font-extrabold transition-all relative flex flex-col items-center justify-center p-1"
                                        >
                                            <span x-text="cell.day"></span>
                                            <span class="w-1.5 h-1.5 rounded-full mt-0.5"
                                                  :class="cell.status === 'full' ? 'bg-red-500' : (cell.status === 'few' ? 'bg-[#f2994a]' : (cell.status === 'available' ? 'bg-emerald-500' : 'bg-transparent'))">
                                            </span>
                                            <span x-show="cell.status === 'full'" class="absolute -top-1 -right-1 text-[6px] font-black bg-red-500 text-white px-1 rounded-full">FULL</span>
                                        </button>
                                    </template>
                                </template>
                            </div>
                        </div>

                        {{-- Input Tanggal & Preview Card --}}
                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-stretch pt-2">
                            <div class="sm:col-span-3 relative">
                                <label for="booking_date" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                    Tanggal Pengerjaan (Form Input):
                                </label>
                                <input type="date"
                                       name="booking_date"
                                       id="booking_date"
                                       x-model="bookingDate"
                                       value="{{ old('booking_date', $selectedDate) }}"
                                       min="{{ now()->toDateString() }}"
                                       required
                                       class="field-input w-full pl-4 pr-4 py-3.5 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white font-bold text-sm focus:border-[#f2994a]">
                            </div>

                            <div class="sm:col-span-2 p-4 rounded-2xl bg-gradient-to-br from-[#f2994a]/12 to-transparent border border-[#f2994a]/25 flex flex-col justify-center gap-1">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#f2994a]">Jadwal Dipilih</p>
                                <p class="text-base font-black text-white leading-tight" x-text="dateLabel"></p>
                                <p class="text-[11px] text-emerald-400 font-semibold flex items-center gap-1 pt-0.5">
                                    ✓ Slot Kuota Tersedia
                                </p>
                            </div>
                        </div>

                        @error('booking_date')
                            <p class="flex items-center gap-1.5 text-red-400 text-xs font-semibold">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="pt-2 flex justify-end">
                            <button type="button" @click="goToStep(2)"
                                    class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-widest rounded-2xl hover:brightness-110 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_8px_24px_rgba(242,153,74,0.35)]">
                                Detail &amp; Pembayaran
                            </button>
                        </div>
                    </section>
                </div>
                {{-- /STEP 1 --}}

                {{-- ══════════ STEP 2 ══════════ --}}
                <div x-show="currentStep === 2"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">

                    {{-- § Data Kendaraan --}}
                    <section class="bg-[#141417] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl space-y-5">
                        <h2 class="flex items-center gap-2.5 text-sm font-black uppercase tracking-widest text-[#f2994a] border-b border-white/10 pb-4">
                            Informasi Kendaraan
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Nama kendaraan --}}
                            <div class="sm:col-span-2 relative">
                                <input type="text" name="vehicle_name"
                                       value="{{ old('vehicle_name') }}"
                                       placeholder="Nama / Model Kendaraan — cth: Honda Civic Turbo"
                                       required
                                       class="field-input w-full pl-4 pr-4 py-4 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white placeholder-gray-600 font-semibold text-sm">
                                @error('vehicle_name')
                                    <p class="flex items-center gap-1.5 text-red-400 text-xs font-semibold mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Warna --}}
                            <div class="relative">
                                <input type="text" name="vehicle_color"
                                       value="{{ old('vehicle_color') }}"
                                       placeholder="Warna — cth: Hitam Glossy"
                                       class="field-input w-full pl-4 pr-4 py-4 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white placeholder-gray-600 font-medium text-sm">
                            </div>

                            {{-- Plat --}}
                            <div class="relative">
                                <input type="text" name="vehicle_license"
                                       value="{{ old('vehicle_license') }}"
                                       placeholder="Nomor Polisi — cth: B 1234 XYZ"
                                       class="field-input w-full pl-4 pr-4 py-4 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white placeholder-gray-600 font-medium text-sm">
                            </div>

                            {{-- Catatan --}}
                            <div class="sm:col-span-2 relative">
                                <textarea name="notes" rows="3"
                                          placeholder="Catatan instruksi khusus pemasangan... (opsional)"
                                          class="field-input w-full pl-4 pr-4 py-4 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white placeholder-gray-600 font-medium text-sm resize-none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </section>

                    {{-- § Skema & Metode Pembayaran --}}
                    <section class="bg-[#141417] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl space-y-6">
                        <h2 class="flex items-center gap-2.5 text-sm font-black uppercase tracking-widest text-[#f2994a] border-b border-white/10 pb-4">
                            Skema &amp; Metode Pembayaran
                        </h2>

                        {{-- Payment Type Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                'dp'    => ['DP 50% di Awal',  'Bayar separuh sekarang, sisanya saat pengerjaan selesai', 'Populer'],
                                'lunas' => ['Lunas 100%',      'Bayar penuh sekarang, prioritas antrean diutamakan', null],
                            ] as $key => [$title, $desc, $badge])
                                <label class="cursor-pointer group block h-full">
                                    <input type="radio" name="payment_type" value="{{ $key }}" class="sr-only"
                                           x-model="paymentType" {{ old('payment_type', 'dp') === $key ? 'checked' : '' }}>
                                    <div class="relative p-5 rounded-2xl border transition-all duration-200 h-full overflow-hidden"
                                         :class="paymentType === '{{ $key }}'
                                            ? 'border-[#f2994a] bg-[#f2994a]/8 shadow-[0_0_24px_rgba(242,153,74,0.18)]'
                                            : 'border-white/10 bg-[#1a1a1e] hover:border-white/25'">

                                        @if($badge)
                                            <span class="absolute top-3 right-3 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-[#f2994a]/20 text-[#f2994a] border border-[#f2994a]/30">
                                                {{ $badge }}
                                            </span>
                                        @endif

                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="font-black text-white text-sm">{{ $title }}</span>
                                        </div>
                                        <p class="text-[12px] text-gray-400 leading-relaxed">{{ $desc }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- Metode Transfer — custom select tanpa label --}}
                        <div class="space-y-4">
                            <div class="relative">
                                <select name="payment_method"
                                        x-model="paymentMethod"
                                        class="select-dark field-input w-full pl-4 py-4 rounded-2xl bg-[#1a1a1e] border border-white/15 text-white font-semibold text-sm">
                                    <option value="transfer_bank" selected>Transfer Bank (BCA / Mandiri / BRI)</option>
                                    <option value="transfer_e_wallet">Transfer E-Wallet (GoPay / OVO / DANA)</option>
                                </select>
                            </div>

                            {{-- Rekening tujuan — x-show agar langsung tampil --}}
                            <div class="rounded-2xl border border-[#f2994a]/25 overflow-hidden">
                                <div class="px-5 py-3 bg-[#f2994a]/8 border-b border-[#f2994a]/20 flex items-center gap-2">
                                    <span class="text-xs font-black uppercase tracking-widest text-[#f2994a]">Nomor Rekening Tujuan</span>
                                </div>

                                {{-- Transfer Bank --}}
                                <div x-show="paymentMethod === 'transfer_bank'"
                                     class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-white/8">

                                    {{-- BCA --}}
                                    <div class="p-5 bg-[#111114] flex items-center justify-between gap-3">
                                        <div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-sky-400 block mb-0.5">Bank BCA</span>
                                            <span class="text-xl font-black text-white tracking-widest font-mono">8720-9988-11</span>
                                            <span class="text-[11px] text-gray-500 block mt-0.5">a.n. Dantie Stiker</span>
                                        </div>
                                        <button type="button" @click="copyText('8720998811', 'Rekening BCA')"
                                                        class="flex-shrink-0 px-3 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-white/8 hover:bg-[#f2994a] hover:text-black text-white transition-all">
                                                        Salin
                                                    </button>
                                    </div>

                                    {{-- Mandiri --}}
                                    <div class="p-5 bg-[#111114] flex items-center justify-between gap-3">
                                        <div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-yellow-400 block mb-0.5">Bank Mandiri</span>
                                            <span class="text-xl font-black text-white tracking-widest font-mono">1420-0099-8811-2</span>
                                            <span class="text-[11px] text-gray-500 block mt-0.5">a.n. Dantie Stiker</span>
                                        </div>
                                        <button type="button" @click="copyText('1420009988112', 'Rekening Mandiri')"
                                                        class="flex-shrink-0 px-3 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-white/8 hover:bg-[#f2994a] hover:text-black text-white transition-all">
                                                        Salin
                                                    </button>
                                    </div>
                                </div>

                                {{-- E-Wallet --}}
                                <div x-show="paymentMethod === 'transfer_e_wallet'"
                                     class="p-5 bg-[#111114] flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 block mb-0.5">GoPay / OVO / DANA</span>
                                        <span class="text-xl font-black text-white tracking-widest font-mono">0812-3456-7890</span>
                                        <span class="text-[11px] text-gray-500 block mt-0.5">a.n. Dantie Stiker</span>
                                    </div>
<button type="button" @click="copyText('081234567890', 'Nomor E-Wallet')"
                                                        class="flex-shrink-0 px-3 py-2 rounded-xl text-[10px] font-extrabold uppercase tracking-wider bg-white/8 hover:bg-[#f2994a] hover:text-black text-white transition-all">
                                                        Salin
                                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- § Upload Bukti Transfer --}}
                    <section class="bg-[#141417] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl space-y-5">
                        <h2 class="flex items-center gap-2.5 text-sm font-black uppercase tracking-widest text-[#f2994a] border-b border-white/10 pb-4">
                            Bukti Transfer
                            <span class="ml-1 text-red-400 text-base leading-none">*</span>
                        </h2>

                        <label class="cursor-pointer block">
                            <input type="file" name="proof_file"
                                   accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png"
                                   required class="sr-only"
                                   @change="handleFileChange($event)">

                            <div class="relative rounded-2xl border-2 border-dashed transition-all duration-200 flex flex-col items-center justify-center min-h-[180px] text-center px-6 py-8"
                                 :class="fileName
                                    ? 'border-[#f2994a]/50 bg-[#f2994a]/5'
                                    : 'border-white/15 bg-[#1a1a1e] hover:border-[#f2994a]/40 hover:bg-[#f2994a]/4'">

                                {{-- state: ada preview gambar --}}
                                <template x-if="imagePreview">
                                    <div class="space-y-3">
                                        <img :src="imagePreview" class="w-24 h-24 object-cover rounded-2xl border border-white/20 mx-auto shadow-xl">
                                        <p class="text-xs font-black text-white truncate max-w-[200px]" x-text="fileName"></p>
                                        <span class="inline-flex items-center text-[11px] font-bold text-[#f2994a] bg-[#f2994a]/10 px-3 py-1 rounded-full">
                                            Klik untuk ganti
                                        </span>
                                    </div>
                                </template>

                                {{-- state: ada file non-gambar --}}
                                <template x-if="!imagePreview && fileName">
                                    <div class="space-y-2">
                                        <p class="text-sm font-black text-white truncate max-w-[200px]" x-text="fileName"></p>
                                        <span class="text-xs text-gray-400">Siap dikirim · Klik untuk ganti</span>
                                    </div>
                                </template>

                                {{-- state: kosong --}}
                                <template x-if="!fileName">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-extrabold text-white">Klik atau seret file di sini</p>
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, atau PDF · Maks. 5 MB</p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </label>

                        @error('proof_file')
                            <p class="flex items-center gap-1.5 text-red-400 text-xs font-semibold">
                                {{ $message }}
                            </p>
                        @enderror
                    </section>

                    {{-- Tombol kembali --}}
                    <div class="flex items-center justify-between pt-2">
                        <button type="button" @click="goToStep(1)"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-extrabold text-gray-300 hover:text-white transition-all">
                            Kembali ke Step 1
                        </button>
                    </div>
                </div>
                {{-- /STEP 2 --}}

            </div>
            {{-- /FORM AREA --}}

            {{-- ── SIDEBAR RINGKASAN ── --}}
            <aside class="lg:col-span-1 lg:sticky lg:top-24">
                <div class="bg-[#141417] border border-white/10 rounded-3xl p-6 shadow-2xl space-y-5 overflow-hidden relative">

                    {{-- subtle bg glow --}}
                    <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-[#f2994a]/5 blur-3xl pointer-events-none"></div>

                    <div class="flex items-center justify-between border-b border-white/10 pb-4 relative">
                        <h3 class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[#f2994a]">
                            Ringkasan
                        </h3>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-white/8 text-gray-300"
                              x-text="'Step ' + currentStep + ' / 2'"></span>
                    </div>

                    <dl class="space-y-3.5 text-xs relative">
                        <div class="flex justify-between items-start gap-3">
                            <dt class="text-gray-400 shrink-0">Paket</dt>
                            <dd class="font-black text-white text-right" x-text="selected ? selected.name : '—'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-3">
                            <dt class="text-gray-400 shrink-0">Estimasi</dt>
                            <dd class="font-bold text-white text-right" x-text="selected?.estimasi ?? '—'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-3">
                            <dt class="text-gray-400 shrink-0">Jadwal</dt>
                            <dd class="font-bold text-white text-right" x-text="dateLabel"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-3">
                            <dt class="text-gray-400 shrink-0">Skema</dt>
                            <dd class="font-black text-[#f2994a] text-right" x-text="payLabel(paymentType)"></dd>
                        </div>

                        <div class="border-t border-white/10 pt-4 space-y-3">
                            <div class="flex justify-between items-center gap-2" x-show="paymentType === 'dp' && selected">
                                <dt class="text-gray-500">Harga Total</dt>
                                <dd class="font-bold text-gray-400" x-text="fmtNumber(price)"></dd>
                            </div>
                            <div class="flex justify-between items-center gap-2 pt-1">
                                <dt class="font-bold text-white">
                                    <span x-text="paymentType === 'dp' ? 'Bayar DP (50%)' : 'Total Tagihan'"></span>
                                </dt>
                                <dd class="text-2xl font-black text-[#f2994a]" x-text="totalText"></dd>
                            </div>
                        </div>
                    </dl>

                    {{-- CTA Button --}}
                    <div class="relative pt-2">
                        <div x-show="currentStep === 1">
                            <button type="button" @click="goToStep(2)"
                                    class="w-full py-4 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-widest rounded-2xl hover:brightness-110 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_8px_28px_rgba(242,153,74,0.4)]">
                                Lanjut ke Detail
                            </button>
                        </div>
                        <div x-show="currentStep === 2">
                            <button type="submit"
                                    class="w-full py-4 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#e28a44] to-[#f2994a] text-black font-black text-xs uppercase tracking-widest rounded-2xl hover:brightness-110 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_8px_28px_rgba(242,153,74,0.4)]">
                                Ajukan Booking
                            </button>
                        </div>
                    </div>

                    <p class="flex items-center justify-center text-[11px] text-gray-500 pt-1 relative">
                        Slot tergaransi &amp; transaksi aman
                    </p>
                </div>
            </aside>

        </div>
    </form>
</div>

<script>
    function bookingWizard(layanans, paymentType, bookingDate, initialLayananId, initialStep) {
        const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const DAYS   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        function fmtNumber(n) {
            return 'Rp\u00a0' + Math.round(n).toLocaleString('id-ID');
        }

        return {
            currentStep : initialStep || 1,
            layanans,
            paymentType,
            paymentMethod : 'transfer_bank',
            bookingDate,
            fileName      : '',
            imagePreview  : null,
            _layananId    : initialLayananId || (layanans[0]?.id ?? null),

            get selected()  { return this.layanans.find(l => String(l.id) === String(this._layananId)) || null; },
            get layananId() { return this._layananId; },
            set layananId(v){ this._layananId = v; },

            get price()     { return this.selected ? Number(this.selected.price) : 0; },
            get dp()        { return Math.round(this.price * 0.5); },
            get total()     { return this.paymentType === 'dp' ? this.dp : this.price; },
            get totalText() { return this.selected ? fmtNumber(this.total) : 'Rp —'; },

            get dateLabel() {
                if (!this.bookingDate) return 'Pilih tanggal';
                const [y, m, d] = this.bookingDate.split('-').map(Number);
                if (!y || !m || !d) return 'Pilih tanggal';
                const dt = new Date(y, m - 1, d);
                return DAYS[dt.getDay()] + ', ' + d + ' ' + MONTHS[m - 1] + ' ' + y;
            },

            payLabel(p) { return p === 'dp' ? 'DP 50% di Awal' : 'Pelunasan 100%'; },
            fmtNumber,

            goToStep(step) {
                if (step === 2 && (!this.selected || !this.bookingDate)) {
                    alert('Silakan pilih paket layanan dan tanggal pengerjaan terlebih dahulu!');
                    return;
                }
                this.currentStep = step;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            copyText(text, label) {
                navigator.clipboard.writeText(text).then(() => {
                    // bisa diganti toast notification jika ada
                    alert(label + ' berhasil disalin!');
                });
            },

            // Calendar states
            calYear: new Date().getFullYear(),
            calMonth: new Date().getMonth(),
            calCells: [],
            calQuota: {},

            get calMonthLabel() {
                return `${MONTHS[this.calMonth]} ${this.calYear}`;
            },

            async initCalendar() {
                if (this.bookingDate) {
                    const [y, m] = this.bookingDate.split('-').map(Number);
                    if (y && m) {
                        this.calYear = y;
                        this.calMonth = m - 1;
                    }
                }
                await this.loadCalQuota();
                this.renderCalendar();
            },

            async loadCalQuota() {
                try {
                    const res = await fetch(`/api/booking/quota-month/${this.calYear}/${this.calMonth + 1}`);
                    if (res.ok) {
                        const json = await res.json();
                        this.calQuota = json.quota ?? {};
                    }
                } catch(e) {}
            },

            renderCalendar() {
                const now = new Date();
                const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
                const MAX = 5;

                const firstDay = new Date(this.calYear, this.calMonth, 1);
                const startWeekday = firstDay.getDay();
                const daysInMonth = new Date(this.calYear, this.calMonth + 1, 0).getDate();
                const cells = [];

                for (let i = 0; i < startWeekday; i++) {
                    cells.push({ empty: true, key: `e${i}` });
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const monthStr = String(this.calMonth + 1).padStart(2, '0');
                    const dayStr = String(d).padStart(2, '0');
                    const dateStr = `${this.calYear}-${monthStr}-${dayStr}`;
                    const past = dateStr < todayStr;
                    const q = this.calQuota[dateStr];
                    const available = q ? q.available : MAX;
                    const status = past ? 'past' : (available <= 0 ? 'full' : (available <= 2 ? 'few' : 'available'));
                    cells.push({
                        empty: false,
                        key: dateStr,
                        date: dateStr,
                        day: d,
                        status,
                        available,
                        max: MAX,
                        disabled: past || status === 'full',
                    });
                }

                this.calCells = cells;
            },

            calCellClasses(cell) {
                const base = 'disabled:opacity-40 disabled:cursor-not-allowed ';
                if (cell.date === this.bookingDate) {
                    return base + 'border-[#f2994a] bg-[#f2994a]/20 text-white shadow-[0_0_15px_rgba(242,153,74,0.3)]';
                }
                if (cell.status === 'full' || cell.status === 'past') return base + 'border-white/10 bg-white/[0.02] text-gray-500';
                if (cell.status === 'few') return base + 'border-[#f2994a]/40 bg-white/5 text-white hover:border-[#f2994a]';
                return base + 'border-white/10 bg-white/5 text-white hover:border-emerald-500';
            },

            selectCalDate(cell) {
                if (cell.disabled) return;
                this.bookingDate = cell.date;
            },

            async prevCalMonth() {
                this.calMonth--;
                if (this.calMonth < 0) { this.calMonth = 11; this.calYear--; }
                await this.loadCalQuota();
                this.renderCalendar();
            },

            async nextCalMonth() {
                this.calMonth++;
                if (this.calMonth > 11) { this.calMonth = 0; this.calYear++; }
                await this.loadCalQuota();
                this.renderCalendar();
            },

            async thisCalMonth() {
                const now = new Date();
                this.calYear = now.getFullYear();
                this.calMonth = now.getMonth();
                await this.loadCalQuota();
                this.renderCalendar();
            },

            handleFileChange(event) {
                const file = event.target.files[0];
                if (!file) { this.fileName = ''; this.imagePreview = null; return; }
                this.fileName = file.name;
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => { this.imagePreview = e.target.result; };
                    reader.readAsDataURL(file);
                } else {
                    this.imagePreview = null;
                }
            }
        };
    }
</script>
@endsection
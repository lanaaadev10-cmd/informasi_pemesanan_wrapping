@extends('layouts.dashboard_customer')

@php
    $accentColor = '#ff6b00';

    $localFallbacks = [
        'variasi mobil' => asset('images/layanan/wrapping-mobil.jpg'),
        'wrapping'      => asset('images/layanan/wrapping-mobil.jpg'),
        'kaca film'     => asset('images/layanan/kaca-film.jpg'),
        'audio'         => asset('images/layanan/audio-head.jpg'),
        'audio mobil'   => asset('images/layanan/audio-head.jpg'),
        'lampu biled'   => asset('images/layanan/lampu-biled.jpg'),
        'striping'      => asset('images/layanan/layanan-striping.jpg'),
    ];

    $selectableLayanans = $layanans->map(function ($l) use ($localFallbacks) {
        $fotoPath = $l->getOriginal('foto_contoh') ?? null;
        $img = null;
        if (!empty($fotoPath) && (file_exists(public_path($fotoPath)) || file_exists(public_path('storage/' . $fotoPath)) || str_starts_with($fotoPath, 'http'))) {
            $img = \App\Helpers\StaticContent::fotoUrl($fotoPath);
        } else {
            $matchKey = strtolower(trim($l->nama_layanan ?? ''));
            $catKey   = strtolower(trim($l->kategori ?? ''));
            $img = $localFallbacks[$matchKey] ?? ($localFallbacks[$catKey] ?? asset('images/layanan/wrapping-mobil.jpg'));
        }

        return [
            'id'        => $l->id_layanan,
            'name'      => $l->nama_layanan,
            'price'     => (int) round((float) ($l->harga ?? 0)),
            'estimasi'  => $l->estimasi_waktu,
            'deskripsi' => $l->deskripsi,
            'foto'      => $img,
        ];
    })->values();

    $defaultName  = old('customer_name', $user->name ?? '');
    $defaultPhone = old('customer_phone', $user->no_hp ?? ($user->phone ?? ''));
    $defaultEmail = old('customer_email', $user->email ?? '');
@endphp

@section('title', 'Booking Jadwal Pengerjaan - Dantie Stiker')

@push('styles')
<style>
    input[type="date"] {
        color-scheme: dark;
    }
    .field-input {
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }
    .field-input:focus {
        border-color: #ff6b00;
        box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.18);
        outline: none;
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.05); }
    }
    .glow-pulse { animation: pulseGlow 4s ease-in-out infinite; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto text-white relative space-y-8 pb-28 lg:pb-12"
     x-data="bookingApp({
         layanans: {{ json_encode($selectableLayanans) }},
         initialPaymentType: '{{ old('payment_type', 'dp') }}',
         initialPaymentMethod: '{{ old('payment_method', 'Transfer BCA') }}',
         initialDate: '{{ old('booking_date', $selectedDate) }}',
         initialTime: '{{ old('booking_time', '09:00') }}',
         initialLayananId: '{{ old('layanan_id') }}',
         defaultName: '{{ addslashes($defaultName) }}',
         defaultPhone: '{{ addslashes($defaultPhone) }}',
         defaultEmail: '{{ addslashes($defaultEmail) }}'
     })"
     x-init="initCalendar()">

    {{-- Subtle Ambient Glows --}}
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ff6b00]/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -left-32 w-80 h-80 bg-[#ff6b00]/5 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ════ TOP HEADER ════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6 relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#ff6b00]/15 border border-[#ff6b00]/30 text-[#ff6b00] text-[10px] font-montserrat font-bold uppercase tracking-widest mb-2.5 shadow-[0_0_18px_rgba(255,107,0,0.2)]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#ff6b00] animate-ping"></span>
                Sistem Reservasi &amp; Booking Online
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-audiowide font-bold tracking-wide text-white">Booking Jadwal Pengerjaan</h1>
            <p class="text-xs sm:text-sm font-questrial text-gray-400 mt-1 max-w-2xl leading-relaxed">
                Pilih tanggal pada kalender interaktif di bawah. Maksimal kuota hanya <span class="text-[#ff6b00] font-bold">5 kendaraan per hari</span> untuk menjaga kualitas pengerjaan terbaik.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('booking.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold uppercase tracking-wider text-gray-300 hover:text-white transition-all shadow-sm w-full sm:w-auto">
                <i class="ph-bold ph-calendar-check text-base text-[#ff6b00]"></i>
                Daftar Booking Saya
            </a>
        </div>
    </div>

    {{-- ════ ERROR / NOTIFIKASI TOAST ════ --}}
    @if(session('toast_error'))
        <div class="p-4 rounded-2xl bg-red-500/15 border border-red-500/40 text-red-200 text-xs sm:text-sm font-montserrat font-medium flex items-center gap-3 shadow-lg">
            <i class="ph-bold ph-warning-circle text-lg text-red-400 shrink-0"></i>
            <div>{{ session('toast_error') }}</div>
        </div>
    @endif

    {{-- ════ FORM UTAMA ════ --}}
    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" @submit="submitBooking($event)">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start relative z-10">

            {{-- ── AREA KONTEN FORM (2 KOLOM) ── --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- ════ 1. KALENDER INTERAKTIF (BULANAN, MINGGUAN, HARIAN) ════ --}}
                <section class="bg-[#141414] border border-white/10 rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00]"></span>
                                <h2 class="text-sm font-audiowide font-bold uppercase tracking-wider text-[#ff6b00]">
                                    Langkah 1: Kalender Jadwal Pengerjaan
                                </h2>
                            </div>
                            <p class="text-xs font-questrial text-gray-400 mt-1">Pilih tanggal yang tersedia. Kuota otomatis terkunci saat mencapai 5 booking.</p>
                        </div>

                        {{-- Mode View Toggle (Bulanan / Mingguan / Harian) --}}
                        <div class="flex items-center bg-[#0d0d0f] border border-white/10 p-1 rounded-2xl w-fit self-start sm:self-auto">
                            <button type="button" @click="setViewMode('month')"
                                    :class="viewMode === 'month' ? 'bg-[#ff6b00] text-black font-montserrat font-extrabold shadow-md' : 'text-gray-400 hover:text-white font-montserrat font-bold'"
                                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                                Bulanan
                            </button>
                            <button type="button" @click="setViewMode('week')"
                                    :class="viewMode === 'week' ? 'bg-[#ff6b00] text-black font-montserrat font-extrabold shadow-md' : 'text-gray-400 hover:text-white font-montserrat font-bold'"
                                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                                Mingguan
                            </button>
                            <button type="button" @click="setViewMode('day')"
                                    :class="viewMode === 'day' ? 'bg-[#ff6b00] text-black font-montserrat font-extrabold shadow-md' : 'text-gray-400 hover:text-white font-montserrat font-bold'"
                                    class="px-3 sm:px-3.5 py-1.5 rounded-xl text-xs transition-all">
                                Harian
                            </button>
                        </div>
                    </div>

                    {{-- Indikator Legenda Warna --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs bg-white/[0.02] border border-white/5 p-3 rounded-2xl font-questrial">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] shrink-0"></span>
                            <span class="text-gray-300 text-[11px] truncate">Tersedia (&le; 2 slot)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00] shadow-[0_0_8px_rgba(255,107,0,0.5)] shrink-0"></span>
                            <span class="text-gray-300 text-[11px] truncate">Hampir Penuh (4/5)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)] shrink-0"></span>
                            <span class="text-gray-300 text-[11px] truncate">Penuh (5/5)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-600 shrink-0"></span>
                            <span class="text-gray-400 text-[11px] truncate">Lewat / Tutup</span>
                        </div>
                    </div>

                    {{-- ── VIEW 1: TAMPILAN BULANAN ── --}}
                    <div x-show="viewMode === 'month'" class="space-y-4">
                        {{-- Navigasi Bulan (Responsive Mobile / Desktop) --}}
                        <div class="flex items-center justify-between bg-[#0e0e11] border border-white/10 px-3 sm:px-4 py-2.5 sm:py-3 rounded-2xl">
                            <button type="button" @click="prevCalMonth()"
                                    class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all flex items-center gap-1">
                                <i class="ph-bold ph-caret-left text-sm"></i>
                                <span class="hidden sm:inline">Bulan Sebelumnya</span>
                            </button>
                            <h3 class="text-sm sm:text-base font-audiowide font-bold text-white capitalize tracking-wide flex items-center gap-2" x-text="calMonthLabel"></h3>
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <button type="button" @click="thisCalMonth()"
                                        class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all">
                                    Bulan Ini
                                </button>
                                <button type="button" @click="nextCalMonth()"
                                        class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all flex items-center gap-1">
                                    <span class="hidden sm:inline">Bulan Berikutnya</span>
                                    <i class="ph-bold ph-caret-right text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Header Nama Hari --}}
                        <div class="grid grid-cols-7 gap-1 sm:gap-2">
                            <template x-for="d in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']" :key="d">
                                <div class="text-center text-[10px] sm:text-[11px] font-montserrat font-bold uppercase text-gray-400 py-1" x-text="d"></div>
                            </template>
                        </div>

                        {{-- Grid Tanggal Bulanan --}}
                        <div class="grid grid-cols-7 gap-1 sm:gap-2">
                            <template x-for="cell in calCells" :key="cell.key">
                                <template x-if="cell.empty">
                                    <div class="h-14 sm:h-16 rounded-xl sm:rounded-2xl bg-white/[0.01] border border-transparent"></div>
                                </template>
                                <template x-if="!cell.empty">
                                    <button type="button"
                                            @click="selectDate(cell.date)"
                                            :disabled="cell.disabled"
                                            :class="getCellClasses(cell)"
                                            class="h-14 sm:h-16 rounded-xl sm:rounded-2xl border text-xs font-montserrat font-bold transition-all duration-200 relative flex flex-col items-center justify-between p-1.5 sm:p-2 text-center group">
                                        <div class="w-full flex items-center justify-between text-[10px] sm:text-[11px]">
                                            <span class="font-audiowide font-bold" x-text="cell.day"></span>
                                            <span x-show="cell.isToday" class="text-[7px] sm:text-[8px] px-1 py-0.2 rounded bg-[#ff6b00] text-black font-montserrat font-black leading-tight">HARI INI</span>
                                        </div>

                                        {{-- Indikator Kuota / Status --}}
                                        <div class="w-full">
                                            <template x-if="cell.status === 'past'">
                                                <span class="text-[8px] sm:text-[9px] text-gray-500 font-medium block">Lewat</span>
                                            </template>
                                            <template x-if="cell.status === 'blocked'">
                                                <span class="text-[8px] sm:text-[9px] text-gray-400 font-medium block truncate" x-text="cell.blockedReason || 'Tutup'"></span>
                                            </template>
                                            <template x-if="cell.status === 'full'">
                                                <span class="text-[8px] sm:text-[9px] px-1 py-0.5 rounded-full bg-rose-500/20 text-rose-300 font-montserrat font-black border border-rose-500/40 block leading-tight">PENUH</span>
                                            </template>
                                            <template x-if="cell.status === 'few'">
                                                <span class="text-[9px] sm:text-[10px] text-[#ff6b00] font-bold block" x-text="cell.used + '/5 booking'"></span>
                                            </template>
                                            <template x-if="cell.status === 'available'">
                                                <span class="text-[9px] sm:text-[10px] text-emerald-400 font-bold block" x-text="cell.used + '/5 booking'"></span>
                                            </template>
                                        </div>
                                    </button>
                                </template>
                            </template>
                        </div>
                    </div>

                    {{-- ── VIEW 2: TAMPILAN MINGGUAN (WEEK VIEW) ── --}}
                    <div x-show="viewMode === 'week'" class="space-y-4">
                        <div class="flex items-center justify-between bg-[#0e0e11] border border-white/10 px-4 py-3 rounded-2xl">
                            <button type="button" @click="prevWeek()" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all">
                                &larr; Minggu Sebelumnya
                            </button>
                            <h3 class="text-xs sm:text-sm font-audiowide font-bold text-white capitalize tracking-wide" x-text="weekRangeLabel"></h3>
                            <button type="button" @click="nextWeek()" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all">
                                Minggu Berikutnya &rarr;
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-7 gap-2.5">
                            <template x-for="day in weekDays" :key="day.date">
                                <button type="button"
                                        @click="selectDate(day.date)"
                                        :disabled="day.disabled"
                                        :class="getCellClasses(day)"
                                        class="p-4 rounded-2xl border text-left transition-all duration-200 flex flex-col justify-between min-h-[110px] sm:min-h-[120px]">
                                    <div>
                                        <p class="text-[10px] font-montserrat font-bold uppercase text-gray-400" x-text="day.dayName"></p>
                                        <p class="text-base sm:text-lg font-audiowide font-bold mt-0.5 text-white" x-text="day.dayNum"></p>
                                        <p class="text-[11px] font-questrial text-gray-400" x-text="day.monthShort"></p>
                                    </div>
                                    <div class="pt-2 border-t border-white/10 mt-2">
                                        <span class="text-[10px] font-montserrat font-bold block"
                                              :class="day.status === 'full' ? 'text-rose-400' : (day.status === 'few' ? 'text-[#ff6b00]' : (day.status === 'available' ? 'text-emerald-400' : 'text-gray-500'))"
                                              x-text="day.statusText">
                                        </span>
                                        <span class="text-[9px] font-questrial text-gray-400 block mt-0.5" x-text="day.subText"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- ── VIEW 3: TAMPILAN HARIAN (DAY VIEW) ── --}}
                    <div x-show="viewMode === 'day'" class="space-y-4">
                        <div class="flex items-center justify-between bg-[#0e0e11] border border-white/10 px-4 py-3 rounded-2xl">
                            <button type="button" @click="prevDay()" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all">
                                &larr; Hari Sebelumnya
                            </button>
                            <h3 class="text-xs sm:text-sm font-audiowide font-bold text-white capitalize tracking-wide" x-text="formatDateFull(bookingDate)"></h3>
                            <button type="button" @click="nextDay()" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all">
                                Hari Berikutnya &rarr;
                            </button>
                        </div>

                        {{-- Day Detail Box --}}
                        <div class="p-5 sm:p-6 rounded-3xl bg-[#0d0d10] border border-white/10 space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                                <div>
                                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400">Status Kuota Hari Terpilih:</span>
                                    <p class="text-lg sm:text-xl font-audiowide font-bold text-white mt-0.5" x-text="formatDateFull(bookingDate)"></p>
                                </div>
                                <div class="px-4 py-2 rounded-2xl border text-right self-start sm:self-auto"
                                     :class="selectedDateQuota.is_full ? 'border-rose-500/40 bg-rose-500/10 text-rose-300' : (selectedDateQuota.available <= 1 ? 'border-amber-500/40 bg-amber-500/10 text-amber-300' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300')">
                                    <p class="text-[9px] font-montserrat font-bold uppercase tracking-widest">Ketersediaan Slot</p>
                                    <p class="text-base sm:text-lg font-audiowide font-bold" x-text="selectedDateQuota.available + ' Slot Tersedia (Maks 5)'"></p>
                                </div>
                            </div>

                            <p class="text-xs font-questrial text-gray-400">
                                Setiap hari memiliki batas 5 slot pengerjaan. Pelanggan dapat memilih salah satu rekomendasi jam kedatangan di bawah.
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal Terpilih & Jam Kedatangan (Responsive Stack) --}}
                    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-[#ff6b00]/15 via-[#1a1a1f] to-transparent border border-[#ff6b00]/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#ff6b00] block mb-1">Tanggal Terpilih</span>
                            <span class="text-base sm:text-lg font-audiowide font-bold text-white block" x-text="formatDateFull(bookingDate)"></span>
                            <span class="text-xs font-questrial"
                                  :class="selectedDateQuota.is_full ? 'text-rose-400 font-bold' : (selectedDateQuota.available <= 1 ? 'text-[#ff6b00] font-bold' : 'text-emerald-400')"
                                  x-text="selectedDateQuota.is_full ? 'Tanggal ini PENUH (5/5). Silakan pilih tanggal lain.' : 'Tersedia ' + selectedDateQuota.available + ' dari 5 slot booking.'">
                            </span>
                        </div>

                        {{-- Pilihan Jam Kedatangan (Grid 3 Kolom di Mobile, Flex di Desktop) --}}
                        <div class="w-full md:w-auto">
                            <label class="block text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                                Jam Kedatangan
                            </label>
                            <div class="grid grid-cols-3 sm:flex sm:flex-wrap gap-2">
                                <template x-for="timeSlot in ['08:30', '10:00', '11:30', '13:30', '15:00', '16:30']" :key="timeSlot">
                                    <button type="button"
                                            @click="bookingTime = timeSlot"
                                            :class="bookingTime === timeSlot ? 'bg-[#ff6b00] text-black font-montserrat font-black border-[#ff6b00] shadow-md scale-105' : 'bg-white/5 text-gray-300 hover:text-white border-white/10 hover:border-white/25 font-montserrat font-bold'"
                                            class="px-2.5 sm:px-3 py-2 rounded-xl border text-xs transition-all text-center">
                                        <span x-text="timeSlot + ' WIB'"></span>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="booking_time" x-model="bookingTime">
                        </div>
                    </div>

                    {{-- Hidden input for real form submission --}}
                    <input type="hidden" name="booking_date" x-model="bookingDate" required>
                    @error('booking_date')
                        <p class="text-red-400 text-xs font-semibold flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </section>

                {{-- ════ 2. PILIH PAKET LAYANAN ════ --}}
                <section class="bg-[#141414] border border-white/10 rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl space-y-6">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00]"></span>
                            <h2 class="text-sm font-audiowide font-bold uppercase tracking-wider text-[#ff6b00]">
                                Langkah 2: Pilih Jenis Layanan
                            </h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="lg:hidden text-[10px] font-montserrat font-bold text-gray-400 flex items-center gap-1">
                                <i class="ph-bold ph-hand-swipe-left text-[#ff6b00]"></i> Geser
                            </span>
                            <span class="text-xs font-montserrat font-bold text-[#ff6b00] bg-[#ff6b00]/10 px-3 py-1 rounded-full border border-[#ff6b00]/25"
                                  x-text="selectedLayanan ? selectedLayanan.name : 'Pilih salah satu'"></span>
                        </div>
                    </div>

                    {{-- Horizontal Swipeable Container di Mobile & Tablet, Grid 3-Kolom di Desktop --}}
                    <div class="flex gap-4 overflow-x-auto pb-4 pt-1 snap-x snap-mandatory no-scrollbar lg:grid lg:grid-cols-3 lg:gap-4 lg:overflow-visible lg:pb-0">
                        <template x-for="item in layanans" :key="item.id">
                            <label class="cursor-pointer group block h-full flex-shrink-0 w-[78vw] max-w-[280px] sm:w-[300px] snap-center lg:w-auto lg:max-w-none">
                                <input type="radio" name="layanan_id"
                                       :value="item.id"
                                       class="sr-only" required x-model="layananId">
                                <div class="rounded-2xl border transition-all duration-300 h-full flex flex-col justify-between relative overflow-hidden group/card shadow-lg"
                                     :class="String(layananId) === String(item.id)
                                        ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_24px_rgba(255,107,0,0.25)] ring-2 ring-[#ff6b00]/40 scale-[1.01]'
                                        : 'border-white/10 bg-[#18181b] hover:border-white/25 hover:bg-white/[0.04]'">

                                    {{-- Thumbnail Foto Layanan --}}
                                    <div class="relative h-36 bg-gradient-to-br from-[#ff6b00]/20 to-transparent overflow-hidden">
                                        <img :src="item.foto"
                                             :alt="item.name"
                                             class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                                        
                                        {{-- Checkmark Selection Badge --}}
                                        <div x-show="String(layananId) === String(item.id)"
                                             class="absolute top-2.5 right-2.5 w-6 h-6 rounded-full bg-[#ff6b00] text-black flex items-center justify-center text-xs font-black shadow-lg">
                                            <i class="ph-bold ph-check text-sm"></i>
                                        </div>

                                        {{-- Estimasi Pill --}}
                                        <template x-if="item.estimasi">
                                            <div class="absolute bottom-2 left-2 bg-black/75 backdrop-blur-sm px-2 py-0.5 rounded-md border border-white/10">
                                                <p class="text-[9px] font-montserrat font-bold text-gray-300 uppercase tracking-wider" x-text="item.estimasi"></p>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Body Card --}}
                                    <div class="p-4 flex flex-col justify-between flex-1">
                                        <div>
                                            <h3 class="font-audiowide font-bold text-white text-base leading-snug group-hover/card:text-[#ff6b00] transition-colors" x-text="item.name"></h3>
                                            <p class="text-[11px] font-questrial text-gray-400 line-clamp-2 leading-relaxed mt-1" x-text="item.deskripsi || 'Layanan wrapping & detailing profesional bergaransi.'"></p>
                                        </div>

                                        <div class="pt-3 border-t border-white/10 mt-3 flex items-baseline justify-between">
                                            <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-gray-500">Tarif Mulai</span>
                                            <span class="text-base sm:text-lg font-audiowide font-bold text-[#ff6b00]" x-text="formatRupiah(item.price)"></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                    @error('layanan_id')
                        <p class="text-red-400 text-xs font-semibold">{{ $message }}</p>
                    @enderror
                </section>

                {{-- ════ 3. DATA PELANGGAN & KENDARAAN ════ --}}
                <section class="bg-[#141414] border border-white/10 rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl space-y-6">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-4">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00]"></span>
                        <h2 class="text-sm font-audiowide font-bold uppercase tracking-wider text-[#ff6b00]">
                            Langkah 3: Data Kontak &amp; Kendaraan
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        {{-- Nama Lengkap --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Nama Lengkap <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="ph-bold ph-user text-sm"></i>
                                </span>
                                <input type="text" name="customer_name" x-model="customerName" required
                                       placeholder="Contoh: Budi Santoso"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                            @error('customer_name') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nomor WhatsApp --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Nomor WhatsApp <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-emerald-400">
                                    <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                                </span>
                                <input type="tel" name="customer_phone" x-model="customerPhone" required
                                       placeholder="Contoh: 081234567890"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                            <p class="text-[10px] font-questrial text-gray-500">Konfirmasi status booking akan dikirimkan ke nomor ini.</p>
                            @error('customer_phone') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email (Opsional) --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Email <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="ph-bold ph-envelope-simple text-sm"></i>
                                </span>
                                <input type="email" name="customer_email" x-model="customerEmail"
                                       placeholder="Contoh: pelanggan@gmail.com"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                            @error('customer_email') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                        </div>

                        {{-- Model Kendaraan --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Jenis / Model Kendaraan <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[#ff6b00]">
                                    <i class="ph-bold ph-car-profile text-sm"></i>
                                </span>
                                <input type="text" name="vehicle_name" x-model="vehicleName"
                                       placeholder="Contoh: Honda Civic Turbo / Pajero Sport"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                        </div>

                        {{-- Warna & Plat Nomor --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Warna Kendaraan
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="ph-bold ph-paint-brush text-sm"></i>
                                </span>
                                <input type="text" name="vehicle_color" x-model="vehicleColor"
                                       placeholder="Contoh: Hitam Glossy"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Nomor Polisi / Plat
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="ph-bold ph-identification-card text-sm"></i>
                                </span>
                                <input type="text" name="vehicle_license" x-model="vehicleLicense"
                                       placeholder="Contoh: B 1234 XYZ"
                                       class="field-input w-full pl-10 pr-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm">
                            </div>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Catatan Khusus / Keperluan Booking
                            </label>
                            <div class="relative">
                                <textarea name="notes" x-model="notes" rows="3"
                                          placeholder="Tuliskan catatan khusus atau permintaan warna stiker yang diinginkan..."
                                          class="field-input w-full px-4 py-3 rounded-xl bg-[#19191e] border border-white/15 text-white font-questrial text-sm resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ════ 4. PEMBAYARAN & UPLOAD BUKTI (OPSIONAL) ════ --}}
                <section class="bg-[#141414] border border-white/10 rounded-3xl p-5 sm:p-7 md:p-8 shadow-2xl space-y-5">
                    <div class="flex items-center gap-2 border-b border-white/10 pb-4">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#ff6b00]"></span>
                        <h2 class="text-sm font-audiowide font-bold uppercase tracking-wider text-[#ff6b00]">
                            Langkah 4: Skema Pembayaran
                        </h2>
                    </div>

                    {{-- Pilihan skema --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Opsi A: DP 50% --}}
                        <label class="cursor-pointer block h-full">
                            <input type="radio" name="payment_type" value="dp" class="sr-only" x-model="paymentType">
                            <div class="p-4 rounded-2xl border transition-all h-full flex flex-col gap-2"
                                 :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.15)]' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-montserrat font-bold uppercase text-[#ff6b00] bg-[#ff6b00]/15 px-2 py-0.5 rounded-md border border-[#ff6b00]/30">Opsi A</span>
                                    <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                                         :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-white/30'">
                                        <div x-show="paymentType === 'dp'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-audiowide font-bold text-white">DP 50% di Awal</h4>
                                    <p class="text-[11px] font-questrial text-gray-400 mt-0.5 leading-relaxed">Kunci slot dengan DP 50% sekarang. <span class="text-[#ff6b00]">Sisa 50% dibayar di workshop saat serah terima.</span></p>
                                </div>
                                <div class="mt-auto pt-2.5 border-t border-white/10 flex items-center justify-between text-xs">
                                    <span class="text-gray-400 font-questrial">Bayar Sekarang</span>
                                    <span class="font-audiowide font-bold text-[#ff6b00]" x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span>
                                </div>
                            </div>
                        </label>

                        {{-- Opsi B: Lunas 100% --}}
                        <label class="cursor-pointer block h-full">
                            <input type="radio" name="payment_type" value="lunas" class="sr-only" x-model="paymentType">
                            <div class="p-4 rounded-2xl border transition-all h-full flex flex-col gap-2"
                                 :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.15)]' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-montserrat font-bold uppercase text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/30">Opsi B</span>
                                    <div class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"
                                         :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]' : 'border-white/30'">
                                        <div x-show="paymentType === 'lunas'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-audiowide font-bold text-white">Lunas 100%</h4>
                                    <p class="text-[11px] font-questrial text-gray-400 mt-0.5 leading-relaxed">Bayar penuh langsung. Tidak perlu transaksi lagi saat mobil diambil.</p>
                                </div>
                                <div class="mt-auto pt-2.5 border-t border-white/10 flex items-center justify-between text-xs">
                                    <span class="text-gray-400 font-questrial">Total</span>
                                    <span class="font-audiowide font-bold text-white" x-text="formatRupiah(selectedLayanan ? selectedLayanan.price : 0)"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Bank Tujuan: Hidden select (form) + pill selector visual --}}
                    {{-- Hidden native select for form submission --}}
                    <select name="payment_method" x-model="selectedBankId" class="sr-only">
                        <template x-for="bank in banks" :key="bank.id">
                            <option :value="bank.id" x-text="bank.name"></option>
                        </template>
                    </select>

                    {{-- Bank selector UI --}}
                    <div class="space-y-3">
                        <p class="text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">Bank Tujuan Transfer <span class="text-[#ff6b00]">*</span></p>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="bank in banks" :key="bank.id">
                                <button type="button"
                                        @click="selectedBankId = bank.id"
                                        :class="selectedBankId === bank.id ? 'border-[#ff6b00] bg-[#ff6b00]/10 text-white shadow-[0_0_12px_rgba(255,107,0,0.25)]' : 'border-white/10 bg-white/[0.02] text-gray-400 hover:border-white/20 hover:text-gray-200'"
                                        class="py-2.5 rounded-xl border text-xs font-montserrat font-extrabold flex flex-col items-center gap-0.5 transition-all">
                                    <span x-text="bank.code" class="text-sm"></span>
                                    <span x-text="bank.shortName" class="text-[9px] font-questrial text-gray-500 font-normal"></span>
                                </button>
                            </template>
                        </div>

                        {{-- Tampilan No. Rekening yang dipilih --}}
                        <div class="flex items-center justify-between gap-3 p-3.5 rounded-2xl bg-[#0e0e11] border border-white/10">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="px-2 py-0.5 rounded text-[10px] font-montserrat font-black border shrink-0"
                                      :class="currentBank.badgeBg"
                                      x-text="currentBank.code"></span>
                                <div class="min-w-0">
                                    <span class="text-sm font-audiowide font-bold text-white tracking-wider" x-text="currentBank.formatted"></span>
                                    <span class="text-[10px] font-questrial text-gray-500 block leading-none mt-0.5" x-text="'a.n. ' + currentBank.holder"></span>
                                </div>
                            </div>
                            <button type="button"
                                    @click="copyRekening(currentBank.number)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 text-xs font-montserrat font-bold text-gray-300 transition-all shrink-0 active:scale-95">
                                <i :class="rekeningCopied ? 'ph-bold ph-check text-emerald-400' : 'ph-bold ph-copy'"></i>
                                <span x-text="rekeningCopied ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Upload Bukti Transfer --}}
                    <div>
                        <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider mb-2">
                            Bukti Transfer <span class="text-gray-500 font-normal normal-case">(Opsional — bisa dikirim nanti)</span>
                        </label>
                        <label class="relative block border-2 border-dashed border-white/15 hover:border-[#ff6b00]/50 rounded-2xl p-5 text-center transition-all bg-white/[0.01] cursor-pointer">
                            <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                                   @change="handleFile($event)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex items-center justify-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#ff6b00]/10 flex items-center justify-center text-[#ff6b00] shrink-0">
                                    <i class="ph-bold ph-upload-simple text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <template x-if="!proofFileName">
                                        <div>
                                            <p class="text-xs font-montserrat font-bold text-white">Klik untuk Pilih File</p>
                                            <p class="text-[10px] font-questrial text-gray-500">JPG, PNG, PDF — maks. 5MB</p>
                                        </div>
                                    </template>
                                    <template x-if="proofFileName">
                                        <div class="flex items-center gap-1.5 text-xs font-montserrat font-bold text-[#ff6b00]">
                                            <i class="ph-bold ph-file-text"></i>
                                            <span x-text="proofFileName" class="truncate max-w-[180px]"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </label>
                    </div>

            </div>

            {{-- ── SIDEBAR RINGKASAN PESANAN (STICKY DESKTOP) ── --}}


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="cursor-pointer block h-full">
                            <input type="radio" name="payment_type" value="dp" class="sr-only" x-model="paymentType">
                            <div class="p-5 rounded-2xl border transition-all h-full relative flex flex-col justify-between"
                                 :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.2)]' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-montserrat font-bold uppercase text-[#ff6b00] bg-[#ff6b00]/15 px-2 py-0.5 rounded-md border border-[#ff6b00]/30">Opsi Terpopuler (Opsi A)</span>
                                        <div class="w-4 h-4 rounded-full border border-white/30 flex items-center justify-center"
                                             :class="paymentType === 'dp' ? 'border-[#ff6b00] bg-[#ff6b00]' : ''">
                                            <div x-show="paymentType === 'dp'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                                        </div>
                                    </div>
                                    <h4 class="text-sm font-audiowide font-bold text-white">DP 50% di Awal</h4>
                                    <p class="text-xs font-questrial text-gray-400 mt-1 leading-relaxed">
                                        Kunci antrean slot jadwal dengan DP 50%. Sisa 50% dibayar langsung di workshop saat serah terima kendaraan.
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                                    <span class="text-gray-400 font-questrial">DP Sekarang:</span>
                                    <span class="font-audiowide font-bold text-[#ff6b00]" x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer block h-full">
                            <input type="radio" name="payment_type" value="lunas" class="sr-only" x-model="paymentType">
                            <div class="p-5 rounded-2xl border transition-all h-full relative flex flex-col justify-between"
                                 :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]/10 shadow-[0_0_20px_rgba(255,107,0,0.2)]' : 'border-white/10 bg-[#18181b] hover:border-white/20'">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-montserrat font-bold uppercase text-emerald-400 bg-emerald-500/15 px-2 py-0.5 rounded-md border border-emerald-500/30">Lunas &amp; Beres</span>
                                        <div class="w-4 h-4 rounded-full border border-white/30 flex items-center justify-center"
                                             :class="paymentType === 'lunas' ? 'border-[#ff6b00] bg-[#ff6b00]' : ''">
                                            <div x-show="paymentType === 'lunas'" class="w-1.5 h-1.5 rounded-full bg-black"></div>
                                        </div>
                                    </div>
                                    <h4 class="text-sm font-audiowide font-bold text-white">Pelunasan Penuh (100%)</h4>
                                    <p class="text-xs font-questrial text-gray-400 mt-1 leading-relaxed">
                                        Bayar penuh 100% di awal. Tidak perlu lagi repot transaksi saat mobil selesai diambil di workshop.
                                    </p>
                                </div>
                                <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                                    <span class="text-gray-400 font-questrial">Total Lunas:</span>
                                    <span class="font-audiowide font-bold text-white" x-text="formatRupiah(selectedLayanan ? selectedLayanan.price : 0)"></span>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Info Box Kebijakan Opsi A --}}
                    <div x-show="paymentType === 'dp'"
                         x-transition
                         class="p-4 rounded-2xl bg-[#ff6b00]/10 border border-[#ff6b00]/30 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-[#ff6b00]/20 border border-[#ff6b00]/30 flex items-center justify-center text-[#ff6b00] shrink-0 mt-0.5">
                            <i class="ph-bold ph-storefront text-base"></i>
                        </div>
                        <div class="space-y-1">
                            <h5 class="text-xs font-montserrat font-bold text-white uppercase tracking-wider">
                                Ketentuan Skema Opsi A (Pelunasan di Workshop)
                            </h5>
                            <p class="text-xs font-questrial text-gray-300 leading-relaxed">
                                Anda cukup membayar DP 50% (<span class="text-[#ff6b00] font-bold" x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span>) sekarang untuk mengunci jadwal. Sisa pelunasan sebesar <span class="text-white font-bold" x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></span> dibayar langsung di workshop Dantie Stiker (bisa Cash, Debit, atau QRIS) saat serah terima mobil selesai.
                            </p>
                        </div>
                    </div>

                    {{-- ── Pilihan Bank Tujuan Transfer (Dropdown + Quick Selector) ── --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider">
                                Bank Tujuan Pembayaran <span class="text-[#ff6b00]">*</span>
                            </label>
                            <span class="text-[10px] font-questrial text-gray-400">Pilih bank tujuan transfer</span>
                        </div>

                        {{-- Dropdown Pilih Bank (Native Select untuk Form) --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#ff6b00]">
                                <i class="ph-bold ph-bank text-base"></i>
                            </div>
                            <select name="payment_method"
                                    x-model="selectedBankId"
                                    class="field-input w-full pl-10 pr-10 py-3.5 rounded-2xl bg-[#19191e] border border-white/15 text-white font-montserrat font-bold text-xs uppercase tracking-wider appearance-none cursor-pointer">
                                <template x-for="bank in banks" :key="bank.id">
                                    <option :value="bank.id" x-text="bank.name + ' (' + bank.formatted + ')'" class="bg-[#19191e] text-white py-2"></option>
                                </template>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                <i class="ph-bold ph-caret-down text-sm"></i>
                            </div>
                        </div>

                        {{-- Quick Access Bank Selector Pills (1-Tap Switch) --}}
                        <div class="grid grid-cols-3 gap-2 pt-1">
                            <template x-for="bank in banks" :key="bank.id">
                                <button type="button"
                                        @click="selectedBankId = bank.id"
                                        :class="selectedBankId === bank.id ? 'border-[#ff6b00] bg-[#ff6b00]/15 text-[#ff6b00] shadow-[0_0_15px_rgba(255,107,0,0.25)]' : 'border-white/10 bg-white/[0.02] text-gray-400 hover:border-white/20 hover:text-white'"
                                        class="px-3 py-2.5 rounded-xl border text-xs font-montserrat font-extrabold flex items-center justify-center gap-1.5 transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="selectedBankId === bank.id ? 'bg-[#ff6b00]' : 'bg-gray-600'"></span>
                                    <span x-text="bank.code"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Rekening Info dengan Tombol Salin 1-Klik --}}
                    <div class="p-5 rounded-2xl bg-[#0e0e11] border border-white/10 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white/[0.02] border border-white/5 p-4 rounded-xl">
                            <div class="space-y-1">
                                <span class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 block">Rekening Resmi Tujuan:</span>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-montserrat font-black border"
                                          :class="currentBank.badgeBg"
                                          x-text="currentBank.code"></span>
                                    <span class="text-base sm:text-lg font-audiowide font-bold text-white tracking-wider"
                                          x-text="currentBank.formatted"></span>
                                    <span class="text-xs font-questrial text-gray-400"
                                          x-text="'(a.n. ' + currentBank.holder + ')'"></span>
                                </div>
                            </div>
                            <button type="button"
                                    @click="copyRekening(currentBank.number)"
                                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/5 hover:bg-[#ff6b00] hover:text-black border border-white/10 text-xs font-montserrat font-bold text-gray-300 transition-all self-start sm:self-auto active:scale-95 shadow-sm">
                                <i :class="rekeningCopied ? 'ph-bold ph-check text-emerald-400' : 'ph-bold ph-copy'"></i>
                                <span x-text="rekeningCopied ? 'Tersalin!' : 'Salin No. Rek'"></span>
                            </button>
                        </div>

                        {{-- Ringkasan Nominal yang Harus Ditransfer --}}
                        <div class="p-3.5 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between gap-3 text-xs">
                            <div class="text-gray-400 font-questrial">
                                Nominal Ditransfer Sekarang (<span class="text-white font-montserrat font-bold" x-text="paymentType === 'dp' ? 'DP 50%' : 'Lunas 100%'"></span>):
                            </div>
                            <div class="text-sm font-audiowide font-bold text-[#ff6b00]"
                                 x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))">
                            </div>
                        </div>

                        {{-- Area Upload Bukti Transfer --}}
                        <div>
                            <label class="block text-xs font-montserrat font-bold text-gray-300 uppercase tracking-wider mb-2">
                                Bukti Transfer <span class="text-gray-500 font-normal">(Bisa diunggah sekarang atau nanti)</span>
                            </label>
                            <div class="relative border-2 border-dashed border-white/15 hover:border-[#ff6b00]/50 rounded-2xl p-4 sm:p-5 text-center transition-all bg-white/[0.01]">
                                <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                                       @change="handleFile($event)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <div class="w-10 h-10 rounded-xl bg-[#ff6b00]/10 flex items-center justify-center text-[#ff6b00]">
                                        <i class="ph-bold ph-upload-simple text-xl"></i>
                                    </div>
                                    <template x-if="!proofFileName">
                                        <div>
                                            <p class="text-xs font-montserrat font-bold text-white">Pilih File Bukti Transfer</p>
                                            <p class="text-[11px] font-questrial text-gray-500 mt-0.5">Format JPG, PNG, atau PDF (Maks. 5MB)</p>
                                        </div>
                                    </template>
                                    <template x-if="proofFileName">
                                        <div class="flex items-center gap-2 text-xs font-montserrat font-bold text-[#ff6b00]">
                                            <i class="ph-bold ph-file-text"></i>
                                            <span x-text="proofFileName"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            {{-- ── SIDEBAR RINGKASAN PESANAN (STICKY DESKTOP) ── --}}
            <aside class="lg:col-span-1 lg:sticky lg:top-24 space-y-6">
                <div class="bg-[#141414] border border-white/10 rounded-3xl p-6 shadow-2xl space-y-5 relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-44 h-44 rounded-full bg-[#ff6b00]/10 blur-3xl pointer-events-none"></div>

                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <h3 class="text-xs font-audiowide font-bold uppercase tracking-wider text-[#ff6b00]">
                            Ringkasan Pesanan
                        </h3>
                        <span class="text-[9px] font-montserrat font-bold uppercase px-2.5 py-0.5 rounded-full bg-white/5 border border-white/5 text-gray-300">
                            Estimasi
                        </span>
                    </div>

                    <dl class="space-y-3.5 text-xs">
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">Layanan</dt>
                            <dd class="font-montserrat font-bold text-white text-right" x-text="selectedLayanan ? selectedLayanan.name : 'Belum dipilih'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">Tanggal Booking</dt>
                            <dd class="font-audiowide font-bold text-[#ff6b00] text-right" x-text="formatDateShort(bookingDate)"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">Jam</dt>
                            <dd class="font-montserrat font-bold text-white text-right" x-text="bookingTime + ' WIB'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">Nama Pemesan</dt>
                            <dd class="font-montserrat font-bold text-white text-right truncate max-w-[140px]" x-text="customerName || '-'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">WhatsApp</dt>
                            <dd class="font-montserrat font-bold text-white text-right" x-text="customerPhone || '-'"></dd>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <dt class="font-questrial text-gray-400">Bank Tujuan</dt>
                            <dd class="font-montserrat font-bold text-white text-right" x-text="currentBank.code + ' (' + currentBank.formatted + ')'"></dd>
                        </div>

                        <div class="border-t border-white/10 pt-4 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <dt class="font-questrial text-gray-400">Total Harga</dt>
                                <dd class="font-audiowide font-bold text-white" x-text="formatRupiah(selectedLayanan ? selectedLayanan.price : 0)"></dd>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-1">
                                <dt class="font-montserrat font-bold text-white" x-text="paymentType === 'dp' ? 'Wajib DP (50%)' : 'Tagihan Lunas'"></dt>
                                <dd class="text-xl font-audiowide font-bold text-[#ff6b00]" x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))"></dd>
                            </div>
                            <template x-if="paymentType === 'dp'">
                                <div class="pt-2 border-t border-dashed border-white/10 space-y-1">
                                    <div class="flex justify-between items-center text-xs">
                                        <dt class="font-questrial text-gray-400">Pelunasan di Workshop</dt>
                                        <dd class="font-montserrat font-bold text-gray-200" x-text="formatRupiah(selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0)"></dd>
                                    </div>
                                    <p class="text-[10px] font-questrial text-[#ff6b00] leading-tight">*Opsi A: Sisa 50% dibayar saat serah terima mobil</p>
                                </div>
                            </template>
                        </div>
                    </dl>

                    {{-- Tombol Konfirmasi Booking Langsung (Desktop) --}}
                    <div class="pt-3">
                        <button type="submit"
                                :disabled="selectedDateQuota.is_full || isSubmitting"
                                class="w-full py-4 inline-flex items-center justify-center gap-2 bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider rounded-2xl hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_6px_25px_rgba(255,107,0,0.35)] disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg x-show="isSubmitting" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="isSubmitting ? 'Memproses Pesanan...' : (selectedDateQuota.is_full ? 'Tanggal Penuh' : 'Konfirmasi Booking Sekarang')">Konfirmasi Booking Sekarang</span>
                            <i x-show="!isSubmitting" class="ph-bold ph-arrow-right text-sm"></i>
                        </button>
                    </div>

                    <p class="text-[11px] font-questrial text-gray-500 text-center flex items-center justify-center gap-1.5 pt-1">
                        <i class="ph-bold ph-shield-check text-emerald-400 text-sm"></i>
                        Transaksi aman &amp; garansi pengerjaan
                    </p>
                </div>
            </aside>

        </div>

        {{-- ════ FLOATING BOTTOM BAR (MOBILE & TABLET ONLY) ════ --}}
        <div class="fixed bottom-0 inset-x-0 z-40 bg-[#141414]/95 backdrop-blur-xl border-t border-white/10 px-4 py-3 shadow-[0_-8px_30px_rgba(0,0,0,0.8)] lg:hidden">
            <div class="max-w-xl mx-auto flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-montserrat font-bold uppercase tracking-wider text-gray-400 truncate">
                        <span x-text="selectedLayanan ? selectedLayanan.name : 'Pilih Layanan'"></span>
                        &bull;
                        <span x-text="formatDateShort(bookingDate)"></span>
                    </p>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-[10px] font-montserrat font-bold text-gray-300" x-text="paymentType === 'dp' ? 'DP:' : 'Total:'"></span>
                        <span class="text-base sm:text-lg font-audiowide font-bold text-[#ff6b00]"
                              x-text="formatRupiah(paymentType === 'dp' ? (selectedLayanan ? Math.round(selectedLayanan.price * 0.5) : 0) : (selectedLayanan ? selectedLayanan.price : 0))"></span>
                    </div>
                </div>
                <button type="submit"
                        :disabled="selectedDateQuota.is_full || isSubmitting"
                        class="px-5 py-3 rounded-xl bg-[#ff6b00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#ff6b00]/30 shrink-0 flex items-center gap-1.5 active:scale-95 disabled:opacity-40">
                    <svg x-show="isSubmitting" class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Memproses...' : (selectedDateQuota.is_full ? 'Penuh' : 'Konfirmasi')">Konfirmasi</span>
                    <i x-show="!isSubmitting" class="ph-bold ph-arrow-right text-sm"></i>
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function bookingApp(config) {
    const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const DAYS = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const MAX_SLOT = 5;

    return {
        layanans: config.layanans || [],
        paymentType: config.initialPaymentType || 'dp',
        bookingDate: config.initialDate || new Date().toISOString().slice(0, 10),
        bookingTime: config.initialTime || '09:00',
        layananId: config.initialLayananId || (config.layanans[0]?.id ?? null),
        customerName: config.defaultName || '',
        customerPhone: config.defaultPhone || '',
        customerEmail: config.defaultEmail || '',
        vehicleName: '',
        vehicleColor: '',
        vehicleLicense: '',
        notes: '',
        proofFile: null,
        proofFileName: '',
        rekeningCopied: false,

        // Daftar Bank Tujuan Transfer
        banks: [
            {
                id: 'Transfer BCA',
                code: 'BCA',
                name: 'Bank Central Asia (BCA)',
                number: '8720998811',
                formatted: '8720-9988-11',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-blue-500/20 text-blue-400 border-blue-500/30'
            },
            {
                id: 'Transfer BRI',
                code: 'BRI',
                name: 'Bank Rakyat Indonesia (BRI)',
                number: '012301001234530',
                formatted: '0123-01-001234-53-0',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-sky-500/20 text-sky-400 border-sky-500/30'
            },
            {
                id: 'Transfer BSI',
                code: 'BSI',
                name: 'Bank Syariah Indonesia (BSI)',
                number: '7123456789',
                formatted: '7123-4567-89',
                holder: 'Dantie Stiker',
                badgeBg: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'
            }
        ],
        selectedBankId: config.initialPaymentMethod || 'Transfer BCA',

        get currentBank() {
            return this.banks.find(b => b.id === this.selectedBankId) || this.banks[0];
        },

        // Anti-Double-Submit
        isSubmitting: false,

        // Calendar modes: 'month', 'week', 'day'
        viewMode: 'month',
        calYear: new Date().getFullYear(),
        calMonth: new Date().getMonth(),
        calCells: [],
        calQuota: {},
        weekOffset: 0,
        weekDays: [],

        get selectedLayanan() {
            return this.layanans.find(l => String(l.id) === String(this.layananId)) || null;
        },

        get selectedDateQuota() {
            const q = this.calQuota[this.bookingDate];
            if (!q) {
                return { available: MAX_SLOT, is_full: false, total_used: 0 };
            }
            return q;
        },

        get calMonthLabel() {
            return `${MONTHS[this.calMonth]} ${this.calYear}`;
        },

        get weekRangeLabel() {
            if (!this.weekDays.length) return '';
            const start = this.weekDays[0];
            const end = this.weekDays[this.weekDays.length - 1];
            return `${start.dayNum} ${start.monthShort} - ${end.dayNum} ${end.monthShort} ${end.year}`;
        },

        setViewMode(mode) {
            this.viewMode = mode;
            if (mode === 'week') this.renderWeek();
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
            this.renderWeek();
        },

        async loadCalQuota() {
            try {
                const res = await fetch(`/api/booking/quota-month/${this.calYear}/${this.calMonth + 1}`);
                if (res.ok) {
                    const json = await res.json();
                    this.calQuota = json.quota ?? {};
                }
            } catch (e) {
                console.error('Gagal memuat kuota kalender:', e);
            }
        },

        renderCalendar() {
            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

            const firstDay = new Date(this.calYear, this.calMonth, 1);
            const startWeekday = firstDay.getDay();
            const daysInMonth = new Date(this.calYear, this.calMonth + 1, 0).getDate();
            const cells = [];

            for (let i = 0; i < startWeekday; i++) {
                cells.push({ empty: true, key: `empty_${i}` });
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const monthStr = String(this.calMonth + 1).padStart(2, '0');
                const dayStr = String(d).padStart(2, '0');
                const dateStr = `${this.calYear}-${monthStr}-${dayStr}`;
                const past = dateStr < todayStr;
                const isToday = dateStr === todayStr;

                const q = this.calQuota[dateStr] || { available: MAX_SLOT, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX_SLOT);
                const used = q.total_used ?? (MAX_SLOT - available);

                let status = 'available';
                if (past) status = 'past';
                else if (isBlocked) status = 'blocked';
                else if (available <= 0) status = 'full';
                else if (available === 1) status = 'few';

                cells.push({
                    empty: false,
                    key: dateStr,
                    date: dateStr,
                    day: d,
                    status,
                    isToday,
                    used,
                    available,
                    blockedReason: q.blocked_reason,
                    disabled: past || isBlocked || status === 'full',
                });
            }

            this.calCells = cells;
        },

        renderWeek() {
            const baseDate = new Date();
            baseDate.setDate(baseDate.getDate() + (this.weekOffset * 7));
            const dayOfWeek = baseDate.getDay();
            const sunday = new Date(baseDate);
            sunday.setDate(baseDate.getDate() - dayOfWeek);

            const now = new Date();
            const todayStr = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

            const days = [];
            for (let i = 0; i < 7; i++) {
                const cur = new Date(sunday);
                cur.setDate(sunday.getDate() + i);

                const y = cur.getFullYear();
                const m = String(cur.getMonth() + 1).padStart(2, '0');
                const d = String(cur.getDate()).padStart(2, '0');
                const dateStr = `${y}-${m}-${d}`;
                const past = dateStr < todayStr;

                const q = this.calQuota[dateStr] || { available: MAX_SLOT, is_full: false, total_used: 0, is_blocked: false };
                const isBlocked = Boolean(q.is_blocked);
                const available = isBlocked ? 0 : (q.available ?? MAX_SLOT);
                const used = q.total_used ?? (MAX_SLOT - available);

                let status = 'available';
                let statusText = `${used}/5 Terisi`;
                let subText = `${available} slot tersisa`;

                if (past) {
                    status = 'past';
                    statusText = 'Lewat';
                    subText = 'Tidak dapat dipilih';
                } else if (isBlocked) {
                    status = 'blocked';
                    statusText = 'Tutup / Libur';
                    subText = q.blocked_reason || 'Tanggal diblokir';
                } else if (available <= 0) {
                    status = 'full';
                    statusText = 'PENUH (5/5)';
                    subText = '0 slot tersisa';
                } else if (available === 1) {
                    status = 'few';
                    statusText = 'Hampir Penuh (4/5)';
                    subText = 'Sisa 1 slot!';
                }

                days.push({
                    date: dateStr,
                    dayName: DAYS[cur.getDay()],
                    dayNum: cur.getDate(),
                    monthShort: MONTHS_SHORT[cur.getMonth()],
                    year: y,
                    status,
                    statusText,
                    subText,
                    disabled: past || isBlocked || status === 'full',
                });
            }

            this.weekDays = days;
        },

        getCellClasses(cell) {
            const isSelected = cell.date === this.bookingDate;

            if (isSelected) {
                return 'border-[#ff6b00] bg-[#ff6b00] text-black font-extrabold shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white scale-105 z-10';
            }

            if (cell.status === 'past' || cell.status === 'blocked') {
                return 'border-white/5 bg-white/[0.02] text-gray-500 cursor-not-allowed opacity-50';
            }
            if (cell.status === 'full') {
                return 'border-rose-500/30 bg-rose-500/10 text-rose-300 cursor-not-allowed';
            }
            if (cell.status === 'few') {
                return 'border-[#ff6b00]/30 bg-[#ff6b00]/10 text-[#ff6b00] hover:border-[#ff6b00] hover:bg-[#ff6b00]/20';
            }
            return 'border-emerald-500/20 bg-emerald-500/5 text-emerald-100 hover:border-emerald-400 hover:bg-emerald-500/15';
        },

        selectDate(dateStr) {
            this.bookingDate = dateStr;
            const [y, m] = dateStr.split('-').map(Number);
            if (y && m && (y !== this.calYear || m - 1 !== this.calMonth)) {
                this.calYear = y;
                this.calMonth = m - 1;
                this.loadCalQuota().then(() => this.renderCalendar());
            }
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

        prevWeek() {
            this.weekOffset--;
            this.renderWeek();
        },

        nextWeek() {
            this.weekOffset++;
            this.renderWeek();
        },

        prevDay() {
            const d = new Date(this.bookingDate);
            d.setDate(d.getDate() - 1);
            const now = new Date();
            now.setHours(0,0,0,0);
            if (d >= now) {
                this.bookingDate = d.toISOString().slice(0, 10);
            }
        },

        nextDay() {
            const d = new Date(this.bookingDate);
            d.setDate(d.getDate() + 1);
            this.bookingDate = d.toISOString().slice(0, 10);
        },

        formatDateShort(dateStr) {
            if (!dateStr) return '-';
            const [y, m, d] = dateStr.split('-').map(Number);
            if (!y || !m || !d) return '-';
            const dt = new Date(y, m - 1, d);
            return `${d} ${MONTHS_SHORT[m - 1]} ${y}`;
        },

        formatDateFull(dateStr) {
            if (!dateStr) return 'Pilih Tanggal';
            const [y, m, d] = dateStr.split('-').map(Number);
            if (!y || !m || !d) return 'Pilih Tanggal';
            const dt = new Date(y, m - 1, d);
            return `${DAYS[dt.getDay()]}, ${d} ${MONTHS[m - 1]} ${y}`;
        },

        formatRupiah(num) {
            return 'Rp ' + Math.round(Number(num || 0)).toLocaleString('id-ID');
        },

        handleFile(event) {
            const file = event.target.files[0] || null;
            this.proofFile = file;
            this.proofFileName = file ? file.name : '';
        },

        copyRekening(nomor) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(nomor);
                this.rekeningCopied = true;
                setTimeout(() => {
                    this.rekeningCopied = false;
                }, 2000);
            }
        },

        submitBooking(e) {
            if (!this.customerName || !this.customerName.trim()) {
                alert('Silakan isi nama lengkap Anda!');
                if (e) e.preventDefault();
                return false;
            }
            if (!this.customerPhone || !this.customerPhone.trim()) {
                alert('Silakan isi nomor WhatsApp Anda!');
                if (e) e.preventDefault();
                return false;
            }
            if (!this.layananId) {
                alert('Silakan pilih salah satu paket layanan!');
                if (e) e.preventDefault();
                return false;
            }
            if (!this.bookingDate) {
                alert('Silakan pilih tanggal booking pada kalender!');
                if (e) e.preventDefault();
                return false;
            }
            if (this.selectedDateQuota && this.selectedDateQuota.is_full) {
                alert('Tanggal yang Anda pilih sudah PENUH (5/5). Silakan pilih tanggal lain yang masih tersedia!');
                if (e) e.preventDefault();
                return false;
            }
            this.isSubmitting = true;
            return true;
        }
    };
}
</script>
@endsection
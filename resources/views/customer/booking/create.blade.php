@extends('layouts.dashboard-customer')

@php
    $accentColor = '#ff6b00';

    // Ambil data rating summary per layanan persis seperti di katalog
    $ratingSummary = \App\Http\Controllers\TestimoniController::summaryPerLayananKeyed();

    // Fallback foto lokal jika file upload belum tersedia
    $localFallbacks = [
        'variasi mobil' => asset('images/layanan/wrapping-mobil.jpg'),
        'wrapping'      => asset('images/layanan/wrapping-mobil.jpg'),
        'kaca film'     => asset('images/layanan/kaca-film.jpg'),
        'audio'         => asset('images/layanan/audio-head.jpg'),
        'audio mobil'   => asset('images/layanan/audio-head.jpg'),
        'lampu biled'   => asset('images/layanan/lampu-biled.jpg'),
        'striping'      => asset('images/layanan/layanan-striping.jpg'),
    ];

    // Petakan data layanan persis seperti katalog
    $selectableLayanans = $layanans->map(function ($package) use ($localFallbacks, $ratingSummary) {
        $fotoPath = $package->getOriginal('foto_contoh') ?? null;
        $imgSrc = null;
        if (!empty($fotoPath) && (file_exists(public_path($fotoPath)) || file_exists(public_path('storage/' . $fotoPath)) || str_starts_with($fotoPath, 'http'))) {
            $imgSrc = \App\Helpers\StaticContent::fotoUrl($fotoPath);
        } else {
            $matchKey = strtolower(trim($package->nama_layanan ?? ''));
            $catKey   = strtolower(trim($package->kategori ?? ''));
            $imgSrc   = $localFallbacks[$matchKey] ?? ($localFallbacks[$catKey] ?? asset('images/layanan/wrapping-mobil.jpg'));
        }

        $tipePaket = $package->tipe_paket ?: 'wrapping';
        $category = 'Wrapping';
        $lowerName = strtolower(trim($package->nama_layanan ?? ''));
        $lowerTipe = strtolower(trim($tipePaket));

        if (str_contains($lowerTipe, 'film') || str_contains($lowerName, 'film')) {
            $category = 'Kaca Film';
        } elseif (str_contains($lowerTipe, 'audio') || str_contains($lowerName, 'audio')) {
            $category = 'Audio';
        } elseif (str_contains($lowerTipe, 'striping')) {
            $category = 'Striping';
        } else {
            $category = 'Wrapping';
        }

        $fiturList = [];
        if (!empty($package->fitur) && is_array($package->fitur)) {
            foreach (array_slice($package->fitur, 0, 4) as $f) {
                $fiturList[] = is_array($f) ? ($f['nama_fitur'] ?? ($f[0] ?? '')) : $f;
            }
        }

        $rating = $ratingSummary[$package->id_layanan] ?? null;

        return [
            'id'           => $package->id_layanan,
            'name'         => $package->nama_layanan,
            'tipe_paket'   => strtoupper($tipePaket),
            'category'     => $category,
            'price'        => (int) round((float) ($package->harga ?? 0)),
            'estimasi'     => $package->estimasi_waktu ?: '2-3 Hari',
            'deskripsi'    => $package->deskripsi,
            'foto'         => $imgSrc,
            'fitur'        => $fiturList,
            'rating_avg'   => $rating && $rating['count'] > 0 ? number_format((float) $rating['avg'], 1, ',', '.') : null,
            'rating_count' => $rating['count'] ?? 0,
        ];
    })->values();

    // Filter Kategori
    $categories = ['Semua'];
    foreach ($selectableLayanans as $sL) {
        if (!in_array($sL['category'], $categories)) {
            $categories[] = $sL['category'];
        }
    }
    $desiredOrder = ['Semua', 'Wrapping', 'Kaca Film', 'Audio'];
    $categories = array_values(array_unique(array_merge($desiredOrder, $categories)));

    // 14 hari ke depan untuk Quick Date Strip
    $today = \Carbon\Carbon::today();
    $stripDays = [];
    for ($i = 0; $i < 14; $i++) {
        $d = $today->copy()->addDays($i);
        $stripDays[] = [
            'date_str'   => $d->toDateString(),
            'day_num'    => $d->format('d'),
            'day_name'   => $d->translatedFormat('D'),
            'month'      => $d->translatedFormat('M'),
            'is_today'   => ($i === 0),
        ];
    }

    $defaultName  = old('customer_name', $user->name ?? '');
    $defaultPhone = old('customer_phone', $user->no_hp ?? ($user->phone ?? ''));
    $defaultEmail = old('customer_email', $user->email ?? '');
@endphp

@section('title', 'Jadwal Booking Pengerjaan - Dantie Stiker')

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
        box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
        outline: none;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto text-white relative space-y-7 pb-24 lg:pb-12"
     x-data="bookingWizardApp({
         layanans: {{ json_encode($selectableLayanans) }},
         categories: {{ json_encode($categories) }},
         stripDays: {{ json_encode($stripDays) }},
         initialPaymentType: '{{ old('payment_type', 'dp') }}',
         initialPaymentMethod: '{{ old('payment_method', 'Transfer BCA') }}',
         initialDate: '{{ old('booking_date', $selectedDate) }}',
         initialTime: '{{ old('booking_time', '08:30') }}',
         initialLayananId: '{{ old('layanan_id', $selectedLayananId ?? ($selectableLayanans[0]['id'] ?? '')) }}',
         defaultName: '{{ addslashes($defaultName) }}',
         defaultPhone: '{{ addslashes($defaultPhone) }}',
         defaultEmail: '{{ addslashes($defaultEmail) }}'
     })"
     x-init="initApp()">

    {{-- Ambient Glows --}}
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#ff6b00]/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute top-1/2 -left-32 w-80 h-80 bg-[#ff6b00]/5 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- ════ 1. HEADER HALAMAN ════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-5 relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#ff6b00]/15 border border-[#ff6b00]/30 text-[#ff6b00] text-[11px] font-montserrat font-bold uppercase tracking-wider mb-2 shadow-[0_0_15px_rgba(255,107,0,0.25)]">
                <span>RESERVASI ONLINE</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-audiowide font-bold text-white tracking-wide">
                Jadwal Booking Pengerjaan
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-gray-400 mt-1 max-w-xl">
                Sistem reservasi dan booking jadwal pengerjaan variasi kendaraan terpercaya
            </p>
        </div>

        <div>
            <a href="{{ route('booking.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#141416] hover:bg-white/10 border border-white/10 text-xs font-montserrat font-bold text-gray-300 hover:text-white transition-all shadow-sm">
                <i class="ph-bold ph-calendar-check text-base text-[#ff6b00]"></i>
                <span>Riwayat Booking</span>
            </a>
        </div>
    </div>

    {{-- ════ 2. STEPPER PROGRESS BAR (4 LANGKAH) ════ --}}
    <div class="relative z-10 bg-[#121215] border border-white/10 rounded-3xl p-5 sm:p-6 shadow-xl">
        <div class="relative">
            <div class="absolute top-4 sm:top-4.5 left-0 right-0 h-0.5 bg-white/10 -translate-y-1/2 z-0 hidden sm:block"></div>

            <div class="grid grid-cols-4 gap-2 relative z-10 text-center">
                {{-- Step 1 --}}
                <button type="button" @click="goToStep(1)" class="group flex flex-col items-center">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-montserrat font-black transition-all"
                         :class="currentStep === 1 ? 'bg-[#ff6b00] text-black shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white/20' : (currentStep > 1 ? 'bg-[#ff6b00] text-black font-black' : 'bg-[#222228] text-gray-400 border border-white/10')">
                        <template x-if="currentStep > 1"><i class="ph-bold ph-check text-sm"></i></template>
                        <template x-if="currentStep <= 1"><span>1</span></template>
                    </div>
                    <span class="text-[11px] sm:text-xs font-montserrat font-bold mt-2 transition-colors"
                          :class="currentStep === 1 ? 'text-white' : (currentStep > 1 ? 'text-gray-300' : 'text-gray-500')">
                        Pilih Layanan
                    </span>
                </button>

                {{-- Step 2 --}}
                <button type="button" @click="goToStep(2)" class="group flex flex-col items-center">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-montserrat font-black transition-all"
                         :class="currentStep === 2 ? 'bg-[#ff6b00] text-black shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white/20' : (currentStep > 2 ? 'bg-[#ff6b00] text-black font-black' : 'bg-[#222228] text-gray-400 border border-white/10')">
                        <template x-if="currentStep > 2"><i class="ph-bold ph-check text-sm"></i></template>
                        <template x-if="currentStep <= 2"><span>2</span></template>
                    </div>
                    <span class="text-[11px] sm:text-xs font-montserrat font-bold mt-2 transition-colors"
                          :class="currentStep === 2 ? 'text-white' : (currentStep > 2 ? 'text-gray-300' : 'text-gray-500')">
                        Pilih Tanggal
                    </span>
                </button>

                {{-- Step 3 --}}
                <button type="button" @click="goToStep(3)" class="group flex flex-col items-center">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-montserrat font-black transition-all"
                         :class="currentStep === 3 ? 'bg-[#ff6b00] text-black shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white/20' : (currentStep > 3 ? 'bg-[#ff6b00] text-black font-black' : 'bg-[#222228] text-gray-400 border border-white/10')">
                        <template x-if="currentStep > 3"><i class="ph-bold ph-check text-sm"></i></template>
                        <template x-if="currentStep <= 3"><span>3</span></template>
                    </div>
                    <span class="text-[11px] sm:text-xs font-montserrat font-bold mt-2 transition-colors"
                          :class="currentStep === 3 ? 'text-white' : (currentStep > 3 ? 'text-gray-300' : 'text-gray-500')">
                        Data Diri
                    </span>
                </button>

                {{-- Step 4 --}}
                <button type="button" @click="goToStep(4)" class="group flex flex-col items-center">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-montserrat font-black transition-all"
                         :class="currentStep === 4 ? 'bg-[#ff6b00] text-black shadow-[0_0_18px_rgba(255,107,0,0.5)] ring-2 ring-white/20' : 'bg-[#222228] text-gray-400 border border-white/10'">
                        <span>4</span>
                    </div>
                    <span class="text-[11px] sm:text-xs font-montserrat font-bold mt-2 transition-colors"
                          :class="currentStep === 4 ? 'text-white' : 'text-gray-500'">
                        Pembayaran
                    </span>
                </button>
            </div>
        </div>

        {{-- Segmented Track Bar --}}
        <div class="w-full bg-[#1e1e23] h-1.5 rounded-full overflow-hidden mt-4">
            <div class="h-full bg-[#FF6B00] transition-all duration-300 rounded-full"
                 :style="'width: ' + ((currentStep / 4) * 100) + '%'"></div>
        </div>
    </div>

    {{-- ════ 3. NOTIFIKASI ERROR / ALERT ════ --}}
    @if(session('toast_error'))
        <div class="p-4 rounded-2xl bg-red-500/15 border border-red-500/40 text-red-200 text-xs sm:text-sm font-montserrat font-medium flex items-center gap-3 shadow-lg">
            <i class="ph-bold ph-warning-circle text-lg text-red-400 shrink-0"></i>
            <div>{{ session('toast_error') }}</div>
        </div>
    @endif

    {{-- ════ 4. FORM BOOKING UTAMA ════ --}}
    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" @submit="submitBooking($event)">
        @csrf

        {{-- Hidden Form Fields --}}
        <input type="hidden" name="layanan_id" x-model="layananId" required>
        <input type="hidden" name="booking_date" x-model="bookingDate" required>
        <input type="hidden" name="booking_time" x-model="bookingTime">
        <input type="hidden" name="payment_type" x-model="paymentType">
        <input type="hidden" name="payment_method" x-model="selectedBankId">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start relative z-10">

            {{-- Kolom Kiri: Wizard Steps --}}
            <div class="lg:col-span-2 space-y-6">
                @include('customer.booking.partials._step-1-layanan')
                @include('customer.booking.partials._step-2-jadwal')
                @include('customer.booking.partials._step-3-data-diri')
                @include('customer.booking.partials._step-4-pembayaran')
            </div>

            {{-- Kolom Kanan: Sidebar Ringkasan --}}
            @include('customer.booking.partials._sidebar-ringkasan')

        </div>

        {{-- Floating Mobile Bar --}}
        @include('customer.booking.partials._floating-mobile-bar')

    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/booking-wizard.js') }}"></script>
@endpush
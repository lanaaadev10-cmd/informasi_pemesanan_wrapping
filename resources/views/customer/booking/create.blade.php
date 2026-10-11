@extends('layouts.dashboard-customer')

@php
    $accentColor = '#ff6b00';

    // Ambil data rating summary per layanan persis seperti di katalog
    $ratingSummary = \App\Http\Controllers\TestimoniController::summaryPerLayananKeyed();

    // Fallback foto standar persis seperti katalog
    $fallbackImages = \App\Helpers\StaticContent::LAYANAN_FALLBACK_IMAGES;

    // Petakan data layanan persis seperti katalog dengan dukungan asisten pintar
    $selectableLayanans = $layanans->map(function ($package, $idx) use ($fallbackImages, $ratingSummary) {
        $fotoPath = $package->getOriginal('foto_contoh') ?? $package->foto_contoh ?? null;
        $imgSrc = !empty($fotoPath)
            ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
            : asset($fallbackImages[$idx % count($fallbackImages)]);

        $tipePaket = $package->tipe_paket ?: 'wrapping';
        $lowerName = strtolower(trim($package->nama_layanan ?? ''));
        $lowerTipe = strtolower(trim($tipePaket));
        $kategoriKendaraan = strtolower(trim($package->kategori ?? 'mobil'));

        $isJasaPasang = str_contains($lowerTipe, 'jasa-pasang') || str_contains($lowerName, 'jasa pasang') || str_contains($lowerName, 'bahan sendiri');
        $isWrapping   = (str_contains($lowerTipe, 'wrapping') || str_contains($lowerName, 'full wrap')) && !$isJasaPasang;
        $isPartial    = in_array($lowerTipe, ['partial', 'striping', 'custom']) || str_contains($lowerName, 'atap') || str_contains($lowerName, 'striping');
        $isAksesoris  = in_array($lowerTipe, ['lighting', 'audio', 'window-film', 'aksesoris']) || str_contains($lowerName, 'biled') || str_contains($lowerName, 'audio') || str_contains($lowerName, 'kaca film');

        if ($isJasaPasang) {
            $scopeKey   = 'jasa-pasang';
            $category   = 'Jasa Pasang';
            $scopeBadge = '✂️ Jasa Pasang Saja';
            $scopeSub   = 'Bawa Bahan Sendiri';
            $scopeClass = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
        } elseif ($isWrapping) {
            $scopeKey   = 'wrapping';
            $category   = 'Full Wrap';
            $scopeBadge = '📦 Full Wrap Toko';
            $scopeSub   = 'Bahan Toko + Garansi';
            $scopeClass = 'bg-[#ff6b00]/20 text-[#ff6b00] border-[#ff6b00]/30';
        } elseif ($isPartial) {
            $scopeKey   = 'partial';
            $category   = 'Custom';
            $scopeBadge = '🎨 Custom';
            $scopeSub   = 'Atap / Striping Decal';
            $scopeClass = 'bg-purple-500/20 text-purple-300 border-purple-500/30';
        } else {
            $scopeKey   = 'aksesoris';
            $category   = 'Aksesoris & Film';
            $scopeBadge = '💡 Aksesoris & Film';
            $scopeSub   = 'Upgrade Fitur & Komponen';
            $scopeClass = 'bg-sky-500/20 text-sky-400 border-sky-500/30';
        }

        $fiturList = [];
        $compatibilityText = '';
        if (!empty($package->fitur) && is_array($package->fitur)) {
            foreach ($package->fitur as $f) {
                $itemText = is_array($f) ? ($f['nama_fitur'] ?? ($f[0] ?? '')) : $f;
                if (str_starts_with(strtolower($itemText), 'cocok:') || str_starts_with(strtolower($itemText), 'cocok untuk:')) {
                    $compatibilityText = $itemText;
                } else {
                    if (count($fiturList) < 3) {
                        $fiturList[] = $itemText;
                    }
                }
            }
        }

        $rating = $ratingSummary[$package->id_layanan] ?? null;

        return [
            'id'                => $package->id_layanan,
            'name'              => $package->nama_layanan,
            'jenis_layanan'     => $package->jenis_layanan ?? ($isJasaPasang ? 'jasa_pasang' : 'paket'),
            'tipe_paket'        => strtoupper($tipePaket),
            'raw_tipe_paket'    => $lowerTipe,
            'scope_key'         => $scopeKey,
            'kategori'          => $kategoriKendaraan,
            'kategori_label'    => $kategoriKendaraan === 'motor' ? 'Motor 🛵' : 'Mobil 🚗',
            'is_jasa_pasang'    => $isJasaPasang,
            'scope_badge'       => $scopeBadge,
            'scope_sub'         => $scopeSub,
            'scope_class'       => $scopeClass,
            'compatibility'     => $compatibilityText,
            'category'          => $category,
            'price'             => (int) round((float) ($package->harga ?? 0)),
            'estimasi'          => $package->estimasi_waktu ?: '2-3 Hari',
            'deskripsi'         => $package->deskripsi,
            'foto'              => $imgSrc,
            'fitur'             => $fiturList,
            'rating_avg'        => $rating && $rating['count'] > 0 ? number_format((float) $rating['avg'], 1, ',', '.') : null,
            'rating_count'      => $rating['count'] ?? 0,
        ];
    });

    // Urutkan layanan secara logis: Mobil dulu, lalu Motor, dengan urutan pengerjaan yang teratur
    $selectableLayanans = $selectableLayanans->sortBy(function ($item) {
        $vehicleWeight = $item['kategori'] === 'mobil' ? 10 : 20;
        $scopeWeight = match($item['scope_key']) {
            'wrapping'    => 1,
            'jasa-pasang' => 2,
            'partial'     => 3,
            'aksesoris'   => 4,
            default       => 5,
        };
        return ($vehicleWeight * 10) + $scopeWeight;
    })->values();

    // Filter Kategori Tradisional
    $categories = ['Semua', 'Full Wrap', 'Jasa Pasang', 'Custom', 'Aksesoris & Film'];

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
    [x-cloak] { display: none !important; }
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
<div class="max-w-7xl mx-auto text-slate-900 relative space-y-7 pb-24 lg:pb-12"
     x-data="bookingWizardApp({
         layanans: {{ json_encode($selectableLayanans) }},
         categories: {{ json_encode($categories) }},
         stripDays: {{ json_encode($stripDays) }},
         vehicleBrands: {{ json_encode($vehicleBrands ?? []) }},
         availableAddons: {{ json_encode($availableAddons ?? []) }},
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
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5 relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#ff6b00]/15 border border-[#ff6b00]/30 text-[#ff6b00] text-[11px] font-montserrat font-bold uppercase tracking-wider mb-2">
                <span>CHECKOUT RESERVASI</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-audiowide font-bold text-slate-900 tracking-wide">
                Checkout &amp; Booking Jadwal
            </h1>
            <p class="text-xs sm:text-sm font-questrial text-slate-500 mt-1 max-w-xl">
                Konfirmasi paket layanan, tentukan jadwal kedatangan, dan selesaikan reservasi kendaraan Anda
            </p>
        </div>

        <div>
            <a href="{{ route('booking.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-montserrat font-bold text-slate-700 shadow-sm transition-all">
                <i class="ph-bold ph-calendar-check text-base text-[#ff6b00]"></i>
                <span>Riwayat Booking</span>
            </a>
        </div>
    </div>

    {{-- ════ 2. E-COMMERCE CHECKOUT INTERACTIVE STEPPER ════ --}}
    <div class="relative z-10 bg-white border border-slate-200/80 rounded-2xl p-3 sm:p-4 shadow-sm space-y-3">
        {{-- Mobile Progress Tracker --}}
        <div class="sm:hidden">
            <div class="flex items-center justify-between text-[11px] font-montserrat font-bold text-slate-700 mb-1.5">
                <span class="flex items-center gap-1.5 text-[#ff6b00]">
                    <span>Langkah <span x-text="currentStep"></span> dari 4</span>
                </span>
                <span class="text-slate-500 font-questrial text-[10px]"
                      x-text="['', 'Pilih Layanan', 'Jadwal & Shift', 'Data Kendaraan', 'Metode Pembayaran'][currentStep]"></span>
            </div>
            <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#ff6b00] to-orange-400 rounded-full transition-all duration-300"
                     :style="'width: ' + (currentStep * 25) + '%'"></div>
            </div>
        </div>

        {{-- Desktop / Tablet Stepper Buttons --}}
        <div class="flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
            {{-- Step 1 --}}
            <button type="button" @click="goToStep(1)"
                    :class="currentStep === 1
                        ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                        : (currentStep > 1 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
                <span :class="currentStep === 1 ? 'bg-[#ff6b00] text-white' : (currentStep > 1 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                      class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                    <template x-if="currentStep > 1">
                        <i class="ph-bold ph-check text-xs"></i>
                    </template>
                    <template x-if="currentStep <= 1">
                        <span>1</span>
                    </template>
                </span>
                <span>Layanan Dipesan</span>
            </button>

            <i :class="currentStep > 1 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

            {{-- Step 2 --}}
            <button type="button" @click="goToStep(2)"
                    :class="currentStep === 2
                        ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                        : (currentStep > 2 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
                <span :class="currentStep === 2 ? 'bg-[#ff6b00] text-white' : (currentStep > 2 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                      class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                    <template x-if="currentStep > 2">
                        <i class="ph-bold ph-check text-xs"></i>
                    </template>
                    <template x-if="currentStep <= 2">
                        <span>2</span>
                    </template>
                </span>
                <span>Jadwal &amp; Shift</span>
            </button>

            <i :class="currentStep > 2 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

            {{-- Step 3 --}}
            <button type="button" @click="goToStep(3)"
                    :class="currentStep === 3
                        ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                        : (currentStep > 3 ? 'bg-emerald-50 border-emerald-300 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100')"
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
                <span :class="currentStep === 3 ? 'bg-[#ff6b00] text-white' : (currentStep > 3 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-600')"
                      class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                    <template x-if="currentStep > 3">
                        <i class="ph-bold ph-check text-xs"></i>
                    </template>
                    <template x-if="currentStep <= 3">
                        <span>3</span>
                    </template>
                </span>
                <span>Data Kendaraan</span>
            </button>

            <i :class="currentStep > 3 ? 'text-emerald-500' : 'text-slate-300'" class="ph-bold ph-caret-right text-xs shrink-0 transition-colors"></i>

            {{-- Step 4 --}}
            <button type="button" @click="goToStep(4)"
                    :class="currentStep === 4
                        ? 'bg-orange-50 border-[#ff6b00]/50 text-[#ff6b00] shadow-sm'
                        : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100'"
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl border text-xs font-montserrat font-bold shrink-0 transition-all cursor-pointer">
                <span :class="currentStep === 4 ? 'bg-[#ff6b00] text-white' : 'bg-slate-200 text-slate-600'"
                      class="w-5 h-5 rounded-full text-[10px] flex items-center justify-center font-black transition-all">
                    <span>4</span>
                </span>
                <span>Metode Pembayaran</span>
            </button>
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
        <input type="hidden" name="layanan_id" x-model="layananId">
        <input type="hidden" name="booking_date" x-model="bookingDate">
        <input type="hidden" name="booking_time" x-model="bookingTime">
        <input type="hidden" name="payment_type" x-model="paymentType">
        <input type="hidden" name="payment_method" x-model="selectedBankId">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start relative z-10">

            {{-- Kolom Kiri: Wizard Steps Berpindah --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- STEP 1: LAYANAN --}}
                <div x-show="currentStep === 1" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('customer.booking.partials._step-1-layanan')
                </div>

                {{-- STEP 2: JADWAL & SHIFT --}}
                <div x-show="currentStep === 2" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('customer.booking.partials._step-2-jadwal')
                </div>

                {{-- STEP 3: DATA KENDARAAN & DIRI --}}
                <div x-show="currentStep === 3" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('customer.booking.partials._step-3-data-diri')
                </div>

                {{-- STEP 4: METODE PEMBAYARAN --}}
                <div x-show="currentStep === 4" x-cloak
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('customer.booking.partials._step-4-pembayaran')
                </div>
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
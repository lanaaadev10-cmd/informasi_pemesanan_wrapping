@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Kalkulator Estimasi Biaya & Kebutuhan Bahan Wrapping')

@section('content')
<div class="max-w-6xl mx-auto py-6 sm:py-10 px-4 sm:px-6 text-white space-y-8 relative overflow-hidden">
    <!-- Glowing orange backdrop -->
    <div class="absolute -top-32 -left-32 w-[500px] h-[400px] bg-[#FF6B00]/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
    <div class="absolute top-1/2 -right-32 w-[400px] h-[350px] bg-[#FF6B00]/5 rounded-full blur-[130px] pointer-events-none z-0"></div>

    <!-- Header Section -->
    <div class="space-y-3 z-10 relative">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-[#FF6B00] animate-pulse"></span>
            <span class="text-[10px] font-montserrat font-black uppercase tracking-widest text-[#FF6B00]">
                Interactive Wrap Studio
            </span>
        </div>
        <h1 class="text-2xl sm:text-4xl font-audiowide font-bold text-white tracking-tight leading-tight">
            Kalkulator Estimasi Biaya &amp; Dimensi Stiker
        </h1>
        <p class="text-xs sm:text-sm font-questrial text-[#8A8D93] max-w-2xl leading-relaxed">
            Dapatkan estimasi akurat kebutuhan panjang bahan (meter), durasi pengerjaan, garansi, serta kisaran biaya pemasangan wrapping &amp; PPF untuk tipe kendaraan Anda secara transparan.
        </p>
    </div>

    <!-- Main Grid: Selector Controls (Left 7 Cols) + Live Calculation Card (Right 5 Cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start z-10 relative">
        
        <!-- LEFT: Interactive Step Controls (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            {{-- STEP 1: Pilih Kategori / Ukuran Kendaraan --}}
            <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-6 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-white/5 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#FF6B00] text-black font-montserrat font-black text-xs flex items-center justify-center">1</span>
                        <h2 class="text-sm sm:text-base font-montserrat font-bold text-white uppercase tracking-wider">
                            Pilih Ukuran Kendaraan
                        </h2>
                    </div>
                    <span class="text-[10px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest">Dimensi Bodi</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="vehicle-category-group">
                    @foreach($vehicleMetrics as $vKey => $vMetric)
                        @php
                            $isSelected = ($vKey === $defaultCategory);
                        @endphp
                        <button type="button"
                                data-type="category"
                                data-value="{{ $vKey }}"
                                onclick="selectOption('category', '{{ $vKey }}')"
                                class="option-btn text-left p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between min-h-[96px] {{ $isSelected ? 'bg-[#FF6B00]/10 border-[#FF6B00] text-white shadow-lg' : 'bg-white/[0.02] border-white/10 text-[#8A8D93] hover:border-white/20 hover:text-white' }}">
                            <div class="flex items-start justify-between w-full">
                                <span class="font-montserrat font-bold text-xs {{ $isSelected ? 'text-[#FF6B00]' : 'text-white' }}">
                                    {{ $vMetric['label'] }}
                                </span>
                                <i class="ph-bold {{ $isSelected ? 'ph-check-circle text-[#FF6B00]' : 'ph-circle text-gray-600' }} text-lg"></i>
                            </div>
                            <p class="text-[10px] font-questrial text-[#8A8D93] mt-2 line-clamp-1">
                                Contoh: {{ $vMetric['examples'] }}
                            </p>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 2: Pilih Cakupan Pengerjaan --}}
            <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-6 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-white/5 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#FF6B00] text-black font-montserrat font-black text-xs flex items-center justify-center">2</span>
                        <h2 class="text-sm sm:text-base font-montserrat font-bold text-white uppercase tracking-wider">
                            Pilih Cakupan Pengerjaan
                        </h2>
                    </div>
                    <span class="text-[10px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest">Scope</span>
                </div>

                <div class="space-y-3" id="scope-category-group">
                    @foreach($scopeMetrics as $sKey => $sMetric)
                        @php
                            $isSelected = ($sKey === $defaultScope);
                        @endphp
                        <button type="button"
                                data-type="scope"
                                data-value="{{ $sKey }}"
                                onclick="selectOption('scope', '{{ $sKey }}')"
                                class="option-btn w-full text-left p-4 rounded-2xl border transition-all duration-200 flex items-start justify-between gap-4 {{ $isSelected ? 'bg-[#FF6B00]/10 border-[#FF6B00] text-white shadow-lg' : 'bg-white/[0.02] border-white/10 text-[#8A8D93] hover:border-white/20 hover:text-white' }}">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-montserrat font-bold text-xs sm:text-sm {{ $isSelected ? 'text-[#FF6B00]' : 'text-white' }}">
                                        {{ $sMetric['label'] }}
                                    </span>
                                </div>
                                <p class="text-[11px] font-questrial text-[#8A8D93] leading-relaxed">
                                    {{ $sMetric['desc'] }}
                                </p>
                            </div>
                            <i class="ph-bold {{ $isSelected ? 'ph-check-circle text-[#FF6B00]' : 'ph-circle text-gray-600' }} text-xl shrink-0 mt-0.5"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- STEP 3: Karakter Bahan & Finishing --}}
            <div class="bg-[#0E0E10] border border-white/10 rounded-[28px] p-6 sm:p-7 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-white/5 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-[#FF6B00] text-black font-montserrat font-black text-xs flex items-center justify-center">3</span>
                        <h2 class="text-sm sm:text-base font-montserrat font-bold text-white uppercase tracking-wider">
                            Pilih Karakter Bahan &amp; Finishing
                        </h2>
                    </div>
                    <span class="text-[10px] font-montserrat font-bold text-[#8A8D93] uppercase tracking-widest">Material Finish</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="finish-category-group">
                    @foreach($finishMetrics as $fKey => $fMetric)
                        @php
                            $isSelected = ($fKey === $defaultFinish);
                        @endphp
                        <button type="button"
                                data-type="finish"
                                data-value="{{ $fKey }}"
                                onclick="selectOption('finish', '{{ $fKey }}')"
                                class="option-btn text-left p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between min-h-[86px] {{ $isSelected ? 'bg-[#FF6B00]/10 border-[#FF6B00] text-white shadow-lg' : 'bg-white/[0.02] border-white/10 text-[#8A8D93] hover:border-white/20 hover:text-white' }}">
                            <div class="flex items-start justify-between w-full">
                                <span class="font-montserrat font-bold text-xs {{ $isSelected ? 'text-[#FF6B00]' : 'text-white' }}">
                                    {{ $fMetric['label'] }}
                                </span>
                                <i class="ph-bold {{ $isSelected ? 'ph-check-circle text-[#FF6B00]' : 'ph-circle text-gray-600' }} text-lg"></i>
                            </div>
                            <p class="text-[10px] font-questrial text-[#8A8D93] mt-2">
                                {{ $fMetric['desc'] }}
                            </p>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- RIGHT: Live Result Card (Sticky on Desktop - 5 Cols) -->
        <div class="lg:col-span-5 sticky top-24 space-y-6">
            <div class="bg-[#0E0E10] border border-white/10 rounded-[32px] p-6 sm:p-8 shadow-2xl relative overflow-hidden" id="result-card">
                <!-- Glowing corner accent -->
                <div class="absolute -top-16 -right-16 w-36 h-36 bg-[#FF6B00]/15 rounded-full blur-[60px] pointer-events-none"></div>

                <!-- Card Header -->
                <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-lightning-fill text-[#FF6B00] text-lg"></i>
                        <span class="text-xs font-montserrat font-bold uppercase tracking-wider text-white">Ringkasan Estimasi</span>
                    </div>
                    <span id="loading-spinner" class="hidden text-xs font-montserrat font-bold text-[#FF6B00] flex items-center gap-1.5 animate-pulse">
                        <i class="ph ph-circle-notch ph-spin"></i> Menghitung...
                    </span>
                </div>

                <!-- Price Highlight -->
                <div class="space-y-1 mb-6">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#8A8D93] block">
                        Estimasi Investasi
                    </span>
                    <h3 class="text-3xl sm:text-4xl font-audiowide font-bold text-[#FF6B00]" id="result-price">
                        {{ $initialCalculation['estimasi_harga_formatted'] }}
                    </h3>
                    <p class="text-xs font-questrial text-[#8A8D93] mt-1" id="result-price-range">
                        Rentang Biaya: {{ $initialCalculation['rentang_harga_formatted'] }}
                    </p>
                </div>

                <!-- Specs Matrix (Meter, Durasi, Garansi) -->
                <div class="grid grid-cols-2 gap-3 mb-6 font-questrial">
                    <div class="bg-[#16161A] border border-white/5 p-3.5 rounded-2xl">
                        <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93] block">Kebutuhan Bahan</span>
                        <span class="text-sm font-bold text-white mt-1 block" id="result-meter">
                            {{ $initialCalculation['panjang_bahan_formatted'] }}
                        </span>
                    </div>
                    <div class="bg-[#16161A] border border-white/5 p-3.5 rounded-2xl">
                        <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93] block">Estimasi Pengerjaan</span>
                        <span class="text-sm font-bold text-white mt-1 block" id="result-duration">
                            {{ $initialCalculation['estimasi_durasi'] }}
                        </span>
                    </div>
                    <div class="col-span-2 bg-[#16161A] border border-white/5 p-3.5 rounded-2xl">
                        <span class="text-[9px] font-montserrat font-bold uppercase tracking-wider text-[#8A8D93] block">Garansi Resmi</span>
                        <span class="text-sm font-bold text-[#FF6B00] mt-1 block" id="result-warranty">
                            {{ $initialCalculation['garansi'] }}
                        </span>
                    </div>
                </div>

                <!-- Included Standard Features -->
                <div class="border-t border-white/5 pt-4 mb-8 space-y-2.5 font-questrial">
                    <span class="text-[10px] font-montserrat font-bold uppercase tracking-widest text-[#8A8D93] block mb-3">
                        Termasuk Dalam Layanan:
                    </span>
                    <ul class="space-y-2 text-xs text-gray-300" id="result-features">
                        @foreach($initialCalculation['features'] as $feat)
                            <li class="flex items-start gap-2">
                                <i class="ph-bold ph-check text-[#FF6B00] text-sm shrink-0 mt-0.5"></i>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Direct CTA to Booking -->
                <a id="cta-booking-btn"
                   href="{{ $initialCalculation['booking_url'] }}"
                   class="w-full flex items-center justify-center gap-2 py-4 px-6 min-h-[50px] bg-[#FF6B00] hover:bg-[#E05D00] active:scale-95 text-black font-montserrat font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-[0_6px_25px_rgba(255,107,0,0.4)] transition-all">
                    <span>Amankan Slot &amp; Booking Sekarang</span>
                    <i class="ph-bold ph-arrow-right text-sm"></i>
                </a>
                <p class="text-[10px] text-[#8A8D93] font-questrial text-center mt-3">
                    Bebas konsultasi warna di bengkel sebelum pengerjaan dimulai.
                </p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // State kalkulator
    let currentCategory = '{{ $defaultCategory }}';
    let currentScope    = '{{ $defaultScope }}';
    let currentFinish   = '{{ $defaultFinish }}';

    async function selectOption(type, value) {
        if (type === 'category') currentCategory = value;
        if (type === 'scope') currentScope = value;
        if (type === 'finish') currentFinish = value;

        // Update UI Button active states
        updateGroupStyles(type, value);

        // Fetch live calculation from API
        await fetchCalculation();
    }

    function updateGroupStyles(type, activeValue) {
        const buttons = document.querySelectorAll(`button[data-type="${type}"]`);
        buttons.forEach(btn => {
            const val = btn.getAttribute('data-value');
            const icon = btn.querySelector('i');
            const title = btn.querySelector('.font-montserrat');

            if (val === activeValue) {
                btn.classList.remove('bg-white/[0.02]', 'border-white/10', 'text-[#8A8D93]');
                btn.classList.add('bg-[#FF6B00]/10', 'border-[#FF6B00]', 'text-white', 'shadow-lg');
                if (icon) {
                    icon.classList.remove('ph-circle', 'text-gray-600');
                    icon.classList.add('ph-check-circle', 'text-[#FF6B00]');
                }
                if (title) {
                    title.classList.remove('text-white');
                    title.classList.add('text-[#FF6B00]');
                }
            } else {
                btn.classList.remove('bg-[#FF6B00]/10', 'border-[#FF6B00]', 'text-white', 'shadow-lg');
                btn.classList.add('bg-white/[0.02]', 'border-white/10', 'text-[#8A8D93]');
                if (icon) {
                    icon.classList.remove('ph-check-circle', 'text-[#FF6B00]');
                    icon.classList.add('ph-circle', 'text-gray-600');
                }
                if (title) {
                    title.classList.remove('text-[#FF6B00]');
                    title.classList.add('text-white');
                }
            }
        });
    }

    async function fetchCalculation() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) spinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route('kalkulator.hitung') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    kategori_kendaraan: currentCategory,
                    cakupan_layanan: currentScope,
                    material_finish: currentFinish
                })
            });

            if (!response.ok) throw new Error('Gagal menghitung estimasi');

            const result = await response.json();
            if (result.status === 'success') {
                renderResult(result.data);
            }
        } catch (err) {
            console.error('Kalkulasi error:', err);
        } finally {
            if (spinner) spinner.classList.add('hidden');
        }
    }

    function renderResult(data) {
        document.getElementById('result-price').innerText = data.estimasi_harga_formatted;
        document.getElementById('result-price-range').innerText = 'Rentang Biaya: ' + data.rentang_harga_formatted;
        document.getElementById('result-meter').innerText = data.panjang_bahan_formatted;
        document.getElementById('result-duration').innerText = data.estimasi_durasi;
        document.getElementById('result-warranty').innerText = data.garansi;

        const ctaBtn = document.getElementById('cta-booking-btn');
        if (ctaBtn) ctaBtn.href = data.booking_url;

        // Render features
        const featContainer = document.getElementById('result-features');
        if (featContainer && data.features) {
            featContainer.innerHTML = data.features.map(f => `
                <li class="flex items-start gap-2">
                    <i class="ph-bold ph-check text-[#FF6B00] text-sm shrink-0 mt-0.5"></i>
                    <span>${f}</span>
                </li>
            `).join('');
        }
    }
</script>
@endpush
@endsection

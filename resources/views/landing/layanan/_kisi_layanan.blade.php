{{-- ═══════════════════════════════════════════════════════════════════
     SERVICE CARDS GRID (Sesuai Grid 4 Kolom di Gambar Referensi)
     Struktur Terorganisir:
     - Khusus Sepeda Motor
     - Khusus Mobil & Modifikasi
     - Responsif 4 Kolom di Desktop (Sesuai Referensi)
 ═══════════════════════════════════════════════════════════════════ --}}
@php
    $services = $services ?? [];
@endphp
@if(count($services) > 0)
    @php
        $motorServices = array_filter($services, fn($s) => strtolower($s['kategori'] ?? '') === 'motor');
        $mobilServices = array_filter($services, fn($s) => strtolower($s['kategori'] ?? '') !== 'motor');
    @endphp

    <div class="space-y-10 sm:space-y-14" id="public-services-wrapper">

        {{-- ══════════════════════════════════════════════════════
             SEKSI 1: 🛵 LAYANAN SEPEDA MOTOR
        ══════════════════════════════════════════════════════ --}}
        @if(count($motorServices) > 0)
            <div id="section-motor" data-section-vehicle="motor" class="space-y-5 sm:space-y-6 transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200/80 pb-5 gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#ea580c]">
                            <span>🛵 Motor</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500 font-normal normal-case tracking-normal">Bebek, Matic, Maxi &amp; Sport</span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold font-montserrat text-slate-900 tracking-tight">
                            Layanan Khusus Sepeda Motor
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl font-normal leading-relaxed">
                            Pilihan <strong class="text-slate-800 font-semibold">Jasa Pasang Saja</strong> hemat bawa bahan sendiri serta paket Full Wrap bergaransi.
                        </p>
                    </div>

                    <div class="shrink-0 self-start sm:self-end">
                        <span class="text-xs font-medium text-slate-500">
                            <strong id="count-motor-services" class="font-bold text-slate-900">{{ count($motorServices) }}</strong> Paket Pilihan
                        </span>
                    </div>
                </div>

                {{-- Grid Motor Cards: 4 Kolom di Desktop Sesuai Gambar Referensi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 items-stretch">
                    @foreach($motorServices as $idx => $svc)
                        @include('landing.layanan._kartu_layanan', ['svc' => $svc, 'idx' => $idx])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════════
             SEKSI 2: 🚗 LAYANAN MOBIL & MODIFIKASI
        ══════════════════════════════════════════════════════ --}}
        @if(count($mobilServices) > 0)
            <div id="section-mobil" data-section-vehicle="mobil" class="space-y-5 sm:space-y-6 transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between border-b border-slate-200/80 pb-5 gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#ea580c]">
                            <span>🚗 Mobil</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500 font-normal normal-case tracking-normal">City Car, Sedan, MPV, SUV</span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold font-montserrat text-slate-900 tracking-tight">
                            Layanan Khusus Mobil &amp; Modifikasi
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-500 max-w-2xl font-normal leading-relaxed">
                            Mulai dari Full Body Wrap, Atap Karbon, <strong class="text-slate-800 font-semibold">Jasa Pasang </strong>, kaca film tolak panas, hingga audio dan lighting.
                        </p>
                    </div>

                    <div class="shrink-0 self-start sm:self-end">
                        <span class="text-xs font-medium text-slate-500">
                            <strong id="count-mobil-services" class="font-bold text-slate-900">{{ count($mobilServices) }}</strong> Paket Pilihan
                        </span>
                    </div>
                </div>

                {{-- Grid Mobil Cards: 4 Kolom di Desktop Sesuai Gambar Referensi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 items-stretch">
                    @foreach($mobilServices as $idx => $svc)
                        @include('landing.layanan._kartu_layanan', ['svc' => $svc, 'idx' => $idx])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty State jika hasil filter/pencarian 0 --}}
        <div id="public-services-empty" class="hidden py-14 px-6 text-center border border-dashed border-slate-200 rounded-3xl bg-slate-50 w-full col-span-full">
            <div class="w-14 h-14 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center mx-auto mb-3">
                <i class="ph-bold ph-magnifying-glass text-xl text-[#ea580c]"></i>
            </div>
            <h4 class="text-base font-bold text-slate-900 mb-1">Tidak ada layanan yang sesuai filter</h4>
            <p class="text-xs text-slate-500 font-normal mb-4 max-w-md mx-auto">
                Tidak ditemukan paket yang cocok dengan kata kunci atau filter kendaraan yang dipilih.
            </p>
            <button type="button" onclick="resetPublicLayananFilters()"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 text-white font-montserrat font-medium text-xs shadow-md shadow-orange-500/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <i class="ph-bold ph-arrow-counter-clockwise"></i>
                <span>Tampilkan Semua Layanan</span>
            </button>
        </div>

    </div>
@else
    <div class="py-16 text-center border border-dashed border-slate-200 rounded-3xl bg-slate-50 w-full col-span-full">
        <div class="w-14 h-14 rounded-full bg-orange-50 border border-orange-100 flex items-center justify-center mx-auto mb-3">
            <i class="ph-bold ph-package text-xl text-[#ea580c]"></i>
        </div>
        <h4 class="text-base font-bold text-slate-900 mb-1">{{ \App\Helpers\StaticContent::LAYANAN_EMPTY }}</h4>
        <p class="text-xs text-slate-500 font-normal">Silakan periksa kembali nanti.</p>
    </div>
@endif

<x-register-prompt />

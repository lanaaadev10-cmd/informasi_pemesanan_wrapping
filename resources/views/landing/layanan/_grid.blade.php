{{-- ============================================
    BAGIAN: Service Cards Grid
    Deskripsi: Grid berisi daftar paket layanan terstruktur rapi:
    - Terpisah jelas antara Sepeda Motor & Mobil (tidak campur aduk)
    - Tanda jelas antara Jasa Pasang Saja vs Paket Toko All-In
============================================ --}}
@if(count($services) > 0)
    @php
        $motorServices = array_filter($services, fn($s) => strtolower($s['kategori'] ?? '') === 'motor');
        $mobilServices = array_filter($services, fn($s) => strtolower($s['kategori'] ?? '') !== 'motor');
    @endphp

    <div class="space-y-8 sm:space-y-12" id="public-services-wrapper">

        {{-- ============================================
             SEKSI 1: 🛵 LAYANAN SEPEDA MOTOR
        ============================================ --}}
        @if(count($motorServices) > 0)
            <div id="section-motor" data-section-vehicle="motor" class="space-y-4 sm:space-y-6 transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 p-3.5 sm:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-emerald-950/40 via-[#141416] to-[#0e0e10] border border-emerald-500/25 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="space-y-1 relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/35">
                                🛵 ZONA MOTOR
                            </span>
                            <span class="text-[11px] sm:text-xs text-gray-400 font-light">Bebek, Matic, Maxi &amp; Sport</span>
                        </div>
                        <h3 class="text-base sm:text-2xl font-black text-white tracking-wide">
                            Layanan Khusus Sepeda Motor
                        </h3>
                        <p class="text-[11px] sm:text-xs text-gray-300 max-w-2xl font-light leading-relaxed">
                            Opsi <strong class="text-emerald-400 font-semibold">Jasa Pasang Saja (Bawa Bahan)</strong> hemat per tipe motor, serta <strong class="text-[var(--accent)] font-semibold">Paket Full Wrap Oracal</strong> bergaransi resmi toko.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 relative z-10 self-start sm:self-auto">
                        <span class="text-[11px] sm:text-xs font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/25 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <i class="ph-bold ph-motorcycle text-xs sm:text-sm"></i>
                            <span>{{ count($motorServices) }} Paket</span>
                        </span>
                    </div>
                </div>

                {{-- Grid Motor Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 items-stretch">
                    @foreach($motorServices as $idx => $svc)
                        @include('landing.layanan._card_item', ['svc' => $svc, 'idx' => $idx])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============================================
             SEKSI 2: 🚗 LAYANAN MOBIL & MODIFIKASI
        ============================================ --}}
        @if(count($mobilServices) > 0)
            <div id="section-mobil" data-section-vehicle="mobil" class="space-y-4 sm:space-y-6 transition-all duration-300">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 p-3.5 sm:p-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-orange-950/40 via-[#141416] to-[#0e0e10] border border-[var(--accent)]/25 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-[var(--accent)]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="space-y-1 relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-wider bg-[var(--accent)]/20 text-[var(--accent)] border border-[var(--accent)]/35">
                                🚗 ZONA MOBIL &amp; VARIASI
                            </span>
                            <span class="text-[11px] sm:text-xs text-gray-400 font-light">City Car, Sedan, MPV, SUV</span>
                        </div>
                        <h3 class="text-base sm:text-2xl font-black text-white tracking-wide">
                            Layanan Khusus Mobil &amp; Modifikasi
                        </h3>
                        <p class="text-[11px] sm:text-xs text-gray-300 max-w-2xl font-light leading-relaxed">
                            Mulai dari <strong class="text-[var(--accent)] font-semibold">Full Body Wrap</strong>, <strong class="text-sky-400 font-semibold">Kap &amp; Atap Karbon 6D</strong>, jasa pasang panel, kaca film tolak panas, hingga audio/lighting.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 relative z-10 self-start sm:self-auto">
                        <span class="text-[11px] sm:text-xs font-bold text-[var(--accent)] bg-[var(--accent)]/10 border border-[var(--accent)]/25 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <i class="ph-bold ph-car-profile text-xs sm:text-sm"></i>
                            <span>{{ count($mobilServices) }} Paket</span>
                        </span>
                    </div>
                </div>

                {{-- Grid Mobil Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 items-stretch">
                    @foreach($mobilServices as $idx => $svc)
                        @include('landing.layanan._card_item', ['svc' => $svc, 'idx' => $idx])
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty State jika hasil filter/pencarian 0 --}}
        <div id="public-services-empty" class="hidden py-16 px-6 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40 w-full col-span-full">
            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
                <i class="ph-bold ph-magnifying-glass text-2xl text-[var(--accent)]"></i>
            </div>
            <h4 class="text-base font-bold text-white mb-1">Tidak ada layanan yang sesuai filter</h4>
            <p class="text-xs text-gray-400 font-light mb-4 max-w-md mx-auto">
                Tidak ditemukan paket yang cocok dengan kata kunci atau filter kendaraan yang dipilih. Silakan coba kata kunci lain.
            </p>
            <button type="button" onclick="resetPublicLayananFilters()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[var(--accent)] text-black font-extrabold text-xs shadow-lg hover:brightness-110 transition-all">
                <i class="ph-bold ph-arrow-counter-clockwise"></i>
                <span>Tampilkan Semua Layanan</span>
            </button>
        </div>

    </div>
@else
    <div class="py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40 w-full col-span-full">
        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
            <i class="ph-bold ph-package text-2xl text-[var(--accent)]"></i>
        </div>
        <h4 class="text-base font-bold text-white mb-1">{{ \App\Helpers\StaticContent::LAYANAN_EMPTY }}</h4>
        <p class="text-xs text-gray-500 font-light">Silakan periksa kembali nanti.</p>
    </div>
@endif

<x-register-prompt />

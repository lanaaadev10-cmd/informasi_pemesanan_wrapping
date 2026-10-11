@php
    $fiturArr = [];
    $compatibilityText = '';
    if (!empty($svc['fitur'])) {
        if (is_array($svc['fitur'])) {
            foreach ($svc['fitur'] as $item) {
                $text = is_array($item) ? ($item['nama_fitur'] ?? $item[0] ?? '') : $item;
                if (str_starts_with(strtolower($text), 'cocok:') || str_starts_with(strtolower($text), 'cocok untuk:')) {
                    $compatibilityText = $text;
                }
                $fiturArr[] = $text;
            }
        } elseif (is_string($svc['fitur'])) {
            $decoded = json_decode($svc['fitur'], true);
            $fiturArr = $decoded ?? [];
        }
    }

    $tp = strtolower($svc['tipe_paket'] ?? '');
    $nama = strtolower($svc['nama_layanan'] ?? $svc['nama'] ?? '');

    $isJasaPasang = $tp === 'jasa-pasang' || str_contains($nama, 'jasa pasang');
    $isWrapping   = ($tp === 'wrapping' || str_contains($nama, 'full wrap')) && !$isJasaPasang;
    $isPartial    = in_array($tp, ['partial', 'striping', 'custom']) || str_contains($nama, 'atap') || str_contains($nama, 'striping');
    $isAksesoris  = in_array($tp, ['lighting', 'audio', 'window-film', 'aksesoris']) || str_contains($nama, 'biled') || str_contains($nama, 'audio') || str_contains($nama, 'kaca film');

    if ($isJasaPasang) {
        $scopeKey   = 'jasa-pasang';
        $badge      = 'Hemat Jasa';
        $badgeBg    = 'rgba(16, 185, 129, 0.15)';
        $badgeClr   = '#34d399';
    } elseif ($isWrapping) {
        $scopeKey   = 'wrapping';
        $badge      = 'Garansi Resmi';
        $badgeBg    = 'rgba(242,153,74,0.15)';
        $badgeClr   = 'var(--accent)';
    } elseif ($isPartial) {
        $scopeKey   = 'partial';
        $badge      = 'Custom';
        $badgeBg    = 'rgba(168, 85, 247, 0.15)';
        $badgeClr   = '#c084fc';
    } else {
        $scopeKey   = 'aksesoris';
        $badge      = 'Aksesoris & Film';
        $badgeBg    = 'rgba(14, 165, 233, 0.15)';
        $badgeClr   = '#38bdf8';
    }

    $vehicleKey = strtolower($svc['kategori'] ?? 'mobil');

    $fotoPath = $svc['foto_contoh'] ?? $svc['gambar'] ?? null;
    $imgSrc = !empty($fotoPath)
        ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
        : ($fallbackImages[$idx % count($fallbackImages)]);
    $isFeatured = ($idx === 0 || $idx === 1);

    $svcSummary = isset($svc['id']) ? ($ratingSummary->get($svc['id']) ?? null) : null;
@endphp

<div class="public-service-card bg-gradient-to-b from-[#161616] to-[#0f0f0f] border border-white/[0.08] rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(242,153,74,0.18)] hover:border-[rgba(242,153,74,0.35)] relative"
     data-vehicle="{{ $vehicleKey }}"
     data-scope="{{ $scopeKey }}"
     data-keywords="{{ strtolower(($svc['nama_layanan'] ?? $svc['nama'] ?? '') . ' ' . ($svc['deskripsi'] ?? '') . ' ' . implode(' ', $fiturArr)) }}">

    <div>
        {{-- Foto Layanan --}}
        <div class="relative h-44 sm:h-56 overflow-hidden bg-gray-950 flex-shrink-0">
            <img src="{{ $imgSrc }}"
                 alt="{{ $svc['nama_layanan'] ?? $svc['nama'] ?? 'Layanan' }}"
                 class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] group-hover:scale-105"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-black/30 to-transparent"></div>
            
            {{-- Badges Kiri Atas: Kendaraan & Jasa Pasang/Paket/Aksesoris --}}
            <div class="absolute top-3.5 left-3.5 z-10 flex flex-wrap items-center gap-1.5">
                <span class="text-[0.6rem] sm:text-[0.65rem] font-black tracking-wider uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-black/85 text-white border border-white/20 backdrop-blur-md">
                    {{ $vehicleKey === 'motor' ? '🛵 Motor' : '🚗 Mobil' }}
                </span>

                @if($isJasaPasang)
                    <span class="text-[0.6rem] sm:text-[0.65rem] font-black tracking-wider uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-emerald-500/25 text-emerald-400 border border-emerald-500/40 backdrop-blur-md">
                        ✂️ Jasa Pasang
                    </span>
                @elseif($isWrapping)
                    <span class="text-[0.6rem] sm:text-[0.65rem] font-black tracking-wider uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-[var(--accent)]/25 text-[var(--accent)] border border-[var(--accent)]/40 backdrop-blur-md">
                        📦 Full Wrap
                    </span>
                @elseif($isPartial)
                    <span class="text-[0.6rem] sm:text-[0.65rem] font-black tracking-wider uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-purple-500/25 text-purple-300 border border-purple-500/40 backdrop-blur-md">
                        🎨 Custom
                    </span>
                @else
                    <span class="text-[0.6rem] sm:text-[0.65rem] font-black tracking-wider uppercase px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md bg-sky-500/25 text-sky-400 border border-sky-500/40 backdrop-blur-md">
                        💡 Aksesoris &amp; Film
                    </span>
                @endif
            </div>

            {{-- Badges Kanan Atas: Garansi / Popular --}}
            <div class="absolute top-3.5 right-3.5 z-10">
                <span class="text-[0.55rem] sm:text-[0.6rem] font-bold tracking-[0.1em] uppercase px-2.5 py-1 rounded-full backdrop-blur-md border shadow-sm"
                      style="background:{{ $badgeBg }};color:{{ $badgeClr }};border-color:{{ $badgeClr }}50">
                    {{ $badge }}
                </span>
            </div>

            {{-- Rating Bintang di Bagian Bawah Gambar --}}
            @if($svcSummary && $svcSummary['count'] > 0)
                <div class="absolute bottom-2.5 left-3 z-10 flex items-center gap-1.5 bg-black/75 backdrop-blur-md px-2 py-0.5 rounded-lg border border-white/10">
                    <span class="text-[var(--accent)] font-bold text-[11px] flex items-center gap-1">
                        <i class="ph-fill ph-star text-xs"></i> {{ number_format((float) $svcSummary['avg'], 1, ',', '.') }}
                    </span>
                    <span class="text-[9px] text-gray-400">({{ $svcSummary['count'] }})</span>
                </div>
            @endif
        </div>

        {{-- Detail Konten Layanan --}}
        <div class="p-4 sm:p-6 space-y-2.5 sm:space-y-3">
            {{-- Subtitle Tipe Layanan --}}
            <div>
                @if($isJasaPasang)
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-emerald-400 block mb-0.5">
                        ✂️ Bawa Bahan Sendiri • Bayar Jasa
                    </span>
                @elseif($isWrapping)
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-[var(--accent)] block mb-0.5">
                        ✨ Bahan Toko Oracal • Garansi
                    </span>
                @elseif($isPartial)
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-purple-400 block mb-0.5">
                        🎨 Desain &amp; Bagian Tertentu
                    </span>
                @else
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-sky-400 block mb-0.5">
                        💡 Upgrade Komponen &amp; Fitur
                    </span>
                @endif

                <h3 class="text-base sm:text-lg font-black text-white leading-tight">
                    {{ $svc['nama_layanan'] ?? $svc['nama'] ?? 'Layanan' }}
                </h3>
            </div>

            {{-- Box Callout Kompatibilitas Model Kendaraan --}}
            @if(!empty($compatibilityText))
                <div class="p-2 sm:p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/25 flex items-start gap-1.5 sm:gap-2">
                    <i class="ph-bold ph-check-square-offset text-[var(--accent)] text-xs shrink-0 mt-0.5"></i>
                    <div class="text-[10px] sm:text-[11px] font-medium text-amber-200/90 leading-tight">
                        {{ $compatibilityText }}
                    </div>
                </div>
            @endif

            {{-- Deskripsi --}}
            @if(!empty($svc['deskripsi']))
                <p class="text-gray-400 text-[11px] sm:text-xs leading-relaxed line-clamp-2 font-light">
                    {!! $svc['deskripsi'] !!}
                </p>
            @endif

            {{-- Fitur Unggulan --}}
            @if(!empty($fiturArr))
                <div class="pt-2.5 border-t border-white/[0.06] space-y-1">
                    @foreach(array_slice($fiturArr, 0, 3) as $item)
                        @if(!empty($item))
                            <div class="flex items-start gap-1.5 text-[10px] sm:text-[11px] text-gray-300">
                                <i class="ph-bold ph-check-circle text-[var(--accent)] text-xs shrink-0 mt-0.5"></i>
                                <span class="truncate">{{ $item }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Footer Harga & Tombol Booking --}}
    <div class="p-4 sm:p-6 pt-0">
        <div class="pt-2.5 border-t border-white/[0.08] flex items-center justify-between text-xs mb-2.5">
            <div>
                <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-wider block {{ $isJasaPasang ? 'text-emerald-400' : 'text-gray-500' }}">
                    {{ $isJasaPasang ? 'Ongkos Tenaga:' : 'Tarif Paket:' }}
                </span>
                <span class="text-sm sm:text-base font-black text-[var(--accent)]">
                    {{ $svc['harga'] }}
                </span>
            </div>

            @if(!empty($svc['estimasi']))
                <div class="text-right">
                    <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-wider text-gray-500 block">Estimasi</span>
                    <span class="text-gray-300 font-light flex items-center justify-end gap-1 text-[10px] sm:text-[11px]">
                        <i class="ph-bold ph-clock text-[var(--accent)]"></i>
                        <span>{{ $svc['estimasi'] }}</span>
                    </span>
                </div>
            @endif
        </div>

        @php
            $isCustomItem = in_array($tp, ['custom']) || str_contains(strtolower($svc['nama_layanan'] ?? $svc['nama'] ?? ''), 'custom');
            $rawWaCard = !empty($profil->nomor_telepon) ? preg_replace('/[^0-9]/', '', $profil->nomor_telepon) : '6283117163605';
            if (str_starts_with($rawWaCard, '0')) { $rawWaCard = '62' . substr($rawWaCard, 1); }
        @endphp

        @if($isCustomItem)
            <a href="https://wa.me/{{ $rawWaCard }}?text={{ urlencode('Halo Dantie Stiker, saya ingin konsultasi & negosiasi harga untuk custom ' . ($svc['nama_layanan'] ?? $svc['nama'] ?? 'Layanan') . '...') }}"
               target="_blank" rel="noopener noreferrer"
               class="block text-center font-extrabold text-[0.7rem] sm:text-[0.75rem] tracking-[0.06em] uppercase py-2.5 sm:py-3 px-3 sm:px-4 rounded-xl transition-all duration-200 hover:brightness-110 active:scale-95 shadow-md flex items-center justify-center gap-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-black">
                <i class="ph-bold ph-whatsapp-logo text-xs"></i>
                <span>Negosiasi via WhatsApp</span>
            </a>
        @else
            @auth
                <a href="{{ route('booking.create', ['layanan_id' => $svc['id'] ?? null]) }}"
                   class="block text-center font-extrabold text-[0.7rem] sm:text-[0.75rem] tracking-[0.06em] uppercase py-2.5 sm:py-3 px-3 sm:px-4 rounded-xl transition-all duration-200 hover:brightness-110 active:scale-95 shadow-md flex items-center justify-center gap-1.5"
                   style="background:var(--accent);color:#0a0a0a">
                    <i class="ph-bold ph-calendar-plus text-xs"></i>
                    <span>Jadwalkan Booking</span>
                </a>
            @else
                <button type="button" onclick="showRegisterPrompt()"
                        class="w-full text-center font-extrabold text-[0.7rem] sm:text-[0.75rem] tracking-[0.06em] uppercase py-2.5 sm:py-3 px-3 sm:px-4 rounded-xl transition-all duration-200 hover:brightness-110 active:scale-95 shadow-md flex items-center justify-center gap-1.5"
                        style="background:var(--accent);color:#0a0a0a">
                    <i class="ph-bold ph-calendar-plus text-xs"></i>
                    <span>Booking Layanan Ini</span>
                </button>
            @endauth
        @endif
    </div>

</div>

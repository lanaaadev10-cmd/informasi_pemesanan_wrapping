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
    } elseif ($isWrapping) {
        $scopeKey   = 'wrapping';
        $badge      = 'Garansi Resmi';
    } elseif ($isPartial) {
        $scopeKey   = 'partial';
        $badge      = 'Custom';
    } else {
        $scopeKey   = 'aksesoris';
        $badge      = 'Aksesoris & Film';
    }

    $vehicleKey = strtolower($svc['kategori'] ?? 'mobil');

    $fotoPath = $svc['foto_contoh'] ?? $svc['gambar'] ?? null;
    $imgSrc = !empty($fotoPath)
        ? \App\Helpers\StaticContent::fotoUrl($fotoPath)
        : ($fallbackImages[$idx % count($fallbackImages)]);

    $svcSummary = isset($svc['id']) ? ($ratingSummary->get($svc['id']) ?? null) : null;
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     KARTU LAYANAN (Mengadaptasi Card Services dari Gambar Referensi)
     Struktur Bersih: Image Box Rounded di Atas + Typography Rapi di Bawah
═══════════════════════════════════════════════════════════════════ --}}
<div class="public-service-card bg-white border border-slate-200/90 rounded-2xl overflow-hidden flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-slate-400/80 shadow-sm relative group"
     data-vehicle="{{ $vehicleKey }}"
     data-scope="{{ $scopeKey }}"
     data-keywords="{{ strtolower(($svc['nama_layanan'] ?? $svc['nama'] ?? '') . ' ' . ($svc['deskripsi'] ?? '') . ' ' . implode(' ', $fiturArr)) }}">

    <div>
        {{-- Foto Layanan (Aspect Box Sesuai Referensi - Clean, Tanpa Badge Overlay yang Menutupi Foto) --}}
        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100 flex-shrink-0 group/img">
            <img src="{{ $imgSrc }}"
                 alt="{{ $svc['nama_layanan'] ?? $svc['nama'] ?? 'Layanan' }}"
                 class="w-full h-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-105"
                 loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 via-transparent to-transparent"></div>
        </div>

        {{-- Detail Konten Layanan --}}
        <div class="p-4 sm:p-5 space-y-2.5">
            {{-- Subtitle Kategori & Rating Minimalis (Clean Text, No Overlay Badges) --}}
            <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
                <span>{{ $vehicleKey === 'motor' ? '🛵 Motor' : '🚗 Mobil' }} &bull; {{ $isJasaPasang ? 'Jasa Pasang Stiker' : ($isWrapping ? 'Full Wrap' : ($isPartial ? 'Custom' : 'Aksesoris')) }}</span>
                @if($svcSummary && $svcSummary['count'] > 0)
                    <span class="flex items-center gap-1 text-amber-500 font-bold text-[11px] normal-case">
                        <i class="ph-fill ph-star text-xs"></i>
                        <span>{{ number_format((float) $svcSummary['avg'], 1, ',', '.') }}</span>
                    </span>
                @endif
            </div>

            {{-- Nama Layanan Sesuai Typography Referensi --}}
            <div>
                <h3 class="text-sm sm:text-base font-black text-slate-900 group-hover:text-[#ea580c] transition-colors leading-snug">
                    {{ $svc['nama_layanan'] ?? $svc['nama'] ?? 'Layanan' }}
                </h3>
            </div>

            {{-- Box Callout Kompatibilitas Model Kendaraan (jika ada) --}}
            @if(!empty($compatibilityText))
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-1.5">
                    <i class="ph-bold ph-check-square-offset text-[#ea580c] text-xs shrink-0 mt-0.5"></i>
                    <div class="text-[10px] font-medium text-slate-700 leading-tight">
                        {{ $compatibilityText }}
                    </div>
                </div>
            @endif

            {{-- Deskripsi --}}
            @if(!empty($svc['deskripsi']))
                <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 font-normal">
                    {!! strip_tags($svc['deskripsi']) !!}
                </p>
            @endif

            {{-- Fitur Unggulan Singkat --}}
            @if(!empty($fiturArr))
                <div class="pt-2 border-t border-slate-100 space-y-1">
                    @foreach(array_slice($fiturArr, 0, 2) as $item)
                        @if(!empty($item) && !str_starts_with(strtolower($item), 'cocok:'))
                            <div class="flex items-start gap-1.5 text-[10px] sm:text-[11px] text-slate-600">
                                <i class="ph-bold ph-check text-emerald-600 text-xs shrink-0 mt-0.5"></i>
                                <span class="truncate">{{ $item }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Footer Harga & Tombol Aksi --}}
    <div class="p-4 sm:p-5 pt-0">
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs mb-3">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-wider block {{ $isJasaPasang ? 'text-emerald-700' : 'text-slate-400' }}">
                    {{ $isJasaPasang ? 'Ongkos Tenaga:' : 'Tarif Paket:' }}
                </span>
                <span class="text-sm sm:text-base font-black text-slate-900">
                    {{ $svc['harga'] }}
                </span>
            </div>

            @if(!empty($svc['estimasi']))
                <div class="text-right">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Estimasi</span>
                    <span class="text-slate-600 font-semibold flex items-center justify-end gap-1 text-[10px]">
                        <i class="ph-bold ph-clock text-[#ea580c]"></i>
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
               class="w-full text-center font-montserrat font-medium text-xs uppercase py-3 px-4 rounded-full transition-all bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-sm flex items-center justify-center gap-1.5 text-white">
                <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                <span>Negosiasi via WhatsApp</span>
            </a>
        @else
            @auth
                <a href="{{ route('booking.create', ['layanan_id' => $svc['id'] ?? null]) }}"
                   class="w-full text-center font-montserrat font-medium text-xs uppercase py-3 px-4 rounded-full transition-all bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 active:scale-95 shadow-md shadow-orange-500/20 flex items-center justify-center gap-1.5 text-white">
                    <i class="ph-bold ph-calendar-plus text-sm"></i>
                    <span>Jadwalkan Booking</span>
                </a>
            @else
                <button type="button" onclick="showRegisterPrompt()"
                        class="w-full text-center font-montserrat font-medium text-xs uppercase py-3 px-4 rounded-full transition-all bg-gradient-to-r from-[#ea580c] to-[#f97316] hover:brightness-105 active:scale-95 shadow-md shadow-orange-500/20 flex items-center justify-center gap-1.5 text-white cursor-pointer">
                    <i class="ph-bold ph-calendar-plus text-sm"></i>
                    <span>Booking Layanan Ini</span>
                </button>
            @endauth
        @endif
    </div>

</div>

{{-- ============================================
    BAGIAN: Service Cards Grid
    Deskripsi: Grid berisi daftar paket layanan yang dinamis dari DB/fallback
============================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 items-stretch">
    @foreach($services as $idx => $svc)
        @php
            $fiturArr = [];
            if (!empty($svc['fitur'])) {
                if (is_array($svc['fitur'])) {
                    foreach ($svc['fitur'] as $item) {
                        if (is_array($item)) {
                            $fiturArr[] = $item['nama_fitur'] ?? $item[0] ?? '';
                        } else {
                            $fiturArr[] = $item;
                        }
                    }
                } elseif (is_string($svc['fitur'])) {
                    $decoded = json_decode($svc['fitur'], true);
                    $fiturArr = $decoded ?? [];
                }
            }

            $imgSrc = !empty($svc['gambar'])
                ? asset('storage/' . $svc['gambar'])
                : ($fallbackImages[$idx % count($fallbackImages)]);

            $badge       = $badgeLabels[$idx % count($badgeLabels)] ?? '';
            $badgeBg     = $badgeColors[$idx % count($badgeColors)];
            $badgeClr    = $badgeTextColors[$idx % count($badgeTextColors)];
            $isFeatured  = ($idx === 1); // Best Seller card
        @endphp
        <div class="svc-card{{ $isFeatured ? ' ring-1 ring-[var(--accent)]/20' : '' }}">

            {{-- ── Car Image ── --}}
            <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0">
                <img src="{{ $imgSrc }}"
                     alt="{{ $svc['nama'] }}"
                     class="svc-card-img w-full h-full object-cover object-center"
                     loading="lazy">
                {{-- Gradient overlay --}}
                <div class="absolute inset-0" style="background: linear-gradient(to top, #0f0f0f 0%, transparent 60%);"></div>

                {{-- Badge --}}
                <div class="absolute top-4 left-4 z-10">
                    <span class="svc-badge" style="background: {{ $badgeBg }}; color: {{ $badgeClr }};">
                        {{ $badge }}
                    </span>
                </div>
            </div>

            {{-- ── Card Body ── --}}
            <div class="flex flex-col flex-1 p-5 sm:p-6 gap-3">

                {{-- Name --}}
                <h3 class="text-lg font-extrabold text-white leading-tight">
                    {{ $svc['nama'] }}
                </h3>

                {{-- Price --}}
                @if(!empty($svc['harga']))
                    <div class="flex items-baseline gap-1">
                        <span class="text-base font-black layanan-accent">{{ $svc['harga'] }}</span>
                        <span class="text-xs text-gray-500 font-medium">/unit</span>
                    </div>
                @endif

                {{-- Description --}}
                @if(!empty($svc['deskripsi']))
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-3">
                        {{ $svc['deskripsi'] }}
                    </p>
                @endif

                {{-- Separator --}}
                <div class="border-t border-white/[0.06] my-1"></div>

                {{-- Features --}}
                @if(!empty($fiturArr))
                    <ul class="space-y-2.5 flex-1">
                        @foreach($fiturArr as $item)
                            @if(!empty($item))
                                <li class="svc-feat">
                                    <span class="svc-feat-dot"></span>
                                    {{ $item }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="flex-1"></div>
                @endif

                {{-- CTA Button --}}
                <a href="{{ route('katalog.user') }}" class="svc-btn mt-2">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    @endforeach
</div>

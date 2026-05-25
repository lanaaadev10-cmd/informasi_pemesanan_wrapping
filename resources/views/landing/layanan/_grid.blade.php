{{-- ============================================
    BAGIAN: Service Cards Grid
    Deskripsi: Grid berisi daftar paket layanan yang dinamis dari DB/fallback
============================================ --}}
@if(count($services) > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
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
        <div class="bg-gradient-to-b from-[#161616] to-[#0f0f0f] border border-white/[0.06] rounded-2xl overflow-hidden flex flex-col transition-all duration-[400ms] ease-[cubic-bezier(.22,.61,.36,1)] hover:-translate-y-1.5 hover:shadow-[0_20px_50px_-12px_rgba(242,153,74,0.15)] hover:border-[rgba(242,153,74,0.25)]{{ $isFeatured ? ' ring-1 ring-[var(--accent)]/20' : '' }}">

            <div class="relative h-48 sm:h-52 overflow-hidden bg-gray-950 flex-shrink-0">
                <img src="{{ $imgSrc }}"
                     alt="{{ $svc['nama'] }}"
                     class="w-full h-full object-cover object-center transition-transform duration-[600ms] ease-[cubic-bezier(.22,.61,.36,1)] group-hover:scale-106"
                     loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] to-transparent"></div>
                <div class="absolute top-4 right-4 z-10">
                    <span class="text-[0.6rem] font-bold tracking-[0.12em] uppercase px-3 py-1.5 rounded-full backdrop-blur-sm border"
                          style="background:{{ $badgeBg }};color:{{ $badgeClr }};border-color:rgba(242,153,74,0.25)">
                        {{ $badge }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col flex-1 p-5 sm:p-6 gap-4">
                <h3 class="text-lg font-extrabold text-white leading-tight">
                    {{ $svc['nama'] }}
                </h3>

                @if(!empty($svc['harga']))
                    <div class="flex items-baseline gap-1">
                        <span class="text-base font-black text-[var(--accent)]">{{ $svc['harga'] }}</span>
                        <span class="text-xs text-gray-500 font-medium">/unit</span>
                    </div>
                @endif

                @if(!empty($svc['deskripsi']))
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-3">
                        {{ $svc['deskripsi'] }}
                    </p>
                @endif

                <div class="border-t border-white/[0.06] my-1"></div>

                @if(!empty($fiturArr))
                    <ul class="space-y-2.5 flex-1">
                        @foreach($fiturArr as $item)
                            @if(!empty($item))
                                <li class="flex items-start gap-2.5 text-[0.78rem] text-white/55">
                                    <svg class="w-3.5 h-3.5 text-[var(--accent)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <div class="flex-1"></div>
                @endif

                <a href="{{ route('katalog.user') }}" class="block text-center font-extrabold text-[0.72rem] tracking-[0.1em] uppercase py-3.5 px-5 rounded-xl transition-opacity duration-200 hover:opacity-88 hover:scale-102 mt-2"
                   style="background:var(--accent);color:#0a0a0a">
                    Pesan Sekarang
                </a>
            </div>
        </div>
    @endforeach
</div>
@else
<div class="py-16 text-center border border-dashed border-white/10 rounded-[32px] bg-[#121212]/40 w-full col-span-full" data-aos="fade-up" data-aos-duration="800">
    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-gray-500 mx-auto mb-4">
        <i class="ph-bold ph-package text-2xl text-[var(--accent)]"></i>
    </div>
    <h4 class="text-base font-bold text-white mb-1">admin belum menambahkan</h4>
    <p class="text-xs text-gray-500 font-light">Admin belum menambahkan paket layanan saat ini.</p>
</div>
@endif

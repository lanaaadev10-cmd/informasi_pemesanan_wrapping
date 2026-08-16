<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
    <div class="lg:col-span-3 space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-white mb-3 italic">
                {{ \App\Helpers\StaticContent::LAYANAN_MENGAPA_TITLE }}
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed max-w-lg">
                {{ \App\Helpers\StaticContent::LAYANAN_MENGAPA_DESC }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-2 text-xs sm:text-sm font-semibold text-gray-300">
            @php
                use App\Helpers\StaticContent;
                $benefitTags = [
                    ['icon' => '🔧', 'text' => StaticContent::LAYANAN_BENEFIT_1],
                    ['icon' => '🏠', 'text' => StaticContent::LAYANAN_BENEFIT_2],
                    ['icon' => '✅', 'text' => StaticContent::LAYANAN_BENEFIT_3],
                ];
            @endphp
            @foreach($benefitTags as $tag)
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--accent)]"></span>
                    <span>{{ $tag['text'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-gradient-to-br from-[rgba(242,153,74,0.08)] to-[#111111] border border-[rgba(242,153,74,0.2)] rounded-3xl p-8 sm:p-10 flex flex-col items-center justify-center text-center gap-4 h-full">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-1 border"
                 style="background:rgba(242,153,74,0.12);border-color:rgba(242,153,74,0.25)">
                <svg class="w-7 h-7 text-[var(--accent)]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                </svg>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-2">
                    {{ \App\Helpers\StaticContent::LAYANAN_GARANSI_TITLE }}
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    {{ \App\Helpers\StaticContent::LAYANAN_GARANSI_DESC }}
                </p>
            </div>
        </div>
    </div>
</div>

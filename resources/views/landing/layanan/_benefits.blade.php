{{-- ============================================
    BAGIAN: Mengapa Memilih Kami & Garansi
    Deskripsi: Grid penjelasan benefit kualitas dan garansi resmi
============================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">

    {{-- LEFT: Mengapa Memilih Kami? (3 cols) --}}
    <div class="lg:col-span-3 space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-white mb-3" style="font-style: italic;">
                Mengapa Memilih Kami?
            </h2>
            <p class="text-gray-400 text-sm leading-relaxed max-w-lg">
                Kami menggunakan keahlian teknis dengan material terbaik dunia untuk memastikan aset Anda terlindung sempurna. Setiap pengerjaan dilakukan di ruangan steril dengan kontrol suhu untuk hasil yang maksimal tanpa gelembung udara.
            </p>
        </div>

        {{-- Benefit Tags --}}
        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-2 text-xs sm:text-sm font-semibold text-gray-300">
            @php
                $benefitTags = [
                    ['icon' => '🔧', 'text' => 'Instalatur Bersertifikat'],
                    ['icon' => '🏠', 'text' => 'Ruangan Steril'],
                    ['icon' => '✅', 'text' => 'Quality Control 3 Lapis'],
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

    {{-- RIGHT: Garansi Resmi Card (2 cols) --}}
    <div class="lg:col-span-2">
        <div class="garansi-card h-full flex flex-col items-center justify-center text-center p-8 sm:p-10 gap-4">
            {{-- Shield Icon --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-1"
                 style="background: rgba(242,153,74,0.12); border: 1px solid rgba(242,153,74,0.25);">
                <svg class="w-7 h-7 layanan-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                </svg>
            </div>

            <div>
                <h3 class="text-xl font-black text-white mb-2">
                    {{ $profil?->layanan_garansi_title ?? 'Garansi Resmi' }}
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-sm">
                    {!! $profil?->layanan_garansi_desc ?? 'Hingga 5 tahun perlindungan terhadap gelembung, pengelupasan, dan kerusakan perekatan.' !!}
                </p>
            </div>
        </div>
    </div>
</div>

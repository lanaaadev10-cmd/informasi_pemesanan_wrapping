@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Tentang Kami')

@section('content')
    @if(!auth()->check())
        <!-- Spacer untuk Public View -->
        <div class="h-28"></div>
    @endif

    @php
        // Setup CSS variables untuk dynamic color
        $accentColor = $profil->accent_color ?? '#f2994a';
        $valuesColumns = $profil->tentang_kami_values_columns ?? 3;
        $showValues = $profil->tentang_kami_show_values ?? true;
        $showHistory = $profil->tentang_kami_show_history ?? true;
        $showTeam = $profil->tentang_kami_show_team ?? true;
    @endphp

    <style>
        :root {
            --accent-color: {{ $accentColor }};
        }
        .accent-color { color: var(--accent-color); }
        .accent-bg { background-color: var(--accent-color); }
        .accent-border { border-color: var(--accent-color); }
        .accent-hover:hover { border-color: color-mix(in srgb, var(--accent-color) 25%, transparent); }
    </style>

    <!-- Container Utama -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12 space-y-16 relative overflow-hidden">

        <!-- Ambient Glowing Core -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

        <!-- HERO HEADER SECTION - CENTERED -->
        <div class="text-center space-y-4 z-10 relative">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
                {{ $profil->tentang_kami_hero_title ?? 'Tentang Kami' }}
            </h1>
            @if($profil?->tentang_kami_hero_desc)
                <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                    {!! $profil->tentang_kami_hero_desc !!}
                </p>
            @endif
        </div>

        <!-- VISI & MISI SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 z-10 relative">
            <!-- VISI -->
            <div class="bg-white/[0.01] border border-white/5 p-8 sm:p-10 rounded-2xl accent-hover transition-all duration-300 group">
                <div class="space-y-4">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl transition-all duration-300" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white">Visi Kami</h3>

                    <div class="text-gray-300 text-base leading-relaxed prose prose-invert max-w-none">
                        @if($profil?->visi)
                            {!! $profil->visi !!}
                        @else
                            <p>Menjadi studio wrapping terdepan yang dikenal dengan kualitas premium dan inovasi berkelanjutan dalam industri.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MISI -->
            <div class="bg-white/[0.01] border border-white/5 p-8 sm:p-10 rounded-2xl accent-hover transition-all duration-300 group">
                <div class="space-y-4">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl transition-all duration-300" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white">Misi Kami</h3>

                    <div class="text-gray-300 text-base leading-relaxed prose prose-invert max-w-none">
                        @if($profil?->misi)
                            {!! $profil->misi !!}
                        @else
                            <p>Memberikan solusi wrapping berkualitas tinggi dengan harga kompetitif dan layanan pelanggan yang luar biasa.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SEJARAH SECTION - 2 COLUMN LAYOUT - CONDITIONAL DISPLAY -->
        @if($showHistory && $profil?->sejarah)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 z-10 relative items-center">
                <!-- LEFT: Judul Only -->
                <div class="space-y-6">
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Satu Dekade Dedikasi pada Perfeksi
                    </h3>
                </div>

                <!-- RIGHT: Gradient Placeholder -->
                <div class="h-96 rounded-[32px] overflow-hidden shadow-2xl" style="background: linear-gradient(to bottom right, color-mix(in srgb, var(--accent-color) 30%, transparent), color-mix(in srgb, var(--accent-color) 10%, transparent)); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-24 h-24 opacity-50 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TEAM SECTION - CONDITIONAL DISPLAY -->
        @if($showTeam && $profil?->tentang_kami_team_title)
            <div class="space-y-8 z-10 relative">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $profil->tentang_kami_team_title }}
                    </h2>
                    @if($profil?->tentang_kami_team_desc)
                        <p class="text-gray-400 text-sm sm:text-base max-w-2xl mx-auto">
                            {!! $profil->tentang_kami_team_desc !!}
                        </p>
                    @endif
                </div>

                <!-- Team Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($profil->tentang_kami_team_members ?? [] as $member)
                        <div class="bg-white/[0.01] border border-white/5 p-6 rounded-[24px] accent-hover transition-all duration-300 group overflow-hidden text-center shadow-lg">
                            <!-- Image Container -->
                            <div class="relative rounded-[16px] overflow-hidden h-48 mb-4">
                                @if(isset($member['foto']) && $member['foto'])
                                    <img src="{{ asset('storage/' . $member['foto']) }}"
                                         alt="{{ $member['nama'] ?? 'Team Member' }}"
                                         class="w-full h-full object-cover transform scale-100 group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(to bottom right, color-mix(in srgb, var(--accent-color) 30%, transparent), color-mix(in srgb, var(--accent-color) 10%, transparent));">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: color-mix(in srgb, var(--accent-color) 50%, transparent);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Overlay Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>

                            <!-- Info -->
                            <h3 class="text-lg font-bold text-white">{{ $member['nama'] ?? 'Team Member' }}</h3>
                            <p class="text-sm font-medium mt-1 accent-color">
                                {{ $member['posisi'] ?? 'Professional' }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-400">
                            <p>Tim akan segera ditampilkan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- VALUES/KOMITMEN SECTION - CONDITIONAL DISPLAY WITH DYNAMIC COLUMNS -->
        @if($showValues)
            <div class="space-y-8 z-10 relative">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                        Komitmen Kami
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base">
                        Nilai-nilai yang menjadi fondasi layanan kami
                    </p>
                </div>

                <div class="@if($valuesColumns == 3) grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 @else grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 @endif grid gap-6">
                    <!-- Value 1: Kualitas Premium -->
                    <div class="bg-white/[0.01] border border-white/5 p-6 rounded-[24px] accent-hover transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 pointer-events-none" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

                        <div class="relative z-10">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl mb-4 transition-all duration-300 group-hover:text-black" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-white mb-2">Kualitas Premium</h3>
                            <p class="text-gray-400 text-sm">Hanya menggunakan material terbaik dari brand ternama dunia.</p>
                        </div>
                    </div>

                    <!-- Value 2: Keahlian Profesional -->
                    <div class="bg-white/[0.01] border border-white/5 p-6 rounded-[24px] accent-hover transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 pointer-events-none" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

                        <div class="relative z-10">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl mb-4 transition-all duration-300 group-hover:text-black" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-white mb-2">Keahlian Profesional</h3>
                            <p class="text-gray-400 text-sm">Tim bersertifikat dengan pengalaman puluhan tahun di industri.</p>
                        </div>
                    </div>

                    <!-- Value 3: Harga Kompetitif -->
                    <div class="bg-white/[0.01] border border-white/5 p-6 rounded-[24px] accent-hover transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 pointer-events-none" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

                        <div class="relative z-10">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl mb-4 transition-all duration-300 group-hover:text-black" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-white mb-2">Harga Kompetitif</h3>
                            <p class="text-gray-400 text-sm">Nilai terbaik untuk investasi Anda tanpa mengorbankan kualitas.</p>
                        </div>
                    </div>

                    <!-- Value 4: Kepuasan Pelanggan - HANYA JIKA 4 KOLOM -->
                    @if($valuesColumns == 4)
                        <div class="bg-white/[0.01] border border-white/5 p-6 rounded-[24px] accent-hover transition-all duration-300 group relative overflow-hidden shadow-lg">
                            <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 pointer-events-none" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

                            <div class="relative z-10">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl mb-4 transition-all duration-300 group-hover:text-black" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-white mb-2">Kepuasan Pelanggan</h3>
                                <p class="text-gray-400 text-sm">Kepuasan Anda adalah prioritas utama dan kesuksesan kami.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- CTA SECTION -->
        <div class="rounded-[32px] p-8 sm:p-10 lg:p-12 text-center space-y-6 z-10 relative shadow-lg transition-all duration-300" style="background: linear-gradient(to right, color-mix(in srgb, var(--accent-color) 10%, transparent), color-mix(in srgb, var(--accent-color) 5%, transparent), transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
            <h3 class="text-2xl sm:text-3xl font-bold text-white">
                Siap Bertransformasi?
            </h3>
            <p class="text-gray-300 max-w-2xl mx-auto text-sm sm:text-base">
                Hubungi kami hari ini dan konsultasikan kebutuhan wrapping kendaraan Anda dengan tim profesional kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('katalog.user') }}" class="inline-flex items-center justify-center px-8 py-3 text-black font-bold rounded-full hover:opacity-90 transition-all duration-300 shadow-lg group" style="background-color: var(--accent-color); box-shadow: 0 4px 20px color-mix(in srgb, var(--accent-color) 30%, transparent);">
                    <span>Lihat Katalog Layanan</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" target="_blank" class="inline-flex items-center justify-center px-8 py-3 font-bold rounded-full transition-all duration-300" style="border: 2px solid var(--accent-color); color: var(--accent-color); background-color: transparent;">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-3.055 2.11-3.653 6.02-1.319 8.93 2.176 2.771 6.133 3.637 9.028 1.783 1.669-.96 2.636-2.461 2.973-4.43.2-1.122-.042-2.26-.614-3.2L8.622 9.897m5.768.45a1.007 1.007 0 10-2.012-.001 1.007 1.007 0 002.012 0z"/>
                    </svg>
                    <span>Hubungi WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
@endsection

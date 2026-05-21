@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Layanan')

@section('content')
    @if(!auth()->check())
        <!-- Spacer untuk Public View -->
        <div class="h-28"></div>
    @endif

    @php
        // Setup CSS variables untuk dynamic color & config
        $accentColor = $profil->accent_color ?? '#f2994a';
        $gridColumns = $profil->layanan_grid_columns ?? 4;
        $cardStyle = $profil->layanan_card_style ?? 'standard';
        $showBenefits = $profil->layanan_show_benefits ?? true;
        $showWarranty = $profil->layanan_show_warranty ?? true;
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

        <!-- HERO HEADER SECTION - CENTERED WITH PREMIUM STYLING -->
        <div class="text-center space-y-6 z-10 relative">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-2" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: var(--accent-color);"></span>
                <span class="text-xs font-bold tracking-widest uppercase" style="color: var(--accent-color);">Paket Layanan Premium</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                {{ $profil->layanan_hero_title ?? 'Precision in Every Layer' }}
            </h1>
            @if($profil?->layanan_hero_desc)
                <p class="text-gray-300 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                    {!! $profil->layanan_hero_desc !!}
                </p>
            @endif
        </div>

        <!-- SERVICES GRID - DYNAMIC COLUMNS & EQUAL HEIGHT CARDS -->
        <div class="@if($gridColumns == 3) grid-cols-1 md:grid-cols-2 lg:grid-cols-3 @else grid-cols-1 md:grid-cols-2 lg:grid-cols-4 @endif grid gap-6 z-10 relative">
            @php
                $services = [
                    ['key' => 'layanan_1'],
                    ['key' => 'layanan_2'],
                    ['key' => 'layanan_3'],
                    ['key' => 'layanan_4'],
                ];
            @endphp

            @foreach($services as $service)
                @php
                    $serviceKey = $service['key'];
                    $namaKey = "{$serviceKey}_nama";
                    $hargaKey = "{$serviceKey}_harga";
                    $deskKey = "{$serviceKey}_deskripsi";
                    $fiturKey = "{$serviceKey}_fitur";
                    $gambarKey = "{$serviceKey}_gambar";

                    $nama = $profil->$namaKey;
                    $harga = $profil->$hargaKey;
                    $deskripsi = $profil->$deskKey;
                    $fitur = $profil->$fiturKey ?? [];
                    $gambar = $profil->$gambarKey;
                @endphp

                @if($nama)
                    <div class="bg-gradient-to-b from-white/[0.05] to-white/[0.01] border border-white/10 rounded-3xl overflow-hidden group hover:border-white/20 transition-all duration-300 flex flex-col h-full shadow-xl hover:shadow-2xl">
                        <!-- Image Section -->
                        <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-900 to-black">
                            @if($gambar)
                                <img src="{{ asset('storage/' . $gambar) }}"
                                     alt="{{ $nama }}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, color-mix(in srgb, var(--accent-color) 25%, transparent), color-mix(in srgb, var(--accent-color) 5%, transparent));">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: color-mix(in srgb, var(--accent-color) 25%, transparent);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content Section - FLEX COLUMN FOR EQUAL HEIGHT -->
                        <div class="p-7 flex-1 flex flex-col">
                            <!-- Title & Price -->
                            <div class="mb-4 flex-shrink-0">
                                <h3 class="text-2xl font-extrabold text-white mb-2 leading-tight">
                                    {{ $nama }}
                                </h3>
                                <div class="text-xl font-black accent-color tracking-wide">
                                    {{ $harga }}
                                </div>
                            </div>

                            <!-- Description -->
                            @if($deskripsi)
                                <p class="text-gray-400 text-sm mb-5 leading-relaxed flex-shrink-0">
                                    {{ $deskripsi }}
                                </p>
                            @endif

                            <!-- Features with Icons - FLEX GROW -->
                            @if($fitur && count($fitur) > 0)
                                <div class="space-y-3 mb-7 flex-1">
                                    @foreach($fitur as $item)
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="flex items-center justify-center h-5 w-5 rounded-full transition-all duration-300" style="border: 1.5px solid var(--accent-color); background-color: color-mix(in srgb, var(--accent-color) 15%, transparent);">
                                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20" style="color: var(--accent-color);">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <span class="text-gray-300 text-sm font-medium">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- CTA Button - ALWAYS AT BOTTOM -->
                            <a href="{{ route('katalog.user') }}" class="inline-flex items-center justify-center w-full px-4 py-3 text-black font-extrabold rounded-2xl transition-all duration-300 text-xs uppercase tracking-widest shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95" style="background-color: var(--accent-color);">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- MENGAPA MEMILIH KAMI SECTION - CONDITIONAL DISPLAY -->
        @if($showBenefits)
            <div class="space-y-8 z-10 relative">
                <div class="text-center">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                        Mengapa Memilih Kami?
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="bg-white/[0.02] border border-white/5 p-8 rounded-2xl text-center group accent-hover transition-all duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 transition-all duration-300" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-3">Instalasi Rapi Profesional</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Teknisi bersertifikat dengan pengalaman puluhan tahun menggaransi hasil sempurna.
                        </p>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white/[0.02] border border-white/5 p-8 rounded-2xl text-center group accent-hover transition-all duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 transition-all duration-300" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-3">Harga Kompetitif</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Nilai terbaik untuk investasi Anda dengan transparansi harga penuh tanpa biaya tersembunyi.
                        </p>
                    </div>

                    <!-- Benefit 3 -->
                    <div class="bg-white/[0.02] border border-white/5 p-8 rounded-2xl text-center group accent-hover transition-all duration-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 transition-all duration-300" style="background-color: color-mix(in srgb, var(--accent-color) 10%, transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-3">Quality Control 3 Lapis</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Setiap pekerjaan melalui inspeksi ketat untuk memastikan kepuasan Anda 100%.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- GARANSI SECTION - CONDITIONAL DISPLAY -->
        @if($showWarranty && $profil?->layanan_garansi_title)
            <div class="rounded-[32px] p-8 sm:p-10 space-y-4 z-10 relative shadow-lg transition-all duration-300" style="background: linear-gradient(to right, color-mix(in srgb, var(--accent-color) 10%, transparent), transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                <h3 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" style="color: var(--accent-color);">
                        <path d="M3.505 2.365A41.622 41.622 0 019 2c5.318 0 8.291 3.253 8.291 5.79 0 1.054-.747 2.213-2.051 2.795.081.119.165.239.252.358 1.265 1.681 2.317 3.637 2.317 5.857 0 2.511-.951 4.941-2.654 6.74-1.663 1.749-4.037 2.986-6.855 2.986-2.817 0-5.192-1.237-6.855-2.986C1.951 15.941 1 13.512 1 11 1 9.159 1.97 7.571 3.505 6.56M9 5a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    {{ $profil->layanan_garansi_title }}
                </h3>
                <p class="text-gray-300">
                    {!! $profil->layanan_garansi_desc ?? 'Semua layanan kami dilindungi dengan garansi resmi dan dukungan pelanggan terbaik.' !!}
                </p>
            </div>
        @endif

        <!-- CTA SECTION -->
        @if($profil?->layanan_cta_title)
            <div class="rounded-[32px] p-8 sm:p-10 lg:p-12 text-center space-y-6 z-10 relative shadow-lg transition-all duration-300" style="background: linear-gradient(to right, color-mix(in srgb, var(--accent-color) 10%, transparent), color-mix(in srgb, var(--accent-color) 5%, transparent), transparent); border: 1px solid color-mix(in srgb, var(--accent-color) 20%, transparent);">
                <h3 class="text-2xl sm:text-3xl font-bold text-white">
                    {{ $profil->layanan_cta_title }}
                </h3>
                <p class="text-gray-300 max-w-2xl mx-auto text-sm sm:text-base">
                    {!! $profil->layanan_cta_desc ?? 'Percayakan kendaraan premium Anda kepada ahlinya.' !!}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('katalog.user') }}" class="inline-flex items-center justify-center px-8 py-3 text-black font-bold rounded-full transition-all duration-300 shadow-lg group" style="background-color: var(--accent-color); box-shadow: 0 4px 20px color-mix(in srgb, var(--accent-color) 30%, transparent);">
                        <span>Lihat Katalog</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '') }}" target="_blank" class="inline-flex items-center justify-center px-8 py-3 font-bold rounded-full transition-all duration-300" style="border: 2px solid var(--accent-color); color: var(--accent-color); background-color: transparent;">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.272-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-3.055 2.11-3.653 6.02-1.319 8.93 2.176 2.771 6.133 3.637 9.028 1.783 1.669-.96 2.636-2.461 2.973-4.43.2-1.122-.042-2.26-.614-3.2L8.622 9.897m5.768.45a1.007 1.007 0 10-2.012-.001 1.007 1.007 0 002.012 0z"/>
                        </svg>
                        <span>Konsultasi Gratis</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection

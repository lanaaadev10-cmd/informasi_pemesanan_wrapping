@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Tentang Kami')

@section('content')
    @php
        // Setup CSS variables & settings
        $accentColor = $profil->accent_color ?? '#f2994a';
        $valuesColumns = $profil->tentang_kami_values_columns ?? 3;
        $showValues = $profil->tentang_kami_show_values ?? true;
        $showHistory = $profil->tentang_kami_show_history ?? true;
        $showTeam = $profil->tentang_kami_show_team ?? true;

        // Fallback values matching mockup
        $heroTitle = $profil->tentang_kami_hero_title ?? 'Precision in Every Layer';
        $heroDesc = $profil->tentang_kami_hero_desc ?? 'Satu pilihan terbaik untuk menjaga kendaraan Anda tetap berkilau dan melindunginya dari goresan, jamur, serta kotoran jalanan demi performa yang selalu cemerlang.';

        $teamMembers = $profil->tentang_kami_team_members;
        if (empty($teamMembers)) {
            $teamMembers = \App\Models\TeamMember::aktif()->orderBy('urutan')->get()->map(function ($m) {
                return [
                    'nama'   => $m->nama,
                    'posisi' => $m->jabatan ?? '',
                    'foto'   => $m->foto ?? '',
                ];
            })->toArray();
        }
    @endphp

    @include('landing.tentang-kami._style')

    <!-- HERO SECTION (Full Cinematic Banner) -->
    @include('landing.tentang-kami._hero')

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-16 sm:py-24 space-y-24 sm:space-y-32 relative overflow-hidden">
        
        <!-- Ambient Glowing Core -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent) 5%, transparent);"></div>

        <!-- SEJARAH SECTION (Satu Dekade Dedikasi) -->
        @include('landing.tentang-kami._history')

        <!-- VISI & MISI SECTION -->
        @include('landing.tentang-kami._vision-mission')

        <!-- VALUES/KOMITMEN SECTION (Nilai yang Kami Junjung) -->
        @include('landing.tentang-kami._values')

        <!-- TEAM SECTION (Dibalik Setiap Detail Sempurna) -->
        @include('landing.tentang-kami._team')

        <!-- CTA SECTION -->
        @include('landing.tentang-kami._cta')
    </div>
@endsection


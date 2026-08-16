@extends('layouts.tampilan_utama')

@section('title', 'Tentang Kami')

@section('content')
    @php
        $accentColor = '#f2994a';
        $showHistory = true;
        $showValues = true;
        $showTeam = true;
        $heroTitle = \App\Helpers\StaticContent::TENTANG_HERO_TITLE;
        $heroDesc = \App\Helpers\StaticContent::TENTANG_HERO_DESC;
        $teamMembers = \App\Helpers\StaticContent::teamMembers();
    @endphp

    @include('landing.tentang-kami._style')

    <!-- HERO SECTION (Full Cinematic Banner) -->
    @include('landing.tentang-kami._hero')

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-16 sm:py-24 space-y-24 sm:space-y-32 relative overflow-hidden">
        
        <!-- Ambient Glowing Core -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

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


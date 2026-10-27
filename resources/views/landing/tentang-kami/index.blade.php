@extends('layouts.tampilan-utama')

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

    <!-- HERO SECTION (Full Cinematic Banner) -->
    @include('landing.tentang-kami._bagian_utama')

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-16 sm:space-y-24 relative overflow-hidden">
        
        <!-- Ambient Glowing Core -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0 bg-[#ea580c]/5"></div>

        <!-- SEJARAH SECTION (Satu Dekade Dedikasi) -->
        @include('landing.tentang-kami._sejarah')

        <!-- VISI & MISI SECTION -->
        @include('landing.tentang-kami._visi_misi')

        <!-- VALUES/KOMITMEN SECTION (Nilai yang Kami Junjung) -->
        @include('landing.tentang-kami._nilai_perusahaan')

        <!-- TEAM SECTION (Dibalik Setiap Detail Sempurna) -->
        @include('landing.tentang-kami._tim_ahli')

        <!-- LOKASI KAMI SECTION (Google Maps) -->
        @include('landing.tentang-kami._lokasi')

        <!-- CTA SECTION -->
        @include('landing.tentang-kami._ajakan_bertindak')
    </div>
@endsection


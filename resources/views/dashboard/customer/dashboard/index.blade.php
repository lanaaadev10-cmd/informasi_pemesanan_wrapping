@extends('layouts.dashboard_customer')

@section('title', 'User Dashboard')

@section('content')
<div class="space-y-10" data-aos="fade-up" data-aos-duration="1000">

    {{-- 1. Halo Greeting Header --}}
    @include('dashboard.customer.dashboard._header')

    {{-- 2. Central Top row Bento Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Active Order Card --}}
        @include('dashboard.customer.dashboard._order-card')
    </div>

    {{-- 3. Paket Layanan dengan Carousel --}}
    @include('dashboard.customer.dashboard._packages-carousel')

    {{-- 3b. CTA Beri Testimoni (Alur 2 — rating tanpa pesanan) --}}
    @include('dashboard.customer.dashboard._testimonial-cta')

    {{-- 4. Aktivitas Terakhir Section --}}
    @include('dashboard.customer.dashboard._recent-activity')

    {{-- 5. Galeri Portofolio Section --}}
    @include('dashboard.customer.dashboard._gallery-section')

</div>
@endsection
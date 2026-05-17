@extends('layouts.tampilan_utama')

@section('title', 'Beranda')

@section('content')
    <!-- 1. Hero Section (Sesuai Gambar) -->
    <section class="max-w-7xl mx-auto px-6 pt-12 pb-24 text-center" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">Premium Sticker & Wrapping</span>
        <h1 class="text-4xl sm:text-6xl md:text-8xl font-extrabold text-gray-900 leading-[1.1] md:leading-[1] tracking-tighter mb-8">
            @if($profil->home_title)
                {!! nl2br(e($profil->home_title)) !!}
            @else
                Make these <br> 
                <span class="text-gradient italic">phenomenal.</span>
            @endif
        </h1>
        <p class="max-w-2xl mx-auto text-gray-500 text-lg md:text-xl leading-relaxed mb-10">
            {{ $profil->home_subtitle ?? ($profil->deskripsi ?? 'Transformasikan kendaraan dan bisnis Anda dengan sentuhan profesional dari tim ahli kami.') }}
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20">
            <a href="https://wa.me/{{ $profil->nomor_telepon ?? '' }}" class="btn-premium text-white px-10 py-4 rounded-2xl font-bold text-lg transition-all hover:scale-105">
                Get Started Now
            </a>
            <a href="{{ route('profil.perusahaan') }}" class="bg-gray-50 hover:bg-gray-100 text-gray-700 px-10 py-4 rounded-2xl font-bold text-lg transition-all border border-gray-200">
                View All Works
            </a>
        </div>

        <!-- Featured Image Mockup (Sesuai Gambar) -->
        <div class="relative max-w-5xl mx-auto" data-aos="zoom-in" data-aos-delay="200">
            <div class="absolute -inset-10 bg-orange-600/5 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="soft-card p-4 overflow-hidden shadow-2xl relative">
                @if($profil->home_hero_image)
                    <img src="{{ asset('storage/' . $profil->home_hero_image) }}" 
                         class="w-full h-auto rounded-2xl" alt="Featured Work">
                @else
                    <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?q=80&w=2101&auto=format&fit=crop" 
                         class="w-full h-auto rounded-2xl" alt="Featured Work">
                @endif
            </div>
        </div>
    </section>

    <!-- 2. Section Header (Designed for Professionals) -->
    <section class="max-w-7xl mx-auto px-6 py-24 text-center" data-aos="fade-up">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
            {!! $profil->home_feature_title ? nl2br(e($profil->home_feature_title)) : 'Designed for <br> Professionals' !!}
        </h2>
        <p class="max-w-xl mx-auto text-gray-500 leading-relaxed font-medium">
            {{ $profil->home_feature_subtitle ?? 'Memberikan solusi wrapping dan stiker yang presisi untuk kebutuhan bisnis skala besar maupun personal.' }}
        </p>
    </section>

    <!-- 3. Feature Cards Grid (Sesuai Gambar) -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="grid md:grid-cols-3 gap-10">
            <!-- Card 1 -->
            <div class="soft-card p-10 group hover:border-orange-500 transition-all duration-500" data-aos="fade-up">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-sketch-logo text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">High-End Wrapping</h3>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed">Material premium dari 3M dan Avery Dennison untuk ketahanan jangka panjang.</p>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" class="w-full h-40 object-cover" alt="Service">
                </div>
            </div>

            <!-- Card 2 -->
            <div class="soft-card p-10 group hover:border-orange-500 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-pencil-circle text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Custom Graphic Design</h3>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed">Tim desainer ahli yang siap mewujudkan konsep visual unik untuk brand Anda.</p>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1542744094-24638eff58bb?q=80&w=2071&auto=format&fit=crop" class="w-full h-40 object-cover" alt="Service">
                </div>
            </div>

            <!-- Card 3 -->
            <div class="soft-card p-10 group hover:border-orange-500 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-shield-check text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Paint Protection</h3>
                <p class="text-gray-500 mb-8 text-sm leading-relaxed">Lapisan pelindung cat transparan untuk menjaga keaslian warna kendaraan Anda.</p>
                <div class="rounded-2xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=2070&auto=format&fit=crop" class="w-full h-40 object-cover" alt="Service">
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Testimonial Section (Sesuai Gambar) -->
    <section class="max-w-4xl mx-auto px-6 py-24 text-center" data-aos="fade-up">
        <div class="mb-10">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1974&auto=format&fit=crop" 
                 class="w-20 h-20 rounded-full mx-auto border-4 border-orange-500/20 shadow-xl" alt="Testimonial">
        </div>
        <div class="flex justify-center gap-1 mb-6">
            @for($i=0; $i<5; $i++) <i class="ph-fill ph-star text-orange-500"></i> @endfor
        </div>
        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 leading-snug mb-8">
            "Kualitas pengerjaan di {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }} benar-benar di atas ekspektasi. <br class="hidden md:block"> 
            Hasilnya sangat presisi dan materialnya sangat kuat."
        </h3>
        <p class="text-gray-500 font-bold uppercase tracking-widest text-sm">Andika Pratama - CEO TechCorp</p>
    </section>

    <!-- 5. Final CTA Section (Sesuai Gambar) -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="btn-premium rounded-[48px] p-12 md:p-24 text-center text-white relative overflow-hidden shadow-2xl" data-aos="zoom-in">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 blur-[80px] rounded-full"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-6xl font-extrabold mb-8">Ready to get <br> started?</h2>
                <p class="text-white/80 text-lg mb-12 max-w-xl mx-auto font-medium">
                    Jadikan kendaraan atau bisnis Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim kami.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-5">
                    <a href="https://wa.me/{{ $profil->nomor_telepon ?? '' }}" class="bg-white text-orange-600 px-10 py-4 rounded-2xl font-bold text-lg shadow-lg hover:scale-105 transition-transform">
                        Hubungi Tim Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
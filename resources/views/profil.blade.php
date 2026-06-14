@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Profil Perusahaan')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 text-center mb-24" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">{{ $profil->profil_section_title ?? 'Identity & Excellence' }}</span>
        <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold text-gray-900 leading-[1.1] tracking-tight mb-8">
            {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }} <br> 
            <span class="text-gradient">{{ $profil->profil_banner_heading ?? 'Wrapping Solutions' }}</span>
        </h1>
        <p class="max-w-3xl mx-auto text-gray-500 text-lg md:text-xl leading-relaxed mb-10">
            {{ $profil->deskripsi ?? 'Kami hadir dengan dedikasi penuh untuk memberikan hasil terbaik bagi bisnis dan kendaraan Anda melalui inovasi stiker yang tak tertandingi.' }}
        </p>
    </section>

    <!-- Stats Section -->
    <section class="max-w-5xl mx-auto px-6 mb-24 grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up">
        <div class="text-center">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">5+</h4>
            <p class="text-gray-500 text-sm font-medium">{{ $profil->profil_stat_label ?? 'Tahun Pengalaman' }}</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">1.2k+</h4>
            <p class="text-gray-500 text-sm font-medium">{{ $profil->profil_stat_label ?? 'Project Selesai' }}</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">99%</h4>
            <p class="text-gray-500 text-sm font-medium">{{ $profil->profil_stat_label ?? 'Kepuasan Pelanggan' }}</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">24h</h4>
            <p class="text-gray-500 text-sm font-medium">{{ $profil->profil_stat_label ?? 'Support Standby' }}</p>
        </div>
    </section>

    <!-- CMS: Visi & Misi Section -->
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div class="grid md:grid-cols-2 gap-10">
            <!-- Visi Card -->
            <div class="soft-card p-10 md:p-12 flex flex-col gap-6 hover:border-orange-500 transition-all duration-300" data-aos="fade-right">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">
                    <i class="ph-bold ph-eye text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-4">{{ $profil->profil_legal_visi_title ?? 'Visi Kami' }}</h2>
                    <div class="text-gray-600 leading-relaxed prose prose-orange max-w-none">
                        {!! $profil->visi ?? '<p>Menjadi perusahaan jasa wrapping dan stiker terdepan di Indonesia dengan kualitas internasional serta inovasi berkelanjutan.</p>' !!}
                    </div>
                </div>
            </div>

            <!-- Misi Card -->
            <div class="soft-card p-10 md:p-12 flex flex-col gap-6 hover:border-orange-500 transition-all duration-300" data-aos="fade-left">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600">
                    <i class="ph-bold ph-target text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-4">{{ $profil->profil_legal_misi_title ?? 'Misi Kami' }}</h2>
                    <div class="text-gray-600 leading-relaxed prose prose-orange max-w-none">
                        {!! $profil->misi ?? '<ul class="list-disc pl-5 space-y-2"><li>Memberikan layanan wrapping berkualitas premium dan presisi tinggi.</li><li>Mengutamakan kepuasan pelanggan melalui pelayanan profesional.</li><li>Menggunakan bahan berstandar dunia dan teknologi cetak terkini.</li></ul>' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CMS: Sejarah Section -->
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div class="soft-card p-10 md:p-16" data-aos="fade-up">
            <div class="grid md:grid-cols-3 gap-10 items-start">
                <div class="md:col-span-1">
                    <span class="text-orange-600 font-bold text-xs uppercase tracking-widest mb-4 block">Tentang Kami</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ $profil->profil_legal_sejarah_title ?? 'Sejarah & Perjalanan' }}</h2>
                    <div class="w-16 h-1 bg-orange-500 mt-6 rounded-full"></div>
                </div>
                <div class="md:col-span-2 text-gray-600 leading-relaxed prose prose-orange max-w-none">
{!! $profil->sejarah ?? '<p class="mb-4">Didirikan dengan dedikasi penuh terhadap dunia otomotif dan stiker, kami memulai perjalanan sebagai studio kecil dengan fokus pada kerapatan dan kualitas. Melalui kerja keras dan kepuasan pelanggan yang terus terjaga, kami terus tumbuh dan dipercaya oleh ribuan pemilik kendaraan dan bisnis.</p><p>Kami terus berinvestasi pada teknologi mesin cetak terbaru dan melatih tim installer profesional agar setiap sudut stiker teraplikasi secara presisi dan tahan lama.</p>' !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Alamat -->
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-map-pin text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $profil->footer_lokasi ?? 'Lokasi' }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $profil->alamat ?? 'Jl. Raya Wrapping No. 77, Malang, Jawa Timur' }}</p>
            </div>
            
            <!-- Email -->
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-envelope text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $profil->form_email ?? 'Email' }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    <a href="mailto:{{ $profil->email ?? 'info@dantiesticker.com' }}" class="hover:text-orange-600 transition-colors">
                        {{ $profil->email ?? 'info@dantiesticker.com' }}
                    </a>
                </p>
            </div>

            <!-- WhatsApp -->
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-whatsapp-logo text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $profil->nav_whatsapp ?? 'WhatsApp' }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-3">
                    {{ $profil->nomor_telepon ?? '081234567890' }}
                </p>
                <a href="{{ $profil ? $profil->whatsapp_link : 'https://wa.me/6281234567890' }}" target="_blank" 
                   class="inline-flex items-center gap-2 text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors">
                    Hubungi WhatsApp <i class="ph-bold ph-arrow-square-out"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Google Maps Location Section -->
    @if(!empty($profil->maps_url))
        <section class="max-w-7xl mx-auto px-6 mb-32" data-aos="fade-up">
            <div class="soft-card overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="ph-bold ph-compass text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $profil->label_temukan_kami ?? 'Temukan Kami' }}</h4>
                            <p class="text-xs text-gray-500">Klik pin maps untuk navigasi langsung</p>
                        </div>
                    </div>
                    <a href="https://maps.google.com/?q={{ urlencode($profil->alamat) }}" target="_blank" 
                       class="btn-premium text-white px-6 py-2 rounded-full text-xs font-bold transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                        <i class="ph-bold ph-map-trifold"></i> Buka Google Maps
                    </a>
                </div>
                <div class="w-full h-[400px] bg-gray-100">
                    <iframe
                        src="{{ $profil->maps_url }}"
                        width="100%"
                        height="400"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </section>
    @endif
@endsection

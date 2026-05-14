@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Profil Perusahaan')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 text-center mb-32" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-6 block">Identity & Excellence</span>
        <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold text-gray-900 leading-[1.1] tracking-tight mb-8">
            @if($profil->about_hero_title)
                {!! nl2br(e($profil->about_hero_title)) !!}
            @else
                Bring everyone together with <br> 
                <span class="text-gradient">solutions that scale.</span>
            @endif
        </h1>
        <p class="max-w-2xl mx-auto text-gray-500 text-lg md:text-xl leading-relaxed mb-10">
            {{ $profil->about_hero_subtitle ?? ($profil->deskripsi ?? 'Kami hadir dengan dedikasi penuh untuk memberikan hasil terbaik bagi bisnis dan kendaraan Anda melalui inovasi stiker yang tak tertandingi.') }}
        </p>
    </section>

    <!-- Stats Section -->
    <section class="max-w-5xl mx-auto px-6 mb-32 grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up">
        <div class="text-center">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">{{ $profil->stats_experience ?? '5+' }}</h4>
            <p class="text-gray-500 text-sm font-medium">Tahun Pengalaman</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">{{ $profil->stats_projects ?? '1.2k' }}</h4>
            <p class="text-gray-500 text-sm font-medium">Project Selesai</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">{{ $profil->stats_satisfaction ?? '99%' }}</h4>
            <p class="text-gray-500 text-sm font-medium">Kepuasan Pelanggan</p>
        </div>
        <div class="text-center border-l border-gray-100">
            <h4 class="text-4xl font-bold text-gray-900 mb-2">{{ $profil->stats_support ?? '24h' }}</h4>
            <p class="text-gray-500 text-sm font-medium">Support Standby</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="soft-card p-10 md:p-16 flex flex-col justify-center gap-8" data-aos="fade-right">
                <div class="w-16 h-16 bg-orange-100 rounded-3xl flex items-center justify-center text-orange-600">
                    <i class="ph-bold ph-lightning text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-5">{{ $profil->about_feature_title ?? 'Pengerjaan Cepat & Presisi' }}</h2>
                    <p class="text-gray-500 leading-relaxed text-lg">
                        {{ $profil->about_feature_desc ?? 'Setiap pengerjaan kami melewati proses kontrol kualitas yang ketat untuk memastikan hasil akhir yang sempurna bagi Anda.' }}
                    </p>
                </div>
                <ul class="space-y-4">
                    @php
                        $featureList = $profil->about_feature_list ?? ['Bahan Vinyl Kualitas Dunia', 'Mesin Cetak Berteknologi Tinggi'];
                    @endphp
                    @foreach($featureList as $item)
                    <li class="flex items-center gap-3 font-semibold text-gray-700">
                        <i class="ph-bold ph-check-circle text-green-500 text-xl"></i>
                        {{ is_array($item) ? ($item['item'] ?? '') : $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="relative group" data-aos="fade-left">
                <div class="absolute -inset-4 bg-orange-600/5 rounded-[40px] scale-95 group-hover:scale-100 transition-transform duration-700"></div>
                <div class="relative rounded-[32px] overflow-hidden shadow-2xl">
                    @if($profil->about_feature_image)
                        <img src="{{ asset('storage/' . $profil->about_feature_image) }}" 
                             class="w-full h-[500px] object-cover group-hover:scale-110 transition-transform duration-1000" alt="Mockup">
                    @else
                        <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" 
                             class="w-full h-[500px] object-cover group-hover:scale-110 transition-transform duration-1000" alt="Mockup">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-map-pin text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Lokasi</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $profil->alamat ?? 'Alamat belum diatur' }}</p>
            </div>
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-envelope text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $profil->email ?? 'Email belum diatur' }}</p>
            </div>
            <div class="soft-card p-8 flex flex-col items-center text-center group hover:border-orange-500 transition-all" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="ph-bold ph-whatsapp-logo text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">WhatsApp</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $profil->nomor_telepon ?? 'Telepon belum diatur' }}</p>
            </div>
        </div>
    </section>
@endsection

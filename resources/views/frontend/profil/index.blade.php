@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Profil Perusahaan')

@section('content')
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 text-center py-8 mb-16">
        <span class="text-[#f2541b] font-bold text-xs uppercase tracking-widest mb-4 block">Identity & Excellence</span>
        <h1 class="font-serif text-4xl sm:text-6xl md:text-7xl font-black text-stone-900 leading-tight tracking-tight mb-6">
            @if($profil->about_hero_title)
                {!! nl2br(e($profil->about_hero_title)) !!}
            @else
                Bring everyone together with <br> 
                <span class="text-[#f2541b]">solutions that scale.</span>
            @endif
        </h1>
        <p class="max-w-2xl mx-auto text-stone-500 text-sm md:text-base leading-relaxed">
            {{ $profil->about_hero_subtitle ?? ($profil->deskripsi ?? 'Kami hadir dengan dedikasi penuh untuk memberikan hasil terbaik bagi bisnis dan kendaraan Anda melalui inovasi stiker yang tak tertandingi.') }}
        </p>
    </section>

    <!-- Stats Section -->
    <section class="max-w-5xl mx-auto px-6 mb-24 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center p-6 bg-white rounded-2xl border border-stone-200/50 shadow-sm">
            <h4 class="font-serif text-3xl md:text-4xl font-black text-stone-900 mb-1">{{ $profil->stats_experience ?? '5+' }}</h4>
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider">Tahun Pengalaman</p>
        </div>
        <div class="text-center p-6 bg-white rounded-2xl border border-stone-200/50 shadow-sm">
            <h4 class="font-serif text-3xl md:text-4xl font-black text-stone-900 mb-1">{{ $profil->stats_projects ?? '1.2k' }}</h4>
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider">Project Selesai</p>
        </div>
        <div class="text-center p-6 bg-white rounded-2xl border border-stone-200/50 shadow-sm">
            <h4 class="font-serif text-3xl md:text-4xl font-black text-stone-900 mb-1">{{ $profil->stats_satisfaction ?? '99%' }}</h4>
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider">Kepuasan Pelanggan</p>
        </div>
        <div class="text-center p-6 bg-white rounded-2xl border border-stone-200/50 shadow-sm">
            <h4 class="font-serif text-3xl md:text-4xl font-black text-stone-900 mb-1">{{ $profil->stats_support ?? '24h' }}</h4>
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider">Support Standby</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            <div class="bg-white rounded-[32px] border border-stone-200/60 p-8 md:p-12 flex flex-col justify-center gap-6 shadow-sm" data-aos="fade-right">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-[#f2541b] shadow-sm">
                    <i class="ph-bold ph-lightning text-xl"></i>
                </div>
                <div>
                    <h2 class="font-serif text-2xl md:text-3xl font-black text-stone-900 mb-3">{{ $profil->about_feature_title ?? 'Pengerjaan Cepat & Presisi' }}</h2>
                    <p class="text-stone-500 leading-relaxed text-sm">
                        {{ $profil->about_feature_desc ?? 'Setiap pengerjaan kami melewati proses kontrol kualitas yang ketat untuk memastikan hasil akhir yang sempurna bagi Anda.' }}
                    </p>
                </div>
                <ul class="space-y-3 pt-2">
                    @php
                        $featureList = $profil->about_feature_list ?? ['Bahan Vinyl Kualitas Dunia', 'Mesin Cetak Berteknologi Tinggi'];
                    @endphp
                    @foreach($featureList as $item)
                    <li class="flex items-center gap-3 font-semibold text-stone-750 text-xs">
                        <span class="text-emerald-500">✓</span>
                        {{ is_array($item) ? ($item['item'] ?? '') : $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="relative group" data-aos="fade-left">
                <div class="absolute -inset-4 bg-[#f2541b]/5 rounded-[40px] scale-95 group-hover:scale-100 transition-transform duration-700"></div>
                <div class="relative rounded-[32px] overflow-hidden shadow-xl border border-stone-200/50">
                    @if($profil->about_feature_image)
                        <img src="{{ asset('storage/' . $profil->about_feature_image) }}" 
                             class="w-full h-[450px] object-cover group-hover:scale-105 transition-transform duration-[1.5s]" alt="Mockup">
                    @else
                        <img src="https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=2070&auto=format&fit=crop" 
                             class="w-full h-[450px] object-cover group-hover:scale-105 transition-transform duration-[1.5s]" alt="Mockup">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="max-w-7xl mx-auto px-6 mb-24">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-[28px] border border-stone-200/60 p-8 flex flex-col items-center text-center group hover:border-[#f2541b]/35 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300">
                <div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center text-[#f2541b] mb-6 group-hover:bg-[#f2541b] group-hover:text-white transition-all shadow-sm">
                    <i class="ph-bold ph-map-pin text-xl"></i>
                </div>
                <h3 class="font-serif text-lg font-black text-stone-900 mb-2">Lokasi</h3>
                <p class="text-stone-500 text-xs leading-relaxed font-semibold">{{ $profil->alamat ?? 'Alamat belum diatur' }}</p>
            </div>
            <div class="bg-white rounded-[28px] border border-stone-200/60 p-8 flex flex-col items-center text-center group hover:border-[#f2541b]/35 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300">
                <div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center text-[#f2541b] mb-6 group-hover:bg-[#f2541b] group-hover:text-white transition-all shadow-sm">
                    <i class="ph-bold ph-envelope text-xl"></i>
                </div>
                <h3 class="font-serif text-lg font-black text-stone-900 mb-2">Email</h3>
                <p class="text-stone-500 text-xs leading-relaxed font-semibold">{{ $profil->email ?? 'Email belum diatur' }}</p>
            </div>
            <div class="bg-white rounded-[28px] border border-stone-200/60 p-8 flex flex-col items-center text-center group hover:border-[#f2541b]/35 hover:shadow-xl hover:shadow-[#f2541b]/5 transition-all duration-300">
                <div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center text-[#f2541b] mb-6 group-hover:bg-[#f2541b] group-hover:text-white transition-all shadow-sm">
                    <i class="ph-bold ph-whatsapp-logo text-xl"></i>
                </div>
                <h3 class="font-serif text-lg font-black text-stone-900 mb-2">WhatsApp</h3>
                <p class="text-stone-500 text-xs leading-relaxed font-semibold">{{ $profil->nomor_telepon ?? 'Telepon belum diatur' }}</p>
            </div>
        </div>
    </section>
@endsection


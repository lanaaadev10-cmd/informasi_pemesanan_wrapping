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
    <section class="max-w-7xl mx-auto px-6 mb-32 text-center" data-aos="fade-up">
        <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-4 block">For Professionals</span>
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
            {!! $profil->home_prof_title ? nl2br(e($profil->home_prof_title)) : 'Designed for <br> Professionals' !!}
        </h2>
        <p class="max-w-xl mx-auto text-gray-500 leading-relaxed font-medium">
            {{ $profil->home_prof_subtitle ?? 'Memberikan solusi wrapping dan stiker yang presisi untuk kebutuhan bisnis skala besar maupun personal.' }}
        </p>
    </section>

    <!-- 4. Catalog Section -->
    <section class="max-w-7xl mx-auto px-6 mb-32" id="katalog">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-4 block">Our Catalog</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                @if($profil->home_catalog_title)
                    {!! nl2br(e($profil->home_catalog_title)) !!}
                @else
                    Pilihan Paket <span class="text-gradient">Terbaik.</span>
                @endif
            </h2>
            <p class="max-w-2xl mx-auto text-gray-500 font-medium italic">
                "{{ $profil->home_catalog_subtitle ?? 'Pilih dari berbagai layanan wrapping dan stiker premium kami yang telah dikurasi untuk hasil maksimal.' }}"
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @forelse($layanans->take(3) as $item)
                <div class="katalog-item group relative bg-white rounded-[2.5rem] border border-gray-100 hover:border-orange-500/30 transition-all duration-700 hover:-translate-y-3 hover:shadow-[0_40px_80px_-15px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden">
                    <div class="relative aspect-[1/1] overflow-hidden m-4 rounded-[2rem]">
                        @if($item->foto_contoh)
                            <img src="{{ asset('storage/' . $item->foto_contoh) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="{{ $item->nama_layanan }}">
                        @else
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-200">
                                <i class="ph-fill ph-sketch-logo text-7xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-5 left-5 z-20">
                            <span class="px-5 py-2 bg-white/20 backdrop-blur-xl border border-white/30 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-full shadow-2xl">
                                {{ $item->kategori ?? 'Masterpiece' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-8 pt-2 flex flex-col flex-grow">
                        <div class="mb-8">
                            <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight group-hover:text-orange-600 transition-colors">{{ $item->nama_layanan }}</h3>
                            <div class="flex items-center gap-3">
                                <div class="h-[1px] w-8 bg-orange-600/30"></div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Premium Package</span>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <div class="flex items-end justify-between mb-8">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Starting Price</span>
                                    <span class="text-3xl font-black text-gray-900 italic tracking-tighter">
                                        <span class="text-orange-600 text-lg not-italic font-bold mr-1">Rp</span>{{ number_format($item->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                                <a href="{{ route('katalog.user') }}" class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all group/cart">
                                    <i class="ph-bold ph-shopping-cart-simple text-2xl group-hover/cart:animate-bounce"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-400 italic font-medium">
                    "Belum ada katalog yang ditambahkan."
                </div>
            @endforelse
        </div>
        
        @if($layanans->count() > 3)
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 font-black text-gray-900 hover:text-orange-600 transition-colors tracking-widest uppercase text-sm">
                    View All Masterpieces <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        @endif
    </section>

    <!-- 4.5 Live Activity Feed -->
    <section class="max-w-7xl mx-auto px-6 mb-32 overflow-hidden">
        <div class="bg-gray-900 rounded-[50px] p-12 md:p-16 relative shadow-2xl">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-orange-600/20 blur-[100px] rounded-full"></div>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-10 relative z-10">
                <div class="max-w-md" data-aos="fade-right">
                    <span class="text-orange-500 font-bold text-xs uppercase tracking-[0.4em] mb-4 block">Live Activity</span>
                    <h2 class="text-4xl font-black text-white mb-6">Recent <br> <span class="text-orange-500 italic">Bookings.</span></h2>
                    <p class="text-gray-400 font-medium leading-relaxed italic">"Witness the transformation. Real-time updates from our workshop and premium clients."</p>
                </div>

                <div class="flex-grow w-full md:max-w-xl" data-aos="fade-left">
                    <div class="space-y-4">
                        @forelse($recentActivity as $act)
                        <div class="flex items-center justify-between p-5 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm hover:bg-white/10 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-600/20 flex items-center justify-center text-orange-500">
                                    <i class="ph-fill ph-user"></i>
                                </div>
                                <div>
                                    <p class="text-white text-sm font-bold">
                                        {{ substr($act->user->name, 0, 1) . str_repeat('*', 3) . substr($act->user->name, -1) }}
                                    </p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">Just secured a service</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-white/5 text-orange-400 text-[10px] font-black rounded-lg border border-white/5 italic">
                                    {{ $act->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 italic text-center">No bookings yet today.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Step-by-Step Ordering Flow (Dynamic from ProfilPerusahaan) -->
    <section class="max-w-7xl mx-auto px-6 mb-32 bg-gray-50 rounded-[60px] py-24 border border-gray-100">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-orange-600 font-bold text-xs uppercase tracking-[0.3em] mb-4 block">Our Process</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">Alur Pemesanan <span class="text-gradient italic">Mudah.</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-12 z-0"></div>

            @php
                $steps = [
                    ['title' => $profil->step_1_title ?? 'Pilih Paket', 'desc' => $profil->step_1_desc ?? 'Jelajahi katalog kami dan pilih paket yang sesuai.'],
                    ['title' => $profil->step_2_title ?? 'Login & Pesan', 'desc' => $profil->step_2_desc ?? 'Masuk ke akun Anda dan isi formulir pemesanan.'],
                    ['title' => $profil->step_3_title ?? 'Konfirmasi', 'desc' => $profil->step_3_desc ?? 'Tim kami akan mengonfirmasi jadwal pengerjaan.'],
                    ['title' => $profil->step_4_title ?? 'Pengerjaan', 'desc' => $profil->step_4_desc ?? 'Bawa kendaraan Anda ke workshop kami.'],
                ];
            @endphp

            @foreach($steps as $index => $step)
            <div class="relative z-10 flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center text-gray-900 text-3xl font-black shadow-xl group-hover:bg-orange-600 group-hover:text-white transition-all mb-8 border border-gray-100 italic">{{ $index + 1 }}</div>
                <h4 class="text-xl font-bold mb-3 tracking-tight">{{ $step['title'] }}</h4>
                <p class="text-gray-500 text-sm leading-relaxed font-medium italic">"{{ $step['desc'] }}"</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- 6. Testimonial Section (Dynamic from ProfilPerusahaan JSON) -->
    @if($testimonis->count() > 0)
    <section class="max-w-4xl mx-auto px-6 py-24 text-center" data-aos="fade-up">
        @php $topTesti = $testimonis->first(); @endphp
        <div class="mb-10">
            @if(isset($topTesti->foto) && $topTesti->foto)
                <img src="{{ asset('storage/' . $topTesti->foto) }}" 
                     class="w-24 h-24 rounded-full mx-auto border-4 border-orange-500/20 shadow-xl object-cover" alt="Testimonial">
            @else
                <div class="w-24 h-24 rounded-full mx-auto bg-gray-100 flex items-center justify-center text-gray-300">
                    <i class="ph-fill ph-user text-5xl"></i>
                </div>
            @endif
        </div>
        <div class="flex justify-center gap-1 mb-6">
            @for($i=0; $i<($topTesti->rating ?? 5); $i++) <i class="ph-fill ph-star text-orange-500"></i> @endfor
        </div>
        <h3 class="text-3xl md:text-4xl font-black text-gray-900 leading-snug mb-8 tracking-tight italic">
            "{!! isset($topTesti->isi) ? nl2br(e($topTesti->isi)) : 'Belum ada isi testimoni.' !!}"
        </h3>
        <p class="text-gray-400 font-black uppercase tracking-[0.3em] text-xs">{{ $topTesti->nama ?? 'Anonymous' }} - {{ $topTesti->jabatan ?? 'Pelanggan' }}</p>
    </section>
    @endif

    <!-- 7. Final CTA Section -->
    <section class="max-w-7xl mx-auto px-6 mb-32">
        <div class="btn-premium rounded-[48px] p-12 md:p-24 text-center text-white relative overflow-hidden shadow-2xl" data-aos="zoom-in">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 blur-[80px] rounded-full"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-7xl font-black mb-8 italic tracking-tighter uppercase">Ready to <br> Transform?</h2>
                <p class="text-white/80 text-lg mb-12 max-w-xl mx-auto font-medium italic leading-relaxed">
                    "Jadikan kendaraan atau bisnis Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim ahli kami."
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-5">
                    <a href="https://wa.me/{{ $profil->nomor_telepon ?? '' }}" class="bg-white text-orange-600 px-12 py-5 rounded-2xl font-black text-sm tracking-widest uppercase shadow-2xl hover:scale-105 transition-transform">
                        CONTACT US NOW
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
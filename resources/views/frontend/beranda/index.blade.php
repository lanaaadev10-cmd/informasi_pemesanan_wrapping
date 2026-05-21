@extends('layouts.tampilan_utama')

@section('title', 'Beranda')

@section('content')
    {{-- Ambil nilai dari DB dengan fallback default --}}
    @php
        $badge       = $profil->home_badge_text         ?? 'Professional Car Wrapping Indonesia';
        $title1      = $profil->home_hero_title_line1   ?? 'Elevasi Estetika';
        $title2      = $profil->home_hero_title_line2   ?? 'Aset Mewah Anda.';
        $subtitle    = $profil->home_subtitle           ?? 'Layanan premium yang melindungi dan memperindah mobil kesayangan Anda. Hubungi kami untuk penawaran terbaik.';
        $stat1v      = $profil->home_stat1_value        ?? '500+';
        $stat1l      = $profil->home_stat1_label        ?? 'Supercars Wrapped';
        $stat2v      = $profil->home_stat2_value        ?? '5 Tahun';
        $stat2l      = $profil->home_stat2_label        ?? 'Garansi Material';

        $k1t = $profil->home_keunggulan_card1_title ?? 'Kualitas Material Grade-A';
        $k1d = $profil->home_keunggulan_card1_desc  ?? 'Kami hanya menggunakan merk premium dunia seperti <span class="text-white font-semibold">Avery Dennison, 3M, dan Teckwrap</span>. Memberikan hasil akhir yang sangat rapi, warna yang tahan lama, serta perlindungan cat orisinil mobil yang maksimal.';
        $k2t = $profil->home_keunggulan_card2_title ?? 'Teknisi Tersertifikasi';
        $k2d = $profil->home_keunggulan_card2_desc  ?? 'Dikerjakan oleh tim profesional yang terlatih dan memiliki sertifikasi resmi di bidang car wrapping untuk menjamin ketelitian tinggi.';
        $k3t = $profil->home_keunggulan_card3_title ?? 'Pengerjaan Tepat Waktu';
        $k3d = $profil->home_keunggulan_card3_desc  ?? 'Kami menghargai waktu berharga Anda. Dengan SOP terstruktur, kami menjamin kendaraan Anda selesai dikerjakan sesuai estimasi waktu.';
        $k4t = $profil->home_keunggulan_card4_title ?? 'Garansi Hingga 5 Tahun';
        $k4d = $profil->home_keunggulan_card4_desc  ?? 'Kami sangat yakin atas kualitas pengerjaan dan ketahanan bahan yang kami berikan. Nikmati perlindungan garansi penuh hingga 5 tahun untuk kepuasan total Anda.';

        $ctaTitle    = $profil->home_cta_title    ?? 'Siap Mengubah Tampilan Kendaraan?';
        $ctaSubtitle = $profil->home_cta_subtitle ?? 'Hubungi konsultan desain gratis sekarang. Tim ahli kami akan membantu Anda memilih material dan warna terbaik yang sesuai dengan kepribadian Anda.';

        $s1t = $profil->home_step1_title ?? 'Konsultasi & Estimasi';
        $s1d = $profil->home_step1_desc  ?? 'Hubungi tim admin kami untuk berkonsultasi mengenai desain & biaya.';
        $s2t = $profil->home_step2_title ?? 'Pilihan Wrapping';
        $s2d = $profil->home_step2_desc  ?? 'Tentukan pilihan material, merk premium, dan warna sesuai keinginan Anda.';
        $s3t = $profil->home_step3_title ?? 'Pengerjaan Rapi';
        $s3d = $profil->home_step3_desc  ?? 'Bawa mobil Anda ke workshop kami dan serahkan pengerjaan pada ahlinya.';

        $waNumber = preg_replace('/[^0-9]/', '', $profil->nomor_telepon ?? '628123456789');
    @endphp

    <!-- 1. Hero Section -->
    <section class="relative min-h-screen pt-32 pb-20 px-6 sm:px-10 lg:px-16 flex items-center overflow-hidden bg-cover bg-center lg:bg-[right_center] bg-no-repeat" style="background-image: url('{{ asset('images/hero_car.png') }}');">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/85 to-black lg:bg-gradient-to-r lg:from-[#0a0a0a] lg:via-[#0a0a0a]/75 lg:to-transparent z-0"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#f2994a]/10 rounded-full blur-[120px] pointer-events-none z-10"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#e28a44]/5 rounded-full blur-[100px] pointer-events-none z-10"></div>

        <div class="max-w-7xl mx-auto w-full relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-8 space-y-8" data-aos="fade-right" data-aos-duration="1200">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 bg-[#f2994a]/10 border border-[#f2994a]/20 px-4 py-2 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse"></span>
                        <span class="text-xs font-bold text-[#f2994a] tracking-wider uppercase">{{ $badge }}</span>
                    </div>

                    <!-- Heading -->
                    <div class="space-y-4">
                        <h1 class="text-4xl sm:text-5xl lg:text-6.5xl font-extrabold text-white leading-[1.1] tracking-tight">
                            {{ $title1 }} <br>
                            <span class="text-gradient">{{ $title2 }}</span>
                        </h1>
                        <p class="text-gray-300 text-sm sm:text-base md:text-lg leading-relaxed max-w-xl">
                            {{ $subtitle }}
                        </p>
                    </div>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-2">
                        <a href="https://wa.me/{{ $waNumber }}"
                           class="btn-premium px-8 py-4 rounded-xl font-bold text-xs sm:text-sm uppercase tracking-widest text-black flex items-center gap-3 transition-all hover:scale-105 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                            Pesan Sekarang <i class="ph-bold ph-arrow-right text-base"></i>
                        </a>
                        <a href="#mahakarya"
                           class="px-8 py-4 rounded-xl border border-white/20 text-white font-bold text-xs sm:text-sm uppercase tracking-widest bg-white/5 hover:bg-white/10 hover:border-white/40 transition-all">
                            Lihat Portofolio
                        </a>
                    </div>

                    <!-- Micro Stats -->
                    <div class="flex items-center gap-12 pt-8 border-t border-white/5 max-w-md">
                        <div>
                            <h3 class="text-3xl font-extrabold text-white">{{ $stat1v }}</h3>
                            <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">{{ $stat1l }}</p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <h3 class="text-3xl font-extrabold text-[#f2994a]">{{ $stat2v }}</h3>
                            <p class="text-gray-400 text-xs mt-1 uppercase tracking-wider">{{ $stat2l }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Keunggulan Section -->
    <section class="py-24 bg-[#080808] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="tentang">
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-[350px] h-[350px] bg-[#e28a44]/5 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block mb-3">Keunggulan Layanan</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Mengapa Memilih <span class="relative inline-block pb-2">Wapping<span class="absolute bottom-0 left-0 w-full h-[3px] bg-[#f2994a] rounded-full"></span></span>?
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Card 1: Wide -->
                <div class="lg:col-span-7 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up">
                    <div class="space-y-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                            <i class="ph-bold ph-shield-check"></i>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k1t }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed max-w-xl">{!! $k1d !!}</p>
                        </div>
                    </div>
                    <div class="pt-8">
                        <a href="{{ route('katalog.user') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group-hover:translate-x-1">
                            Selengkapnya <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="lg:col-span-5 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="space-y-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                            <i class="ph-bold ph-seal-check"></i>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k2t }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">{{ $k2d }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="lg:col-span-5 bg-[#121212] border border-white/5 rounded-3xl p-8 sm:p-10 flex flex-col justify-between hover:border-[#f2994a]/30 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="space-y-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center text-2xl text-[#f2994a] group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                            <i class="ph-bold ph-clock"></i>
                        </div>
                        <div class="space-y-3">
                            <h3 class="text-xl font-extrabold text-white group-hover:text-[#f2994a] transition-all">{{ $k3t }}</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">{{ $k3d }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Orange Emphasized -->
                <div class="lg:col-span-7 bg-gradient-to-br from-[#e28a44] to-[#f2994a] rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8 shadow-lg shadow-[#f2994a]/10 hover:shadow-[#f2994a]/20 hover:scale-[1.01] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="space-y-6 md:max-w-md">
                        <div class="space-y-3">
                            <h3 class="text-2xl font-black text-black leading-tight">{{ $k4t }}</h3>
                            <p class="text-black/80 text-sm leading-relaxed">{{ $k4d }}</p>
                        </div>
                        <div class="pt-2">
                            <a href="https://wa.me/{{ $waNumber }}"
                               class="inline-block bg-black text-[#f2994a] hover:bg-black/90 hover:text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                                Cek Syarat & Ketentuan
                            </a>
                        </div>
                    </div>
                    <div class="flex-shrink-0 bg-black/5 rounded-3xl p-6 border border-black/10 flex items-center justify-center self-center">
                        <i class="ph-bold ph-award text-6xl text-black"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Portofolio Section -->
    <section class="py-24 bg-[#0a0a0a] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="mahakarya">
        <div class="absolute bottom-0 right-0 w-[450px] h-[450px] bg-[#f2994a]/5 rounded-full blur-[110px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16" data-aos="fade-up">
                <div class="space-y-3">
                    <span class="text-xs font-bold text-[#f2994a] tracking-[0.25em] uppercase block">Showcase Portofolio</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Mahakarya Kami</h2>
                    <p class="text-gray-500 text-sm max-w-lg">Berikut adalah hasil pengerjaan car wrapping premium dari tim ahli profesional kami.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('galeri.user') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-[#f2994a] hover:text-[#e28a44] transition-all group">
                        Lihat Semua <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up">
                    <div class="relative h-64 overflow-hidden">
                        <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">Varian Favorit</span>
                        <img src="{{ asset('images/tesla_model_s.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Tesla Model S Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Tesla Model S</h3>
                        <p class="text-gray-400 text-sm">Luxury Matte Grey / Blue</p>
                    </div>
                </div>

                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-64 overflow-hidden">
                        <span class="absolute top-4 left-4 z-20 bg-[#f2994a]/95 text-black font-extrabold text-[10px] uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-md">Sangat Direkomendasikan</span>
                        <img src="{{ asset('images/range_rover.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Range Rover Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Range Rover Sport</h3>
                        <p class="text-gray-400 text-sm">Satin Liquid Silver Wrap</p>
                    </div>
                </div>

                <div class="bg-[#121212] border border-white/5 rounded-3xl overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-500 shadow-md" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ asset('images/ferrari_f8.png') }}" width="400" height="256" class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-700 ease-out" alt="Ferrari F8 Wrapping">
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-lg font-bold text-white group-hover:text-[#f2994a] transition-all">Ferrari F8</h3>
                        <p class="text-gray-400 text-sm">Satin Metallic Gold Yellow</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. CTA + Langkah Mudah Section -->
    <section class="py-24 bg-[#080808] px-6 sm:px-10 lg:px-16 relative overflow-hidden" id="testimoni">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-[#f2994a]/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="bg-[#121212] border border-white/5 rounded-[40px] p-8 sm:p-12 lg:p-16" data-aos="zoom-in" data-aos-duration="1200">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                    <!-- Left: CTA Text -->
                    <div class="lg:col-span-7 space-y-6">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-[1.15] tracking-tight">
                            {{ $ctaTitle }}
                        </h2>
                        <p class="text-gray-400 text-sm sm:text-base leading-relaxed max-w-xl">
                            {{ $ctaSubtitle }}
                        </p>
                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-4">
                            <a href="https://wa.me/{{ $waNumber }}"
                               class="btn-premium px-8 py-4 rounded-xl font-bold text-xs sm:text-sm uppercase tracking-wider text-black flex items-center gap-3 transition-all hover:scale-105 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                                <i class="ph-bold ph-whatsapp-logo text-lg"></i> Hubungi WhatsApp
                            </a>
                            <a href="{{ route('katalog.user') }}"
                               class="px-8 py-4 rounded-xl border border-white/10 text-white font-bold text-xs sm:text-sm uppercase tracking-wider bg-white/5 hover:bg-white/10 hover:border-white/30 transition-all">
                                Pelajari Prosedur
                            </a>
                        </div>
                    </div>

                    <!-- Right: Steps -->
                    <div class="lg:col-span-5">
                        <div class="bg-[#181818] border border-white/5 rounded-3xl p-8 space-y-6 shadow-xl relative overflow-hidden group hover:border-[#f2994a]/25 transition-all">
                            <div class="flex justify-between items-center pb-4 border-b border-white/5">
                                <h4 class="text-xs font-bold text-[#f2994a] tracking-widest uppercase">Langkah Mudah</h4>
                                <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Fast Process</span>
                            </div>
                            <div class="space-y-6">
                                @foreach([
                                    ['no'=>1, 'title'=>$s1t, 'desc'=>$s1d],
                                    ['no'=>2, 'title'=>$s2t, 'desc'=>$s2d],
                                    ['no'=>3, 'title'=>$s3t, 'desc'=>$s3d],
                                ] as $step)
                                <div class="flex items-start gap-4">
                                    <div class="w-8 h-8 rounded-full bg-[#f2994a]/10 border border-[#f2994a]/30 flex items-center justify-center text-xs font-extrabold text-[#f2994a] flex-shrink-0 group-hover:bg-[#f2994a] group-hover:text-black transition-all duration-300">
                                        {{ $step['no'] }}
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-white">{{ $step['title'] }}</h5>
                                        <p class="text-gray-500 text-xs mt-1 leading-relaxed">{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
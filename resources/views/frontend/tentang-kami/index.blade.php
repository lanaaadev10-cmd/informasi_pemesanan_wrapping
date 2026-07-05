@extends('layouts.tampilan_utama')

@section('title', 'Tentang Kami')

@section('content')
    @php
        $accentColor = '#f2994a';
        $showHistory = true;
        $showValues = true;
        $showTeam = true;
        $heroTitle = 'Precision in Every Layer';
        $heroDesc = 'Satu pilihan terbaik untuk menjaga kendaraan Anda tetap berkilau dan melindunginya dari goresan, jamur, serta kotoran jalanan demi performa yang selalu cemerlang.';
        $teamMembers = [
            ['nama' => 'Adrian Wijaya', 'posisi' => 'Master Wrapper', 'foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=600&auto=format&fit=crop'],
            ['nama' => 'Siska Pratama', 'posisi' => 'Lead Designer', 'foto' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop'],
            ['nama' => 'Budi Santoso', 'posisi' => 'Detailing Specialist', 'foto' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop'],
            ['nama' => 'Kevin Rahardja', 'posisi' => 'Operation Manager', 'foto' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=600&auto=format&fit=crop'],
        ];
    @endphp

    <style>
        :root {
            --accent-color: {{ $accentColor }};
        }
        .accent-color { color: var(--accent-color); }
        .accent-bg { background-color: var(--accent-color); }
        .accent-border { border-color: var(--accent-color); }
        .text-glow {
            text-shadow: 0 0 10px rgba(242, 153, 74, 0.3);
        }
    </style>

    <!-- HERO SECTION (Full Cinematic Banner) -->
    <div class="relative w-full h-[50vh] sm:h-[60vh] md:h-[70vh] flex items-center justify-center overflow-hidden rounded-[32px] sm:rounded-[48px] {{ auth()->check() ? 'mt-4' : '-mt-24 sm:-mt-32' }}">
       <!-- Background Image with studio lights -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover object-center" alt="Premium Wrapping Car">
            <!-- Dark Studio Overlays -->
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/40 to-[#0a0a0a]"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>  

        <!-- Hero Content -->
        <div class="relative z-10 text-center max-w-4xl mx-auto px-6 space-y-6" data-aos="fade-up">
            <span class="text-xs uppercase font-extrabold tracking-[0.3em] text-[#f2994a] bg-[#f2994a]/10 px-4 py-2 rounded-full border border-[#f2994a]/20">
                Tentang Kami
            </span>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
                {!! nl2br(e($heroTitle)) !!}
            </h1>
            <p class="text-gray-300 text-sm sm:text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                {{ $heroDesc }}
            </p>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-16 sm:py-24 space-y-24 sm:space-y-32 relative overflow-hidden">
        
        <!-- Ambient Glowing Core -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] rounded-full blur-[120px] pointer-events-none z-0" style="background-color: color-mix(in srgb, var(--accent-color) 5%, transparent);"></div>

        <!-- SEJARAH SECTION (Satu Dekade Dedikasi) -->
        @if($showHistory)
            <div class="z-10 relative" data-aos="fade-up">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                    <!-- Left: Description -->
                    <div class="space-y-6 lg:col-span-5">
                        <span class="text-xs uppercase font-extrabold tracking-widest text-[#f2994a]">Sejarah Kami</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                            Satu Dekade Dedikasi pada Perfeksi.
                        </h2>
                        <div class="text-gray-400 space-y-4 leading-relaxed text-sm sm:text-base">
                            <p>
                                Sejak didirikan pada tahun 2014, Wrapping Mobil telah menjadi pioneer dalam teknologi stiker kendaraan premium. Dari garasi kecil, kami kini melayani ribuan pelanggan dengan komitmen yang tak pernah pudar terhadap detail dan kepuasan pelanggan.
                            </p>
                            <p>
                                Kini, kami bangga menjadi workshop wrapping terpilih yang dipercaya untuk memproteksi dan mempercantik kualitas kendaraan mewah dari berbagai merek. Setiap proyek adalah karya seni yang kami kerjakan dengan ketelitian tertinggi.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Asymmetrical Grid from mockup -->
                    <div class="grid grid-cols-12 gap-4 lg:col-span-7">
                        <div class="col-span-7 flex flex-col gap-4">
                            <!-- Shop photo -->
                            <div class="rounded-[24px] overflow-hidden shadow-2xl border border-white/5 h-48 sm:h-56 relative group">
                                <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=800&auto=format&fit=crop" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                     alt="Detailing Shop">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a]/80 via-transparent to-transparent"></div>
                            </div>
                            <!-- Stats card -->
                            <div class="bg-[#121212]/90 border border-white/5 rounded-[24px] p-6 sm:p-8 flex flex-col justify-center h-36 sm:h-40 relative overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-300">
                                <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-500 bg-[#f2994a]/5"></div>
                                <span class="text-4xl sm:text-5xl font-extrabold text-[#f2994a] tracking-tight">500+</span>
                                <span class="text-xs uppercase font-bold tracking-wider text-gray-400 mt-2">Project Selesai</span>
                            </div>
                        </div>
                        
                        <!-- 10th Anniversary vertical card -->
                        <div class="col-span-5 bg-[#121212]/90 border border-white/5 rounded-[24px] overflow-hidden relative group hover:border-[#f2994a]/30 transition-all duration-300 flex flex-col justify-between h-[360px] sm:h-[416px]">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent z-10"></div>
                            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=800&auto=format&fit=crop" 
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 alt="Supercar">
                            <div class="relative z-20 p-6 flex flex-col justify-between h-full">
                                <div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-xl py-2 px-3 self-start text-[9px] font-extrabold uppercase tracking-widest text-[#f2994a]">
                                    Anniversary
                                </div>
                                <div>
                                    <span class="text-4xl font-extrabold text-[#f2994a] tracking-tight block">10th</span>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-gray-300 mt-1 block">Dedikasi & Inovasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISI & MISI SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 z-10 relative" data-aos="fade-up">
            <!-- VISI -->
            <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group">
                <div class="space-y-6">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 bg-[#f2994a]/10 border border-[#f2994a]/20">
                        <i class="ph-bold ph-eye text-2xl text-[#f2994a]"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Visi Kami</h3>
                    <div class="text-gray-400 text-sm sm:text-base leading-relaxed prose prose-invert max-w-none">
                        <p>Menjadi studio wrapping terdepan yang dikenal dengan kualitas premium dan inovasi berkelanjutan dalam industri modifikasi estetika kendaraan.</p>
                    </div>
                </div>
            </div>

            <!-- MISI -->
            <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group">
                <div class="space-y-6">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 bg-[#f2994a]/10 border border-[#f2994a]/20">
                        <i class="ph-bold ph-target text-2xl text-[#f2994a]"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Misi Kami</h3>
                    <div class="text-gray-400 text-sm sm:text-base leading-relaxed prose prose-invert max-w-none">
                        <p>Memberikan solusi wrapping berkualitas tinggi dengan menggunakan material premium dunia, teknik instalasi presisi, dan komitmen layanan pelanggan yang tiada tanding.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- VALUES/KOMITMEN SECTION (Nilai yang Kami Junjung) -->
        @if($showValues)
            <div class="space-y-12 z-10 relative" data-aos="fade-up">
                <div class="text-center space-y-4 max-w-2xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                        Nilai yang Kami Junjung
                    </h2>
                    <div class="w-16 h-1 bg-[#f2994a] mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Value 1: Presisi -->
                    <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                                <i class="ph-bold ph-target text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-white text-xl">Presisi</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Setiap sudut, lekukan, dan detail kendaraan ditangani dengan tingkat presisi ekstrem oleh teknisi ahli berlisensi resmi.
                            </p>
                        </div>
                    </div>

                    <!-- Value 2: Integritas -->
                    <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                                <i class="ph-bold ph-shield-check text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-white text-xl">Integritas</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Kami berkomitmen terhadap kejujuran dengan selalu menggunakan produk material premium yang terjamin keasliannya dan bergaransi resmi.
                            </p>
                        </div>
                    </div>

                    <!-- Value 3: Eksklusivitas -->
                    <div class="bg-[#121212]/40 border border-white/5 p-8 sm:p-10 rounded-[32px] hover:border-[#f2994a]/30 transition-all duration-300 group relative overflow-hidden shadow-lg">
                        <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full blur-xl group-hover:blur-2xl transition-all duration-500 bg-[#f2994a]/5"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-[#f2994a]/10 border border-[#f2994a]/20 text-[#f2994a]">
                                <i class="ph-bold ph-crown text-2xl"></i>
                            </div>
                            <h3 class="font-bold text-white text-xl">Eksklusivitas</h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Setiap proyek adalah maha karya unik. Kami memberikan sentuhan personal demi menciptakan tampilan mewah dan prestisius bagi Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TEAM SECTION (Dibalik Setiap Detail Sempurna) -->
        @if($showTeam)
            <div class="space-y-12 z-10 relative" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-white/5 pb-8">
                    <div class="space-y-3">
                        <span class="text-xs uppercase font-extrabold tracking-widest text-[#f2994a]">Tim Kami</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                            Dibalik Setiap Detail Sempurna.
                        </h2>
                    </div>
                    <p class="text-gray-400 text-sm sm:text-base max-w-xl leading-relaxed">
                        Didukung oleh mekanik bersertifikat dan berdedikasi tinggi yang memastikan setiap pemasangan stiker berjalan dengan sempurna dan presisi.
                    </p>
                </div>

                <!-- Team Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($teamMembers as $member)
                        @php
                            $photoUrl = '';
                            if (isset($member['foto']) && $member['foto']) {
                                if (str_starts_with($member['foto'], 'http')) {
                                    $photoUrl = $member['foto'];
                                } else {
                                    $photoUrl = asset('storage/' . $member['foto']);
                                }
                            } else {
                                $photoUrl = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=600&auto=format&fit=crop';
                            }
                        @endphp
                        <div class="bg-[#121212]/90 border border-white/5 rounded-[24px] overflow-hidden group hover:border-[#f2994a]/30 transition-all duration-300 shadow-lg">
                            <!-- Image Container -->
                            <div class="relative h-64 sm:h-72 overflow-hidden">
                                <img src="{{ $photoUrl }}"
                                     alt="{{ $member['nama'] ?? 'Team Member' }}"
                                     class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-500">
                                <!-- Dark Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                
                                <!-- Hover Info Overlay -->
                                <div class="absolute bottom-0 inset-x-0 p-6 z-20">
                                    <h3 class="text-lg font-bold text-white leading-tight">{{ $member['nama'] ?? 'Team Member' }}</h3>
                                    <p class="text-xs font-semibold text-[#f2994a] uppercase tracking-wider mt-1">
                                        {{ $member['posisi'] ?? 'Professional' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CTA SECTION -->
        <div class="rounded-[32px] p-8 sm:p-12 lg:p-16 text-center space-y-6 z-10 relative overflow-hidden shadow-2xl border border-white/5 hover:border-[#f2994a]/20 transition-all duration-500" 
             style="background: linear-gradient(135deg, rgba(242, 153, 74, 0.08) 0%, rgba(0, 0, 0, 0) 100%), #121212;"
             data-aos="zoom-in">
            <div class="absolute top-0 left-0 w-64 h-64 bg-[#f2994a]/5 blur-[80px] rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Siap Mengubah Tampilan Kendaraan Anda?
                </h3>
                <p class="text-gray-400 text-sm sm:text-base leading-relaxed">
                    Jadikan kendaraan Anda pusat perhatian hari ini. Konsultasikan kebutuhan Anda secara gratis dengan tim kami yang berpengalaman.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                    <a href="https://wa.me/628123456789" 
                       target="_blank"
                       class="inline-flex items-center justify-center px-8 py-4 text-black font-extrabold rounded-2xl hover:opacity-90 transition-all duration-300 shadow-lg group btn-premium bg-gradient-to-r from-[#e28a44] to-[#f2994a] hover:scale-105 active:scale-95">
                        <span>Hubungi Kami Sekarang</span>
                        <i class="ph-bold ph-arrow-right text-base ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('galeri.user') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 font-bold rounded-2xl transition-all duration-300 border border-white/10 hover:bg-white/5 text-white hover:scale-105 active:scale-95">
                        <span>Lihat Portofolio</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

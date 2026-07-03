@extends(auth()->check() ? 'layouts.dashboard_customer' : 'layouts.tampilan_utama')

@section('title', 'Profil Perusahaan')

@section('content')
    @php
        $siteConfig = config('site');
        $profile = $siteConfig['profile'] ?? [];
        $heroBadge = $profile['hero_badge'] ?? 'ABOUT US';
        $heroTitle = $profile['hero_title'] ?? 'Company Profile';
        $heroStatus = $profile['hero_status'] ?? 'Premium Wrap Studio';
        $companyName = $profile['company_name'] ?? 'WAPPING';
        $description = $profile['description'] ?? 'WAPPING adalah studio car wrapping premium yang menggabungkan estetika dan proteksi kelas dunia untuk kendaraan Anda.';
        $statsProjects = $profile['stats_projects'] ?? '98%';
        $statsLabel = $profile['stats_label'] ?? 'Client Satisfaction';
        $heroImage = $profile['hero_image'] ?? 'images/landing/hero_car.png';
        $textureImage = $profile['texture_image'] ?? 'images/landing/master_craft_texture.png';
        $studioMapImage = $profile['studio_map_image'] ?? 'images/landing/studio_network_map.png';
        $locations = $profile['locations'] ?? [];
        $vision = $profile['vision'] ?? 'Menjadi penyedia layanan wrapping dan stiker terpercaya dengan inovasi, kualitas, dan kepuasan pelanggan sebagai prioritas utama.';
        $mission = $profile['mission'] ?? 'Memberikan solusi wrapping dan stiker berkualitas tinggi dengan harga kompetitif, layanan excellent, dan dukungan purna jual terbaik.';
        $history = $profile['history'] ?? 'Didirikan dengan komitmen teguh terhadap kualitas estetika dan perlindungan kendaraan, WAPPING tumbuh menjadi pilihan utama bagi pemilik kendaraan mewah. Dari sebuah bengkel kecil dengan impian besar, kini kami mengoperasikan studio modern dengan standar clean-room berkelas dunia.';
    @endphp

    @if(!auth()->check())
        <!-- Spacer untuk Public View agar tidak tertutup Navbar -->
        <div class="h-28"></div>
    @endif

    <!-- Container Utama -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-12 relative overflow-hidden">
        
        <!-- Ambient Glowing Core di Background -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[250px] bg-[#f2994a]/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <!-- 1. HEADER SECTION -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 z-10 relative">
            <div>
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest font-mono">{{ $heroBadge }}</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-1 bg-gradient-to-r from-white via-white to-gray-500 bg-clip-text text-transparent">
                    {{ $heroTitle }}
                </h1>
            </div>
            <!-- Decorative Live Status Indicator -->
            <div class="flex items-center gap-2.5 bg-white/[0.02] border border-white/5 px-4 py-2 rounded-2xl">
                <span class="w-2 h-2 rounded-full bg-[#f2994a] animate-pulse"></span>
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $heroStatus }}</span>
            </div>
        </div>

        <!-- 2. HERO ROW (Main Banner + Stats + Master Craft) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 z-10 relative">
            
            <!-- Left Banner: The Art of Precision (lg:col-span-2) -->
            <div class="lg:col-span-2 relative rounded-[32px] overflow-hidden h-[320px] sm:h-[380px] lg:h-[420px] group shadow-[0_15px_35px_rgba(0,0,0,0.6)] border border-white/5">
                <!-- Background Image -->
                <img src="{{ asset($heroImage) }}" 
                     width="800" height="420"
                     class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-[1.03] transition-transform duration-1000 ease-out z-0" 
                     alt="{{ $heroTitle }}">
                
                <!-- Premium Dark Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-10"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-transparent to-transparent z-10"></div>

                <!-- Content Area -->
                <div class="absolute bottom-0 inset-x-0 p-8 sm:p-10 z-20 space-y-3.5">
                    <span class="bg-[#f2994a]/10 border border-[#f2994a]/20 px-3.5 py-1.5 rounded-full text-[9px] font-extrabold text-[#f2994a] uppercase tracking-widest inline-block">
                        {{ strtoupper($companyName) }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight tracking-tight max-w-xl">
                        The Art of Precision
                    </h2>
                    <p class="text-gray-300 text-xs sm:text-sm font-medium max-w-lg leading-relaxed opacity-90">
                        {{ $description }}
                    </p>
                </div>
            </div>

            <!-- Right Column: Stats & Texture Showcase -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                
                <!-- Card 1: 98% Client Satisfaction -->
                <div class="bg-white/[0.01] border border-white/5 p-6 sm:p-8 rounded-[32px] flex flex-col justify-between h-[198px] hover:border-[#f2994a]/25 transition-all duration-300 group relative overflow-hidden shadow-lg">
                    <!-- Ambient Glow Ball inside card -->
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-[#f2994a]/5 rounded-full blur-2xl group-hover:bg-[#f2994a]/10 transition-all duration-500 pointer-events-none"></div>

                    <div class="space-y-1">
                        <span class="text-4xl sm:text-5xl font-black text-[#f2994a] tracking-tight group-hover:scale-105 inline-block transition-transform duration-300">
                            {{ $statsProjects }}
                        </span>
                        <span class="block text-[9px] font-extrabold uppercase tracking-widest text-gray-500 mt-1">
                            {{ $statsLabel }}
                        </span>
                    </div>

                    <p class="text-gray-400 text-xs leading-relaxed font-light mt-4">
                        Over 5,000 premium vehicles transformed with obsessive attention to every single detail.
                    </p>
                </div>

                <!-- Card 2: Master Craft close-up -->
                <div class="relative rounded-[32px] overflow-hidden h-[198px] group shadow-lg border border-white/5">
                    <!-- Texture Image -->
                    <img src="{{ asset($textureImage) }}" 
                         width="400" height="198"
                         class="absolute inset-0 w-full h-full object-cover transform scale-100 group-hover:scale-[1.04] transition-transform duration-700 ease-out z-0" 
                         alt="Master Craft">
                    
                    <!-- Texture Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent z-10"></div>
                    
                    <!-- Text Overlay -->
                    <span class="absolute bottom-6 left-6 text-sm font-bold text-white uppercase tracking-widest z-20 bg-black/35 backdrop-blur-sm border border-white/5 px-4 py-2 rounded-full">
                        Master Craft
                    </span>
                </div>

            </div>

        </div>

        <!-- 3. OUR NARRATIVE SECTION (Legacy of Excellence) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 pt-14 border-t border-white/5 items-center z-10 relative">
            
            <!-- Left Side: Core Narrative Text -->
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <span class="text-[#f2994a] text-[10px] font-bold uppercase tracking-widest block font-mono">OUR NARRATIVE</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight tracking-tight mt-1">
                        Legacy of Excellence
                    </h2>
                </div>
                
                <div class="space-y-4">
                    <p class="text-gray-400 text-sm leading-relaxed font-light">
                            {{ $profile['narrative_paragraphs'][0] ?? '' }}
                        </p>
                        <p class="text-gray-400 text-sm leading-relaxed font-light">
                            {{ $profile['narrative_paragraphs'][1] ?? '' }}
                        </p>
                </div>

                <!-- Call to action: Opens Vision & Mission Modal -->
                <button onclick="openHistoryModal()" 
                        class="inline-flex items-center gap-2.5 bg-white/[0.02] border border-white/10 px-6 py-3 rounded-2xl text-xs font-bold text-white hover:bg-white/5 hover:border-white/20 transition-all hover:scale-[1.02] active:scale-98 shadow-sm">
                    Read Full History <i class="ph ph-arrow-up-right text-xs"></i>
                </button>
            </div>

            <!-- Right Side: Staggered Asymmetric Grid -->
            <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                
                <!-- Column 1 -->
                <div class="space-y-4">
                    <!-- Photo 1: Clean detailing workshop / car wrapping -->
                    <div class="relative aspect-square rounded-2xl overflow-hidden border border-white/10 group shadow-lg bg-[#0d0d0d]">
                        <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?q=80&w=400&auto=format&fit=crop" 
                             width="300" height="300"
                             class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-all duration-500" 
                             alt="Clean Workshop">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    
                    <!-- Badge Card 1 -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-5 hover:border-[#f2994a]/20 transition-all duration-300 group shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-[#f2994a]/10 flex items-center justify-center text-[#f2994a] mb-3 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-shield-check text-base"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white mb-1 uppercase tracking-wider">Globus Certification</h4>
                        <p class="text-[10px] text-gray-500 leading-normal font-light">Certified installers for XPEL, SunTek, and STEK protective films.</p>
                    </div>
                </div>

                <!-- Column 2 (Staggered Downward) -->
                <div class="space-y-4 pt-8">
                    <!-- Badge Card 2 -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-5 hover:border-[#f2994a]/20 transition-all duration-300 group shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-[#f2994a]/10 flex items-center justify-center text-[#f2994a] mb-3 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-wind text-base"></i>
                        </div>
                        <h4 class="text-xs font-bold text-white mb-1 uppercase tracking-wider">Clean Room Protocol</h4>
                        <p class="text-[10px] text-gray-500 leading-normal font-light">Multi-stage filtration environment for dust-defect finishes.</p>
                    </div>
                    
                    <!-- Photo 2: Luxury Car lineup -->
                    <div class="relative aspect-square rounded-2xl overflow-hidden border border-white/10 group shadow-lg bg-[#0d0d0d]">
                        <img src="https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=400&auto=format&fit=crop" 
                             width="300" height="300"
                             class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-all duration-500" 
                             alt="Luxury Cars">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                </div>

            </div>

        </div>

        <!-- 4. THE THREE PILLARS SECTION -->
        <div class="pt-14 border-t border-white/5 space-y-10 z-10 relative">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">The Three Pillars</h3>
                <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-[#f2994a] to-transparent mx-auto mt-2"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Pillar 1: Precision Engineering -->
                <div class="bg-white/[0.01] border border-white/5 p-8 rounded-3xl hover:border-[#f2994a]/25 hover:bg-white/[0.02] transition-all duration-300 group shadow-md flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-bold ph-compass text-xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-white uppercase tracking-wider">Precision Engineering</h4>
                        <p class="text-gray-400 text-xs sm:text-sm leading-relaxed font-light">
                            Every edge is tucked, every corner is seamless. We utilize CAD-designed templates and surgical application techniques for a factory-level finish.
                        </p>
                    </div>
                </div>

                <!-- Pillar 2: Bespoke Materials -->
                <div class="bg-white/[0.01] border border-white/5 p-8 rounded-3xl hover:border-[#f2994a]/25 hover:bg-white/[0.02] transition-all duration-300 group shadow-md flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-bold ph-gem text-xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-white uppercase tracking-wider">Bespoke Materials</h4>
                        <p class="text-gray-400 text-xs sm:text-sm leading-relaxed font-light">
                            We source only the highest grade polymers and adhesives from world-leading suppliers, ensuring longevity and paint protection without compromise.
                        </p>
                    </div>
                </div>

                <!-- Pillar 3: White-Glove Service -->
                <div class="bg-white/[0.01] border border-white/5 p-8 rounded-3xl hover:border-[#f2994a]/25 hover:bg-white/[0.02] transition-all duration-300 group shadow-md flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] group-hover:scale-110 transition-transform duration-300">
                            <i class="ph-bold ph-sparkles text-xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-white uppercase tracking-wider">White-Glove Service</h4>
                        <p class="text-gray-400 text-xs sm:text-sm leading-relaxed font-light">
                            From initial consultation to final inspection, our process is designed for transparency and peace of mind. We provide full digital documentation of every project.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- 5. GLOBAL STUDIO NETWORK SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-14 border-t border-white/5 z-10 relative">
            
            <!-- Left Info Panel (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-6 flex flex-col justify-between">
                <div class="space-y-3">
                    <span class="text-[#f2994a] text-[10px] font-bold uppercase tracking-widest block font-mono">GLOBAL NETWORK</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Global Studio Network
                    </h3>
                    <p class="text-gray-400 text-xs sm:text-sm font-light leading-relaxed">
                        Our signature quality is available across major hubs. Visit any of our studios for a private viewing of our material catalogs and current projects.
                    </p>
                </div>

                <!-- Studio Locations Stack -->
                <div class="space-y-4 pt-4 sm:pt-0">
                    <!-- Location 1: Los Angeles HQ -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-5 flex items-start gap-4 hover:border-[#f2994a]/20 transition-all group shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] mt-0.5 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-map-pin text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Los Angeles HQ</h4>
                            <p class="text-[10px] text-gray-500 mt-1 font-light">{{ $locations[0]['address'] ?? '7821 Sunset Blvd, CA' }}</p>
                        </div>
                    </div>

                    <!-- Location 2: Dubai Creative Hub -->
                    <div class="bg-white/[0.01] border border-white/5 rounded-2xl p-5 flex items-start gap-4 hover:border-[#f2994a]/20 transition-all group shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-[#f2994a]/5 flex items-center justify-center text-[#f2994a] mt-0.5 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-map-pin text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">{{ $locations[1]['title'] ?? 'Dubai Creative Hub' }}</h4>
                            <p class="text-[10px] text-gray-500 mt-1 font-light">{{ $locations[1]['address'] ?? '2042 Meydan Rd, Meydan City, Dubai' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual Panel: Map (lg:col-span-7) -->
            <div class="lg:col-span-7">
                <div class="bg-[#0b0b0b] border border-white/5 rounded-[32px] overflow-hidden aspect-[16/10] relative group shadow-[0_15px_30px_rgba(0,0,0,0.5)]">
                    <!-- Glowing map image -->
                    <img src="{{ asset($studioMapImage) }}" 
                         width="600" height="375"
                         class="w-full h-full object-cover transform scale-100 group-hover:scale-[1.02] transition-transform duration-700 z-0" 
                         alt="Studio Network Map">
                    
                    <!-- Soft glass border gloss -->
                    <div class="absolute inset-0 border border-white/10 rounded-[32px] pointer-events-none z-10"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- 6. HISTORY & MISSION POPUP MODAL (HIDDEN BY DEFAULT) -->
    <div id="history-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/85 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
        <!-- Modal Content Box -->
        <div class="bg-[#0f0f0f] border border-white/10 rounded-[32px] max-w-2xl w-full p-8 relative transform scale-95 transition-all duration-300 overflow-y-auto max-h-[90vh] shadow-[0_20px_50px_rgba(0,0,0,0.8)]">
            
            <!-- Close Button -->
            <button onclick="closeHistoryModal()" 
                    class="absolute top-6 right-6 w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 hover:border-white/20 transition-all hover:scale-105 active:scale-95">
                <i class="ph ph-x text-sm"></i>
            </button>

            <!-- Modal Body -->
            <div class="space-y-6">
                <div>
                    <span class="text-[#f2994a] text-[10px] font-bold uppercase tracking-widest block font-mono">OUR NARRATIVE</span>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight mt-1">Corporate History & Vision</h3>
                </div>

                <!-- Vision & Mission -->
                <div class="grid md:grid-cols-2 gap-6 pt-5 border-t border-white/5">
                    <!-- Vision -->
                    <div class="space-y-3 p-5 rounded-2xl bg-white/[0.01] border border-white/5">
                        <div class="flex items-center gap-2 text-[#f2994a]">
                            <i class="ph-bold ph-eye text-lg"></i>
                            <h4 class="text-xs font-bold uppercase tracking-widest">Our Vision</h4>
                        </div>
                        <div class="text-gray-400 text-xs leading-relaxed font-light space-y-2">
                            {!! $vision !!}
                        </div>
                    </div>

                    <!-- Mission -->
                    <div class="space-y-3 p-5 rounded-2xl bg-white/[0.01] border border-white/5">
                        <div class="flex items-center gap-2 text-[#f2994a]">
                            <i class="ph-bold ph-target text-lg"></i>
                            <h4 class="text-xs font-bold uppercase tracking-widest">Our Mission</h4>
                        </div>
                        <div class="text-gray-400 text-xs leading-relaxed font-light space-y-2">
                            {!! $mission !!}
                        </div>
                    </div>
                </div>

                <!-- Long narrative history -->
                <div class="pt-5 border-t border-white/5 space-y-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Perjalanan Kami</h4>
                    <p class="text-gray-400 text-xs leading-relaxed font-light">
                        {{ $history }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripting for interactive modal popup -->
    <script>
        function openHistoryModal() {
            const modal = document.getElementById('history-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
            document.body.style.overflow = 'hidden'; // Lock body scrolling
        }

        function closeHistoryModal() {
            const modal = document.getElementById('history-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.transform').classList.remove('scale-100');
            modal.querySelector('.transform').classList.add('scale-95');
            document.body.style.overflow = 'auto'; // Restore body scrolling
        }

        // Close modal if user clicks outside the modal content box
        document.getElementById('history-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeHistoryModal();
            }
        });
    </script>
@endsection

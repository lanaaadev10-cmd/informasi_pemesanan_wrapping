{{-- ============================================
    BAGIAN: Tim Kami
    Deskripsi: Grid profil tim dan mekanik professional wrapping
============================================ --}}
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

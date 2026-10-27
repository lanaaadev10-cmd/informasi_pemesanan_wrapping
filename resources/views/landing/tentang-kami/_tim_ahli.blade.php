@if($showTeam)
    <div class="space-y-10 z-10 relative" data-aos="fade-up">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-200/80 pb-6">
            <div class="space-y-1.5">
                <span class="text-xs sm:text-sm font-black uppercase tracking-widest text-[#ea580c] block">
                    Keahlian &amp; Sertifikasi
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-montserrat tracking-tight">
                    {{ \App\Helpers\StaticContent::TIM_TITLE }}
                </h2>
            </div>
            <p class="text-slate-500 text-xs sm:text-sm max-w-lg leading-relaxed font-normal">
                {{ \App\Helpers\StaticContent::TIM_DESC }}
            </p>
        </div>

        <!-- Team Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $defaultNames = [
                    'Dantie' => 'Owner & Founder',
                    'Rian'   => 'Admin & Content Creator',
                    'Dimas'  => 'Team Wrapping',
                    'Aris'   => 'Team Wrapping',
                ];
                $nameKeys = array_keys($defaultNames);
                $roleValues = array_values($defaultNames);
            @endphp

            @foreach($teamMembers as $idx => $member)
                @php
                    $photoUrl = '';
                    if (!empty($member['foto'])) {
                        if (str_starts_with($member['foto'], 'http')) {
                            $photoUrl = $member['foto'];
                        } elseif (file_exists(public_path($member['foto']))) {
                            $photoUrl = asset($member['foto']);
                        } elseif (file_exists(public_path('storage/' . $member['foto']))) {
                            $photoUrl = asset('storage/' . $member['foto']);
                        }
                    }

                    $rawName = trim($member['nama'] ?? '');
                    $displayName = (!empty($rawName) && !str_starts_with($rawName, '.') && !str_contains(strtolower($rawName), 'isi nama'))
                        ? $rawName
                        : ($nameKeys[$idx % count($nameKeys)] ?? 'Teknisi Ahli');

                    $displayRole = (!empty($member['posisi']) && !str_starts_with($member['posisi'], '.'))
                        ? $member['posisi']
                        : ($roleValues[$idx % count($roleValues)] ?? 'Team Wrapping');
                @endphp
                <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden group hover:border-orange-300 hover:shadow-md transition-all duration-300 shadow-sm flex flex-col justify-between">
                    <!-- Image Box -->
                    <div class="relative h-64 sm:h-72 overflow-hidden bg-slate-100">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 alt="{{ $displayName }} - {{ $displayRole }}"
                                 class="w-full h-full object-cover transform scale-100 group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 via-transparent to-transparent"></div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 p-6 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-200/80 border border-slate-300/80 flex items-center justify-center mb-3">
                                    <i class="ph-bold ph-user text-2xl text-slate-500"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Info Box -->
                    <div class="p-5 text-center space-y-1.5 bg-white">
                        <h3 class="text-base sm:text-lg font-bold font-montserrat text-slate-900 group-hover:text-[#ea580c] transition-colors leading-snug">
                            {{ $displayName }}
                        </h3>
                        <p class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-orange-50 text-[#ea580c] border border-orange-100/80">
                            {{ $displayRole }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

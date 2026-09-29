{{--
    VIEW: filament.auth.login
    Layout login administrator sesuai referensi:
    - Split-card dengan sisi kiri berwarna orange
    - Teks kontras putih di atas warna orange
    - Pembatas S-curve wave organik (putih membelah orange)
    - Background halaman putih / light
    - Form kanan dengan pill-shaped inputs berkelas
    - Form disubmit via native HTTP POST (@csrf) agar 100% andal di semua browser
--}}
<div class="w-full bg-white rounded-3xl md:rounded-[36px] shadow-[0_25px_70px_rgba(0,0,0,0.09)] border border-gray-100 overflow-hidden flex flex-col md:flex-row relative min-h-[580px] md:min-h-[600px]">

    {{-- ==================== SISI KIRI: ORANGE THEMED BRANDING ==================== --}}
    <div class="relative w-full md:w-5/12 min-h-[220px] md:min-h-full overflow-hidden flex flex-col justify-between p-7 sm:p-10 text-white bg-gradient-to-br from-[#FF6B00] via-[#FF7518] to-[#E05D00]">

        {{-- Subtle Geometric & Glow Accents on Orange --}}
        <div class="absolute -top-16 -left-16 w-56 h-56 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-white/15 via-transparent to-black/10 pointer-events-none"></div>

        {{-- Top Brand Identity --}}
        <div class="relative z-10 flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-white text-[#FF6B00] flex items-center justify-center font-extrabold shadow-lg shadow-black/10">
                <i class="ph-bold ph-steering-wheel text-2xl"></i>
            </div>
            <div>
                <h2 class="font-audiowide text-base sm:text-xl tracking-wider text-white uppercase leading-none drop-shadow-sm">
                    {{ $profil->nama_perusahaan ?? 'Dantie Sticker' }}
                </h2>
                <p class="font-mono text-[9px] sm:text-[10px] uppercase tracking-widest text-white/95 mt-1 font-bold">
                    {{ $profil->dashboard_subtitle ?? 'Car Wrapping & Detailing' }}
                </p>
            </div>
        </div>

        {{-- Bottom Description (Desktop) --}}
        <div class="relative z-10 hidden md:block pt-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/20 border border-white/30 text-[10px] font-montserrat font-bold uppercase tracking-wider text-white backdrop-blur-md mb-4 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                Admin Portal
            </span>
            <h3 class="font-montserrat font-black text-2xl sm:text-3xl text-white leading-tight drop-shadow-sm">
                Pusat Kendali Operasional Wrapping
            </h3>
            <p class="font-questrial text-xs sm:text-sm text-white/90 mt-2.5 leading-relaxed font-medium">
                Akses manajemen antrean booking, stok material, approval pembayaran, dan pelaporan keuangan.
            </p>
        </div>
    </div>

    {{-- ==================== SISI KANAN: FORM LOGIN DENGAN WAVE ==================== --}}
    <div class="relative w-full md:w-7/12 bg-white px-6 py-8 sm:px-12 sm:py-12 md:px-14 md:py-12 flex flex-col justify-center">

        {{-- S-Curve Wave Divider (Desktop Only - White curving into Orange) --}}
        <svg class="hidden md:block absolute top-0 -left-[46px] lg:-left-[60px] h-full w-[48px] lg:w-[62px] text-white pointer-events-none z-20"
             viewBox="0 0 100 100"
             preserveAspectRatio="none"
             fill="currentColor">
            <path d="M 100 0 L 0 0 C 0 25, 95 35, 95 55 C 95 75, 15 85, 15 100 L 100 100 Z" />
        </svg>

        {{-- Form Content Container --}}
        <div class="w-full max-w-md mx-auto relative z-10">

            {{-- Header Greeting --}}
            <div class="mb-7 text-center md:text-left">
                <h1 class="text-3xl sm:text-4xl font-montserrat font-black text-gray-900 tracking-tight">
                    Welcome
                </h1>
                <p class="mt-1.5 text-xs sm:text-sm text-gray-400 font-questrial">
                    Log in to your account to continue
                </p>
            </div>

            {{-- Form Native POST: 100% Teruji & Andal --}}
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                {{-- Input Email --}}
                <div>
                    <div class="relative">
                        <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input
                            type="email"
                            id="admin_email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="admin@wrapping.com"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-full text-xs sm:text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 focus:bg-white focus:border-racing-orange focus:ring-4 focus:ring-racing-orange/15 shadow-sm @error('email') border-red-500 @enderror"
                        >
                    </div>
                    @error('email')
                        <p class="text-[11px] text-red-500 font-medium mt-1.5 pl-4 flex items-center gap-1">
                            <i class="ph-bold ph-warning-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Input Password --}}
                <div x-data="{ showPassword: false }">
                    <div class="relative">
                        <i class="ph-bold ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input
                            x-ref="pwdInput"
                            type="password"
                            id="admin_password"
                            name="password"
                            required
                            placeholder="••••••••••••"
                            class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-full text-xs sm:text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 focus:bg-white focus:border-racing-orange focus:ring-4 focus:ring-racing-orange/15 shadow-sm @error('password') border-red-500 @enderror"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword; $refs.pwdInput.type = showPassword ? 'text' : 'password'"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors p-1"
                            tabindex="-1"
                        >
                            <i class="ph-bold text-base" :class="showPassword ? 'ph-eye-slash' : 'ph-eye'"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-red-500 font-medium mt-1.5 pl-4 flex items-center gap-1">
                            <i class="ph-bold ph-warning-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Options: Remember & Forgot Link --}}
                <div class="flex items-center justify-between px-2 pt-1 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-500 select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember_me"
                            {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-racing-orange focus:ring-racing-orange accent-racing-orange cursor-pointer"
                        >
                        <span class="font-medium text-gray-600">Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}"
                       class="text-gray-400 hover:text-racing-orange transition-colors font-medium">
                        Forgot password?
                    </a>
                </div>

                {{-- Submit Button (Pill-shaped, vibrant brand color) --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 bg-racing-orange hover:bg-racing-orangeDark active:scale-[0.98] text-white font-montserrat font-bold text-xs uppercase tracking-widest rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>LOGIN</span>
                    </button>
                </div>

                {{-- Navigation Back Link --}}
                <div class="text-center pt-2">
                    <p class="text-xs text-gray-500">
                        Bukan administrator?
                        <a href="{{ route('home') }}" class="font-bold text-racing-orange hover:underline ml-1">
                            Kembali ke Website
                        </a>
                    </p>
                </div>

                {{-- Social / Workshop Icons --}}
                <div class="pt-4 border-t border-gray-100 flex items-center justify-center gap-3">
                    @if(!empty($profil->facebook_url))
                        <a href="{{ $profil->facebook_url }}" target="_blank" title="Facebook"
                           class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-racing-orange hover:border-racing-orange hover:bg-orange-50/50 transition-all text-sm">
                            <i class="ph-bold ph-facebook-logo"></i>
                        </a>
                    @endif

                    @if(!empty($profil->instagram_url))
                        <a href="{{ $profil->instagram_url }}" target="_blank" title="Instagram"
                           class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-racing-orange hover:border-racing-orange hover:bg-orange-50/50 transition-all text-sm">
                            <i class="ph-bold ph-instagram-logo"></i>
                        </a>
                    @endif

                    <a href="{{ $profil->whatsapp_link }}" target="_blank" title="WhatsApp Konsultasi"
                       class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-racing-orange hover:border-racing-orange hover:bg-orange-50/50 transition-all text-sm">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </a>

                    @if(!empty($profil->email))
                        <a href="mailto:{{ $profil->email }}" title="Email Workshop"
                           class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-racing-orange hover:border-racing-orange hover:bg-orange-50/50 transition-all text-sm">
                            <i class="ph-bold ph-envelope"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>

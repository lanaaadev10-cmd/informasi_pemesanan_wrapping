<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $profil->meta_title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-black font-['Plus_Jakarta_Sans',sans-serif]">
    {{-- Ambient glow --}}
    <div class="fixed w-[400px] h-[400px] -top-[100px] -right-[100px] rounded-full blur-[100px] pointer-events-none z-0 bg-[#f2994a]/5"></div>
    <div class="fixed w-[300px] h-[300px] -bottom-[80px] -left-[80px] rounded-full blur-[100px] pointer-events-none z-0 bg-[#f2994a]/5"></div>

    {{-- ====== MOBILE LAYOUT (full screen form) ====== --}}
    <div class="relative z-10 md:hidden min-h-screen flex flex-col px-6 py-10">
        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-[#f2994a]">
                    <i class="ph-bold ph-sketch-logo text-base"></i>
                </div>
                <span class="font-extrabold text-sm text-white uppercase tracking-wider">{{ $profil->nama_perusahaan ?? 'Wrapping' }}</span>
            </a>
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                <i class="ph ph-x text-lg"></i>
            </a>
        </div>

        {{-- Form area --}}
        <div class="flex-1 flex flex-col justify-center max-w-sm mx-auto w-full">
            {{-- Title --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang</h1>
                <p class="text-sm text-gray-500">Masuk untuk melanjutkan</p>
            </div>

            <x-auth-session-status class="mb-4 text-xs font-semibold text-green-500" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Email</label>
                    <div class="relative">
                        <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full py-3.5 px-4 pl-[46px] bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="email@kamu.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-[#f2994a] font-semibold hover:underline">Lupa?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="password" name="password" id="m_login_pwd" required
                               class="w-full py-3.5 px-4 pl-[46px] pr-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="••••••••">
                        <button type="button" onclick="togglePwd('m_login_pwd', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <i class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500" />
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="remember" id="m_remember"
                           class="rounded bg-[#161616] border-white/10 text-[#f2994a] focus:ring-[#f2994a] w-4 h-4">
                    <label for="m_remember" class="text-xs text-gray-500">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#f2994a] text-black font-extrabold text-xs tracking-widest uppercase border-0 rounded-xl cursor-pointer transition-all hover:bg-[#e28a44] active:scale-[0.98]">
                        Masuk Sekarang →
                    </button>
                </div>

                {{-- Register link --}}
                <p class="text-center text-sm text-gray-500 pt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Daftar</a>
                </p>
            </form>
        </div>
    </div>

    {{-- ====== DESKTOP LAYOUT (2 column) ====== --}}
    <div class="hidden md:flex min-h-screen items-center justify-center p-8 lg:p-12 relative z-10">
        <div class="max-w-5xl w-full bg-[#111111] rounded-[32px] overflow-hidden border border-white/5 flex shadow-2xl">
            {{-- Left: Form --}}
            <div class="w-1/2 p-10 lg:p-14 bg-[#0c0c0c] flex flex-col justify-center">
                <div class="space-y-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-[10px] text-gray-500 hover:text-[#f2994a] transition-colors font-medium">
                        <i class="ph ph-arrow-left text-sm"></i> {{ $profil->cta_kembali ?? 'Kembali ke Beranda' }}
                    </a>
                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-white tracking-tight">{{ $profil->auth_selamat_datang ?? 'Selamat Datang Kembali' }}</h2>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Masuk ke akun Anda untuk mengelola pemesanan layanan premium kami.</p>
                    </div>

                    <x-auth-session-status class="text-xs font-semibold text-green-500" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_email ?? 'Email' }}</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="w-full pl-11 pr-4 py-3.5 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="email@premiumwrap.id">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[9px] font-bold text-[#f2994a] hover:underline uppercase tracking-wider">{{ $profil->form_lupa_sandi ?? 'Lupa Sandi?' }}</a>
                                @endif
                            </div>
                            <div class="relative">
                                <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="password" name="password" id="d_login_pwd" required
                                       class="w-full pl-11 pr-12 py-3.5 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="••••••••">
                                <button type="button" onclick="togglePwd('d_login_pwd', this)"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                                    <i class="ph ph-eye text-lg"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <input type="checkbox" name="remember" id="d_remember_me" class="rounded bg-[#161616] border-white/5 text-[#f2994a] focus:ring-[#f2994a]">
                            <label for="d_remember_me" class="text-[10px] text-gray-400 font-light">{{ $profil->form_ingat_saya ?? 'Ingat saya di perangkat ini' }}</label>
                        </div>
                        <button type="submit" class="w-full py-4 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all flex items-center justify-center gap-2 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                            {{ $profil->cta_masuk_sekarang ?? 'Masuk Sekarang' }} <i class="ph-bold ph-arrow-right text-sm"></i>
                        </button>
                        <p class="text-center text-[10px] text-gray-400 font-medium pt-2">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Daftar di sini</a>
                        </p>
                    </form>
                </div>
            </div>
            {{-- Right: Visual --}}
            <div class="w-1/2 flex flex-col justify-between text-white relative min-h-[620px] bg-cover bg-center" style="background-image: url('{{ asset('images/tesla_model_s.png') }}');">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0a0a0a]/75 via-[#0a0a0a]/85 to-[#0a0a0a] z-0"></div>
                <div class="z-10 p-10 lg:p-14">
                    <a href="/" class="inline-flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-widest text-[#f2994a] uppercase">{{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }}</span>
                    </a>
                </div>
                <div class="z-10 space-y-6 p-10 lg:p-14">
                    <p class="text-gray-300 text-sm font-light leading-relaxed max-w-sm">Luxury is in the details. Protect your vehicle with the ultimate matte or glossy shield.</p>
                    <div class="border border-[#f2994a]/30 rounded-2xl p-5 bg-[#0a0a0a]/40 backdrop-blur-sm">
                        <p class="text-xs text-gray-200 leading-relaxed font-light italic">"Layanan car wrapping terbaik yang pernah saya temukan. Hasil kilapnya seperti cermin."</p>
                        <span class="text-[10px] text-[#f2994a] font-bold block mt-3 uppercase tracking-wider">— Siska A., Luxury Sedan Owner</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePwd(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ph ph-eye-slash text-base';
            } else {
                input.type = 'password';
                icon.className = 'ph ph-eye text-base';
            }
        }
    </script>
</body>
</html>

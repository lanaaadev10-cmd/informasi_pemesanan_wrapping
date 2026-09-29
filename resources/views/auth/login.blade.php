<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - {{ $profil->meta_title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-racing-black font-['Plus_Jakarta_Sans',sans-serif]">

    {{-- Ambient Glow Decoration --}}
    <div class="glow-orb glow-orb-tr"></div>
    <div class="glow-orb glow-orb-bl"></div>

    {{-- ====== MOBILE LAYOUT (tampilan penuh, single kolom) ====== --}}
    <div class="relative z-10 md:hidden min-h-screen flex flex-col px-6 py-10">

        {{-- Top bar: Logo + tombol kembali --}}
        <div class="flex items-center justify-between mb-10">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-racing-orangeLight">
                    <i class="ph-bold ph-sketch-logo text-base"></i>
                </div>
                <span class="font-extrabold text-sm text-white uppercase tracking-wider">
                    {{ $profil->nama_perusahaan ?? 'Wrapping' }}
                </span>
            </a>
            <a href="{{ url('/') }}"
               class="px-4 py-2 rounded-lg bg-white/[0.04] border border-white/[0.08]
                      text-gray-300 hover:text-white hover:border-racing-orangeLight/40
                      transition-all text-xs font-bold uppercase tracking-wider">
                Beranda
            </a>
        </div>

        {{-- Form area --}}
        <div class="flex-1 flex flex-col justify-center max-w-sm mx-auto w-full">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang</h1>
                <p class="text-sm text-gray-500">Masuk untuk melanjutkan</p>
            </div>

            <x-auth-session-status class="mb-4 text-xs font-semibold text-green-400" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="label-form-mobile">Email</label>
                    <div class="relative">
                        <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="input-dark-mobile" placeholder="email@kamu.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="label-form-mobile mb-0">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-racing-orangeLight font-semibold hover:underline">
                                Lupa?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="password" name="password" id="m_login_pwd" required
                               class="input-dark-mobile pr-12" placeholder="••••••••">
                        <button type="button" onclick="togglePwd('m_login_pwd', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <i class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                {{-- Ingat Saya --}}
                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="remember" id="m_remember"
                           class="rounded bg-racing-input border-white/10 text-racing-orangeLight focus:ring-racing-orangeLight w-4 h-4">
                    <label for="m_remember" class="text-xs text-gray-500">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" class="btn-primary">Masuk Sekarang →</button>
                </div>

                <p class="text-center text-sm text-gray-500 pt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-racing-orangeLight font-bold hover:underline ml-1">
                        Daftar
                    </a>
                </p>
            </form>
        </div>
    </div>

    {{-- ====== DESKTOP LAYOUT (2 kolom) ====== --}}
    <div class="hidden md:flex min-h-screen items-center justify-center p-8 lg:p-12 relative z-10">
        <div class="auth-card">

            {{-- Kolom Kiri: Form Login --}}
            <div class="auth-panel-form">
                <div class="space-y-8">

                    <a href="{{ url('/') }}" class="back-link">
                        <i class="ph ph-arrow-left text-sm"></i>
                        {{ $profil->cta_kembali ?? 'Kembali ke Beranda' }}
                    </a>

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-white tracking-tight">
                            {{ $profil->auth_selamat_datang ?? 'Selamat Datang Kembali' }}
                        </h2>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">
                            Masuk ke akun Anda untuk mengelola pemesanan layanan premium kami.
                        </p>
                    </div>

                    <x-auth-session-status class="text-xs font-semibold" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label class="label-form">{{ $profil->form_email ?? 'Email' }}</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="input-dark" placeholder="email@premiumwrap.id">
                            </div>
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="label-form">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-[9px] font-bold text-racing-orangeLight hover:underline uppercase tracking-wider">
                                        {{ $profil->form_lupa_sandi ?? 'Lupa Sandi?' }}
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="password" name="password" id="d_login_pwd" required
                                       class="input-dark pr-12" placeholder="••••••••">
                                <button type="button" onclick="togglePwd('d_login_pwd', this)"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                                    <i class="ph ph-eye text-lg"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        {{-- Ingat Saya --}}
                        <div class="flex items-center gap-3 pt-1">
                            <input type="checkbox" name="remember" id="d_remember_me"
                                   class="rounded bg-racing-input border-white/5 text-racing-orangeLight focus:ring-racing-orangeLight">
                            <label for="d_remember_me" class="text-[10px] text-gray-400 font-light">
                                {{ $profil->form_ingat_saya ?? 'Ingat saya di perangkat ini' }}
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-primary-lg">
                            {{ $profil->cta_masuk_sekarang ?? 'Masuk Sekarang' }}
                            <i class="ph-bold ph-arrow-right text-sm"></i>
                        </button>

                        <p class="text-center text-[10px] text-gray-400 font-medium pt-2">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}"
                               class="text-racing-orangeLight font-bold hover:underline ml-1">
                                Daftar di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Visual / Foto --}}
            <div class="auth-panel-visual" style="background-image: url('{{ asset('images/tesla_model_s.png') }}');">
                <div class="auth-visual-overlay"></div>

                <div class="z-10 p-10 lg:p-14">
                    <a href="/" class="inline-flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-widest text-racing-orangeLight uppercase">
                            {{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }}
                        </span>
                    </a>
                </div>

                <div class="z-10 space-y-6 p-10 lg:p-14">
                    <p class="text-gray-300 text-sm font-light leading-relaxed max-w-sm">
                        Luxury is in the details. Protect your vehicle with the ultimate matte or glossy shield.
                    </p>
                    <div class="auth-testimonial-card">
                        <p class="text-xs text-gray-200 leading-relaxed font-light italic">
                            "Layanan car wrapping terbaik yang pernah saya temukan. Hasil kilapnya seperti cermin."
                        </p>
                        <span class="text-[10px] text-racing-orangeLight font-bold block mt-3 uppercase tracking-wider">
                            — Siska A., Luxury Sedan Owner
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePwd(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon  = btn.querySelector('i');
            if (input.type === 'password') {
                input.type     = 'text';
                icon.className = 'ph ph-eye-slash text-base';
            } else {
                input.type     = 'password';
                icon.className = 'ph ph-eye text-base';
            }
        }
    </script>
</body>
</html>

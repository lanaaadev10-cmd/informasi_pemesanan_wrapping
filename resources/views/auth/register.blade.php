<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - {{ $profil->meta_title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-racing-black font-['Plus_Jakarta_Sans',sans-serif]">

    {{-- Ambient Glow Decoration --}}
    <div class="glow-orb glow-orb-bl"></div>
    <div class="glow-orb glow-orb-tr"></div>

    {{-- ====== MOBILE LAYOUT ====== --}}
    @include('auth.partials._register-mobile')

    {{-- ====== DESKTOP LAYOUT (2 kolom) ====== --}}
    <div class="hidden md:flex min-h-screen items-center justify-center p-8 lg:p-12 relative z-10">
        <div class="auth-card">

            {{-- Kolom Kiri: Visual / Foto --}}
            <div class="auth-panel-visual"
                 style="background-image: url('{{ asset('images/hero_car.png') }}');">
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
                        Precision in every layer. Transform your assets with the world's finest automotive films.
                    </p>
                    <div class="auth-testimonial-card">
                        <p class="text-xs text-gray-200 leading-relaxed font-light italic">
                            "Hasil pengerjaan sangat presisi dan detail. Benar-benar standar kelas dunia untuk mobil koleksi saya."
                        </p>
                        <span class="text-[10px] text-racing-orangeLight font-bold block mt-3 uppercase tracking-wider">
                            — Robert O., Automotive Enthusiast
                        </span>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form Registrasi --}}
            <div class="w-1/2 p-10 lg:p-14 bg-racing-formDark border-l border-white/5 flex flex-col justify-center">
                <div class="space-y-7">

                    <a href="{{ url('/') }}" class="back-link">
                        <i class="ph ph-arrow-left text-sm"></i>
                        {{ $profil->cta_kembali ?? 'Kembali ke Beranda' }}
                    </a>

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-white tracking-tight">Buat Akun Baru</h2>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">
                            Lengkapi detail di bawah untuk memulai pemesanan layanan eksklusif kami.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div class="space-y-1.5">
                            <label class="label-form">{{ $profil->form_nama_lengkap ?? 'Nama Lengkap' }}</label>
                            <div class="relative">
                                <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="text" name="name" id="d_name" value="{{ old('name') }}" required autofocus
                                       class="input-dark" placeholder="John Doe"
                                       oninput="valName(this.value, 'd')">
                            </div>
                            <p id="d_name_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label class="label-form">{{ $profil->form_email ?? 'Email' }}</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="email" name="email" id="d_email" value="{{ old('email') }}" required
                                       class="input-dark" placeholder="email@premiumwrap.id"
                                       oninput="valEmail(this.value, 'd')">
                            </div>
                            <p id="d_email_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        {{-- WhatsApp --}}
                        <div class="space-y-1.5">
                            <label class="label-form">Nomor WhatsApp</label>
                            <div class="relative">
                                <i class="ph ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="text" name="phone" id="d_wa" value="{{ old('phone') }}"
                                       class="input-dark pr-10" placeholder="08xxxxxxxxxx"
                                       oninput="valWA(this.value, 'd')">
                                <i id="d_wa_check" class="ph ph-check-circle hidden absolute right-4 top-1/2 -translate-y-1/2 text-lg text-green-500"></i>
                            </div>
                            <p id="d_wa_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('phone')" />
                        </div>

                        {{-- Password & Konfirmasi (grid 2 kolom) --}}
                        <div class="grid grid-cols-2 gap-3">

                            {{-- Kata Sandi --}}
                            <div class="space-y-1.5">
                                <label class="label-form">Kata Sandi</label>
                                <div class="relative">
                                    <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                    <input type="password" name="password" id="d_pwd" required
                                           class="input-dark pr-12" placeholder="••••••••"
                                           oninput="valPassword(this.value, 'd')">
                                    <button type="button" onclick="togglePwd('d_pwd', this)"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                                        <i class="ph ph-eye text-lg"></i>
                                    </button>
                                </div>
                                <div id="d_pwd_checklist" class="mt-2 space-y-1 hidden">
                                    <p id="d_pwd_chk_minlen" class="transition-colors duration-200 text-[10px] text-gray-500">
                                        <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Minimal 9 karakter
                                    </p>
                                    <p id="d_pwd_chk_maxlen" class="transition-colors duration-200 text-[10px] text-gray-500">
                                        <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Maksimal 20 karakter
                                    </p>
                                    <p id="d_pwd_chk_upper" class="transition-colors duration-200 text-[10px] text-gray-500">
                                        <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung huruf kapital (A-Z)
                                    </p>
                                    <p id="d_pwd_chk_lower" class="transition-colors duration-200 text-[10px] text-gray-500">
                                        <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung huruf kecil (a-z)
                                    </p>
                                </div>
                                <x-input-error :messages="$errors->get('password')" />
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="space-y-1.5">
                                <label class="label-form">{{ $profil->form_konfirmasi_password ?? 'Konfirmasi' }}</label>
                                <div class="relative">
                                    <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                    <input type="password" name="password_confirmation" id="d_pwd_conf" required
                                           class="input-dark pr-12" placeholder="••••••••"
                                           oninput="valConfirm(this.value, 'd_pwd', 'd')">
                                    <button type="button" onclick="togglePwd('d_pwd_conf', this)"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                                        <i class="ph ph-eye text-lg"></i>
                                    </button>
                                </div>
                                <p id="d_pwd_conf_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                                <i id="d_pwd_conf_check" class="ph ph-check-circle hidden text-green-500 text-sm mt-1.5"></i>
                                <x-input-error :messages="$errors->get('password')" />
                            </div>

                        </div>

                        {{-- Syarat & Ketentuan --}}
                        <div class="flex items-start gap-3 pt-1">
                            <input type="checkbox" id="d_terms" required
                                   class="mt-0.5 rounded bg-racing-input border-white/5
                                          text-racing-orangeLight focus:ring-racing-orangeLight">
                            <label for="d_terms" class="text-[10px] text-gray-400 leading-normal font-light">
                                {{ $profil->form_setuju_syarat ?? 'Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi.' }}
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-primary">
                            {{ $profil->cta_daftar_sekarang ?? 'Daftar Sekarang' }}
                            <i class="ph-bold ph-arrow-right text-sm"></i>
                        </button>

                        <p class="text-center text-[10px] text-gray-400 font-medium pt-1">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}"
                               class="text-racing-orangeLight font-bold hover:underline ml-1">
                                Masuk di sini
                            </a>
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Script validasi form --}}
    @include('auth.partials._register-validation-script')
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - {{ $profil->nama_perusahaan ?? config('app.name', 'Dantie Stiker') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Questrial&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 font-questrial antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-12">

    {{-- Main Auth Card (Mengikuti Komposisi Visual Gambar Referensi) --}}
    <div class="max-w-4xl lg:max-w-5xl w-full bg-white rounded-2xl md:rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.08)] overflow-hidden flex flex-col md:flex-row relative border border-slate-200 my-auto">
        
        {{-- KOLOM KIRI: FORM LOGIN --}}
        <div class="w-full md:w-[48%] lg:w-[45%] bg-white p-7 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-between z-10 order-2 md:order-1 min-h-[480px]">
            <div>
                {{-- Judul Halaman Sesuai Gambar Referensi --}}
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-wider uppercase font-montserrat mb-8 sm:mb-12">
                    MEMBER LOGIN
                </h1>

                {{-- Status Session Feedback --}}
                <x-auth-session-status class="mb-5 text-xs font-semibold text-emerald-600" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6 sm:space-y-8">
                    @csrf

                    {{-- Field 1: Username / Email dengan Garis Bawah & Ikon User --}}
                    <div>
                        <div class="relative">
                            <input type="email"
                                   name="email"
                                   id="login_email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="Username"
                                   class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-3 pl-1 pr-9 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                            <i class="ph ph-user absolute right-1 top-1/2 -translate-y-1/2 text-slate-300 text-lg pointer-events-none"></i>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    {{-- Field 2: Password dengan Garis Bawah & Ikon Gembok --}}
                    <div>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="login_pwd"
                                   required
                                   placeholder="Password"
                                   class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-3 pl-1 pr-16 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                            <div class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                <button type="button"
                                        onclick="togglePwd('login_pwd', this)"
                                        class="text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none"
                                        title="Lihat / Sembunyikan Kata Sandi">
                                    <i class="ph ph-eye text-base"></i>
                                </button>
                                <i class="ph ph-lock text-slate-300 text-lg pointer-events-none"></i>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    {{-- Ingat Saya (Subtle Checkbox) --}}
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox"
                               name="remember"
                               id="remember_me"
                               class="rounded border-slate-300 text-[#d35400] focus:ring-[#d35400] w-4 h-4 cursor-pointer">
                        <label for="remember_me" class="text-xs text-slate-500 font-questrial cursor-pointer select-none">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Tombol Oval Pill Khas Desain Referensi --}}
                    <div class="pt-2 sm:pt-4">
                        <button type="submit"
                                class="w-full sm:w-auto min-w-[140px] px-10 py-3.5 rounded-full bg-[#d35400] hover:bg-[#b84300] active:scale-95 text-white font-montserrat font-bold text-xs sm:text-sm tracking-widest uppercase shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center min-h-[46px]">
                            LOGIN
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Tautan Bawah (Forgot Password & Register) --}}
            <div class="flex items-center justify-between gap-4 pt-8 sm:pt-10 text-xs font-montserrat font-semibold border-t border-slate-100 mt-6">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-[#d35400] hover:text-[#b84300] transition-colors">
                        Lupa Password?
                    </a>
                @else
                    <span></span>
                @endif
                <a href="{{ route('register') }}"
                   class="text-[#d35400] hover:text-[#b84300] transition-colors">
                    Register
                </a>
            </div>
        </div>

        {{-- KOLOM KANAN: HERO / VISUAL PANEL --}}
        <div class="w-full md:w-[52%] lg:w-[55%] relative min-h-[220px] sm:min-h-[280px] md:min-h-[580px] bg-cover bg-center flex flex-col justify-between p-7 sm:p-10 md:p-12 lg:p-14 text-white overflow-hidden order-1 md:order-2"
             style="background-image: url('{{ asset('images/banner_exclusive_car.webp') }}');">
            
            {{-- Dark Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/75 to-slate-950/70 z-0"></div>

            {{-- Tombol Close "X" di Kanan Atas --}}
            <a href="{{ url('/') }}"
               class="absolute top-5 right-6 z-20 text-white/70 hover:text-white hover:scale-110 active:scale-95 transition-all text-3xl font-light leading-none cursor-pointer"
               title="Tutup &amp; Kembali ke Beranda">
                &times;
            </a>

            {{-- Spacer Top --}}
            <div class="z-10 hidden md:block">
                <span class="text-[10px] font-mono tracking-widest uppercase text-white/60">
                    {{ $profil->nama_perusahaan ?? 'Dantie Stiker' }}
                </span>
            </div>

            {{-- Konten Utama Teks Showcase --}}
            <div class="my-auto text-center space-y-3 sm:space-y-4 px-2 sm:px-4 z-10 py-6 md:py-0">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-wider uppercase font-montserrat leading-tight drop-shadow-md">
                    HALLO, SELAMAT DATANG DI SISTEM INFORMASI
                </h2>
                <p class="text-white/85 font-questrial text-xs sm:text-sm md:text-base max-w-sm mx-auto leading-relaxed drop-shadow">
                    Pemesanan Wrapping
                </p>
            </div>

            {{-- Footer Bawah Hero --}}
            <div class="z-10 text-center md:text-left hidden md:block">
                <span class="text-[10px] font-mono tracking-widest uppercase text-white/50">
                    Official Workshop &amp; Studio System
                </span>
            </div>
        </div>

    </div>

    {{-- Script Toggle Password Visibility --}}
    <script>
        function togglePwd(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        }
    </script>
</body>
</html>

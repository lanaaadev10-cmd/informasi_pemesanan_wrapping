<!DOCTYPE html>
<html lang="id" class="bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Register - {{ $profil->nama_perusahaan ?? config('app.name', 'Dantie Stiker') }}</title>
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
        
        {{-- KOLOM KIRI: FORM REGISTER --}}
        <div class="w-full md:w-[50%] lg:w-[48%] bg-white p-7 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-between z-10 order-2 md:order-1 min-h-[580px]">
            <div>
                {{-- Judul Halaman Sesuai Gambar Referensi --}}
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-wider uppercase font-montserrat mb-6 sm:mb-8">
                    MEMBER REGISTER
                </h1>

                <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5" novalidate>
                    @csrf

                    {{-- Field 1: Nama Lengkap / Username --}}
                    <div>
                        <div class="relative">
                            <input type="text"
                                   name="name"
                                   id="reg_name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   placeholder="Username / Nama Lengkap"
                                   oninput="valName(this.value, 'reg')"
                                   class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 pl-1 pr-9 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                            <i class="ph ph-user absolute right-1 top-1/2 -translate-y-1/2 text-slate-300 text-lg pointer-events-none"></i>
                        </div>
                        <p id="reg_name_error" class="hidden text-[11px] text-red-500 font-medium mt-1"></p>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500 font-medium" />
                    </div>

                    {{-- Field 2: Email --}}
                    <div>
                        <div class="relative">
                            <input type="email"
                                   name="email"
                                   id="reg_email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="Email Address"
                                   oninput="valEmail(this.value, 'reg')"
                                   class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 pl-1 pr-9 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                            <i class="ph ph-envelope absolute right-1 top-1/2 -translate-y-1/2 text-slate-300 text-lg pointer-events-none"></i>
                        </div>
                        <p id="reg_email_error" class="hidden text-[11px] text-red-500 font-medium mt-1"></p>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500 font-medium" />
                    </div>

                    {{-- Field 3: WhatsApp --}}
                    <div>
                        <div class="relative">
                            <input type="text"
                                   name="phone"
                                   id="reg_wa"
                                   value="{{ old('phone') }}"
                                   placeholder="Nomor WhatsApp (08xxxxxxxxxx)"
                                   oninput="valWA(this.value, 'reg')"
                                   class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 pl-1 pr-9 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                            <i class="ph ph-whatsapp-logo absolute right-1 top-1/2 -translate-y-1/2 text-slate-300 text-lg pointer-events-none"></i>
                            <i id="reg_wa_check" class="ph ph-check-circle hidden absolute right-7 top-1/2 -translate-y-1/2 text-base text-emerald-500"></i>
                        </div>
                        <p id="reg_wa_error" class="hidden text-[11px] text-red-500 font-medium mt-1"></p>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1 text-xs text-red-500 font-medium" />
                    </div>

                    {{-- Field 4 & 5: Password & Konfirmasi Password (Grid Responsif) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <div>
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="reg_pwd"
                                       required
                                       placeholder="Password"
                                       oninput="valPassword(this.value, 'reg')"
                                       class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 pl-1 pr-14 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                                <div class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                    <button type="button"
                                            onclick="togglePwd('reg_pwd', this)"
                                            class="text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none"
                                            title="Lihat / Sembunyikan Kata Sandi">
                                        <i class="ph ph-eye text-base"></i>
                                    </button>
                                    <i class="ph ph-lock text-slate-300 text-lg pointer-events-none"></i>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500 font-medium" />
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <div class="relative">
                                <input type="password"
                                       name="password_confirmation"
                                       id="reg_pwd_conf"
                                       required
                                       placeholder="Confirm Password"
                                       class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 pl-1 pr-14 text-sm font-questrial text-slate-800 placeholder-slate-400 transition-colors focus:outline-none">
                                <div class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                    <button type="button"
                                            onclick="togglePwd('reg_pwd_conf', this)"
                                            class="text-slate-400 hover:text-slate-600 transition-colors p-1 focus:outline-none"
                                            title="Lihat / Sembunyikan Kata Sandi">
                                        <i class="ph ph-eye text-base"></i>
                                    </button>
                                    <i class="ph ph-shield-check text-slate-300 text-lg pointer-events-none"></i>
                                </div>
                            </div>
                            <p id="reg_pwd_conf_error" class="hidden text-[11px] text-red-500 font-medium mt-1"></p>
                        </div>
                    </div>

                    {{-- Checklist Kriteria Password (Dinamis & Ringkas) --}}
                    <div id="reg_pwd_checklist" class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 hidden">
                        <p id="reg_pwd_chk_minlen" class="text-[10px] text-slate-500 transition-colors">
                            <span class="circle-icon inline">&bull;</span><span class="check-icon hidden text-emerald-600 font-bold">&check;</span> Minimal 9 karakter
                        </p>
                        <p id="reg_pwd_chk_maxlen" class="text-[10px] text-slate-500 transition-colors">
                            <span class="circle-icon inline">&bull;</span><span class="check-icon hidden text-emerald-600 font-bold">&check;</span> Maksimal 20 karakter
                        </p>
                        <p id="reg_pwd_chk_upper" class="text-[10px] text-slate-500 transition-colors">
                            <span class="circle-icon inline">&bull;</span><span class="check-icon hidden text-emerald-600 font-bold">&check;</span> Huruf kapital (A-Z)
                        </p>
                        <p id="reg_pwd_chk_lower" class="text-[10px] text-slate-500 transition-colors">
                            <span class="circle-icon inline">&bull;</span><span class="check-icon hidden text-emerald-600 font-bold">&check;</span> Huruf kecil (a-z)
                        </p>
                    </div>

                    {{-- Syarat & Ketentuan --}}
                    <div class="flex items-start gap-2 pt-1">
                        <input type="checkbox"
                               id="reg_terms"
                               required
                               class="mt-0.5 rounded border-slate-300 text-[#d35400] focus:ring-[#d35400] w-4 h-4 cursor-pointer">
                        <label for="reg_terms" class="text-[11px] text-slate-500 font-questrial leading-snug cursor-pointer select-none">
                            Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi bengkel.
                        </label>
                    </div>

                    {{-- Tombol Oval Pill REGISTER --}}
                    <div class="pt-3 sm:pt-4">
                        <button type="submit"
                                class="w-36 sm:w-44 py-3.5 rounded-full bg-[#d35400] hover:bg-[#b84300] active:scale-95 text-white font-montserrat font-bold text-xs sm:text-sm tracking-widest uppercase shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center min-h-[46px]">
                            REGISTER
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer Tautan Bawah (Link ke Login) --}}
            <div class="flex items-center justify-between gap-4 pt-6 sm:pt-8 text-xs font-montserrat font-semibold border-t border-slate-100 mt-6">
                <span class="text-slate-500 font-normal">
                    Sudah memiliki akun?
                </span>
                <a href="{{ route('login') }}"
                   class="text-[#d35400] hover:text-[#b84300] transition-colors">
                    Login
                </a>
            </div>
        </div>

        {{-- KOLOM KANAN: HERO / VISUAL PANEL --}}
        <div class="w-full md:w-[50%] lg:w-[52%] relative min-h-[220px] sm:min-h-[280px] md:min-h-[620px] bg-cover bg-center flex flex-col justify-between p-7 sm:p-10 md:p-12 lg:p-14 text-white overflow-hidden order-1 md:order-2"
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
                    Pemesanan dantie wrapping 
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

    {{-- Script Validasi Form Terpadu & Password Checklist --}}
    @include('auth.partials._register-validation-script')

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

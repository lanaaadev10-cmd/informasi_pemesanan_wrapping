<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - {{ $profil->meta_title ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-black font-['Plus_Jakarta_Sans',sans-serif]">
    {{-- Ambient glow --}}
    <div class="fixed w-[350px] h-[350px] -top-[80px] -left-[80px] rounded-full blur-[100px] pointer-events-none z-0 bg-[#f2994a]/5"></div>
    <div class="fixed w-[300px] h-[300px] -bottom-[60px] -right-[60px] rounded-full blur-[100px] pointer-events-none z-0 bg-[#f2994a]/5"></div>

    {{-- ====== MOBILE LAYOUT (full screen, single step form) ====== --}}
    <div class="relative z-10 md:hidden min-h-screen flex flex-col px-6 py-10">
        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-8">
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
            <div class="mb-7">
                <h1 class="text-2xl font-bold text-white mb-1">Buat Akun</h1>
                <p class="text-sm text-gray-500">Daftar dan mulai pesan layanan premium</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Nama Lengkap</label>
                    <div class="relative">
                        <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="text" name="name" id="m_name" value="{{ old('name') }}" required autofocus
                               class="w-full py-3.5 px-4 pl-[46px] bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="Nama lengkap kamu"
                               oninput="valName(this.value, 'm')">
                    </div>
                    <p id="m_name_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Email</label>
                    <div class="relative">
                        <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="email" name="email" id="m_email" value="{{ old('email') }}" required
                               class="w-full py-3.5 px-4 pl-[46px] bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="email@kamu.com"
                               oninput="valEmail(this.value, 'm')">
                    </div>
                    <p id="m_email_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Nomor WhatsApp</label>
                    <div class="relative">
                        <i class="ph ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="text" name="phone" id="m_wa" value="{{ old('phone') }}"
                               class="w-full py-3.5 px-4 pl-[46px] pr-10 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="08xxxxxxxxxx"
                               oninput="valWA(this.value, 'm')">
                        <i id="m_wa_check" class="ph ph-check-circle hidden absolute right-4 top-1/2 -translate-y-1/2 text-base text-green-500"></i>
                    </div>
                    <p id="m_wa_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                    <x-input-error :messages="$errors->get('phone')" />
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Kata Sandi</label>
                    <div class="relative">
                        <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="password" name="password" id="m_pwd" required
                               class="w-full py-3.5 px-4 pl-[46px] pr-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="Min. 9 karakter"
                               oninput="valPassword(this.value, 'm')">
                        <button type="button" onclick="togglePwd('m_pwd', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <i class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    <div id="m_pwd_checklist" class="mt-2.5 space-y-1.5 hidden">
                        <p id="m_pwd_chk_minlen" class="transition-colors duration-200 text-[11px] text-gray-500">
                            <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Minimal 9 karakter
                        </p>
                        <p id="m_pwd_chk_maxlen" class="transition-colors duration-200 text-[11px] text-gray-500">
                            <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Maksimal 20 karakter
                        </p>
                        <p id="m_pwd_chk_upper" class="transition-colors duration-200 text-[11px] text-gray-500">
                            <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung huruf kapital (A-Z)
                        </p>
                        <p id="m_pwd_chk_lower" class="transition-colors duration-200 text-[11px] text-gray-500">
                            <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung huruf kecil (a-z)
                        </p>
                        <p id="m_pwd_chk_symbol" class="transition-colors duration-200 text-[11px] text-gray-500">
                            <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung simbol (!@#$%^&*)
                        </p>
                    </div>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-widest">Konfirmasi Sandi</label>
                    <div class="relative">
                        <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                        <input type="password" name="password_confirmation" id="m_pwd_conf" required
                               class="w-full py-3.5 px-4 pl-[46px] pr-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-[#f2994a] focus:bg-[#f2994a]/5" placeholder="Ulangi kata sandi"
                               oninput="valConfirm(this.value, 'm_pwd', 'm')">
                        <button type="button" onclick="togglePwd('m_pwd_conf', this)"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <i class="ph ph-eye text-base"></i>
                        </button>
                    </div>
                    <p id="m_pwd_conf_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                    <i id="m_pwd_conf_check" class="ph ph-check-circle hidden text-green-500 text-sm mt-1.5"></i>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-3 pt-1">
                    <input type="checkbox" id="m_terms" required
                           class="mt-0.5 rounded w-4 h-4 accent-[#f2994a]">
                    <label for="m_terms" class="text-xs text-gray-500 leading-relaxed">
                        Saya menyetujui <span class="text-[#f2994a] font-semibold">Syarat & Ketentuan</span> serta Kebijakan Privasi.
                    </label>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#f2994a] text-black font-extrabold text-xs tracking-widest uppercase border-0 rounded-xl cursor-pointer transition-all hover:bg-[#e28a44] active:scale-[0.98]">
                        Daftar Sekarang →
                    </button>
                </div>

                {{-- Login link --}}
                <p class="text-center text-sm text-gray-500 pt-1">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Masuk</a>
                </p>
            </form>
        </div>
    </div>

    {{-- ====== DESKTOP LAYOUT (2 column) ====== --}}
    <div class="hidden md:flex min-h-screen items-center justify-center p-8 lg:p-12 relative z-10">
        <div class="max-w-5xl w-full bg-[#111111] rounded-[32px] overflow-hidden border border-white/5 flex shadow-2xl">
            {{-- Left: Visual --}}
            <div class="w-1/2 flex flex-col justify-between text-white relative min-h-[620px] bg-cover bg-center" style="background-image: url('{{ asset('images/hero_car.png') }}');">
                <div class="absolute inset-0 bg-gradient-to-b from-[#0a0a0a]/75 via-[#0a0a0a]/85 to-[#0a0a0a] z-0"></div>
                <div class="z-10 p-10 lg:p-14">
                    <a href="/" class="inline-flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-widest text-[#f2994a] uppercase">{{ $profil->nama_perusahaan ?? 'Dantie Wrapping' }}</span>
                    </a>
                </div>
                <div class="z-10 space-y-6 p-10 lg:p-14">
                    <p class="text-gray-300 text-sm font-light leading-relaxed max-w-sm">Precision in every layer. Transform your assets with the world's finest automotive films.</p>
                    <div class="border border-[#f2994a]/30 rounded-2xl p-5 bg-[#0a0a0a]/40 backdrop-blur-sm">
                        <p class="text-xs text-gray-200 leading-relaxed font-light italic">"Hasil pengerjaan sangat presisi dan detail. Benar-benar standar kelas dunia untuk mobil koleksi saya."</p>
                        <span class="text-[10px] text-[#f2994a] font-bold block mt-3 uppercase tracking-wider">— Robert O., Automotive Enthusiast</span>
                    </div>
                </div>
            </div>
            {{-- Right: Form --}}
            <div class="w-1/2 p-10 lg:p-14 bg-[#0c0c0c] border-l border-white/5 flex flex-col justify-center">
                <div class="space-y-7">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-[10px] text-gray-500 hover:text-[#f2994a] transition-colors font-medium">
                        <i class="ph ph-arrow-left text-sm"></i> {{ $profil->cta_kembali ?? 'Kembali ke Beranda' }}
                    </a>
                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-white tracking-tight">Buat Akun Baru</h2>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Lengkapi detail di bawah untuk memulai pemesanan layanan eksklusif kami.</p>
                    </div>
                    <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
                        @csrf
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_nama_lengkap ?? 'Nama Lengkap' }}</label>
                            <div class="relative">
                                <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="text" name="name" id="d_name" value="{{ old('name') }}" required autofocus
                                       class="w-full pl-11 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="John Doe"
                                       oninput="valName(this.value, 'd')">
                            </div>
                            <p id="d_name_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_email ?? 'Email' }}</label>
                            <div class="relative">
                                <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="email" name="email" id="d_email" value="{{ old('email') }}" required
                                       class="w-full pl-11 pr-4 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="email@premiumwrap.id"
                                       oninput="valEmail(this.value, 'd')">
                            </div>
                            <p id="d_email_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor WhatsApp</label>
                            <div class="relative">
                                <i class="ph ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                <input type="text" name="phone" id="d_wa" value="{{ old('phone') }}"
                                       class="w-full pl-11 pr-10 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                       placeholder="08xxxxxxxxxx" oninput="valWA(this.value, 'd')">
                                <i id="d_wa_check" class="ph ph-check-circle hidden absolute right-4 top-1/2 -translate-y-1/2 text-lg text-green-500"></i>
                            </div>
                            <p id="d_wa_error" class="hidden text-[10px] text-red-400 mt-1"></p>
                            <x-input-error :messages="$errors->get('phone')" />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kata Sandi</label>
                                <div class="relative">
                                    <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                    <input type="password" name="password" id="d_pwd" required
                                           class="w-full pl-11 pr-12 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                           placeholder="••••••••"
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
                                    <p id="d_pwd_chk_symbol" class="transition-colors duration-200 text-[10px] text-gray-500">
                                        <span class="circle-icon inline">○</span><span class="check-icon hidden">✓</span> Mengandung simbol (!@#$%^&*)
                                    </p>
                                </div>
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $profil->form_konfirmasi_password ?? 'Konfirmasi' }}</label>
                                <div class="relative">
                                    <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
                                    <input type="password" name="password_confirmation" id="d_pwd_conf" required
                                           class="w-full pl-11 pr-12 py-3 bg-[#161616] border border-white/5 rounded-xl focus:ring-1 focus:ring-[#f2994a] focus:border-[#f2994a] text-white text-xs placeholder-gray-600 transition-all outline-none"
                                           placeholder="••••••••"
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
                        <div class="flex items-start gap-3 pt-1">
                            <input type="checkbox" id="d_terms" required class="mt-0.5 rounded bg-[#161616] border-white/5 text-[#f2994a] focus:ring-[#f2994a]">
                            <label for="d_terms" class="text-[10px] text-gray-400 leading-normal font-light">{{ $profil->form_setuju_syarat ?? 'Saya menyetujui Syarat & Ketentuan serta Kebijakan Privasi.' }}</label>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-[#f2994a] text-black font-extrabold text-xs uppercase tracking-widest rounded-xl hover:bg-[#e28a44] transition-all flex items-center justify-center gap-2 active:scale-95 shadow-[0_4px_20px_rgba(242,153,74,0.3)]">
                            {{ $profil->cta_daftar_sekarang ?? 'Daftar Sekarang' }} <i class="ph-bold ph-arrow-right text-sm"></i>
                        </button>
                        <p class="text-center text-[10px] text-gray-400 font-medium pt-1">
                            Sudah memiliki akun?
                            <a href="{{ route('login') }}" class="text-[#f2994a] font-bold hover:underline ml-1">Masuk di sini</a>
                        </p>
                    </form>
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
                icon.className = icon.className.replace('ph-eye', 'ph-eye-slash');
            } else {
                input.type = 'password';
                icon.className = icon.className.replace('ph-eye-slash', 'ph-eye');
            }
        }

        function showError(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;
            if (msg) {
                el.textContent = '⚠️ ' + msg;
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
                el.textContent = '';
            }
        }

        function valName(value, prefix) {
            const errId = prefix + '_name_error';
            if (!value) {
                showError(errId, '');
                return;
            }
            if (value.length < 3) {
                showError(errId, 'Nama minimal 3 karakter');
                return;
            }
            if (/\d/.test(value)) {
                showError(errId, 'Nama tidak boleh mengandung angka');
                return;
            }
            showError(errId, '');
        }

        function valEmail(value, prefix) {
            const errId = prefix + '_email_error';
            if (!value) {
                showError(errId, '');
                return;
            }
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(value)) {
                showError(errId, 'Format email tidak valid. Contoh: nama@email.com');
                return;
            }
            showError(errId, '');
        }

        function valWA(value, prefix) {
            const errId = prefix + '_wa_error';
            const checkEl = document.getElementById(prefix + '_wa_check');
            if (!value) {
                showError(errId, '');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }
            const digits = value.replace(/\D/g, '');
            const fullRegex = /^(\+62|62|0)8[1-9][0-9]{7,10}$/;

            if (!/^(\+62|62|0)8/.test(value)) {
                showError(errId, 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }
            if (digits.length < 10) {
                showError(errId, 'Nomor WhatsApp minimal 10 digit');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }
            if (digits.length > 13) {
                showError(errId, 'Nomor WhatsApp maksimal 13 digit');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }
            if (!fullRegex.test(value)) {
                showError(errId, 'Format nomor tidak valid. Gunakan format: 08xxxxxxxxxx');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }
            showError(errId, '');
            if (checkEl) checkEl.classList.remove('hidden');
        }

        function valPassword(value, prefix) {
            const checklist = document.getElementById(prefix + '_pwd_checklist');
            if (!checklist) return;

            if (!value) {
                checklist.classList.add('hidden');
                return;
            }
            checklist.classList.remove('hidden');

            const chkMin = document.getElementById(prefix + '_pwd_chk_minlen');
            const chkMax = document.getElementById(prefix + '_pwd_chk_maxlen');
            const chkUpper = document.getElementById(prefix + '_pwd_chk_upper');
            const chkLower = document.getElementById(prefix + '_pwd_chk_lower');
            const chkSymbol = document.getElementById(prefix + '_pwd_chk_symbol');

            if (chkMin) toggleCheck(chkMin, value.length >= 9);
            if (chkMax) toggleCheck(chkMax, value.length <= 20);
            if (chkUpper) toggleCheck(chkUpper, /[A-Z]/.test(value));
            if (chkLower) toggleCheck(chkLower, /[a-z]/.test(value));
            if (chkSymbol) toggleCheck(chkSymbol, /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value));

            const confirmId = (prefix === 'm' ? 'm_pwd_conf' : 'd_pwd_conf');
            const confirmEl = document.getElementById(confirmId);
            if (confirmEl && confirmEl.value) {
                valConfirm(confirmEl.value, (prefix === 'm' ? 'm_pwd' : 'd_pwd'), prefix);
            }
        }

        function toggleCheck(el, met) {
            if (met) {
                el.classList.add('text-green-500');
                el.querySelectorAll('.circle-icon').forEach(i => i.classList.add('hidden'));
                el.querySelectorAll('.check-icon').forEach(i => i.classList.remove('hidden'));
            } else {
                el.classList.remove('text-green-500');
                el.querySelectorAll('.circle-icon').forEach(i => i.classList.remove('hidden'));
                el.querySelectorAll('.check-icon').forEach(i => i.classList.add('hidden'));
            }
        }

        function valConfirm(value, pwdId, prefix) {
            const errId = prefix + '_pwd_conf_error';
            const checkEl = document.getElementById(prefix + '_pwd_conf_check');
            const pwdEl = document.getElementById(pwdId);

            if (!value) {
                showError(errId, '');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }

            const pwdValue = pwdEl ? pwdEl.value : '';
            if (value !== pwdValue) {
                showError(errId, 'Kata sandi tidak cocok, silakan periksa kembali');
                if (checkEl) checkEl.classList.add('hidden');
                return;
            }

            showError(errId, '');
            if (checkEl) checkEl.classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const mPwd = document.getElementById('m_pwd');
            const dPwd = document.getElementById('d_pwd');
            const mConf = document.getElementById('m_pwd_conf');
            const dConf = document.getElementById('d_pwd_conf');

            if (mPwd && mConf) {
                mPwd.addEventListener('input', function () {
                    if (mConf.value) valConfirm(mConf.value, 'm_pwd', 'm');
                });
            }
            if (dPwd && dConf) {
                dPwd.addEventListener('input', function () {
                    if (dConf.value) valConfirm(dConf.value, 'd_pwd', 'd');
                });
            }
        });
    </script>
</body>
</html>

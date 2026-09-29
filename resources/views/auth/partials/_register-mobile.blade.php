{{-- ====== MOBILE LAYOUT (full screen, single step form) ====== --}}
<div class="relative z-10 md:hidden min-h-screen flex flex-col px-6 py-10">
    {{-- Top bar --}}
    <div class="flex items-center justify-between mb-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white bg-racing-orangeLight">
                <i class="ph-bold ph-sketch-logo text-base"></i>
            </div>
            <span class="font-extrabold text-sm text-white uppercase tracking-wider">{{ $profil->nama_perusahaan ?? 'Wrapping' }}</span>
        </a>
        <a href="{{ url('/') }}"
           class="px-4 py-2 rounded-lg bg-white/[0.04] border border-white/[0.08] text-gray-300 hover:text-white hover:border-racing-orangeLight/40 transition-all text-xs font-bold uppercase tracking-wider">
            Dashboard
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
                <label class="label-form mb-2">Nama Lengkap</label>
                <div class="relative">
                    <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                    <input type="text" name="name" id="m_name" value="{{ old('name') }}" required autofocus
                           class="w-full py-3.5 px-4 pl-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-racing-orangeLight focus:bg-racing-orangeLight/5" placeholder="Nama lengkap kamu"
                           oninput="valName(this.value, 'm')">
                </div>
                <p id="m_name_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                <x-input-error :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div>
                <label class="label-form mb-2">Email</label>
                <div class="relative">
                    <i class="ph ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                    <input type="email" name="email" id="m_email" value="{{ old('email') }}" required
                           class="w-full py-3.5 px-4 pl-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-racing-orangeLight focus:bg-racing-orangeLight/5" placeholder="email@kamu.com"
                           oninput="valEmail(this.value, 'm')">
                </div>
                <p id="m_email_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            {{-- WhatsApp --}}
            <div>
                <label class="label-form mb-2">Nomor WhatsApp</label>
                <div class="relative">
                    <i class="ph ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                    <input type="text" name="phone" id="m_wa" value="{{ old('phone') }}"
                           class="w-full py-3.5 px-4 pl-12 pr-10 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-racing-orangeLight focus:bg-racing-orangeLight/5" placeholder="08xxxxxxxxxx"
                           oninput="valWA(this.value, 'm')">
                    <i id="m_wa_check" class="ph ph-check-circle hidden absolute right-4 top-1/2 -translate-y-1/2 text-base text-green-500"></i>
                </div>
                <p id="m_wa_error" class="hidden text-xs text-red-400 mt-1.5"></p>
                <x-input-error :messages="$errors->get('phone')" />
            </div>

            {{-- Password --}}
            <div>
                <label class="label-form mb-2">Kata Sandi</label>
                <div class="relative">
                    <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                    <input type="password" name="password" id="m_pwd" required
                           class="w-full py-3.5 px-4 pl-12 pr-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-racing-orangeLight focus:bg-racing-orangeLight/5" placeholder="Min. 9 karakter"
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
                </div>
                <x-input-error :messages="$errors->get('password')" />
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="label-form mb-2">Konfirmasi Sandi</label>
                <div class="relative">
                    <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-base"></i>
                    <input type="password" name="password_confirmation" id="m_pwd_conf" required
                           class="w-full py-3.5 px-4 pl-12 pr-12 bg-white/[0.04] border border-white/[0.08] rounded-xl text-white text-sm outline-none transition-all placeholder:text-gray-600 focus:border-racing-orangeLight focus:bg-racing-orangeLight/5" placeholder="Ulangi kata sandi"
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
                       class="mt-0.5 rounded w-4 h-4 accent-racing-orangeLight">
                <label for="m_terms" class="text-xs text-gray-500 leading-relaxed">
                    Saya menyetujui <span class="text-racing-orangeLight font-semibold">Syarat & Ketentuan</span> serta Kebijakan Privasi.
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 bg-racing-orangeLight text-black font-extrabold text-xs tracking-widest uppercase border-0 rounded-xl cursor-pointer transition-all hover:bg-racing-orangeHover active:scale-[0.98]">
                    Daftar Sekarang →
                </button>
            </div>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-500 pt-1">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-racing-orangeLight font-bold hover:underline ml-1">Masuk</a>
            </p>
        </form>
    </div>
</div>

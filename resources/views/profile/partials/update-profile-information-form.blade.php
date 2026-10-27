<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                Nama Lengkap <span class="text-[#FF6B00]">*</span>
            </label>
            <input id="name"
                   name="name"
                   type="text"
                   class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 outline-none transition-all shadow-sm"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name" />
            <x-input-error class="mt-1.5 text-xs text-rose-500 font-medium" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                Alamat Email <span class="text-[#FF6B00]">*</span>
            </label>
            <input id="email"
                   name="email"
                   type="email"
                   class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 outline-none transition-all shadow-sm"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username" />
            <x-input-error class="mt-1.5 text-xs text-rose-500 font-medium" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-xs font-questrial text-amber-800">
                    <p>
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" class="font-montserrat font-bold text-[#FF6B00] underline hover:text-[#e05d00] ml-1">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-montserrat font-bold text-xs text-emerald-600">
                            ✓ Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#FF6B00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 active:scale-95 transition-all cursor-pointer">
                Simpan Profil
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs font-montserrat font-bold text-emerald-600 flex items-center gap-1.5">
                    <i class="ph-bold ph-check-circle text-base"></i> Berhasil disimpan!
                </p>
            @endif
        </div>
    </form>
</section>

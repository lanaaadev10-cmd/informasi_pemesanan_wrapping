<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                Kata Sandi Saat Ini <span class="text-[#FF6B00]">*</span>
            </label>
            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 outline-none transition-all shadow-sm"
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                Kata Sandi Baru <span class="text-[#FF6B00]">*</span>
            </label>
            <input id="update_password_password"
                   name="password"
                   type="password"
                   class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 outline-none transition-all shadow-sm"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                Konfirmasi Kata Sandi Baru <span class="text-[#FF6B00]">*</span>
            </label>
            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-[#FF6B00] focus:ring-2 focus:ring-[#FF6B00]/20 outline-none transition-all shadow-sm"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-500 font-medium" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#FF6B00] hover:bg-[#ea580c] text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-orange-500/20 active:scale-95 transition-all cursor-pointer">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs font-montserrat font-bold text-emerald-600 flex items-center gap-1.5">
                    <i class="ph-bold ph-check-circle text-base"></i> Kata sandi berhasil diperbarui!
                </p>
            @endif
        </div>
    </form>
</section>

<x-guest-layout>
    <div class="mb-5 text-sm text-slate-600 leading-relaxed font-questrial">
        {{ __('Lupa kata sandi? Masukkan alamat email terdaftar Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-600" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-montserrat font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Email
            </label>
            <input id="email" class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 px-1 text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="email@domain.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-full bg-[#d35400] hover:bg-[#b84300] active:scale-95 text-white font-montserrat font-bold text-xs tracking-widest uppercase shadow transition-all cursor-pointer">
                {{ __('Kirim Tautan Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>

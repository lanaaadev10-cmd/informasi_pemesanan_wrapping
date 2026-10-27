<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-xl font-bold font-montserrat uppercase tracking-wider text-slate-900 mb-1">
            Atur Ulang Kata Sandi
        </h1>
        <p class="text-xs text-slate-500 font-questrial">
            Masukkan kata sandi baru untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-montserrat font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Email
            </label>
            <input id="email" class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 px-1 text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-montserrat font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Kata Sandi Baru
            </label>
            <input id="password" class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 px-1 text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-montserrat font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Konfirmasi Kata Sandi Baru
            </label>
            <input id="password_confirmation" class="w-full bg-transparent border-0 border-b border-slate-300 focus:border-[#d35400] focus:ring-0 py-2.5 px-1 text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-colors" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-500 font-medium" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-full bg-[#d35400] hover:bg-[#b84300] active:scale-95 text-white font-montserrat font-bold text-xs tracking-widest uppercase shadow transition-all cursor-pointer">
                {{ __('Simpan Kata Sandi Baru') }}
            </button>
        </div>
    </form>
</x-guest-layout>

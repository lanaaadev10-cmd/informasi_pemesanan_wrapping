<section class="space-y-4">
    <p class="text-xs font-questrial text-slate-600 leading-relaxed">
        Setelah akun Anda dihapus, seluruh data profil, riwayat pesanan, dan informasi transaksi akan dihapus secara permanen dari basis data. Pastikan Anda telah menyimpan bukti pembayaran atau invoice yang diperlukan sebelum melanjutkan.
    </p>

    <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-rose-600/20 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-2">
        <i class="ph-bold ph-trash text-sm"></i>
        <span>Hapus Akun Saya</span>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 bg-white text-slate-900 rounded-3xl">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3.5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-warning text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-audiowide font-bold text-slate-900">
                        Konfirmasi Penghapusan Akun
                    </h2>
                    <p class="text-xs font-questrial text-slate-500 mt-0.5">
                        Tindakan ini permanen dan tidak dapat dipulihkan.
                    </p>
                </div>
            </div>

            <p class="text-xs font-questrial text-slate-600 leading-relaxed mb-5">
                Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.
            </p>

            <div>
                <label for="password" class="block text-xs font-montserrat font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi Anda <span class="text-rose-600">*</span>
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-900 text-sm font-questrial placeholder-slate-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 outline-none transition-all shadow-sm"
                       placeholder="Masukkan kata sandi Anda" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-rose-500 font-medium" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="w-full sm:w-auto px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-montserrat font-bold text-xs uppercase tracking-wider transition-all">
                    Batal
                </button>

                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-montserrat font-bold text-xs uppercase tracking-wider shadow-md shadow-rose-600/20 active:scale-95 transition-all">
                    Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>

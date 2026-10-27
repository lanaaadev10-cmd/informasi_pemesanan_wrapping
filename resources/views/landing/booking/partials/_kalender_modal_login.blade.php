{{-- ============================================
    MODAL POP-UP: Clean & Elegan untuk Arahan Login Sebelum Melakukan Booking
============================================ --}}
<div x-show="showLoginModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] bg-stone-950/65 backdrop-blur-md flex items-center justify-center p-4 sm:p-6" 
     x-cloak>
    
    <div @click.outside="showLoginModal = false" 
         x-show="showLoginModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="bg-white border border-stone-200/90 rounded-[32px] sm:rounded-[36px] p-7 sm:p-10 max-w-lg w-full text-center relative shadow-2xl space-y-6">
        
        {{-- Close Button --}}
        <button @click="showLoginModal = false" 
                class="absolute top-5 right-5 w-9 h-9 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-500 hover:text-stone-900 flex items-center justify-center text-sm font-bold transition-all active:scale-90"
                aria-label="Tutup Popup">
            <i class="ph-bold ph-x text-base"></i>
        </button>

        {{-- Title & Narrative --}}
        <div class="space-y-2 pt-2">
            <h3 class="text-2xl sm:text-3xl font-serif font-bold text-stone-900 tracking-tight">
                Masuk untuk Reservasi Jadwal
            </h3>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-light max-w-sm mx-auto">
                Untuk menjamin kepastian slot workshop dan memudahkan Anda memantau progres pengerjaan mobil secara real-time, silakan masuk ke akun Anda terlebih dahulu.
            </p>
        </div>

        {{-- Value Propositions / Benefit Box --}}
        <div class="bg-stone-50 rounded-2xl p-4 text-left border border-stone-100 space-y-2.5 text-xs text-stone-700 font-light">
            <div class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                    <i class="ph-bold ph-check font-bold"></i>
                </div>
                <span>Slot pengerjaan langsung terkunci eksklusif atas nama Anda</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                    <i class="ph-bold ph-check font-bold"></i>
                </div>
                <span>Pantau status pengerjaan &amp; foto kendaraan secara live di dashboard</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs shrink-0">
                    <i class="ph-bold ph-check font-bold"></i>
                </div>
                <span>Sertifikat garansi resmi &amp; invoice digital tersimpan aman</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="space-y-3 pt-1">
            <a :href="'{{ route('login') }}' + (pendingDate ? '?redirect=' + encodeURIComponent('/booking/create?tanggal=' + pendingDate) : '')" 
               class="w-full py-4 rounded-full text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white text-center shadow-lg shadow-orange-500/25 hover:brightness-105 transition-all flex items-center justify-center gap-2 block active:scale-95">
                <span>Masuk ke Akun Sekarang</span>
                <i class="ph-bold ph-arrow-right text-sm"></i>
            </a>

            <a :href="'{{ route('register') }}' + (pendingDate ? '?redirect=' + encodeURIComponent('/booking/create?tanggal=' + pendingDate) : '')" 
               class="w-full py-3.5 rounded-full border border-stone-300 text-xs font-bold text-stone-700 hover:bg-stone-50 hover:text-stone-900 text-center transition-all block active:scale-95">
                Belum Punya Akun? Daftar Cepat
            </a>

            <button @click="showLoginModal = false" 
                    type="button" 
                    class="text-xs text-stone-400 hover:text-stone-600 transition-colors pt-1 font-medium">
                Nanti Saja, Saya Ingin Melihat Kalender Dulu
            </button>
        </div>

    </div>
</div>

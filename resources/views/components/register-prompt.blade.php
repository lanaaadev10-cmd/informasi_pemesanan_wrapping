{{-- ============================================
    COMPONENT: Register Prompt Modal
    Deskripsi: Modal popup untuk guest yang mencoba memesan
    Usage: <x-register-prompt />
============================================ --}}
@guest
<div id="register-prompt-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div id="register-modal-overlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative bg-white border border-slate-200 rounded-[28px] p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl animate-modal-in">
        <div class="relative z-10 text-center space-y-5">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-orange-50 border border-orange-200 flex items-center justify-center">
                <i class="ph-bold ph-user-plus text-3xl text-[#ea580c]"></i>
            </div>
            <div class="space-y-1.5">
                <h3 class="text-xl font-bold font-montserrat text-slate-900">Registrasi Diperlukan</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Untuk melakukan reservasi jadwal atau pemesanan paket, silakan masuk atau buat akun terlebih dahulu.
                </p>
            </div>
            <div class="flex flex-col gap-2.5 pt-2">
                <a href="{{ route('register') }}"
                   class="flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-[#ea580c] to-[#f97316] text-white font-montserrat font-medium text-xs tracking-wider uppercase px-5 rounded-full transition-all duration-200 hover:brightness-105 active:scale-95 shadow-md shadow-orange-500/20">
                    <i class="ph-bold ph-user-plus text-sm"></i> Daftar Akun Baru
                </a>
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 py-3 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-montserrat font-medium text-xs tracking-wider uppercase px-5 rounded-full transition-all duration-200 active:scale-95">
                    <i class="ph-bold ph-sign-in text-sm text-[#ea580c]"></i> Masuk ke Akun
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Register global function if not exists
    if (typeof window.showRegisterPrompt !== 'function') {
        window.showRegisterPrompt = function() {
            const modal = document.getElementById('register-prompt-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.opacity = '0';
                modal.style.transition = 'opacity 0.2s ease';
                requestAnimationFrame(() => {
                    modal.style.opacity = '1';
                });
            }
        };
    }

    // Auto-attach close handlers once
    if (!window.registerPromptHandlersAttached) {
        window.registerPromptHandlersAttached = true;

        // Close on overlay click
        document.addEventListener('click', function(e) {
            if (e.target.id === 'register-modal-overlay') {
                const modal = document.getElementById('register-prompt-modal');
                if (modal) {
                    modal.style.opacity = '0';
                    setTimeout(() => modal.classList.add('hidden'), 200);
                }
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('register-prompt-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    modal.style.opacity = '0';
                    setTimeout(() => modal.classList.add('hidden'), 200);
                }
            }
        });
    }
</script>
@endguest

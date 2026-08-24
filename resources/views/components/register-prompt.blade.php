{{-- ============================================
    COMPONENT: Register Prompt Modal
    Deskripsi: Modal popup untuk guest yang mencoba memesan
    Usage: <x-register-prompt />
============================================ --}}
@guest
<div id="register-prompt-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div id="register-modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-[#121212] border border-white/10 rounded-[28px] p-8 max-w-sm w-full mx-4 shadow-2xl animate-modal-in">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#f2994a]/5 blur-[60px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 text-center space-y-6">
            <div class="w-16 h-16 mx-auto rounded-full bg-[#f2994a]/10 border border-[#f2994a]/20 flex items-center justify-center">
                <i class="ph-bold ph-user-plus text-2xl text-[#f2994a]"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-extrabold text-white">Registrasi Diperlukan</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Untuk melakukan pemesanan, silakan registrasi terlebih dahulu ya.
                </p>
            </div>
            <div class="flex flex-col gap-3">
                <a href="{{ route('register') }}"
                   class="flex items-center justify-center gap-2 py-3.5 bg-[#f2994a] hover:bg-[#e28a44] text-black font-extrabold text-[10px] tracking-[0.1em] uppercase px-5 rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-95 shadow-[0_4px_15px_rgba(242,153,74,0.3)]">
                    <i class="ph-bold ph-user-plus text-xs"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 py-3.5 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white font-extrabold text-[10px] tracking-[0.1em] uppercase px-5 rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-95">
                    <i class="ph-bold ph-sign-in text-xs"></i> Masuk
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

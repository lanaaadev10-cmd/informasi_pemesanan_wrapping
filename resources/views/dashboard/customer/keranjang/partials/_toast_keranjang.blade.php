{{-- ============================================
    SKRIP: Toast Notification & Animation Styles Keranjang
============================================ --}}
<script>
    // Dynamic clean toast notifications
    function showToast(message, type = 'info') {
        const existing = document.querySelector('[data-toast]');
        if (existing) existing.remove();

        const colors = {
            success: 'bg-[#FF6B00]/15 border-[#FF6B00]/40 text-white',
            error: 'bg-red-500/10 border-red-500/30 text-red-300',
            info: 'bg-white/5 border-white/10 text-gray-300',
        };

        const toast = document.createElement('div');
        toast.setAttribute('data-toast', '');
        toast.className = `fixed bottom-6 right-6 p-4 rounded-2xl border ${colors[type]} max-w-sm z-50 shadow-lg animate-slide-in-up backdrop-blur-md`;
        toast.innerHTML = `
            <div class="flex items-center justify-between gap-4">
                <p class="text-xs font-bold tracking-wide">${message}</p>
                <button onclick="this.closest('[data-toast]').remove()" class="text-lg opacity-50 hover:opacity-100">&times;</button>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 4000);
    }

    // Load styles dynamically
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slide-in-up {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-in-up {
            animation: slide-in-up 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes modal-in {
            from { transform: scale(0.9) translateY(20px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }
        .animate-modal-in {
            animation: modal-in 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    `;
    document.head.appendChild(style);
</script>

{{-- Bridge: flash message via showToast() --}}
@if(session('toast_success') || session('toast_error'))
    <script>
        document.getElementById('floating-toast')?.remove();
        document.getElementById('floating-toast-error')?.remove();
@if(session('toast_success'))
        showToast(@js(session('toast_success')), 'success');
@endif
@if(session('toast_error'))
        showToast(@js(session('toast_error')), 'error');
@endif
    </script>
@endif

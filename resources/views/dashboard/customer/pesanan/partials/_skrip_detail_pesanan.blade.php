{{-- Script Upload, Clipboard, dan Toast untuk Pesanan Show --}}
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('{{ $profil->alert_rekening_disalin ?? 'Nomor Rekening berhasil disalin' }}: ' + text);
        });
    }

    function previewFile() {
        const input = document.getElementById('bukti-input');
        const container = document.getElementById('preview-container');
        const image = document.getElementById('preview-image');
        
        if (input && input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFile() {
        const input = document.getElementById('bukti-input');
        const container = document.getElementById('preview-container');
        if (input) input.value = "";
        if (container) container.classList.add('hidden');
    }
</script>

<!-- Floating Success Toast -->
@if(session('toast_success') || session('success'))
<div id="success-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-500 ease-out max-w-sm w-full bg-[#121c15]/95 border border-emerald-500/20 rounded-2xl p-4 shadow-[0_8px_32px_rgba(16,185,129,0.15)] backdrop-blur-md flex gap-4 pointer-events-auto">
    <!-- Pulse glowing bubble for the checkmark -->
    <div class="relative flex items-center justify-center shrink-0">
        <div class="absolute inset-0 bg-emerald-500/20 rounded-xl blur animate-pulse"></div>
        <div class="relative w-10 h-10 bg-emerald-500/10 rounded-xl border border-emerald-500/30 flex items-center justify-center text-emerald-400">
            <i class="ph-bold ph-check text-xl"></i>
        </div>
    </div>
    <!-- Toast Content -->
    <div class="flex-1 space-y-1">
        <h4 class="text-xs font-bold text-white tracking-wide">Success Action</h4>
        <p class="text-[11px] text-gray-400 leading-relaxed">{{ session('toast_success') ?? session('success') }}</p>
    </div>
    <!-- Close Button -->
    <button onclick="dismissToast()" class="shrink-0 w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
        <i class="ph ph-x text-xs"></i>
    </button>
    
    <!-- Countdown progress bar -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-950 rounded-b-2xl overflow-hidden">
        <div id="toast-progress" class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 w-full transition-all duration-5000 ease-linear"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toast = document.getElementById('success-toast');
        const progress = document.getElementById('toast-progress');
        if (toast) {
            // Trigger entry animation
            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 100);

            // Progress bar animation
            setTimeout(() => {
                if (progress) progress.style.width = '0%';
            }, 200);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                dismissToast();
            }, 5000);
        }
    });

    function dismissToast() {
        const toast = document.getElementById('success-toast');
        if (toast) {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-10', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }
</script>
@endif

<style>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
    .duration-5000 {
        transition-duration: 5000ms;
    }
</style>

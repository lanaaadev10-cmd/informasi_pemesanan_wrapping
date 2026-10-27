{{-- ============================================
    MODAL & SKRIP: Konfirmasi Hapus & Kosongkan Keranjang
============================================ --}}
<div id="confirm-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div id="modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-[#121212] border border-white/10 rounded-[28px] p-8 max-w-sm w-full mx-4 shadow-2xl animate-modal-in">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#FF6B00]/10 blur-[60px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 text-center space-y-6">
            <div class="w-16 h-16 mx-auto rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center">
                <i class="ph-bold ph-trash-simple text-2xl text-red-400"></i>
            </div>
            <div class="space-y-2">
                <h3 id="modal-title" class="text-lg font-extrabold text-white">Konfirmasi</h3>
                <p id="modal-message" class="text-xs text-gray-400 leading-relaxed"></p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="modal-cancel" class="flex-1 px-5 py-3 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white rounded-2xl font-extrabold text-[10px] tracking-wider uppercase transition-all active:scale-95">
                    Batal
                </button>
                <button type="button" id="modal-confirm" class="flex-1 px-5 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-extrabold text-[10px] tracking-wider uppercase transition-all shadow-[0_4px_15px_rgba(239,68,68,0.3)] active:scale-95">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let confirmResolve = null;

    function showConfirmModal(title, message, confirmText = 'Ya, Hapus') {
        return new Promise((resolve) => {
            confirmResolve = resolve;
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').innerHTML = message;
            document.getElementById('modal-confirm').textContent = confirmText;
            document.getElementById('confirm-modal').classList.remove('hidden');
        });
    }

    document.getElementById('modal-confirm').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(true);
    });

    document.getElementById('modal-cancel').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(false);
    });

    document.getElementById('modal-overlay').addEventListener('click', () => {
        document.getElementById('confirm-modal').classList.add('hidden');
        if (confirmResolve) confirmResolve(false);
    });

    async function confirmEmptyCart(event, form) {
        event.preventDefault();
        const confirmed = await showConfirmModal(
            'Kosongkan Keranjang',
            @js($profil->alert_konfirmasi_kosongkan ?? 'Apakah Anda yakin ingin mengosongkan seluruh isi keranjang? Tindakan ini tidak dapat dibatalkan.'),
            'Ya, Kosongkan'
        );
        if (confirmed) form.submit();
    }

    async function confirmDeleteItem(event, form, itemName) {
        event.preventDefault();
        const confirmed = await showConfirmModal(
            'Hapus Item',
            `<strong>${itemName}</strong><br>` + @js($profil->alert_hapus_keranjang ?? 'Apakah Anda yakin ingin menghapus layanan ini dari keranjang?'),
            'Ya, Hapus'
        );
        if (confirmed) form.submit();
    }
</script>

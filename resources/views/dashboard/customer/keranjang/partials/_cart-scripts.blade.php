<!-- Quantity Adjustment Controller -->
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    async function changeQty(idDetail, delta) {
        const qtySpan = document.getElementById(`qty-${idDetail}`);
        const decBtn = document.getElementById(`btn-dec-${idDetail}`);
        const subtotalSpan = document.getElementById(`subtotal-${idDetail}`);
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryTotal = document.getElementById('summary-total');

        let currentQty = parseInt(qtySpan.textContent);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        // Optimistic UI updates
        qtySpan.textContent = newQty;
        decBtn.disabled = (newQty <= 1);

        try {
            // Update quantity via patch Ajax request ke route web (session + CSRF)
            const response = await fetch('{{ route('keranjang.update', '__ID__') }}'.replace('__ID__', idDetail), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ jumlah: newQty })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const subtotalStr = data.subtotal;
                const totalStr = data.total_payment;
                const totalSum = parseRupiah(totalStr);

                const serviceFee = 150000;
                const grandTotal = totalSum + serviceFee;

                qtySpan.textContent = data.jumlah;
                subtotalSpan.textContent = subtotalStr;

                if (summarySubtotal) {
                    summarySubtotal.textContent = totalStr;
                }
                if (summaryTotal) {
                    summaryTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
                }

                showToast('Keranjang berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (err) {
            qtySpan.textContent = currentQty;
            decBtn.disabled = (currentQty <= 1);
            showToast('Gagal memperbarui keranjang: ' + err.message, 'error');
            console.error('Cart increment adjustment error:', err);
        }
    }

    // Ubah string "Rp 1.500.000" menjadi angka
    function parseRupiah(str) {
        const num = parseInt(String(str || '0').replace(/[^0-9]/g, ''));
        return isNaN(num) ? 0 : num;
    }

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

{{-- Custom Confirmation Modal --}}
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

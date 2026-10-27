<!-- Quantity & Item Selection Controller -->
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const SERVICE_FEE = {{ !empty($profil->keranjang_service_charge_amount) ? (float) $profil->keranjang_service_charge_amount : 0 }};

    // State pemilihan barang (diinisialisasi dari sesi backend)
    let selectedItemIds = @json(array_map('intval', $selectedIds ?? []));
    let allItemIds = @json($keranjang ? $keranjang->details->pluck('id_detail')->map(fn($v)=>(int)$v)->toArray() : []);
    let syncDebounceTimer = null;

    // Format angka ke format Rupiah standar Indonesia
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, Math.round(number)));
    }

    // Ubah string "Rp 1.500.000" menjadi angka numerik
    function parseRupiah(str) {
        const num = parseInt(String(str || '0').replace(/[^0-9]/g, ''));
        return isNaN(num) ? 0 : num;
    }

    // Tangani klik pada kartu barang (cegah klik tombol hapus atau kuantitas agar tidak memicu toggle)
    function handleCardClick(event, idDetail) {
        if (event.target.closest('button, a, input, form, select, textarea')) {
            return;
        }
        toggleItemSelection(idDetail, event);
    }

    // Toggle status pilihan per item
    function toggleItemSelection(idDetail, event) {
        if (event) {
            event.stopPropagation();
        }

        const id = parseInt(idDetail);
        const index = selectedItemIds.indexOf(id);

        if (index > -1) {
            selectedItemIds.splice(index, 1);
        } else {
            selectedItemIds.push(id);
        }

        applySingleItemDomState(id);
        updateSummary();
        syncSelectionWithBackend();
    }

    // Terapkan perubahan visual pada 1 item kartu & checkbox
    function applySingleItemDomState(id) {
        const isSelected = selectedItemIds.includes(id);
        const card = document.getElementById(`cart-item-${id}`);
        const indicator = document.getElementById(`checkbox-indicator-${id}`);
        const icon = document.getElementById(`checkbox-icon-${id}`);

        if (card) {
            if (isSelected) {
                card.classList.add('border-emerald-500/60', 'bg-emerald-50/10', 'shadow-sm', 'ring-1', 'ring-emerald-500/20');
                card.classList.remove('border-slate-200/80', 'opacity-90');
            } else {
                card.classList.remove('border-emerald-500/60', 'bg-emerald-50/10', 'shadow-sm', 'ring-1', 'ring-emerald-500/20');
                card.classList.add('border-slate-200/80', 'opacity-90');
            }
        }

        if (indicator) {
            if (isSelected) {
                indicator.className = 'w-6 h-6 rounded-lg flex items-center justify-center transition-all bg-emerald-500 border-emerald-500 text-white shadow-sm shadow-emerald-500/30';
            } else {
                indicator.className = 'w-6 h-6 rounded-lg flex items-center justify-center transition-all border-2 border-slate-300 bg-white hover:border-emerald-400';
            }
        }

        if (icon) {
            if (isSelected) {
                icon.classList.remove('hidden');
            } else {
                icon.classList.add('hidden');
            }
        }
    }

    // Toggle Pilih Semua
    function toggleSelectAll() {
        if (selectedItemIds.length === allItemIds.length && allItemIds.length > 0) {
            deselectAllItems();
        } else {
            selectAllItems();
        }
    }

    // Pilih Seluruh Barang di Keranjang
    function selectAllItems() {
        selectedItemIds = [...allItemIds];
        allItemIds.forEach(id => applySingleItemDomState(id));
        updateSummary();
        syncSelectionWithBackend();
    }

    // Batalkan Semua Pilihan
    function deselectAllItems() {
        selectedItemIds = [];
        allItemIds.forEach(id => applySingleItemDomState(id));
        updateSummary();
        syncSelectionWithBackend();
    }

    // Hitung ulang ringkasan belanja berdasarkan barang yang dicentang
    function updateSummary() {
        let subtotalTerpilih = 0;

        selectedItemIds.forEach(id => {
            const qtySpan = document.getElementById(`qty-${id}`);
            const subtotalSpan = document.getElementById(`subtotal-${id}`);
            const unitPrice = subtotalSpan ? parseFloat(subtotalSpan.getAttribute('data-unit-price') || '0') : 0;
            const qty = qtySpan ? parseInt(qtySpan.textContent || '1') : 1;
            subtotalTerpilih += (unitPrice * qty);
        });

        const selectedCount = selectedItemIds.length;
        const totalCount = allItemIds.length;
        const fee = selectedCount > 0 ? SERVICE_FEE : 0;
        const grandTotal = subtotalTerpilih + fee;

        // Update tampilan subtotal & total
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryTotal = document.getElementById('summary-total');
        const summarySelectedCount = document.getElementById('summary-selected-count');
        const badgeSelectedCount = document.getElementById('badge-selected-count');
        const summaryTotalCartCount = document.getElementById('summary-total-cart-count');
        const badgeTotalCount = document.getElementById('badge-total-count');

        if (summarySubtotal) summarySubtotal.textContent = formatRupiah(subtotalTerpilih);
        if (summaryTotal) summaryTotal.textContent = formatRupiah(grandTotal);
        if (summarySelectedCount) summarySelectedCount.textContent = selectedCount;
        if (badgeSelectedCount) badgeSelectedCount.textContent = selectedCount;
        if (summaryTotalCartCount) summaryTotalCartCount.textContent = totalCount;
        if (badgeTotalCount) badgeTotalCount.textContent = totalCount;

        // Update indikator tombol "Pilih Semua"
        const checkAllBox = document.getElementById('check-all-box');
        const checkAllIcon = document.getElementById('check-all-icon');
        const labelSelectAll = document.getElementById('label-select-all');

        if (checkAllBox && checkAllIcon && labelSelectAll) {
            if (selectedCount === totalCount && totalCount > 0) {
                checkAllBox.className = 'w-5 h-5 rounded-md flex items-center justify-center transition-all bg-emerald-500 border-emerald-500 text-white shadow-sm';
                checkAllIcon.classList.remove('hidden');
                labelSelectAll.textContent = 'Pilih Semua (Aktif)';
            } else {
                checkAllBox.className = 'w-5 h-5 rounded-md flex items-center justify-center transition-all border-2 border-slate-300 bg-white';
                checkAllIcon.classList.add('hidden');
                labelSelectAll.textContent = 'Pilih Semua';
            }
        }

        // Update tombol Checkout (disabled jika belum ada yang dipilih)
        const btnCheckout = document.getElementById('btn-checkout-submit');
        const btnLabel = document.getElementById('btn-checkout-label');
        const btnDirectBooking = document.getElementById('btn-direct-booking');

        if (btnCheckout && btnLabel) {
            if (selectedCount === 0) {
                btnCheckout.disabled = true;
                btnLabel.textContent = 'Pilih Barang Terlebih Dahulu';
                if (btnDirectBooking) btnDirectBooking.classList.add('hidden');
            } else {
                btnCheckout.disabled = false;
                btnLabel.textContent = `Checkout (${selectedCount} Barang)`;
                if (btnDirectBooking) btnDirectBooking.classList.remove('hidden');
            }
        }

        // Sinkronisasi hidden input form checkout
        const hiddenContainer = document.getElementById('hidden-selected-container');
        if (hiddenContainer) {
            hiddenContainer.innerHTML = '';
            selectedItemIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected[]';
                input.value = id;
                input.className = 'hidden-selected-input';
                hiddenContainer.appendChild(input);
            });
        }
    }

    // Kirim status pilihan ke backend secara otomatis via AJAX
    function syncSelectionWithBackend() {
        clearTimeout(syncDebounceTimer);
        syncDebounceTimer = setTimeout(async () => {
            try {
                await fetch('{{ route('keranjang.pilihan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ selected_ids: selectedItemIds })
                });
            } catch (err) {
                console.warn('Gagal sinkronisasi pilihan keranjang:', err);
            }
        }, 200);
    }

    // Fungsi update jumlah unit dengan recalculation otomatis
    async function changeQty(idDetail, delta) {
        const qtySpan = document.getElementById(`qty-${idDetail}`);
        const decBtn = document.getElementById(`btn-dec-${idDetail}`);
        const subtotalSpan = document.getElementById(`subtotal-${idDetail}`);

        let currentQty = parseInt(qtySpan.textContent);
        let newQty = currentQty + delta;
        if (newQty < 1) return;

        // Optimistic UI updates
        qtySpan.textContent = newQty;
        decBtn.disabled = (newQty <= 1);
        const unitPrice = parseFloat(subtotalSpan.getAttribute('data-unit-price') || '0');
        subtotalSpan.textContent = formatRupiah(unitPrice * newQty);
        updateSummary();

        try {
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
                qtySpan.textContent = data.jumlah;
                subtotalSpan.textContent = data.subtotal;
                updateSummary();
                showToast('Jumlah unit berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Update failed');
            }
        } catch (err) {
            qtySpan.textContent = currentQty;
            decBtn.disabled = (currentQty <= 1);
            subtotalSpan.textContent = formatRupiah(unitPrice * currentQty);
            updateSummary();
            showToast('Gagal memperbarui keranjang: ' + err.message, 'error');
            console.error('Cart increment adjustment error:', err);
        }
    }
</script>

{{-- Toast & Notifikasi Keranjang --}}
@include('dashboard.customer.keranjang.partials._toast_keranjang')

{{-- Modal & Konfirmasi Hapus Keranjang --}}
@include('dashboard.customer.keranjang.partials._modal_konfirmasi_keranjang')


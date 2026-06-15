const CartComponent = {
  async addToCart(layananId, jumlah = 1, catatan = null) {
    UI.loading.show('Menambahkan ke keranjang...');

    const result = await api.keranjang.add(layananId, jumlah, catatan);

    UI.loading.hide();

    if (result.ok) {
      const itemName = result.data?.data?.details?.length > 0
        ? result.data.data.details[result.data.data.details.length - 1]?.layanan?.nama_layanan
        : 'Paket';

      const cartCount = result.data?.data?.details?.length || 1;

      const swalResult = await Swal.fire({
        icon: 'success',
        title: 'Paket Ditambahkan!',
        text: `Paket ${itemName} berhasil dimasukkan ke keranjang`,
        confirmButtonText: 'Lihat Keranjang',
        confirmButtonColor: '#f2994a',
        showCancelButton: true,
        cancelButtonText: 'Lanjut Belanja',
        cancelButtonColor: '#6b7280',
        timer: 3000,
        timerProgressBar: true,
        customClass: {
          popup: 'swal-custom-popup',
          title: 'swal-custom-title',
          confirmButton: 'swal-custom-confirm',
          cancelButton: 'swal-custom-cancel'
        },
        didOpen: () => {
          Swal.getPopup().style.backgroundColor = '#121212';
          Swal.getPopup().style.color = '#ffffff';
          Swal.getPopup().style.border = '1px solid rgba(255,255,255,0.1)';
          Swal.getTitle().style.color = '#f2994a';
        }
      });

      if (swalResult.isConfirmed) {
        window.location.href = '/keranjang';
      }

      CartComponent.updateCartBadge();
      return true;
    } else {
      if (result.data?.status === 'error' && (result.data?.max_items || result.status === 422)) {
        CartComponent.showCartLimitWarning();
      } else {
        UI.toast.error(result.error || 'Gagal menambahkan ke keranjang');
      }
      return false;
    }
  },

  async showCartLimitWarning() {
    const cartResult = await api.keranjang.get();
    let cartItemsHtml = '';

    if (cartResult.ok && cartResult.data?.data?.details) {
      cartItemsHtml = cartResult.data.data.details.map((item, idx) => `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:rgba(255,255,255,0.05);border-radius:8px;margin-bottom:6px;">
          <span style="font-size:12px;color:#fff;">${idx + 1}. ${item.layanan?.nama_layanan || 'Paket'}</span>
          <button onclick="removeCartItem(${item.id_detail})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:14px;padding:4px 8px;">
            <i class="ph ph-trash"></i>
          </button>
        </div>
      `).join('');
    }

    const swalResult = await Swal.fire({
      icon: 'warning',
      title: 'Batas Keranjang Tercapai!',
      html: `
        <div style="text-align:left;color:#d1d5db;font-size:13px;">
          <p style="margin-bottom:12px;">Keranjang Anda sudah penuh (maksimal 3 paket). Hapus salah satu paket untuk menambahkan yang baru.</p>
          <div style="margin-top:12px;border-top:1px solid rgba(255,255,255,0.1);padding-top:12px;">
            <p style="font-size:12px;font-weight:bold;color:#f2994a;margin-bottom:8px;">Paket di Keranjang:</p>
            ${cartItemsHtml}
          </div>
        </div>
      `,
      confirmButtonText: 'Kelola Keranjang',
      confirmButtonColor: '#f2994a',
      showCancelButton: true,
      cancelButtonText: 'Batal',
      cancelButtonColor: '#6b7280',
      customClass: {
        popup: 'swal-custom-popup',
        title: 'swal-custom-title'
      },
      didOpen: () => {
        Swal.getPopup().style.backgroundColor = '#121212';
        Swal.getPopup().style.color = '#ffffff';
        Swal.getPopup().style.border = '1px solid rgba(255,255,255,0.1)';
        Swal.getTitle().style.color = '#f2994a';
      }
    });

    if (swalResult.isConfirmed) {
      window.location.href = '/keranjang';
    }
  },

  async removeCartItem(idDetail) {
    const result = await api.keranjang.remove(idDetail);
    if (result.ok) {
      CartComponent.updateCartBadge();
      const popup = Swal.getPopup();
      if (popup) {
        const btn = popup.querySelector(`button[onclick*="${idDetail}"]`);
        if (btn) btn.closest('div[style*="display:flex"]')?.remove();
      }
      UI.toast.success('Paket berhasil dihapus dari keranjang');
      storage.set('cart_updated', true);
    }
  },

  async updateQuantity(idDetail, jumlah) {
    const result = await api.keranjang.update(idDetail, jumlah);

    if (result.ok) {
      storage.set('cart_updated', true);
      return result.data;
    } else {
      UI.toast.error(result.error || 'Gagal memperbarui jumlah');
      throw result.error;
    }
  },

  async removeFromCart(idDetail) {
    const result = await api.keranjang.remove(idDetail);

    if (result.ok) {
      UI.toast.success('Layanan dihapus dari keranjang');
      storage.set('cart_updated', true);
      CartComponent.updateCartBadge();
      return true;
    } else {
      UI.toast.error(result.error || 'Gagal menghapus layanan');
      return false;
    }
  },

  async emptyCart() {
    const confirmed = await UI.confirm(
      'Apakah Anda yakin ingin mengosongkan seluruh keranjang?',
      async () => {
        UI.loading.show('Mengosongkan keranjang...');
        const result = await api.keranjang.empty();
        UI.loading.hide();

        if (result.ok) {
          UI.toast.success('Keranjang berhasil dikosongkan');
          storage.set('cart_updated', true);
          CartComponent.updateCartBadge();
          setTimeout(() => {
            window.location.href = '/katalog-layanan';
          }, 1000);
        } else {
          UI.toast.error(result.error || 'Gagal mengosongkan keranjang');
        }
      }
    );
  },

  async getCart() {
    const result = await api.keranjang.get();

    if (result.ok) {
      return result.data;
    } else {
      console.error('Error fetching cart:', result.error);
      return null;
    }
  },

  updateCartBadge() {
    api.keranjang.get().then((result) => {
      if (result.ok && result.data?.data?.details) {
        const count = result.data.data.details.length;
        const badges = document.querySelectorAll('[data-cart-badge]');
        badges.forEach((badge) => {
          if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
          } else {
            badge.classList.add('hidden');
          }
        });
      }
    });
  },

  setupCartObserver() {
    setInterval(() => {
      if (storage.get('cart_updated')) {
        CartComponent.updateCartBadge();
        storage.remove('cart_updated');
      }
    }, 500);
  },
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    CartComponent.setupCartObserver();
  });
} else {
  CartComponent.setupCartObserver();
}

window.removeCartItem = (id) => CartComponent.removeCartItem(id);
window.CartComponent = CartComponent;

if (typeof module !== 'undefined' && module.exports) {
  module.exports = CartComponent;
}

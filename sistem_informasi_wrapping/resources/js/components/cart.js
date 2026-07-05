/**
 * Cart Component
 * Handles all cart operations via API
 */

const CartComponent = {
  async addToCart(layananId, jumlah = 1, catatan = null) {
    UI.loading.show('Menambahkan ke keranjang...');

    const result = await api.keranjang.add(layananId, jumlah, catatan);

    UI.loading.hide();

    if (result.ok) {
      UI.toast.success('Layanan berhasil ditambahkan ke keranjang!');
      // Refresh cart count
      CartComponent.updateCartBadge();
      return true;
    } else {
      UI.toast.error(result.error || 'Gagal menambahkan ke keranjang');
      return false;
    }
  },

  async updateQuantity(idDetail, jumlah) {
    const result = await api.keranjang.update(idDetail, jumlah);

    if (result.ok) {
      // Update cart in storage
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
          // Redirect to catalog
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
      if (result.ok && result.data?.data) {
        const count = result.data.data.length;
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
    // Watch for cart updates
    setInterval(() => {
      if (storage.get('cart_updated')) {
        CartComponent.updateCartBadge();
        storage.remove('cart_updated');
      }
    }, 500);
  },
};

// Initialize cart observer when DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    CartComponent.setupCartObserver();
  });
} else {
  CartComponent.setupCartObserver();
}

// Export for module environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = CartComponent;
}

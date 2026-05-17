/**
 * Local Storage Utilities
 * Simple key-value storage with TTL support
 */

const storage = {
  prefix: 'wrapping_app_',

  set: (key, value, ttlMinutes = null) => {
    try {
      const item = {
        value,
        timestamp: Date.now(),
        ttl: ttlMinutes ? ttlMinutes * 60 * 1000 : null,
      };
      localStorage.setItem(storage.prefix + key, JSON.stringify(item));
    } catch (e) {
      console.error('Storage.set error:', e);
    }
  },

  get: (key) => {
    try {
      const item = JSON.parse(localStorage.getItem(storage.prefix + key));
      if (!item) return null;

      if (item.ttl && Date.now() - item.timestamp > item.ttl) {
        storage.remove(key);
        return null;
      }

      return item.value;
    } catch (e) {
      console.error('Storage.get error:', e);
      return null;
    }
  },

  remove: (key) => {
    try {
      localStorage.removeItem(storage.prefix + key);
    } catch (e) {
      console.error('Storage.remove error:', e);
    }
  },

  clear: () => {
    try {
      const keys = Object.keys(localStorage);
      keys.forEach((key) => {
        if (key.startsWith(storage.prefix)) {
          localStorage.removeItem(key);
        }
      });
    } catch (e) {
      console.error('Storage.clear error:', e);
    }
  },

  // Cart specific storage
  cart: {
    get: () => storage.get('cart') || [],
    set: (items) => storage.set('cart', items, 60), // 1 hour
    add: (item) => {
      const cart = storage.cart.get();
      const existing = cart.find((i) => i.id_detail === item.id_detail);
      if (existing) {
        existing.jumlah += item.jumlah || 1;
      } else {
        cart.push(item);
      }
      storage.cart.set(cart);
      return cart;
    },
    remove: (id_detail) => {
      const cart = storage.cart.get().filter((i) => i.id_detail !== id_detail);
      storage.cart.set(cart);
      return cart;
    },
    clear: () => storage.cart.set([]),
  },
};

// Export for module environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = storage;
}

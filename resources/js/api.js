/**
 * Centralized API Client
 * Handles all HTTP requests to backend API with CSRF token injection and error handling
 */

const API_BASE = '/api';
const TIMEOUT = 10000;

class ApiClient {
  constructor() {
    this.baseURL = API_BASE;
    this.timeout = TIMEOUT;
    this.csrfToken = this.getCSRFToken();
  }

  getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }

  async request(method, endpoint, data = null, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    };

    if (this.csrfToken) {
      headers['X-CSRF-TOKEN'] = this.csrfToken;
    }

    const config = {
      method,
      headers: { ...headers, ...options.headers },
      ...options,
    };

    if (data && ['POST', 'PATCH', 'PUT', 'DELETE'].includes(method)) {
      config.body = JSON.stringify(data);
    }

    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), this.timeout);

      const response = await fetch(url, {
        ...config,
        signal: controller.signal,
      });

      clearTimeout(timeoutId);

      const contentType = response.headers.get('content-type');
      const responseData = contentType?.includes('application/json')
        ? await response.json()
        : await response.text();

      if (!response.ok) {
        throw {
          status: response.status,
          data: responseData,
          message: responseData?.message || 'Request failed',
        };
      }

      return {
        ok: true,
        status: response.status,
        data: responseData,
      };
    } catch (error) {
      if (error instanceof TypeError && error.message.includes('Failed to fetch')) {
        return {
          ok: false,
          error: 'Network error. Please check your connection.',
          originalError: error,
        };
      }

      return {
        ok: false,
        status: error.status || 500,
        error: error.message || 'Unknown error occurred',
        data: error.data,
        originalError: error,
      };
    }
  }

  get(endpoint, options = {}) {
    return this.request('GET', endpoint, null, options);
  }

  post(endpoint, data, options = {}) {
    return this.request('POST', endpoint, data, options);
  }

  patch(endpoint, data, options = {}) {
    return this.request('PATCH', endpoint, data, options);
  }

  put(endpoint, data, options = {}) {
    return this.request('PUT', endpoint, data, options);
  }

  delete(endpoint, options = {}) {
    return this.request('DELETE', endpoint, null, options);
  }

  // Layanan (Katalog) endpoints
  layanans = {
    getAll: (filters = {}) => {
      const params = new URLSearchParams();
      if (filters.kategori) params.append('kategori', filters.kategori);
      if (filters.tipe_layanan) params.append('tipe_layanan', filters.tipe_layanan);
      if (filters.search) params.append('search', filters.search);
      if (filters.per_page) params.append('per_page', filters.per_page);

      const query = params.toString() ? `?${params.toString()}` : '';
      return this.get(`/layanans${query}`);
    },
    getOne: (id) => this.get(`/layanans/${id}`),
    getCategories: () => this.get(`/layanans/categories`),
    getByCategory: (kategori, filters = {}) => {
      const params = new URLSearchParams();
      if (filters.per_page) params.append('per_page', filters.per_page);
      const query = params.toString() ? `?${params.toString()}` : '';
      return this.get(`/layanans/kategori/${kategori}${query}`);
    },
  };

  // Keranjang endpoints
  keranjang = {
    get: () => this.get('/keranjang'),
    add: (id_paket, jumlah, catatan_custom = null) =>
      this.post('/keranjang/tambah', { id_paket, jumlah, catatan_custom }),
    update: (id, jumlah) =>
      this.patch(`/keranjang/${id}`, { jumlah }),
    remove: (id) =>
      this.delete(`/keranjang/${id}`),
    empty: () =>
      this.delete('/keranjang'),
  };

  // Pesanan endpoints
  pesanan = {
    getAll: (filters = {}) => {
      const params = new URLSearchParams();
      if (filters.status) params.append('status', filters.status);
      if (filters.per_page) params.append('per_page', filters.per_page);
      const query = params.toString() ? `?${params.toString()}` : '';
      return this.get(`/pesanan${query}`);
    },
    create: (data) => this.post('/pesanan', data),
    getOne: (id) => this.get(`/pesanan/${id}`),
    getStatus: (id) => this.get(`/pesanan/${id}/status`),
    getTimeline: (id) => this.get(`/pesanan/${id}/timeline`),
    uploadBukti: (id, file) => {
      const formData = new FormData();
      formData.append('bukti_transfer', file);

      return this.request('POST', `/pesanan/${id}/upload-bukti`, null, {
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': this.csrfToken,
        },
      });
    },
  };

  // Pembayaran endpoints
  pembayaran = {
    getStatus: (pesanan_id) =>
      this.get(`/pembayaran/${pesanan_id}`),
    verify: (pesanan_id) =>
      this.post(`/pembayaran/${pesanan_id}/verify`, {}),
    getMethods: () =>
      this.get('/pembayaran/methods'),
  };

  // User Profile endpoints
  user = {
    getProfile: () => this.get('/user/profile'),
    updateProfile: (data) => this.patch('/user/profile', data),
    updatePassword: (data) => this.patch('/user/password', data),
    deleteAccount: () => this.delete('/user/profile'),
    getDashboardStats: () => this.get('/user/dashboard/stats'),
  };

  // Notifikasi endpoints
  notifikasi = {
    getAll: (filters = {}) => {
      const params = new URLSearchParams();
      if (filters.type) params.append('type', filters.type);
      if (filters.per_page) params.append('per_page', filters.per_page);
      const query = params.toString() ? `?${params.toString()}` : '';
      return this.get(`/notifikasi${query}`);
    },
    getUnread: () => this.get('/notifikasi/unread'),
    getOne: (id) => this.get(`/notifikasi/${id}`),
    markAsRead: (id) => this.patch(`/notifikasi/${id}`, {}),
    markAllAsRead: () => this.patch('/notifikasi/mark-all-read', {}),
    delete: (id) => this.delete(`/notifikasi/${id}`),
    deleteAll: () => this.delete('/notifikasi'),
  };
}

// Create global instance
const api = new ApiClient();

// Make available globally
if (typeof window !== 'undefined') {
  window.api = api;
}

// Export for module environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = api;
}

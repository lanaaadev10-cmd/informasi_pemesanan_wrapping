/**
 * UI Utilities
 * Toast notifications, loading states, modals
 */

const UI = {
  toast: {
    // Remove previous toast
    clear: () => {
      document.querySelectorAll('[data-toast]').forEach((el) => el.remove());
    },

    show: (message, type = 'info', duration = 3000) => {
      UI.toast.clear();

      const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800',
        warning: 'bg-amber-50 border-amber-200 text-amber-800',
      };

      const icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠',
      };

      const toast = document.createElement('div');
      toast.setAttribute('data-toast', '');
      toast.className = `fixed bottom-6 right-6 p-4 rounded-lg border ${colors[type]} max-w-md z-50 animate-slide-in-up shadow-lg`;
      toast.innerHTML = `
        <div class="flex items-start gap-3">
          <span class="text-lg font-bold shrink-0">${icons[type]}</span>
          <div class="flex-1">
            <p class="text-sm font-medium">${message}</p>
          </div>
          <button onclick="this.closest('[data-toast]').remove()" class="shrink-0 opacity-50 hover:opacity-100">
            ×
          </button>
        </div>
      `;

      document.body.appendChild(toast);

      if (duration > 0) {
        setTimeout(() => toast.remove(), duration);
      }

      return toast;
    },

    success: (message, duration = 3000) => UI.toast.show(message, 'success', duration),
    error: (message, duration = 5000) => UI.toast.show(message, 'error', duration),
    info: (message, duration = 3000) => UI.toast.show(message, 'info', duration),
    warning: (message, duration = 4000) => UI.toast.show(message, 'warning', duration),
  },

  loading: {
    show: (message = 'Loading...') => {
      const loader = document.createElement('div');
      loader.id = 'ui-loading-overlay';
      loader.className = 'fixed inset-0 bg-black/30 backdrop-blur-sm z-40 flex items-center justify-center';
      loader.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex flex-col items-center gap-4">
          <div class="w-10 h-10 border-4 border-stone-200 border-t-[#f2541b] rounded-full animate-spin"></div>
          <p class="text-sm font-medium text-stone-600">${message}</p>
        </div>
      `;
      document.body.appendChild(loader);
      return loader;
    },

    hide: () => {
      document.getElementById('ui-loading-overlay')?.remove();
    },
  },

  confirm: (message, onConfirm, onCancel = null) => {
    return new Promise((resolve) => {
      const modal = document.createElement('div');
      modal.className = 'fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4';
      modal.innerHTML = `
        <div class="bg-white rounded-lg max-w-md p-6 space-y-6">
          <p class="text-sm font-medium text-stone-900">${message}</p>
          <div class="flex gap-3 justify-end">
            <button class="cancel-btn px-4 py-2 rounded-lg text-sm font-medium text-stone-600 hover:bg-stone-50">
              Batal
            </button>
            <button class="confirm-btn px-4 py-2 rounded-lg text-sm font-medium text-white bg-[#f2541b] hover:bg-[#d33d0a]">
              Konfirmasi
            </button>
          </div>
        </div>
      `;

      document.body.appendChild(modal);

      modal.querySelector('.cancel-btn').addEventListener('click', () => {
        modal.remove();
        onCancel?.();
        resolve(false);
      });

      modal.querySelector('.confirm-btn').addEventListener('click', () => {
        modal.remove();
        onConfirm?.();
        resolve(true);
      });
    });
  },
};

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
  @keyframes slide-in-up {
    from {
      transform: translateY(100%);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  .animate-slide-in-up {
    animation: slide-in-up 0.3s ease-out;
  }
`;
document.head.appendChild(style);

// Export for module environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = UI;
}

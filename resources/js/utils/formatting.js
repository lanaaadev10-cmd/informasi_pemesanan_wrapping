/**
 * formatting.js — Utilitas Format Data
 *
 * Berisi fungsi pembantu untuk memformat angka, mata uang (Rupiah),
 * dan tanggal ke format lokal Indonesia.
 *
 * Penggunaan di Blade/skrip inline:
 *   formatters.currency(150000)  → 'Rp 150.000'
 *   formatters.date('2026-09-29') → '29 Sep 2026'
 */

const formatters = {
  currency: (amount) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(amount).replace('IDR', 'Rp');
  },

  number: (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
  },

  date: (date, format = 'short') => {
    const d = new Date(date);
    const options = {
      short: { year: 'numeric', month: 'short', day: 'numeric' },
      long: { year: 'numeric', month: 'long', day: 'numeric' },
      time: { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' },
    };
    return new Intl.DateTimeFormat('id-ID', options[format] || options.short).format(d);
  },

  shortDate: (date) => formatters.date(date, 'short'),
  longDate: (date) => formatters.date(date, 'long'),
  dateTime: (date) => formatters.date(date, 'time'),
};

// ES Module export (digunakan oleh app.js via Vite)
export { formatters };

// Fallback ke window agar bisa diakses dari inline script Blade
if (typeof window !== 'undefined') {
  window.formatters = formatters;
}

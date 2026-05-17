/**
 * Formatting Utilities
 * Currency, numbers, dates formatting
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

// Export for module environments
if (typeof module !== 'undefined' && module.exports) {
  module.exports = formatters;
}

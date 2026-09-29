import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // ─────────────────────────────────────────────────────────────
            //  TIPOGRAFI — Sesuaikan dengan Google Fonts yang dimuat di app.css
            // ─────────────────────────────────────────────────────────────
            fontFamily: {
                sans:       ['Questrial', 'Montserrat', ...defaultTheme.fontFamily.sans],
                audiowide:  ['Audiowide', 'cursive', 'sans-serif'],
                montserrat: ['Montserrat', 'sans-serif'],
                questrial:  ['Questrial', 'sans-serif'],
            },

            // ─────────────────────────────────────────────────────────────
            //  PALET WARNA — "racing" = design system aplikasi ini
            //
            //  Panduan penggunaan:
            //    bg-racing-black        → Latar utama halaman
            //    bg-racing-dark         → Latar area sekunder (0a0a0a)
            //    bg-racing-card         → Surface kartu/form utama
            //    bg-racing-input        → Latar field input form
            //    bg-racing-surface      → Surface elevated (topbar, modal)
            //    text-racing-orange     → Aksen utama (tombol, link, ikon aktif)
            //    text-racing-orangeLight → Aksen lebih cerah/terang
            //    text-racing-muted      → Teks sekunder / placeholder label
            //    border-racing-glass    → Border tipis semi-transparan
            // ─────────────────────────────────────────────────────────────
            colors: {
                racing: {
                    black:        '#000000',       // --bg-carbon
                    dark:         '#0a0a0a',       // --surface-dark (landing bg)
                    card:         '#121212',       // Kartu utama
                    cardLight:    '#181818',       // Kartu hover/elevated
                    input:        '#161616',       // Latar input form
                    surface:      '#16161A',       // --surface-elevated (topbar, modal)
                    surfaceMid:   '#0E0E10',       // Surface sedang
                    formDark:     '#0c0c0c',       // Panel form auth desktop
                    border:       'rgba(255, 255, 255, 0.08)', // --border-glass
                    orange:       '#FF6B00',       // --primary-orange (tombol primer)
                    orangeDark:   '#E05D00',       // hover tombol primer
                    orangeLight:  '#f2994a',       // Aksen terang (auth, landing)
                    orangeHover:  '#e28a44',       // Hover aksen terang
                    orangeBright: '#f97316',       // Orange terang (Tailwind orange-500)
                    muted:        '#8A8D93',       // --text-muted (teks sekunder)
                }
            },

            // ─────────────────────────────────────────────────────────────
            //  BOX SHADOW — Shadow branded
            // ─────────────────────────────────────────────────────────────
            boxShadow: {
                'glow-orange':  '0 4px 20px rgba(242, 153, 74, 0.30)',
                'glow-orange-lg': '0 4px 24px rgba(255, 107, 0, 0.35)',
            },
        },
    },

    plugins: [forms],
};

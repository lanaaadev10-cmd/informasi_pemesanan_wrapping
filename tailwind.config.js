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
            fontFamily: {
                sans: ['Questrial', 'Montserrat', ...defaultTheme.fontFamily.sans],
                audiowide: ['Audiowide', 'cursive', 'sans-serif'],
                montserrat: ['Montserrat', 'sans-serif'],
                questrial: ['Questrial', 'sans-serif'],
            },
            colors: {
                racing: {
                    black: '#000000',
                    dark: '#0a0a0a',
                    card: '#121212',
                    cardLight: '#181818',
                    border: 'rgba(255, 255, 255, 0.08)',
                    orange: '#ff6b00',
                    orangeLight: '#f2994a',
                    orangeBright: '#f97316',
                }
            }
        },
    },

    plugins: [forms],
};


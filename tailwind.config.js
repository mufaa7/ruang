import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                geist: ['Geist', 'Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Instrument Serif', 'Cormorant Garamond', ...defaultTheme.fontFamily.serif],
                mono: ['JetBrains Mono', 'SF Mono', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};

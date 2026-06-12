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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                mono: ['DM Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                'fp-accent':    '#4361EE',
                'fp-accent-h':  '#3451D1',
                'fp-sec':       '#EEF2FF',
                'fp-sec-h':     '#E0E7FF',
                'fp-sec-bd':    '#C7D2FE',
                'fp-sec-bd-h':  '#A5B4FC',
            },
        },
    },

    plugins: [forms],
};
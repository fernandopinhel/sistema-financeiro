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
                // Views use Tailwind's raw `indigo-*`/`red-*` scales as the de facto
                // primary/danger action colors. Overriding them here keeps every existing
                // class name working while making them resolve to the single brand blue
                // (--fp-accent) and brand danger (--fp-danger) instead of Tailwind's stock
                // hues — see design-system/COMPONENT_HIERARCHY.md "achados de inconsistência".
                indigo: {
                    50:  '#EEF2FF',
                    100: '#E0E7FF',
                    200: '#C7D2FE',
                    300: '#A5B4FC',
                    400: '#4361EE',
                    500: '#4361EE',
                    600: '#4361EE',
                    700: '#3451D1',
                    800: '#293F9E',
                    900: '#22337D',
                },
                red: {
                    50:  '#FEF2F2',
                    100: '#FEE2E2',
                    200: '#FECACA',
                    300: '#FCA5A5',
                    400: '#ED6873',
                    500: '#E63946',
                    600: '#E63946',
                    700: '#C42430',
                    800: '#9E1C26',
                    900: '#7F1D1D',
                },
            },
        },
    },

    plugins: [forms],
};
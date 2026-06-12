import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/**
 * vite.config.js
 *
 * ATENÇÃO: @tailwindcss/vite é o plugin do Tailwind v4.
 * Este projeto usa tailwindcss v3 + postcss. Não importar @tailwindcss/vite aqui.
 * O Tailwind v3 é processado automaticamente pelo PostCSS (postcss.config.js).
 */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

/**
 * vite.config.js
 *
 * ATENÇÃO: @tailwindcss/vite é o plugin do Tailwind v4.
 * Este projeto usa tailwindcss v3 + postcss. Não importar @tailwindcss/vite aqui.
 * O Tailwind v3 é processado automaticamente pelo PostCSS (postcss.config.js).
 *
 * PWA: o build do Laravel/Vite gera os assets em `public/build/` (não na raiz
 * pública), então o manifest.webmanifest e o sw.js também nascem lá. Para o
 * service worker controlar o site inteiro (scope "/") a partir de `/build/sw.js`,
 * o header `Service-Worker-Allowed: /` precisa ser enviado por esse arquivo —
 * ver `public/.htaccess`. O registro do SW é feito manualmente em
 * resources/js/app.js (injectRegister: false) apontando pra `/build/sw.js`
 * com scope "/" explícito.
 */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            strategies: 'generateSW',
            registerType: 'autoUpdate',
            injectRegister: false,
            manifestFilename: 'manifest.webmanifest',
            includeManifestIcons: false,
            devOptions: {
                enabled: false,
            },
            manifest: {
                name: 'Finanças FP',
                short_name: 'Finanças FP',
                description: 'Controle financeiro pessoal — transações, categorias e recorrências.',
                lang: 'pt-BR',
                start_url: '/',
                scope: '/',
                display: 'standalone',
                orientation: 'portrait-primary',
                background_color: '#F7F8FA',
                theme_color: '#4361EE',
                icons: [
                    { src: '/icons/icon-192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
                    { src: '/icons/icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
                    { src: '/icons/maskable-icon-192.png', sizes: '192x192', type: 'image/png', purpose: 'maskable' },
                    { src: '/icons/maskable-icon-512.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
                ],
            },
            workbox: {
                // Só precacheia os assets versionados do build (JS/CSS/fontes).
                // Páginas HTML nunca são cacheadas: é um sistema financeiro e
                // servir dados antigos offline é pior do que não ter offline.
                globPatterns: ['**/*.{js,css,woff,woff2}'],
                navigateFallback: null,
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/fonts\.googleapis\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-stylesheets',
                            expiration: { maxEntries: 10, maxAgeSeconds: 60 * 60 * 24 * 365 },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.gstatic\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-webfonts',
                            expiration: { maxEntries: 30, maxAgeSeconds: 60 * 60 * 24 * 365 },
                            cacheableResponse: { statuses: [0, 200] },
                        },
                    },
                ],
            },
        }),
    ],
});
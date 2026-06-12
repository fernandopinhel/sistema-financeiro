/**
 * resources/js/app.js
 *
 * Ponto de entrada principal do JavaScript da aplicação.
 * Alpine.js é registrado globalmente aqui — não deve ser importado
 * em nenhum outro arquivo para evitar instâncias duplicadas.
 */

import './bootstrap';

import Alpine from 'alpinejs';

// ── Registra Alpine globalmente (necessário para x-data inline em Blade) ──
window.Alpine = Alpine;

// ── x-cloak: oculta elementos não inicializados pelo Alpine ──────────────
// Injeta o estilo apenas 1× antes de Alpine.start() rodar
if (!document.getElementById('alpine-cloak-style')) {
    const style = document.createElement('style');
    style.id = 'alpine-cloak-style';
    style.textContent = '[x-cloak] { display: none !important; }';
    document.head.appendChild(style);
}

Alpine.start();

// Função para disparar quando o usuário clicar em "Permitir"
function permitirRastreamento() {
    window.dataLayer = window.dataLayer || [];
    function gtag(){window.dataLayer.push(arguments);}
    gtag('consent', 'update', {
        'analytics_storage': 'granted',
        'ad_storage': 'granted'
    });
    // Salva a preferência para não mostrar o banner novamente
    localStorage.setItem('consentimento_cookies', 'permitido');
    fecharBanner();
}

// Função para quando o usuário clicar em "Recusar"
function recusarRastreamento() {
    window.dataLayer = window.dataLayer || [];
    function gtag(){window.dataLayer.push(arguments);}
    // Mantém como 'denied' (ou garante que está negado)
    gtag('consent', 'update', {
        'analytics_storage': 'denied',
        'ad_storage': 'denied'
    });
    localStorage.setItem('consentimento_cookies', 'negado');
    fecharBanner();
}
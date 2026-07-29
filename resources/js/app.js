/**
 * resources/js/app.js
 *
 * Ponto de entrada principal do JavaScript da aplicação.
 * Alpine.js é registrado globalmente aqui — não deve ser importado
 * em nenhum outro arquivo para evitar instâncias duplicadas.
 */

import './bootstrap';

import Alpine from 'alpinejs';
import sort from '@alpinejs/sort';
import { Passkeys, PasskeyError, UserCancelledError, NotSupportedError, PasskeyExistsError, InvalidDomainError } from '@laravel/passkeys';

// ── Passkeys (Face ID / Biometria) ────────────────────────────────────────
// O cliente já lê a <meta name="csrf-token"> e usa as rotas padrão do
// pacote laravel/passkeys sozinho — nenhuma configuração extra é necessária.
// Exposto em window pra ser chamado pelos scripts inline de login.blade.php
// e profile/edit.blade.php (essas páginas não usam import/bundling próprio).
//
// As mensagens de erro do @laravel/passkeys vêm em inglês (err.message) —
// nunca exibir isso direto pro usuário. translateError() mapeia pelo TIPO
// do erro (instanceof) pra uma mensagem em pt-BR equivalente.
function translatePasskeyError(err) {
    if (err instanceof InvalidDomainError) {
        return 'Face ID/biometria não funciona neste endereço. Se estiver testando localmente, acesse pelo endereço "localhost" em vez de um IP.';
    }
    if (err instanceof NotSupportedError) {
        return 'Este navegador ou dispositivo não suporta Face ID/biometria.';
    }
    if (err instanceof PasskeyExistsError) {
        return 'Este dispositivo já está cadastrado.';
    }
    if (err instanceof UserCancelledError) {
        return 'Operação cancelada.';
    }
    return 'Não foi possível concluir a operação. Tente novamente.';
}

window.FpPasskeys = {
    Passkeys, PasskeyError, UserCancelledError, NotSupportedError, PasskeyExistsError, InvalidDomainError,
    translateError: translatePasskeyError,
};

// ── Registra Alpine globalmente (necessário para x-data inline em Blade) ──
window.Alpine = Alpine;
Alpine.plugin(sort);

// ── PWA install: store global ───────────────────────────────────────────
// Usado só pelo item "Instalar App" no menu do usuário (layouts/app.blade.php)
// — não há mais banner flutuante automático, instalação é sempre uma ação
// explícita do usuário via esse item de menu.
//
// ANDROID/CHROME/EDGE: o navegador dispara `beforeinstallprompt` quando os
// critérios de instalabilidade são atendidos. Guardamos o evento e chamamos
// `.prompt()` quando o usuário clicar no item do menu.
//
// iOS/Safari: não existe API pra instalar programaticamente — a única forma
// é manual (Compartilhar → Adicionar à Tela de Início). `install()` nesse
// caso abre o modal de instruções (#pwa-ios-instructions-modal, definido em
// layouts/app.blade.php) em vez de tentar instalar de verdade.
Alpine.store('pwaInstall', {
    mode: null, // 'android' | 'ios' | null
    deferredPrompt: null,

    init() {
        if (this._isStandalone()) return;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.mode = 'android';
        });

        if (this._isIOS()) {
            this.mode = 'ios';
        }

        window.addEventListener('appinstalled', () => {
            this.mode = null;
            this.deferredPrompt = null;
        });
    },

    get available() {
        return this.mode !== null;
    },

    async install() {
        if (this.mode === 'android' && this.deferredPrompt) {
            this.deferredPrompt.prompt();
            await this.deferredPrompt.userChoice;
            this.deferredPrompt = null;
            return;
        }
        if (this.mode === 'ios') {
            document.getElementById('pwa-ios-instructions-modal')?.showModal();
        }
    },

    _isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
    },

    _isIOS() {
        const ua = window.navigator.userAgent.toLowerCase();
        return /iphone|ipad|ipod/.test(ua)
            || (ua.includes('mac') && navigator.maxTouchPoints > 1); // iPadOS 13+
    },
});

// ── Dashboard arrastável (drag-and-drop) ──────────────────────────────────
// Ordem dos cards de resumo e dos blocos de Gráfico/Categorias é salva só
// no navegador (localStorage) — não sincroniza entre dispositivos, é uma
// preferência de UI, não um dado do usuário. `applyOrder` roda aqui, ANTES
// de `Alpine.start()` processar os `x-sort` da página, pra já nascer com a
// ordem salva sem dar um "pulo" visual depois de renderizada. É um no-op
// em qualquer página que não seja o dashboard (containers não existem).
const FP_DASHBOARD_CARDS_KEY  = 'fp_dashboard_cards_order';
const FP_DASHBOARD_BLOCKS_KEY = 'fp_dashboard_blocks_order';

function fpLoadOrder(key) {
    try {
        const raw = localStorage.getItem(key);
        return raw ? JSON.parse(raw) : null;
    } catch (_) {
        return null;
    }
}

function fpSaveOrder(key, order) {
    try {
        localStorage.setItem(key, JSON.stringify(order));
    } catch (_) {}
}

function fpReadCurrentOrder(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return [];
    return Array.from(container.children)
        .map((el) => el.getAttribute('data-card-key'))
        .filter(Boolean);
}

function fpApplyOrder(containerId, order) {
    if (!order || !order.length) return;
    const container = document.getElementById(containerId);
    if (!container) return;
    order.forEach((cardKey) => {
        const el = container.querySelector('[data-card-key="' + cardKey + '"]');
        if (el) container.appendChild(el); // appendChild em um nó já existente MOVE, não clona
    });
}

fpApplyOrder('dashboard-cards-grid', fpLoadOrder(FP_DASHBOARD_CARDS_KEY));
fpApplyOrder('dashboard-blocks-grid', fpLoadOrder(FP_DASHBOARD_BLOCKS_KEY));

window.FpDashboard = {
    onCardsSort() {
        fpSaveOrder(FP_DASHBOARD_CARDS_KEY, fpReadCurrentOrder('dashboard-cards-grid'));
    },
    onBlocksSort() {
        fpSaveOrder(FP_DASHBOARD_BLOCKS_KEY, fpReadCurrentOrder('dashboard-blocks-grid'));
    },
};

// ── x-cloak: oculta elementos não inicializados pelo Alpine ──────────────
// Injeta o estilo apenas 1× antes de Alpine.start() rodar
if (!document.getElementById('alpine-cloak-style')) {
    const style = document.createElement('style');
    style.id = 'alpine-cloak-style';
    style.textContent = '[x-cloak] { display: none !important; }';
    document.head.appendChild(style);
}

Alpine.start();

// ── PWA: registro do Service Worker ───────────────────────────────────────
// Só em produção: o build do Vite escreve o sw.js em /build/sw.js (mesma
// pasta dos outros assets), mas ele precisa controlar o site inteiro
// (scope "/") para a instalação do app funcionar. O header
// `Service-Worker-Allowed: /` (ver public/.htaccess) autoriza esse escopo
// mais amplo mesmo o arquivo não estando na raiz — mas só funciona atrás
// de Apache com mod_headers (produção/XAMPP). O `php artisan serve` local
// ignora .htaccess, então o registro com scope "/" falha nesse ambiente;
// nesse caso caímos pro scope padrão ("/build/") pra não travar o console
// nem impedir os testes locais.
function registerServiceWorker(registration) {
    // Ativação automática (equivalente ao registerType "autoUpdate" do
    // vite-plugin-pwa, que não se aplica automaticamente porque o registro
    // é manual aqui): ao detectar um SW novo instalado, manda SKIP_WAITING
    // pra ele assumir o controle imediatamente.
    registration.addEventListener('updatefound', () => {
        const newWorker = registration.installing;
        newWorker?.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                newWorker.postMessage({ type: 'SKIP_WAITING' });
            }
        });
    });
}

if (import.meta.env.PROD && 'serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker
            .register('/build/sw.js', { scope: '/' })
            .then(registerServiceWorker)
            .catch(() => {
                // Sem o header Service-Worker-Allowed (ex.: dev local via
                // `php artisan serve`) — registra com o escopo padrão do
                // diretório mesmo, só sem cobrir o site inteiro.
                navigator.serviceWorker
                    .register('/build/sw.js')
                    .then(registerServiceWorker)
                    .catch((err) => console.error('[PWA] Falha ao registrar o Service Worker:', err));
            });

        let refreshingAfterUpdate = false;
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (refreshingAfterUpdate) return;
            refreshingAfterUpdate = true;
            window.location.reload();
        });
    });
}

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
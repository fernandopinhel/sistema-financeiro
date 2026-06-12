<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Finanças FP - @yield('title', 'Dashboard')</title>

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    {{-- Fontes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    {{-- ════════════════════════════════════════════════════════════════
         GOOGLE TAG MANAGER — CONSENT MODE V2  (LGPD-compliant)
         ────────────────────────────────────────────────────────────────
         Estratégia (corrigida):
         1. Lê o cookie fp_cookie_consent server-side
         2. Se 'rejected' → NENHUM script de rastreamento é injetado
         3. Se 'accepted' → GTM + GA4 + Hotjar carregam normalmente
         4. Se 'pending'  → GTM carrega com analytics_storage='denied'
                            e wait_for_update=500 para aguardar o banner
         5. Ao aceitar no banner/privacidade → injeção dinâmica + reload
            não é necessário porque os scripts ainda não existiam
         6. Ao RECUSAR no banner/privacidade → salva cookie e RECARREGA
            a página, garantindo que o servidor não injete nada

         POR QUE RELOAD AO RECUSAR?
         gtag('consent','update') só afeta hits FUTUROS — não desfaz
         scripts que já foram carregados e já dispararam no page load.
         O reload é a única forma de parar coleta já iniciada (LGPD art. 8).

         IMPORTANTE: ad_storage permanece 'denied' permanentemente.
         ════════════════════════════════════════════════════════════════ --}}

    @php
        $consent         = request()->cookie('fp_cookie_consent');
        $consentAccepted = $consent === 'accepted';
        $consentRejected = $consent === 'rejected';
        $consentPending  = !$consentAccepted && !$consentRejected;
    @endphp

    {{-- Só injeta scripts de rastreamento se NÃO foi recusado --}}
    @if(!$consentRejected)

    {{-- PASSO 1 — Consent default (ANTES do GTM) --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }

        gtag('consent', 'default', {
            'analytics_storage':     '{{ $consentAccepted ? "granted" : "denied" }}',
            'ad_storage':            'denied',
            'ad_user_data':          'denied',
            'ad_personalization':    'denied',
            'functionality_storage': 'granted',
            'security_storage':      'granted',
            @if($consentPending)
            'wait_for_update':       500
            @endif
        });

        gtag('set', 'ads_data_redaction', true);
    </script>

    {{-- PASSO 2 — GTM (apenas se não recusado) --}}
    <script>
        (function(w,d,s,l,i){
            w[l]=w[l]||[];
            w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
            var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),
                dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;
            j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
            f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TRDMX2K5');
    </script>

    {{-- PASSO 3 — GA4 direto (somente se aceito) --}}
    @if($consentAccepted)
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-56BXHJ5LNW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-56BXHJ5LNW');
    </script>
    @endif

    {{-- PASSO 4 — Hotjar (somente se aceito) 
    @if($consentAccepted)
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:6722011,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    @endif--}}

    @endif {{-- fim @if(!$consentRejected) --}}

    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:6722011,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

</head>
<body class="h-full">

    {{-- GTM noscript — somente se o rastreamento não foi recusado --}}
    @if(!$consentRejected)
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TRDMX2K5"
                height="0" width="0"
                style="display:none;visibility:hidden"
                title="Google Tag Manager"></iframe>
    </noscript>
    @endif

    <div class="fp-overlay" id="fp-overlay"></div>

    {{-- ── Sidebar ── --}}
    <aside class="fp-sidebar" id="fp-sidebar" aria-label="Menu principal">
        <div class="fp-sidebar-logo">
            <div class="logo-icon" aria-hidden="true">FP</div>
            <div class="logo-text">Finanças <span>FP</span></div>
        </div>

        <nav class="fp-nav" aria-label="Navegação principal">
            <span class="fp-nav-section">Principal</span>

            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
               aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('transacoes.index') }}"
               class="{{ request()->routeIs('transacoes.*') ? 'active' : '' }}"
               aria-current="{{ request()->routeIs('transacoes.*') ? 'page' : 'false' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transações
            </a>

            <span class="fp-nav-section">Configurações</span>

            <a href="{{ route('categories.index') }}"
               class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"
               aria-current="{{ request()->routeIs('categories.*') ? 'page' : 'false' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                </svg>
                Categorias
            </a>

            <a href="{{ route('recorrentes.index') }}"
                class="{{ request()->routeIs('recorrentes.*') ? 'active' : '' }}"
                aria-current="{{ request()->routeIs('recorrentes.*') ? 'page' : 'false' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Recorrentes
            </a>

            <a href="{{ route('privacidade') }}"
               class="{{ request()->routeIs('privacidade') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                Privacidade
            </a>
        </nav>

        {{-- User menu --}}
        <div class="fp-user-menu">
            <button class="fp-user-btn" id="fp-user-btn"
                    aria-expanded="false" aria-haspopup="true" aria-controls="fp-user-dropdown">

                <div class="fp-user-avatar" aria-hidden="true">
                    @php
                        $avatarUrl = Auth::user()->avatar_url;
                        $initials  = collect(explode(' ', trim(Auth::user()->name)))
                            ->filter()
                            ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                            ->take(2)
                            ->implode('');
                    @endphp
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="" class="fp-user-avatar-img">
                    @else
                        <span>{{ $initials }}</span>
                    @endif
                </div>

                <div class="fp-user-info">
                    <div class="fp-user-name">{{ Auth::user()->name }}</div>
                    <div class="fp-user-email">{{ Auth::user()->email }}</div>
                </div>

                <svg class="fp-user-chevron w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div class="fp-dropdown" id="fp-user-dropdown" role="menu" aria-labelledby="fp-user-btn">
                <a href="{{ route('profile.edit') }}" role="menuitem">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    Meu Perfil
                </a>
                <a href="{{ route('privacidade') }}" role="menuitem">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    Privacidade & Cookies
                </a>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="danger" role="menuitem">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main ── --}}
    <main class="fp-main" role="main">
        <header class="fp-topbar">
            <button class="fp-hamburger" id="fp-hamburger"
                    aria-label="Abrir menu" aria-expanded="false" aria-controls="fp-sidebar">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <span class="fp-topbar-title">@yield('title', 'Dashboard')</span>

            <div class="fp-topbar-actions">
                @stack('topbar-actions')
            </div>
        </header>

        <div class="fp-content">
            @if(session('success'))
                <div class="fp-alert fp-alert-success mb-6" role="alert">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="fp-alert fp-alert-error mb-6" role="alert">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Banner de cookies --}}
    @include('components.cookie-banner')

    <script>
    (function () {
        'use strict';

        // ── Sidebar ──────────────────────────────────────────────────
        const sidebar   = document.getElementById('fp-sidebar');
        const hamburger = document.getElementById('fp-hamburger');
        const overlay   = document.getElementById('fp-overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            hamburger.setAttribute('aria-expanded', 'true');
            hamburger.setAttribute('aria-label', 'Fechar menu');
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
            hamburger.setAttribute('aria-label', 'Abrir menu');
        }

        hamburger?.addEventListener('click', () =>
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
        );
        overlay?.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });

        // ── Dropdown do usuário ───────────────────────────────────────
        const userBtn      = document.getElementById('fp-user-btn');
        const userDropdown = document.getElementById('fp-user-dropdown');

        userBtn?.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = userDropdown.classList.toggle('open');
            userBtn.setAttribute('aria-expanded', String(isOpen));
        });
        document.addEventListener('click', () => {
            userDropdown?.classList.remove('open');
            userBtn?.setAttribute('aria-expanded', 'false');
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                userDropdown?.classList.remove('open');
                userBtn?.setAttribute('aria-expanded', 'false');
                userBtn?.focus();
            }
        });

        // ── Consent Mode v2 — responde em tempo real ─────────────────
        //
        // AO ACEITAR: injeta GA4 + Hotjar dinamicamente (sem reload).
        //   Scripts ainda não existiam na sessão → injeção dinâmica é suficiente.
        //
        // AO RECUSAR: salva cookie e RECARREGA a página.
        //   Motivo: scripts de rastreamento já foram carregados no page load
        //   (ex: usuário tinha 'accepted' antes e mudou para 'rejected').
        //   gtag('consent','update') só afeta hits futuros — não desfaz coleta
        //   já iniciada. O reload garante que o servidor não injete nada (LGPD).
        window.addEventListener('fp:consent-updated', function (e) {
            const accepted = e.detail === 'accepted';

            if (accepted) {
                // Atualiza consent mode para tags futuras no GTM
                if (typeof gtag === 'function') {
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted'
                    });
                }

                // Injeta GA4 dinamicamente se ainda não carregado
                if (!document.querySelector('script[src*="G-56BXHJ5LNW"]')) {
                    const s = document.createElement('script');
                    s.async = true;
                    s.src   = 'https://www.googletagmanager.com/gtag/js?id=G-56BXHJ5LNW';
                    document.head.appendChild(s);
                    s.onload = function () {
                        window.dataLayer = window.dataLayer || [];
                        gtag('js', new Date());
                        gtag('config', 'G-56BXHJ5LNW');
                    };
                }

                // Injeta Hotjar dinamicamente se ainda não carregado
                if (!window.hj) {
                    (function(h,o,t,j,a,r){
                        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                        h._hjSettings={hjid:6722011,hjsv:6};
                        a=o.getElementsByTagName('head')[0];
                        r=o.createElement('script');r.async=1;
                        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                        a.appendChild(r);
                    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
                }

            } else {
                // RECUSAR: recarregar para que o servidor não injete nenhum script
                // O cookie já foi salvo pelo banner/privacyCenter antes de disparar este evento
                window.location.reload();
            }
        });

    })();
    </script>

    @stack('scripts')
</body>
</html>
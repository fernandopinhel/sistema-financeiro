<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Finanças FP - @isset($title){{ $title }}@else Acesso @endisset</title>

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- reCAPTCHA --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ════════════════════════════════════════════════════════════════
         GOOGLE TAG MANAGER — CONSENT MODE V2  (LGPD-compliant)
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

    {{-- PASSO 4 — Hotjar (somente se aceito) --}}
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
    @endif

    @endif {{-- fim @if(!$consentRejected) --}}
</head>
<body style="background: var(--fp-bg); font-family: var(--fp-sans); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 16px;">

    {{-- GTM noscript — somente se não recusado --}}
    @if(!$consentRejected)
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TRDMX2K5"
                height="0" width="0"
                style="display:none;visibility:hidden"
                title="Google Tag Manager"></iframe>
    </noscript>
    @endif

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="fp-guest-logo" aria-label="Finanças FP">
        <div class="fp-guest-logo-icon">FP</div>
        <span class="fp-guest-logo-text">Finanças <em>FP</em></span>
    </a>

    {{-- Card --}}
    <div>
        {{-- Se for um componente, renderiza o $slot. Se não, renderiza o yield --}}
        @if(isset($slot) && !empty($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </div>

    {{-- Footer --}}
    <p class="fp-guest-footer">
        <a href="{{ route('privacidade') }}" class="fp-guest-footer-link">
            Diretrizes de Privacidade
        </a>
    </p>

    @include('components.cookie-banner')

    {{-- Consent Mode v2 — mesmo comportamento do app.blade.php
         Aceitar → injeção dinâmica | Recusar → reload --}}
    <script>
    window.addEventListener('fp:consent-updated', function (e) {
        const accepted = e.detail === 'accepted';

        if (accepted) {
            if (typeof gtag === 'function') {
                gtag('consent', 'update', { 'analytics_storage': 'granted' });
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
            // Recusar → reload para que o servidor não injete nenhum script
            window.location.reload();
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
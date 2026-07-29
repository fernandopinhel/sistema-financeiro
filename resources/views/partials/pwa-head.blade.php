{{--
    resources/views/partials/pwa-head.blade.php

    Tags necessárias para o site ser instalável como PWA no Android/Chrome
    e adicionável à tela de início no iOS/Safari. Incluído em app.blade.php
    e guest.blade.php para que a instalação funcione mesmo antes do login.
--}}

{{-- Manifest: gerado pelo vite-plugin-pwa dentro de public/build/ --}}
<link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">

{{-- Cor da barra de status / moldura do app --}}
<meta name="theme-color" content="#4361EE">

{{-- iOS/Safari: não segue o manifest.json, precisa das meta tags abaixo --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Finanças FP">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

{{-- Android/Chrome instalado: evita comportamento de navegador (pull-to-refresh, etc.) --}}
<meta name="mobile-web-app-capable" content="yes">

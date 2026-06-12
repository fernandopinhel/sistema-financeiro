<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} - {{ $title }} · Finanças FP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --fp-bg:      #F7F8FA;
            --fp-surface: #FFFFFF;
            --fp-border:  #E4E7EE;
            --fp-accent:  #4361EE;
            --fp-text:    #1A1D2E;
            --fp-muted:   #6B7280;
            --fp-danger:  #E63946;
            --fp-warning: #F4A261;
            --fp-sans:    'DM Sans', system-ui, sans-serif;
            --fp-mono:    'DM Mono', monospace;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--fp-sans);
            background: var(--fp-bg);
            color: var(--fp-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            text-align: center;
        }
        .err-logo {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; margin-bottom: 40px;
        }
        .err-logo-icon {
            width: 44px; height: 44px; border-radius: 13px;
            background: var(--fp-accent);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--fp-mono); font-size: 16px; font-weight: 700;
            color: #fff; letter-spacing: -.5px;
            box-shadow: 0 4px 12px rgba(67,97,238,.3);
            flex-shrink: 0;
        }
        .err-logo-text {
            font-size: 20px; font-weight: 700; color: var(--fp-text);
        }
        .err-logo-text em { color: var(--fp-accent); font-style: normal; }
        .err-card {
            width: 100%; max-width: 480px;
            background: var(--fp-surface);
            border: 1px solid var(--fp-border);
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow: 0 4px 32px rgba(26,29,46,.08);
        }
        .err-icon {
            width: 72px; height: 72px; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .err-icon svg { width: 36px; height: 36px; }
        .err-code {
            font-family: var(--fp-mono); font-size: 13px; font-weight: 500;
            letter-spacing: .08em; text-transform: uppercase;
            margin-bottom: 8px;
        }
        .err-title {
            font-size: 24px; font-weight: 700; letter-spacing: -.3px;
            margin-bottom: 12px; color: var(--fp-text);
        }
        .err-desc {
            font-size: 14px; color: var(--fp-muted); line-height: 1.6;
            margin-bottom: 32px;
        }
        .err-actions {
            display: flex; flex-direction: column; gap: 10px;
        }
        .err-btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 24px; border-radius: 10px;
            font-size: 14px; font-weight: 600; font-family: var(--fp-sans);
            color: #fff; text-decoration: none;
            background: var(--fp-accent);
            border: none; cursor: pointer; transition: all .15s;
        }
        .err-btn-primary:hover {
            background: #3451D1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67,97,238,.3);
        }
        .err-btn-ghost {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 24px; border-radius: 10px;
            font-size: 14px; font-weight: 600; font-family: var(--fp-sans);
            color: var(--fp-muted); text-decoration: none;
            background: transparent;
            border: 1.5px solid var(--fp-border);
            cursor: pointer; transition: all .15s;
        }
        .err-btn-ghost:hover { background: var(--fp-bg); color: var(--fp-text); }
        .err-footer {
            margin-top: 32px; font-size: 12px; color: var(--fp-muted);
        }
        .err-footer a { color: var(--fp-muted); text-decoration: underline; }
        .err-footer a:hover { color: var(--fp-accent); }
        @media (max-width: 480px) {
            .err-card { padding: 32px 20px; border-radius: 16px; }
            .err-title { font-size: 20px; }
        }
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="err-logo" aria-label="Finanças FP — ir para o início">
        <div class="err-logo-icon" aria-hidden="true">FP</div>
        <span class="err-logo-text">Finanças <em>FP</em></span>
    </a>

    <div class="err-card" role="main">
        <div class="err-icon" style="background:{{ $iconBg }};">
            {!! $icon !!}
        </div>

        <p class="err-code" style="color:{{ $codeColor }};">Erro {{ $code }}</p>
        <h1 class="err-title">{{ $title }}</h1>
        <p class="err-desc">{{ $description }}</p>

        <div class="err-actions">
            {!! $actions !!}
        </div>
    </div>

    <p class="err-footer">
        <a href="{{ route('privacidade') }}">Diretrizes de Privacidade</a>
    </p>

</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Redefinição de Senha — Finanças FP</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt; mso-table-rspace:0pt; }
        img { -ms-interpolation-mode:bicubic; border:0; outline:none; text-decoration:none; }
        * { box-sizing:border-box; }

        body {
            margin:0; padding:0;
            background-color:#F7F8FA;
            font-family:'DM Sans',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
        }
        .email-wrapper { width:100%; background-color:#F7F8FA; padding:40px 16px; }
        .email-container {
            max-width:520px; margin:0 auto;
            background:#ffffff;
            border:1px solid #E4E7EE;
            border-radius:20px;
            overflow:hidden;
        }

        /* Header */
        .email-header { background:#1A1D2E; padding:32px 40px; text-align:center; }
        .email-logo-box { display:inline-flex; align-items:center; gap:10px; justify-content: center;}
        .email-logo-icon {
            width:44px; height:44px; background:#4361EE; border-radius:12px;
            font-family:'Courier New',monospace; font-size:16px; font-weight:700;
            color:#fff; text-align:center; line-height:44px; display:inline-block;
            margin-right: 8px;
        }
        .email-logo-text { font-size:20px; font-weight:700; color:#fff; }
        .email-logo-text span { color:#4361EE; }

        /* Body */
        .email-body { padding:36px 40px; }
        .email-title { font-size:22px; font-weight:700; color:#1A1D2E; margin:0 0 12px; line-height:1.3; }
        .email-text  { font-size:15px; color:#6B7280; line-height:1.7; margin:0 0 28px; }
        .email-text strong { color:#1A1D2E; }

        /* CTA */
        .email-cta   { text-align:center; margin:0 0 28px; }
        .email-btn   {
            display:inline-block; background:#4361EE; color:#fff !important;
            text-decoration:none; font-size:15px; font-weight:700;
            padding:14px 36px; border-radius:12px;
        }

        /* Divider */
        .email-divider { border:none; border-top:1px solid #E4E7EE; margin:28px 0; }

        /* Link fallback */
        .email-link-fallback { font-size:13px; color:#6B7280; margin:0 0 8px; }
        .email-link-url      { font-size:12px; color:#4361EE; word-break:break-all; }

        /* Warning */
        .email-warning {
            background:#FFF7ED; border:1px solid #FED7AA;
            border-radius:10px; padding:12px 16px;
            font-size:13px; color:#92400E; margin:20px 0 0; line-height:1.6;
        }

        /* Footer */
        .email-footer { background:#F7F8FA; border-top:1px solid #E4E7EE; padding:20px 40px; text-align:center; }
        .email-footer p { font-size:12px; color:#9CA3AF; margin:0; line-height:1.6; }

        @media (max-width:560px) {
            .email-body   { padding:28px 24px; }
            .email-header { padding:24px 20px; }
            .email-footer { padding:16px 24px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        <div class="email-header">
            <div class="email-logo-box">
                <div class="email-logo-icon">FP</div>
                <span class="email-logo-text">Finanças <span>FP</span></span>
            </div>
        </div>

        <div class="email-body">
            <h1 class="email-title">Redefinição de senha</h1>

            <p class="email-text">
                Olá, <strong>{{ $user->name ?? 'usuário' }}</strong>!<br><br>
                Recebemos uma solicitação para redefinir a senha da sua conta no
                <strong>Finanças FP</strong>. Clique no botão abaixo para criar uma nova senha.
            </p>

            <div class="email-cta">
                <a href="{{ $actionUrl }}" class="email-btn" target="_blank">
                    Redefinir minha senha
                </a>
            </div>

            <hr class="email-divider">

            <p class="email-link-fallback">
                Se o botão não funcionar, copie e cole o link abaixo no seu navegador:
            </p>
            <p class="email-link-url">{{ $actionUrl }}</p>

            <div class="email-warning">
                ⏰ <strong>Este link expira em {{ $expireTimeString ?? '60 minutos' }}</strong>.
                Se você não solicitou a redefinição de senha, ignore este e-mail
                — sua conta permanece segura.
            </div>
        </div>

        <div class="email-footer">
            <p>
                © {{ date('Y') }} <strong>Finanças FP</strong> · Todos os direitos reservados<br>
                Este é um e-mail automático, por favor não responda.
            </p>
        </div>

    </div>
</div>
</body>
</html>
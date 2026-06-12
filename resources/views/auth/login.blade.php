<x-guest-layout>
<div class="fp-guest-card">

    <h2 class="fp-auth-title">Bem-vindo</h2>
    <p class="fp-auth-subtitle">Gerencie seus gastos de forma simples.</p>

    {{-- Status (ex: senha redefinida) --}}
    @if(session('status'))
    <div class="fp-alert fp-alert-success" role="alert" aria-live="polite" style="margin-bottom:20px;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        {{ session('status') }}
    </div>
    @endif

    {{-- Erro do OAuth --}}
    @if($errors->has('social'))
    <div class="fp-alert fp-alert-error" role="alert" aria-live="assertive" style="margin-bottom:20px;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        {{ $errors->first('social') }}
    </div>
    @endif

    <form
        id="js-login-form"
        method="POST"
        action="{{ route('login') }}"
        novalidate
        data-testid="login-form"
        aria-label="Formulário de acesso"
        class="fp-auth-form"
    >
        @csrf

        {{-- E-mail --}}
        <div class="fp-field">
            <label for="email" class="fp-label">E-mail</label>
            <div class="fp-input-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com"
                    required
                    autofocus
                    autocomplete="username"
                    data-testid="input-email"
                    class="fp-input {{ $errors->has('email') ? 'error' : '' }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    aria-describedby="email-err"
                >
            </div>
            <p id="email-err" class="fp-field-error" role="alert" aria-live="assertive"
               @unless($errors->has('email')) hidden @endunless>
                @error('email')
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- Senha --}}
        <div class="fp-field">
            <label for="password" class="fp-label">Senha</label>
            <div class="fp-input-icon fp-input-icon--has-toggle">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                    data-testid="input-password"
                    class="fp-input {{ $errors->has('password') ? 'error' : '' }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    aria-describedby="pass-err"
                >
                <button
                    type="button"
                    class="fp-toggle-pw"
                    data-target="password"
                    aria-label="Mostrar senha"
                    data-testid="btn-toggle-password"
                >
                    <svg class="icon-eye" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <svg class="icon-eye-off" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
            <p id="pass-err" class="fp-field-error" role="alert" aria-live="assertive"
               @unless($errors->has('password')) hidden @endunless>
                @error('password')
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- Lembrar de mim + Esqueci a senha --}}
        <div class="fp-remember-row">
            <label class="fp-checkbox-label" data-testid="remember-label">
                <input
                    type="checkbox"
                    name="remember"
                    class="fp-checkbox"
                    data-testid="input-remember"
                >
                <span>Lembrar de mim</span>
            </label>
            @if(Route::has('password.request'))
            <a
                href="{{ route('password.request') }}"
                class="fp-link-sm js-track-forgot-password"
                data-testid="link-forgot-password"
            >Esqueci a senha</a>
            @endif
        </div>

        {{-- Divisor "ou" --}}
        <div class="fp-or-divider" role="separator" aria-hidden="true">
            <span>ou</span>
        </div>

        {{-- Login com Google --}}
        <a
            href="{{ route('social.redirect', 'google') }}"
            class="fp-btn-google js-track-google-login"
            data-testid="btn-google-login"
            aria-label="Entrar com sua conta Google"
        >
            <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Entrar com Google
        </a>

        {{-- reCAPTCHA --}}
        <div>
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}" data-testid="recaptcha"></div>
            @error('g-recaptcha-response')
            <p class="fp-field-error" role="alert" style="margin-top:8px;">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Enviar --}}
        <button
            type="submit"
            class="fp-btn fp-btn-primary fp-btn-full js-track-login-submit"
            data-testid="btn-login-submit"
        >
            Entrar na conta
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </button>

        <p class="fp-auth-footer-text">
            Não tem conta?
            <a href="{{ route('register') }}" class="fp-link js-track-register-link" data-testid="link-register">
                Criar conta grátis
            </a>
        </p>

    </form>
</div>

@push('scripts')
<style>
/* ── Auth local styles ──────────────────────────────────────────── */
.fp-auth-title   { font-size: 22px; font-weight: 700; color: var(--fp-text); letter-spacing: -.3px; margin: 0 0 4px; }
.fp-auth-subtitle{ color: var(--fp-muted); font-size: 14px; margin: 0 0 24px; }
.fp-auth-form    { display: flex; flex-direction: column; gap: 16px; }

.fp-field { display: flex; flex-direction: column; gap: 0; }

.fp-field-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: 6px; font-size: 12px; font-weight: 500;
    color: var(--fp-danger);
}
.fp-field-error svg { color: var(--fp-danger); flex-shrink: 0; }

/* Botão toggle de senha */
.fp-input-icon--has-toggle .fp-input { padding-right: 40px; }
.fp-toggle-pw {
    position: absolute; right: 10px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--fp-muted); padding: 4px;
    display: flex; align-items: center; border-radius: 4px;
    transition: color .15s;
}
.fp-toggle-pw:hover { color: var(--fp-text); }
.fp-toggle-pw:focus-visible { outline: 2px solid var(--fp-accent); outline-offset: 1px; }

/* Estado de sucesso no input */
.fp-input.success {
    border-color: var(--fp-success);
    box-shadow: 0 0 0 3px rgba(46,196,182,.12);
}

/* Linha remember + forgot */
.fp-remember-row {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
}
.fp-checkbox-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; color: var(--fp-text); cursor: pointer;
}
.fp-checkbox {
    width: 16px; height: 16px; border-radius: 4px;
    accent-color: var(--fp-accent); cursor: pointer; flex-shrink: 0;
}
.fp-link-sm {
    font-size: 12px; font-weight: 600;
    color: var(--fp-accent); text-decoration: none; transition: opacity .15s;
    white-space: nowrap;
}
.fp-link-sm:hover { opacity: .75; }

/* Divisor "ou" */
.fp-or-divider {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; color: var(--fp-muted);
}
.fp-or-divider::before, .fp-or-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--fp-border);
}

/* Botão Google outline */
.fp-btn-google {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 11px 16px; border-radius: 10px;
    font-size: 14px; font-weight: 600; font-family: var(--fp-sans);
    color: var(--fp-text); text-decoration: none;
    background: var(--fp-surface);
    border: 1.5px solid #C9CDD6;
    box-shadow: 0 1px 2px rgba(26,29,46,.06);
    transition: background .15s, border-color .15s, box-shadow .15s;
    cursor: pointer;
}
.fp-btn-google:hover {
    background: var(--fp-bg);
    border-color: #9ca3af;
    box-shadow: 0 2px 6px rgba(26,29,46,.10);
}
.fp-btn-google:focus-visible {
    outline: 2px solid var(--fp-accent);
    outline-offset: 2px;
}

/* Footer link */
.fp-link {
    font-weight: 600; color: var(--fp-accent);
    text-decoration: none; transition: opacity .15s;
}
.fp-link:hover { opacity: .75; }
.fp-auth-footer-text {
    text-align: center; font-size: 14px; color: var(--fp-muted); margin: 0;
}

/* Autofill override — remove browser blue/yellow background */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px var(--fp-surface) inset !important;
    -webkit-text-fill-color: var(--fp-text) !important;
    caret-color: var(--fp-text);
    transition: background-color 5000s ease-in-out 0s;
}
</style>

<script>
(function () {
    'use strict';

    var ERR_ICON = '<svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ';

    function setError(id, msg) {
        var input = document.getElementById(id);
        var err   = document.getElementById(id + '-err');
        if (!input) return;
        input.classList.add('error');
        input.classList.remove('success');
        input.setAttribute('aria-invalid', 'true');
        if (err) { err.innerHTML = ERR_ICON + msg; err.removeAttribute('hidden'); }
    }

    function clearError(id) {
        var input = document.getElementById(id);
        var err   = document.getElementById(id + '-err');
        if (!input) return;
        input.classList.remove('error');
        input.setAttribute('aria-invalid', 'false');
        if (err) { err.innerHTML = ''; err.setAttribute('hidden', ''); }
    }

    function setSuccess(id) {
        var input = document.getElementById(id);
        if (!input) return;
        input.classList.remove('error');
        input.classList.add('success');
        input.setAttribute('aria-invalid', 'false');
        var err = document.getElementById(id + '-err');
        if (err) { err.innerHTML = ''; err.setAttribute('hidden', ''); }
    }

    function isValidEmail(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val);
    }

    /* ── Email ── */
    var emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            var val = this.value.trim();
            if (!val)                   setError('email', 'Insira seu e-mail para continuar.');
            else if (!isValidEmail(val)) setError('email', 'Insira um endereço de e-mail válido.');
            else                        setSuccess('email');
        });
        emailInput.addEventListener('input', function () {
            if (this.value.trim()) clearError('email');
        });
    }

    /* ── Senha ── */
    var passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            if (!this.value) setError('password', 'Insira sua senha para continuar.');
            else             clearError('password');
        });
        passwordInput.addEventListener('input', function () {
            if (this.value) clearError('password');
        });
    }

    /* ── Toggle visibilidade ── */
    document.querySelectorAll('.fp-toggle-pw').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(this.getAttribute('data-target'));
            if (!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            this.querySelector('.icon-eye').style.display     = show ? 'none' : '';
            this.querySelector('.icon-eye-off').style.display = show ? '' : 'none';
            this.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
        });
    });

    /* ── Focus no primeiro erro ao submeter ── */
    var form = document.getElementById('js-login-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var first = null;
            var emailVal = emailInput ? emailInput.value.trim() : '';
            var passVal  = passwordInput ? passwordInput.value : '';

            if (!emailVal) {
                setError('email', 'Insira seu e-mail para continuar.');
                first = first || 'email';
            } else if (!isValidEmail(emailVal)) {
                setError('email', 'Insira um endereço de e-mail válido.');
                first = first || 'email';
            }

            if (!passVal) {
                setError('password', 'Insira sua senha para continuar.');
                first = first || 'password';
            }

            if (first) {
                e.preventDefault();
                var el = document.getElementById(first);
                if (el) el.focus();
            }
        });
    }

})();
</script>
@endpush
</x-guest-layout>
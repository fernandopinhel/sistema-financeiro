<x-guest-layout>
<div class="fp-guest-card">

    <h2 class="fp-auth-title">Criar conta</h2>
    <p class="fp-auth-subtitle">Preencha os dados para começar gratuitamente.</p>

    {{-- Cadastro rápido com Google --}}
    <a
        href="{{ route('social.redirect', 'google') }}"
        class="fp-btn-google js-track-google-register"
        data-testid="btn-google-register"
        aria-label="Criar conta com sua conta Google"
    >
        <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
        </svg>
        Cadastrar com Google
    </a>

    <div class="fp-or-divider" role="separator" aria-hidden="true" style="margin:16px 0 4px;">
        <span>ou cadastre com e-mail</span>
    </div>

    <form
        id="js-register-form"
        method="POST"
        action="{{ route('register') }}"
        novalidate
        data-testid="register-form"
        aria-label="Formulário de cadastro"
        class="fp-auth-form"
    >
        @csrf

        {{-- Nome --}}
        <div class="fp-field">
            <label for="name" class="fp-label">Nome completo</label>
            <div class="fp-input-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Seu nome completo"
                    required
                    autofocus
                    autocomplete="name"
                    data-testid="input-name"
                    class="fp-input {{ $errors->has('name') ? 'error' : '' }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                    aria-describedby="name-err"
                    maxlength="255"
                >
            </div>
            <p id="name-err" class="fp-field-error" role="alert" aria-live="assertive"
               @unless($errors->has('name')) hidden @endunless>
                @error('name')
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
                @enderror
            </p>
        </div>

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
                    autocomplete="username"
                    data-testid="input-email"
                    class="fp-input {{ $errors->has('email') ? 'error' : '' }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                    aria-describedby="email-err"
                    maxlength="255"
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
                    placeholder="Mín. 8 caracteres"
                    required
                    autocomplete="new-password"
                    data-testid="input-password"
                    class="fp-input {{ $errors->has('password') ? 'error' : '' }}"
                    aria-required="true"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                    aria-describedby="pass-err pass-hint"
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

            {{-- Barra de força da senha --}}
            <div id="pw-strength" class="fp-pw-strength" hidden aria-live="polite">
                <div class="fp-pw-strength-bar">
                    <div class="fp-pw-strength-fill" id="pw-strength-fill"></div>
                </div>
                <span class="fp-pw-strength-label" id="pw-strength-label"></span>
            </div>

            <p id="pass-hint" class="fp-field-hint">
                Mín. 8 caracteres, com maiúscula, número e símbolo.
            </p>
            <p id="pass-err" class="fp-field-error" role="alert" aria-live="assertive"
               @unless($errors->has('password')) hidden @endunless>
                @error('password')
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
                @enderror
            </p>
        </div>

        {{-- Confirmar Senha --}}
        <div class="fp-field">
            <label for="password_confirmation" class="fp-label">Confirmar senha</label>
            <div class="fp-input-icon fp-input-icon--has-toggle">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Repita a senha"
                    required
                    autocomplete="new-password"
                    data-testid="input-password-confirm"
                    class="fp-input"
                    aria-required="true"
                    aria-invalid="false"
                    aria-describedby="confirm-err"
                >
                <button
                    type="button"
                    class="fp-toggle-pw"
                    data-target="password_confirmation"
                    aria-label="Mostrar confirmação de senha"
                    data-testid="btn-toggle-password-confirm"
                >
                    <svg class="icon-eye" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <svg class="icon-eye-off" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
            <p id="confirm-err" class="fp-field-error" role="alert" aria-live="assertive" hidden></p>
        </div>

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
            class="fp-btn fp-btn-primary fp-btn-full js-track-register-submit"
            style="margin-top:2px;"
            data-testid="btn-register-submit"
        >
            Criar minha conta
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </button>

        <p class="fp-auth-footer-text">
            Já tem conta?
            <a href="{{ route('login') }}" class="fp-link js-track-login-link" data-testid="link-login">Entrar</a>
        </p>

        <p style="text-align:center; font-size:11px; color:var(--fp-muted); margin:0;">
            Ao criar sua conta, você concorda com nossa
            <a href="{{ route('privacidade') }}?origem=register" class="fp-link" style="font-size:11px;" data-testid="link-privacy">
                Política de Privacidade
            </a>.
        </p>

    </form>
</div>

@push('scripts')
<style>
/* ── Auth local styles ──────────────────────────────────────────── */
.fp-auth-title   { font-size: 22px; font-weight: 700; color: var(--fp-text); letter-spacing: -.3px; margin: 0 0 4px; }
.fp-auth-subtitle{ color: var(--fp-muted); font-size: 14px; margin: 0 0 20px; }
.fp-auth-form    { display: flex; flex-direction: column; gap: 14px; }

.fp-field { display: flex; flex-direction: column; gap: 0; }

.fp-field-error {
    display: flex; align-items: center; gap: 5px;
    margin-top: 6px; font-size: 12px; font-weight: 500;
    color: var(--fp-danger);
}
.fp-field-error svg { color: var(--fp-danger); flex-shrink: 0; }
.fp-field-hint  { font-size: 11px; color: var(--fp-muted); margin-top: 4px; }

/* Toggle senha */
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

/* Estado de sucesso */
.fp-input.success {
    border-color: var(--fp-success);
    box-shadow: 0 0 0 3px rgba(46,196,182,.12);
}

/* Barra de força */
.fp-pw-strength { margin-top: 8px; }
.fp-pw-strength-bar {
    height: 4px; background: var(--fp-border); border-radius: 2px;
    overflow: hidden; margin-bottom: 4px;
}
.fp-pw-strength-fill {
    height: 100%; border-radius: 2px; width: 0;
    transition: width .3s ease, background .3s ease;
}
.fp-pw-strength-fill.weak   { width: 33%; background: var(--fp-danger); }
.fp-pw-strength-fill.medium { width: 66%; background: var(--fp-warning); }
.fp-pw-strength-fill.strong { width: 100%; background: var(--fp-success); }
.fp-pw-strength-label { font-size: 11px; font-weight: 600; }
.fp-pw-strength-label.weak   { color: var(--fp-danger); }
.fp-pw-strength-label.medium { color: var(--fp-warning); }
.fp-pw-strength-label.strong { color: var(--fp-success, #2EC4B6); }

/* Botão Google */
.fp-btn-google {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 11px 16px; border-radius: 10px;
    font-size: 14px; font-weight: 600; font-family: var(--fp-sans);
    color: var(--fp-text); text-decoration: none;
    background: var(--fp-surface);
    border: 1.5px solid #C9CDD6;
    box-shadow: 0 1px 2px rgba(26,29,46,.06);
    transition: background .15s, border-color .15s, box-shadow .15s;
}
.fp-btn-google:hover {
    background: var(--fp-bg); border-color: #9ca3af;
    box-shadow: 0 2px 6px rgba(26,29,46,.10);
}
.fp-btn-google:focus-visible { outline: 2px solid var(--fp-accent); outline-offset: 2px; }

/* Divisor */
.fp-or-divider {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; color: var(--fp-muted);
}
.fp-or-divider::before, .fp-or-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--fp-border);
}

/* Footer links */
.fp-link { font-weight: 600; color: var(--fp-accent); text-decoration: none; transition: opacity .15s; }
.fp-link:hover { opacity: .75; }
.fp-auth-footer-text { text-align: center; font-size: 14px; color: var(--fp-muted); margin: 0; }

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
        input.classList.add('error'); input.classList.remove('success');
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
        input.classList.remove('error'); input.classList.add('success');
        input.setAttribute('aria-invalid', 'false');
        var err = document.getElementById(id + '-err');
        if (err) { err.innerHTML = ''; err.setAttribute('hidden', ''); }
    }

    function isValidEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v); }

    function getPasswordError(v) {
        if (!v)             return 'Este campo é obrigatório.';
        if (v.length < 8)   return 'A senha deve ter pelo menos 8 caracteres.';
        if (!/[A-Z]/.test(v)) return 'Inclua pelo menos uma letra maiúscula.';
        if (!/[0-9]/.test(v)) return 'Inclua pelo menos um número.';
        if (!/[^A-Za-z0-9]/.test(v)) return 'Inclua pelo menos um símbolo (!@#$...).';
        return null;
    }

    /* ── Nome ── */
    var nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('blur', function () {
            var v = this.value.trim();
            if (!v)                            setError('name', 'Este campo é obrigatório.');
            else if (v.length < 2)             setError('name', 'O nome deve ter pelo menos 2 caracteres.');
            else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(v)) setError('name', 'Use apenas letras e espaços no seu nome.');
            else                               setSuccess('name');
        });
        nameInput.addEventListener('input', function () { if (this.value.trim()) clearError('name'); });
    }

    /* ── E-mail ── */
    var emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            var v = this.value.trim();
            if (!v)               setError('email', 'Este campo é obrigatório.');
            else if (!isValidEmail(v)) setError('email', 'Insira um endereço de e-mail válido.');
            else                  setSuccess('email');
        });
        emailInput.addEventListener('input', function () { if (this.value.trim()) clearError('email'); });
    }

    /* ── Senha + força ── */
    var pwInput   = document.getElementById('password');
    var pwStr     = document.getElementById('pw-strength');
    var pwFill    = document.getElementById('pw-strength-fill');
    var pwLabel   = document.getElementById('pw-strength-label');

    function updateStrength(v) {
        if (!v || !pwStr) return;
        var score = 0;
        if (v.length >= 8)          score++;
        if (/[A-Z]/.test(v))        score++;
        if (/[0-9]/.test(v))        score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        pwStr.removeAttribute('hidden');
        pwFill.className = 'fp-pw-strength-fill';
        pwLabel.className = 'fp-pw-strength-label';
        if (score <= 1) {
            pwFill.classList.add('weak');  pwLabel.classList.add('weak');  pwLabel.textContent = 'Senha fraca';
        } else if (score <= 2) {
            pwFill.classList.add('medium'); pwLabel.classList.add('medium'); pwLabel.textContent = 'Senha média';
        } else {
            pwFill.classList.add('strong'); pwLabel.classList.add('strong'); pwLabel.textContent = 'Senha forte';
        }
    }

    if (pwInput) {
        pwInput.addEventListener('input', function () {
            if (!this.value && pwStr) { pwStr.setAttribute('hidden', ''); return; }
            updateStrength(this.value);
            clearError('password');
            if (confirmInput && confirmInput.value) validateConfirm();
        });
        pwInput.addEventListener('blur', function () {
            var err = getPasswordError(this.value);
            if (err) setError('password', err);
            else     setSuccess('password');
        });
    }

    /* ── Confirmar senha ── */
    var confirmInput = document.getElementById('password_confirmation');

    function validateConfirm() {
        if (!confirmInput) return;
        var v = confirmInput.value;
        if (!v) {
            setError('password_confirmation', 'Este campo é obrigatório.');
        } else if (v !== (pwInput ? pwInput.value : '')) {
            setError('password_confirmation', 'As senhas não são iguais. Verifique e tente novamente.');
        } else {
            setSuccess('password_confirmation');
        }
    }

    if (confirmInput) {
        confirmInput.addEventListener('blur', validateConfirm);
        confirmInput.addEventListener('input', function () {
            if (!this.value) return;
            if (this.value === (pwInput ? pwInput.value : '')) setSuccess('password_confirmation');
            else setError('password_confirmation', 'As senhas não são iguais. Verifique e tente novamente.');
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
    var form = document.getElementById('js-register-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            var first = null;

            var nv = nameInput ? nameInput.value.trim() : '';
            if (!nv) { setError('name', 'Este campo é obrigatório.'); first = first || 'name'; }
            else if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(nv)) { setError('name', 'Use apenas letras e espaços no seu nome.'); first = first || 'name'; }

            var ev = emailInput ? emailInput.value.trim() : '';
            if (!ev) { setError('email', 'Este campo é obrigatório.'); first = first || 'email'; }
            else if (!isValidEmail(ev)) { setError('email', 'Insira um endereço de e-mail válido.'); first = first || 'email'; }

            var pv = pwInput ? pwInput.value : '';
            var pe = getPasswordError(pv);
            if (pe) { setError('password', pe); first = first || 'password'; }

            var cv = confirmInput ? confirmInput.value : '';
            if (!cv) { setError('password_confirmation', 'Este campo é obrigatório.'); first = first || 'password_confirmation'; }
            else if (cv !== pv) { setError('password_confirmation', 'As senhas não são iguais. Verifique e tente novamente.'); first = first || 'password_confirmation'; }

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
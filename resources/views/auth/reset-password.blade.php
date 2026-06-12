<x-guest-layout>

    {{--
        resources/views/auth/reset-password.blade.php
        ──────────────────────────────────────────────
        PÁGINA WEB do formulário de nova senha.
        Recebe: $request (do NewPasswordController::create())
        NÃO confundir com o template de e-mail (emails/auth/reset-password.blade.php)
    --}}
    <div class="fp-card">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#EEF2FF;border:1px solid #C7D2FE;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#4361EE" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;color:var(--fp-text);margin:0 0 2px;">
                    Criar nova senha
                </h2>
                <p style="font-size:13px;color:var(--fp-muted);margin:0;">
                    Escolha uma senha forte para sua conta.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('password.store') }}" novalidate
            style="display:flex;flex-direction:column;gap:18px;">
            @csrf

            {{-- Token oculto — obrigatório para validação do reset --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- E-mail --}}
            <div>
                <label for="email" class="fp-label">E-mail</label>
                <div class="fp-input-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <input id="email" type="email" name="email"
                        value="{{ old('email', $request->email) }}"
                        placeholder="seu@email.com"
                        required autofocus autocomplete="username"
                        class="fp-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>
                @error('email')
                    <p class="fp-field-error" role="alert">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Nova senha --}}
            <div>
                <label for="password" class="fp-label">Nova senha</label>
                <div class="fp-input-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                    <input id="password" type="password" name="password"
                        placeholder="Mín. 8 caracteres"
                        required autocomplete="new-password"
                        class="fp-input {{ $errors->has('password') ? 'error' : '' }}">
                </div>
                <p style="font-size:11px;color:var(--fp-muted);margin-top:5px;">
                    Use letras maiúsculas, números e símbolos para maior segurança.
                </p>
                @error('password')
                    <p class="fp-field-error" role="alert">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Confirmar nova senha --}}
            <div>
                <label for="password_confirmation" class="fp-label">Confirmar nova senha</label>
                <div class="fp-input-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        placeholder="Repita a nova senha"
                        required autocomplete="new-password"
                        class="fp-input">
                </div>
                @error('password_confirmation')
                    <p class="fp-field-error" role="alert">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="fp-btn fp-btn-primary fp-btn-full" style="margin-top:2px;">
                Redefinir senha
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>

            <p class="fp-auth-footer-text">
                <a href="{{ route('login') }}" class="fp-link-sm">← Voltar ao login</a>
            </p>
        </form>
    </div>
</x-guest-layout>

@push('scripts')
<style>
.fp-field-error  { display:flex;align-items:center;gap:5px;margin-top:6px;font-size:12px;font-weight:500;color:var(--fp-danger); }
.fp-link-sm      { font-size:13px;font-weight:600;color:var(--fp-accent);text-decoration:none;transition:opacity .15s; }
.fp-link-sm:hover{ opacity:.75; }
.fp-auth-footer-text { text-align:center;font-size:14px;color:var(--fp-muted);margin:0; }
</style>
@endpush
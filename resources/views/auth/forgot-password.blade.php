<x-guest-layout>
    <div class="fp-guest-card">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#FFF7ED;border:1px solid #FED7AA;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F97316" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:700;color:var(--fp-text);margin:0 0 2px;">Esqueceu sua senha?</h2>
                <p style="font-size:13px;color:var(--fp-muted);margin:0;">Enviaremos um link de redefinição para o seu e-mail.</p>
            </div>
        </div>

        @if(session('status'))
            <div class="fp-alert fp-alert-success" style="margin-bottom:20px;" role="alert">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate
            style="display:flex;flex-direction:column;gap:18px;">
            @csrf

            <div>
                <label for="email" class="fp-label">E-mail cadastrado</label>
                <div class="fp-input-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="seu@email.com"
                        required autofocus autocomplete="email"
                        class="fp-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>
                @error('email')
                    <p class="fp-field-error" role="alert">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="fp-btn fp-btn-primary fp-btn-full">
                Enviar link de redefinição
            </button>

            <p class="fp-auth-footer-text">
                <a href="{{ route('login') }}" class="fp-link-sm">← Voltar ao login</a>
            </p>
        </form>
    </div>
</x-guest-layout>

@push('scripts')
<style>
.fp-field-error{display:flex;align-items:center;gap:5px;margin-top:6px;font-size:12px;font-weight:500;color:var(--fp-danger);}
.fp-link-sm{font-size:13px;font-weight:600;color:var(--fp-accent);text-decoration:none;transition:opacity .15s;}
.fp-link-sm:hover{opacity:.75;}
.fp-auth-footer-text{text-align:center;font-size:14px;color:var(--fp-muted);margin:0;}
</style>
@endpush
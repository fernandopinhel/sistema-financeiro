@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-2xl border border-slate-200/80
           shadow-sm p-6 sm:p-10 my-6 text-slate-700 leading-relaxed">

    {{-- Header da seção --}}
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 24px; font-weight: 700; color: var(--fp-text); margin: 0 0 4px;">{{ Auth::user()->name }}</h1>
        <p style="font-size: 14px; color: var(--fp-muted); margin: 0;">Gerencie seus dados pessoais e segurança da conta.</p>
    </div>

    {{-- ── Dados Pessoais + Avatar ── --}}
    <div style="margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 700; color: var(--fp-text); margin: 0 0 4px;">Informações pessoais</h2>
        <p style="font-size: 13px; color: var(--fp-muted); margin: 0 0 24px;">Seu nome, e-mail e foto de perfil.</p>

        @php
            $profileInitials = collect(explode(' ', trim($user->name)))
                ->filter()
                ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                ->take(2)
                ->implode('');
        @endphp

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Avatar --}}
            <div style="margin-bottom: 20px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:8px; letter-spacing:.02em; text-transform:uppercase;">
                    Foto de Perfil
                </label>
                <div style="display:flex; align-items:center; gap:16px; background:#F9FAFB; padding:16px; border-radius:12px; border:1.5px solid var(--fp-border);">
                    {{-- Avatar atual (server-rendered) --}}
                    <div id="profile-avatar-default" style="flex-shrink:0;">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}"
                                 alt="Avatar"
                                 style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid #6366f1;">
                        @else
                            <div style="width:64px;height:64px;border-radius:50%;background:var(--fp-accent);border:2px solid #6366f1;display:flex;align-items:center;justify-content:center;">
                                <span style="font-family:var(--fp-mono);font-size:20px;font-weight:700;color:#fff;letter-spacing:-.5px;line-height:1;user-select:none;">{{ $profileInitials }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Preview (oculto até selecionar arquivo) --}}
                    <img id="profile-avatar-preview" src="" alt="Preview"
                         style="display:none;width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid #6366f1;flex-shrink:0;">

                    {{-- Input --}}
                    <div style="flex:1;">
                        <input id="avatar" name="avatar" type="file" accept="image/*"
                               onchange="(function(input){
                                   var file = input.files[0];
                                   if (!file) return;
                                   var reader = new FileReader();
                                   reader.onload = function(e) {
                                       var preview = document.getElementById('profile-avatar-preview');
                                       var def     = document.getElementById('profile-avatar-default');
                                       preview.src          = e.target.result;
                                       preview.style.display = 'block';
                                       def.style.display     = 'none';
                                   };
                                   reader.readAsDataURL(file);
                               })(this)"
                               style="display:block; width:100%; font-size:12px; color:#6b7280;"
                               class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl
                                      file:text-xs file:font-bold file:bg-fp-sec file:text-fp-accent
                                      file:border file:border-fp-sec-bd hover:file:bg-fp-sec-h
                                      file:cursor-pointer transition">
                        <p style="font-size:11px; color:#9ca3af; margin-top:4px;">Formatos: JPG, PNG, GIF, WebP. Máx.: 2 MB.</p>
                    </div>
                </div>
                @error('avatar')
                    <p style="font-size: 12px; color: var(--fp-danger); margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nome --}}
            <div style="margin-bottom: 16px;">
                <label class="fp-label" for="name">Nome completo</label>
                <input id="name" type="text" name="name" class="fp-input @error('name') error @enderror"
                    value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name')
                    <p style="font-size: 12px; color: var(--fp-danger); margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-mail --}}
            <div style="margin-bottom: 24px;">
                <label class="fp-label" for="email">E-mail</label>
                <input id="email" type="email" name="email" class="fp-input @error('email') error @enderror"
                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <p style="font-size: 12px; color: var(--fp-danger); margin-top: 4px;">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top: 8px; padding: 10px 14px; background: rgba(244,162,97,.1); border: 1px solid rgba(244,162,97,.3); border-radius: 8px; font-size: 12px; color: #92400E;">
                        Seu e-mail não foi verificado.
                        <button form="send-verification" style="background:none;border:none;cursor:pointer;color:var(--fp-accent);font-weight:600;font-size:12px;padding:0;">Reenviar verificação</button>
                    </div>
                    <form id="send-verification" method="POST" action="{{ route('verification.send') }}" style="display:none;">@csrf</form>
                @endif
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="submit" class="fp-btn fp-btn-secondary fp-btn-sm">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Salvar alterações
                </button>
                @if (session('status') === 'profile-updated')
                    <span style="font-size: 13px; color: #0D9488; font-weight: 500;">Salvo com sucesso!</span>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Alterar Senha ── --}}
    <div style="margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 700; color: var(--fp-text); margin: 0 0 4px;">Alterar senha</h2>
        <p style="font-size: 13px; color: var(--fp-muted); margin: 0 0 24px;">Recomendamos uma senha forte com letras, números e símbolos.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 16px;">
                <label class="fp-label" for="current_password">Senha atual</label>
                <input id="current_password" type="password" name="current_password"
                    class="fp-input @error('current_password', 'updatePassword') error @enderror"
                    autocomplete="current-password" placeholder="••••••••">
                @error('current_password', 'updatePassword')
                    <p style="font-size: 12px; color: var(--fp-danger); margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label class="fp-label" for="password">Nova senha</label>
                <input id="password" type="password" name="password"
                    class="fp-input @error('password', 'updatePassword') error @enderror"
                    autocomplete="new-password" placeholder="Mín. 8 caracteres">
                @error('password', 'updatePassword')
                    <p style="font-size: 12px; color: var(--fp-danger); margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label class="fp-label" for="password_confirmation">Confirmar nova senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                    class="fp-input" autocomplete="new-password" placeholder="Repita a nova senha">
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="submit" class="fp-btn fp-btn-secondary fp-btn-sm">Atualizar senha</button>
                @if (session('status') === 'password-updated')
                    <span style="font-size: 13px; color: #0D9488; font-weight: 500;">Senha atualizada!</span>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Sair da conta ── --}}
    <div style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div>
                <h2 style="font-size: 16px; font-weight: 700; color: var(--fp-text); margin: 0 0 4px;">Sessão ativa</h2>
                <p style="font-size: 13px; color: var(--fp-muted); margin: 0;">Encerre sua sessão neste dispositivo.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fp-btn fp-btn-ghost fp-btn-sm">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Sair da conta
                </button>
            </form>
        </div>
    </div>

    {{-- ── Excluir Conta ── --}}
    <div style="border-color: rgba(230,57,70,.25); background: rgba(230,57,70,.02);">
        <h2 style="font-size: 16px; font-weight: 700; color: var(--fp-danger); margin: 0 0 4px;">Zona de perigo</h2>
        <p style="font-size: 13px; color: var(--fp-muted); margin: 0 0 20px;">
            A exclusão da conta é <strong>permanente e irreversível</strong>. Todos os seus dados, transações e histórico serão apagados em conformidade com a LGPD.
        </p>

        <button type="button" class="fp-btn fp-btn-danger fp-btn-sm"
                onclick="document.getElementById('deleteModal').showModal()">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
            </svg>
            Excluir minha conta
        </button>
    </div>
</div>

{{-- Modal de confirmação de exclusão --}}
<dialog id="deleteModal" style="border:none;border-radius:16px;padding:0;box-shadow:0 20px 60px rgba(0,0,0,.2);max-width:420px;width:90vw;">
    <div style="padding:28px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(230,57,70,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#E63946">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h3 style="font-size:17px;font-weight:700;color:var(--fp-text);margin:0;">Excluir conta permanentemente</h3>
        </div>

        <p style="font-size:13px;color:var(--fp-muted);line-height:1.6;margin:0 0 20px;">
            Esta ação não pode ser desfeita. Para confirmar, digite sua senha atual.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div style="margin-bottom:20px;">
                <label class="fp-label" for="delete_password">Senha atual</label>
                <input id="delete_password" type="password" name="password"
                    class="fp-input @error('password', 'userDeletion') error @enderror"
                    placeholder="Confirme sua senha">
                @error('password', 'userDeletion')
                    <p style="font-size:12px;color:var(--fp-danger);margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="fp-btn fp-btn-ghost fp-btn-sm"
                        onclick="document.getElementById('deleteModal').close()">
                    Cancelar
                </button>
                <button type="submit" class="fp-btn fp-btn-sm"
                        style="background:var(--fp-danger);color:#fff;">
                    Sim, excluir conta
                </button>
            </div>
        </form>
    </div>
</dialog>

<style>
    dialog::backdrop { background: rgba(0,0,0,.4); }
    .fp-label { display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; letter-spacing:.02em; text-transform:uppercase; }
    .fp-input { width:100%; padding:10px 14px; border:1.5px solid var(--fp-border); border-radius:10px; font-size:14px; font-family:inherit; color:var(--fp-text); background:#F9FAFB; transition:border-color .15s,box-shadow .15s,background .15s; outline:none; box-sizing:border-box; }
    .fp-input:focus { border-color:var(--fp-accent); background:#fff; box-shadow:0 0 0 3px rgba(67,97,238,.12); }
    .fp-input.error { border-color:var(--fp-danger); }
</style>

@endsection

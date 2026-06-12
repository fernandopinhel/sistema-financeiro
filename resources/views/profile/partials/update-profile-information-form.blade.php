<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">
            Informações do Perfil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Atualize o nome de exibição, endereço de e-mail e foto de perfil da sua conta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div x-data="{ imgPreview: null }">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto de Perfil</label>
            <div class="flex items-center space-x-5 bg-gray-50 p-4 rounded-xl border border-gray-200">
                <template x-if="!imgPreview">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="h-16 w-16 rounded-full object-cover border-2 border-indigo-600 shadow-sm">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff" class="h-16 w-16 rounded-full object-cover border-2 border-indigo-600 shadow-sm">
                    @endif
                </template>
                <template x-if="imgPreview">
                    <img :src="imgPreview" class="h-16 w-16 rounded-full object-cover border-2 border-indigo-600 shadow-sm">
                </template>
                
                <div class="flex-grow">
                    <input id="avatar" name="avatar" type="file" accept="image/*"
                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imgPreview = e.target.result; }; reader.readAsDataURL(file); }"
                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer transition" />
                    <p class="text-xs text-gray-400 mt-1">Formatos suportados: JPG, PNG. Máx: 2MB.</p>
                </div>
            </div>
            @if($errors->has('avatar'))
                <p class="text-red-600 text-xs mt-1 font-medium">{{ $errors->first('avatar') }}</p>
            @endif
        </div>

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nome Completo</label>
            <input id="name" name="name" type="text" class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 p-3 text-sm transition-all outline-none text-gray-900 bg-white" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">E-mail Corporativo / Pessoal</label>
            <input id="email" name="email" type="email" class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 p-3 text-sm transition-all outline-none text-gray-900 bg-white" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="bg-amber-50 p-3 rounded-xl border border-amber-100 mt-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <p class="text-xs font-medium text-amber-800">Seu endereço de e-mail não foi verificado.</p>
                    <button form="send-verification" class="text-xs font-bold text-amber-700 hover:underline">Reenviar link de verificação</button>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-semibold text-green-600">Um novo link de verificação foi enviado para o seu e-mail.</p>
                @endif
            @endif
        </div>

        <div class="flex items-center space-x-4 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-sm transition">
                Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="text-sm text-green-600 font-semibold flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    <span>Salvo com sucesso!</span>
                </div>
            @endif
        </div>
    </form>
</section>
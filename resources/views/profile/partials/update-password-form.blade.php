<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">
            Atualizar Senha
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Certifique-se de que sua conta está usando uma senha forte e longa para manter a segurança dos seus dados.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Senha Atual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 text-sm transition-all outline-none" autocomplete="current-password" />
            <x-input-error class="mt-1" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Nova Senha</label>
            <input id="update_password_password" name="password" type="password" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 text-sm transition-all outline-none" autocomplete="new-password" />
            <x-input-error class="mt-1" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirmar Nova Senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 text-sm transition-all outline-none" autocomplete="new-password" />
            <x-input-error class="mt-1" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center space-x-4 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-sm transition">
                Alterar Senha
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="text-sm text-green-600 font-semibold flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor\" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Senha atualizada!</span>
                </div>
            @endif
        </div>
    </form>
</section>
<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-red-900">
            Excluir Conta Permanentemente
        </h2>
        <p class="mt-1 text-sm text-red-700/80">
            Depois que sua conta for excluída, todos os seus recursos e dados financeiros salvos serão perdidos de forma irreversível.
        </p>
    </header>

    <button type="button"
            x-data
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-red-700 shadow-sm transition">
        Excluir Minha Conta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-900">
                Você tem certeza absoluta?
            </h2>

            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                Esta ação não pode ser desfeita. Por segurança, digite a sua senha atual para confirmar o encerramento definitivo dos seus dados.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Sua Senha Atual</label>
                <input id="password" name="password" type="password" class="w-full sm:w-3/4 border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 p-3 text-sm transition-all outline-none" placeholder="Digite sua senha aqui" required />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-8 flex justify-end space-x-3 border-t border-gray-100 pt-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-sm transition">
                    Confirmar e Excluir
                </button>
            </div>
        </form>
    </x-modal>
</section>
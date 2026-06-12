<x-guest-layout>
    <div class="fp-guest-card">
        <div class="flex items-center gap-3 mb-7">
            <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Área protegida</h2>
                <p class="text-slate-400 text-sm">Confirme sua senha para continuar.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Senha atual</label>
                <input id="password" type="password" name="password"
                    placeholder="••••••••"
                    required autocomplete="current-password"
                    class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                            placeholder-slate-400
                            {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3 px-4
                        bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl
                        shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                Confirmar e continuar
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </form>
    </div>
</x-guest-layout>

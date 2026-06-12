<x-guest-layout>
    <div class="fp-guest-card">
        <div class="text-center mb-6">
            <div class="mx-auto h-14 w-14 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                <svg class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615A2.25 2.25 0 012.25 6.993V6.75"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Verifique seu e-mail</h2>
            <p class="text-slate-400 text-sm mt-2 leading-relaxed max-w-xs mx-auto">
                Enviamos um link de verificação para o seu endereço de e-mail. Clique nele para ativar sua conta.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Novo link enviado! Verifique sua caixa de entrada.
            </div>
        @endif

        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4
                            bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl
                            shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    Reenviar e-mail de verificação
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 px-4
                            text-sm font-semibold text-slate-500 hover:text-slate-700
                            bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors
                            focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Sair da conta
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>

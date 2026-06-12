{{--
    navigation.blade.php
    Salvar em: resources/views/layouts/navigation.blade.php

    Correções:
    - Avatar: exibe foto se existir, senão as INICIAIS do nome do usuário
    - Iniciais geradas a partir do nome real (não email)
--}}

@php
    $user     = Auth::user();
    // Gera iniciais a partir do nome: "Fernando Pinhel" → "FP"
    $initials = collect(explode(' ', trim($user->name)))
                    ->filter()
                    ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
                    ->take(2)
                    ->implode('');
@endphp

{{-- Sidebar / Navigation (mantém estrutura original do projeto) --}}
<nav x-data="{ mobileOpen: false }">

    {{-- ─── Sidebar desktop ─────────────────────────────────────── --}}
    <aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-56 bg-slate-900 z-40">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-5 border-b border-slate-800">
            <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-black">FP</span>
            </div>
            <span class="text-white font-bold text-sm">Finanças <span class="text-indigo-400">FP</span></span>
        </div>

        {{-- Links --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Principal</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('transacoes.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('transacoes.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                </svg>
                Transações
            </a>

            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                Categorias
            </a>
        </div>

        {{-- ── Perfil do usuário (rodapé do sidebar) ── --}}
        <div class="border-t border-slate-800 p-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 transition-colors group">

                    {{-- Avatar ou iniciais --}}
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             alt="{{ $user->name }}"
                             class="h-8 w-8 rounded-full object-cover flex-shrink-0 ring-2 ring-slate-700">
                    @else
                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0 ring-2 ring-slate-700">
                            <span class="text-white text-xs font-bold">{{ $initials }}</span>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                    </div>

                    <svg class="h-4 w-4 text-slate-500 flex-shrink-0 transition-transform"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                {{-- Dropdown do perfil --}}
                <div x-show="open"
                     x-cloak
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute bottom-full left-0 right-0 mb-1 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl overflow-hidden z-50">

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Meu Perfil
                    </a>

                    <div class="border-t border-slate-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-slate-400 hover:bg-slate-700 hover:text-red-400 transition-colors">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                                </svg>
                                Sair da conta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ─── Header mobile ────────────────────────────────────────── --}}
    <header class="md:hidden flex items-center justify-between px-4 py-3 bg-slate-900 sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center">
                <span class="text-white text-xs font-black">FP</span>
            </div>
            <span class="text-white font-bold text-sm">Finanças <span class="text-indigo-400">FP</span></span>
        </div>
        <button @click="mobileOpen = !mobileOpen"
                class="h-9 w-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </header>

    {{-- ─── Menu mobile (drawer) ─────────────────────────────────── --}}
    <div x-show="mobileOpen"
         x-cloak
         @click="mobileOpen = false"
         class="md:hidden fixed inset-0 bg-black/50 z-30"></div>

    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         class="md:hidden fixed inset-y-0 left-0 w-64 bg-slate-900 z-40 flex flex-col shadow-2xl">

        <div class="flex items-center gap-2.5 px-5 py-5 border-b border-slate-800">
            <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-black">FP</span>
            </div>
            <span class="text-white font-bold text-sm">Finanças <span class="text-indigo-400">FP</span></span>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
            <p class="px-3 pt-2 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">Principal</p>

            <a href="{{ route('dashboard') }}" @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('transacoes.index') }}" @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('transacoes.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                </svg>
                Transações
            </a>

            <a href="{{ route('categories.index') }}" @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                </svg>
                Categorias
            </a>
        </div>

        {{-- Perfil mobile --}}
        <div class="border-t border-slate-800 p-3 space-y-1">
            <a href="{{ route('profile.edit') }}" @click="mobileOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                {{-- Avatar mobile --}}
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         class="h-8 w-8 rounded-full object-cover flex-shrink-0">
                @else
                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">{{ $initials }}</span>
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-200 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Sair da conta
                </button>
            </form>
        </div>
    </div>
</nav>
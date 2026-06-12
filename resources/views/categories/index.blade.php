@extends('layouts.app')
@section('title', 'Categorias')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 leading-tight">Histórico de Categorias</h1>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie suas categorias.</p>
    </div>
    <button type="button" onclick="document.getElementById('modal-nova-categoria').classList.remove('hidden')"
            class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700
                   text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all w-full sm:w-auto">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nova Categoria
    </button>
</div>



{{-- Filtro de Nome --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('categories.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Descrição</label>
                {{-- name="search" garante compatibilidade com as queries do Controller --}}
                <input type="text" name="search" value="{{ request('search') ?? request('busca') }}" placeholder="Buscar por descrição..."
                       class="w-full px-3 h-[42.67px] text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl outline-none transition-all placeholder-slate-400 focus:bg-white focus:border-indigo-400">
            </div>
            
            <div class="md:col-span-3 flex items-end gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 h-[42.67px] text-sm font-semibold
                               text-fp-accent bg-fp-sec hover:bg-fp-sec-h
                               border border-fp-sec-bd hover:border-fp-sec-bd-h
                               rounded-xl transition-all whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request()->hasAny(['busca', 'search']))
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition-all h-[38px]">
                        Limpar
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    @if($categories->isEmpty())

        {{-- Empty state --}}
        <div class="p-12 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="font-semibold text-slate-500">Nenhuma categoria encontrada.</p>
            <p class="text-xs mt-1">Tente ajustar os filtros ou crie uma nova categoria.</p>
        </div>

    @else

        {{-- Mobile: cards --}}
        <div class="divide-y divide-slate-100 md:hidden">
            @foreach($categories as $cat)
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                  style="background-color: {{ $cat->color }}"></span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">
                                    <span class="mr-2">{{ $cat->name }}</span>
                                    @if($cat->show_on_dashboard)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Visível
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-400">
                                        Oculto
                                    </span>
                                    @endif
                                </p>
                                <!--p class="text-xs text-slate-400 mt-0.5">
                                    {{-- $cat->transactions_count }} {{ $cat->transactions_count === 1 ? 'transação' : 'transações'--}}
                                </p-->
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <div class="flex items-center gap-1">
                                <button onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->color }}', {{ $cat->show_on_dashboard ? 'true' : 'false' }})"
                                        class="h-7 w-7 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-500 flex items-center justify-center transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                    </svg>
                                </button>
                                <button type="button"
                                        onclick="openDuplicateModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                        aria-label="Duplicar categoria {{ $cat->name }}"
                                        class="h-7 w-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-500 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                </button>
                                <button type="button"
                                        onclick="openDeleteModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                        class="h-7 w-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 flex items-center justify-center transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Desktop: div-grid --}}
        <div class="hidden md:block">

            {{-- Header --}}
            <div class="grid grid-cols-[1fr_200px_120px] border-b border-slate-100 bg-slate-50">
                <div class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Categoria</div>
                <div class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Dashboard</div>
                <div class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Ações</div>
            </div>

            {{-- Rows --}}
            <div class="divide-y divide-slate-100">
                @foreach($categories as $index => $cat)
                <div class="grid grid-cols-[1fr_200px_120px] items-center
                            {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}
                            hover:bg-indigo-50/30 transition-colors">

                    {{-- Categoria --}}
                    <div class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full flex-shrink-0"
                                  style="background-color: {{ $cat->color }}"></span>
                            <span class="font-semibold text-slate-800 text-sm">{{ $cat->name }}</span>
                            <!--span class="text-xs text-slate-400">({{-- $cat->transactions_count }}
                                {{ $cat->transactions_count === 1 ? 'transação' : 'transações' --}})</!---->
                        </div>
                    </div>

                    {{-- Dashboard --}}
                    <div class="px-5 py-4 flex justify-center">
                        @if($cat->show_on_dashboard)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Visível
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-400">
                                Oculto
                            </span>
                        @endif
                    </div>

                    {{-- Ações --}}
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1.5">

                            {{-- Editar --}}
                            <div class="relative group">
                                <button onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->color }}', {{ $cat->show_on_dashboard ? 'true' : 'false' }})"
                                        class="h-8 w-8 rounded-lg flex items-center justify-center bg-indigo-50 hover:bg-indigo-100 text-indigo-500 hover:text-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] font-bold rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                                    Editar
                                    <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></span>
                                </span>
                            </div>

                            {{-- Duplicar --}}
                            <div class="relative group">
                                <button type="button"
                                        onclick="openDuplicateModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                        aria-label="Duplicar categoria {{ $cat->name }}"
                                        class="h-8 w-8 rounded-lg flex items-center justify-center bg-emerald-50 hover:bg-emerald-100 text-emerald-500 hover:text-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] font-bold rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                                    Duplicar
                                    <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></span>
                                </span>
                            </div>

                            {{-- Excluir --}}
                            <div class="relative group">
                                <button type="button"
                                        onclick="openDeleteModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                        class="h-8 w-8 rounded-lg flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] font-bold rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                                    Excluir
                                    <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></span>
                                </span>
                            </div>

                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>

    @endif

    @if($categories->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $categories->links() }}
        </div>
    @endif
</div>

{{-- Modal Nova Categoria --}}
<div id="modal-nova-categoria"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-slate-900">Nova Categoria</h2>
            <button onclick="document.getElementById('modal-nova-categoria').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nome</label>
                <input type="text" name="name" required maxlength="100" placeholder="Ex: Alimentação"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl outline-none transition-all
                              placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cor</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="#6366f1"
                           class="h-10 w-14 rounded-xl border border-slate-200 cursor-pointer p-1 bg-slate-50">
                    <span class="text-xs text-slate-400">Escolha uma cor para identificar a categoria.</span>
                </div>
            </div>
            <label class="flex items-center gap-3 cursor-pointer bg-slate-50 rounded-xl px-4 py-3">
                <input type="checkbox" name="show_on_dashboard" value="1"
                       class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400 cursor-pointer">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Exibir no Dashboard</p>
                    <p class="text-xs text-slate-400 mt-0.5">Cria um card de resumo na tela principal.</p>
                </div>
            </label>
            <div class="flex gap-3 pt-1">
                <button type="button"
                        onclick="document.getElementById('modal-nova-categoria').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all">
                    Criar Categoria
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Categoria --}}
<div id="modal-editar-categoria"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-slate-900">Editar Categoria</h2>
            <button onclick="document.getElementById('modal-editar-categoria').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="form-editar-categoria" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nome</label>
                <input type="text" id="edit-name" name="name" required maxlength="100"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl outline-none transition-all
                              focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cor</label>
                <div class="flex items-center gap-3">
                    <input type="color" id="edit-color" name="color"
                           class="h-10 w-14 rounded-xl border border-slate-200 cursor-pointer p-1 bg-slate-50">
                    <span class="text-xs text-slate-400">Escolha uma cor para identificar a categoria.</span>
                </div>
            </div>
            <label class="flex items-center gap-3 cursor-pointer bg-slate-50 rounded-xl px-4 py-3">
                <input type="checkbox" id="edit-show-dashboard" name="show_on_dashboard" value="1"
                       class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400 cursor-pointer">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Exibir no Dashboard</p>
                    <p class="text-xs text-slate-400 mt-0.5">Cria um card de resumo na tela principal.</p>
                </div>
            </label>
            <div class="flex gap-3 pt-1">
                <button type="button"
                        onclick="document.getElementById('modal-editar-categoria').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal de Confirmação de Duplicação de Categoria --}}
<div id="modal-duplicar-categoria"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-duplicar-cat-titulo">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
         onclick="event.stopPropagation()">

        {{-- Cabeçalho --}}
        <div class="flex items-center gap-4 mb-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100
                        flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                </svg>
            </div>
            <div>
                <h2 id="modal-duplicar-cat-titulo"
                    class="text-base font-bold text-slate-900">Duplicar Categoria</h2>
                <p class="text-xs text-slate-400 mt-0.5">Esta ação criará uma cópia idêntica.</p>
            </div>
        </div>

        {{-- Nome da categoria --}}
        <p class="text-sm text-slate-600 mb-1">Deseja realmente duplicar a categoria:</p>
        <p id="modal-duplicar-cat-label"
           class="text-sm font-semibold text-slate-900 bg-slate-50 border border-slate-100
                  rounded-xl px-4 py-2.5 mb-5 truncate">-</p>

        {{-- Nota informativa --}}
        <div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-100
                    rounded-xl px-3.5 py-3 mb-5">
            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <p class="text-xs text-emerald-700 leading-relaxed">
                A cópia será criada com o nome <strong>"… (Cópia)"</strong>,
                mantendo a mesma cor e configuração de dashboard.
            </p>
        </div>

        {{-- Form de submit --}}
        <form id="form-duplicar-categoria" method="POST" action="">
            @csrf
            <div class="flex items-center justify-end gap-2.5">
                <button type="button"
                        onclick="closeDuplicateCategoryModal()"
                        class="px-4 py-2.5 text-sm font-semibold text-slate-600
                               bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors
                               focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white
                               bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm
                               transition-all focus:outline-none focus:ring-2
                               focus:ring-emerald-400 focus:ring-offset-1
                               inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                    Confirmar Duplicação
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Customizado de Exclusão de Categoria --}}
<div id="modal-excluir-categoria"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-2">Excluir Categoria</h2>
        <p class="text-sm text-slate-500 mb-5">
            Tem certeza que deseja excluir a categoria <span id="delete-label" class="font-semibold text-slate-800"></span>? As transações vinculadas não serão apagadas.
        </p>
        
        <form id="form-excluir-categoria" method="POST" action="">
            @csrf
            @method('DELETE')
            
            <div class="flex items-center justify-end gap-2.5">
                <button type="button" 
                        onclick="document.getElementById('modal-excluir-categoria').classList.add('hidden')" 
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                
                <button type="submit" 
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm">
                    Confirmar Exclusão
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, color, showOnDashboard) {
    document.getElementById('form-editar-categoria').action = "{{ url('categorias') }}/" + id;
    document.getElementById('edit-name').value             = name;
    document.getElementById('edit-color').value            = color;
    document.getElementById('edit-show-dashboard').checked = showOnDashboard;
    document.getElementById('modal-editar-categoria').classList.remove('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('form-excluir-categoria').action = "{{ url('categorias') }}/" + id;
    document.getElementById('delete-label').textContent = name;
    document.getElementById('modal-excluir-categoria').classList.remove('hidden');
}

function openDuplicateModal(id, nome) {
    const modal = document.getElementById('modal-duplicar-categoria');
    const form  = document.getElementById('form-duplicar-categoria');
    const label = document.getElementById('modal-duplicar-cat-label');

    form.action       = "{{ url('categorias') }}/" + id + "/duplicar";
    label.textContent = nome;
    modal.classList.remove('hidden');

    // Foca no Cancelar por acessibilidade
    setTimeout(() => modal.querySelector('button[type="button"]').focus(), 50);
}

function closeDuplicateCategoryModal() {
    const modal = document.getElementById('modal-duplicar-categoria');
    const form  = document.getElementById('form-duplicar-categoria');
    const label = document.getElementById('modal-duplicar-cat-label');

    modal.classList.add('hidden');
    form.action       = '';
    label.textContent = '-';
}

// Fecha modal de duplicação ao clicar no backdrop ou pressionar Escape
document.getElementById('modal-duplicar-categoria').addEventListener('click', function (e) {
    if (e.target === this) closeDuplicateCategoryModal();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' &&
        !document.getElementById('modal-duplicar-categoria').classList.contains('hidden')) {
        closeDuplicateCategoryModal();
    }
});
</script>

@endsection
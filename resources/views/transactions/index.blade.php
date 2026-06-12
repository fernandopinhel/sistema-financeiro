@extends('layouts.app')
@section('title', 'Transações')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 leading-tight">Histórico de Transações</h1>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie suas entradas e saídas financeiras.</p>
    </div>
    <a href="{{ route('transacoes.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all w-full sm:w-auto">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nova Transação
    </a>
</div>

@include('transactions.partials.pending-recurrences')

{{-- ── Filtros ── --}}
<form method="GET" action="{{ route('transacoes.index') }}"
      class="bg-white border border-slate-100 rounded-xl shadow-sm p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_1fr_auto] gap-3">

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Descrição</label>
            <input type="text" name="busca" value="{{ request('busca') }}"
                   placeholder="Buscar por descrição..."
                   class="w-full px-3 h-[42.67px] text-sm text-slate-700 bg-slate-50 border border-slate-200
                          rounded-xl outline-none focus:bg-white focus:border-indigo-400 focus:ring-2
                          focus:ring-indigo-100 transition-all placeholder-slate-400">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Ano</label>
            <select name="ano" class="fp-select fp-select-sm w-full">
                <option value="">Todos</option>
                @foreach($anosDisponiveis as $a)
                    <option value="{{ $a }}" {{ request('ano') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Mês</label>
            <select name="mes" class="fp-select fp-select-sm w-full">
                <option value="">Todos</option>
                @foreach(['01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril','05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto','09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro'] as $num => $nome)
                    <option value="{{ $num }}" {{ request('mes') === $num ? 'selected' : '' }}>{{ $nome }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Tipo</label>
            <select name="tipo" class="fp-select fp-select-sm w-full">
                <option value="">Todos</option>
                <option value="income"  {{ request('tipo') === 'income'  ? 'selected' : '' }}>Receitas</option>
                <option value="expense" {{ request('tipo') === 'expense' ? 'selected' : '' }}>Despesas</option>
            </select>
        </div>

        <div class="sm:col-span-2 lg:col-span-1">
            <label class="block text-xs mb-1.5 invisible select-none" aria-hidden="true">‌</label>
            <div class="flex gap-2">
                <button type="submit"
                        class="w-full lg:w-auto inline-flex justify-center items-center gap-1.5 px-4
                               h-[42.67px] text-sm font-semibold text-fp-accent bg-fp-sec hover:bg-fp-sec-h
                               border border-fp-sec-bd hover:border-fp-sec-bd-h
                               rounded-xl transition-all whitespace-nowrap">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    Filtrar
                </button>
                @if(request('busca') || request('ano') || request('mes') || request('tipo'))
                    <a href="{{ route('transacoes.index') }}"
                       class="flex-1 lg:flex-none inline-flex justify-center items-center gap-1.5 px-4 py-2
                              text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200
                              rounded-xl transition-colors whitespace-nowrap">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar
                    </a>
                @endif
            </div>
        </div>
    </div>
</form>

{{-- ── Lista ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    {{-- Mobile --}}
    <div class="divide-y divide-slate-100 md:hidden">
        @forelse($transactions as $t)
            <div class="p-4" x-data="{ openDelete: false }">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="h-8 w-8 rounded-lg flex items-center justify-center
                            {{ $t->type === 'expense' ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-500' }}">
                            @if($t->type === 'expense')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $t->description }}</p>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                            <p class="text-xs text-slate-400">{{ $t->date->format('d/m/Y') }}</p>
                            @if($t->category)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color:{{ $t->category->color }}18;color:{{ $t->category->color }}">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $t->category->color }}"></span>
                                    {{ $t->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <span class="text-sm font-bold {{ $t->type === 'expense' ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $t->type === 'expense' ? '−' : '+' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                        </span>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('transacoes.edit', $t->id) }}"
                               class="h-7 w-7 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition-colors"
                               title="Editar">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>

                            {{-- Duplicar mobile --}}
                            <button type="button"
                                    onclick="openDuplicateModal({{ $t->id }}, '{{ addslashes($t->description) }}')"
                                    class="h-7 w-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors"
                                    title="Duplicar">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                            </button>

                            <button @click="openDelete = true"
                                    class="h-7 w-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors"
                                    title="Excluir">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @include('partials.delete-modal', ['id' => $t->id, 'label' => $t->description, 'route' => route('transacoes.destroy', $t->id)])
            </div>
        @empty
            @include('partials.empty-state', [
                'message' => 'Nenhuma transação encontrada.',
                'hint'    => request()->hasAny(['busca','ano','mes','tipo'])
                    ? 'Tente ajustar os filtros.'
                    : 'Comece adicionando sua primeira transação.'
            ])
        @endforelse
    </div>

    {{-- Desktop --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Data</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Descrição</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Categoria</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Tipo</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-right">Valor</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center w-32">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $index => $t)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }} hover:bg-indigo-50/30 transition-colors"
                    x-data="{ openDelete: false }">

                    <td class="px-5 py-4 text-sm text-slate-500 whitespace-nowrap">
                        {{ $t->date->format('d/m/Y') }}
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg flex-shrink-0 flex items-center justify-center
                                {{ $t->type === 'expense' ? 'bg-red-50 text-red-400' : 'bg-emerald-50 text-emerald-500' }}">
                                @if($t->type === 'expense')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/></svg>
                                @else
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-slate-900">{{ $t->description }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($t->category)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                  style="background-color:{{ $t->category->color }}18;color:{{ $t->category->color }}">
                                <span class="h-1.5 w-1.5 rounded-full" style="background:{{ $t->category->color }}"></span>
                                {{ $t->category->name }}
                            </span>
                        @else
                            <span class="text-slate-300 text-xs">-</span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $t->type === 'expense' ? 'bg-red-50 text-red-700 ring-1 ring-red-100' : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $t->type === 'expense' ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                            {{ $t->type === 'expense' ? 'Despesa' : 'Receita' }}
                        </span>
                    </td>

                    <td class="px-5 py-4 text-right">
                        <span class="text-sm font-bold tabular-nums {{ $t->type === 'expense' ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $t->type === 'expense' ? '−' : '+' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">

                            {{-- Editar --}}
                            <div class="relative group">
                                <a href="{{ route('transacoes.edit', $t->id) }}"
                                   aria-label="Editar {{ $t->description }}"
                                   class="h-8 w-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-500
                                          hover:text-indigo-700 flex items-center justify-center transition-colors
                                          focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>
                                <span class="fp-tooltip fp-tooltip-top" role="tooltip">Editar</span>
                            </div>

                            {{-- Duplicar --}}
                            <div class="relative group">
                                <button type="button"
                                        onclick="openDuplicateModal({{ $t->id }}, '{{ addslashes($t->description) }}')"
                                        aria-label="Duplicar {{ $t->description }}"
                                        class="h-8 w-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-500
                                               hover:text-emerald-700 flex items-center justify-center transition-colors
                                               focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                    </svg>
                                </button>
                                <span class="fp-tooltip fp-tooltip-top" role="tooltip">Duplicar</span>
                            </div>

                            {{-- Excluir --}}
                            <div class="relative group">
                                <button @click="openDelete = true"
                                        aria-label="Excluir {{ $t->description }}"
                                        class="h-8 w-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400
                                               hover:text-red-600 flex items-center justify-center transition-colors
                                               focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                                <span class="fp-tooltip fp-tooltip-top" role="tooltip">Excluir</span>
                            </div>
                        </div>

                        @include('partials.delete-modal', [
                            'id'    => $t->id,
                            'label' => $t->description,
                            'route' => route('transacoes.destroy', $t->id)
                        ])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <p class="text-slate-600 font-semibold">Nenhuma transação encontrada</p>
                        <p class="text-slate-400 text-sm mt-1">
                            @if(request()->hasAny(['busca','ano','mes','tipo']))
                                Tente ajustar os filtros ou <a href="{{ route('transacoes.index') }}" class="text-indigo-600 hover:underline">limpar</a>.
                            @else
                                Comece adicionando sua primeira transação.
                            @endif
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

{{-- ════════════════════════════════════════════
     MODAL DE CONFIRMAÇÃO DE DUPLICAÇÃO
     Padrão visual idêntico ao modal de exclusão
════════════════════════════════════════════ --}}
<div id="modal-duplicar-transacao"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-duplicar-titulo">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6
                transform transition-all"
         {{-- Impede fechar ao clicar no card interno --}}
         onclick="event.stopPropagation()">

        {{-- Ícone decorativo --}}
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
                <h2 id="modal-duplicar-titulo"
                    class="text-base font-bold text-slate-900">
                    Duplicar Transação
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Esta ação criará uma cópia idêntica.</p>
            </div>
        </div>

        {{-- Descrição da transação --}}
        <p class="text-sm text-slate-600 mb-1">
            Deseja realmente duplicar a transação:
        </p>
        <p class="text-sm font-semibold text-slate-900 bg-slate-50 border border-slate-100
                  rounded-xl px-4 py-2.5 mb-5 truncate"
           id="modal-duplicar-label">-</p>

        {{-- Nota informativa --}}
        <div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-100
                    rounded-xl px-3.5 py-3 mb-5">
            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
            </svg>
            <p class="text-xs text-emerald-700 leading-relaxed">
                A cópia será criada com a descrição <strong>"… (Cópia)"</strong>,
                mantendo o mesmo tipo, valor, data e categoria.
            </p>
        </div>

        {{-- Formulário de submit --}}
        <form id="form-duplicar-transacao" method="POST" action="">
            @csrf
            <div class="flex items-center justify-end gap-2.5">
                <button type="button"
                        onclick="closeDuplicateModal()"
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

@push('scripts')
<script>
(function () {
    'use strict';

    const modal      = document.getElementById('modal-duplicar-transacao');
    const form       = document.getElementById('form-duplicar-transacao');
    const labelEl    = document.getElementById('modal-duplicar-label');
    const baseUrl    = '/transacoes/';
    const sufixo     = '/duplicar';

    /**
     * Abre o modal preenchendo a action do form e o nome da transação.
     * Chamado pelos botões inline via onclick="openDuplicateModal(id, 'descricao')".
     */
    window.openDuplicateModal = function (id, descricao) {
        form.action   = baseUrl + id + sufixo;
        labelEl.textContent = descricao;
        modal.classList.remove('hidden');
        // Foca no botão Cancelar por acessibilidade
        setTimeout(() => modal.querySelector('button[type="button"]').focus(), 50);
    };

    window.closeDuplicateModal = function () {
        modal.classList.add('hidden');
        form.action      = '';
        labelEl.textContent = '-';
    };

    // Fecha ao clicar no backdrop (fora do card)
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeDuplicateModal();
    });

    // Fecha com Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeDuplicateModal();
        }
    });
})();
</script>

{{-- CSS dos tooltips (complementa o app.css) --}}
<style>
.fp-tooltip {
    pointer-events: none;
    position: absolute;
    z-index: 20;
    white-space: nowrap;
    background: #1e293b;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    opacity: 0;
    transition: opacity .15s;
}
.group:hover .fp-tooltip { opacity: 1; }

/* Posição: acima do botão */
.fp-tooltip-top {
    bottom: calc(100% + 6px);
    left: 50%;
    transform: translateX(-50%);
}
.fp-tooltip-top::after {
    content: '';
    position: absolute;
    top: 100%; left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: #1e293b;
}

/* Posição: à esquerda (para último item da linha) */
.fp-tooltip-left {
    right: calc(100% + 6px);
    top: 50%;
    transform: translateY(-50%);
}
.fp-tooltip-left::after {
    content: '';
    position: absolute;
    left: 100%; top: 50%;
    transform: translateY(-50%);
    border: 4px solid transparent;
    border-left-color: #1e293b;
}
</style>
@endpush

@endsection
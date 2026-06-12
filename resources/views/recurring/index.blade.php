@extends('layouts.app')
@section('title', 'Contas Recorrentes')
@section('content')

{{-- ── Cabeçalho ── --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 leading-tight">Contas Recorrentes</h1>
        <p class="text-sm text-slate-500 mt-0.5">Modelos de transações que se repetem todo mês.</p>
    </div>
    <a href="{{ route('recorrentes.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700
              text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm transition-all w-full sm:w-auto">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Nova Recorrente
    </a>
</div>

{{-- ── Lista ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    @if($templates->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center px-6">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-700">Nenhuma conta recorrente cadastrada</p>
            <p class="text-xs text-slate-400 mt-1 mb-5 max-w-xs">
                Adicione contas que se repetem todo mês e receba lembretes automáticos ao abrir o sistema.
            </p>
            <a href="{{ route('recorrentes.create') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Criar primeira recorrente
            </a>
        </div>

    @else

        {{-- ── Mobile ── --}}
        <div class="divide-y divide-slate-100 md:hidden">
            @foreach($templates as $t)
                <div class="p-4" x-data="{ openDelete: false }">
                    <div class="flex items-start justify-between gap-3">

                        <div class="flex-shrink-0 mt-0.5">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center
                                {{ $t->type === 'expense' ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-500' }}">
                                @if($t->type === 'expense')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/>
                                    </svg>
                                @else
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $t->description }}</p>
                                @if(!$t->active)
                                    <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full flex-shrink-0">Pausado</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-xs text-slate-400">vence dia {{ $t->day_of_month }}</span>
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
                            <div class="flex items-center gap-2">

                                {{-- Switch toggle mobile --}}
                                <form method="POST" action="{{ route('recorrentes.update', $t->id) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="description"  value="{{ $t->description }}">
                                    <input type="hidden" name="amount"       value="{{ $t->amount }}">
                                    <input type="hidden" name="type"         value="{{ $t->type }}">
                                    <input type="hidden" name="day_of_month" value="{{ $t->day_of_month }}">
                                    <input type="hidden" name="category_id"  value="{{ $t->category_id }}">
                                    <input type="hidden" name="active"       value="{{ $t->active ? '0' : '1' }}">
                                    <button type="submit" title="{{ $t->active ? 'Desativar lembrete' : 'Ativar lembrete' }}"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2
                                                   transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1
                                                   {{ $t->active ? 'bg-indigo-50 border-indigo-200 hover:bg-indigo-100' : 'bg-slate-200 border-transparent' }}">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full shadow
                                                     transition duration-200 ease-in-out
                                                     {{ $t->active ? 'bg-indigo-500 translate-x-4' : 'bg-white translate-x-0' }}"></span>
                                    </button>
                                </form>

                                <a href="{{ route('recorrentes.edit', $t->id) }}"
                                   class="h-7 w-7 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>

                                <button @click="openDelete = true"
                                        class="h-7 w-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @include('partials.delete-modal', [
                        'id'    => $t->id,
                        'label' => $t->description,
                        'route' => route('recorrentes.destroy', $t->id)
                    ])
                </div>
            @endforeach
        </div>

        {{-- ── Desktop ── --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Descrição</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Tipo</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-right">Valor</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Vence</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center">Lembrete</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wide text-center w-28">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($templates as $index => $t)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }} hover:bg-indigo-50/30 transition-colors {{ $t->active ? '' : 'opacity-60' }}"
                        x-data="{ openDelete: false }">

                        {{-- Descrição --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex-shrink-0 flex items-center justify-center
                                    {{ $t->type === 'expense' ? 'bg-red-50 text-red-400' : 'bg-emerald-50 text-emerald-500' }}">
                                    @if($t->type === 'expense')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-900">{{ $t->description }}</span>
                                    @if(!$t->active)
                                        <span class="ml-2 text-xs font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">Pausado</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Categoria --}}
                        <td class="px-5 py-4">
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

                        {{-- Tipo --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $t->type === 'expense' ? 'bg-red-50 text-red-700 ring-1 ring-red-100' : 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $t->type === 'expense' ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                                {{ $t->type === 'expense' ? 'Despesa' : 'Receita' }}
                            </span>
                        </td>

                        {{-- Valor --}}
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold tabular-nums {{ $t->type === 'expense' ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $t->type === 'expense' ? '−' : '+' }} R$ {{ number_format($t->amount, 2, ',', '.') }}
                            </span>
                        </td>

                        {{-- Vence --}}
                        <td class="px-5 py-4 text-center">
                            <span class="text-sm text-slate-500 tabular-nums">dia {{ $t->day_of_month }}</span>
                        </td>

                        {{-- Switch lembrete ativo --}}
                        <td class="px-5 py-4 text-center">
                            <form method="POST" action="{{ route('recorrentes.update', $t->id) }}" class="inline-flex items-center justify-center">
                                @csrf @method('PUT')
                                <input type="hidden" name="description"  value="{{ $t->description }}">
                                <input type="hidden" name="amount"       value="{{ $t->amount }}">
                                <input type="hidden" name="type"         value="{{ $t->type }}">
                                <input type="hidden" name="day_of_month" value="{{ $t->day_of_month }}">
                                <input type="hidden" name="category_id"  value="{{ $t->category_id }}">
                                {{-- Inverte o estado: ativo → desativa / pausado → ativa --}}
                                <input type="hidden" name="active"       value="{{ $t->active ? '0' : '1' }}">
                                <button type="submit"
                                        title="{{ $t->active ? 'Desativar lembrete' : 'Ativar lembrete' }}"
                                        aria-label="{{ $t->active ? 'Desativar' : 'Ativar' }} lembrete de {{ $t->description }}"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2
                                               transition-colors duration-200 ease-in-out
                                               focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2
                                               {{ $t->active ? 'bg-indigo-50 border-indigo-200 hover:bg-indigo-100' : 'bg-slate-200 border-transparent hover:bg-slate-300' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full shadow
                                                 ring-0 transition duration-200 ease-in-out
                                                 {{ $t->active ? 'bg-indigo-500 translate-x-5' : 'bg-white translate-x-0' }}"></span>
                                </button>
                            </form>
                        </td>

                        {{-- Ações --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-1.5">

                                {{-- Editar --}}
                                <div class="relative group">
                                    <a href="{{ route('recorrentes.edit', $t->id) }}"
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
                                'route' => route('recorrentes.destroy', $t->id)
                            ])
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

    @if($templates->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $templates->links() }}
        </div>
    @endif
</div>

@push('scripts')
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
</style>
@endpush

@endsection

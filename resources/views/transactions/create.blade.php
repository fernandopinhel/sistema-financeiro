@extends('layouts.app')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('transacoes.index') }}"
           class="h-9 w-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Nova Transação</h1>
            {{-- ← NOVO: badge quando vier de um template --}}
            @if($prefill)
                <p class="text-xs text-indigo-600 font-medium mt-0.5 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Pré-preenchido a partir de "{{ $prefill->description }}"
                </p>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl">
            <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('transacoes.store') }}" class="space-y-5">
            @csrf

            {{-- ← NOVO: campo oculto que vincula a transação ao template --}}
            @if($prefill)
                <input type="hidden" name="recurring_template_id" value="{{ $prefill->id }}">
            @endif

            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tipo</label>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Receita --}}
                    <label class="relative cursor-pointer">
                        {{-- ← pré-seleciona o tipo do template se existir --}}
                        <input type="radio" name="type" value="income"
                               {{ old('type', $prefill->type ?? 'income') === 'income' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-slate-200
                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                            <svg class="w-5 h-5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Receita</p>
                                <p class="text-xs text-slate-400">Entrada de valor</p>
                            </div>
                        </div>
                    </label>

                    {{-- Despesa --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="expense"
                               {{ old('type', $prefill->type ?? 'income') === 'expense' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-slate-200
                                    peer-checked:border-red-400 peer-checked:bg-red-50 transition-all">
                            <svg class="w-5 h-5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Despesa</p>
                                <p class="text-xs text-slate-400">Saída de valor</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label for="description" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Descrição</label>
                <input type="text" name="description" id="description" required maxlength="255"
                       {{-- ← pré-preenche a descrição do template --}}
                       value="{{ old('description', $prefill->description ?? '') }}"
                       placeholder="Ex: Aluguel, Salário, Supermercado..."
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              placeholder-slate-400
                              {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Valor --}}
            <div>
                <label for="amount" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Valor (R$)</label>
                <input type="text" name="amount" id="amount" required
                       {{-- ← pré-preenche o valor formatado do template --}}
                       value="{{ old('amount', $prefill ? number_format($prefill->amount, 2, ',', '.') : '') }}"
                       placeholder="0,00"
                       inputmode="decimal"
                       oninput="formatarValor(this)"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              placeholder-slate-400
                              {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('amount')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Data --}}
            <div>
                <label for="date" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Data</label>
                {{-- ← sugerimos o dia de vencimento do template no mês atual --}}
                @php
                    $dataDefault = $prefill
                        ? now()->day($prefill->day_of_month)->format('Y-m-d')
                        : now()->format('Y-m-d');
                @endphp
                <input type="date" name="date" id="date" required
                       value="{{ old('date', $dataDefault) }}"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('date') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @if($prefill)
                    <p class="mt-1.5 text-xs text-slate-400">
                        Data sugerida com base no vencimento do template (dia {{ $prefill->day_of_month }}). Ajuste se necessário.
                    </p>
                @endif
                @error('date')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Categoria --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wide">
                        Categoria <span class="text-slate-300 normal-case font-normal">(opcional)</span>
                    </label>
                    <button type="button"
                            onclick="document.getElementById('modal-nova-categoria-rapida').classList.remove('hidden')"
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nova categoria
                    </button>
                </div>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl
                               outline-none transition-all focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50 cursor-pointer">
                    <option value="">- Sem categoria -</option>
                    @foreach($categories as $cat)
                        {{-- ← pré-seleciona a categoria do template --}}
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $prefill->category_id ?? null) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ route('transacoes.index') }}"
                   class="flex-1 py-3 text-center text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 px-4
                               bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                               text-white text-sm font-bold rounded-xl shadow-sm
                               transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                    Salvar Transação
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal rápido nova categoria (inalterado) --}}
<div id="modal-nova-categoria-rapida"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">Nova Categoria</h2>
            <button onclick="document.getElementById('modal-nova-categoria-rapida').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="redirect_back" value="transaction">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nome</label>
                <input type="text" name="name" required maxlength="100" placeholder="Ex: Lazer"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl outline-none transition-all
                              placeholder-slate-400 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50">
            </div>
            <div class="flex items-end gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cor</label>
                    <input type="color" name="color" value="#6366f1"
                           class="h-10 w-14 rounded-xl border border-slate-200 cursor-pointer p-1 bg-slate-50">
                </div>
                <label class="flex items-center gap-2 cursor-pointer pb-1">
                    <input type="checkbox" name="show_on_dashboard" value="1"
                           class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400">
                    <span class="text-xs text-slate-600 font-medium">Mostrar no dashboard</span>
                </label>
            </div>
            <div class="flex gap-2 pt-1">
                <button type="button"
                        onclick="document.getElementById('modal-nova-categoria-rapida').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-all">
                    Criar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function formatarValor(input) {
    let v = input.value.replace(/\D/g, '');
    v = (parseInt(v || '0') / 100).toFixed(2);
    v = v.replace('.', ',');
    v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    input.value = v;
}
</script>

@endsection
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
        <h1 class="text-2xl font-bold text-gray-800">Editar Transação</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
        {{--
            CORREÇÃO: action aponta para transacoes.update com o ID correto
            e usa @method('PUT') para spoofing do Laravel.
        --}}
        <form method="POST" action="{{ route('transacoes.update', $transaction->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tipo</label>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Receita --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="income"
                               {{ old('type', $transaction->type) === 'income' ? 'checked' : '' }}
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
                               {{ old('type', $transaction->type) === 'expense' ? 'checked' : '' }}
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
                       value="{{ old('description', $transaction->description) }}"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
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
                       value="{{ old('amount', number_format($transaction->amount, 2, ',', '.')) }}"
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

            {{-- Data — CORREÇÃO: Carbon formata para Y-m-d compatível com input[type=date] --}}
            <div>
                <label for="date" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Data</label>
                <input type="date" name="date" id="date" required
                       value="{{ old('date', \Carbon\Carbon::parse($transaction->date)->format('Y-m-d')) }}"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('date') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
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
                    <a href="{{ route('categories.index') }}"
                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                        Gerenciar →
                    </a>
                </div>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl
                               outline-none transition-all focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50 cursor-pointer">
                    <option value="">- Sem categoria -</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
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
                               bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm
                               transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                    Salvar Alterações
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

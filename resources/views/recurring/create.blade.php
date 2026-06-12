@extends('layouts.app')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('recorrentes.index') }}"
           class="h-9 w-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-600 shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Nova Conta Recorrente</h1>
            <p class="text-xs text-slate-400 mt-0.5">Define um modelo que gera lembretes todo mês.</p>
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
        <form method="POST" action="{{ route('recorrentes.store') }}" class="space-y-5">
            @csrf

            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tipo</label>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Receita --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="income"
                               {{ old('type') === 'income' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-slate-200
                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                            <svg class="w-5 h-5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Receita</p>
                                <p class="text-xs text-slate-400">Entrada mensal</p>
                            </div>
                        </div>
                    </label>

                    {{-- Despesa --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="expense"
                               {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border-2 border-slate-200
                                    peer-checked:border-red-400 peer-checked:bg-red-50 transition-all">
                            <svg class="w-5 h-5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-slate-700">Despesa</p>
                                <p class="text-xs text-slate-400">Saída mensal</p>
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
                       value="{{ old('description') }}"
                       placeholder="Ex: Aluguel, Salário, Netflix..."
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
                       value="{{ old('amount') }}"
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

            {{-- Dia do mês --}}
            <div>
                <label for="day_of_month" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                    Dia de vencimento
                    <span class="text-slate-300 normal-case font-normal">(dia do mês esperado)</span>
                </label>
                <input type="number" name="day_of_month" id="day_of_month" required
                       min="1" max="31"
                       value="{{ old('day_of_month', 1) }}"
                       placeholder="1"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('day_of_month') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('day_of_month')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Categoria --}}
            <div>
                <label for="category_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                    Categoria <span class="text-slate-300 normal-case font-normal">(opcional)</span>
                </label>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-xl
                               outline-none transition-all focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50 cursor-pointer">
                    <option value="">- Sem categoria -</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Toggle ativo --}}
            <div class="flex items-center justify-between py-3 px-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <p class="text-sm font-semibold text-slate-700">Lembrete ativo</p>
                    <p class="text-xs text-slate-400">Desative para pausar sem excluir.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="active" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-checked:bg-indigo-50 peer-checked:ring-2 peer-checked:ring-inset peer-checked:ring-indigo-200 rounded-full transition-colors
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                peer-checked:after:bg-indigo-500 peer-checked:after:translate-x-5"></div>
                </label>
            </div>


            {{-- Botões --}}
            <div class="flex gap-3 pt-1">
                <a href="{{ route('recorrentes.index') }}"
                   class="flex-1 py-3 text-center text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 px-4
                               bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                               text-white text-sm font-bold rounded-xl shadow-sm
                               transition-all focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                    Salvar Recorrente
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
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
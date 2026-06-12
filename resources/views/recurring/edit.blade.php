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
            <h1 class="text-2xl font-bold text-gray-800">Editar Conta Recorrente</h1>
            <p class="text-xs text-slate-400 mt-0.5">Altere o modelo de lembrete mensal.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('recorrentes.update', $template->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tipo</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="income"
                               {{ old('type', $template->type) === 'income' ? 'checked' : '' }}
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
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="expense"
                               {{ old('type', $template->type) === 'expense' ? 'checked' : '' }}
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
            </div>

            {{-- Descrição --}}
            <div>
                <label for="description" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Descrição</label>
                <input type="text" name="description" id="description" required maxlength="255"
                       value="{{ old('description', $template->description) }}"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Valor --}}
            <div>
                <label for="amount" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Valor (R$)</label>
                <input type="text" name="amount" id="amount" required
                       value="{{ old('amount', number_format($template->amount, 2, ',', '.')) }}"
                       inputmode="decimal"
                       oninput="formatarValor(this)"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('amount')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dia do mês --}}
            <div>
                <label for="day_of_month" class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                    Dia de vencimento
                </label>
                <input type="number" name="day_of_month" id="day_of_month" required
                       min="1" max="31"
                       value="{{ old('day_of_month', $template->day_of_month) }}"
                       class="w-full px-4 py-3 text-sm text-slate-900 bg-slate-50 border rounded-xl outline-none transition-all
                              {{ $errors->has('day_of_month') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:bg-white focus:border-indigo-400 focus:ring-3 focus:ring-indigo-50' }}">
                @error('day_of_month')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">{{ $message }}</p>
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
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $template->category_id) == $cat->id ? 'selected' : '' }}>
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
                    <input type="checkbox" name="active" value="1" class="sr-only peer"
                           {{ old('active', $template->active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-checked:bg-indigo-50 peer-checked:ring-2 peer-checked:ring-inset peer-checked:ring-indigo-200 rounded-full transition-colors
                                after:content-[''] after:absolute after:top-0.5 after:left-0.5
                                after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                peer-checked:after:bg-indigo-500 peer-checked:after:translate-x-5"></div>
                </label>
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ route('recorrentes.index') }}"
                   class="flex-1 py-3 text-center text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 px-4
                               bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all">
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
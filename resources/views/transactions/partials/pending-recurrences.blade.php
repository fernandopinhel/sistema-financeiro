@if($pendentes->isNotEmpty())
<div class="mb-6" x-data="{ aberto: false }">

    {{-- Cabeçalho colapsável --}}
    <button type="button"
            @click="aberto = !aberto"
            class="w-full flex items-center justify-between mb-3 group">
        <div class="flex items-center gap-2">
            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-red-100">
                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </span>
            <span class="text-sm font-semibold text-slate-700">Pendentes este mês</span>
            <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-0.5 rounded-full">
                {{ $pendentes->count() }}
            </span>
        </div>
        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
             :class="aberto ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Lista de pendentes --}}
    <div x-show="aberto"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="flex flex-col gap-2">

        @foreach($pendentes as $template)
        <div class="flex items-center justify-between gap-3 px-4 py-3 bg-white rounded-xl border border-slate-200
                    {{ $template->type === 'expense' ? 'border-l-red-400' : 'border-l-emerald-400' }} border-l-[3px]">

            <div class="flex items-center gap-3 min-w-0">
                {{-- Ícone do tipo --}}
                <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg
                             {{ $template->type === 'expense' ? 'bg-red-50 text-red-400' : 'bg-emerald-50 text-emerald-500' }}">
                    @if($template->type === 'expense')
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    @endif
                </span>

                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ $template->description }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        @if($template->category)
                            <span class="w-2 h-2 rounded-full flex-shrink-0"
                                  style="background-color: {{ $template->category->color }}"></span>
                            <span class="text-xs text-slate-400">{{ $template->category->name }} ·</span>
                        @endif
                        <span class="text-xs text-slate-400">vence dia {{ $template->day_of_month }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="text-sm font-semibold {{ $template->type === 'expense' ? 'text-red-500' : 'text-emerald-600' }}">
                    {{ $template->type === 'expense' ? '–' : '+' }}
                    R$ {{ number_format($template->amount, 2, ',', '.') }}
                </span>
                {{-- Botão Registrar: redireciona para create pré-preenchido --}}
                <a href="{{ route('transacoes.create', ['from_template' => $template->id]) }}"
                   class="flex items-center gap-1 text-xs font-semibold text-fp-accent bg-fp-sec hover:bg-fp-sec-h border border-fp-sec-bd hover:border-fp-sec-bd-h
                          px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrar
                </a>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endif
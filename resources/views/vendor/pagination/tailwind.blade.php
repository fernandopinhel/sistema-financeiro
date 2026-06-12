@if ($paginator->hasPages())
<nav role="navigation" aria-label="Paginação"
     class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

    {{-- Descrição --}}
    <p class="text-sm text-slate-500">
        Página
        <span class="font-semibold text-slate-700">{{ $paginator->currentPage() }}</span>
        de
        <span class="font-semibold text-slate-700">{{ $paginator->lastPage() }}</span>
        -
        <span class="font-semibold text-slate-700">{{ $paginator->count() }}</span>
        {{ $paginator->count() === 1 ? 'item' : 'itens' }} nesta página
        -
        <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
        no total
    </p>

    {{-- Botões --}}
    <div class="flex items-center gap-1">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true"
                  class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                         text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"
               class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                      text-slate-500 bg-white border border-slate-200
                      hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600
                      transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        @endif

        {{-- Números --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 text-sm text-slate-400 select-none">
                    &hellip;
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                     text-sm font-bold text-fp-accent bg-fp-sec border border-fp-sec-bd
                                     cursor-default">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" aria-label="Página {{ $page }}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                  text-sm font-medium text-slate-600 bg-white border border-slate-200
                                  hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600
                                  transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Próximo --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Próxima página"
               class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                      text-slate-500 bg-white border border-slate-200
                      hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600
                      transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
        @else
            <span aria-disabled="true"
                  class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                         text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
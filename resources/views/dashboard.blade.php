@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Cabeçalho --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 md:mb-8">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Olá, {{ Auth::user()->name }}
        </h1>
        <p class="text-sm text-slate-400 mt-0.5">Aqui está o resumo das suas finanças.</p>
    </div>

    {{-- Filtros + Exportação — grid 2x2 no mobile (ano+mês / excel+pdf),
         linha única a partir de sm: --}}
    <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-row sm:items-center w-full sm:w-auto">

        {{-- Select Ano — customizado com seta correta --}}
        <div class="relative">
            <select id="select-ano" class="fp-select fp-select-sm">
                @foreach($anosDisponiveis as $a)
                    <option value="{{ $a }}" {{ (string)$ano === (string)$a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        {{-- Select Mês --}}
        <div class="relative">
            <select id="select-mes" class="fp-select fp-select-sm">
                <option value="">Todos os Meses</option>
                @php
                    $mesesNomes = [1=>'Jan',2=>'Fev',3=>'Mar',4=>'Abr',5=>'Mai',6=>'Jun',
                                   7=>'Jul',8=>'Ago',9=>'Set',10=>'Out',11=>'Nov',12=>'Dez'];
                    $mesSelecionado = request('mes');
                @endphp
                @foreach($mesesNomes as $num => $nome)
                    <option value="{{ $num }}" {{ (string)$mesSelecionado === (string)$num ? 'selected' : '' }}>
                        {{ $nome }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Export Excel --}}
        <a id="btn-excel"
           href="{{ route('transacoes.export.excel', ['ano' => $ano, 'mes' => request('mes')]) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center justify-center gap-1.5 text-sm px-3 py-2.5 sm:py-2 bg-emerald-600 hover:bg-emerald-700
                  active:bg-emerald-800 text-white rounded-xl shadow-sm font-semibold transition-all whitespace-nowrap">
            <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Excel
        </a>

        {{-- Export PDF — abre em nova aba --}}
        <a id="btn-pdf"
           href="{{ route('transacoes.export.pdf', ['ano' => $ano, 'mes' => request('mes')]) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center justify-center gap-1.5 text-sm px-3 py-2.5 sm:py-2 bg-rose-600 hover:bg-rose-700
                  active:bg-rose-800 text-white rounded-xl shadow-sm font-semibold transition-all whitespace-nowrap">
            <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            PDF
        </a>
    </div>
</div>

{{-- Grid de Cards Contadores --}}
<div id="dashboard-cards-grid"
     class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6 md:mb-8"
     x-data="{}"
     x-sort="window.FpDashboard.onCardsSort()"
     x-sort.ghost
     x-sort:config="{ delay: 150, delayOnTouchOnly: true }">

    {{-- Receitas --}}
    <div data-card-key="receitas" x-sort:item="receitas"
         class="fp-draggable bg-white border border-slate-100 rounded-2xl shadow-sm p-5 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-1">Receitas</p>
            <h4 id="card-receitas" class="text-xl md:text-2xl font-bold text-emerald-600 whitespace-nowrap">
                R$ {{ number_format($receitas, 2, ',', '.') }}
            </h4>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100/50 flex items-center justify-center p-3 text-emerald-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="flex sm:hidden flex-col gap-1">
                <button type="button" onclick="window.FpDashboard.moveCard('receitas','up')" aria-label="Mover Receitas para cima" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                </button>
                <button type="button" onclick="window.FpDashboard.moveCard('receitas','down')" aria-label="Mover Receitas para baixo" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Despesas --}}
    <div data-card-key="despesas" x-sort:item="despesas"
         class="fp-draggable bg-white border border-slate-100 rounded-2xl shadow-sm p-5 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-1">Despesas</p>
            <h4 id="card-despesas" class="text-xl md:text-2xl font-bold text-rose-600 whitespace-nowrap">
                R$ {{ number_format($despesas, 2, ',', '.') }}
            </h4>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100/50 flex items-center justify-center p-3 text-rose-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
            <div class="flex sm:hidden flex-col gap-1">
                <button type="button" onclick="window.FpDashboard.moveCard('despesas','up')" aria-label="Mover Despesas para cima" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                </button>
                <button type="button" onclick="window.FpDashboard.moveCard('despesas','down')" aria-label="Mover Despesas para baixo" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Saldo --}}
    <div data-card-key="saldo" x-sort:item="saldo"
         class="fp-draggable bg-white border border-slate-100 rounded-2xl shadow-sm p-5 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-1">Saldo Atual</p>
            <h4 id="card-saldo" class="text-xl md:text-2xl font-bold whitespace-nowrap {{ $saldo >= 0 ? 'text-indigo-600' : 'text-rose-700' }}">
                R$ {{ number_format($saldo, 2, ',', '.') }}
            </h4>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100/50 flex items-center justify-center p-3 text-indigo-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex sm:hidden flex-col gap-1">
                <button type="button" onclick="window.FpDashboard.moveCard('saldo','up')" aria-label="Mover Saldo para cima" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                </button>
                <button type="button" onclick="window.FpDashboard.moveCard('saldo','down')" aria-label="Mover Saldo para baixo" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="dashboard-blocks-grid"
     class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Gráfico --}}
    <div data-card-key="grafico"
         class="lg:col-span-2 bg-white border border-slate-100 rounded-2xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 id="titulo-grafico" class="text-base font-bold text-slate-800">
                Gastos por Mês ({{ $ano }})
            </h3>
            {{-- Mover (sem drag-and-drop — botões pra reordenar em qualquer tamanho de tela) --}}
            <div class="flex gap-0.5">
                <button type="button" onclick="window.FpDashboard.moveBlock('grafico','up')" aria-label="Mover Gráfico para cima" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                </button>
                <button type="button" onclick="window.FpDashboard.moveBlock('grafico','down')" aria-label="Mover Gráfico para baixo" class="fp-move-btn">
                    <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
        </div>
        <div class="h-[300px] relative">
            <canvas id="grafico-gastos" aria-label="Gráfico de gastos mensais" role="img"></canvas>
        </div>
    </div>

    {{-- Categorias com filtro de busca --}}
    <div data-card-key="categorias"
         class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 flex flex-col">
        <div class="mb-3">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Categorias</h3>
                {{-- Mover (sem drag-and-drop — botões pra reordenar em qualquer tamanho de tela) --}}
                <div class="flex gap-0.5">
                    <button type="button" onclick="window.FpDashboard.moveBlock('categorias','up')" aria-label="Mover Categorias para cima" class="fp-move-btn">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <button type="button" onclick="window.FpDashboard.moveBlock('categorias','down')" aria-label="Mover Categorias para baixo" class="fp-move-btn">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
            <div id="categorias-badges-row" class="flex flex-wrap items-center gap-2 mt-2">
                <span id="total-geral-badge"
                      class="inline-flex items-center flex-shrink-0 text-sm font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-xl whitespace-nowrap">
                    Total: R$ {{ number_format($totalGeral, 2, ',', '.') }}
                </span>
                @if($maiorDespesaCategoriaId)
                    <span id="maior-despesa-badge"
                          class="inline-flex items-center gap-1 max-w-full text-sm font-bold text-rose-700 bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-xl"
                          title="Maior despesa do período" aria-label="Maior despesa do período">
                        <svg width="12" height="12" class="flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                        </svg>
                        <span id="maior-despesa-nome" class="truncate min-w-0">{{ $maiorDespesaCategoriaNome }}</span>
                        <span id="maior-despesa-valor" class="whitespace-nowrap flex-shrink-0">R$ {{ number_format($maiorDespesaValor, 2, ',', '.') }}</span>
                    </span>
                @endif
                @if($maiorReceitaCategoriaId)
                    <span id="maior-receita-badge"
                          class="inline-flex items-center gap-1 max-w-full text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-xl"
                          title="Maior receita do período" aria-label="Maior receita do período">
                        <svg width="12" height="12" class="flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span id="maior-receita-nome" class="truncate min-w-0">{{ $maiorReceitaCategoriaNome }}</span>
                        <span id="maior-receita-valor" class="whitespace-nowrap flex-shrink-0">R$ {{ number_format($maiorReceitaValor, 2, ',', '.') }}</span>
                    </span>
                @endif
            </div>
        </div>

        {{-- Filtro de busca por categoria --}}
        <div class="fp-cat-search">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text"
                   id="filtro-categoria"
                   placeholder="Buscar categoria..."
                   autocomplete="off"
                   aria-label="Filtrar categorias por nome">
        </div>

        <div id="container-categorias" class="space-y-2 flex-1 overflow-y-auto max-h-[280px] pr-1">
            @forelse($dadosCards as $card)
                <div class="cat-item flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100/80"
                     data-name="{{ strtolower($card['name']) }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $card['color'] }}"></span>
                        <span class="text-sm font-semibold text-slate-700 truncate cat-name">{{ $card['name'] }}</span>
                        @if($card['id'] === $maiorReceitaCategoriaId)
                            <span class="cat-crown cat-crown-receita" title="Maior receita do período" aria-label="Maior receita do período">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </span>
                        @endif
                        @if($card['id'] === $maiorDespesaCategoriaId)
                            <span class="cat-crown cat-crown-despesa" title="Maior despesa do período" aria-label="Maior despesa do período">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-500 bg-white border border-slate-100 px-2 py-1 rounded-lg flex-shrink-0">
                        R$ {{ number_format($card['total'], 2, ',', '.') }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400">
                    <p class="text-xs font-medium">Nenhuma categoria ativa para o dashboard.</p>
                </div>
            @endforelse

            {{-- Mensagem vazia do filtro --}}
            <div id="cat-empty" class="hidden text-center py-6 text-slate-400">
                <p class="text-xs font-medium">Nenhuma categoria encontrada.</p>
            </div>
        </div>
    </div>
</div>

@include('components.cookie-banner')

@push('scripts')
<style>
/* Dashboard arrastável — affordance visual de drag-and-drop (@alpinejs/sort) */
.fp-draggable { cursor: grab; }
.fp-draggable:active { cursor: grabbing; }

/* Botões de mover ↑/↓ — usados pelos blocos de Gráfico/Categorias em
   qualquer tamanho de tela (sem drag-and-drop) e pelos cards de resumo
   no mobile (no desktop os cards de resumo continuam arrastáveis). */
.fp-move-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 6px;
    color: #6b7280; background: #f3f4f6; border: none;
    transition: background-color .15s, color .15s;
}
.fp-move-btn:active { background: #e5e7eb; color: #374151; }

/* Selo de "maior despesa"/"maior receita" da categoria no período filtrado */
.cat-crown {
    display: inline-flex; align-items: center; justify-content: center;
    width: 16px; height: 16px; border-radius: 999px; flex-shrink: 0;
}
.cat-crown-despesa { background: #ffe4e6; color: #e11d48; }
.cat-crown-receita { background: #d1fae5; color: #059669; }

/* Placeholder deixado pelo x-sort.ghost no lugar durante o arraste */
.sortable-ghost { opacity: .4; }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // ── Referências ────────────────────────────────────────────────
        const ctx          = document.getElementById('grafico-gastos');
        const selectAno    = document.getElementById('select-ano');
        const selectMes    = document.getElementById('select-mes');
        const btnExcel     = document.getElementById('btn-excel');
        const btnPdf       = document.getElementById('btn-pdf');
        const filtroCat    = document.getElementById('filtro-categoria');
        const catEmpty     = document.getElementById('cat-empty');

        const excelBase    = "{{ route('transacoes.export.excel') }}";
        const pdfBase      = "{{ route('transacoes.export.pdf') }}";
        const dashboardUrl = "{{ route('dashboard') }}";
        const csrfToken    = "{{ csrf_token() }}";

        const MESES_NOMES  = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                              'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

        let dadosAnuaisGlobal = @json($dadosAnuais ?? array_fill(0, 12, 0));

        // ── Cores do gráfico ─────────────────────────────────────────
        function obterCores(mesIndex) {
            const ativo   = 'rgba(99,102,241,0.85)';
            const inativo = 'rgba(99,102,241,0.18)';
            const bAtivo  = 'rgb(99,102,241)';
            const bInat   = 'rgba(99,102,241,0.35)';

            if (!mesIndex) {
                return { bg: Array(12).fill('rgba(99,102,241,0.6)'), border: Array(12).fill(bAtivo) };
            }
            const idx = parseInt(mesIndex) - 1;
            return {
                bg:     Array.from({length:12}, (_,i) => i===idx ? ativo  : inativo),
                border: Array.from({length:12}, (_,i) => i===idx ? bAtivo : bInat)
            };
        }

        if (!ctx) return;

        const coresIniciais = obterCores(selectMes.value);
        const grafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
                datasets: [{
                    label: 'Despesas (R$)',
                    data: dadosAnuaisGlobal,
                    backgroundColor: coresIniciais.bg,
                    borderColor: coresIniciais.border,
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(241,245,249,0.8)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ── Formatar moeda ───────────────────────────────────────────
        function formatarMoeda(v) {
            return 'R$ ' + Number(v).toFixed(2)
                .replace('.', ',')
                .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        }

        // ── Atualiza href dos botões de export ───────────────────────
        function atualizarHrefs() {
            const ano = selectAno.value;
            const mes = selectMes.value;
            const qs  = '?ano=' + ano + (mes ? '&mes=' + mes : '');
            btnExcel.href = excelBase + qs;
            btnPdf.href   = pdfBase   + qs;
        }

        // ── Busca AJAX e atualiza dashboard ──────────────────────────
        function gerenciarFiltros() {
            const ano = selectAno.value;
            const mes = selectMes.value;

            atualizarHrefs();

            document.getElementById('titulo-grafico').textContent =
                mes ? `Gastos em ${MESES_NOMES[mes]} de ${ano}` : `Gastos por Mês (${ano})`;

            fetch(dashboardUrl + '?ano=' + ano + (mes ? '&mes=' + mes : '') + '&json=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => {
                dadosAnuaisGlobal = data.dadosAnuais;
                const cores = obterCores(mes);

                grafico.data.datasets[0].data            = dadosAnuaisGlobal;
                grafico.data.datasets[0].backgroundColor = cores.bg;
                grafico.data.datasets[0].borderColor      = cores.border;
                grafico.update();

                document.getElementById('card-receitas').textContent = formatarMoeda(data.receitas);
                document.getElementById('card-despesas').textContent = formatarMoeda(data.despesas);

                const cardSaldo = document.getElementById('card-saldo');
                cardSaldo.textContent = formatarMoeda(data.saldo);
                cardSaldo.className = 'text-xl md:text-2xl font-bold whitespace-nowrap ' +
                    (data.saldo >= 0 ? 'text-indigo-600' : 'text-rose-700');

                document.getElementById('total-geral-badge').textContent =
                    'Total: ' + formatarMoeda(data.totalGeral);

                // Badges de "maior despesa"/"maior receita" — removidos e
                // recriados a cada filtro, já que podem aparecer, sumir ou
                // trocar de categoria dependendo do período selecionado.
                document.getElementById('maior-despesa-badge')?.remove();
                document.getElementById('maior-receita-badge')?.remove();

                const badgesRow = document.getElementById('categorias-badges-row');

                function criarBadgeDestaque(id, nome, valor, cor, pathIcone, tituloAcessivel) {
                    const span = document.createElement('span');
                    span.id = id;
                    span.className = `inline-flex items-center gap-1 max-w-full text-sm font-bold text-${cor}-700 bg-${cor}-50 border border-${cor}-100 px-2.5 py-1 rounded-xl`;
                    span.title = tituloAcessivel;
                    span.setAttribute('aria-label', tituloAcessivel);
                    span.innerHTML = `
                        <svg width="12" height="12" class="flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="${pathIcone}"/>
                        </svg>
                        <span class="truncate min-w-0"></span>
                        <span class="whitespace-nowrap flex-shrink-0"></span>`;
                    span.querySelector('span:nth-of-type(1)').textContent = nome;
                    span.querySelector('span:nth-of-type(2)').textContent = formatarMoeda(valor);
                    return span;
                }

                if (data.maiorDespesaCategoriaId) {
                    badgesRow.appendChild(criarBadgeDestaque(
                        'maior-despesa-badge', data.maiorDespesaCategoriaNome, data.maiorDespesaValor,
                        'rose', 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6', 'Maior despesa do período'
                    ));
                }
                if (data.maiorReceitaCategoriaId) {
                    badgesRow.appendChild(criarBadgeDestaque(
                        'maior-receita-badge', data.maiorReceitaCategoriaNome, data.maiorReceitaValor,
                        'emerald', 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'Maior receita do período'
                    ));
                }

                const container = document.getElementById('container-categorias');
                container.innerHTML = '';

                if (data.dadosCards && data.dadosCards.length > 0) {
                    const crownReceita = `
                        <span class="cat-crown cat-crown-receita" title="Maior receita do período" aria-label="Maior receita do período">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </span>`;
                    const crownDespesa = `
                        <span class="cat-crown cat-crown-despesa" title="Maior despesa do período" aria-label="Maior despesa do período">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </span>`;

                    data.dadosCards.forEach(card => {
                        const div = document.createElement('div');
                        div.className = 'cat-item flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100/80';
                        div.dataset.name = card.name.toLowerCase();
                        div.innerHTML = `
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-3 h-3 rounded-full flex-shrink-0"
                                      style="background-color:${card.color}"></span>
                                <span class="text-sm font-semibold text-slate-700 truncate cat-name">${card.name}</span>
                                ${card.id === data.maiorReceitaCategoriaId ? crownReceita : ''}
                                ${card.id === data.maiorDespesaCategoriaId ? crownDespesa : ''}
                            </div>
                            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-100 px-2 py-1 rounded-lg flex-shrink-0">
                                ${formatarMoeda(card.total)}
                            </span>`;
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = `
                        <div class="text-center py-8 text-slate-400">
                            <p class="text-xs font-medium">Nenhuma categoria ativa.</p>
                        </div>`;
                }

                // Reaplica o filtro de texto após atualizar
                aplicarFiltroCat();
            })
            .catch(err => console.error('Erro ao atualizar dashboard:', err));
        }

        // ── Filtro de busca de categorias ────────────────────────────
        function aplicarFiltroCat() {
            const termo = (filtroCat.value || '').toLowerCase().trim();
            const items = document.querySelectorAll('#container-categorias .cat-item');
            let visiveis = 0;

            items.forEach(item => {
                const nome = item.dataset.name || '';
                const match = !termo || nome.includes(termo);
                item.style.display = match ? '' : 'none';
                if (match) visiveis++;
            });

            catEmpty.classList.toggle('hidden', visiveis > 0);
        }

        // ── Event listeners ──────────────────────────────────────────
        selectAno.addEventListener('change', gerenciarFiltros);
        selectMes.addEventListener('change', gerenciarFiltros);
        filtroCat.addEventListener('input', aplicarFiltroCat);

        // PDF em nova aba/contexto (funciona bem em navegador normal e Android).
        btnPdf.setAttribute('target', '_blank');
        btnPdf.setAttribute('rel', 'noopener noreferrer');

        // Excel: baixa via blob em vez de navegar. No PWA instalado do iOS,
        // target="_blank" não abre um contexto separado de verdade — a
        // navegação "prende" dentro da janela do app, sem barra nem botão de
        // voltar. Baixar por blob nunca navega a página, então não tem como
        // prender em lugar nenhum, em qualquer plataforma.
        btnExcel.addEventListener('click', function (e) {
            e.preventDefault();
            var url = this.href;

            fetch(url, { credentials: 'same-origin' })
                .then(function (r) {
                    if (!r.ok) throw new Error('Falha ao exportar.');
                    var disposition = r.headers.get('Content-Disposition') || '';
                    var match = disposition.match(/filename="?([^"]+)"?/);
                    var filename = match ? match[1] : 'transacoes.csv';
                    return r.blob().then(function (blob) { return { blob: blob, filename: filename }; });
                })
                .then(function (result) {
                    var blobUrl = URL.createObjectURL(result.blob);
                    var a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = result.filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(function () { URL.revokeObjectURL(blobUrl); }, 1000);
                })
                .catch(function () {
                    window.alert('Não foi possível baixar o Excel. Tente novamente.');
                });
        });
    });
})();
</script>
@endpush

@endsection
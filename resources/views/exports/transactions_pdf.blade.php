<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório de Transações — {{ $periodoLabel }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 13px;
            color: #1f2937;
            background: #fff;
            padding: 32px;
        }
        .no-print { margin-bottom: 20px; display: flex; gap: 10px; }
        .btn {
            padding: 10px 20px; border: none; border-radius: 8px;
            cursor: pointer; font-size: 13px; font-weight: 600;
        }
        .btn-primary { background: #4f46e5; color: #fff; }
        .btn-secondary { background: #f3f4f6; color: #374151; }

        .header {
            display: flex; justify-content: space-between;
            align-items: flex-start; margin-bottom: 28px;
            padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;
        }
        .header-title { font-size: 22px; font-weight: 700; color: #111827; }
        .header-sub   { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .header-right { text-align: right; font-size: 12px; color: #6b7280; }
        .header-right strong { display: block; font-size: 14px; font-weight: 700; color: #111827; }

        .summary { display: flex; gap: 16px; margin-bottom: 28px; }
        .summary-card {
            flex: 1; padding: 14px 18px; border-radius: 10px;
            background: #f9fafb; border-left: 4px solid;
        }
        .summary-card.blue   { border-color: #3b82f6; }
        .summary-card.green  { border-color: #10b981; }
        .summary-card.red    { border-color: #ef4444; }
        .summary-label {
            font-size: 11px; color: #6b7280; font-weight: 600;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .summary-value { font-size: 18px; font-weight: 700; margin-top: 4px; }
        .summary-card.blue  .summary-value { color: #1d4ed8; }
        .summary-card.green .summary-value { color: #059669; }
        .summary-card.red   .summary-value { color: #dc2626; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #4f46e5; color: #fff; }
        thead th {
            padding: 10px 14px; text-align: left;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .04em;
        }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td { padding: 9px 14px; }

        .badge {
            display: inline-block; padding: 2px 8px;
            border-radius: 999px; font-size: 11px; font-weight: 600;
        }
        .badge-income  { background: #d1fae5; color: #065f46; }
        .badge-expense { background: #fee2e2; color: #991b1b; }

        .amount-income  { color: #059669; font-weight: 600; }
        .amount-expense { color: #dc2626; font-weight: 600; }

        .footer {
            margin-top: 32px; padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px; color: #9ca3af;
            display: flex; justify-content: space-between;
        }

        @media print {
            body { padding: 16px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print ">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
    <!--button class="btn btn-secondary" onclick="window.history.back()">← Voltar</!--button-->
</div>

<div class="header">
    <div>
        <div class="header-title">Relatório de Transações</div>
        <div class="header-sub">Período: {{ $periodoLabel }} &nbsp;|&nbsp; Usuário: {{ $user->name }}</div>
    </div>
    <div class="header-right">
        <span>Gerado em</span>
        <strong>{{ now()->format('d/m/Y H:i') }}</strong>
    </div>
</div>

<div class="summary">
    <div class="summary-card blue">
        <div class="summary-label">Saldo do Período</div>
        <div class="summary-value">R$ {{ number_format($saldo, 2, ',', '.') }}</div>
    </div>
    <div class="summary-card green">
        <div class="summary-label">Total de Receitas</div>
        <div class="summary-value">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</div>
    </div>
    <div class="summary-card red">
        <div class="summary-label">Total de Despesas</div>
        <div class="summary-value">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Tipo</th>
            <th style="text-align:right">Valor</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $t)
        <tr>
            <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
            <td>{{ $t->description }}</td>
            <td>{{ $t->category ? $t->category->name : '—' }}</td>
            <td>
                @if($t->type === 'income')
                    <span class="badge badge-income">Receita</span>
                @else
                    <span class="badge badge-expense">Despesa</span>
                @endif
            </td>
            <td style="text-align:right">
                <span class="{{ $t->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                    {{ $t->type === 'expense' ? '-' : '' }}R$ {{ number_format($t->amount, 2, ',', '.') }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center; color:#9ca3af; padding:24px;">
                Nenhuma transação encontrada para o período selecionado.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>{{ config('app.name', 'Finanças') }} — Relatório gerado automaticamente</span>
    <span>{{ $transactions->count() }} {{ $transactions->count() === 1 ? 'transação' : 'transações' }} no total</span>
</div>

</body>
</html>

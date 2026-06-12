<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RecurringService;

class TransactionController extends Controller
{
    private function prepareAmount($value): float
    {
        if (!$value) return 0;
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }

    public function dashboard(Request $request)
    {
        $user      = Auth::user();
        $ano       = (int) $request->input('ano', now()->year);
        $mesFiltro = $request->input('mes');
        $anoAtual  = now()->year;

        // Dados do gráfico — 12 meses do ano selecionado
        $dadosAnuais = [];
        for ($m = 1; $m <= 12; $m++) {
            $dadosAnuais[] = (float) $user->transactions()
                ->where('type', 'expense')
                ->whereYear('date', $ano)
                ->whereMonth('date', $m)
                ->sum('amount');
        }

        // Contadores do topo — respeitam filtro de mês
        $queryReceitas = $user->transactions()->where('type', 'income')->whereYear('date', $ano);
        $queryDespesas = $user->transactions()->where('type', 'expense')->whereYear('date', $ano);

        if ($mesFiltro) {
            $queryReceitas->whereMonth('date', (int) $mesFiltro);
            $queryDespesas->whereMonth('date', (int) $mesFiltro);
        }

        $receitas = (float) $queryReceitas->sum('amount');
        $despesas = (float) $queryDespesas->sum('amount');
        $saldo    = $receitas - $despesas;

        // Categorias do dashboard
        $dadosCards  = [];
        $totalGeral  = 0;

        $categoriasDashboard = $user->categories()->where('show_on_dashboard', true)->get();
        foreach ($categoriasDashboard as $cat) {
            $queryCat = $user->transactions()
                ->where('category_id', $cat->id)
                ->whereYear('date', $ano);

            if ($mesFiltro) {
                $queryCat->whereMonth('date', (int) $mesFiltro);
            }

            $soma = (float) $queryCat->sum('amount');
            $dadosCards[] = [
                'id'    => $cat->id,
                'name'  => $cat->name,
                'color' => $cat->color,
                'total' => $soma,
            ];
            $totalGeral += $soma;
        }

        if ($request->ajax() || $request->expectsJson() || $request->has('json')) {
            return response()->json([
                'dadosAnuais' => $dadosAnuais,
                'receitas'    => $receitas,
                'despesas'    => $despesas,
                'saldo'       => $saldo,
                'dadosCards'  => $dadosCards,
                'totalGeral'  => $totalGeral,
            ]);
        }

        $anosDisponiveis = range($anoAtual - 5, $anoAtual + 1);

        return view('dashboard', compact(
            'ano', 'dadosAnuais', 'dadosCards', 'totalGeral',
            'anosDisponiveis', 'categoriasDashboard',
            'receitas', 'despesas', 'saldo'
        ));
    }

    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with('category');

        if ($request->filled('busca')) {
            $query->where('description', 'like', '%' . $request->busca . '%');
        }
        if ($request->filled('ano')) {
            $query->whereYear('date', $request->ano);
        }
        if ($request->filled('mes')) {
            $query->whereMonth('date', $request->mes);
        }
        if ($request->filled('tipo') && in_array($request->tipo, ['income', 'expense'])) {
            $query->where('type', $request->tipo);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();

        $anosDisponiveis = Auth::user()->transactions()
            ->selectRaw('YEAR(date) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($anosDisponiveis)) {
            $anosDisponiveis = [now()->year];
        }
        
        // ← NOVO: pendentes recorrentes do mês atual
        $pendentes = app(RecurringService::class)
        ->pendentes(Auth::user(), now()->year, now()->month);

        return view('transactions.index', compact('transactions', 'anosDisponiveis', 'pendentes'));
    }

    public function create(Request $request)
    {
        $categories = Auth::user()->categories()->select('id', 'name', 'color')->get();
    
        $prefill = null;
        if ($request->filled('from_template')) {
            $prefill = Auth::user()->recurringTemplates()
                ->with('category')
                ->findOrFail($request->from_template);
        }
    
        return view('transactions.create', compact('categories', 'prefill'));
    }

    public function store(Request $request)
    {
        $request->merge(['amount' => $this->prepareAmount($request->amount)]);

        $validated = $request->validate([
            'description'           => ['required', 'max:255', 'regex:/^[a-zA-Z0-9\sÀ-ÿ.,\-()\[\]\/]*$/u'],
            'amount'                => 'required|numeric|min:0.01',
            'type'                  => 'required|in:income,expense',
            'date'                  => 'required|date',
            'category_id'           => 'nullable|exists:categories,id',
            'recurring_template_id' => 'nullable|exists:recurring_templates,id',
        ], [
            'description.required' => 'A descrição é obrigatória.',
            'description.max'      => 'A descrição não pode ultrapassar 255 caracteres.',
            'description.regex'    => 'A descrição contém caracteres não permitidos.',
            'amount.required'      => 'O valor é obrigatório.',
            'amount.numeric'       => 'O valor deve ser numérico.',
            'amount.min'           => 'O valor mínimo é R$ 0,01.',
            'type.required'        => 'O tipo de transação é obrigatório.',
            'type.in'              => 'O tipo deve ser Receita ou Despesa.',
            'date.required'        => 'A data é obrigatória.',
            'date.date'            => 'Informe uma data válida.',
            'category_id.exists'   => 'Categoria inválida.',
        ]);

        if (!empty($validated['category_id'])) {
            $owns = Auth::user()->categories()->where('id', $validated['category_id'])->exists();
            if (!$owns) $validated['category_id'] = null;
        }

        if (!empty($validated['recurring_template_id'])) {
            $owns = Auth::user()->recurringTemplates()
                ->where('id', $validated['recurring_template_id'])->exists();
            if (!$owns) $validated['recurring_template_id'] = null;
        }

        $request->user()->transactions()->create($validated);

        return redirect()->route('transacoes.index')->with('success', 'Transação salva com sucesso!');
    }

    public function edit($id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);
        $categories  = Auth::user()->categories()->select('id', 'name', 'color')->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);
        $request->merge(['amount' => $this->prepareAmount($request->amount)]);

        $validated = $request->validate([
            'description' => ['required', 'max:255', 'regex:/^[a-zA-Z0-9\sÀ-ÿ.,\-()\[\]\/]*$/u'],
            'amount'      => 'required|numeric|min:0.01',
            'type'        => 'required|in:income,expense',
            'date'        => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
        ], [
            'description.required' => 'A descrição é obrigatória.',
            'description.max'      => 'A descrição não pode ultrapassar 255 caracteres.',
            'description.regex'    => 'A descrição contém caracteres não permitidos.',
            'amount.required'      => 'O valor é obrigatório.',
            'amount.numeric'       => 'O valor deve ser numérico.',
            'amount.min'           => 'O valor mínimo é R$ 0,01.',
            'type.required'        => 'O tipo de transação é obrigatório.',
            'type.in'              => 'O tipo deve ser Receita ou Despesa.',
            'date.required'        => 'A data é obrigatória.',
            'date.date'            => 'Informe uma data válida.',
            'category_id.exists'   => 'Categoria inválida.',
        ]);

        if (!empty($validated['category_id'])) {
            $owns = Auth::user()->categories()->where('id', $validated['category_id'])->exists();
            if (!$owns) $validated['category_id'] = null;
        }

        $transaction->update($validated);

        return redirect()->route('transacoes.index')->with('success', 'Transação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Auth::user()->transactions()->findOrFail($id)->delete();
        return redirect()->route('transacoes.index')->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Duplica uma transação pertencente ao usuário autenticado.
     *
     * CORREÇÃO: usava Transaction::findOrFail($id) sem import e sem verificar
     * ownership — trocado para Auth::user()->transactions()->findOrFail($id),
     * que garante que o registro pertence ao usuário e usa o model correto.
     */
    public function duplicate($id)
    {
        // Garante que a transação existe E pertence ao usuário autenticado
        $original = Auth::user()->transactions()->findOrFail($id);

        // replicate() copia todos os atributos exceto primary key e timestamps
        $copia = $original->replicate();
        $copia->description = $original->description . ' (Cópia)';

        // Mantém a data original (replicate já copia, mas deixamos explícito)
        $copia->date = $original->date;

        // Timestamps de criação/atualização são gerenciados pelo Eloquent automaticamente
        $copia->save();

        return redirect()
            ->route('transacoes.index')
            ->with('success', "Transação \"{$original->description}\" duplicada com sucesso!");
    }

    public function exportExcel(Request $request)
    {
        $user  = Auth::user();
        $ano   = $request->input('ano', now()->year);
        $mes   = $request->input('mes');
        $query = $user->transactions()->with('category')->whereYear('date', $ano);
        if ($mes) $query->whereMonth('date', $mes);
        $transactions = $query->orderBy('date', 'desc')->get();

        $bom    = "\xEF\xBB\xBF";
        $linhas = ["Data;Descrição;Tipo;Categoria;Valor (R$)"];
        foreach ($transactions as $t) {
            $tipo      = $t->type === 'income' ? 'Receita' : 'Despesa';
            $categoria = $t->category ? $t->category->name : '-';
            $valor     = number_format($t->amount, 2, ',', '.');
            $linhas[]  = "{$t->date};{$t->description};{$tipo};{$categoria};{$valor}";
        }

        $periodo = $mes ? "_{$mes}" : '';
        return response($bom . implode("\n", $linhas), 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"transacoes_{$ano}{$periodo}.csv\"",
        ]);
    }

    public function exportPdf(Request $request)
    {
        $user          = Auth::user();
        $ano           = $request->input('ano', now()->year);
        $mes           = $request->input('mes');
        $query         = $user->transactions()->with('category')->whereYear('date', $ano);
        if ($mes) $query->whereMonth('date', $mes);
        $transactions  = $query->orderBy('date', 'desc')->get();
        $meses         = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                          'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        $periodoLabel  = $mes ? "{$meses[(int)$mes]} de {$ano}" : "Ano {$ano}";
        $totalReceitas = $transactions->where('type', 'income')->sum('amount');
        $totalDespesas = $transactions->where('type', 'expense')->sum('amount');
        $saldo         = $totalReceitas - $totalDespesas;

        return view('exports.transactions_pdf',
            compact('transactions', 'periodoLabel', 'totalReceitas', 'totalDespesas', 'saldo', 'user'));
    }
}
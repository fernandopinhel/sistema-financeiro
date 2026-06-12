<?php

namespace App\Http\Controllers;

use App\Models\RecurringTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecurringTemplateController extends Controller
{
    private function prepareAmount($value): float
    {
        if (!$value) return 0;
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }

    public function index()
    {
        $templates = Auth::user()->recurringTemplates()
            ->with('category')
            ->orderBy('day_of_month')
            ->paginate(10);

        return view('recurring.index', compact('templates'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->select('id', 'name', 'color')->get();
        return view('recurring.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Converte valor formatado (1.234,56 → 1234.56) antes de validar
        $request->merge(['amount' => $this->prepareAmount($request->amount)]);

        $validated = $request->validate([
            'description'  => ['required', 'max:255'],
            'amount'       => 'required|numeric|min:0.01',
            'type'         => 'required|in:income,expense',
            'category_id'  => 'nullable|exists:categories,id',
            'day_of_month' => 'required|integer|min:1|max:31',
        ], [
            'description.required'  => 'A descrição é obrigatória.',
            'amount.required'       => 'O valor é obrigatório.',
            'amount.min'            => 'O valor mínimo é R$ 0,01.',
            'type.required'         => 'O tipo é obrigatório.',
            'day_of_month.required' => 'Informe o dia de vencimento.',
            'day_of_month.min'      => 'O dia deve ser entre 1 e 31.',
            'day_of_month.max'      => 'O dia deve ser entre 1 e 31.',
        ]);

        Auth::user()->recurringTemplates()->create($validated);

        return redirect()->route('recorrentes.index')
            ->with('success', 'Conta recorrente criada com sucesso!');
    }

    public function edit($id)
    {
        $template   = Auth::user()->recurringTemplates()->findOrFail($id);
        $categories = Auth::user()->categories()->select('id', 'name', 'color')->get();
        return view('recurring.edit', compact('template', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $template = Auth::user()->recurringTemplates()->findOrFail($id);

        $request->merge(['amount' => $this->prepareAmount($request->amount)]);

        $validated = $request->validate([
            'description'  => ['required', 'max:255'],
            'amount'       => 'required|numeric|min:0.01',
            'type'         => 'required|in:income,expense',
            'category_id'  => 'nullable|exists:categories,id',
            'day_of_month' => 'required|integer|min:1|max:31',
            'active'       => 'nullable|boolean',
        ], [
            'description.required'  => 'A descrição é obrigatória.',
            'amount.required'       => 'O valor é obrigatório.',
            'amount.min'            => 'O valor mínimo é R$ 0,01.',
            'type.required'         => 'O tipo é obrigatório.',
            'day_of_month.required' => 'Informe o dia de vencimento.',
            'day_of_month.min'      => 'O dia deve ser entre 1 e 31.',
            'day_of_month.max'      => 'O dia deve ser entre 1 e 31.',
        ]);

        // Checkbox desmarcado não envia o campo — força false quando ausente
        $validated['active'] = (bool) $request->input('active', 0);

        $template->update($validated);

        return redirect()->route('recorrentes.index')
            ->with('success', 'Conta recorrente atualizada!');
    }

    public function destroy($id)
    {
        Auth::user()->recurringTemplates()->findOrFail($id)->delete();
        return redirect()->route('recorrentes.index')
            ->with('success', 'Conta recorrente removida.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Captura o termo de pesquisa vindo do input 'search' ou 'busca'
        $search = $request->input('search') ?? $request->input('busca');

        $categories = Auth::user()->categories()
            ->withCount('transactions')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => 'required|string|max:100|regex:/^[a-zA-ZÀ-ÿ0-9\s]+$/u',
            'color'             => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'show_on_dashboard' => 'nullable|boolean',
        ]);

        Auth::user()->categories()->create([
            'name'              => strip_tags($request->name),
            'color'             => $request->color,
            'show_on_dashboard' => $request->boolean('show_on_dashboard'),
        ]);

        // Se criado a partir do form de transação, volta para lá
        if ($request->input('redirect_back') === 'transaction') {
            return redirect()->route('transacoes.create')
                ->with('success', 'Categoria criada com sucesso!');
        }

        return redirect()->route('categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_if($category->user_id !== Auth::id(), 403);

        $request->validate([
            'name'              => 'required|string|max:100',
            'color'             => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'show_on_dashboard' => 'nullable|boolean',
        ]);

        $category->update([
            'name'              => strip_tags($request->name),
            'color'             => $request->color,
            'show_on_dashboard' => $request->boolean('show_on_dashboard'),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_if($category->user_id !== Auth::id(), 403);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }

    public function duplicate(Category $category)
    {
        $newCategory = $category->replicate();
        $newCategory->name = $category->name . ' (Cópia)';
        $newCategory->save();

        return back()->with('success', 'Categoria duplicada com sucesso!');
    }
}
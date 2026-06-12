<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecurringTemplateController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Rota pública para as diretrizes de privacidade (LGPD)
Route::get('/privacidade', function () {
    return view('privacidade');
})->name('privacidade');

// Grupo de rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    Route::resource('recorrentes', RecurringTemplateController::class)
    ->names('recorrentes')
    ->except(['show']);
    
    // Dashboard
    Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');

    // Transações
    Route::get('/transacoes',                        [TransactionController::class, 'index'])->name('transacoes.index');
    Route::get('/transacoes/nova',                   [TransactionController::class, 'create'])->name('transacoes.create');
    Route::post('/transacoes',                       [TransactionController::class, 'store'])->name('transacoes.store');
    Route::get('/transacoes/{id}/alterar',           [TransactionController::class, 'edit'])->name('transacoes.edit');
    Route::put('/transacoes/{id}',                   [TransactionController::class, 'update'])->name('transacoes.update');
    Route::delete('/transacoes/{id}',                [TransactionController::class, 'destroy'])->name('transacoes.destroy');
    Route::post('/transacoes/{id}/duplicar',           [TransactionController::class, 'duplicate'])->name('transacoes.duplicate');


    // Exportações do dashboard
    Route::get('/transacoes/exportar/planilha',      [TransactionController::class, 'exportExcel'])->name('transacoes.export.excel');
    Route::get('/transacoes/exportar/relatorio',     [TransactionController::class, 'exportPdf'])->name('transacoes.export.pdf');

    // Categorias
    Route::get('/categorias',                               [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categorias',                              [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categorias/{category}',                    [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categorias/{category}',                 [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categorias/{category}/duplicar',            [CategoryController::class, 'duplicate'])->name('categories.duplicate');

    // Perfil
    Route::get('/perfil',                                [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil',                              [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil',                             [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
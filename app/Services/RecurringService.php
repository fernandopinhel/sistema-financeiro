<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class RecurringService
{
    /**
     * Retorna os templates recorrentes ativos que ainda NÃO foram
     * efetivados como transação no mês/ano indicado.
     */
    public function pendentes(User $user, int $ano, int $mes): Collection
    {
        // IDs de templates já efetivados neste mês
        $efetivadosIds = $user->transactions()
            ->whereYear('date', $ano)
            ->whereMonth('date', $mes)
            ->whereNotNull('recurring_template_id')
            ->pluck('recurring_template_id');

        // Templates ativos que NÃO estão nessa lista
        return $user->recurringTemplates()
            ->where('active', true)
            ->whereNotIn('id', $efetivadosIds)
            ->with('category')
            ->orderBy('day_of_month')
            ->get();
    }
}
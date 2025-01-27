<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\CurrencyRate;

class BudgetService
{


    public function calculateBudgetTotal(Budget $budget): string
    {
        $accountsArray = $budget->accounts->toArray();
        $total = array_reduce($accountsArray, callback: function ($sum, $item) use ($budget) {
            $currencyRate = new CurrencyRate();
            if ($item['currency'] === $budget->currency) {
                $sum += $item['amount'];
            } else {
                $amountIRubbles = $currencyRate->convertToRubbles($item['currency'], $item['amount']);
//                dd($amountIRubbles, $item);
                if ($budget->currency === 'RUB') {
                    $sum += $amountIRubbles;
                } else {
                    $sum += $currencyRate->convertToCurrency($budget->currency, $amountIRubbles);
                }
            }
            return $sum;
        }, initial: 0);
        return $total;
    }

    public function updateBudgetTotal(Budget $budget, float $total): void
    {
        $budget->balance = $total;
        $budget->save();
    }
}

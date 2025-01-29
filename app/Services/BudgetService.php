<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\CurrencyRate;

class BudgetService
{
    private CurrencyRateService $currencyRateService;

    public function __construct(CurrencyRateService $currencyRateService)
    {
        $this->currencyRateService = $currencyRateService;
    }


    public function calculateBudgetTotal(Budget $budget): string
    {
        $accountsArray = $budget->accounts->toArray();
        $total = array_reduce($accountsArray, callback: function ($sum, $item) use ($budget) {
            $currencyRate = new CurrencyRate();
            if ($item['currency'] === $budget->currency) {
                $sum += $item['amount'];
            } else {
                $amountIRubbles = $this->currencyRateService->convertToRubbles($item['currency'], $item['amount']);
//                dd($amountIRubbles, $item);
                if ($budget->currency === 'RUB') {
                    $sum += $amountIRubbles;
                } else {
                    $sum += $this->currencyRateService->convertToCurrency($budget->currency, $amountIRubbles);
                }
            }
            return $sum;
        }, initial: 0);
        return $total;
    }

    public function updateBudgetTotal(Budget $budget, float $total): void
    {
        if ($budget->balance !== $total) {
            $budget->balance = $total;
            $budget->save();
        }
    }
}

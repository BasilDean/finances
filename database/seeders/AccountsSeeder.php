<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountsSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = array(
            [
                'title' => 'Наличка (BD)',
                'amount' => 0,
                'currency'=> 'RUB',
                'type' => 'cash',
            ],
            [
                'title' => 'Счёт Т-Банк',
                'amount' => 0,
                'currency'=> 'RUB',
                'type' => 'account',
            ],
            [
                'title' => 'Счёт Credo',
                'amount' => 0,
                'currency'=> 'GEL',
                'type' => 'account',
            ],
        );
        try {
            DB::transaction(function () use ($accounts) {
                foreach ($accounts as $accountData) {
                    $account = Account::create($accountData);
                    $budget = Budget::find(1);
                    if ($budget) {
                        $budget->accounts()->save($account);
                    } else {
                        // Handle the case when the budget is not found
                        Log::warning('Budget ID 1 not found; account not associated with any budget.', ['account' => $accountData]);
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to seed accounts:', ['error' => $e->getMessage()]);
        }
    }
}

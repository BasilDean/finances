<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public static function adjustBalance(Account $account, float $amount): void
    {
        $account->amount = round($account->amount + $amount, 2);
        $account->save();
    }
}

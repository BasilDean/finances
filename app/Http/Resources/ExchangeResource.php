<?php

namespace App\Http\Resources;

use App\Models\Exchange;
use App\Services\UserSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Exchange */
class ExchangeResource extends JsonResource
{
    public static function getFields(?string $context = null): array
    {
        $users = self::getUsers();
        $accounts = self::getAccounts();
        // Default fields (shared across contexts)
        $defaultFields = [
            'amount_from' => self::makeField('number', 'amount_from', false, ['filter' => true, 'filter-type' => 'text']),
            'amount_to' => self::makeField('number', 'amount_to', false, ['filter' => true, 'filter-type' => 'text']),
            'date' => self::makeField('date', 'date', false, ['filter' => true, 'filter-type' => 'text']),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
//                'type' => self::makeField('list', 'type', false, ['values' => ['cash', 'account']]),
//                'currency' => self::makeField('list', false, 3, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
                'user' => self::makeField('relation', 'user', false, ['values' => $users, 'multiple' => false, 'showField' => 'name']),
                'account_from' => self::makeField('relation', 'account_from', false, ['values' => $accounts, 'multiple' => false, 'showField' => 'title']),
                'account_to' => self::makeField('relation', 'account_to', false, ['values' => $accounts, 'multiple' => false, 'showField' => 'title']),
            ],
            'show' => [
                'currency_from' => self::makeField('string', 'currency_from'),
                'currency_to' => self::makeField('string', 'currency_to'),
                'exchange_rate' => self::makeField('number', 'exchange_rate'),
            ],
            'admin' => [
                'oficial_rate' => self::makeField('number', 'oficial_rate'),
            ],
            default => [],
        };
        // Return merged fields sorted by the 'order' field
        return collect(array_merge($defaultFields, $contextFields))
            ->sortBy('order')
            ->toArray();
    }

    private static function getUsers(): Collection
    {
        return (new UserSettingsService())->getActiveBudget()->users;
    }

    private static function getAccounts(): Collection
    {
        return (new UserSettingsService())->getActiveBudget()->accounts;
    }

    private static function makeField(
        string $type,
        string $name,
        bool   $hideOnMobile = false,
        array  $additionalAttributes = []
    ): array
    {
        $orderMap = self::fieldOrderMap();
        if (!isset($orderMap[$name])) {
            Log::warning("Field '{$name}' does not have an explicit position in the field order map.");
        }
        return array_merge([
            'type' => $type,
            'hideOnMobile' => $hideOnMobile,
            'order' => $orderMap[$name] ?? 100, // Default to 100 for unordered fields
        ], $additionalAttributes);
    }

    private static function fieldOrderMap(): array
    {
        return [
            'amount_from' => 0,
            'account_from' => 1,
            'amount_to' => 2,
            'account_to' => 3,
            'user' => 4,
            'date' => 5,
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount_from' => $this->amount_from,
            'currency_from' => $this->currency_from,
            'amount_to' => $this->amount_to,
            'currency_to' => $this->currency_to,
            'exchange_rate' => $this->exchange_rate,
            'oficial_rate' => $this->oficial_rate,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date' => $this->date,

            'account_from' => $this->account_from,
            'account_to' => $this->account_to,
            'user_id' => $this->user_id,
            'expense_id' => $this->expense_id,
            'income_id' => $this->income_id,

            'accountFrom' => new AccountResource($this->whenLoaded('accountFrom')),
            'accountTo' => new AccountResource($this->whenLoaded('accountTo')),
            'expense' => new ExpenseResource($this->whenLoaded('expense')),
            'income' => new IncomeResource($this->whenLoaded('income')),
        ];
    }
}

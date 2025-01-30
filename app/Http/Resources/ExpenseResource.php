<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Expense;
use App\Services\UserSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Expense */
class ExpenseResource extends JsonResource
{

    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    public static function getFields(?string $context = null): array
    {
        $users = self::getUsers();
        $accounts = self::getAccounts();
        $categories = Category::orderBy('sort')->get();
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
            'amount' => self::makeField('number', 'amount', false, ['filter' => true, 'filter-type' => 'text']),
            'user' => self::makeField('relation', 'user', false, ['values' => $users, 'multiple' => false, 'showField' => 'name']),
            'account' => self::makeField('relation', 'account', false, ['values' => $accounts, 'multiple' => false, 'showField' => 'title']),
            'date' => self::makeField('date', 'date', false, ['filter' => true, 'filter-type' => 'text']),
            'source' => self::makeField('relation', 'source', false, [
                'multiple' => true,
                'showField' => 'title',
                'values' => $categories,
            ]),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
                'has_items' => self::makeField('boolean', 'has_items', false, ['filter' => true, 'filter-type' => 'text']),
            ],
            'show' => [
                'currency' => self::makeField('list', 'currency', false, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
                'amount' => self::makeField('number', 'amount', false),
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
            'title' => 0,
            'amount' => 1,
            'currency' => 2,
            'source' => 3,
            'account' => 4,
            'user' => 5,
            'date' => 6,
        ];
    }

    private static function getCurrencies(): array
    {
        return cache()->remember('currencies', now()->addDay(), fn() => config('currencies'));
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'normalized_title' => $this->normalized_title,
            'title' => $this->title,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'has_items' => $this->has_items,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
            'amount_calculated' => $this->amount_calculated,
            'date' => $this->date,
            'type' => $this->type,
            'categories_count' => $this->categories_count,
            'exchanges_count' => $this->exchanges_count,
            'items_count' => $this->items_count,

            'account_id' => $this->account_id,
            'user_id' => $this->user_id,

            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}

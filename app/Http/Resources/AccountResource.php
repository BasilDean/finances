<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Account */
class AccountResource extends JsonResource
{
    public static function getFields(): array
    {
        return [
            'title' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'amount' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'currency' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => config('currencies'),
                'filter' => true,
                'filter-type' => 'select'
            ],
            'type' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => [
                    'cash',
                    'account'
                ],
                'filter' => true,
                'filter-type' => 'select'
            ]
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'normalized_title' => $this->normalized_title,
            'budgets_count' => $this->budgets_count,
            'exchanges_from_count' => $this->exchanges_from_count,
            'exchanges_to_count' => $this->exchanges_to_count,
            'expenses_count' => $this->expenses_count,
            'incomes_count' => $this->incomes_count,
            'operations_count' => $this->operations_count,
        ];
    }

}

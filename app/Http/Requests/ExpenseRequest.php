<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'has_items' => ['required'],
            'date' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Expense::class)->or($this->user()->can('update', $this->route('expense')));
    }
}

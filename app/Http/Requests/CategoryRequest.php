<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'sort' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Category::class)->or($this->user()->can('update', $this->route('category')));
    }
}

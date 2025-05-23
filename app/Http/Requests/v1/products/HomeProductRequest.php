<?php

namespace App\Http\Requests\v1\products;

use Illuminate\Foundation\Http\FormRequest;

class HomeProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'min_price' => ['nullable', 'numeric', 'gte:0'],
            'max_price' => ['nullable', 'numeric', 'gte:0'],
            'sort' => ['nullable', 'in:latest,price_low,price_high'],
        ];
    }
}

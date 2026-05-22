<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'search' => 'nullable|string',

            'category_id' => 'nullable|exists:categories,id',

            'brand_id' => 'nullable|exists:brands,id',

            'min_price' => 'nullable|numeric|min:0',

            'max_price' => 'nullable|numeric|min:0',

            'rating' => 'nullable|numeric|min:1|max:5',

            'sort' => 'nullable|in:latest,oldest,price_low,price_high,rating'
        ];
    }
}

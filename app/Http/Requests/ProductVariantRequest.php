<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
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

            'product_id' => 'required|exists:products,id',

            'sku' => 'required|string|unique:product_variants,sku',

            'size' => 'nullable|string',

            'color' => 'nullable|string',

            'price' => 'required|numeric|min:1',

            'stock' => 'required|integer|min:0',
        ];
    }
}

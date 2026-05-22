<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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

            'code' => 'required|string|unique:coupons,code',

            'type' => 'required|in:fixed,percentage',

            'value' => 'required|numeric|min:1',

            'minimum_amount' => 'nullable|numeric|min:0',

            'usage_limit' => 'nullable|integer|min:1',

            'expires_at' => 'nullable|date',

            'status' => 'required|boolean'
        ];
    }
}

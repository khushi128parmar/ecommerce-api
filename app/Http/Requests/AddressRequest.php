<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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

            'full_name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',

            'address_line_1' => 'required|string',

            'address_line_2' => 'nullable|string',

            'city' => 'required|string',

            'state' => 'required|string',

            'country' => 'required|string',

            'postal_code' => 'required|string',

            'is_default' => 'required|boolean',
        ];
    }
}

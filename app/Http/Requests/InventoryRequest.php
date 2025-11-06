<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
            'quantity' => ['required', 'gt:-1'],
            'expiration_date' => ['sometimes', 'nullable', 'after:yesterday'],
            'catalog_id' => ['required', 'gt:0'],
            'catalog_description' => ['required'],
            'category_id' => ['required'],
            'category_name' => ['required'],
            'purchase_date' => [],
            'brand_id' => [],
            'brand_name' => [],
            'uom_id' => ['required', 'gt:0'],
            'uom_abbreviation' => [],
            'house_id' => ['gt:0'],
            'house_description' => [],
            'merged_id' => ['gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'expiration_date.after' => 'The expiration date can not be a past date.',
            'quantity.required' => 'Quantity is mandatory.',
            'quantity.lte' => 'Quantity must be greater than ZERO',
        ];
    }
}

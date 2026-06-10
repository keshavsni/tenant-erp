<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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

            'warehouse_id' => [
                'required',
                Rule::exists('warehouses', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'company_id',
                            auth()->user()->company_id
                        );
                    }),
            ],

            'products' => [
                'required',
                'array',
                'min:1'
            ],

            'products.*.product_id' => [
                'required',
                'exists:products,id'
            ],

            'products.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' =>
            'At least one product is required.',

            'products.*.quantity.min' =>
            'Quantity must be greater than zero.'
        ];
    }
}

<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'              => 'required|string|max:255|unique:products,sku',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'quantity'         => 'required|integer|min:0',
            'total'            => 'nullable|numeric|min:0',
            'manufacture_date' => 'nullable|date',
            'expiry_date'      => 'nullable|date|after_or_equal:manufacture_date',
            'category_id'      => 'required|exists:categories,id',
        ];
    }
}
